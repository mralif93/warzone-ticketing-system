@extends('layouts.admin')

@section('title', 'Event Details')
@section('page-title', 'Event Details')

@section('content')
<div class="flex-1">
    <!-- Header -->
    <div class="bg-white shadow-lg border-b border-wwc-neutral-200">
        <div class="px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('admin.events.index') }}" class="text-wwc-neutral-400 hover:text-wwc-neutral-600 mr-6 transition-colors duration-200">
                        <i class='bx bx-chevron-left text-2xl'></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-wwc-neutral-900 font-display">{{ $event->name }}</h1>
                        <p class="mt-2 text-lg text-wwc-neutral-600 font-medium">{{ $event->date_time->format('M j, Y \a\t g:i A') }} • {{ $event->venue }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                        @if($event->status === 'On Sale') bg-wwc-success text-white
                        @elseif($event->status === 'Sold Out') bg-wwc-error text-white
                        @elseif($event->status === 'Cancelled') bg-wwc-neutral-400 text-white
                        @else bg-wwc-warning text-white
                        @endif">
                        {{ $event->status }}
                    </span>
                    <a href="{{ route('admin.events.edit', $event) }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-semibold rounded-xl text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-edit text-lg mr-2'></i>
                        Edit Event
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="px-8 py-8">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Event Details -->
            <div class="xl:col-span-2">
                <div class="bg-white shadow-lg rounded-2xl border border-wwc-neutral-200">
                    <div class="px-8 py-6 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                        <h2 class="text-xl font-semibold text-wwc-neutral-900 font-display">Event Details</h2>
                    </div>
                    <div class="p-8">
                        <dl class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-2">
                            <div>
                                <dt class="text-base font-semibold text-wwc-neutral-700 mb-2">Event Name</dt>
                                <dd class="text-lg text-wwc-neutral-900">{{ $event->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-base font-semibold text-wwc-neutral-700 mb-2">Date & Time</dt>
                                <dd class="text-lg text-wwc-neutral-900">{{ $event->date_time->format('M j, Y \a\t g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-base font-semibold text-wwc-neutral-700 mb-2">Venue</dt>
                                <dd class="text-lg text-wwc-neutral-900">{{ $event->venue }}</dd>
                            </div>
                            <div>
                                <dt class="text-base font-semibold text-wwc-neutral-700 mb-2">Max Tickets Per Order</dt>
                                <dd class="text-lg text-wwc-neutral-900">{{ $event->max_tickets_per_order }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-base font-semibold text-wwc-neutral-700 mb-2">Description</dt>
                                <dd class="text-lg text-wwc-neutral-900">{{ $event->description ?: 'No description provided' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Recent Tickets -->
                @if($recentTickets->count() > 0)
                    <div class="mt-8 bg-white shadow-lg rounded-2xl border border-wwc-neutral-200">
                        <div class="px-8 py-6 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                            <h2 class="text-xl font-semibold text-wwc-neutral-900 font-display">Recent Ticket Sales</h2>
                        </div>
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-wwc-neutral-200">
                                <thead class="bg-wwc-neutral-50">
                                    <tr>
                                        <th class="px-8 py-4 text-left text-sm font-semibold text-wwc-neutral-700 uppercase tracking-wider">Ticket ID</th>
                                        <th class="px-8 py-4 text-left text-sm font-semibold text-wwc-neutral-700 uppercase tracking-wider">Customer</th>
                                        <th class="px-8 py-4 text-left text-sm font-semibold text-wwc-neutral-700 uppercase tracking-wider">Seat</th>
                                        <th class="px-8 py-4 text-left text-sm font-semibold text-wwc-neutral-700 uppercase tracking-wider">Status</th>
                                        <th class="px-8 py-4 text-left text-sm font-semibold text-wwc-neutral-700 uppercase tracking-wider">Price</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-wwc-neutral-200">
                                    @foreach($recentTickets as $ticket)
                                        <tr class="hover:bg-wwc-neutral-50 transition-colors duration-200">
                                            <td class="px-8 py-4 whitespace-nowrap text-sm font-semibold text-wwc-neutral-900">
                                                #{{ $ticket->id }}
                                            </td>
                                            <td class="px-8 py-4 whitespace-nowrap text-sm text-wwc-neutral-900">
                                                {{ $ticket->order->user->name }}
                                            </td>
                                            <td class="px-8 py-4 whitespace-nowrap text-sm text-wwc-neutral-900">
                                                {{ $ticket->seat_identifier }}
                                            </td>
                                            <td class="px-8 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                                    @if($ticket->status === 'Sold') bg-wwc-success text-white
                                                    @elseif($ticket->status === 'Held') bg-wwc-warning text-white
                                                    @elseif($ticket->status === 'Scanned') bg-wwc-info text-white
                                                    @else bg-wwc-neutral-400 text-white
                                                    @endif">
                                                    {{ $ticket->status }}
                                                </span>
                                            </td>
                                            <td class="px-8 py-4 whitespace-nowrap text-sm font-semibold text-wwc-neutral-900">
                                                ${{ number_format($ticket->price_paid, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Statistics Sidebar -->
            <div class="xl:col-span-1">
                <!-- Ticket Statistics -->
                <div class="bg-white shadow-lg rounded-2xl border border-wwc-neutral-200 mb-8">
                    <div class="px-8 py-6 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                        <h2 class="text-xl font-semibold text-wwc-neutral-900 font-display">Ticket Statistics</h2>
                    </div>
                    <div class="p-8">
                        <div class="space-y-6">
                            <div>
                                <div class="flex justify-between text-sm font-semibold mb-2">
                                    <span class="text-wwc-neutral-600">Total Capacity</span>
                                    <span class="text-wwc-neutral-900">{{ number_format($ticketStats['total_capacity']) }}</span>
                                </div>
                                <div class="w-full bg-wwc-neutral-200 rounded-full h-3">
                                    <div class="bg-wwc-primary h-3 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between text-sm font-semibold mb-2">
                                    <span class="text-wwc-neutral-600">Tickets Sold</span>
                                    <span class="text-wwc-neutral-900">{{ number_format($ticketStats['tickets_sold']) }}</span>
                                </div>
                                <div class="w-full bg-wwc-neutral-200 rounded-full h-3">
                                    <div class="bg-wwc-success h-3 rounded-full" style="width: {{ $ticketStats['sold_percentage'] }}%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between text-sm font-semibold mb-2">
                                    <span class="text-wwc-neutral-600">Available</span>
                                    <span class="text-wwc-neutral-900">{{ number_format($ticketStats['tickets_available']) }}</span>
                                </div>
                                <div class="w-full bg-wwc-neutral-200 rounded-full h-3">
                                    <div class="bg-wwc-info h-3 rounded-full" style="width: {{ 100 - $ticketStats['sold_percentage'] }}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 pt-6 border-t border-wwc-neutral-200">
                            <div class="text-center">
                                <div class="text-4xl font-bold text-wwc-neutral-900 font-display">{{ $ticketStats['sold_percentage'] }}%</div>
                                <div class="text-sm text-wwc-neutral-600 font-medium">Sold</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white shadow-lg rounded-2xl border border-wwc-neutral-200">
                    <div class="px-8 py-6 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                        <h2 class="text-xl font-semibold text-wwc-neutral-900 font-display">Quick Actions</h2>
                    </div>
                    <div class="p-8">
                        <div class="space-y-4">
                            @if($event->isOnSale())
                                <a href="{{ route('public.tickets.select', $event) }}" 
                                   class="w-full bg-wwc-success hover:bg-wwc-success-dark text-white px-6 py-3 rounded-xl font-semibold transition-colors duration-200 text-center block">
                                    <svg class="h-5 w-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                    </svg>
                                    Buy Tickets
                                </a>
                            @endif
                            
                            <a href="{{ route('admin.events.edit', $event) }}" 
                               class="w-full bg-wwc-primary hover:bg-wwc-primary-dark text-white px-6 py-3 rounded-xl font-semibold transition-colors duration-200 text-center block">
                                <svg class="h-5 w-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Event
                            </a>
                            
                            @if($event->status === 'Draft')
                                <form action="{{ route('admin.events.change-status', $event) }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="status" value="On Sale">
                                    <button type="submit" 
                                            class="w-full bg-wwc-success hover:bg-wwc-success-dark text-white px-6 py-3 rounded-xl font-semibold transition-colors duration-200">
                                        <svg class="h-5 w-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Go On Sale
                                    </button>
                                </form>
                            @elseif($event->status === 'On Sale')
                                <form action="{{ route('admin.events.change-status', $event) }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="status" value="Sold Out">
                                    <button type="submit" 
                                            class="w-full bg-wwc-error hover:bg-wwc-error-dark text-white px-6 py-3 rounded-xl font-semibold transition-colors duration-200">
                                        <svg class="h-5 w-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Mark Sold Out
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('admin.events.index') }}" 
                               class="w-full bg-wwc-neutral-600 hover:bg-wwc-neutral-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors duration-200 text-center block">
                                <svg class="h-5 w-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back to Events
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
