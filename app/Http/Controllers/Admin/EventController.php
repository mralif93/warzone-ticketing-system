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

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('date_time', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date_time', '<=', $request->date_to . ' 23:59:59');
        }

        $events = $query->withCount('tickets')->latest()->paginate(15);
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
            'venue' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'max_tickets_per_order' => 'required|integer|min:1|max:20',
            'status' => 'required|in:Draft,On Sale,Sold Out,Cancelled',
        ]);

        $event = Event::create($request->all());

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
        ]);

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified event
     */
    public function show(Event $event)
    {
        $event->loadCount('tickets');
        $recentTickets = $event->tickets()->with('order.user', 'seat')->latest()->take(10)->get();
        
        $ticketStats = [
            'total_capacity' => 7000,
            'tickets_sold' => $event->tickets()->where('status', 'Sold')->count(),
            'tickets_held' => $event->tickets()->where('status', 'Held')->count(),
            'tickets_available' => 7000 - $event->tickets()->whereIn('status', ['Sold', 'Held'])->count(),
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
            'venue' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'max_tickets_per_order' => 'required|integer|min:1|max:20',
            'status' => 'required|in:Draft,On Sale,Sold Out,Cancelled',
        ]);

        $oldValues = $event->toArray();

        $event->update($request->all());

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
        ]);

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Event updated successfully!');
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
            'status' => 'required|in:Draft,On Sale,Sold Out,Cancelled'
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
        ]);

        return back()->with('success', 'Event status updated successfully.');
    }
}
