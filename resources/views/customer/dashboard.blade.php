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
        

        <!-- Quick Actions -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-wwc-neutral-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('customer.tickets') }}" 
                   class="group bg-white rounded-xl border border-wwc-neutral-200 p-6 hover:border-wwc-accent hover:shadow-md transition-all duration-200">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 bg-wwc-accent rounded-lg flex items-center justify-center mb-3 group-hover:bg-wwc-accent-dark transition-colors duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-wwc-neutral-900 group-hover:text-wwc-accent">My Tickets</h3>
                        <p class="text-xs text-wwc-neutral-500 mt-1">View your tickets</p>
                    </div>
                </a>

                <a href="{{ route('customer.orders') }}" 
                   class="group bg-white rounded-xl border border-wwc-neutral-200 p-6 hover:border-wwc-success hover:shadow-md transition-all duration-200">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 bg-wwc-success rounded-lg flex items-center justify-center mb-3 group-hover:bg-wwc-success-dark transition-colors duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-wwc-neutral-900 group-hover:text-wwc-success">Order History</h3>
                        <p class="text-xs text-wwc-neutral-500 mt-1">View past orders</p>
                    </div>
                </a>

                <a href="{{ route('customer.support') }}" 
                   class="group bg-white rounded-xl border border-wwc-neutral-200 p-6 hover:border-wwc-info hover:shadow-md transition-all duration-200">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 bg-wwc-info rounded-lg flex items-center justify-center mb-3 group-hover:bg-wwc-info-dark transition-colors duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-wwc-neutral-900 group-hover:text-wwc-info">Get Help</h3>
                        <p class="text-xs text-wwc-neutral-500 mt-1">Support & FAQ</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- My Tickets Table -->
        <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
            <div class="px-6 py-4 border-b border-wwc-neutral-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-wwc-neutral-900">My Tickets</h2>
                    <a href="{{ route('customer.tickets') }}" 
                       class="text-sm text-wwc-primary hover:text-wwc-primary-dark font-medium">
                        View all →
                    </a>
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
                                        @if($ticket->status === 'sold') bg-wwc-success text-white
                                        @elseif($ticket->status === 'held') bg-wwc-warning text-white
                                        @elseif($ticket->status === 'scanned') bg-wwc-info text-white
                                        @else bg-wwc-neutral-200 text-wwc-neutral-800
                                        @endif">
                                        {{ ucwords($ticket->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('customer.tickets.show', $ticket->id) }}" 
                                       class="text-wwc-primary hover:text-wwc-primary-dark mr-3">
                                        View
                                    </a>
                                    @if($ticket->qr_code)
                                        <a href="{{ route('customer.tickets.qr', $ticket->id) }}" 
                                           class="text-wwc-accent hover:text-wwc-accent-dark">
                                            QR Code
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-wwc-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-wwc-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-wwc-neutral-900 mb-2">No tickets yet</h3>
                        <p class="text-wwc-neutral-500 mb-6">You haven't purchased any tickets yet. Start exploring our events!</p>
                        <a href="{{ route('public.events') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary">
                            Browse Events
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection