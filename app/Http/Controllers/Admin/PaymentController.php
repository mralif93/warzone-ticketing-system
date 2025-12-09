<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // Preserve search/filters when moving between pages
        $payments = $query->latest()->paginate($perPage)->withQueryString();
        $statuses = Payment::select('status')->distinct()->pluck('status');
        $paymentMethods = Payment::select('method')->distinct()->pluck('method');
        $paymentMethodsList = $paymentMethods->filter()->values();
        $paymentExportableFields = $this->paymentExportableFields();

        // Calculate additional statistics from ALL payments (not paginated)
        $allPaymentsQuery = Payment::query();
        
        // Apply the same filters as the main query
        if ($request->filled('search')) {
            $search = $request->search;
            $allPaymentsQuery->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('method', 'like', "%{$search}%")
                  ->orWhereHas('order.user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        if ($request->filled('status')) {
            $allPaymentsQuery->where('status', $request->status);
        }
        if ($request->filled('method')) {
            $allPaymentsQuery->where('method', $request->method);
        }
        if ($request->filled('date_from')) {
            $allPaymentsQuery->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $allPaymentsQuery->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }
        
        $allPayments = $allPaymentsQuery->get();
        $totalRevenue = $allPayments->whereIn('status', ['succeeded', 'completed'])->sum('amount');
        $totalRefunded = $allPayments->whereIn('status', ['refunded', 'partially_refunded'])->sum('refund_amount');
        
        // Calculate individual statistics
        $totalPayments = $allPayments->count();
        $succeededPayments = $allPayments->whereIn('status', ['succeeded', 'completed'])->count();
        $pendingPayments = $allPayments->whereIn('status', ['pending'])->count();
        $failedPayments = $allPayments->whereIn('status', ['failed'])->count();

        // Diagnostic logging: breakdown by method and status to verify completeness
        $methodBreakdown = Payment::select('method', DB::raw('COUNT(*) as total'), DB::raw('SUM(amount) as total_amount'))
            ->groupBy('method')
            ->orderBy('total', 'desc')
            ->get();

        $statusBreakdown = Payment::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderBy('total', 'desc')
            ->get();

        Log::info('Payment index diagnostics', [
            'filters' => $request->only(['search', 'status', 'method', 'date_from', 'date_to', 'limit', 'page']),
            'total_payments' => $totalPayments,
            'method_breakdown' => $methodBreakdown,
            'status_breakdown' => $statusBreakdown,
            'methods_list' => $paymentMethodsList,
        ]);

        return view('admin.payments.index', compact('payments', 'statuses', 'paymentMethods', 'paymentMethodsList', 'paymentExportableFields', 'totalRevenue', 'totalRefunded', 'totalPayments', 'succeededPayments', 'pendingPayments', 'failedPayments'));
    }

    public function exportPage()
    {
        $fields = $this->paymentExportableFields();
        return view('admin.payments.export', compact('fields'));
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
            'status' => 'required|in:pending,succeeded,failed,refunded,cancelled,partially_refunded'
        ]);

        $oldValues = $payment->toArray();
        $payment->update(['status' => $request->status]);

        // Sync Order and PurchaseTicket statuses based on Payment status
        if ($payment->order) {
            $order = $payment->order;
            
            if ($request->status === 'succeeded') {
                // Payment succeeded → Order paid → Tickets active
                $order->update(['status' => 'paid', 'paid_at' => now()]);
                $order->purchaseTickets()->update(['status' => 'active']);
            } elseif ($request->status === 'refunded') {
                // Payment refunded → Order refunded → Tickets refunded
                $order->update(['status' => 'refunded']);
                $order->purchaseTickets()->update(['status' => 'refunded']);
            } elseif ($request->status === 'failed' || $request->status === 'cancelled') {
                // Payment failed/cancelled → Order pending → Tickets pending
                $order->update(['status' => 'pending']);
                $order->purchaseTickets()->update(['status' => 'pending']);
            } elseif ($request->status === 'pending') {
                // Payment pending → Order pending → Tickets pending
                $order->update(['status' => 'pending']);
                $order->purchaseTickets()->update(['status' => 'pending']);
            }
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
        $fields = $this->parseExportFields($request->get('fields'), $this->paymentExportableFields());

        $query = Payment::with(['order.user'])
            ->when($request->filled('search'), function($q) use ($request) {
                $search = $request->search;
                $q->where(function($q2) use ($search) {
                    $q2->where('transaction_id', 'like', "%{$search}%")
                       ->orWhere('method', 'like', "%{$search}%")
                       ->orWhereHas('order.user', function($userQuery) use ($search) {
                           $userQuery->where('name', 'like', "%{$search}%");
                       });
                });
            })
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('method'), fn($q) => $q->where('method', $request->method))
            ->when($request->filled('date_from'), fn($q) => $q->where('created_at', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->where('created_at', '<=', $request->date_to . ' 23:59:59'))
            ->orderByDesc('created_at');

        $filename = 'payments_' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function() use ($query, $fields) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $fields);

            $query->chunk(500, function($payments) use ($file, $fields) {
                foreach ($payments as $payment) {
                    $row = [];
                    foreach ($fields as $field) {
                        $row[] = data_get($payment, $field);
                    }
                    fputcsv($file, $row);
                }
            });

            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    /**
     * Import payments from CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');
        if (!$handle) {
            return back()->withErrors(['file' => 'Unable to read uploaded file.']);
        }

        $headers = fgetcsv($handle);
        if (!$headers) {
            return back()->withErrors(['file' => 'CSV appears empty.']);
        }

        $allowed = $this->paymentExportableFields();
        $selected = array_values(array_intersect($headers, $allowed));
        if (empty($selected)) {
            fclose($handle);
            return back()->withErrors(['file' => 'No valid columns found in CSV.']);
        }

        $created = 0; $updated = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($headers, $row);
            $payload = array_intersect_key($data, array_flip($allowed));

            $id = $payload['id'] ?? null;
            unset($payload['id']);

            if ($id && $payment = Payment::find($id)) {
                $payment->forceFill($payload);
                if (isset($payload['created_at']) || isset($payload['updated_at'])) {
                    $payment->timestamps = false;
                }
                $payment->save();
                $updated++;
            } else {
                $payment = new Payment();
                $payment->forceFill($payload);
                if (isset($payload['created_at']) || isset($payload['updated_at'])) {
                    $payment->timestamps = false;
                }
                $payment->save();
                $created++;
            }
        }
        fclose($handle);

        return back()->with('success', "Import completed. Created: {$created}, Updated: {$updated}");
    }

    private function parseExportFields($fields, array $allowed): array
    {
        if (!$fields) {
            return $allowed;
        }
        if (is_array($fields)) {
            $requested = collect($fields)->map(fn($f) => trim($f));
        } else {
            $requested = collect(explode(',', $fields))->map(fn($f) => trim($f));
        }
        $requested = $requested->filter()->unique()->values()->all();

        $valid = array_values(array_intersect($requested, $allowed));
        return count($valid) ? $valid : $allowed;
    }

    private function paymentExportableFields(): array
    {
        return [
            'id',
            'order_id',
            'transaction_id',
            'stripe_charge_id',
            'stripe_payment_intent_id',
            'method',
            'amount',
            'currency',
            'status',
            'failure_reason',
            'processed_at',
            'refund_amount',
            'refund_date',
            'refund_reason',
            'refund_method',
            'refund_reference',
            'payment_date',
            'notes',
            'created_at',
            'updated_at',
        ];
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
            'status' => 'required|in:pending,succeeded,failed,refunded,cancelled,partially_refunded',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Generate transaction ID if not provided
        if (!$request->transaction_id) {
            $request->merge(['transaction_id' => 'PAY-' . strtoupper(uniqid())]);
        }

        $payment = Payment::create($request->all());

        // Sync Order and PurchaseTicket statuses based on Payment status
        if ($payment->order) {
            $order = $payment->order;
            
            if ($request->status === 'succeeded') {
                // Payment succeeded → Order paid → Tickets active
                $order->update(['status' => 'paid', 'paid_at' => now()]);
                $order->purchaseTickets()->update(['status' => 'active']);
            } elseif ($request->status === 'refunded') {
                // Payment refunded → Order refunded → Tickets refunded
                $order->update(['status' => 'refunded']);
                $order->purchaseTickets()->update(['status' => 'refunded']);
            } elseif (in_array($request->status, ['failed', 'cancelled', 'pending'])) {
                // Payment failed/cancelled/pending → Order pending → Tickets pending
                $order->update(['status' => 'pending']);
                $order->purchaseTickets()->update(['status' => 'pending']);
            }
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
            'status' => 'required|in:pending,succeeded,failed,refunded,cancelled,partially_refunded',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000'
        ]);

        $oldValues = $payment->toArray();
        $payment->update($request->all());

        // Sync Order and PurchaseTicket statuses based on Payment status
        if ($payment->order) {
            $order = $payment->order;
            
            if ($request->status === 'succeeded') {
                // Payment succeeded → Order paid → Tickets active
                $order->update(['status' => 'paid', 'paid_at' => now()]);
                $order->purchaseTickets()->update(['status' => 'active']);
            } elseif ($request->status === 'refunded') {
                // Payment refunded → Order refunded → Tickets refunded
                $order->update(['status' => 'refunded']);
                $order->purchaseTickets()->update(['status' => 'refunded']);
            } elseif (in_array($request->status, ['failed', 'cancelled', 'pending'])) {
                // Payment failed/cancelled/pending → Order pending → Tickets pending
                $order->update(['status' => 'pending']);
                $order->purchaseTickets()->update(['status' => 'pending']);
            }
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
        if ($payment->status === 'succeeded') {
            return back()->withErrors(['error' => 'Cannot delete a succeeded payment.']);
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
