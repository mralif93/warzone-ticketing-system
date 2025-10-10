@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-lg mx-4 mt-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <i class='bx bx-check text-2xl text-green-600'></i>
                </div>
                <h1 class="mt-4 text-3xl font-bold text-gray-900">Purchase Successful!</h1>
                <p class="mt-2 text-lg text-gray-600">Your tickets have been confirmed</p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Order Details -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Order Details</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Order Number</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $order->order_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Purchase Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('M j, Y \a\t g:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Event</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->tickets->first()->event->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Event Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->tickets->first()->event->date_time->format('M j, Y \a\t g:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-bold">RM{{ number_format($order->total_amount, 0) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $order->status }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Tickets -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Your Tickets</h3>
                    <div class="space-y-4">
                        @foreach($order->tickets as $ticket)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="h-16 w-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                    <i class='bx bx-receipt text-3xl text-gray-400'></i>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-lg font-medium text-gray-900">{{ $ticket->seat_identifier }}</h4>
                                                <p class="text-sm text-gray-500">{{ $ticket->seat->price_zone }} • {{ $ticket->seat->seat_type }}</p>
                                                <p class="text-sm text-gray-500">QR Code: {{ $ticket->qrcode }}</p>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-lg font-bold text-indigo-600">RM{{ number_format($ticket->price_paid, 0) }}</div>
                                                <div class="text-sm text-gray-500">Ticket #{{ $ticket->id }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Important Information -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class='bx bx-info-circle text-lg text-blue-400'></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Important Information</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Your tickets have been sent to your email address</li>
                                <li>Please arrive at the venue 30 minutes before the event starts</li>
                                <li>Bring a valid ID and the confirmation email</li>
                                <li>QR codes will be scanned at the entrance</li>
                                <li>No refunds or exchanges after purchase</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4">
                <a href="{{ route('public.tickets.my-tickets') }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition duration-150 ease-in-out">
                    View My Tickets
                </a>
                <a href="{{ route('events.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition duration-150 ease-in-out">
                    Browse More Events
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
