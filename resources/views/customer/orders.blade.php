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
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        

        <!-- Search and Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 mb-8">
            <div class="px-6 py-4 border-b border-wwc-neutral-200">
                <h2 class="text-lg font-semibold text-wwc-neutral-900">Search & Filter Orders</h2>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('customer.orders') }}" class="space-y-4">
                    <!-- Form Fields Row -->
                    <div class="flex gap-4 items-end">
                        <div class="flex-1">
                            <label for="search" class="block text-sm font-medium text-wwc-neutral-700 mb-2">Search Orders</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Order number, email..."
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div class="w-1/3">
                            <label for="status" class="block text-sm font-medium text-wwc-neutral-700 mb-2">Status</label>
                            <select name="status" id="status" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Buttons Row -->
                    <div class="flex justify-end gap-3">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary">
                            Search Orders
                        </button>
                        <a href="{{ route('customer.orders') }}" 
                           class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 text-sm font-medium rounded-md text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders List -->
        @if($orders->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900">My Orders</h2>
                        <span class="text-sm text-wwc-neutral-500">Showing {{ $orders->count() }} of {{ $orders->total() }} orders</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-wwc-neutral-200">
                        <thead class="bg-wwc-neutral-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                    Order
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                    Amount
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-center">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                    Date
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-right">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-wwc-neutral-200">
                            @foreach($orders as $order)
                                <tr class="hover:bg-wwc-neutral-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-lg bg-wwc-primary-light flex items-center justify-center">
                                                    <i class='bx bx-receipt text-lg text-wwc-primary'></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-wwc-neutral-900">{{ $order->order_number }}</div>
                                                <div class="text-xs text-wwc-neutral-500">{{ $order->tickets->count() }} tickets</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-wwc-neutral-900">{{ $order->customer_name }}</div>
                                        <div class="text-xs text-wwc-neutral-500">{{ $order->customer_email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-wwc-neutral-900">RM{{ number_format($order->total_amount, 2) }}</div>
                                        <div class="text-xs text-wwc-neutral-500">total amount</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex flex-col items-center space-y-1">
                                            <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                @if($order->status === 'paid') bg-green-100 text-green-800
                                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                                @elseif($order->status === 'refunded') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800
                                            @endif">
                                                {{ ucwords($order->status) }}
                                            </span>
                                            @if($order->status === 'pending')
                                                <span class="inline-flex items-center text-xs text-wwc-accent font-medium">
                                                    <i class='bx bx-time-five mr-1'></i>
                                                    Payment Required
                                                </span>
                                            @elseif($order->status === 'cancelled')
                                                <span class="inline-flex items-center text-xs text-red-600 font-medium">
                                                    <i class='bx bx-x-circle mr-1'></i>
                                                    Expired
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-wwc-neutral-900">{{ $order->created_at->format('M j, Y') }}</div>
                                        <div class="text-xs text-wwc-neutral-500">{{ $order->created_at->format('g:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('customer.orders.show', $order) }}" 
                                               class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                               title="View order details">
                                                <i class='bx bx-show text-xs mr-1.5'></i>
                                                View
                                            </a>
                                            @if($order->status === 'pending')
                                                <a href="{{ route('public.tickets.payment', $order) }}" 
                                                   class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-wwc-accent hover:bg-wwc-accent-dark rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                                   title="Complete payment">
                                                    <i class='bx bx-credit-card text-xs mr-1.5'></i>
                                                    Pay Now
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-wwc-neutral-200">
                    {{ $orders->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-wwc-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-wwc-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-wwc-neutral-900 mb-2">No orders found</h3>
                    <p class="text-sm text-wwc-neutral-500 mb-6">You haven't placed any orders yet or no orders match your filters.</p>
                    <div>
                        @if(request()->hasAny(['search', 'status', 'date_from']))
                            <a href="{{ route('customer.orders') }}" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 text-sm font-medium rounded-md text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary ml-3">
                                Clear Filters
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        </div>
    </div>
</div>
@endsection