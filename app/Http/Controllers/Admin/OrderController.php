<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'tickets']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $orders = $query->latest()->paginate(15);
        $statuses = Order::select('status')->distinct()->pluck('status');

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load(['user', 'tickets.seat', 'tickets.event', 'payments']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Paid,Cancelled,Refunded'
        ]);

        $oldValues = $order->toArray();
        $order->update(['status' => $request->status]);

        // Log the status update
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'orders',
            'record_id' => $order->id,
            'old_values' => $oldValues,
            'new_values' => $order->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Cancel order
     */
    public function cancel(Order $order)
    {
        if ($order->status === 'Paid') {
            return back()->withErrors(['error' => 'Cannot cancel a paid order. Use refund instead.']);
        }

        $oldValues = $order->toArray();
        $order->update(['status' => 'Cancelled']);

        // Cancel all tickets in this order
        $order->tickets()->update(['status' => 'Cancelled']);

        // Log the order cancellation
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'orders',
            'record_id' => $order->id,
            'old_values' => $oldValues,
            'new_values' => $order->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Order cancelled successfully!');
    }

    /**
     * Refund order
     */
    public function refund(Order $order)
    {
        if ($order->status !== 'Paid') {
            return back()->withErrors(['error' => 'Only paid orders can be refunded.']);
        }

        $oldValues = $order->toArray();
        $order->update(['status' => 'Refunded']);

        // Cancel all tickets in this order
        $order->tickets()->update(['status' => 'Cancelled']);

        // Log the order refund
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'orders',
            'record_id' => $order->id,
            'old_values' => $oldValues,
            'new_values' => $order->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Order refunded successfully!');
    }
}
