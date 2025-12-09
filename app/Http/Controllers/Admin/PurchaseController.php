<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseTicket;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Show the form for creating a new purchase ticket.
     */
    public function create()
    {
        $events = Event::select('id', 'name', 'date_time', 'status')->orderBy('date_time', 'asc')->get();
        $orders = Order::with('user')->select('id', 'user_id', 'total_amount', 'status')->orderBy('created_at', 'desc')->get();
        $ticketTypes = \App\Models\Ticket::with('event')->select('id', 'event_id', 'name', 'price')->orderBy('name', 'asc')->get();
        
        return view('admin.purchases.create', compact('events', 'orders', 'ticketTypes'));
    }

    /**
     * Store a newly created purchase ticket.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'event_id' => 'required|exists:events,id',
            'ticket_type_id' => 'required|exists:tickets,id',
            'price_paid' => 'required|numeric|min:0',
            'status' => 'required|in:pending,scanned,cancelled',
            'event_day' => 'nullable|date',
            'is_combo_purchase' => 'boolean',
            'combo_group_id' => 'nullable|string|max:255',
            'original_price' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
        ]);

        $purchase = PurchaseTicket::create([
            'order_id' => $request->order_id,
            'event_id' => $request->event_id,
            'ticket_type_id' => $request->ticket_type_id,
            'qrcode' => PurchaseTicket::generateQRCode(),
            'price_paid' => $request->price_paid,
            'status' => $request->status,
            'event_day' => $request->event_day,
            'event_day_name' => $request->event_day ? \Carbon\Carbon::parse($request->event_day)->format('M d, Y') : null,
            'is_combo_purchase' => $request->has('is_combo_purchase'),
            'combo_group_id' => $request->combo_group_id,
            'original_price' => $request->original_price ?? $request->price_paid,
            'discount_amount' => $request->discount_amount ?? 0,
            'scanned_at' => $request->status === 'scanned' ? now() : null,
        ]);

        return redirect()->route('admin.purchases.show', $purchase)
                        ->with('success', 'Purchase ticket created successfully.');
    }

    /**
     * Display a listing of purchase tickets.
     */
    public function index(Request $request)
    {
        $query = PurchaseTicket::with([
            'order' => function ($orderQuery) {
                $orderQuery->withTrashed()->with([
                    'user' => function ($userQuery) {
                        $userQuery->withTrashed();
                    }
                ]);
            },
            'event',
            'ticketType'
        ]);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('qrcode', 'like', "%{$search}%")
                  ->orWhereHas('order.user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
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

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $perPage = $request->get('limit', 10);
        $perPage = in_array($perPage, [10, 15, 25, 50, 100]) ? $perPage : 10;
        // Preserve applied filters when paging through purchases
        $purchases = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        // Statistics
        $totalPurchases = PurchaseTicket::count();
        $scannedPurchases = PurchaseTicket::where('status', 'scanned')->count();
        $pendingPurchases = PurchaseTicket::where('status', 'pending')->count();
        $cancelledPurchases = PurchaseTicket::where('status', 'cancelled')->count();
        $totalRevenue = PurchaseTicket::whereIn('status', ['sold', 'active', 'scanned'])->sum('price_paid');

        // Get events for filter dropdown
        $events = Event::select('id', 'name', 'date_time')->orderBy('date_time', 'desc')->get();

        return view('admin.purchases.index', compact(
            'purchases',
            'totalPurchases',
            'scannedPurchases',
            'pendingPurchases',
            'cancelledPurchases',
            'totalRevenue',
            'events'
        ));
    }

    /**
     * Display the specified purchase ticket.
     */
    public function show(PurchaseTicket $purchase)
    {
        $purchase->load(['order.user', 'event', 'ticketType']);
        
        return view('admin.purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified purchase ticket.
     */
    public function edit(PurchaseTicket $purchase)
    {
        $purchase->load(['order.user', 'event', 'ticketType']);
        
        // Get data for dropdowns
        $events = Event::select('id', 'name', 'date_time', 'status')->orderBy('date_time', 'asc')->get();
        $orders = Order::with('user')->select('id', 'user_id', 'total_amount', 'status')->orderBy('created_at', 'desc')->get();
        $ticketTypes = \App\Models\Ticket::with('event')->select('id', 'event_id', 'name', 'price')->orderBy('name', 'asc')->get();
        
        return view('admin.purchases.edit', compact('purchase', 'events', 'orders', 'ticketTypes'));
    }

    /**
     * Update the specified purchase ticket.
     */
    public function update(Request $request, PurchaseTicket $purchase)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'event_id' => 'required|exists:events,id',
            'ticket_type_id' => 'required|exists:tickets,id',
            'price_paid' => 'required|numeric|min:0',
            'status' => 'required|in:pending,scanned,cancelled',
            'event_day' => 'nullable|date',
            'is_combo_purchase' => 'boolean',
            'combo_group_id' => 'nullable|string|max:255',
            'original_price' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
        ]);

        $purchase->update([
            'order_id' => $request->order_id,
            'event_id' => $request->event_id,
            'ticket_type_id' => $request->ticket_type_id,
            'price_paid' => $request->price_paid,
            'status' => $request->status,
            'event_day' => $request->event_day,
            'event_day_name' => $request->event_day ? \Carbon\Carbon::parse($request->event_day)->format('M d, Y') : null,
            'is_combo_purchase' => $request->has('is_combo_purchase'),
            'combo_group_id' => $request->combo_group_id,
            'original_price' => $request->original_price ?? $request->price_paid,
            'discount_amount' => $request->discount_amount ?? 0,
            'scanned_at' => $request->status === 'scanned' ? ($purchase->scanned_at ?? now()) : null,
        ]);

        return redirect()->route('admin.purchases.show', $purchase)
                        ->with('success', 'Purchase ticket updated successfully.');
    }

    /**
     * Remove the specified purchase ticket.
     */
    public function destroy(PurchaseTicket $purchase)
    {
        // Only allow deletion of pending tickets
        if ($purchase->status !== 'pending') {
            return redirect()->back()
                           ->with('error', 'Only pending tickets can be deleted.');
        }

        $purchase->delete();

        return redirect()->route('admin.purchases.index')
                        ->with('success', 'Purchase ticket deleted successfully.');
    }

    /**
     * Mark a purchase ticket as scanned.
     */
    public function markScanned(PurchaseTicket $purchase)
    {
        $purchase->update([
            'status' => 'scanned',
            'scanned_at' => now(),
        ]);

        return redirect()->back()
                        ->with('success', 'Ticket marked as scanned successfully.');
    }

    /**
     * Cancel a purchase ticket.
     */
    public function cancel(PurchaseTicket $purchase)
    {
        if ($purchase->status === 'scanned') {
            return redirect()->back()
                           ->with('error', 'Cannot cancel a scanned ticket.');
        }

        $purchase->update([
            'status' => 'cancelled',
        ]);

        return redirect()->back()
                        ->with('success', 'Ticket cancelled successfully.');
    }

    /**
     * Display a listing of trashed purchases
     */
    public function trashed(Request $request)
    {
        $query = PurchaseTicket::onlyTrashed()->with([
            'order' => function ($orderQuery) {
                $orderQuery->withTrashed()->with([
                    'user' => function ($userQuery) {
                        $userQuery->withTrashed();
                    }
                ]);
            },
            'event',
            'ticketType'
        ]);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('qrcode', 'like', "%{$search}%")
                  ->orWhereHas('order.user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
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

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $perPage = $request->get('limit', 10);
        $perPage = in_array($perPage, [10, 15, 25, 50, 100]) ? $perPage : 10;
        // Preserve filters/search across trashed pagination too
        $purchases = $query->orderBy('deleted_at', 'desc')->paginate($perPage)->withQueryString();

        // Statistics
        $totalTrashed = PurchaseTicket::onlyTrashed()->count();
        $recentlyDeleted = PurchaseTicket::onlyTrashed()->where('deleted_at', '>=', now()->subDays(7))->count();

        // Get events for filter dropdown
        $events = Event::select('id', 'name', 'date_time')->orderBy('date_time', 'desc')->get();

        return view('admin.purchases.trashed', compact('purchases', 'totalTrashed', 'recentlyDeleted', 'events'));
    }

    /**
     * Restore a trashed purchase
     */
    public function restore(PurchaseTicket $purchase)
    {
        $purchase->restore();

        return redirect()->route('admin.purchases.trashed')
                        ->with('success', 'Purchase ticket restored successfully.');
    }

    /**
     * Permanently delete a trashed purchase
     */
    public function forceDelete(PurchaseTicket $purchase)
    {
        $qrcode = $purchase->qrcode;
        $purchase->forceDelete();

        return redirect()->route('admin.purchases.trashed')
                        ->with('success', "Purchase ticket '{$qrcode}' permanently deleted.");
    }
}
