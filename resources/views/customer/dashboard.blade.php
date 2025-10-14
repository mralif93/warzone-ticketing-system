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
        
        <!-- Statistics Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Orders -->
            <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-wwc-primary rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-wwc-neutral-600">Total Orders</p>
                        <p class="text-2xl font-bold text-wwc-neutral-900">{{ $orderStats['total_orders'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Tickets -->
            <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-wwc-accent rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-wwc-neutral-600">Total Tickets</p>
                        <p class="text-2xl font-bold text-wwc-neutral-900">{{ $orderStats['total_tickets'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Spent -->
            <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-wwc-success rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-wwc-neutral-600">Total Spent</p>
                        <p class="text-2xl font-bold text-wwc-neutral-900">RM{{ number_format($orderStats['total_spent'], 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-wwc-info rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-wwc-neutral-600">Upcoming Events</p>
                        <p class="text-2xl font-bold text-wwc-neutral-900">{{ $orderStats['upcoming_events'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-wwc-neutral-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('customer.events') }}" 
                   class="group bg-white rounded-xl border border-wwc-neutral-200 p-6 hover:border-wwc-primary hover:shadow-md transition-all duration-200">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 bg-wwc-primary rounded-lg flex items-center justify-center mb-3 group-hover:bg-wwc-primary-dark transition-colors duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-wwc-neutral-900 group-hover:text-wwc-primary">Browse Events</h3>
                        <p class="text-xs text-wwc-neutral-500 mt-1">Discover new events</p>
                    </div>
                </a>

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

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Tickets -->
            <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900">Recent Tickets</h2>
                        <a href="{{ route('customer.tickets') }}" 
                           class="text-sm text-wwc-primary hover:text-wwc-primary-dark font-medium">
                            View all →
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentTickets->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentTickets->take(3) as $ticket)
                            <div class="flex items-center p-4 bg-wwc-neutral-50 rounded-lg hover:bg-wwc-neutral-100 transition-colors duration-200">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-wwc-primary rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-wwc-neutral-900 truncate">{{ $ticket->event->name }}</h3>
                                    <p class="text-xs text-wwc-neutral-500">{{ $ticket->event->date_time->format('M j, Y') }} • {{ $ticket->zone }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-semibold text-wwc-neutral-900">RM{{ number_format($ticket->price_paid, 0) }}</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($ticket->status === 'Sold') bg-wwc-success text-white
                                        @elseif($ticket->status === 'Held') bg-wwc-warning text-white
                                        @elseif($ticket->status === 'Scanned') bg-wwc-info text-white
                                        @else bg-wwc-neutral-200 text-wwc-neutral-800
                                        @endif">
                                        {{ $ticket->status }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-wwc-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-wwc-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-wwc-neutral-900 mb-2">No tickets yet</h3>
                            <p class="text-xs text-wwc-neutral-500 mb-4">Start exploring amazing events!</p>
                            <a href="{{ route('customer.events') }}" 
                               class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white rounded-lg text-sm font-medium hover:bg-wwc-primary-dark transition-colors duration-200">
                                Browse Events
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900">Upcoming Events</h2>
                        <a href="{{ route('customer.events') }}" 
                           class="text-sm text-wwc-accent hover:text-wwc-accent-dark font-medium">
                            View all →
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($upcomingEvents->count() > 0)
                        <div class="space-y-4">
                            @foreach($upcomingEvents->take(3) as $event)
                            <div class="flex items-center p-4 bg-wwc-neutral-50 rounded-lg hover:bg-wwc-neutral-100 transition-colors duration-200">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-wwc-accent rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-wwc-neutral-900 truncate">{{ $event->name }}</h3>
                                    <p class="text-xs text-wwc-neutral-500">{{ $event->date_time->format('M j, Y \a\t g:i A') }} • {{ $event->venue }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-wwc-success text-white">
                                        On Sale
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-wwc-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-wwc-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-wwc-neutral-900 mb-2">No upcoming events</h3>
                            <p class="text-xs text-wwc-neutral-500">Check back later for new events!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection