@extends('layouts.public')

@section('title', 'Browse Events - Warzone Ticketing')
@section('description', 'Browse all available events and find the perfect tickets for concerts, sports, and entertainment.')

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold font-display mb-4">
                Browse Events
            </h1>
            <p class="text-xl text-wwc-primary-light max-w-2xl mx-auto">
                Discover amazing events and get your tickets today
            </p>
        </div>
    </div>
</section>

<!-- Search and Filters -->
<section class="py-8 bg-wwc-neutral-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-6">
            <form method="GET" action="{{ route('public.events') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search Events</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           placeholder="Event name, venue, description..."
                           class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                </div>
                <div>
                    <label for="venue" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Venue</label>
                    <select name="venue" id="venue" class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        <option value="">All Venues</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue }}" {{ request('venue') == $venue ? 'selected' : '' }}>
                                {{ $venue }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="date_from" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">From Date</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                           class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-2xl text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-search text-sm mr-2'></i>
                        Search Events
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Events Grid -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-wwc-neutral-900 font-display">All Events</h2>
                <p class="text-wwc-neutral-600">Showing {{ $events->count() }} of {{ $events->total() }} events</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-wwc-neutral-500">
                    <span class="font-semibold text-wwc-primary">{{ $events->sum(function($event) { return $event->zones->sum('available_seats'); }) }}</span> tickets available
                </div>
                <div class="text-sm text-wwc-neutral-500">
                    <span class="font-semibold text-wwc-success">{{ $events->sum(function($event) { return $event->zones->sum('sold_seats'); }) }}</span> tickets sold
                </div>
            </div>
        </div>

        @if($events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($events as $event)
                <div class="bg-white rounded-2xl shadow-lg border border-wwc-neutral-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <!-- Event Image Placeholder -->
                    <div class="h-48 bg-gradient-to-br from-wwc-primary-light to-wwc-accent-light flex items-center justify-center">
                        <div class="text-center">
                            <i class='bx bx-calendar-event text-6xl text-wwc-primary mx-auto mb-4'></i>
                            <p class="text-lg font-semibold text-wwc-primary">{{ $event->name }}</p>
                        </div>
                    </div>

                    <!-- Event Details -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-wwc-neutral-900 font-display mb-3">{{ $event->name }}</h3>
                        <p class="text-wwc-neutral-600 text-sm mb-4">{{ Str::limit($event->description, 100) }}</p>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-wwc-neutral-600">
                                <i class='bx bx-calendar text-sm mr-2 text-wwc-neutral-400'></i>
                                {{ $event->getFormattedDateRange() }}
                            </div>
                            <div class="flex items-center text-wwc-neutral-600">
                                <i class='bx bx-map text-sm mr-2 text-wwc-neutral-400'></i>
                                {{ $event->venue ?? 'Venue TBA' }}
                            </div>
                        </div>

                        <!-- Status and Tickets -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                @if($event->status === 'On Sale') bg-wwc-success text-white
                                @elseif($event->status === 'Sold Out') bg-wwc-error text-white
                                @else bg-wwc-neutral-400 text-white
                                @endif">
                                {{ $event->status }}
                            </span>
                            <div class="text-sm text-wwc-neutral-500">
                                {{ $event->zones->sum('sold_seats') ?? 0 }} tickets sold
                            </div>
                        </div>

                        <!-- Ticket Zones -->
                        @if($event->zones->count() > 0)
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-wwc-neutral-900 mb-2">Available Zones</h4>
                                <div class="space-y-2">
                                    @foreach($event->zones->take(3) as $zone)
                                        <div class="flex items-center justify-between p-2 bg-wwc-neutral-50 rounded-lg">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-2 h-2 bg-wwc-primary rounded-full"></div>
                                                <span class="text-sm font-medium text-wwc-neutral-900">{{ $zone->name }}</span>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm font-semibold text-wwc-primary">RM{{ number_format($zone->price, 0) }}</div>
                                                <div class="text-xs text-wwc-neutral-500">{{ $zone->available_seats }} available</div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($event->zones->count() > 3)
                                        <div class="text-xs text-wwc-neutral-500 text-center">
                                            +{{ $event->zones->count() - 3 }} more zones
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Action Button -->
                        @if($event->status === 'On Sale' && $event->zones->where('available_seats', '>', 0)->count() > 0)
                            <a href="{{ route('public.events.show', $event) }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-2xl text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-receipt text-sm mr-2'></i>
                                Get Tickets
                            </a>
                        @elseif($event->status === 'Sold Out' || $event->zones->where('available_seats', '>', 0)->count() === 0)
                            <button disabled class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-2xl text-white bg-wwc-neutral-400 cursor-not-allowed">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Sold Out
                            </button>
                        @else
                            <button disabled class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-2xl text-white bg-wwc-neutral-400 cursor-not-allowed">
                                <i class='bx bx-time text-sm mr-2'></i>
                                {{ $event->status }}
                            </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $events->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="mx-auto h-16 w-16 rounded-2xl bg-wwc-neutral-100 flex items-center justify-center mb-4">
                    <i class='bx bx-calendar text-3xl text-wwc-neutral-400'></i>
                </div>
                <h3 class="text-xl font-semibold text-wwc-neutral-900 mb-2">No events found</h3>
                <p class="text-wwc-neutral-600 mb-6">No events match your current filters.</p>
                <a href="{{ route('public.events') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-2xl transition-colors duration-200">
                    <i class='bx bx-refresh text-sm mr-2'></i>
                    Clear Filters
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
