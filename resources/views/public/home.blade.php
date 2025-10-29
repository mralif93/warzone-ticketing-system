@extends('layouts.public')

@section('title', 'Warzone Ticketing - Premium Event Tickets')
@section('description', 'Get premium tickets for the best events. Secure, fast, and reliable ticketing system for concerts, sports, and entertainment.')

@section('content')
<!-- Hero Section with Main Event -->
@if($mainEvent)
<div class="bg-gradient-to-br from-wwc-primary to-wwc-primary-dark text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Full Width Title Section -->
        <div class="text-left mb-12">
            <div class="flex items-center space-x-2 mb-6">
                @if($mainEvent->default)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-400 text-yellow-900">
                        <i class='bx bx-star mr-1'></i>
                        Featured Event
                    </span>
                @endif
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-400 text-green-900">
                    <i class='bx bx-check-circle mr-1'></i>
                    {{ str_replace('_', '', ucwords(str_replace('_', ' ', $mainEvent->status))) }}
                </span>
            </div>

            <h1 class="text-4xl lg:text-7xl font-bold leading-tight mb-8">
                {{ $mainEvent->name }}
            </h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Event Information -->
            <div class="space-y-6">

                <div class="space-y-3">
                    @if($mainEvent->isMultiDay())
                        <div class="flex items-center text-lg">
                            <i class='bx bx-calendar mr-3 text-2xl'></i>
                            <span>{{ $mainEvent->start_date->format('l, F j, Y') }} - {{ $mainEvent->end_date->format('F j, Y') }}</span>
                        </div>
                        <div class="flex items-center text-lg">
                            <i class='bx bx-time mr-3 text-2xl'></i>
                            <span>{{ $mainEvent->start_date->format('g:i A') }} - {{ $mainEvent->end_date->format('g:i A') }}</span>
                        </div>
                    @else
                        <div class="flex items-center text-lg">
                            <i class='bx bx-calendar mr-3 text-2xl'></i>
                            <span>{{ $mainEvent->date_time->format('l, F j, Y') }}</span>
                        </div>
                        <div class="flex items-center text-lg">
                            <i class='bx bx-time mr-3 text-2xl'></i>
                            <span>{{ $mainEvent->date_time->format('g:i A') }}</span>
                        </div>
                    @endif
                    @if($mainEvent->venue)
                    <div class="flex items-center text-lg">
                        <i class='bx bx-map mr-3 text-2xl'></i>
                        <span>{{ $mainEvent->venue }}</span>
                    </div>
                    @endif
                </div>

                @if($mainEvent->description)
                <p class="text-lg text-wwc-neutral-100 leading-relaxed">
                    {{ Str::limit($mainEvent->description, 200) }}
                </p>
                @endif

                <div class="w-full">
                    <a href="{{ route('public.tickets.cart', $mainEvent) }}" 
                       class="inline-flex items-center justify-center w-full px-8 py-4 bg-white text-wwc-primary font-semibold rounded-lg hover:bg-wwc-neutral-100 transition-colors duration-200 shadow-lg hover:shadow-xl">
                        <i class='bx bx-ticket mr-2'></i>
                        Get Tickets Now
                    </a>
                </div>
            </div>

            <!-- Event Visual/Image - Arena Layout -->
            <div class="relative">
                <div class="bg-gradient-to-br from-wwc-primary to-red-600 rounded-2xl p-8 shadow-2xl">
                    <div class="bg-wwc-neutral-900 rounded-xl p-6">
                        @if(file_exists(public_path('images/all-layout.jpeg')))
                            <img src="{{ asset('images/all-layout.jpeg') }}" 
                                 alt="N9 Arena Layout" 
                                 onclick="openArenaLayoutModal()"
                                 class="w-full h-auto rounded-lg shadow-lg cursor-pointer hover:opacity-90 transition-opacity">
                        @elseif(file_exists(public_path('images/arena-layout.png')))
                            <img src="{{ asset('images/arena-layout.png') }}" 
                                 alt="N9 Arena Layout" 
                                 onclick="openArenaLayoutModal()"
                                 class="w-full h-auto rounded-lg shadow-lg cursor-pointer hover:opacity-90 transition-opacity">
                        @elseif(file_exists(public_path('images/arena-layout.svg')))
                            <img src="{{ asset('images/arena-layout.svg') }}" 
                                 alt="N9 Arena Layout" 
                                 onclick="openArenaLayoutModal()"
                                 class="w-full h-auto rounded-lg shadow-lg cursor-pointer hover:opacity-90 transition-opacity">
                        @else
                            <div class="text-center py-12">
                                <i class='bx bx-image text-6xl text-wwc-neutral-600 mb-4'></i>
                                <p class="text-wwc-neutral-400 text-lg">N9 Arena Layout</p>
                                <p class="text-wwc-neutral-500 text-sm mt-2">Arena seating layout image</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif


