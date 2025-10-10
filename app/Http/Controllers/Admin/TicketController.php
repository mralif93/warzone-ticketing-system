<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['order.user', 'event', 'seat']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('qrcode', 'like', "%{$search}%")
                  ->orWhereHas('order.user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('event', function($eventQuery) use ($search) {
                      $eventQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by price zone
        if ($request->filled('price_zone')) {
            $query->where('price_zone', $request->price_zone);
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

        $tickets = $query->latest()->paginate(15);
        $statuses = Ticket::select('status')->distinct()->pluck('status');
        $priceZones = \App\Models\PriceZone::active()->ordered()->pluck('name');
        $events = \App\Models\Event::select('id', 'name')->get();

        return view('admin.tickets.index', compact('tickets', 'statuses', 'priceZones', 'events'));
    }

    /**
     * Show the form for creating a new ticket
     */
    public function create()
    {
        $events = \App\Models\Event::where('status', 'On Sale')->get();
        $seats = \App\Models\Seat::all();
        $orders = \App\Models\Order::with('user')->get();

        return view('admin.tickets.create', compact('events', 'seats', 'orders'));
    }

    /**
     * Store a newly created ticket
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'seat_id' => 'required|exists:seats,id',
            'order_id' => 'required|exists:orders,id',
            'price_paid' => 'required|numeric|min:0',
            'status' => 'required|in:Sold,Held,Used,Cancelled',
            'qrcode' => 'nullable|string|max:255|unique:tickets,qrcode'
        ]);

        // Generate QR code if not provided
        if (!$request->qrcode) {
            $request->merge(['qrcode' => 'TKT-' . strtoupper(uniqid())]);
        }

        $ticket = Ticket::create($request->all());

        // Log the ticket creation
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE',
            'table_name' => 'tickets',
            'record_id' => $ticket->id,
            'old_values' => null,
            'new_values' => $ticket->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.tickets.show', $ticket)
                        ->with('success', 'Ticket created successfully!');
    }

    /**
     * Display the specified ticket
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['order.user', 'event', 'seat', 'admittanceLogs.staffUser']);

        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified ticket
     */
    public function edit(Ticket $ticket)
    {
        $events = \App\Models\Event::where('status', 'On Sale')->get();
        $seats = \App\Models\Seat::all();
        $orders = \App\Models\Order::with('user')->get();

        return view('admin.tickets.edit', compact('ticket', 'events', 'seats', 'orders'));
    }

    /**
     * Update the specified ticket
     */
    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'seat_id' => 'required|exists:seats,id',
            'order_id' => 'required|exists:orders,id',
            'price_paid' => 'required|numeric|min:0',
            'status' => 'required|in:Sold,Held,Used,Cancelled',
            'qrcode' => 'nullable|string|max:255|unique:tickets,qrcode,' . $ticket->id
        ]);

        $oldValues = $ticket->toArray();
        $ticket->update($request->all());

        // Log the ticket update
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

        return redirect()->route('admin.tickets.show', $ticket)
                        ->with('success', 'Ticket updated successfully!');
    }

    /**
     * Remove the specified ticket
     */
    public function destroy(Ticket $ticket)
    {
        if ($ticket->status === 'Used') {
            return back()->withErrors(['error' => 'Cannot delete a used ticket.']);
        }

        $oldValues = $ticket->toArray();
        $ticket->delete();

        // Log the ticket deletion
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
                        ->with('success', 'Ticket deleted successfully!');
    }

    /**
     * Update ticket status
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:Sold,Held,Used,Cancelled'
        ]);

        $oldValues = $ticket->toArray();
        $ticket->update(['status' => $request->status]);

        // Log the status update
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

        return back()->with('success', 'Ticket status updated successfully!');
    }

    /**
     * Cancel ticket
     */
    public function cancel(Ticket $ticket)
    {
        if ($ticket->status === 'Used') {
            return back()->withErrors(['error' => 'Cannot cancel a used ticket.']);
        }

        $oldValues = $ticket->toArray();
        $ticket->update(['status' => 'Cancelled']);

        // Log the ticket cancellation
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'tickets',
            'record_id' => $ticket->id,
            'old_values' => $oldValues,
            'new_values' => $ticket->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Ticket cancelled successfully!');
    }

    /**
     * Mark ticket as used
     */
    public function markUsed(Ticket $ticket)
    {
        if ($ticket->status !== 'Sold') {
            return back()->withErrors(['error' => 'Only sold tickets can be marked as used.']);
        }

        $oldValues = $ticket->toArray();
        $ticket->update(['status' => 'Used']);

        // Log the ticket usage
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'tickets',
            'record_id' => $ticket->id,
            'old_values' => $oldValues,
            'new_values' => $ticket->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Ticket marked as used successfully!');
    }

    /**
     * Bulk update ticket statuses
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ticket_ids' => 'required|array|min:1',
            'ticket_ids.*' => 'exists:tickets,id',
            'status' => 'required|in:Sold,Held,Used,Cancelled'
        ]);

        $tickets = Ticket::whereIn('id', $request->ticket_ids)->get();
        $updatedCount = 0;

        foreach ($tickets as $ticket) {
            if ($ticket->status !== $request->status) {
                $oldValues = $ticket->toArray();
                $ticket->update(['status' => $request->status]);
                $updatedCount++;

                // Log each ticket update
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
            }
        }

        return back()->with('success', "Updated {$updatedCount} tickets successfully!");
    }
}
