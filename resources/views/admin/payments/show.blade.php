@extends('layouts.admin')

@section('title', 'Payment Details - ' . $payment->id)
@section('page-title', 'Payment Details')

@section('content')
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
                                <!-- Payment ID -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-receipt text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Payment ID</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $payment->id }}</span>
                                    </div>
                                </div>

                                <!-- Order -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-shopping-bag text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Order</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $payment->order->order_number }}</span>
                                    </div>
                                </div>

                                <!-- Amount -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                            <i class='bx bx-dollar text-sm text-orange-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Amount</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($payment->amount, 2) }}</span>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-credit-card text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Payment Method</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $payment->formatted_method ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                            <i class='bx bx-check-circle text-sm text-indigo-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Status</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @switch($payment->status)
                                                @case('succeeded')
                                                    bg-green-100 text-green-800
                                                    @break
                                                @case('pending')
                                                    bg-yellow-100 text-yellow-800
                                                    @break
                                                @case('failed')
                                                    bg-red-100 text-red-800
                                                    @break
                                                @case('cancelled')
                                                    bg-gray-100 text-gray-800
                                                    @break
                                                @case('refunded')
                                                    bg-blue-100 text-blue-800
                                                    @break
                                                @default
                                                    bg-gray-100 text-gray-800
                                            @endswitch">
                                            @switch($payment->status)
                                                @case('succeeded')
                                                    Succeeded
                                                    @break
                                                @case('pending')
                                                    Pending
                                                    @break
                                                @case('failed')
                                                    Failed
                                                    @break
                                                @case('cancelled')
                                                    Cancelled
                                                    @break
                                                @case('refunded')
                                                    Refunded
                                                    @break
                                                @default
                                                    Unknown
                                            @endswitch
                                        </span>
                                    </div>
                                </div>

                                <!-- Transaction ID -->
                                @if($payment->transaction_id)
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-wwc-accent/20 flex items-center justify-center">
                                            <i class='bx bx-hash text-sm text-wwc-accent'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Transaction ID</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $payment->transaction_id }}</span>
                                    </div>
                                </div>
                                @endif

                                <!-- Payment Date -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <i class='bx bx-calendar text-sm text-gray-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Payment Date</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $payment->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                </div>

                                <!-- Refund Amount -->
                                @if($payment->refund_amount > 0)
                                <div class="flex items-center py-3">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-red-100 flex items-center justify-center">
                                            <i class='bx bx-undo text-sm text-red-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Refund Amount</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($payment->refund_amount, 2) }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Payment Summary -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Payment Summary</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-calculator text-sm'></i>
                                    <span>Amount details</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-wwc-neutral-600">Original Amount</span>
                                <span class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($payment->amount, 2) }}</span>
                            </div>
                            @if($payment->refund_amount > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-wwc-neutral-600">Refunded</span>
                                <span class="text-base font-medium text-red-600">-RM{{ number_format($payment->refund_amount, 2) }}</span>
                            </div>
                            <div class="border-t border-wwc-neutral-200 pt-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-wwc-neutral-900">Net Amount</span>
                                    <span class="text-xl font-bold text-wwc-neutral-900">RM{{ number_format($payment->amount - $payment->refund_amount, 2) }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Order Info</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-shopping-bag text-sm'></i>
                                    <span>Related order</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i class='bx bx-receipt text-sm text-blue-600'></i>
                                    </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Order Number</span>
                                </div>
                                <span class="text-base font-medium text-wwc-neutral-900">{{ $payment->order->order_number }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                        <i class='bx bx-user text-sm text-green-600'></i>
                                    </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Customer</span>
                                </div>
                                <span class="text-base font-medium text-wwc-neutral-900">{{ $payment->order->user->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                        <i class='bx bx-check-circle text-sm text-purple-600'></i>
                                    </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Order Status</span>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                    @if($payment->order->status === 'paid') bg-green-100 text-green-800
                                    @elseif($payment->order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($payment->order->status === 'cancelled') bg-red-100 text-red-800
                                    @elseif($payment->order->status === 'refunded') bg-gray-100 text-gray-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucwords($payment->order->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Actions</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-cog text-sm'></i>
                                    <span>Quick actions</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('admin.orders.show', $payment->order) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-receipt text-sm mr-2'></i>
                                View Order
                            </a>
                            
                            <a href="{{ route('admin.users.show', $payment->order->user) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-info text-white text-sm font-semibold rounded-lg hover:bg-wwc-info-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-info transition-colors duration-200">
                                <i class='bx bx-user text-sm mr-2'></i>
                                View Customer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection