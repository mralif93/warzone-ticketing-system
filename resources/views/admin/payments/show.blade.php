@extends('layouts.admin')

@section('title', 'Payment Details')
@section('page-title', 'Payment Details')

@section('content')
<!-- Professional Payment Details with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <div class="flex items-center">
                    <a href="{{ route('admin.payments.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Payments
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Payment Details -->
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Payment Details</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-info-circle text-sm'></i>
                                    <span>Payment information</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Transaction ID -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-credit-card text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Transaction ID</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $payment->transaction_id }}</span>
                                    </div>
                                </div>

                                <!-- Order ID -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-receipt text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Order ID</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">#{{ $payment->order_id }}</span>
                                    </div>
                                </div>

                                <!-- Customer -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-user text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Customer</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $payment->order->user->name ?? 'Guest' }}</span>
                                    </div>
                                </div>

                                <!-- Amount -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                                            <i class='bx bx-dollar text-sm text-emerald-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Amount</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($payment->amount, 0) }}</span>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                            <i class='bx bx-credit-card text-sm text-orange-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Payment Method</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $payment->payment_method }}</span>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-yellow-100 flex items-center justify-center">
                                            <i class='bx bx-check-circle text-sm text-yellow-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Status</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">
                                            @switch($payment->status)
                                                @case('Completed')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Completed
                                                    </span>
                                                    @break
                                                @case('Pending')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Pending
                                                    </span>
                                                    @break
                                                @case('Failed')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Failed
                                                    </span>
                                                    @break
                                                @case('Refunded')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        Refunded
                                                    </span>
                                                    @break
                                            @endswitch
                                        </span>
                                    </div>
                                </div>

                                <!-- Payment Date -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                            <i class='bx bx-calendar text-sm text-indigo-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Payment Date</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">
                                            {{ $payment->payment_date ? $payment->payment_date->format('M d, Y h:i A') : 'Not set' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Refund Information -->
                                @if($payment->refund_amount)
                                    <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                        <div class="flex-shrink-0 mr-4">
                                            <div class="h-8 w-8 rounded-lg bg-red-100 flex items-center justify-center">
                                                <i class='bx bx-undo text-sm text-red-600'></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 flex items-center justify-between">
                                            <span class="text-sm font-semibold text-wwc-neutral-600">Refund Amount</span>
                                            <span class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($payment->refund_amount, 0) }}</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Notes -->
                                @if($payment->notes)
                                    <div class="flex items-center py-3">
                                        <div class="flex-shrink-0 mr-4">
                                            <div class="h-8 w-8 rounded-lg bg-teal-100 flex items-center justify-center">
                                                <i class='bx bx-note text-sm text-teal-600'></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 flex items-center justify-between">
                                            <span class="text-sm font-semibold text-wwc-neutral-600">Notes</span>
                                            <span class="text-base font-medium text-wwc-neutral-900">{{ $payment->notes }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Payment Statistics -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Payment Statistics</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Created Date -->
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-wwc-neutral-900">{{ $payment->created_at->format('M d, Y') }}</div>
                                    <div class="text-sm text-wwc-neutral-500">Created Date</div>
                                </div>
                                <!-- Updated Date -->
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-wwc-neutral-900">{{ $payment->updated_at->format('M d, Y') }}</div>
                                    <div class="text-sm text-wwc-neutral-500">Last Updated</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Quick Actions</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <a href="{{ route('admin.payments.edit', $payment) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                    <i class='bx bx-edit text-sm mr-2'></i>
                                    Edit Payment
                                </a>
                                @if($payment->status === 'Completed')
                                    <form method="POST" action="{{ route('admin.payments.refund', $payment) }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                            <i class='bx bx-undo text-sm mr-2'></i>
                                            Process Refund
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.payments.index') }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                    <i class='bx bx-list-ul text-sm mr-2'></i>
                                    View All Payments
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection