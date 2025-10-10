@extends('layouts.admin')

@section('title', 'Event Details')
@section('page-title', 'Event Details')

@section('content')
<!-- Professional Event Details with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <div class="flex items-center">
                    <a href="{{ route('admin.events.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Events
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Event Details -->
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Event Details</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-info-circle text-sm'></i>
                                    <span>Event information</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Event Name -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-red-100 flex items-center justify-center">
                                            <i class='bx bx-calendar text-sm text-red-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Event Name</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $event->name }}</span>
                                    </div>
                                </div>

                                <!-- Date & Time -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-time text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Date & Time</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $event->date_time->format('M j, Y \a\t g:i A') }}</span>
                                    </div>
                                </div>

                                <!-- Venue -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                            <i class='bx bx-map text-sm text-orange-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Venue</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $event->venue }}</span>
                                    </div>
                                </div>

                                <!-- Max Tickets Per Order -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-receipt text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Max Tickets Per Order</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $event->max_tickets_per_order }}</span>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="flex items-center py-3">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-detail text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Description</span>
                                        <span class="text-base font-medium text-wwc-neutral-900 leading-relaxed text-right max-w-md">{{ $event->description ?: 'No description provided' }}</span>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

                <!-- Recent Tickets -->
                @if($recentTickets->count() > 0)
                        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                            <div class="px-6 py-4 border-b border-wwc-neutral-100">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-bold text-wwc-neutral-900">Recent Ticket Sales</h3>
                                    <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                        <i class='bx bx-receipt text-sm'></i>
                                        <span>Latest transactions</span>
                                    </div>
                                </div>
                        </div>
                        <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-wwc-neutral-100">
                                <thead class="bg-wwc-neutral-50">
                                    <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Ticket ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Customer</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Seat</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Price</th>
                                    </tr>
                                </thead>
                                    <tbody class="bg-white divide-y divide-wwc-neutral-100">
                                    @foreach($recentTickets as $ticket)
                                            <tr class="hover:bg-wwc-neutral-50 transition-colors duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-wwc-neutral-900">
                                                #{{ $ticket->id }}
                                            </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-900">
                                                {{ $ticket->order->user->name }}
                                            </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-900">
                                                {{ $ticket->seat_identifier }}
                                            </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                    @if($ticket->status === 'Sold') bg-wwc-success text-white
                                                    @elseif($ticket->status === 'Held') bg-wwc-warning text-white
                                                    @elseif($ticket->status === 'Scanned') bg-wwc-info text-white
                                                    @else bg-wwc-neutral-400 text-white
                                                    @endif">
                                                        @if($ticket->status === 'Sold')
                                                            <i class='bx bx-check-circle text-xs mr-1'></i>
                                                        @elseif($ticket->status === 'Held')
                                                            <i class='bx bx-time text-xs mr-1'></i>
                                                        @elseif($ticket->status === 'Scanned')
                                                            <i class='bx bx-check text-xs mr-1'></i>
                                                        @else
                                                            <i class='bx bx-x text-xs mr-1'></i>
                                                        @endif
                                                    {{ $ticket->status }}
                                                </span>
                                            </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-wwc-neutral-900">
                                                    RM{{ number_format($ticket->price_paid, 0) }}
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
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                    <div class="px-6 py-4 border-b border-wwc-neutral-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Ticket Statistics</h3>
                            <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                <i class='bx bx-bar-chart text-sm'></i>
                                <span>Event metrics</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm font-semibold mb-2">
                                    <span class="text-wwc-neutral-600">Total Capacity</span>
                                    <span class="text-wwc-neutral-900">{{ number_format($ticketStats['total_capacity']) }}</span>
                                </div>
                                <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                    <div class="bg-wwc-primary h-2 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between text-sm font-semibold mb-2">
                                    <span class="text-wwc-neutral-600">Tickets Sold</span>
                                    <span class="text-wwc-neutral-900">{{ number_format($ticketStats['tickets_sold']) }}</span>
                                </div>
                                <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                    <div class="bg-wwc-success h-2 rounded-full" style="width: {{ $ticketStats['sold_percentage'] }}%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between text-sm font-semibold mb-2">
                                    <span class="text-wwc-neutral-600">Available</span>
                                    <span class="text-wwc-neutral-900">{{ number_format($ticketStats['tickets_available']) }}</span>
                                </div>
                                <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                    <div class="bg-wwc-info h-2 rounded-full" style="width: {{ 100 - $ticketStats['sold_percentage'] }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-wwc-neutral-200">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-wwc-neutral-900 font-display">{{ $ticketStats['sold_percentage'] }}%</div>
                                <div class="text-sm text-wwc-neutral-600 font-medium">Sold</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Quick Actions</h3>
                            <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                <i class='bx bx-cog text-sm'></i>
                                <span>Event actions</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @if($event->isOnSale())
                                <a href="{{ route('public.tickets.select', $event) }}" 
                                    class="w-full bg-wwc-success hover:bg-wwc-success-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                        <i class='bx bx-receipt text-sm mr-2'></i>
                                    Buy Tickets
                                </a>
                            @endif
                            
                            <a href="{{ route('admin.events.edit', $event) }}" 
                                class="w-full bg-wwc-primary hover:bg-wwc-primary-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                    <i class='bx bx-edit text-sm mr-2'></i>
                                Edit Event
                            </a>
                            
                            @if($event->status === 'Draft')
                                <form action="{{ route('admin.events.change-status', $event) }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="status" value="On Sale">
                                    <button type="submit" 
                                                class="w-full bg-wwc-success hover:bg-wwc-success-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-sm">
                                            <i class='bx bx-play text-sm mr-2'></i>
                                        Go On Sale
                                    </button>
                                </form>
                            @elseif($event->status === 'On Sale')
                                <form action="{{ route('admin.events.change-status', $event) }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="status" value="Sold Out">
                                    <button type="submit" 
                                                class="w-full bg-wwc-error hover:bg-wwc-error-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-sm">
                                            <i class='bx bx-x text-sm mr-2'></i>
                                        Mark Sold Out
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('admin.events.index') }}" 
                                class="w-full bg-wwc-neutral-600 hover:bg-wwc-neutral-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                    <i class='bx bx-arrow-back text-sm mr-2'></i>
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
