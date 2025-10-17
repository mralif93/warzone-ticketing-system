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
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
            <!-- Total Orders -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $orders->total() }}</div>
                        <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Orders</div>
                        <div class="flex items-center">
                            <div class="flex items-center text-xs text-wwc-success font-semibold">
                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                {{ $orders->where('status', 'Completed')->count() }} Completed
                            </div>
                        </div>
                    </div>
                    <div class="h-12 w-12 rounded-lg bg-red-100 flex items-center justify-center">
                        <i class='bx bx-receipt text-2xl text-red-600'></i>
                    </div>
                </div>
            </div>

            <!-- Completed Orders -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $orders->where('status', 'Completed')->count() }}</div>
                        <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Completed</div>
                        <div class="flex items-center">
                            <div class="flex items-center text-xs text-wwc-success font-semibold">
                                <i class='bx bx-check text-xs mr-1'></i>
                                {{ $orders->where('status', 'Pending')->count() }} Pending
                            </div>
                        </div>
                    </div>
                    <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                        <i class='bx bx-check-circle text-2xl text-green-600'></i>
                    </div>
                </div>
            </div>

            <!-- Pending Orders -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $orders->where('status', 'Pending')->count() }}</div>
                        <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Pending</div>
                        <div class="flex items-center">
                            <div class="flex items-center text-xs text-wwc-error font-semibold">
                                <i class='bx bx-x text-xs mr-1'></i>
                                {{ $orders->where('status', 'Cancelled')->count() }} Cancelled
                            </div>
                        </div>
                    </div>
                    <div class="h-12 w-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                        <i class='bx bx-time text-2xl text-yellow-600'></i>
                    </div>
                </div>
            </div>

            <!-- Total Value -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">RM{{ number_format($orders->where('status', 'Completed')->sum('total_amount'), 0) }}</div>
                        <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Value</div>
                        <div class="flex items-center">
                            <div class="flex items-center text-xs text-wwc-accent font-semibold">
                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                RM{{ number_format($orders->where('status', 'Completed')->avg('total_amount'), 0) }} Avg
                            </div>
                        </div>
                    </div>
                    <div class="h-12 w-12 rounded-lg bg-orange-100 flex items-center justify-center">
                        <i class='bx bx-dollar text-2xl text-orange-600'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
            <div class="px-6 py-4 border-b border-wwc-neutral-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-wwc-neutral-900">Search & Filter Orders</h3>
                    <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                        <i class='bx bx-search text-sm'></i>
                        <span>Find specific orders</span>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('customer.orders') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search Orders</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               placeholder="Order number, email..."
                               class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Status</label>
                        <select name="status" id="status" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
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
                               class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                    </div>
                    <div class="sm:col-span-2 lg:col-span-4 flex justify-end gap-3">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <i class='bx bx-search text-sm mr-2'></i>
                            Search Orders
                        </button>
                        <a href="{{ route('customer.orders') }}" 
                           class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <i class='bx bx-x text-sm mr-2'></i>
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders List -->
        @if($orders->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">All Orders</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-receipt text-sm'></i>
                            <span>Showing {{ $orders->count() }} of {{ $orders->total() }} orders</span>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-wwc-neutral-100">
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
                        <tbody class="bg-white divide-y divide-wwc-neutral-100">
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
                                        <div class="text-sm font-semibold text-wwc-neutral-900">RM{{ number_format($order->total_amount, 0) }}</div>
                                        <div class="text-xs text-wwc-neutral-500">total amount</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            @if($order->status === 'Completed') bg-green-100 text-green-800
                                            @elseif($order->status === 'Pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'Cancelled') bg-red-100 text-red-800
                                            @elseif($order->status === 'Refunded') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $order->status }}
                                    </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-wwc-neutral-900">{{ $order->created_at->format('M j, Y') }}</div>
                                        <div class="text-xs text-wwc-neutral-500">{{ $order->created_at->format('g:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-1">
                                            <a href="{{ route('customer.orders.show', $order) }}" 
                                               class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                               title="View order details">
                                                <i class='bx bx-show text-xs mr-1.5'></i>
                                                View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-wwc-neutral-100">
                    {{ $orders->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="text-center py-12">
                    <div class="mx-auto h-16 w-16 rounded-full bg-wwc-neutral-100 flex items-center justify-center mb-4">
                        <i class='bx bx-receipt text-3xl text-wwc-neutral-400'></i>
                    </div>
                    <h3 class="text-xl font-semibold text-wwc-neutral-900 font-display mb-2">No orders found</h3>
                    <p class="text-sm text-wwc-neutral-600 mb-6">You haven't placed any orders yet or no orders match your filters.</p>
                    <div>
                        <a href="{{ route('customer.events') }}" 
                           class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <i class='bx bx-calendar text-sm mr-2'></i>
                            Browse Events
                        </a>
                        @if(request()->hasAny(['search', 'status', 'date_from']))
                            <a href="{{ route('customer.orders') }}" 
                               class="inline-flex items-center px-6 py-3 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200 ml-3">
                                <i class='bx bx-x text-sm mr-2'></i>
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