<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of events
     */
    public function index()
    {
        $events = Event::withCount('customerTickets')
            ->orderBy('date_time', 'asc')
            ->paginate(10);

        return view('public.events', compact('events'));
    }

    /**
     * Show the form for creating a new event
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created event
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_time' => 'required|date|after:now',
            'description' => 'nullable|string',
            'venue' => 'nullable|string|max:255',
            'max_tickets_per_order' => 'required|integer|min:1|max:20',
            'total_seats' => 'required|integer|min:1|max:100000',
        ]);

        DB::beginTransaction();
        try {
            $event = Event::create([
                'name' => $request->name,
                'date_time' => $request->date_time,
                'description' => $request->description,
                'venue' => $request->venue ?? 'Warzone Arena',
                'max_tickets_per_order' => $request->max_tickets_per_order,
                'total_seats' => $request->total_seats,
                'status' => 'draft',
            ]);

            // Log the event creation
            AuditLog::createLog(
                Auth::id(),
                'CREATE',
                'events',
                $event->id,
                null,
                $event->toArray(),
                "Event '{$event->name}' created"
            );

            DB::commit();

            return redirect()->route('public.events')
                ->with('success', 'Event created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create event. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Display the specified event
     */
    public function show(Event $event)
    {
        $event->loadCount('tickets');
        
        // Get ticket statistics
        $ticketStats = [
            'total_capacity' => $event->getTotalCapacity(),
            'tickets_sold' => $event->getTicketsSoldCount(),
            'tickets_available' => $event->getRemainingTicketsCount(),
            'sold_percentage' => $event->getTotalCapacity() > 0 
                ? round(($event->getTicketsSoldCount() / $event->getTotalCapacity()) * 100, 2) 
                : 0,
        ];

        // Get recent tickets
        $recentTickets = $event->tickets()
            ->with(['order.user'])
            ->latest()
            ->limit(10)
            ->get();

        return view('events.show', compact('event', 'ticketStats', 'recentTickets'));
    }

    /**
     * Show the form for editing the event
     */
    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified event
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_time' => 'required|date|after:now',
            'description' => 'nullable|string',
            'venue' => 'nullable|string|max:255',
            'max_tickets_per_order' => 'required|integer|min:1|max:20',
            'total_seats' => 'required|integer|min:1|max:100000',
            'status' => 'required|in:draft,on_sale,sold_out,cancelled',
        ]);

        $oldValues = $event->toArray();

        DB::beginTransaction();
        try {
            $event->update([
                'name' => $request->name,
                'date_time' => $request->date_time,
                'description' => $request->description,
                'venue' => $request->venue ?? 'Warzone Arena',
                'max_tickets_per_order' => $request->max_tickets_per_order,
                'total_seats' => $request->total_seats,
                'status' => $request->status,
            ]);

            // Log the event update
            AuditLog::createLog(
                Auth::id(),
                'UPDATE',
                'events',
                $event->id,
                $oldValues,
                $event->toArray(),
                "Event '{$event->name}' updated"
            );

            DB::commit();

            return redirect()->route('events.show', $event)
                ->with('success', 'Event updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update event. Please try again.'])
                ->withInput();
        }
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

        DB::beginTransaction();
        try {
            $oldValues = $event->toArray();
            $eventName = $event->name;
            
            $event->delete();

            // Log the event deletion
            AuditLog::createLog(
                Auth::id(),
                'DELETE',
                'events',
                $event->id,
                $oldValues,
                null,
                "Event '{$eventName}' deleted"
            );

            DB::commit();

            return redirect()->route('public.events')
                ->with('success', 'Event deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete event. Please try again.']);
        }
    }

    /**
     * Change event status
     */
    public function changeStatus(Request $request, Event $event)
    {
        $request->validate([
            'status' => 'required|in:draft,on_sale,sold_out,cancelled',
        ]);

        $oldStatus = $event->status;
        $newStatus = $request->status;

        DB::beginTransaction();
        try {
            $event->update(['status' => $newStatus]);

            // Log the status change
            AuditLog::createLog(
                Auth::id(),
                'STATUS_CHANGE',
                'events',
                $event->id,
                ['status' => $oldStatus],
                ['status' => $newStatus],
                "Event '{$event->name}' status changed from '{$oldStatus}' to '{$newStatus}'"
            );

            DB::commit();

            return back()->with('success', "Event status changed to {$newStatus}!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to change event status. Please try again.']);
        }
    }

    /**
     * Get zone pricing information
     */
    public function getZonePricing()
    {
        $zones = [
            'Warzone Exclusive' => ['price' => 350.00, 'available' => 100],
            'Warzone VIP' => ['price' => 250.00, 'available' => 28],
            'Warzone Grandstand' => ['price' => 220.00, 'available' => 60],
            'Warzone Premium Ringside' => ['price' => 199.00, 'available' => 1716],
            'Level 1 Zone A/B/C/D' => ['price' => 129.00, 'available' => 1946],
            'Level 2 Zone A/B/C/D' => ['price' => 89.00, 'available' => 1682],
            'Standing Zone A/B' => ['price' => 49.00, 'available' => 300],
        ];

        return response()->json([
            'zones' => $zones,
        ]);
    }
}