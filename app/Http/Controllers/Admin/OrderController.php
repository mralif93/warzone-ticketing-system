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
     * Show the form for creating a new order
     */
    public function create()
    {
        $users = \App\Models\User::all();
        $events = \App\Models\Event::where('status', 'On Sale')->get();
        return view('admin.orders.create', compact('users', 'events'));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'subtotal' => 'required|numeric|min:0',
            'service_fee' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:Pending,Paid,Cancelled,Refunded',
            'notes' => 'nullable|string|max:1000'
        ]);

        $order = Order::create([
            'user_id' => $request->user_id,
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'subtotal' => $request->subtotal,
            'service_fee' => $request->service_fee ?? 0,
            'tax_amount' => $request->tax_amount ?? 0,
            'total_amount' => $request->total_amount,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE',
            'table_name' => 'orders',
            'record_id' => $order->id,
            'old_values' => null,
            'new_values' => $order->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order created successfully!');
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
     * Show the form for editing the specified order
     */
    public function edit(Order $order)
    {
        $users = \App\Models\User::all();
        $events = \App\Models\Event::where('status', 'On Sale')->get();
        return view('admin.orders.edit', compact('order', 'users', 'events'));
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'subtotal' => 'required|numeric|min:0',
            'service_fee' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:Pending,Paid,Cancelled,Refunded',
            'notes' => 'nullable|string|max:1000'
        ]);

        $oldValues = $order->toArray();
        $order->update([
            'user_id' => $request->user_id,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'subtotal' => $request->subtotal,
            'service_fee' => $request->service_fee ?? 0,
            'tax_amount' => $request->tax_amount ?? 0,
            'total_amount' => $request->total_amount,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

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

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order updated successfully!');
    }

    /**
     * Remove the specified order from storage
     */
    public function destroy(Order $order)
    {
        if ($order->status === 'Paid') {
            return back()->withErrors(['error' => 'Cannot delete a paid order.']);
        }

        $oldValues = $order->toArray();
        $order->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE',
            'table_name' => 'orders',
            'record_id' => $order->id,
            'old_values' => $oldValues,
            'new_values' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully!');
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
