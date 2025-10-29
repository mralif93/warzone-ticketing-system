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
                <!-- Total Ticket Types -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $ticketTypes->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Ticket Types</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    {{ $ticketTypes->groupBy('event_id')->count() }} Events
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
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ number_format($totalSeats) }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Seats</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <i class='bx bx-check text-xs mr-1'></i>
                                    {{ number_format($soldSeats) }} Sold
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
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ number_format($availableSeats) }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Available Seats</div>
                            @if(isset($hasMultiDayEvent) && $hasMultiDayEvent && isset($day1TotalAvailable) && isset($day2TotalAvailable))
                            <div class="space-y-1 mt-2 pt-2 border-t border-wwc-neutral-200">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-wwc-neutral-500">Day 1:</span>
                                    <span class="font-semibold text-green-600">{{ number_format($day1TotalAvailable) }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-wwc-neutral-500">Day 2:</span>
                                    <span class="font-semibold text-green-600">{{ number_format($day2TotalAvailable) }}</span>
                                </div>
                            </div>
                            @else
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-warning font-semibold">
                                    <i class='bx bx-time text-xs mr-1'></i>
                                    {{ $ticketTypes->sum('scanned_seats') }} Scanned
                                </div>
                            </div>
                            @endif
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
                            @php
                                $averagePrice = $soldSeats > 0 ? $totalRevenue / $soldSeats : 0;
                            @endphp
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">RM{{ number_format($totalRevenue, 0) }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Revenue</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <i class='bx bx-trending-up text-xs mr-1'></i>
                                    RM{{ number_format($averagePrice, 0) }} Avg
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
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Search & Filter Ticket Types</h3>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.tickets.trashed') }}" 
                               class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors duration-200">
                                <i class='bx bx-trash text-xs mr-1'></i>
                                View Trashed
                            </a>
                            <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                <i class='bx bx-search text-sm'></i>
                                <span>Find specific ticket types</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search Ticket Types</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Ticket type name, event name..."
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Status</label>
                            <select name="status" id="status" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="sold_out" {{ request('status') == 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                            <label for="zone" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Ticket Type</label>
                            <input type="text" name="zone" id="zone" value="{{ request('zone') }}"
                                   placeholder="Ticket type name..."
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div class="sm:col-span-2 lg:col-span-4 flex justify-end gap-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-search text-sm mr-2'></i>
                                Search Ticket Types
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
                    Create New Ticket Type
                </a>
            </div>

            <!-- Ticket Types List -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Ticket Types Overview</h3>
                        <div class="flex flex-wrap items-center gap-2 sm:gap-4">
                            <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs text-wwc-neutral-500">
                                <span class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                    {{ number_format($soldSeats) }} Sold
                                </span>
                                <span class="flex items-center">
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                    {{ number_format($availableSeats) }} Available
                                </span>
                                <span class="flex items-center">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                    {{ number_format($scannedSeats) }} Scanned
                                </span>
                                <span class="flex items-center">
                                    <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                    {{ $soldOutTicketTypes }} Sold Out
                                </span>
                                @if($comboTicketTypes > 0)
                                <span class="flex items-center">
                                    <div class="w-2 h-2 bg-wwc-accent rounded-full mr-2"></div>
                                    {{ $comboTicketTypes }} Combo
                                </span>
                                @endif
                            </div>
                            <form method="GET" class="flex items-center space-x-2">
                                @foreach(request()->except('limit') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <label for="limit" class="text-xs font-semibold text-wwc-neutral-600">Limit:</label>
                                <select name="limit" id="limit" onchange="this.form.submit()" class="px-2 py-1 border border-wwc-neutral-300 rounded text-xs focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary">
                                    <option value="10" {{ request('limit', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="15" {{ request('limit', 10) == 15 ? 'selected' : '' }}>15</option>
                                    <option value="25" {{ request('limit', 10) == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('limit', 10) == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('limit', 10) == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                @if($ticketTypes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-wwc-neutral-100">
                            <thead class="bg-wwc-neutral-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Event
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Ticket Type
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-center">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-center">
                                        Occupancy
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-center">
                                        Seating Image
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-right">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-wwc-neutral-100">
                                @foreach($ticketTypes as $ticketType)
                                    <tr class="hover:bg-wwc-neutral-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                                        <i class='bx bx-calendar text-lg text-blue-600'></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-wwc-neutral-900">{{ $ticketType->event->name }}</div>
                                                    <div class="text-xs text-wwc-neutral-500">{{ $ticketType->event->date_time->format('M j, Y') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="text-sm font-semibold text-wwc-neutral-900">{{ $ticketType->name }}</div>
                                                @if($ticketType->is_combo)
                                                    <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-wwc-accent text-white">
                                                        <i class='bx bx-discount text-xs mr-1'></i>
                                                        Combo
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-wwc-neutral-500">{{ $ticketType->sold_seats }} sold, {{ $ticketType->scanned_seats }} scanned</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-wwc-neutral-900">RM{{ number_format($ticketType->price, 0) }}</div>
                                            <div class="text-xs text-wwc-neutral-500">Per seat</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="text-lg font-bold text-wwc-neutral-900">{{ $ticketType->total_seats }}</div>
                                            <div class="text-xs text-wwc-neutral-500">Total</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if(isset($hasMultiDayEvent) && $hasMultiDayEvent && isset($ticketType->is_multi_day) && $ticketType->is_multi_day)
                                            <!-- Multi-day event: Day 1 | Day 2 -->
                                            <div class="space-y-2">
                                                <!-- Row 1: Day 1 | Available | Sold -->
                                                <div class="flex items-center gap-3">
                                                    <!-- Day Label -->
                                                    <div class="text-sm font-bold text-wwc-neutral-900">Day 1</div>
                                                    <!-- Separator -->
                                                    <div class="border-l border-wwc-neutral-300 h-8"></div>
                                                    <!-- Available -->
                                                    <div class="text-center">
                                                        <div class="text-sm font-bold text-green-600">
                                                            {{ isset($ticketType->day1_available) ? $ticketType->day1_available : 0 }}
                                                        </div>
                                                        <div class="text-xs text-wwc-neutral-500">Available</div>
                                                    </div>
                                                    <!-- Separator -->
                                                    <div class="border-l border-wwc-neutral-300 h-8"></div>
                                                    <!-- Sold -->
                                                    <div class="text-center">
                                                        <div class="text-sm font-bold text-red-600">
                                                            {{ isset($ticketType->day1_sold) ? $ticketType->day1_sold : 0 }}
                                                        </div>
                                                        <div class="text-xs text-wwc-neutral-500">Sold</div>
                                                    </div>
                                                </div>
                                                <!-- Separator: ---- -->
                                                <div class="flex items-center justify-center py-1">
                                                    <div class="border-t border-wwc-neutral-300 w-full"></div>
                                                </div>
                                                <!-- Row 2: Day 2 | Available | Sold -->
                                                <div class="flex items-center gap-3">
                                                    <!-- Day Label -->
                                                    <div class="text-sm font-bold text-wwc-neutral-900">Day 2</div>
                                                    <!-- Separator -->
                                                    <div class="border-l border-wwc-neutral-300 h-8"></div>
                                                    <!-- Available -->
                                                    <div class="text-center">
                                                        <div class="text-sm font-bold text-green-600">
                                                            {{ isset($ticketType->day2_available) ? $ticketType->day2_available : 0 }}
                                                        </div>
                                                        <div class="text-xs text-wwc-neutral-500">Available</div>
                                                    </div>
                                                    <!-- Separator -->
                                                    <div class="border-l border-wwc-neutral-300 h-8"></div>
                                                    <!-- Sold -->
                                                    <div class="text-center">
                                                        <div class="text-sm font-bold text-red-600">
                                                            {{ isset($ticketType->day2_sold) ? $ticketType->day2_sold : 0 }}
                                                        </div>
                                                        <div class="text-xs text-wwc-neutral-500">Sold</div>
                                                    </div>
                                                </div>
                                            </div>
                                            @elseif(isset($hasMultiDayEvent) && $hasMultiDayEvent && (!isset($ticketType->is_multi_day) || !$ticketType->is_multi_day))
                                            <!-- Multi-day event but single-day ticket: Show total -->
                                            <div class="text-center">
                                                <div class="text-lg font-bold {{ $ticketType->available_seats > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $ticketType->available_seats }}</div>
                                                <div class="text-xs text-wwc-neutral-500 mt-1">Available</div>
                                            </div>
                                            @else
                                            <!-- Single-day event: Show total available -->
                                            <div class="text-center">
                                                <div class="text-lg font-bold {{ $ticketType->available_seats > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $ticketType->available_seats }}</div>
                                                <div class="text-xs text-wwc-neutral-500 mt-1">Available</div>
                                            </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                @if($ticketType->status === 'active') bg-green-100 text-green-800
                                                @elseif($ticketType->status === 'sold_out') bg-red-100 text-red-800
                                                @elseif($ticketType->status === 'inactive') bg-gray-100 text-gray-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                @if($ticketType->status === 'active')
                                                    <i class='bx bx-check-circle text-xs mr-1'></i>
                                                @elseif($ticketType->status === 'sold_out')
                                                    <i class='bx bx-x-circle text-xs mr-1'></i>
                                                @elseif($ticketType->status === 'inactive')
                                                    <i class='bx bx-pause-circle text-xs mr-1'></i>
                                                @else
                                                    <i class='bx bx-question-mark text-xs mr-1'></i>
                                                @endif
                                                {{ ucfirst(str_replace('_', ' ', $ticketType->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="text-sm font-semibold text-wwc-neutral-900">{{ $ticketType->getOccupancyPercentage() }}%</div>
                                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $ticketType->getOccupancyPercentage() }}%"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($ticketType->seating_image)
                                                <div class="flex justify-center">
                                                    <img src="{{ asset('storage/' . $ticketType->seating_image) }}" 
                                                         alt="Seating layout" 
                                                         class="w-12 h-12 object-cover rounded-lg border border-wwc-neutral-200 cursor-pointer hover:shadow-md transition-shadow duration-200"
                                                         onclick="openImageModal('{{ asset('storage/' . $ticketType->seating_image) }}', '{{ $ticketType->name }} Seating Layout')"
                                                         title="Click to view full size">
                                                </div>
                                            @else
                                                <div class="flex justify-center">
                                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                                        <i class='bx bx-image text-gray-400 text-lg'></i>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-1">
                                                <a href="{{ route('admin.tickets.show', $ticketType) }}" 
                                                   class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                                   title="View ticket type details">
                                                    <i class='bx bx-show text-xs mr-1.5'></i>
                                                    View
                                                </a>
                                                <a href="{{ route('admin.tickets.edit', $ticketType) }}" 
                                                   class="inline-flex items-center px-3 py-2 text-xs font-semibold text-wwc-neutral-700 bg-white border border-wwc-neutral-300 hover:bg-wwc-neutral-50 hover:border-wwc-neutral-400 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                                   title="Edit ticket type">
                                                    <i class='bx bx-edit text-xs mr-1.5'></i>
                                                    Edit
                                                </a>
                                                @if($ticketType->sold_seats == 0)
                                                    <button onclick="confirmDelete('{{ $ticketType->id }}', '{{ $ticketType->name }}')" 
                                                            class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                                            title="Delete ticket type">
                                                        <i class='bx bx-trash text-xs mr-1.5'></i>
                                                        Delete
                                                    </button>
                                                @else
                                                    <button disabled 
                                                            class="inline-flex items-center px-3 py-2 text-xs font-semibold text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed"
                                                            title="Cannot delete ticket type with sold tickets">
                                                        <i class='bx bx-trash text-xs mr-1.5'></i>
                                                        Delete
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-wwc-neutral-100">
                        {{ $ticketTypes->appends(request()->query())->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div class="mx-auto h-24 w-24 rounded-full bg-wwc-neutral-100 flex items-center justify-center mb-4">
                            <i class='bx bx-price-tag text-6xl text-wwc-neutral-300'></i>
                        </div>
                        <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">No ticket types found</h3>
                        <p class="text-wwc-neutral-600 mb-6">Get started by creating your first ticket type or adjust your search criteria.</p>
                        <a href="{{ route('admin.tickets.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <i class='bx bx-plus text-sm mr-2'></i>
                            Create First Ticket Type
                        </a>
                    </div>
                @endif
            </div>
    </div>
</div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 w-11/12 max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Seating Layout</h3>
                <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
            <div class="p-4">
                <img id="modalImage" src="" alt="Seating layout" class="w-full h-auto rounded-lg">
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class='bx bx-trash text-2xl text-red-600'></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Ticket Type</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete the ticket type "<span id="deleteTicketName" class="font-semibold text-gray-900"></span>"?
                </p>
                <p class="text-xs text-red-600 mt-2">
                    This action cannot be undone.
                </p>
            </div>
            <div class="flex justify-center space-x-3 mt-4">
                <button onclick="closeDeleteModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(ticketId, ticketName) {
    document.getElementById('deleteTicketName').textContent = ticketName;
    document.getElementById('deleteForm').action = `/admin/tickets/${ticketId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Image modal functions
function openImageModal(imageSrc, title) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Close image modal when clicking outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});
</script>
@endsection