<!-- Ticket Types Section for Default Event -->
@if($mainEvent && $mainEvent->tickets->count() > 0)
<div class="bg-wwc-neutral-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-wwc-neutral-900 mb-4">Available Tickets</h2>
            <p class="text-lg text-wwc-neutral-600">
                @if($mainEvent->isMultiDay())
                    Choose your preferred day and ticket type
                @else
                    Choose your preferred seating and experience level
                @endif
            </p>
        </div>

        @if($mainEvent->isMultiDay())
            <!-- Multi-day event: Combined layout with total availability -->
            <div class="space-y-8">

                <!-- Tickets Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($mainEvent->tickets->where('available_seats', '>', 0) as $ticket)
                    @php
                        // Calculate total availability across all days
                        $totalAvailable = $ticket->available_seats;
                        $totalSold = $ticket->sold_seats;
                        $totalSeats = $ticket->total_seats;
                        
                        if (!$ticket->is_combo) {
                            // For single-day tickets, calculate total across both days
                            // Include sold, active, pending, and scanned statuses (scanned tickets are still considered sold)
                            $day1Sold = \App\Models\PurchaseTicket::where('ticket_type_id', $ticket->id)
                                ->where('event_day_name', $mainEvent->getEventDays()[0]['day_name'])
                                ->whereIn('status', ['sold', 'active', 'pending', 'scanned'])
                                ->count();
                            $day2Sold = \App\Models\PurchaseTicket::where('ticket_type_id', $ticket->id)
                                ->where('event_day_name', $mainEvent->getEventDays()[1]['day_name'])
                                ->whereIn('status', ['sold', 'active', 'pending', 'scanned'])
                                ->count();
                            $totalSold = $day1Sold + $day2Sold;
                            $totalAvailable = $totalSeats - $totalSold;
                        }
                    @endphp
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 group ticket-card border border-gray-100 overflow-hidden flex flex-col h-full" 
                         data-ticket="{{ $ticket->name }}" 
                         data-price="{{ $ticket->price }}" 
                         data-available="{{ $totalAvailable }}"
                         data-description="{{ $ticket->description }}"
                         data-total="{{ $totalSeats }}"
                         data-sold="{{ $totalSold }}">
                        
                        <!-- Top Section with Price -->
                        <div class="bg-gradient-to-r from-wwc-primary to-red-500 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-bold">{{ $ticket->name }}</h3>
                                </div>
                                <div class="text-right">
                                    <div class="text-3xl font-black">RM{{ number_format($ticket->price, 0) }}</div>
                                    <div class="text-sm opacity-80">per ticket</div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="p-6 flex flex-col flex-1">
                            @if($ticket->description)
                            <p class="text-gray-600 text-sm mb-4">{{ $ticket->description }}</p>
                            @endif

                            <!-- Bottom Section: Day Info + Buttons -->
                            <div class="mt-auto space-y-4">
                                <!-- Day Availability Info -->
                                @if($ticket->is_combo)
                                    @php
                                        // For combo tickets: 4 combo tickets = 8 PurchaseTicket records (4 Day 1 + 4 Day 2)
                                        // Count PurchaseTicket records per day for display
                                        $day1Sold = \App\Models\PurchaseTicket::where('ticket_type_id', $ticket->id)
                                            ->where('event_day_name', $mainEvent->getEventDays()[0]['day_name'])
                                            ->whereIn('status', ['sold', 'active', 'pending', 'scanned'])
                                            ->count();
                                        $day2Sold = \App\Models\PurchaseTicket::where('ticket_type_id', $ticket->id)
                                            ->where('event_day_name', $mainEvent->getEventDays()[1]['day_name'])
                                            ->whereIn('status', ['sold', 'active', 'pending', 'scanned'])
                                            ->count();
                                        
                                        // Available per day: total seats minus sold count for that day
                                        $day1Available = $totalSeats - $day1Sold;
                                        $day2Available = $totalSeats - $day2Sold;
                                    @endphp
                                    
                                    <div>
                                        <div class="space-y-3">
                                            <!-- Day 1 -->
                                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-3">
                                                        <div class="text-sm font-bold text-gray-800">Day 1</div>
                                                    </div>
                                                    <div class="flex items-center gap-6">
                                                        <div class="text-center">
                                                            <div class="text-lg font-bold text-green-600">{{ $day1Available }}</div>
                                                            <div class="text-xs text-gray-600">Available</div>
                                                        </div>
                                                        <div class="text-center">
                                                            <div class="text-lg font-bold text-red-600">{{ $day1Sold }}</div>
                                                            <div class="text-xs text-gray-600">Sold</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Day 2 -->
                                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-3">
                                                        <div class="text-sm font-bold text-gray-800">Day 2</div>
                                                    </div>
                                                    <div class="flex items-center gap-6">
                                                        <div class="text-center">
                                                            <div class="text-lg font-bold text-green-600">{{ $day2Available }}</div>
                                                            <div class="text-xs text-gray-600">Available</div>
                                                        </div>
                                                        <div class="text-center">
                                                            <div class="text-lg font-bold text-red-600">{{ $day2Sold }}</div>
                                                            <div class="text-xs text-gray-600">Sold</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="space-y-3">
                                <a href="{{ route('public.tickets.cart', $mainEvent) }}" 
                                   class="block w-full text-center px-6 py-3 bg-gradient-to-r from-wwc-primary to-red-500 text-white font-bold rounded-lg hover:from-red-500 hover:to-wwc-primary transition-all duration-300 shadow-md hover:shadow-lg"
                                   onclick="event.stopPropagation()">
                                    <div class="flex items-center justify-center gap-2">
                                        <i class='bx bx-shopping-cart'></i>
                                        <span>Get This Ticket</span>
                                    </div>
                                </a>
                                
                                <button type="button" 
                                        class="view-details-btn w-full px-4 py-2 bg-transparent border-2 border-wwc-primary text-wwc-primary font-medium rounded-lg hover:bg-wwc-primary hover:text-white transition-all duration-300"
                                        data-ticket="{{ $ticket->name }}" 
                                        data-price="{{ $ticket->price }}" 
                                        data-available="{{ $totalAvailable }}"
                                        data-description="{{ $ticket->description }}"
                                        data-total="{{ $totalSeats }}"
                                        data-sold="{{ $totalSold }}"
                                        data-is-combo="{{ $ticket->is_combo ? 'true' : 'false' }}"
                                        data-day1-available="{{ $day1Available }}"
                                        data-day1-sold="{{ $day1Sold }}"
                                        data-day2-available="{{ $day2Available }}"
                                        data-day2-sold="{{ $day2Sold }}"
                                        data-seating-image="{{ $ticket->seating_image ? asset('storage/' . $ticket->seating_image) : '' }}">
                                    <div class="flex items-center justify-center gap-2">
                                        <i class='bx bx-info-circle'></i>
                                        <span>View Details</span>
                                    </div>
                                </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($mainEvent->tickets->where('available_seats', '>', 0)->count() == 0)
                <div class="text-center py-12">
                    <i class='bx bx-ticket text-5xl text-wwc-neutral-300 mb-4'></i>
                    <p class="text-wwc-neutral-500 text-lg">No tickets available</p>
                </div>
                @endif
            </div>
        @else
            <!-- Single-day event: Original display -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($mainEvent->tickets as $ticket)
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-wwc-neutral-200 hover:border-wwc-primary ticket-card flex flex-col" 
                 data-ticket="{{ $ticket->name }}" 
                 data-price="{{ $ticket->price }}" 
                 data-available="{{ $ticket->available_seats }}"
                 data-description="{{ $ticket->description }}"
                 data-total="{{ $ticket->total_seats }}"
                 data-sold="{{ $ticket->sold_seats }}">
                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-wwc-primary bg-opacity-10 text-wwc-primary">
                            <i class='bx bx-ticket mr-1'></i>
                            {{ $ticket->name }}
                        </span>
                        @if($ticket->status === 'active' && $ticket->available_seats > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            <i class='bx bx-check-circle mr-1'></i>
                            Active
                        </span>
                        @elseif($ticket->status === 'sold_out' || $ticket->available_seats <= 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                            <i class='bx bx-x-circle mr-1'></i>
                            Sold Out
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                            <i class='bx bx-pause-circle mr-1'></i>
                            Inactive
                        </span>
                        @endif
                    </div>

                    <div class="text-center mb-6">
                        <div class="text-4xl font-bold text-wwc-primary mb-2">RM{{ number_format($ticket->price, 2) }}</div>
                        <div class="text-sm text-wwc-neutral-600">per ticket</div>
                    </div>

                    @if($ticket->description)
                    <p class="text-sm text-wwc-neutral-600 mb-4 text-center">{{ $ticket->description }}</p>
                    @endif

                    <div class="space-y-3 mb-6">
                        @if($mainEvent->isMultiDay())
                            @if($ticket->is_combo)
                                <!-- Combo ticket display (for multi-day purchases) -->
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-wwc-neutral-600">Total Seats (Per Day):</span>
                                    <span class="font-semibold">{{ number_format($ticket->total_seats) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-wwc-neutral-600">Available (Per Day):</span>
                                    <span class="font-semibold text-green-600">{{ number_format($ticket->available_seats) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-wwc-neutral-600">Sold (Per Day):</span>
                                    <span class="font-semibold text-wwc-neutral-900">{{ number_format($ticket->sold_seats) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-wwc-neutral-600">Total Capacity ({{ $mainEvent->getDurationInDays() }} days):</span>
                                    <span class="font-semibold text-blue-600">{{ number_format($ticket->total_seats * $mainEvent->getDurationInDays()) }}</span>
                                </div>
                            @else
                                <!-- Single-day ticket display (for individual day purchases) -->
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-wwc-neutral-600">Total Seats (Per Day):</span>
                                    <span class="font-semibold">{{ number_format($ticket->total_seats) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-wwc-neutral-600">Available (Per Day):</span>
                                    <span class="font-semibold text-green-600">{{ number_format($ticket->available_seats) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-wwc-neutral-600">Sold (Per Day):</span>
                                    <span class="font-semibold text-wwc-neutral-900">{{ number_format($ticket->sold_seats) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-wwc-neutral-600">Event Duration:</span>
                                    <span class="font-semibold text-blue-600">{{ $mainEvent->getDurationInDays() }} days</span>
                                </div>
                            @endif
                        @else
                            <!-- Single-day event display -->
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-wwc-neutral-600">Total Seats:</span>
                                <span class="font-semibold">{{ number_format($ticket->total_seats) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-wwc-neutral-600">Available:</span>
                                <span class="font-semibold text-green-600">{{ number_format($ticket->available_seats) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-wwc-neutral-600">Sold:</span>
                                <span class="font-semibold text-wwc-neutral-900">{{ number_format($ticket->sold_seats) }}</span>
                            </div>
                        @endif
                    </div>

                    @if($ticket->total_seats > 0)
                    <div class="mb-6">
                        <div class="flex justify-between text-xs text-wwc-neutral-500 mb-2">
                            <span>Availability {{ $mainEvent->isMultiDay() ? '(Per Day)' : '' }}</span>
                            <span>{{ round(($ticket->available_seats / $ticket->total_seats) * 100, 1) }}%</span>
                        </div>
                        <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-wwc-primary to-green-500 h-2 rounded-full transition-all duration-300" 
                                 style="width: {{ ($ticket->available_seats / $ticket->total_seats) * 100 }}%"></div>
                        </div>
                        @if($mainEvent->isMultiDay())
                            @if($ticket->is_combo)
                            <div class="mt-2 text-xs text-wwc-neutral-500 text-center">
                                {{ $mainEvent->getDurationInDays() }} days × {{ number_format($ticket->total_seats) }} seats = {{ number_format($ticket->total_seats * $mainEvent->getDurationInDays()) }} total capacity
                            </div>
                            @else
                            <div class="mt-2 text-xs text-wwc-neutral-500 text-center">
                                Choose any day from {{ $mainEvent->getDurationInDays() }}-day event
                            </div>
                            @endif
                        @endif
                    </div>
                    @endif

                    <div class="space-y-3 mt-auto">
                        @if($ticket->available_seats > 0)
                        <a href="{{ route('public.tickets.cart', $mainEvent) }}" 
                           class="inline-flex items-center justify-center w-full px-4 py-3 bg-wwc-primary text-white font-semibold rounded-lg hover:bg-wwc-primary-dark transition-colors duration-200 shadow-lg hover:shadow-xl"
                           onclick="event.stopPropagation()">
                            <i class='bx bx-shopping-cart mr-2'></i>
                            Select This Ticket
                        </a>
                        @else
                        <button disabled 
                                class="inline-flex items-center justify-center w-full px-4 py-3 bg-wwc-neutral-300 text-wwc-neutral-500 font-semibold rounded-lg cursor-not-allowed">
                            <i class='bx bx-x-circle mr-2'></i>
                            Sold Out
                        </button>
                        @endif
                        
                        <button type="button" 
                                class="view-details-btn inline-flex items-center justify-center w-full px-4 py-2 border border-wwc-primary text-wwc-primary font-semibold rounded-lg hover:bg-wwc-primary hover:text-white transition-colors duration-200"
                                data-ticket="{{ $ticket->name }}" 
                                data-price="{{ $ticket->price }}" 
                                data-available="{{ $ticket->available_seats }}"
                                data-description="{{ $ticket->description }}"
                                data-total="{{ $ticket->total_seats }}"
                                data-sold="{{ $ticket->sold_seats }}"
                                data-is-combo="{{ $ticket->is_combo ? 'true' : 'false' }}"
                                data-day1-available=""
                                data-day1-sold=""
                                data-day2-available=""
                                data-day2-sold=""
                                data-seating-image="{{ $ticket->seating_image ? asset('storage/' . $ticket->seating_image) : '' }}">
                            <i class='bx bx-info-circle mr-2'></i>
                            View Details
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
            </div>
        @endif
    </div>
</div>
@endif

<!-- Call to Action Section -->
@if($mainEvent)
<div class="bg-wwc-primary text-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Get Your Tickets?</h2>
        <p class="text-lg mb-8 text-wwc-neutral-100">
            Secure your seats now to experience the battles unfold live in the arena!
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('public.tickets.cart', $mainEvent) }}" 
               class="inline-flex items-center justify-center px-8 py-4 bg-white text-wwc-primary font-semibold rounded-lg hover:bg-wwc-neutral-100 transition-colors duration-200 shadow-lg">
                <i class='bx bx-ticket mr-2'></i>
                Get Tickets Now
            </a>
            <a href="{{ route('public.about') }}" 
               class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-wwc-primary transition-colors duration-200">
                <i class='bx bx-info-circle mr-2'></i>
                Learn More
            </a>
        </div>
    </div>
</div>
@endif

@if(!$mainEvent)
<!-- No Events Available Section -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center">
    <!-- Hero Section -->
    <div class="relative overflow-hidden w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <!-- Animated Icon -->
                <div class="mx-auto h-32 w-32 text-wwc-primary mb-8 animate-pulse">
                    <i class='bx bx-calendar-star text-8xl'></i>
                </div>
                
                <!-- Main Message -->
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    Exciting Events Coming Soon!
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    We're preparing something amazing for you. Stay tuned for the best events and experiences!
                </p>
                
                <!-- Features Grid -->
                <div class="grid md:grid-cols-3 gap-8 mt-16 mb-16">
                    <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <div class="text-wwc-primary text-4xl mb-4">
                            <i class='bx bx-calendar-check'></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Premium Events</h3>
                        <p class="text-gray-600">Access to exclusive concerts, sports, and entertainment events</p>
                    </div>
                    
                    <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <div class="text-wwc-primary text-4xl mb-4">
                            <i class='bx bx-shield-alt-2'></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure Booking</h3>
                        <p class="text-gray-600">Safe and reliable ticket purchasing with instant confirmation</p>
                    </div>
                    
                    <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <div class="text-wwc-primary text-4xl mb-4">
                            <i class='bx bx-support'></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">24/7 Support</h3>
                        <p class="text-gray-600">Round-the-clock customer support for all your needs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($mainEvent)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Ticket images mapping
    const ticketImages = {
        'Warzone Exclusive': 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=800&h=600&fit=crop&crop=center',
        'Warzone VIP': 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop&crop=center',
        'Warzone Grandstand': 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800&h=600&fit=crop&crop=center',
        'Warzone Premium Ringside': 'https://images.unsplash.com/photo-1571266028243-e4732b0a0a6e?w=800&h=600&fit=crop&crop=center',
        'Level 1 Zone A/B/C/D': 'https://images.unsplash.com/photo-1571266028243-e4732b0a0a6e?w=800&h=600&fit=crop&crop=center',
        'Level 2 Zone A/B/C/D': 'https://images.unsplash.com/photo-1571266028243-e4732b0a0a6e?w=800&h=600&fit=crop&crop=center',
        'Standing Zone A/B': 'https://images.unsplash.com/photo-1571266028243-e4732b0a0a6e?w=800&h=600&fit=crop&crop=center'
    };
    
    // Function to show ticket details popup
    function showTicketDetails(ticketName, ticketPrice, ticketAvailable, ticketDescription, ticketTotal, ticketSold, isCombo = false, day1Available = null, day1Sold = null, day2Available = null, day2Sold = null, seatingImage = null) {
        const ticketImage = ticketImages[ticketName] || 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=800&h=600&fit=crop&crop=center';
        
        // Calculate availability percentage
        const availabilityPercentage = ticketTotal > 0 ? Math.round((ticketAvailable / ticketTotal) * 100) : 0;
        
        // Generate day-by-day breakdown HTML if it's a combo ticket
        let dayBreakdownHtml = '';
        if (isCombo && day1Available !== null && day2Available !== null) {
            dayBreakdownHtml = `
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-5 border border-green-200">
                    <div class="flex items-center mb-3">
                        <i class='bx bx-calendar text-green-600 text-xl mr-2'></i>
                        <h4 class="font-bold text-gray-900 text-lg">Daily Availability</h4>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm text-gray-700">
                            <div class="flex items-center">
                                <i class='bx bx-calendar-check text-green-500 mr-2'></i>
                                <span>Day 1</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-green-600 font-semibold">${day1Available} Available</span>
                                <span class="text-red-600 font-semibold">${day1Sold} Sold</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm text-gray-700">
                            <div class="flex items-center">
                                <i class='bx bx-calendar-check text-blue-500 mr-2'></i>
                                <span>Day 2</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-green-600 font-semibold">${day2Available} Available</span>
                                <span class="text-red-600 font-semibold">${day2Sold} Sold</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Show SweetAlert with ticket details
        Swal.fire({
            title: `Ticket Details`,
            html: `
                <div class="text-left">
                    <!-- Ticket Header Info -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <i class='bx bx-star text-yellow-500 text-lg'></i>
                                <span class="text-lg font-semibold text-gray-800">${ticketName}</span>
                                <i class='bx bx-star text-yellow-500 text-lg'></i>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-red-600">RM${parseFloat(ticketPrice).toLocaleString()}</div>
                                <div class="text-xs text-gray-500">per ticket</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Seating Layout at Top -->
                    ${seatingImage ? `
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-5 border border-green-200 mb-6">
                        <div class="flex items-center mb-3">
                            <i class='bx bx-image text-green-600 text-xl mr-2'></i>
                            <h4 class="font-bold text-gray-900 text-lg">Seating Layout</h4>
                        </div>
                        <div class="mt-3">
                            <img src="${seatingImage}" 
                                 alt="Seating layout for ${ticketName}" 
                                 class="w-full rounded-lg border border-green-200 shadow-sm">
                        </div>
                    </div>
                    ` : ''}
                    
                    <div class="space-y-4">
                        ${dayBreakdownHtml}
                    </div>
                </div>
            `,
            width: '650px',
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonText: '<i class="bx bx-shopping-cart mr-2"></i>Get This Ticket',
            cancelButtonText: '<i class="bx bx-x mr-2"></i>Close',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            customClass: {
                popup: 'rounded-2xl shadow-2xl',
                title: 'text-2xl font-bold text-gray-900',
                confirmButton: 'px-8 py-4 rounded-xl font-bold text-lg hover:shadow-lg transition-all duration-300 transform hover:scale-105',
                cancelButton: 'px-8 py-4 rounded-xl font-semibold text-lg hover:shadow-md transition-all duration-300'
            },
            didOpen: () => {
                // Add click handler to confirm button
                const confirmButton = document.querySelector('.swal2-confirm');
                if (confirmButton) {
                    confirmButton.addEventListener('click', () => {
                        // Redirect to cart page
                        window.location.href = '{{ $mainEvent ? route("public.tickets.cart", $mainEvent) : "#" }}';
                    });
                }
            }
        });
    }


    // Add click event to each View Details button
    const viewDetailsBtns = document.querySelectorAll('.view-details-btn');
    viewDetailsBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent triggering the card click event
            
            const ticketName = this.dataset.ticket;
            const ticketPrice = this.dataset.price;
            const ticketAvailable = this.dataset.available;
            const ticketDescription = this.dataset.description;
            const ticketTotal = this.dataset.total;
            const ticketSold = this.dataset.sold;
            const isCombo = this.dataset.isCombo === 'true';
            const day1Available = this.dataset.day1Available || null;
            const day1Sold = this.dataset.day1Sold || null;
            const day2Available = this.dataset.day2Available || null;
            const day2Sold = this.dataset.day2Sold || null;
            const seatingImage = this.dataset.seatingImage || null;
            
            showTicketDetails(ticketName, ticketPrice, ticketAvailable, ticketDescription, ticketTotal, ticketSold, isCombo, day1Available, day1Sold, day2Available, day2Sold, seatingImage);
        });
    });
});

// Seating modal functions
function openSeatingModal(imageSrc, title) {
    // Create modal if it doesn't exist
    let modal = document.getElementById('seatingModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'seatingModal';
        modal.className = 'fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full hidden z-50';
        modal.innerHTML = `
            <div class="relative top-20 mx-auto p-5 w-11/12 max-w-4xl">
                <div class="bg-white rounded-lg shadow-lg">
                    <div class="flex justify-between items-center p-4 border-b border-gray-200">
                        <h3 id="seatingModalTitle" class="text-lg font-semibold text-gray-900">Seating Layout</h3>
                        <button onclick="closeSeatingModal()" class="text-gray-400 hover:text-gray-600">
                            <i class='bx bx-x text-2xl'></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <img id="seatingModalImage" src="" alt="Seating layout" class="w-full h-auto rounded-lg">
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    document.getElementById('seatingModalImage').src = imageSrc;
    document.getElementById('seatingModalTitle').textContent = title;
    modal.classList.remove('hidden');
}

function closeSeatingModal() {
    const modal = document.getElementById('seatingModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Close seating modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('seatingModal');
    if (modal && e.target === modal) {
        closeSeatingModal();
    }
});

// Arena Layout Modal
function openArenaLayoutModal() {
    // Get the image source
    let imageSrc = "{{ asset('images/all-layout.jpeg') }}";
    
    // Check if modal exists, if not create it
    let modal = document.getElementById('arenaLayoutModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'arenaLayoutModal';
        modal.className = 'fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4';
        modal.innerHTML = `
            <div class="relative bg-white rounded-lg shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-hidden">
                <div class="flex justify-between items-center p-4 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">N9 Arena Layout</h3>
                    <button onclick="closeArenaLayoutModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class='bx bx-x text-3xl'></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]">
                    <img id="arenaModalImage" src="" alt="Arena Layout" class="w-full h-auto rounded-lg">
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    document.getElementById('arenaModalImage').src = imageSrc;
    modal.classList.remove('hidden');
}

function closeArenaLayoutModal() {
    const modal = document.getElementById('arenaLayoutModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Close arena modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('arenaLayoutModal');
    if (modal && e.target === modal) {
        closeArenaLayoutModal();
    }
});

// Close arena modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeArenaLayoutModal();
    }
});
</script>
@endpush
@endif
@endsection
