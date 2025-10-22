<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments
     */
    public function index(Request $request)
    {
        $query = Payment::with(['order.user']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('method', 'like', "%{$search}%")
                  ->orWhereHas('order.user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('method')) {
            $query->where('method', $request->method);
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
        $payments = $query->latest()->paginate($perPage);
        $statuses = Payment::select('status')->distinct()->pluck('status');
        $paymentMethods = Payment::select('method')->distinct()->pluck('method');

        return view('admin.payments.index', compact('payments', 'statuses', 'paymentMethods'));
    }

    /**
     * Display the specified payment
     */
    public function show(Payment $payment)
    {
        $payment->load([
            'order.user', 
            'order.purchaseTickets.ticketType',
            'order.purchaseTickets.event'
        ]);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Update payment status
     */
    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,refunded'
        ]);

        $oldValues = $payment->toArray();
        $payment->update(['status' => $request->status]);

        // If payment is completed, update order status
        if ($request->status === 'completed' && $payment->order) {
            $payment->order->update(['status' => 'paid']);
        }

        // If payment is refunded, update order status
        if ($request->status === 'refunded' && $payment->order) {
            $payment->order->update(['status' => 'refunded']);
        }

        // Log the payment update
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'payments',
            'record_id' => $payment->id,
            'old_values' => $oldValues,
            'new_values' => $payment->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Payment status updated successfully!');
    }

    /**
     * Process refund
     */
    public function refund(Request $request, Payment $payment)
    {
        $request->validate([
            'refund_amount' => 'required|numeric|min:0.01|max:' . $payment->getRemainingRefundableAmount(),
            'refund_reason' => 'required|string|max:500',
            'refund_method' => 'nullable|string|max:255',
            'refund_reference' => 'nullable|string|max:255'
        ]);

        if (!$payment->isSuccessful()) {
            return back()->withErrors(['error' => 'Only successful payments can be refunded.']);
        }

        if ($payment->isFullyRefunded()) {
            return back()->withErrors(['error' => 'Payment has already been fully refunded.']);
        }

        $oldValues = $payment->toArray();
        
        try {
            // Process refund using the model method
            $payment->processRefund(
                $request->refund_amount,
                $request->refund_reason,
                $request->refund_method,
                $request->refund_reference
            );

            // Update order status if fully refunded
            if ($payment->isFullyRefunded() && $payment->order) {
                $payment->order->update(['status' => 'refunded']);
                
                // Update all tickets to refunded status
                $payment->order->purchaseTickets()->update(['status' => 'refunded']);
            }

            // Log the refund
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'UPDATE',
                'table_name' => 'payments',
                'record_id' => $payment->id,
                'old_values' => $oldValues,
                'new_values' => $payment->toArray(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Refund processed successfully!');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Refund failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Export payments to CSV
     */
    public function export(Request $request)
    {
        $query = Payment::with(['order.user']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('method', 'like', "%{$search}%")
                  ->orWhereHas('order.user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $payments = $query->latest()->get();

        $filename = 'payments_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'Transaction ID',
                'Order ID',
                'Customer Name',
                'Customer Email',
                'Amount',
                'Payment Method',
                'Status',
                'Refund Amount',
                'Refund Reason',
                'Created At'
            ]);

            // CSV data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->transaction_id,
                    $payment->order_id,
                    $payment->order->user->name ?? 'N/A',
                    $payment->order->customer_email ?? 'N/A',
                    $payment->amount,
                    $payment->method,
                    $payment->status,
                    $payment->refund_amount ?? '',
                    $payment->refund_reason ?? '',
                    $payment->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for creating a new payment
     */
    public function create()
    {
        $orders = \App\Models\Order::with('user')->get();
        
        // Prepare order data for JavaScript
        $orderData = $orders->keyBy('id')->map(function($order) {
            return [
                'id' => $order->id,
                'total_amount' => $order->total_amount,
                'customer_name' => $order->user->name ?? 'Guest',
                'customer_email' => $order->customer_email ?? 'N/A'
            ];
        });
        
        return view('admin.payments.create', compact('orders', 'orderData'));
    }

    /**
     * Store a newly created payment
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|string|max:255',
            'transaction_id' => 'nullable|string|max:255|unique:payments,transaction_id',
            'status' => 'required|in:pending,completed,failed,refunded',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Generate transaction ID if not provided
        if (!$request->transaction_id) {
            $request->merge(['transaction_id' => 'PAY-' . strtoupper(uniqid())]);
        }

        $payment = Payment::create($request->all());

        // If payment is completed, update order status
        if ($request->status === 'completed' && $payment->order) {
            $payment->order->update(['status' => 'paid']);
        }

        // Log the payment creation
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE',
            'table_name' => 'payments',
            'record_id' => $payment->id,
            'old_values' => null,
            'new_values' => $payment->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.payments.show', $payment)->with('success', 'Payment created successfully!');
    }

    /**
     * Show the form for editing the specified payment
     */
    public function edit(Payment $payment)
    {
        $orders = \App\Models\Order::with('user')->get();
        
        // Prepare order data for JavaScript
        $orderData = $orders->keyBy('id')->map(function($order) {
            return [
                'id' => $order->id,
                'total_amount' => $order->total_amount,
                'customer_name' => $order->user->name ?? 'Guest',
                'customer_email' => $order->customer_email ?? 'N/A'
            ];
        });
        
        return view('admin.payments.edit', compact('payment', 'orders', 'orderData'));
    }

    /**
     * Update the specified payment
     */
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|string|max:255',
            'transaction_id' => 'nullable|string|max:255|unique:payments,transaction_id,' . $payment->id,
            'status' => 'required|in:pending,completed,failed,refunded',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000'
        ]);

        $oldValues = $payment->toArray();
        $payment->update($request->all());

        // If payment is completed, update order status
        if ($request->status === 'completed' && $payment->order) {
            $payment->order->update(['status' => 'paid']);
        }

        // If payment is refunded, update order status
        if ($request->status === 'refunded' && $payment->order) {
            $payment->order->update(['status' => 'refunded']);
        }

        // Log the payment update
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'payments',
            'record_id' => $payment->id,
            'old_values' => $oldValues,
            'new_values' => $payment->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.payments.show', $payment)->with('success', 'Payment updated successfully!');
    }

    /**
     * Remove the specified payment
     */
    public function destroy(Payment $payment)
    {
        if ($payment->status === 'completed') {
            return back()->withErrors(['error' => 'Cannot delete a completed payment.']);
        }

        $oldValues = $payment->toArray();
        $payment->delete();

        // Log the payment deletion
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE',
            'table_name' => 'payments',
            'record_id' => $payment->id,
            'old_values' => $oldValues,
            'new_values' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.payments.index')->with('success', 'Payment deleted successfully!');
    }

    /**
     * Change payment status
     */
    public function changeStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,succeeded,failed,cancelled,refunded'
        ]);

        $oldStatus = $payment->status;
        $newStatus = $request->status;

        $payment->update([
            'status' => $newStatus,
            'processed_at' => $newStatus === 'Succeeded' ? now() : null
        ]);

        // Log the status change
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'payments',
            'record_id' => $payment->id,
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $newStatus],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->back()->with('success', "Payment status changed from {$oldStatus} to {$newStatus} successfully!");
    }
}
