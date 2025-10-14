@extends('layouts.admin')

@section('title', 'Ticket Management')
@section('page-subtitle', 'Manage all tickets and sales')

@section('content')
<!-- Professional Ticket Management with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">
    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <!-- Total Zones -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $tickets->groupBy('zone')->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Zones</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    {{ $tickets->groupBy('event_id')->count() }} Events
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-map text-2xl text-blue-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Total Seats -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $tickets->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Seats</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <i class='bx bx-check text-xs mr-1'></i>
                                    {{ $tickets->where('status', 'Sold')->count() }} Sold
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class='bx bx-chair text-2xl text-green-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Available Seats -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $tickets->where('status', 'Held')->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Available Seats</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-warning font-semibold">
                                    <i class='bx bx-time text-xs mr-1'></i>
                                    {{ $tickets->where('status', 'Scanned')->count() }} Scanned
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                            <i class='bx bx-time text-2xl text-yellow-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">RM{{ number_format($tickets->where('status', 'Sold')->sum('price_paid'), 0) }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Revenue</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <i class='bx bx-trending-up text-xs mr-1'></i>
                                    {{ $tickets->where('status', 'Sold')->count() > 0 ? number_format(($tickets->where('status', 'Sold')->sum('price_paid') / $tickets->where('status', 'Sold')->count()), 0) : 0 }} Avg
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-purple-100 flex items-center justify-center">
                            <i class='bx bx-dollar text-2xl text-purple-600'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Search & Filter Tickets</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-search text-sm'></i>
                            <span>Find specific tickets</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search Tickets</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Ticket ID, QR code..."
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Status</label>
                            <select name="status" id="status" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Statuses</option>
                                <option value="Sold" {{ request('status') == 'Sold' ? 'selected' : '' }}>Sold</option>
                                <option value="Held" {{ request('status') == 'Held' ? 'selected' : '' }}>Held</option>
                                <option value="Scanned" {{ request('status') == 'Scanned' ? 'selected' : '' }}>Scanned</option>
                                <option value="Invalid" {{ request('status') == 'Invalid' ? 'selected' : '' }}>Invalid</option>
                                <option value="Refunded" {{ request('status') == 'Refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        <div>
                            <label for="event_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Event</label>
                            <select name="event_id" id="event_id" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Events</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="zone" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Zone</label>
                            <select name="zone" id="zone" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Zones</option>
                                <option value="Warzone Exclusive" {{ request('zone') == 'Warzone Exclusive' ? 'selected' : '' }}>Warzone Exclusive</option>
                                <option value="Warzone VIP" {{ request('zone') == 'Warzone VIP' ? 'selected' : '' }}>Warzone VIP</option>
                                <option value="Warzone Grandstand" {{ request('zone') == 'Warzone Grandstand' ? 'selected' : '' }}>Warzone Grandstand</option>
                                <option value="Warzone Premium Ringside" {{ request('zone') == 'Warzone Premium Ringside' ? 'selected' : '' }}>Warzone Premium Ringside</option>
                                <option value="Level 1 Zone A/B/C/D" {{ request('zone') == 'Level 1 Zone A/B/C/D' ? 'selected' : '' }}>Level 1 Zone A/B/C/D</option>
                                <option value="Level 2 Zone A/B/C/D" {{ request('zone') == 'Level 2 Zone A/B/C/D' ? 'selected' : '' }}>Level 2 Zone A/B/C/D</option>
                                <option value="Standing Zone A/B" {{ request('zone') == 'Standing Zone A/B' ? 'selected' : '' }}>Standing Zone A/B</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2 lg:col-span-4 flex justify-end gap-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-search text-sm mr-2'></i>
                                Search Tickets
                            </button>
                            <a href="{{ route('admin.tickets.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Header Section with Create Button -->
            <div class="flex justify-end items-center mb-6">
                <a href="{{ route('admin.tickets.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-plus text-sm mr-2'></i>
                    Create New Ticket
                </a>
            </div>

            <!-- Zones List -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Zones Overview</h3>
                        <div class="flex items-center space-x-4 text-xs text-wwc-neutral-500">
                            <span class="flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                {{ $tickets->where('status', 'Sold')->count() }} Sold
                            </span>
                            <span class="flex items-center">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                {{ $tickets->where('status', 'Held')->count() }} Available
                            </span>
                            <span class="flex items-center">
                                <div class="w-2 h-2 bg-orange-500 rounded-full mr-2"></div>
                                {{ $tickets->where('status', 'Scanned')->count() }} Scanned
                            </span>
                            <span class="flex items-center">
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                {{ $tickets->where('status', 'Invalid')->count() }} Invalid
                            </span>
                        </div>
                    </div>
                </div>

                @if($tickets->count() > 0)
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-wwc-neutral-100">
                            <thead class="bg-wwc-neutral-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Event
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Zone Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Price
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-center">
                                        Total Seats
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-center">
                                        Available
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-right">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-wwc-neutral-100">
                                @php
                                    $zones = $tickets->groupBy(['event_id', 'zone']);
                                @endphp
                                @foreach($zones as $eventId => $eventZones)
                                    @foreach($eventZones as $zoneName => $zoneTickets)
                                        @php
                                            $event = $zoneTickets->first()->event;
                                            $totalSeats = $zoneTickets->count();
                                            $availableSeats = $zoneTickets->where('status', 'Held')->count();
                                            $soldSeats = $zoneTickets->where('status', 'Sold')->count();
                                            $scannedSeats = $zoneTickets->where('status', 'Scanned')->count();
                                            $price = $zoneTickets->first()->price_paid;
                                        @endphp
                                        <tr class="hover:bg-wwc-neutral-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                                            <i class='bx bx-calendar text-lg text-blue-600'></i>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-semibold text-wwc-neutral-900">{{ $event->name }}</div>
                                                        <div class="text-xs text-wwc-neutral-500">{{ $event->date_time->format('M j, Y') }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-wwc-neutral-900">{{ $zoneName }}</div>
                                                <div class="text-xs text-wwc-neutral-500">{{ $soldSeats }} sold, {{ $scannedSeats }} scanned</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-wwc-neutral-900">RM{{ number_format($price, 0) }}</div>
                                                <div class="text-xs text-wwc-neutral-500">Per seat</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="text-lg font-bold text-wwc-neutral-900">{{ $totalSeats }}</div>
                                                <div class="text-xs text-wwc-neutral-500">Total</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="text-lg font-bold {{ $availableSeats > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $availableSeats }}</div>
                                                <div class="text-xs text-wwc-neutral-500">{{ $availableSeats > 0 ? 'Available' : 'Sold Out' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-1">
                                                    <a href="{{ route('admin.tickets.index', ['event_id' => $eventId, 'zone' => $zoneName]) }}" 
                                                       class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                                       title="View zone tickets">
                                                        <i class='bx bx-show text-xs mr-1.5'></i>
                                                        View Tickets
                                                    </a>
                                                    <a href="{{ route('admin.tickets.create', ['event_id' => $eventId, 'zone' => $zoneName]) }}" 
                                                       class="inline-flex items-center px-3 py-2 text-xs font-semibold text-wwc-neutral-700 bg-white border border-wwc-neutral-300 hover:bg-wwc-neutral-50 hover:border-wwc-neutral-400 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                                       title="Add more seats">
                                                        <i class='bx bx-plus text-xs mr-1.5'></i>
                                                        Add Seats
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-wwc-neutral-100">
                        {{ $tickets->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div class="mx-auto h-24 w-24 rounded-full bg-wwc-neutral-100 flex items-center justify-center mb-4">
                            <i class='bx bx-map text-6xl text-wwc-neutral-300'></i>
                        </div>
                        <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">No zones found</h3>
                        <p class="text-wwc-neutral-600 mb-6">Get started by creating your first zone with seats or adjust your search criteria.</p>
                        <a href="{{ route('admin.tickets.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <i class='bx bx-plus text-sm mr-2'></i>
                            Create First Zone
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection