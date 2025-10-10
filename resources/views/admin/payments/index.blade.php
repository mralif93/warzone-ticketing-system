@extends('layouts.admin')

@section('title', 'Payments Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-wwc-neutral-900 font-display">Payments Management</h1>
            <p class="text-wwc-neutral-600 mt-1">Manage all payment transactions and refunds</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.payments.export') }}" 
               class="inline-flex items-center px-4 py-2 bg-wwc-accent text-white rounded-xl text-sm font-semibold hover:bg-wwc-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-accent transition-colors duration-200">
                <i class='bx bx-download text-lg mr-2'></i>
                Export CSV
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Total Payments -->
        <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $payments->total() }}</div>
                    <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Payments</div>
                    <div class="flex items-center">
                        <div class="flex items-center text-xs text-wwc-success font-semibold">
                            <i class='bx bx-check text-xs mr-1'></i>
                            {{ $payments->where('status', 'Completed')->count() }} Completed
                        </div>
                    </div>
                </div>
                <div class="h-12 w-12 rounded-lg bg-wwc-primary-light flex items-center justify-center">
                    <i class='bx bx-credit-card text-2xl text-wwc-primary'></i>
                </div>
            </div>
        </div>

        <!-- Completed Payments -->
        <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $payments->where('status', 'Completed')->count() }}</div>
                    <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Completed</div>
                    <div class="flex items-center">
                        <div class="flex items-center text-xs text-wwc-success font-semibold">
                            <i class='bx bx-check-circle text-xs mr-1'></i>
                            {{ $payments->where('status', 'Pending')->count() }} Pending
                        </div>
                    </div>
                </div>
                <div class="h-12 w-12 rounded-lg bg-wwc-success-light flex items-center justify-center">
                    <i class='bx bx-check-circle text-2xl text-wwc-success'></i>
                </div>
            </div>
        </div>

        <!-- Failed Payments -->
        <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $payments->where('status', 'Failed')->count() }}</div>
                    <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Failed</div>
                    <div class="flex items-center">
                        <div class="flex items-center text-xs text-wwc-error font-semibold">
                            <i class='bx bx-x text-xs mr-1'></i>
                            {{ $payments->where('status', 'Refunded')->count() }} Refunded
                        </div>
                    </div>
                </div>
                <div class="h-12 w-12 rounded-lg bg-wwc-error-light flex items-center justify-center">
                    <i class='bx bx-x-circle text-2xl text-wwc-error'></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">RM{{ number_format($payments->where('status', 'Completed')->sum('amount'), 0) }}</div>
                    <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Revenue</div>
                    <div class="flex items-center">
                        <div class="flex items-center text-xs text-wwc-accent font-semibold">
                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            +12% this month
                        </div>
                    </div>
                </div>
                <div class="h-12 w-12 rounded-lg bg-wwc-accent-light flex items-center justify-center">
                    <i class='bx bx-dollar text-2xl text-wwc-accent'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class='bx bx-search text-wwc-neutral-400'></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               class="block w-full pl-10 pr-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm"
                               placeholder="Search by transaction ID, payment method...">
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Status</label>
                    <select name="status" id="status" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Payment Method Filter -->
                <div>
                    <label for="payment_method" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        <option value="">All Methods</option>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method }}" {{ request('payment_method') == $method ? 'selected' : '' }}>
                                {{ $method }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label for="date_from" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Date From</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white rounded-xl text-sm font-semibold hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-search text-lg mr-2'></i>
                        Search
                    </button>
                    <a href="{{ route('admin.payments.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-wwc-neutral-100 text-wwc-neutral-700 rounded-xl text-sm font-semibold hover:bg-wwc-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-neutral-500 transition-colors duration-200">
                        <i class='bx bx-refresh text-lg mr-2'></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
        @if($payments->count() > 0)
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-wwc-neutral-900 font-display">All Payments</h2>
                        <p class="text-wwc-neutral-600 text-sm">Showing {{ $payments->count() }} of {{ $payments->total() }} payments</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="text-xs text-wwc-neutral-600">
                            <span class="font-semibold">{{ $payments->where('status', 'Completed')->count() }}</span> Completed
                        </div>
                        <div class="text-xs text-wwc-neutral-600">
                            <span class="font-semibold">{{ $payments->where('status', 'Pending')->count() }}</span> Pending
                        </div>
                        <div class="text-xs text-wwc-neutral-600">
                            <span class="font-semibold">{{ $payments->where('status', 'Failed')->count() }}</span> Failed
                        </div>
                        <div class="text-xs text-wwc-neutral-600">
                            <span class="font-semibold">{{ $payments->where('status', 'Refunded')->count() }}</span> Refunded
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-wwc-neutral-200">
                    <thead class="bg-wwc-neutral-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Transaction</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-wwc-neutral-200">
                        @foreach($payments as $payment)
                        <tr class="hover:bg-wwc-neutral-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-2xl bg-wwc-primary-light flex items-center justify-center shadow-sm">
                                            <i class='bx bx-credit-card text-lg text-wwc-primary'></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-wwc-neutral-900 font-display">{{ $payment->transaction_id }}</div>
                                        <div class="text-xs text-wwc-neutral-500">Order #{{ $payment->order_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-wwc-neutral-900">{{ $payment->order->user->name ?? 'N/A' }}</div>
                                <div class="text-xs text-wwc-neutral-500">{{ $payment->order->customer_email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-wwc-neutral-900">RM{{ number_format($payment->amount, 0) }}</div>
                                @if($payment->refund_amount > 0)
                                    <div class="text-xs text-wwc-error">Refunded: RM{{ number_format($payment->refund_amount, 0) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-wwc-neutral-100 text-wwc-neutral-800">
                                    {{ $payment->payment_method }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($payment->status)
                                    @case('Completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-wwc-success-light text-wwc-success">
                                            <i class='bx bx-check text-xs mr-1'></i>
                                            Completed
                                        </span>
                                        @break
                                    @case('Pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-wwc-warning-light text-wwc-warning">
                                            <i class='bx bx-time text-xs mr-1'></i>
                                            Pending
                                        </span>
                                        @break
                                    @case('Failed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-wwc-error-light text-wwc-error">
                                            <i class='bx bx-x text-xs mr-1'></i>
                                            Failed
                                        </span>
                                        @break
                                    @case('Refunded')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-wwc-neutral-100 text-wwc-neutral-800">
                                            <i class='bx bx-undo text-xs mr-1'></i>
                                            Refunded
                                        </span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-500">
                                {{ $payment->created_at->format('M j, Y g:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.payments.show', $payment) }}" 
                                       class="text-wwc-primary hover:text-wwc-primary-dark">
                                        <i class='bx bx-show text-lg'></i>
                                    </a>
                                    @if($payment->status === 'Completed')
                                        <button onclick="openRefundModal({{ $payment->id }})" 
                                                class="text-wwc-error hover:text-wwc-error-dark">
                                            <i class='bx bx-undo text-lg'></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-wwc-neutral-200 bg-wwc-neutral-50">
                {{ $payments->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class='bx bx-credit-card text-6xl text-wwc-neutral-300 mb-4'></i>
                <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">No Payments Found</h3>
                <p class="text-wwc-neutral-600 mb-6">No payments match your current filters.</p>
            </div>
        @endif
    </div>
</div>

<!-- Refund Modal -->
<div id="refundModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-4">Process Refund</h3>
            <form id="refundForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="refund_amount" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Refund Amount (RM)</label>
                        <input type="number" name="refund_amount" id="refund_amount" step="0.01" min="0.01" required
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
function openRefundModal(paymentId) {
    document.getElementById('refundModal').classList.remove('hidden');
    document.getElementById('refundForm').action = `/admin/payments/${paymentId}/refund`;
}

function closeRefundModal() {
    document.getElementById('refundModal').classList.add('hidden');
}
</script>
@endsection
