@extends('layouts.admin')

@section('title', 'Purchase Details - ' . $purchase->id)
@section('page-title', 'Purchase Details')

@section('content')
<div class="min-h-screen bg-wwc-neutral-50">
    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <div class="flex items-center">
                    <a href="{{ route('admin.purchases.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Purchases
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Purchase Details -->
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Purchase Details</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-info-circle text-sm'></i>
                                    <span>Purchase information</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Purchase ID -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-receipt text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Purchase ID</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $purchase->id }}</span>
                                    </div>
                                </div>

                                <!-- Ticket Type -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-ticket text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Ticket Type</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $purchase->ticketType->name ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <!-- Event -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-calendar text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Event</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $purchase->event->name ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <!-- Customer -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                            <i class='bx bx-user text-sm text-orange-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Customer</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $purchase->order->user->name ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <!-- Price Paid -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-wwc-accent/20 flex items-center justify-center">
                                            <i class='bx bx-dollar text-sm text-wwc-accent'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Price Paid</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($purchase->price_paid, 2) }}</span>
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
                                            @if($purchase->status === 'scanned') bg-green-100 text-green-800
                                            @elseif($purchase->status === 'sold') bg-blue-100 text-blue-800
                                            @elseif($purchase->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($purchase->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($purchase->status) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Purchase Date -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <i class='bx bx-calendar text-sm text-gray-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Purchase Date</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $purchase->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                </div>

                                <!-- QR Code -->
                                @if($purchase->qr_code)
                                <div class="flex items-center py-3">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-wwc-primary/20 flex items-center justify-center">
                                            <i class='bx bx-qr-scan text-sm text-wwc-primary'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">QR Code</span>
                                        <p class="text-xs text-wwc-neutral-500 mt-1">{{ $purchase->qr_code }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
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
                                <span class="text-base font-medium text-wwc-neutral-900">{{ $purchase->order->order_number ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                        <i class='bx bx-check-circle text-sm text-green-600'></i>
                                    </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Order Status</span>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                    @if(($purchase->order->status ?? '') === 'paid') bg-green-100 text-green-800
                                    @elseif(($purchase->order->status ?? '') === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif(($purchase->order->status ?? '') === 'cancelled') bg-red-100 text-red-800
                                    @elseif(($purchase->order->status ?? '') === 'refunded') bg-gray-100 text-gray-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucwords($purchase->order->status ?? 'N/A') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                        <i class='bx bx-credit-card text-sm text-purple-600'></i>
                                    </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Payment Method</span>
                                </div>
                                <span class="text-base font-medium text-wwc-neutral-900">{{ $purchase->order->formatted_payment_method ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Customer Info</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-user text-sm'></i>
                                    <span>Customer details</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                        <i class='bx bx-user text-sm text-green-600'></i>
                                    </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Name</span>
                                </div>
                                <span class="text-base font-medium text-wwc-neutral-900">{{ $purchase->order->user->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i class='bx bx-envelope text-sm text-blue-600'></i>
                                    </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Email</span>
                                </div>
                                <span class="text-base font-medium text-wwc-neutral-900">{{ $purchase->order->user->email ?? 'N/A' }}</span>
                            </div>
                            @if($purchase->order->user->phone ?? null)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                        <i class='bx bx-phone text-sm text-purple-600'></i>
                                    </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Phone</span>
                                </div>
                                <span class="text-base font-medium text-wwc-neutral-900">{{ $purchase->order->user->phone }}</span>
                            </div>
                            @endif
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
                            <a href="{{ route('admin.orders.show', $purchase->order) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-receipt text-sm mr-2'></i>
                                View Order
                            </a>
                            
                            <a href="{{ route('admin.users.show', $purchase->order->user) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-info text-white text-sm font-semibold rounded-lg hover:bg-wwc-info-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-info transition-colors duration-200">
                                <i class='bx bx-user text-sm mr-2'></i>
                                View Customer
                            </a>
                            
                            <a href="{{ route('admin.events.show', $purchase->event) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-accent text-white text-sm font-semibold rounded-lg hover:bg-wwc-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-accent transition-colors duration-200">
                                <i class='bx bx-calendar text-sm mr-2'></i>
                                View Event
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection