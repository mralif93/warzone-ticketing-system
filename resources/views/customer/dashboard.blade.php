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
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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

            <div class="bg-white rounded-xl border border-wwc-neutral-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-wwc-info rounded-lg flex items-center justify-center">
                            <i class='bx bx-calendar text-white text-sm'></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-wwc-neutral-500">Upcoming Events</p>
                        <p class="text-2xl font-bold text-wwc-neutral-900">{{ $orderStats['upcoming_events'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        @if($upcomingEvents->count() > 0)
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-wwc-neutral-900 mb-4">Upcoming Events</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($upcomingEvents as $event)
                <div class="bg-white rounded-xl border border-wwc-neutral-200 overflow-hidden hover:shadow-md transition-all duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-wwc-primary rounded-lg flex items-center justify-center">
                                <i class='bx bx-calendar text-white text-lg'></i>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-wwc-success text-white">
                                On Sale
                            </span>
                        </div>
                        <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">{{ $event->name }}</h3>
                        <p class="text-sm text-wwc-neutral-600 mb-4">{{ $event->venue }}</p>
                        <div class="flex items-center text-sm text-wwc-neutral-500">
                            <i class='bx bx-time-five mr-2'></i>
                            {{ $event->date_time->format('M j, Y g:i A') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- My Tickets Table -->
        <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
            <div class="px-6 py-4 border-b border-wwc-neutral-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-wwc-neutral-900">My Tickets</h2>
                </div>
            </div>
            <div class="overflow-x-auto">
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
                                    <div class="text-sm text-wwc-neutral-900">{{ $ticket->event->date_time->format('M j, Y') }}</div>
                                    <div class="text-sm text-wwc-neutral-500">{{ $ticket->event->date_time->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-wwc-neutral-900">RM{{ number_format($ticket->price_paid, 0) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($ticket->status === 'sold') bg-green-100 text-green-800
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
                                    <a href="{{ route('customer.tickets.show', $ticket->id) }}" 
                                           class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
                                           title="View ticket details">
                                            <i class='bx bx-show text-xs mr-1.5'></i>
                                        View
                                    </a>
                                        @if($ticket->qrcode)
                                        <a href="{{ route('customer.tickets.qr', $ticket->id) }}" 
                                               class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-wwc-accent hover:bg-wwc-accent-dark rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
                                               title="View QR code">
                                                <i class='bx bx-qr-scan text-xs mr-1.5'></i>
                                            QR Code
                                        </a>
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