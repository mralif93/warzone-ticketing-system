@extends('layouts.customer')

@section('title', 'Order History')
@section('description', 'View and track your ticket orders in your Warzone World Championship customer portal.')

@section('content')
<!-- Professional Order History -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Header Section -->
    <div class="bg-white border-b border-wwc-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display">Order History</h1>
                    <p class="text-wwc-neutral-600 mt-1">View and track your ticket orders</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('customer.events') }}" 
                       class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white rounded-lg text-sm font-medium hover:bg-wwc-primary-dark transition-colors duration-200">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Browse Events
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Search and Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 mb-8">
            <div class="px-6 py-4 border-b border-wwc-neutral-200">
                <h2 class="text-lg font-semibold text-wwc-neutral-900">Search & Filter Orders</h2>
                <p class="text-wwc-neutral-600 text-sm mt-1">Find specific orders using the filters below</p>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('customer.orders') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search Orders</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               placeholder="Order number, email..."
                               class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Status</label>
                        <select name="status" id="status" class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                            <option value="">All Status</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="Refunded" {{ request('status') == 'Refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <div>
                        <label for="date_from" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">From Date</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                               class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filter Orders
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders List -->
        <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-wwc-neutral-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-wwc-neutral-900">All Orders</h2>
                        <p class="text-wwc-neutral-600 text-sm mt-1">Showing {{ $orders->count() }} of {{ $orders->total() }} orders</p>
                    </div>
                </div>
            </div>
            
            @if($orders->count() > 0)
                <div class="p-6">
                    <div class="space-y-6">
                        @foreach($orders as $order)
                        <div class="bg-wwc-neutral-50 border border-wwc-neutral-200 rounded-lg hover:bg-wwc-neutral-100 transition-colors duration-200 overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-4">
                                        <!-- Order Icon -->
                                        <div class="flex-shrink-0">
                                            <div class="h-12 w-12 rounded-lg bg-wwc-accent flex items-center justify-center">
                                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        
                                        <!-- Order Details -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-semibold text-wwc-neutral-900">Order #{{ $order->order_number }}</h3>
                                            <div class="space-y-1 mt-1">
                                                <p class="text-sm text-wwc-neutral-600">
                                                    <svg class="h-4 w-4 inline mr-2 text-wwc-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ $order->created_at->format('M j, Y \a\t g:i A') }}
                                                </p>
                                                <p class="text-sm text-wwc-neutral-600">
                                                    <svg class="h-4 w-4 inline mr-2 text-wwc-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ $order->customer_email }}
                                                </p>
                                                <p class="text-sm text-wwc-neutral-600">
                                                    <svg class="h-4 w-4 inline mr-2 text-wwc-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                                    </svg>
                                                    {{ $order->tickets->count() }} ticket(s)
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <!-- Order Total and Status -->
                                        <div class="text-right flex-shrink-0">
                                            <div class="text-xl font-bold text-wwc-neutral-900 mb-2">${{ number_format($order->total_amount, 2) }}</div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                @if($order->status === 'Completed') bg-wwc-success text-white
                                                @elseif($order->status === 'Pending') bg-wwc-warning text-white
                                                @elseif($order->status === 'Cancelled') bg-wwc-error text-white
                                                @elseif($order->status === 'Refunded') bg-wwc-info text-white
                                                @else bg-wwc-neutral-200 text-wwc-neutral-800
                                                @endif">
                                                {{ $order->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Order Items -->
                                <div class="border-t border-wwc-neutral-200 pt-4">
                                    <h4 class="text-sm font-semibold text-wwc-neutral-900 mb-3">Order Items</h4>
                                    <div class="space-y-2">
                                        @foreach($order->tickets as $ticket)
                                        <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <div class="h-8 w-8 rounded-lg bg-wwc-primary flex items-center justify-center">
                                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-wwc-neutral-900">{{ $ticket->event->name }}</p>
                                                    <p class="text-xs text-wwc-neutral-500">{{ $ticket->seat_identifier }} • {{ $ticket->seat->price_zone }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-wwc-neutral-900">${{ number_format($ticket->price_paid, 2) }}</p>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                                    @if($ticket->status === 'Sold') bg-wwc-success text-white
                                                    @elseif($ticket->status === 'Held') bg-wwc-warning text-white
                                                    @elseif($ticket->status === 'Cancelled') bg-wwc-error text-white
                                                    @elseif($ticket->status === 'Used') bg-wwc-info text-white
                                                    @else bg-wwc-neutral-200 text-wwc-neutral-800
                                                    @endif">
                                                    {{ $ticket->status }}
                                                </span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Order Summary -->
                                <div class="border-t border-wwc-neutral-200 pt-4 mt-4">
                                    <div class="flex justify-between items-center">
                                        <div class="text-sm text-wwc-neutral-600">
                                            <p>Subtotal: ${{ number_format($order->subtotal, 2) }}</p>
                                            <p>Service Fee: ${{ number_format($order->service_fee, 2) }}</p>
                                            <p>Tax: ${{ number_format($order->tax_amount, 2) }}</p>
                                        </div>
                                        <div class="text-right">
                                            <a href="{{ route('customer.orders.show', $order) }}" 
                                               class="inline-flex items-center px-4 py-2 text-sm font-semibold text-wwc-primary hover:bg-wwc-primary-light hover:text-wwc-primary-dark rounded-lg transition-colors duration-200">
                                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8 pt-6 border-t border-wwc-neutral-200">
                        {{ $orders->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto h-16 w-16 rounded-lg bg-wwc-neutral-100 flex items-center justify-center mb-4">
                        <svg class="h-8 w-8 text-wwc-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-wwc-neutral-900 mb-2">No orders found</h3>
                    <p class="text-sm text-wwc-neutral-600 mb-6">You haven't placed any orders yet or no orders match your filters.</p>
                    <div class="space-x-4">
                        <a href="{{ route('customer.events') }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-lg transition-colors duration-200">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Browse Events
                        </a>
                        @if(request()->hasAny(['search', 'status', 'date_from']))
                            <a href="{{ route('customer.orders') }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-semibold text-wwc-neutral-600 hover:bg-wwc-neutral-100 rounded-lg transition-colors duration-200">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Clear Filters
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection