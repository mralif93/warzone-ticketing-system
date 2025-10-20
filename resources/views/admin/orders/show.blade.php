@extends('layouts.admin')

@section('title', 'Order Details - ' . $order->order_number)
@section('page-title', 'Order Details')

@section('content')
<div class="min-h-screen bg-wwc-neutral-50">
    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <div class="flex items-center">
                    <a href="{{ route('admin.orders.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Orders
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Order Details -->
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Order Details</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-info-circle text-sm'></i>
                                    <span>Order information</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Order Number -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-receipt text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Order Number</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $order->order_number }}</span>
                                    </div>
                                </div>

                                <!-- Customer -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-user text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Customer</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $order->user->name ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-check-circle text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Status</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($order->status === 'paid') bg-green-100 text-green-800
                                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @elseif($order->status === 'refunded') bg-gray-100 text-gray-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucwords($order->status) }}
                                        </span>
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
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $order->payment_method ? ucwords(str_replace('_', ' ', $order->payment_method)) : 'N/A' }}</span>
                                    </div>
                                </div>

                                <!-- Order Date -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                            <i class='bx bx-calendar text-sm text-indigo-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Order Date</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $order->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                <!-- Total Amount -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-wwc-accent/20 flex items-center justify-center">
                                            <i class='bx bx-dollar text-sm text-wwc-accent'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Total Amount</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                </div>

                                <!-- Notes -->
                                @if($order->notes)
                                <div class="flex items-center py-3">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <i class='bx bx-file-text text-sm text-gray-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Notes</span>
                                        <p class="text-base font-medium text-wwc-neutral-900 mt-1">{{ $order->notes }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tickets -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mt-6">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Tickets</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-ticket text-sm'></i>
                                    <span>{{ $order->purchaseTickets->count() }} tickets</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($order->purchaseTickets->count() > 0)
                                <div class="space-y-4">
                                    @foreach($order->purchaseTickets as $ticket)
                                    <div class="bg-wwc-neutral-50 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center space-x-3">
                                                <div class="h-8 w-8 rounded-lg bg-wwc-primary flex items-center justify-center">
                                                    <i class='bx bx-ticket text-sm text-white'></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-semibold text-wwc-neutral-900">{{ $ticket->ticketType->name ?? 'N/A' }}</h4>
                                                    <p class="text-xs text-wwc-neutral-600">{{ $ticket->event->name ?? 'N/A' }}</p>
                                                    <p class="text-xs text-wwc-neutral-500">{{ $ticket->event->getFormattedDateRange() ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-bold text-wwc-neutral-900">RM{{ number_format($ticket->price_paid, 0) }}</p>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                                    @if($ticket->status === 'sold') bg-green-100 text-green-800
                                                    @elseif($ticket->status === 'scanned') bg-blue-100 text-blue-800
                                                    @elseif($ticket->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($ticket->status === 'cancelled') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucwords($ticket->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        @if($ticket->is_combo_purchase)
                                        <div class="flex items-center justify-center">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-wwc-accent/20 text-wwc-accent border border-wwc-accent/30">
                                                <i class='bx bx-gift text-xs mr-1'></i>
                                                Combo Purchase
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="h-12 w-12 rounded-full bg-wwc-neutral-100 flex items-center justify-center mx-auto mb-3">
                                        <i class='bx bx-ticket text-xl text-wwc-neutral-400'></i>
                                    </div>
                                    <h4 class="text-sm font-semibold text-wwc-neutral-900 mb-1">No Tickets</h4>
                                    <p class="text-xs text-wwc-neutral-600">This order doesn't have any tickets.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Order Summary -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Order Summary</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-calculator text-sm'></i>
                                    <span>Price breakdown</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-wwc-neutral-600">Subtotal</span>
                                <span class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-wwc-neutral-600">Service Fee</span>
                                <span class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($order->service_fee, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-wwc-neutral-600">Tax</span>
                                <span class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($order->tax, 2) }}</span>
                            </div>
                            @if($order->discount > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-wwc-neutral-600">Discount</span>
                                <span class="text-base font-medium text-wwc-success">-RM{{ number_format($order->discount, 2) }}</span>
                            </div>
                            @endif
                            <div class="border-t border-wwc-neutral-200 pt-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-wwc-neutral-900">Total</span>
                                    <span class="text-xl font-bold text-wwc-neutral-900">RM{{ number_format($order->total_amount, 2) }}</span>
                                </div>
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
                                <span class="text-base font-medium text-wwc-neutral-900">{{ $order->user->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i class='bx bx-envelope text-sm text-blue-600'></i>
                                    </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Email</span>
                                </div>
                                <span class="text-base font-medium text-wwc-neutral-900">{{ $order->user->email ?? 'N/A' }}</span>
                            </div>
                            @if($order->user->phone ?? null)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                        <i class='bx bx-phone text-sm text-purple-600'></i>
                                    </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Phone</span>
                                </div>
                                <span class="text-base font-medium text-wwc-neutral-900">{{ $order->user->phone }}</span>
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
                            <a href="{{ route('admin.orders.edit', $order) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-edit text-sm mr-2'></i>
                                Edit Order
                            </a>
                            
                            <a href="{{ route('admin.users.show', $order->user) }}" 
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