@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center">
                <a href="{{ route('public.tickets.my-tickets') }}" class="text-gray-400 hover:text-gray-600 mr-4">
                    <i class='bx bx-chevron-left text-2xl'></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Ticket Details</h1>
                    <p class="mt-1 text-sm text-gray-600">Ticket #{{ $ticket->id }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Ticket Card -->
                <div>
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <!-- Ticket Header -->
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-xl font-bold text-white">{{ $ticket->event->name }}</h2>
                                    <p class="text-indigo-100">{{ $ticket->event->venue }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-white">RM{{ number_format($ticket->price_paid, 0) }}</div>
                                    <div class="text-indigo-100 text-sm">Ticket #{{ $ticket->id }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Ticket Body -->
                        <div class="px-6 py-6">
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Date & Time</h3>
                                    <p class="text-lg font-semibold text-gray-900">{{ $ticket->event->date_time->format('M j, Y') }}</p>
                                    <p class="text-sm text-gray-600">{{ $ticket->event->date_time->format('g:i A') }}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Seat</h3>
                                    <p class="text-lg font-semibold text-gray-900">{{ $ticket->seat_identifier }}</p>
                                    <p class="text-sm text-gray-600">{{ $ticket->seat->price_zone }} • {{ $ticket->seat->seat_type }}</p>
                                </div>
                            </div>

                            <!-- QR Code Placeholder -->
                            <div class="text-center mb-6">
                                <div class="inline-block bg-gray-100 p-4 rounded-lg">
                                    <div class="w-32 h-32 bg-white border-2 border-gray-300 rounded-lg flex items-center justify-center">
                                        <div class="text-center">
                                            <i class='bx bx-qr-scan text-4xl text-gray-400 mx-auto'></i>
                                            <p class="text-xs text-gray-500 mt-2">QR Code</p>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">{{ $ticket->qrcode }}</p>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="text-center">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                                    @if($ticket->status === 'Sold') bg-green-100 text-green-800
                                    @elseif($ticket->status === 'Held') bg-yellow-100 text-yellow-800
                                    @elseif($ticket->status === 'Scanned') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @if($ticket->status === 'Sold')
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Valid Ticket
                                    @elseif($ticket->status === 'Held')
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Pending Payment
                                    @elseif($ticket->status === 'Scanned')
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Used
                                    @else
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Invalid
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Information -->
                <div>
                    <div class="bg-white shadow rounded-lg mb-6">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Order Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Order Number</dt>
                                    <dd class="text-sm text-gray-900 font-mono">{{ $ticket->order->order_number }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Purchase Date</dt>
                                    <dd class="text-sm text-gray-900">{{ $ticket->order->created_at->format('M j, Y \a\t g:i A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Customer</dt>
                                    <dd class="text-sm text-gray-900">{{ $ticket->order->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm text-gray-900">{{ $ticket->order->customer_email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Paid</dt>
                                    <dd class="text-sm text-gray-900 font-bold">RM{{ number_format($ticket->order->total_amount, 0) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Important Information -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class='bx bx-info-circle text-lg text-blue-400'></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Important Information</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Present this ticket at the venue entrance</li>
                                        <li>Arrive 30 minutes before the event starts</li>
                                        <li>Bring a valid photo ID</li>
                                        <li>QR code will be scanned for entry</li>
                                        <li>No refunds or exchanges after purchase</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-center space-x-4">
                <a href="{{ route('public.tickets.my-tickets') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition duration-150 ease-in-out">
                    Back to My Tickets
                </a>
                <a href="{{ route('events.index') }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition duration-150 ease-in-out">
                    Browse More Events
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
