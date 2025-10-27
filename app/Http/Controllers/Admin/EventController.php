<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of events
     */
    public function index(Request $request)
    {
        $query = Event::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range (check start_date)
        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('start_date', '<=', $request->date_to . ' 23:59:59');
        }

        $perPage = $request->get('limit', 10);
        $perPage = in_array($perPage, [10, 15, 25, 50, 100]) ? $perPage : 10;
        $events = $query->withCount('purchaseTickets')->latest()->paginate($perPage);
        $statuses = Event::select('status')->distinct()->pluck('status');

        return view('admin.events.index', compact('events', 'statuses'));
    }

    /**
     * Show the form for creating a new event
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created event
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_time' => 'required|date|after:now',
            'start_date' => 'nullable|date|after_or_equal:date_time',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'venue' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'max_tickets_per_order' => 'required|integer|min:1|max:20',
            'total_seats' => 'required|integer|min:1',
            'status' => 'required|in:draft,on_sale,sold_out,cancelled',
            'combo_discount_percentage' => 'nullable|numeric|min:0|max:100',
            'combo_discount_enabled' => 'nullable|boolean',
            'default' => 'nullable|boolean',
        ]);

        // Prepare data for creation
        $data = $request->all();
        
        // Handle combo discount checkbox
        $data['combo_discount_enabled'] = $request->has('combo_discount_enabled');
        
        // Handle default checkbox
        $data['default'] = $request->has('default');
        
        // If setting as default, unset any existing default event
        if ($data['default']) {
            $existingDefault = Event::where('default', true)->first();
            if ($existingDefault) {
                $existingDefault->update(['default' => false]);
            }
        }
        
        // Set default combo discount percentage if not provided
        if (!isset($data['combo_discount_percentage'])) {
            $data['combo_discount_percentage'] = 10.00;
        }

        $event = Event::create($data);

        // Log the event creation
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE',
            'table_name' => 'events',
            'record_id' => $event->id,
            'old_values' => null,
            'new_values' => $event->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => "Event created: {$event->name} at {$event->venue} on {$event->date_time}"
        ]);

        $message = 'Event created successfully!';
        if ($event->default) {
            $message .= ' This event has been set as the default event.';
        }

        return redirect()->route('admin.events.show', $event)
            ->with('success', $message);
    }

    /**
     * Display the specified event
     */
    public function show(Event $event)
    {
        $event->loadCount('purchaseTickets');
        $event->load('ticketTypes');
        $recentTickets = $event->purchaseTickets()->with('order.user')->latest()->take(10)->get();
        
        $ticketStats = [
            'total_capacity' => $event->total_seats,
            'tickets_sold' => $event->purchaseTickets()->where('status', 'Sold')->count(),
            'tickets_held' => $event->purchaseTickets()->where('status', 'Held')->count(),
            'tickets_available' => $event->total_seats - $event->purchaseTickets()->whereIn('status', ['Sold', 'Held'])->count(),
        ];
        
        $ticketStats['sold_percentage'] = $ticketStats['total_capacity'] > 0 
            ? round(($ticketStats['tickets_sold'] / $ticketStats['total_capacity']) * 100, 2) 
            : 0;

        return view('admin.events.show', compact('event', 'recentTickets', 'ticketStats'));
    }

    /**
     * Show the form for editing the specified event
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified event
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_time' => 'required|date|after:now',
            'start_date' => 'nullable|date|after_or_equal:date_time',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'venue' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'max_tickets_per_order' => 'required|integer|min:1|max:20',
            'total_seats' => 'required|integer|min:1',
            'status' => 'required|in:draft,on_sale,sold_out,cancelled',
            'combo_discount_percentage' => 'nullable|numeric|min:0|max:100',
            'combo_discount_enabled' => 'nullable|boolean',
            'default' => 'nullable|boolean',
        ]);

        $oldValues = $event->toArray();

        // Prepare data for update
        $data = $request->all();
        
        // Handle combo discount checkbox
        $data['combo_discount_enabled'] = $request->has('combo_discount_enabled');
        
        // Handle default checkbox
        $data['default'] = $request->has('default');
        
        // If setting as default, unset any existing default event
        if ($data['default']) {
            $existingDefault = Event::where('default', true)->where('id', '!=', $event->id)->first();
            if ($existingDefault) {
                $existingDefault->update(['default' => false]);
            }
        }
        
        // Set default combo discount percentage if not provided
        if (!isset($data['combo_discount_percentage'])) {
            $data['combo_discount_percentage'] = 10.00;
        }

        $event->update($data);

        // Log the event update
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'events',
            'record_id' => $event->id,
            'old_values' => $oldValues,
            'new_values' => $event->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => "Event updated: {$event->name}"
        ]);

        $message = 'Event updated successfully!';
        if ($event->default) {
            $message .= ' This event has been set as the default event.';
        }

        return redirect()->route('admin.events.show', $event)
            ->with('success', $message);
    }

    /**
     * Remove the specified event
     */
    public function destroy(Event $event)
    {
        // Check if event has tickets
        if ($event->tickets()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete event with existing tickets.']);
        }

        $oldValues = $event->toArray();
        $eventName = $event->name;

        $event->delete();

        // Log the event deletion
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE',
            'table_name' => 'events',
            'record_id' => $event->id,
            'old_values' => $oldValues,
            'new_values' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => "Event deleted: {$event->name}"
        ]);

        return redirect()->route('admin.events.index')
            ->with('success', "Event '{$eventName}' deleted successfully!");
    }

    /**
     * Change event status
     */
    public function changeStatus(Request $request, Event $event)
    {
        $request->validate([
            'status' => 'required|in:draft,on sale,sold out,cancelled'
        ]);

        $oldValues = $event->toArray();
        $event->update(['status' => $request->status]);

        // Log the status change
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'events',
            'record_id' => $event->id,
            'old_values' => $oldValues,
            'new_values' => $event->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => "Event status changed: {$event->name} to {$event->status}"
        ]);

        return back()->with('success', 'Event status updated successfully.');
    }

    /**
     * Get ticket types for a specific event
     */
    public function getTicketTypes(Event $event)
    {
        $ticketTypes = $event->ticketTypes()
            ->whereIn('status', ['active', 'Active'])
            ->select('id', 'event_id', 'name', 'price', 'available_seats', 'total_seats', 'sold_seats')
            ->get();

        // For multi-day events, calculate day-specific availability
        if ($event->isMultiDay()) {
            $eventDays = $event->getEventDays();
            $ticketTypes = $ticketTypes->map(function($ticket) use ($eventDays) {
                $dayAvailability = [];
                
                foreach ($eventDays as $day) {
                    // Count sold and pending tickets for this specific day and ticket type
                    $soldForDay = \App\Models\PurchaseTicket::where('event_id', $ticket->event_id)
                        ->where('ticket_type_id', $ticket->id)
                        ->whereDate('event_day', $day['date'])
                        ->whereIn('status', ['sold', 'pending'])
                        ->count();
                    
                    $availableForDay = $ticket->total_seats - $soldForDay;
                    
                    $dayAvailability[] = [
                        'day_name' => $day['day_name'],
                        'date' => $day['date'],
                        'available' => max(0, $availableForDay),
                        'sold' => $soldForDay
                    ];
                }
                
                $ticket->day_availability = $dayAvailability;
                return $ticket;
            });
        }

        return response()->json([
            'ticketTypes' => $ticketTypes,
            'event' => [
                'id' => $event->id,
                'name' => $event->name,
                'is_multi_day' => $event->isMultiDay(),
                'combo_discount_enabled' => $event->combo_discount_enabled,
                'combo_discount_percentage' => $event->combo_discount_percentage,
                'event_days' => $event->getEventDays()
            ]
        ]);
    }
}
