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

        // Calculate additional statistics
        $totalSeats = $ticketTypes->sum('total_seats');
        $soldSeats = $ticketTypes->sum('sold_seats');
        $availableSeats = $ticketTypes->sum('available_seats');
        $comboTicketTypes = $ticketTypes->where('is_combo', true)->count();
        $soldOutTicketTypes = $ticketTypes->where('status', 'sold_out')->count();

        return view('admin.tickets.index', compact('ticketTypes', 'statuses', 'events', 'totalSeats', 'soldSeats', 'availableSeats', 'comboTicketTypes', 'soldOutTicketTypes'));
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

        return view('admin.tickets.show', compact('ticket'));
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
        ]);

        return redirect()->route('admin.tickets.index')
                        ->with('success', 'Ticket type deleted successfully!');
    }

}
