<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            'event_id' => 'required|exists:events,id',
            'customer_email' => 'required|email|max:255',
            'payment_method' => 'required|string|max:255',
            'zone' => 'required|string|in:Warzone Exclusive,Warzone VIP,Warzone Grandstand,Warzone Premium Ringside,Level 1 Zone A/B/C/D,Level 2 Zone A/B/C/D,Standing Zone A/B',
            'quantity' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();
        try {
            // Get event and user
            $event = \App\Models\Event::findOrFail($request->event_id);
            $user = \App\Models\User::findOrFail($request->user_id);

            // Calculate base pricing
            $basePrice = $this->getZonePrice($request->zone);
            $quantity = $request->quantity;
            
            // Calculate pricing for multiple tickets
            $subtotal = $basePrice * $quantity;
            $serviceFee = $this->calculateServiceFee($subtotal);
            $taxAmount = $this->calculateTax($subtotal + $serviceFee);
            $totalAmount = $subtotal + $serviceFee + $taxAmount;
            
            // Create order with calculated pricing (1 order = multiple tickets)
            $order = Order::create([
                'user_id' => $request->user_id,
                'customer_email' => $request->customer_email,
                'order_number' => Order::generateOrderNumber(),
                'qrcode' => Order::generateQRCode(),
                'subtotal' => $subtotal,
                'service_fee' => $serviceFee,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'status' => 'Paid', // Admin-created orders are considered paid
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            // Create multiple tickets for this order (same zone)
            for ($i = 0; $i < $quantity; $i++) {
                \App\Models\Ticket::create([
                    'order_id' => $order->id,
                    'event_id' => $event->id,
                    'zone' => $request->zone,
                    'qrcode' => \App\Models\Ticket::generateQRCode(),
                    'status' => 'Sold',
                    'price_paid' => $basePrice,
                ]);
            }

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

            DB::commit();

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Order created successfully with ' . $quantity . ' tickets.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create order. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load(['user', 'tickets.event', 'payments']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order
     */
    public function edit(Order $order)
    {
        $order->load(['tickets']);
        $users = \App\Models\User::all();
        return view('admin.orders.edit', compact('order', 'users'));
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'customer_email' => 'required|email|max:255',
            'payment_method' => 'required|string|max:255',
            'status' => 'required|in:Pending,Paid,Cancelled,Refunded',
            'zone' => 'required|string|in:Warzone Exclusive,Warzone VIP,Warzone Grandstand,Warzone Premium Ringside,Level 1 Zone A/B/C/D,Level 2 Zone A/B/C/D,Standing Zone A/B',
            'quantity' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();
        try {
            $oldValues = $order->toArray();
            
            // Update order basic information
            $order->update([
                'user_id' => $request->user_id,
                'customer_email' => $request->customer_email,
                'payment_method' => $request->payment_method,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            // Handle ticket updates (1 order = multiple tickets)
            $newZone = $request->zone;
            $newQuantity = $request->quantity;
            $basePrice = $this->getZonePrice($newZone);
            $currentTickets = $order->tickets;
            $currentQuantity = $currentTickets->count();
            $currentZone = $currentTickets->first()->zone ?? '';

            // Check if zone or quantity changed
            if ($newZone != $currentZone || $newQuantity != $currentQuantity) {
                // Delete existing tickets
                $order->tickets()->delete();

                // Create new tickets with updated zone and quantity
                for ($i = 0; $i < $newQuantity; $i++) {
                    \App\Models\Ticket::create([
                        'order_id' => $order->id,
                        'event_id' => $currentTickets->first()->event_id,
                        'zone' => $newZone,
                        'qrcode' => \App\Models\Ticket::generateQRCode(),
                        'status' => 'Sold',
                        'price_paid' => $basePrice,
                    ]);
                }

                // Update order pricing
                $subtotal = $basePrice * $newQuantity;
                $serviceFee = $this->calculateServiceFee($subtotal);
                $taxAmount = $this->calculateTax($subtotal + $serviceFee);
                $totalAmount = $subtotal + $serviceFee + $taxAmount;

                $order->update([
                    'subtotal' => $subtotal,
                    'service_fee' => $serviceFee,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                ]);
            }

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

            DB::commit();

            return redirect()->route('admin.orders.show', $order)->with('success', 'Order updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update order. Please try again.'])
                ->withInput();
        }
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

    /**
     * Get zone price
     */
    private function getZonePrice($zone)
    {
        $zonePrices = [
            'Warzone Exclusive' => 500,
            'Warzone VIP' => 250,
            'Warzone Grandstand' => 199,
            'Warzone Premium Ringside' => 150,
            'Level 1 Zone A/B/C/D' => 100,
            'Level 2 Zone A/B/C/D' => 75,
            'Standing Zone A/B' => 50,
        ];

        return $zonePrices[$zone] ?? 0;
    }
}
