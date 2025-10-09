@extends('layouts.admin')

@section('title', 'Ticket Details')
@section('page-title', 'Ticket Details')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('admin.tickets') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            Ticket #{{ $ticket->id }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Ticket details and admittance information
                        </p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($ticket->status === 'Sold') bg-green-100 text-green-800
                        @elseif($ticket->status === 'Held') bg-yellow-100 text-yellow-800
                        @elseif($ticket->status === 'Cancelled') bg-red-100 text-red-800
                        @elseif($ticket->status === 'Used') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $ticket->status }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Ticket Information -->
            <div class="lg:col-span-2">
                <!-- Event Information -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Event Information</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Event Title</dt>
                                <dd class="text-sm text-gray-900">{{ $ticket->event->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Event Date</dt>
                                <dd class="text-sm text-gray-900">{{ $ticket->event->date_time->format('M d, Y g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Venue</dt>
                                <dd class="text-sm text-gray-900">{{ $ticket->event->venue }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($ticket->event->status === 'On Sale') bg-green-100 text-green-800
                                        @elseif($ticket->event->status === 'Sold Out') bg-red-100 text-red-800
                                        @elseif($ticket->event->status === 'Cancelled') bg-gray-100 text-gray-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ $ticket->event->status }}
                                    </span>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seat Information -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Seat Information</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Seat Number</dt>
                                <dd class="text-sm text-gray-900">{{ $ticket->seat->row }}{{ $ticket->seat->number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Price Zone</dt>
                                <dd class="text-sm text-gray-900">{{ $ticket->seat->price_zone }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Section</dt>
                                <dd class="text-sm text-gray-900">{{ $ticket->seat->section }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Price Paid</dt>
                                <dd class="text-sm text-gray-900 font-medium">${{ number_format($ticket->price_paid, 2) }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                @if($ticket->order && $ticket->order->user)
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 h-12 w-12">
                                <div class="h-12 w-12 rounded-full bg-indigo-500 flex items-center justify-center">
                                    <span class="text-lg font-medium text-white">
                                        {{ substr($ticket->order->user->name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900">{{ $ticket->order->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $ticket->order->user->email }}</div>
                                <div class="text-sm text-gray-500">{{ $ticket->order->user->phone_number ?: 'No phone provided' }}</div>
                            </div>
                            <div>
                                <a href="{{ route('admin.users.show', $ticket->order->user) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    View Customer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Admittance Logs -->
                @if($ticket->admittanceLogs->count() > 0)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Admittance History</h3>
                        <div class="space-y-3">
                            @foreach($ticket->admittanceLogs as $log)
                            <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-b-0">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $log->scan_result }} - Gate {{ $log->gate_id }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $log->scan_time->format('M d, Y g:i A') }}</div>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $log->scan_time->diffForHumans() }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Ticket Summary -->
            <div class="lg:col-span-1">
                <!-- QR Code -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">QR Code</h3>
                        <div class="text-center">
                            <div class="inline-block p-4 bg-gray-100 rounded-lg">
                                <div class="text-xs font-mono text-gray-600 break-all">
                                    {{ $ticket->qrcode }}
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">Scan this code for admittance</p>
                        </div>
                    </div>
                </div>

                <!-- Ticket Details -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Ticket Details</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Ticket ID</dt>
                                <dd class="text-sm text-gray-900">#{{ $ticket->id }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Price Paid</dt>
                                <dd class="text-sm text-gray-900">${{ number_format($ticket->price_paid, 2) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Purchase Date</dt>
                                <dd class="text-sm text-gray-900">{{ $ticket->created_at->format('M d, Y') }}</dd>
                            </div>
                            @if($ticket->hold_until)
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Hold Until</dt>
                                <dd class="text-sm text-gray-900">{{ $ticket->hold_until->format('M d, Y g:i A') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Order Information -->
                @if($ticket->order)
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Information</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Order ID</dt>
                                <dd class="text-sm text-gray-900">#{{ $ticket->order->id }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Order Status</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($ticket->order->status === 'Completed') bg-green-100 text-green-800
                                        @elseif($ticket->order->status === 'Pending') bg-yellow-100 text-yellow-800
                                        @elseif($ticket->order->status === 'Cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $ticket->order->status }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Total Amount</dt>
                                <dd class="text-sm text-gray-900">${{ number_format($ticket->order->total_amount, 2) }}</dd>
                            </div>
                        </dl>
                        <div class="mt-4">
                            <a href="{{ route('admin.orders.show', $ticket->order) }}" 
                               class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                View Full Order →
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                        <div class="space-y-3">
                            @if($ticket->order)
                                <a href="{{ route('admin.orders.show', $ticket->order) }}" 
                                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    View Order
                                </a>
                            @endif
                            @if($ticket->order && $ticket->order->user)
                                <a href="{{ route('admin.users.show', $ticket->order->user) }}" 
                                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    View Customer
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
