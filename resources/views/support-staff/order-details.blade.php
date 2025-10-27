@extends('layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navigation Header -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('support-staff.dashboard') }}" class="flex items-center text-gray-600 hover:text-red-600 transition-colors duration-200">
                        <div class="h-9 w-9 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-red-50">
                            <i class="bx bx-chevron-left text-lg"></i>
                        </div>
                    </a>
                    <div class="ml-4">
                        <h1 class="text-xl font-semibold text-gray-900">Order Details</h1>
                        <p class="text-gray-500 text-sm">Order #{{ $order->order_number }}</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Summary Card -->
                <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-white">Order Summary</h2>
                                <p class="text-red-100 text-sm mt-1">{{ $order->order_number }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-red-100 text-sm">Total Amount</p>
                                <p class="text-2xl font-bold text-white">RM{{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class='bx bx-hash text-blue-600 text-xl'></i>
                                        </div>
                                        <label class="text-sm font-semibold text-gray-700">Order Number</label>
                                    </div>
                                </div>
                                <p class="text-lg font-mono text-gray-900">{{ $order->order_number }}</p>
                            </div>
                            
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class='bx bx-calendar text-purple-600 text-xl'></i>
                                        </div>
                                        <label class="text-sm font-semibold text-gray-700">Order Date</label>
                                    </div>
                                </div>
                                <p class="text-lg font-medium text-gray-900">{{ $order->created_at->format('M j, Y g:i A') }}</p>
                            </div>

                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class='bx bx-purchase-tag text-yellow-600 text-xl'></i>
                                        </div>
                                        <label class="text-sm font-semibold text-gray-700">Total Tickets</label>
                                    </div>
                                </div>
                                <p class="text-lg font-medium text-gray-900">{{ $order->tickets ? $order->tickets->count() : 0 }}</p>
                            </div>

                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class='bx bx-check-circle text-green-600 text-xl'></i>
                                        </div>
                                        <label class="text-sm font-semibold text-gray-700">Status</label>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold
                                    @if($order->status === 'paid') bg-green-100 text-green-800
                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @elseif($order->status === 'refunded') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucwords($order->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class='bx bx-user-circle text-red-600 mr-2 text-xl'></i>
                            Customer Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Full Name</label>
                                <p class="text-base text-gray-900 font-medium">{{ $order->user->name ?? $order->customer_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Email Address</label>
                                <p class="text-base text-gray-900 font-medium">{{ $order->customer_email }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Phone Number</label>
                                <p class="text-base text-gray-900 font-medium">{{ $order->customer_phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ticket Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class='bx bx-ticket text-red-600 mr-2 text-xl'></i>
                            Ticket Details ({{ $order->tickets ? $order->tickets->count() : 0 }})
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($order->tickets && $order->tickets->count() > 0)
                            <div class="space-y-4">
                                @foreach($order->tickets as $ticket)
                                <div class="bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-xl p-5 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <h4 class="text-lg font-bold text-gray-900">{{ $ticket->ticketType->name ?? 'General' }}</h4>
                                                <span class="ml-3 px-3 py-1 text-xs font-semibold rounded-full
                                                    @if($ticket->status === 'active' || $ticket->status === 'sold') bg-red-100 text-red-800
                                                    @elseif($ticket->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($ticket->status === 'used' || $ticket->status === 'scanned') bg-green-100 text-green-800
                                                    @elseif($ticket->status === 'cancelled') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucwords($ticket->status) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-1">Event: {{ $ticket->event->name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-2xl font-bold text-gray-900">RM{{ number_format($ticket->price_paid, 2) }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">QR Code ID</label>
                                            <p class="text-sm font-mono text-gray-900 break-all">{{ $ticket->qrcode }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Zone</label>
                                            <p class="text-sm text-gray-900">{{ $ticket->zone }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class='bx bx-ticket text-6xl text-gray-300 mb-4'></i>
                                <p class="text-gray-500">No tickets found for this order</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-24">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-red-100">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class='bx bx-cog text-red-600 mr-2'></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <form action="{{ route('support-staff.orders.refund', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to process a refund?')">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class='bx bx-refund text-lg mr-2'></i>
                                Process Refund
                            </button>
                        </form>
                        
                        <button class="w-full flex items-center justify-center px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold transition-all duration-200">
                            <i class='bx bx-printer text-lg mr-2'></i>
                            Print Receipt
                        </button>

                        <a href="{{ route('support-staff.scanner') }}" 
                           class="w-full flex items-center justify-center px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg font-semibold transition-all duration-200 border border-blue-200">
                            <i class='bx bx-search text-lg mr-2'></i>
                            Search Ticket
                        </a>

                        <div class="pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-500 text-center">View this order in detail for management actions</p>
                        </div>
                    </div>
                </div>

                <!-- Pricing Breakdown -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Pricing Breakdown</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Subtotal</span>
                                <span class="text-sm font-medium text-gray-900">RM{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Discount</span>
                                <span class="text-sm font-medium text-green-600">-RM{{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Service Fee <span class="text-gray-400">({{ number_format($serviceFeePercentage, 1) }}%)</span></span>
                                <span class="text-sm font-medium text-gray-900">RM{{ number_format($order->service_fee, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Tax <span class="text-gray-400">({{ number_format($taxPercentage, 1) }}%)</span></span>
                                <span class="text-sm font-medium text-gray-900">RM{{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                            <div class="pt-3 border-t border-gray-200 flex justify-between">
                                <span class="text-base font-bold text-gray-900">Total</span>
                                <span class="text-lg font-bold text-red-600">RM{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
