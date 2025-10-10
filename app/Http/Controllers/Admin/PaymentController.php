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
                  ->orWhere('payment_method', 'like', "%{$search}%")
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
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $payments = $query->latest()->paginate(15);
        $statuses = Payment::select('status')->distinct()->pluck('status');
        $paymentMethods = Payment::select('payment_method')->distinct()->pluck('payment_method');

        return view('admin.payments.index', compact('payments', 'statuses', 'paymentMethods'));
    }

    /**
     * Display the specified payment
     */
    public function show(Payment $payment)
    {
        $payment->load(['order.user', 'order.tickets']);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Update payment status
     */
    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:Pending,Completed,Failed,Refunded'
        ]);

        $oldValues = $payment->toArray();
        $payment->update(['status' => $request->status]);

        // If payment is completed, update order status
        if ($request->status === 'Completed' && $payment->order) {
            $payment->order->update(['status' => 'Paid']);
        }

        // If payment is refunded, update order status
        if ($request->status === 'Refunded' && $payment->order) {
            $payment->order->update(['status' => 'Refunded']);
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
            'refund_amount' => 'required|numeric|min:0.01|max:' . $payment->amount,
            'refund_reason' => 'required|string|max:500'
        ]);

        if ($payment->status !== 'Completed') {
            return back()->withErrors(['error' => 'Only completed payments can be refunded.']);
        }

        $oldValues = $payment->toArray();
        
        // Update payment status
        $payment->update([
            'status' => 'Refunded',
            'refund_amount' => $request->refund_amount,
            'refund_reason' => $request->refund_reason,
            'refunded_at' => now(),
        ]);

        // Update order status
        if ($payment->order) {
            $payment->order->update(['status' => 'Refunded']);
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
                  ->orWhere('payment_method', 'like', "%{$search}%")
                  ->orWhereHas('order.user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
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
                    $payment->payment_method,
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
}
