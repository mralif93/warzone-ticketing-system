@extends('layouts.admin')

@section('title', 'Payment Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-wwc-neutral-900 font-display">Payment Details</h1>
            <p class="text-wwc-neutral-600 mt-1">Transaction ID: {{ $payment->transaction_id }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.payments.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-wwc-neutral-100 text-wwc-neutral-700 rounded-xl text-sm font-semibold hover:bg-wwc-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-neutral-500 transition-colors duration-200">
                <i class='bx bx-arrow-back text-lg mr-2'></i>
                Back to Payments
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Payment Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Details Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-wwc-neutral-900 font-display">Payment Information</h2>
                    @switch($payment->status)
                        @case('Completed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-wwc-success-light text-wwc-success">
                                <i class='bx bx-check text-sm mr-1'></i>
                                Completed
                            </span>
                            @break
                        @case('Pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-wwc-warning-light text-wwc-warning">
                                <i class='bx bx-time text-sm mr-1'></i>
                                Pending
                            </span>
                            @break
                        @case('Failed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-wwc-error-light text-wwc-error">
                                <i class='bx bx-x text-sm mr-1'></i>
                                Failed
                            </span>
                            @break
                        @case('Refunded')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-wwc-neutral-100 text-wwc-neutral-800">
                                <i class='bx bx-undo text-sm mr-1'></i>
                                Refunded
                            </span>
                            @break
                    @endswitch
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Transaction ID</label>
                        <p class="text-wwc-neutral-700 font-mono text-sm">{{ $payment->transaction_id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Payment Method</label>
                        <p class="text-wwc-neutral-700">{{ $payment->payment_method }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Amount</label>
                        <p class="text-2xl font-bold text-wwc-neutral-900">RM{{ number_format($payment->amount, 0) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Payment Date</label>
                        <p class="text-wwc-neutral-700">{{ $payment->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    @if($payment->refund_amount > 0)
                    <div>
                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Refund Amount</label>
                        <p class="text-lg font-semibold text-wwc-error">RM{{ number_format($payment->refund_amount, 0) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Refund Date</label>
                        <p class="text-wwc-neutral-700">{{ $payment->refunded_at ? \Carbon\Carbon::parse($payment->refunded_at)->format('M j, Y g:i A') : 'N/A' }}</p>
                    </div>
                    @endif
                </div>

                @if($payment->refund_reason)
                <div class="mt-6">
                    <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Refund Reason</label>
                    <p class="text-wwc-neutral-700 bg-wwc-neutral-50 p-3 rounded-xl">{{ $payment->refund_reason }}</p>
                </div>
                @endif
            </div>

            <!-- Order Information -->
            @if($payment->order)
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-6">
                <h2 class="text-xl font-semibold text-wwc-neutral-900 font-display mb-6">Order Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Order Number</label>
                        <p class="text-wwc-neutral-700 font-mono text-sm">{{ $payment->order->order_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Order Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($payment->order->status === 'Paid') bg-wwc-success-light text-wwc-success
                            @elseif($payment->order->status === 'Pending') bg-wwc-warning-light text-wwc-warning
                            @elseif($payment->order->status === 'Cancelled') bg-wwc-error-light text-wwc-error
                            @else bg-wwc-neutral-100 text-wwc-neutral-800 @endif">
                            {{ $payment->order->status }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Order Date</label>
                        <p class="text-wwc-neutral-700">{{ $payment->order->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Total Amount</label>
                        <p class="text-lg font-semibold text-wwc-neutral-900">RM{{ number_format($payment->order->total_amount, 0) }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('admin.orders.show', $payment->order) }}" 
                       class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white rounded-xl text-sm font-semibold hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-receipt text-lg mr-2'></i>
                        View Order Details
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Information -->
            @if($payment->order && $payment->order->user)
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-6">
                <h3 class="text-lg font-semibold text-wwc-neutral-900 font-display mb-4">Customer</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-wwc-neutral-500 mb-1">Name</label>
                        <p class="text-wwc-neutral-900">{{ $payment->order->user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-wwc-neutral-500 mb-1">Email</label>
                        <p class="text-wwc-neutral-700 text-sm">{{ $payment->order->user->email }}</p>
                    </div>
                    @if($payment->order->user->phone_number)
                    <div>
                        <label class="block text-xs font-semibold text-wwc-neutral-500 mb-1">Phone</label>
                        <p class="text-wwc-neutral-700 text-sm">{{ $payment->order->user->phone_number }}</p>
                    </div>
                    @endif
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.users.show', $payment->order->user) }}" 
                       class="inline-flex items-center px-3 py-2 bg-wwc-neutral-100 text-wwc-neutral-700 rounded-xl text-sm font-semibold hover:bg-wwc-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-neutral-500 transition-colors duration-200">
                        <i class='bx bx-user text-sm mr-2'></i>
                        View Profile
                    </a>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-6">
                <h3 class="text-lg font-semibold text-wwc-neutral-900 font-display mb-4">Actions</h3>
                
                <div class="space-y-3">
                    @if($payment->status === 'Completed')
                        <button onclick="openRefundModal()" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-error text-white rounded-xl text-sm font-semibold hover:bg-wwc-error-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-error transition-colors duration-200">
                            <i class='bx bx-undo text-lg mr-2'></i>
                            Process Refund
                        </button>
                    @endif

                    @if($payment->status === 'Pending')
                        <form method="POST" action="{{ route('admin.payments.update-status', $payment) }}" class="space-y-2">
                            @csrf
                            <select name="status" class="w-full px-3 py-2 border border-wwc-neutral-300 rounded-xl text-sm">
                                <option value="Completed">Mark as Completed</option>
                                <option value="Failed">Mark as Failed</option>
                            </select>
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-primary text-white rounded-xl text-sm font-semibold hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-check text-lg mr-2'></i>
                                Update Status
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div id="refundModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-4">Process Refund</h3>
            <form method="POST" action="{{ route('admin.payments.refund', $payment) }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="refund_amount" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Refund Amount (RM)</label>
                        <input type="number" name="refund_amount" id="refund_amount" step="0.01" min="0.01" max="{{ $payment->amount }}" value="{{ $payment->amount }}" required
                               class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                    </div>
                    <div>
                        <label for="refund_reason" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Refund Reason</label>
                        <textarea name="refund_reason" id="refund_reason" rows="3" required
                                  class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm"></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeRefundModal()" 
                            class="px-4 py-2 bg-wwc-neutral-100 text-wwc-neutral-700 rounded-xl text-sm font-semibold hover:bg-wwc-neutral-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-wwc-error text-white rounded-xl text-sm font-semibold hover:bg-wwc-error-dark">
                        Process Refund
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRefundModal() {
    document.getElementById('refundModal').classList.remove('hidden');
}

function closeRefundModal() {
    document.getElementById('refundModal').classList.add('hidden');
}
</script>
@endsection
