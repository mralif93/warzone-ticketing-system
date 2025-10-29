@extends('layouts.customer')

@section('title', 'Customer Dashboard')
@section('description', 'Manage your tickets, orders, and profile in your Warzone World Championship customer portal.')

@section('content')
<!-- Professional Customer Dashboard -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Header Section -->
    <div class="bg-white border-b border-wwc-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display">Welcome back, {{ $user->name }}!</h1>
                    <p class="text-wwc-neutral-600 mt-1">Here's an overview of your account and activities</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-wwc-neutral-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-wwc-primary rounded-lg flex items-center justify-center">
                            <i class='bx bx-receipt text-white text-sm'></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-wwc-neutral-500">Total Orders</p>
                        <p class="text-2xl font-bold text-wwc-neutral-900">{{ $orderStats['total_orders'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-wwc-neutral-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-wwc-accent rounded-lg flex items-center justify-center">
                            <i class='bx bx-purchase-tag text-white text-sm'></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-wwc-neutral-500">Total Tickets</p>
                        <p class="text-2xl font-bold text-wwc-neutral-900">{{ $orderStats['total_tickets'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-wwc-neutral-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-wwc-success rounded-lg flex items-center justify-center">
                            <i class='bx bx-money text-white text-sm'></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-wwc-neutral-500">Total Spent</p>
                        <p class="text-2xl font-bold text-wwc-neutral-900">RM{{ number_format($orderStats['total_spent'], 0) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Tickets Table -->
        <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
            <div class="px-6 py-4 border-b border-wwc-neutral-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-wwc-neutral-900">My Tickets</h2>
                </div>
            </div>
            <div class="overflow-x-auto rounded-b-xl">
                @if($recentTickets->count() > 0)
                    <table class="min-w-full divide-y divide-wwc-neutral-200">
                        <thead class="bg-wwc-neutral-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Ticket Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-wwc-neutral-200">
                            @foreach($recentTickets as $ticket)
                            <tr class="hover:bg-wwc-neutral-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 bg-wwc-primary rounded-lg flex items-center justify-center">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-wwc-neutral-900">{{ $ticket->event->name }}</div>
                                            <div class="text-sm text-wwc-neutral-500">{{ $ticket->event->venue }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-wwc-neutral-900">{{ $ticket->ticketType->name ?? 'General' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($ticket->event_day_name && $ticket->event_day_name !== 'All Days')
                                        @php
                                            // Extract day number from event_day_name (e.g., "Day 1" -> 1)
                                            $dayNumber = null;
                                            if (preg_match('/Day (\d+)/', $ticket->event_day_name, $matches)) {
                                                $dayNumber = (int)$matches[1];
                                            }
                                            
                                            // Get the event days array
                                            $eventDays = $ticket->event->getEventDays();
                                            
                                            // Use the day number to get the correct date, or fallback to first day
                                            $dayIndex = $dayNumber ? $dayNumber - 1 : 0;
                                            $displayDate = isset($eventDays[$dayIndex]) ? $eventDays[$dayIndex]['display'] : ($eventDays[0]['display'] ?? 'TBD');
                                        @endphp
                                        <div class="text-sm font-medium text-wwc-neutral-900">{{ $ticket->event_day_name }}</div>
                                        <div class="text-sm text-wwc-neutral-500">{{ $displayDate }}</div>
                                    @elseif($ticket->event_day)
                                        @php
                                            // Use the event_day field directly and determine which day it is
                                            $eventDays = $ticket->event->getEventDays();
                                            $dayName = 'Event Day';
                                            $displayDate = $ticket->event_day->format('M j, Y');
                                            
                                            // Try to match the date with the event days
                                            foreach ($eventDays as $index => $day) {
                                                if ($day['date'] === $ticket->event_day->format('Y-m-d')) {
                                                    $dayName = $day['day_name'];
                                                    $displayDate = $day['display'];
                                                    break;
                                                }
                                            }
                                        @endphp
                                        <div class="text-sm font-medium text-wwc-neutral-900">{{ $dayName }}</div>
                                        <div class="text-sm text-wwc-neutral-500">{{ $displayDate }}</div>
                                    @else
                                        <div class="text-sm font-medium text-wwc-neutral-900">{{ $ticket->event->getEventDays()[0]['day_name'] }}</div>
                                        <div class="text-sm text-wwc-neutral-500">{{ $ticket->event->getEventDays()[0]['display'] }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-wwc-neutral-900">
                                        @if($ticket->discount_amount > 0)
                                            <div class="flex flex-col">
                                                <span class="line-through text-gray-400">RM{{ number_format($ticket->original_price ?? 0, 2) }}</span>
                                                <span class="text-wwc-success">RM{{ number_format($ticket->price_paid ?? 0, 2) }}</span>
                                                <span class="text-xs text-green-600 font-semibold">Discount: RM{{ number_format($ticket->discount_amount, 2) }}</span>
                                            </div>
                                        @else
                                            RM{{ number_format($ticket->original_price ?? $ticket->price_paid ?? 0, 2) }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($ticket->status === 'active') bg-green-100 text-green-800
                                        @elseif($ticket->status === 'sold') bg-green-100 text-green-800
                                        @elseif($ticket->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($ticket->status === 'scanned') bg-blue-100 text-blue-800
                                        @elseif($ticket->status === 'cancelled') bg-red-100 text-red-800
                                        @elseif($ticket->status === 'refunded') bg-purple-100 text-purple-800
                                        @elseif($ticket->status === 'invalid') bg-gray-100 text-gray-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucwords($ticket->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        @if($ticket->order->status === 'paid' && $ticket->status === 'active' && $ticket->qrcode)
                                        <a href="{{ route('customer.tickets.qr', $ticket->id) }}" 
                                               class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-wwc-accent hover:bg-wwc-accent-dark rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
                                               title="View QR code">
                                                <i class='bx bx-qr-scan text-xs mr-1.5'></i>
                                            QR Code
                                        </a>
                                        @elseif($ticket->order->status === 'pending' && (!$ticket->order->held_until || $ticket->order->held_until->isFuture()))
                                        <a href="{{ route('public.tickets.payment', $ticket->order) }}" 
                                               class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-wwc-success hover:bg-green-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
                                               title="Complete payment for this order">
                                                <i class='bx bx-credit-card text-xs mr-1.5'></i>
                                            Pay Now
                                        </a>
                                        @elseif($ticket->order->status === 'pending' && $ticket->order->held_until && $ticket->order->held_until->isPast())
                                        <span class="inline-flex items-center px-3 py-2 text-xs font-semibold text-red-600 bg-red-50 rounded-lg"
                                              title="Payment session expired">
                                                <i class='bx bx-time-five text-xs mr-1.5'></i>
                                            Expired
                                        </span>
                                    @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    @if($recentTickets->hasPages())
                        <div class="px-6 py-4 border-t border-wwc-neutral-200">
                            {{ $recentTickets->links('vendor.pagination.tailwind') }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-wwc-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-wwc-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-wwc-neutral-900 mb-2">No tickets yet</h3>
                        <p class="text-wwc-neutral-500 mb-6">You haven't purchased any tickets yet. Start exploring our events!</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection