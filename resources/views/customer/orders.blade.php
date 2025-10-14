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
                        <i class='bx bx-calendar mr-2'></i>
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
                            <i class='bx bx-search mr-2'></i>
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
                        <a href="{{ route('customer.orders.show', $order) }}" class="block">
                            <div class="bg-white border border-wwc-neutral-200 rounded-xl shadow-sm hover:shadow-md hover:border-wwc-primary transition-all duration-200 overflow-hidden cursor-pointer group">
                                <!-- Order Header -->
                                <div class="px-6 py-5 bg-wwc-neutral-50 border-b border-wwc-neutral-200">
                                    <div class="flex items-center justify-between">
                                        <!-- Left Section: Order Info -->
                                        <div class="flex items-center space-x-4">
                                            <!-- Order Icon -->
                                            <div class="flex-shrink-0">
                                                <div class="h-12 w-12 rounded-lg bg-wwc-accent flex items-center justify-center group-hover:bg-wwc-accent-dark transition-colors duration-200">
                                                    <i class='bx bx-receipt text-white text-xl'></i>
                                                </div>
                                            </div>
                                            
                                            <!-- Order Details -->
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-lg font-semibold text-wwc-neutral-900 group-hover:text-wwc-primary transition-colors duration-200">Order #{{ $order->order_number }}</h3>
                                                <div class="flex flex-wrap gap-4 mt-2">
                                                    <div class="flex items-center text-sm text-wwc-neutral-600">
                                                        <i class='bx bx-calendar mr-2 text-wwc-neutral-400'></i>
                                                        {{ $order->created_at->format('M j, Y \a\t g:i A') }}
                                                    </div>
                                                    <div class="flex items-center text-sm text-wwc-neutral-600">
                                                        <i class='bx bx-envelope mr-2 text-wwc-neutral-400'></i>
                                                        {{ $order->customer_email }}
                                                    </div>
                                                    <div class="flex items-center text-sm text-wwc-neutral-600">
                                                        <i class='bx bx-purchase-tag mr-2 text-wwc-neutral-400'></i>
                                                        {{ $order->tickets->count() }} ticket(s)
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Right Section: Total & Status -->
                                        <div class="text-right flex-shrink-0">
                                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-2">RM {{ number_format($order->total_amount, 0) }}</div>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
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

                                <!-- Order Content -->
                                <div class="p-6">
                                    <!-- Order Items -->
                                    <div class="mb-6">
                                        <h4 class="text-sm font-semibold text-wwc-neutral-900 mb-4 flex items-center">
                                            <i class='bx bx-package mr-2 text-wwc-neutral-400'></i>
                                            Order Items
                                        </h4>
                                        <div class="space-y-3">
                                            @foreach($order->tickets as $ticket)
                                            <div class="flex items-center justify-between p-4 bg-wwc-neutral-50 rounded-lg border border-wwc-neutral-200">
                                                <div class="flex items-center space-x-4">
                                                <div class="h-10 w-10 rounded-lg bg-wwc-primary flex items-center justify-center">
                                                    <i class='bx bx-purchase-tag text-white text-lg'></i>
                                                </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-wwc-neutral-900">{{ $ticket->event->name }}</p>
                                                        <p class="text-xs text-wwc-neutral-500 mt-1">{{ $ticket->ticket_identifier }}</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm font-semibold text-wwc-neutral-900">RM{{ number_format($ticket->price_paid, 0) }}</p>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold mt-1
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
                                    <div class="border-t border-wwc-neutral-200 pt-4">
                                        <div class="text-sm text-wwc-neutral-600 space-y-2">
                                            <div class="flex justify-between items-center">
                                                <span>Subtotal:</span>
                                                <span class="font-medium">RM{{ number_format($order->subtotal, 0) }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span>Service Fee:</span>
                                                <span class="font-medium">RM{{ number_format($order->service_fee, 0) }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span>Tax:</span>
                                                <span class="font-medium">RM{{ number_format($order->tax_amount, 0) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
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
                        <i class='bx bx-receipt text-4xl text-wwc-neutral-400'></i>
                    </div>
                    <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">No orders found</h3>
                    <p class="text-sm text-wwc-neutral-600 mb-6">You haven't placed any orders yet or no orders match your filters.</p>
                    <div class="space-x-4">
                        <a href="{{ route('customer.events') }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-lg transition-colors duration-200">
                            <i class='bx bx-calendar mr-2'></i>
                            Browse Events
                        </a>
                        @if(request()->hasAny(['search', 'status', 'date_from']))
                            <a href="{{ route('customer.orders') }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-semibold text-wwc-neutral-600 hover:bg-wwc-neutral-100 rounded-lg transition-colors duration-200">
                                <i class='bx bx-refresh mr-2'></i>
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