@extends('layouts.admin')
@section('page-title', 'Order Management')
@section('title', 'Order Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('admin.orders') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            Order #{{ $order->id }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Order details and ticket information
                        </p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($order->status === 'Completed') bg-green-100 text-green-800
                        @elseif($order->status === 'Pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'Cancelled') bg-red-100 text-red-800
                        @elseif($order->status === 'Refunded') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $order->status }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Order Information -->
            <div class="lg:col-span-2">
                <!-- Customer Information -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Customer Name</dt>
                                <dd class="text-sm text-gray-900">{{ $order->user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900">{{ $order->user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="text-sm text-gray-900">{{ $order->user->phone_number ?: 'Not provided' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Role</dt>
                                <dd class="text-sm text-gray-900">{{ $order->user->role }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Details</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Order ID</dt>
                                <dd class="text-sm text-gray-900">#{{ $order->id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                                <dd class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                                <dd class="text-sm text-gray-900">{{ $order->payment_method }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                                <dd class="text-sm text-gray-900 font-medium">${{ number_format($order->total_amount, 2) }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tickets -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Tickets ({{ $order->tickets->count() }})</h3>
                        @if($order->tickets->count() > 0)
                            <div class="space-y-4">
                                @foreach($order->tickets as $ticket)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-sm font-medium text-gray-900">{{ $ticket->event->title }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        Seat: {{ $ticket->seat->row }}{{ $ticket->seat->number }} 
                                                        ({{ $ticket->seat->price_zone }})
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $ticket->event->event_date->format('M d, Y g:i A') }}
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-sm font-medium text-gray-900">${{ number_format($ticket->price_paid, 2) }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                            @if($ticket->status === 'Sold') bg-green-100 text-green-800
                                                            @elseif($ticket->status === 'Held') bg-yellow-100 text-yellow-800
                                                            @elseif($ticket->status === 'Cancelled') bg-red-100 text-red-800
                                                            @else bg-gray-100 text-gray-800
                                                            @endif">
                                                            {{ $ticket->status }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('admin.tickets.show', $ticket) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                                View Ticket
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-500">No tickets found for this order</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <!-- Order Summary -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Subtotal</dt>
                                <dd class="text-sm text-gray-900">${{ number_format($order->total_amount, 2) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Tax</dt>
                                <dd class="text-sm text-gray-900">$0.00</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Processing Fee</dt>
                                <dd class="text-sm text-gray-900">$0.00</dd>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between">
                                    <dt class="text-base font-medium text-gray-900">Total</dt>
                                    <dd class="text-base font-medium text-gray-900">${{ number_format($order->total_amount, 2) }}</dd>
                                </div>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Payment Information -->
                @if($order->payments->count() > 0)
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
                        @foreach($order->payments as $payment)
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Payment ID</dt>
                                <dd class="text-sm text-gray-900">#{{ $payment->id }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Amount</dt>
                                <dd class="text-sm text-gray-900">${{ number_format($payment->amount, 2) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Status</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($payment->status === 'Completed') bg-green-100 text-green-800
                                        @elseif($payment->status === 'Pending') bg-yellow-100 text-yellow-800
                                        @elseif($payment->status === 'Failed') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $payment->status }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Date</dt>
                                <dd class="text-sm text-gray-900">{{ $payment->created_at->format('M d, Y g:i A') }}</dd>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Order Actions -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.users.show', $order->user) }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                View Customer
                            </a>
                            <a href="{{ route('admin.tickets') }}?order={{ $order->id }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                View All Tickets
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
