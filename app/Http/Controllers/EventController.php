<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Seat;
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
        $events = Event::withCount('tickets')
            ->orderBy('date_time', 'asc')
            ->paginate(10);

        return view('events.index', compact('events'));
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
        ]);

        DB::beginTransaction();
        try {
            $event = Event::create([
                'name' => $request->name,
                'date_time' => $request->date_time,
                'description' => $request->description,
                'venue' => $request->venue ?? 'Warzone Arena',
                'max_tickets_per_order' => $request->max_tickets_per_order,
                'status' => 'Draft',
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

            return redirect()->route('events.index')
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
            ->with(['order.user', 'seat'])
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
            'status' => 'required|in:Draft,On Sale,Sold Out,Cancelled',
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

            return redirect()->route('events.index')
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
            'status' => 'required|in:Draft,On Sale,Sold Out,Cancelled',
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
     * Get available seats for an event
     */
    public function getAvailableSeats(Event $event)
    {
        $priceZone = request('price_zone');
        $quantity = request('quantity', 1);

        $query = $event->availableSeats();
        
        if ($priceZone) {
            $query->byPriceZone($priceZone);
        }

        $availableSeats = $query->limit($quantity)->get();

        return response()->json([
            'available_seats' => $availableSeats,
            'count' => $availableSeats->count(),
        ]);
    }

    /**
     * Get seat pricing information
     */
    public function getSeatPricing()
    {
        $pricing = Seat::select('price_zone', 'seat_type')
            ->selectRaw('MIN(base_price) as min_price')
            ->selectRaw('MAX(base_price) as max_price')
            ->selectRaw('AVG(base_price) as avg_price')
            ->selectRaw('COUNT(*) as seat_count')
            ->groupBy('price_zone', 'seat_type')
            ->orderBy('min_price')
            ->get();

        return response()->json($pricing);
    }
}