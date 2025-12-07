<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\PurchaseTicket;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Display a listing of ticket types (zones)
     */
    public function index(Request $request)
    {
        $query = Ticket::with('event');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('event', function($eventQuery) use ($search) {
                      $eventQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        // No default filter - show all tickets when no status filter is applied

        // Filter by zone name
        if ($request->filled('zone')) {
            $query->where('name', 'like', "%{$request->zone}%");
        }

        // Filter by event
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $perPage = $request->get('limit', 10);
        $perPage = in_array($perPage, [10, 15, 25, 50, 100]) ? $perPage : 10;
        $ticketTypes = $query->latest()->paginate($perPage);
        $statuses = Ticket::select('status')->distinct()->pluck('status');
        $events = \App\Models\Event::select('id', 'name')->get();
        
        // Calculate Day 1 and Day 2 availability for each ticket type
        $eventIds = $ticketTypes->pluck('event_id')->unique();
        $multiDayEvents = \App\Models\Event::whereIn('id', $eventIds)
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->get()
            ->keyBy('id')
            ->map(function($event) {
                return $event->isMultiDay() ? $event->getEventDays() : null;
            })
            ->filter();
        
        // Add Day 1 and Day 2 availability to each ticket type
        $ticketTypes->getCollection()->transform(function($ticketType) use ($multiDayEvents) {
            if (isset($multiDayEvents[$ticketType->event_id]) && count($multiDayEvents[$ticketType->event_id]) >= 2) {
                $eventDays = $multiDayEvents[$ticketType->event_id];
                $day1Name = $eventDays[0]['day_name'];
                $day2Name = $eventDays[1]['day_name'];
                
                // Count sold tickets per day
                $day1Sold = \App\Models\PurchaseTicket::where('ticket_type_id', $ticketType->id)
                    ->where('event_day_name', $day1Name)
                    ->whereIn('status', ['sold', 'active', 'scanned'])
                    ->count();
                    
                $day2Sold = \App\Models\PurchaseTicket::where('ticket_type_id', $ticketType->id)
                    ->where('event_day_name', $day2Name)
                    ->whereIn('status', ['sold', 'active', 'scanned'])
                    ->count();
                
                // Calculate available per day
                $ticketType->day1_available = max(0, $ticketType->total_seats - $day1Sold);
                $ticketType->day2_available = max(0, $ticketType->total_seats - $day2Sold);
                $ticketType->day1_sold = $day1Sold;
                $ticketType->day2_sold = $day2Sold;
                $ticketType->is_multi_day = true;
            } else {
                $ticketType->day1_available = null;
                $ticketType->day2_available = null;
                $ticketType->day1_sold = null;
                $ticketType->day2_sold = null;
                $ticketType->is_multi_day = false;
            }
            return $ticketType;
        });

        // Calculate additional statistics from ALL tickets (not paginated)
        $allTicketsQuery = Ticket::query();
        
        // Apply the same filters as the main query
        if ($request->filled('search')) {
            $search = $request->search;
            $allTicketsQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('event', function($eventQuery) use ($search) {
                      $eventQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        if ($request->filled('status')) {
            $allTicketsQuery->where('status', $request->status);
        }
        if ($request->filled('zone')) {
            $allTicketsQuery->where('name', 'like', "%{$request->zone}%");
        }
        if ($request->filled('event_id')) {
            $allTicketsQuery->where('event_id', $request->event_id);
        }
        if ($request->filled('date_from')) {
            $allTicketsQuery->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $allTicketsQuery->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }
        
        $allTickets = $allTicketsQuery->get();
        $ticketIds = $allTickets->pluck('id')->toArray();
        
        // Update cached values for all tickets to match actual PurchaseTickets data
        foreach ($allTickets as $ticket) {
            $ticket->updateSoldSeats();
        }
        // Reload tickets to get updated values
        $allTickets = $allTicketsQuery->get();
        
        // Calculate statistics directly from PurchaseTickets for accuracy
        // For combo tickets: 4 combo tickets = 8 PurchaseTicket records (4 Day 1 + 4 Day 2)
        // total_seats for combo = combo ticket count, need to multiply by 2 for PurchaseTicket capacity
        $purchaseTicketsQuery = \App\Models\PurchaseTicket::whereIn('ticket_type_id', $ticketIds);
        
        // Calculate total seats: for combo tickets, multiply by 2 since each combo = 2 PurchaseTicket records
        $totalSeats = 0;
        foreach ($allTickets as $ticket) {
            if ($ticket->is_combo) {
                $totalSeats += $ticket->total_seats * 2; // Each combo ticket = 2 PurchaseTicket slots
            } else {
                $totalSeats += $ticket->total_seats; // Single-day tickets count as-is
            }
        }
        
        // Count sold seats - count PurchaseTicket records directly (as per system design)
        $soldSeats = $purchaseTicketsQuery->whereIn('status', ['sold', 'active', 'scanned'])->count();
        $scannedSeats = $purchaseTicketsQuery->where('status', 'scanned')->count();
        $availableSeats = $totalSeats - $soldSeats;
        $comboTicketTypes = $allTickets->where('is_combo', true)->count();
        $soldOutTicketTypes = $allTickets->where('status', 'sold_out')->count();
        
        // Calculate Day 1 and Day 2 totals for multi-day events
        $day1TotalAvailable = 0;
        $day2TotalAvailable = 0;
        $day1TotalSold = 0;
        $day2TotalSold = 0;
        $hasMultiDayEvent = false;
        
        // Check if any filtered event is multi-day
        $eventIds = $allTickets->pluck('event_id')->unique();
        $multiDayEvents = \App\Models\Event::whereIn('id', $eventIds)
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->get()
            ->filter(function($event) {
                return $event->isMultiDay();
            });
        
        if ($multiDayEvents->count() > 0) {
            $hasMultiDayEvent = true;
            // Get the first multi-day event's days (assuming all are 2-day events)
            $firstMultiDayEvent = $multiDayEvents->first();
            $eventDays = $firstMultiDayEvent->getEventDays();
            
            if (count($eventDays) >= 2) {
                $day1Name = $eventDays[0]['day_name'];
                $day2Name = $eventDays[1]['day_name'];
                
                foreach ($allTickets as $ticket) {
                    // Count sold tickets per day
                    $day1Sold = \App\Models\PurchaseTicket::where('ticket_type_id', $ticket->id)
                        ->where('event_day_name', $day1Name)
                        ->whereIn('status', ['sold', 'active', 'scanned'])
                        ->count();
                        
                    $day2Sold = \App\Models\PurchaseTicket::where('ticket_type_id', $ticket->id)
                        ->where('event_day_name', $day2Name)
                        ->whereIn('status', ['sold', 'active', 'scanned'])
                        ->count();
                    
                    // Calculate available per day
                    $day1Available = $ticket->total_seats - $day1Sold;
                    $day2Available = $ticket->total_seats - $day2Sold;
                    
                    $day1TotalAvailable += max(0, $day1Available);
                    $day2TotalAvailable += max(0, $day2Available);
                    $day1TotalSold += $day1Sold;
                    $day2TotalSold += $day2Sold;
                }
            }
        }
        
        // Calculate revenue from actual purchased tickets (only active and scanned tickets that were paid)
        // Use the model's relationship instead of join to avoid table name issues
        $totalRevenue = \App\Models\PurchaseTicket::whereIn('ticket_type_id', $ticketIds)
            ->whereIn('status', ['sold', 'active', 'scanned'])
            ->whereHas('order', function($query) {
                $query->where('status', 'paid');
            })
            ->sum('price_paid');

        return view('admin.tickets.index', compact('ticketTypes', 'statuses', 'events', 'totalSeats', 'soldSeats', 'availableSeats', 'scannedSeats', 'comboTicketTypes', 'soldOutTicketTypes', 'totalRevenue', 'day1TotalAvailable', 'day2TotalAvailable', 'day1TotalSold', 'day2TotalSold', 'hasMultiDayEvent'));
    }

    /**
     * Show the form for creating a new ticket type
     */
    public function create()
    {
        $events = \App\Models\Event::select('id', 'name', 'date_time', 'start_date', 'end_date', 'status')
                                  ->orderBy('date_time', 'asc')
                                  ->get()
                                  ->map(function($event) {
                                      $event->is_multi_day = $event->isMultiDay();
                                      $event->duration_days = $event->getDurationInDays();
                                      return $event;
                                  });

        return view('admin.tickets.create', compact('events'));
    }

    /**
     * Store a newly created ticket type
     */
    public function store(Request $request)
    {
        $rules = [
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'total_seats' => 'required|integer|min:1|max:10000',
            'status' => 'required|in:active,inactive,sold_out',
            'description' => 'nullable|string|max:1000',
            'is_combo' => 'nullable|boolean',
        ];

        // Only validate seating_image if a file is actually uploaded
        if ($request->hasFile('seating_image')) {
            $rules['seating_image'] = 'image|mimes:jpeg,png,jpg,gif|max:10240';
        }

        $request->validate($rules);

        $event = \App\Models\Event::findOrFail($request->event_id);

        // Check if ticket type already exists for this event
        $existingTicketType = Ticket::where('event_id', $request->event_id)
                                 ->where('name', $request->name)
                                 ->first();

        if ($existingTicketType) {
            return redirect()->back()
                           ->withErrors(['name' => 'A ticket type with this name already exists for this event.'])
                           ->withInput();
        }

        // Handle seating image upload
        $seatingImagePath = null;
        if ($request->hasFile('seating_image')) {
            $seatingImagePath = $request->file('seating_image')->store('seating-images', 'public');
        }

        // Prepare data for creation
        $data = [
            'event_id' => $request->event_id,
            'name' => $request->name,
            'price' => $request->price,
            'total_seats' => $request->total_seats,
            'available_seats' => $request->total_seats,
            'sold_seats' => 0,
            'scanned_seats' => 0,
            'status' => $request->status, // Admin-selected status
            'description' => $request->description,
            'seating_image' => $seatingImagePath,
            'is_combo' => $request->has('is_combo'),
        ];

        // Create ticket type record
        $ticketType = Ticket::create($data);

        // Log ticket type creation
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE',
            'table_name' => 'tickets',
            'record_id' => $ticketType->id,
            'old_values' => null,
            'new_values' => $ticketType->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => "Ticket type created: {$ticketType->name} - RM{$ticketType->price}"
        ]);

        $comboText = $request->has('is_combo') ? ' (Combo)' : '';
        return redirect()->route('admin.tickets.index')
                        ->with('success', "Successfully created ticket type '{$request->name}'{$comboText} with {$request->total_seats} seats for {$event->name}!");
    }

    /**
     * Display the specified ticket type
     */
    public function show(Ticket $ticket)
    {
        $ticket->load('event');
        
        // Calculate Day 1 and Day 2 availability for multi-day events
        $day1Available = null;
        $day2Available = null;
        $day1Sold = null;
        $day2Sold = null;
        $day1Scanned = null;
        $day2Scanned = null;
        $totalCapacity = $ticket->total_seats;
        
        if ($ticket->event->isMultiDay()) {
            $eventDays = $ticket->event->getEventDays();
            
            if (count($eventDays) >= 2) {
                $day1Name = $eventDays[0]['day_name'];
                $day2Name = $eventDays[1]['day_name'];
                
                // Count tickets per day
                $day1Sold = \App\Models\PurchaseTicket::where('ticket_type_id', $ticket->id)
                    ->where('event_day_name', $day1Name)
                    ->whereIn('status', ['sold', 'active', 'scanned'])
                    ->count();
                    
                $day2Sold = \App\Models\PurchaseTicket::where('ticket_type_id', $ticket->id)
                    ->where('event_day_name', $day2Name)
                    ->whereIn('status', ['sold', 'active', 'scanned'])
                    ->count();
                
                $day1Scanned = \App\Models\PurchaseTicket::where('ticket_type_id', $ticket->id)
                    ->where('event_day_name', $day1Name)
                    ->where('status', 'scanned')
                    ->count();
                    
                $day2Scanned = \App\Models\PurchaseTicket::where('ticket_type_id', $ticket->id)
                    ->where('event_day_name', $day2Name)
                    ->where('status', 'scanned')
                    ->count();
                
                // Calculate available per day
                $day1Available = max(0, $ticket->total_seats - $day1Sold);
                $day2Available = max(0, $ticket->total_seats - $day2Sold);
                
                // For multi-day events, total capacity is combined for 2 days
                // If combo ticket: total_seats represents combo capacity, multiply by 2 for PurchaseTicket capacity
                if ($ticket->is_combo) {
                    $totalCapacity = $ticket->total_seats * 2; // Each combo = 2 PurchaseTicket slots
                } else {
                    $totalCapacity = $ticket->total_seats * 2; // 2 days worth of capacity
                }
            }
        }

        return view('admin.tickets.show', compact('ticket', 'day1Available', 'day2Available', 'day1Sold', 'day2Sold', 'day1Scanned', 'day2Scanned', 'totalCapacity'));
    }

    /**
     * Show the form for editing the specified ticket type
     */
    public function edit(Ticket $ticket)
    {
        $events = \App\Models\Event::select('id', 'name', 'date_time', 'status')
            ->orderBy('date_time', 'asc')
            ->get();

        return view('admin.tickets.edit', compact('ticket', 'events'));
    }

    /**
     * Update the specified ticket type
     */
    public function update(Request $request, Ticket $ticket)
    {
        $rules = [
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'total_seats' => 'required|integer|min:1|max:10000',
            'status' => 'required|in:active,inactive,sold_out',
            'description' => 'nullable|string|max:1000',
            'is_combo' => 'nullable|boolean'
        ];

        // Only validate seating_image if a file is actually uploaded
        if ($request->hasFile('seating_image')) {
            $rules['seating_image'] = 'image|mimes:jpeg,png,jpg,gif|max:10240';
        }

        $request->validate($rules);

        $oldValues = $ticket->toArray();
        
        // Handle seating image upload
        $seatingImagePath = $ticket->seating_image; // Keep existing image by default
        if ($request->hasFile('seating_image')) {
            // Delete old image if exists
            if ($ticket->seating_image && Storage::disk('public')->exists($ticket->seating_image)) {
                Storage::disk('public')->delete($ticket->seating_image);
            }
            // Store new image
            $seatingImagePath = $request->file('seating_image')->store('seating-images', 'public');
        }
        
        // Calculate new available seats
        $newAvailableSeats = $request->total_seats - $ticket->sold_seats;
        
        $ticket->update([
            'event_id' => $request->event_id,
            'name' => $request->name,
            'price' => $request->price,
            'total_seats' => $request->total_seats,
            'available_seats' => max(0, $newAvailableSeats),
            'description' => $request->description,
            'seating_image' => $seatingImagePath,
            'is_combo' => $request->has('is_combo'),
            'status' => $request->status, // Admin-selected status
        ]);

        // Log the ticket type update
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'tickets',
            'record_id' => $ticket->id,
            'old_values' => $oldValues,
            'new_values' => $ticket->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => "Ticket type updated: {$ticket->name}"
        ]);

        $comboText = $request->has('is_combo') ? ' (Combo)' : '';
        return redirect()->route('admin.tickets.index')
                        ->with('success', "Successfully updated ticket type '{$request->name}'{$comboText}!");
    }

    /**
     * Remove the specified ticket type
     */
    public function destroy(Ticket $ticket)
    {
        if ($ticket->sold_seats > 0) {
            return back()->withErrors(['error' => 'Cannot delete a ticket type that has sold tickets.']);
        }

        $oldValues = $ticket->toArray();
        $ticket->delete();

        // Log the ticket type deletion
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE',
            'table_name' => 'tickets',
            'record_id' => $ticket->id,
            'old_values' => $oldValues,
            'new_values' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => "Ticket type deleted: {$ticket->name}"
        ]);

        return redirect()->route('admin.tickets.index')
                        ->with('success', 'Ticket type deleted successfully!');
    }

    /**
     * Display a listing of trashed tickets
     */
    public function trashed(Request $request)
    {
        $query = Ticket::onlyTrashed()->with(['event']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('event', function ($eventQuery) use ($search) {
                      $eventQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by event
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $perPage = $request->get('limit', 10);
        $perPage = in_array($perPage, [10, 15, 25, 50, 100]) ? $perPage : 10;
        $tickets = $query->orderBy('deleted_at', 'desc')->paginate($perPage);

        // Statistics
        $totalTrashed = Ticket::onlyTrashed()->count();
        $recentlyDeleted = Ticket::onlyTrashed()->where('deleted_at', '>=', now()->subDays(7))->count();

        // Get events for filter dropdown
        $events = \App\Models\Event::select('id', 'name', 'date_time')->orderBy('date_time', 'desc')->get();

        return view('admin.tickets.trashed', compact('tickets', 'totalTrashed', 'recentlyDeleted', 'events'));
    }

    /**
     * Restore a trashed ticket
     */
    public function restore(Ticket $ticket)
    {
        $ticket->restore();

        return redirect()->route('admin.tickets.trashed')
                        ->with('success', 'Ticket type restored successfully.');
    }

    /**
     * Permanently delete a trashed ticket
     */
    public function forceDelete(Ticket $ticket)
    {
        $ticketName = $ticket->name;
        $ticket->forceDelete();

        return redirect()->route('admin.tickets.trashed')
                        ->with('success', "Ticket type '{$ticketName}' permanently deleted.");
    }

    /**
     * Cancel pending oversold orders
     */
    public function cancelPendingOversold(Request $request)
    {
        $orderIds = explode(',', $request->input('order_ids', ''));
        $orderIds = array_filter(array_map('intval', $orderIds));

        if (empty($orderIds)) {
            return back()->withErrors(['error' => 'No order IDs provided.']);
        }

        $cancelledCount = 0;
        $errors = [];

        foreach ($orderIds as $orderId) {
            try {
                $order = \App\Models\Order::find($orderId);

                if (!$order) {
                    $errors[] = "Order ID {$orderId} not found.";
                    continue;
                }

                if ($order->status !== 'pending') {
                    $errors[] = "Order {$order->order_number} is not pending (status: {$order->status}).";
                    continue;
                }

                $oldValues = $order->toArray();

                // Update order status to cancelled
                $order->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Cancelled due to overselling - order placed after capacity reached'
                ]);

                // Cancel all purchase tickets in this order
                $order->purchaseTickets()->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Cancelled due to overselling - order placed after capacity reached'
                ]);

                // Update Payment status to cancelled if exists
                $order->payments()->where('status', '!=', 'refunded')->update(['status' => 'cancelled']);

                // Log the cancellation
                AuditLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'CANCEL_OVERSOLD',
                    'table_name' => 'orders',
                    'record_id' => $order->id,
                    'old_values' => $oldValues,
                    'new_values' => $order->fresh()->toArray(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $cancelledCount++;

            } catch (\Exception $e) {
                $errors[] = "Error cancelling order ID {$orderId}: " . $e->getMessage();
            }
        }

        $message = "Successfully cancelled {$cancelledCount} pending oversold order(s).";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', $errors);
        }

        return back()->with('success', $message);
    }

}
