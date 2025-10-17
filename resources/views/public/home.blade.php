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
                    {{ $mainEvent->status }}
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

            <!-- Event Visual/Image Placeholder -->
            <div class="relative">
                <div class="bg-wwc-neutral-800 rounded-2xl p-8 shadow-2xl text-center">
                    <div class="py-12">
                        <i class='bx bx-calendar-event text-8xl text-wwc-neutral-600 mb-6'></i>
                        <h3 class="text-2xl font-bold text-wwc-neutral-300 mb-4">Premium Event Experience</h3>
                        <p class="text-wwc-neutral-400 text-lg">
                            Join us for an unforgettable experience with world-class entertainment and exclusive access.
                        </p>
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
            <h2 class="text-3xl font-bold text-wwc-neutral-900 mb-4">Available Ticket Types</h2>
            <p class="text-lg text-wwc-neutral-600">Choose your preferred seating and experience level</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($mainEvent->tickets as $ticket)
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-wwc-neutral-200 hover:border-wwc-primary cursor-pointer ticket-card" 
                 data-ticket="{{ $ticket->name }}" 
                 data-price="{{ $ticket->price }}" 
                 data-available="{{ $ticket->available_seats }}"
                 data-description="{{ $ticket->description }}"
                 data-total="{{ $ticket->total_seats }}"
                 data-sold="{{ $ticket->sold_seats }}">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-wwc-primary bg-opacity-10 text-wwc-primary">
                            <i class='bx bx-ticket mr-1'></i>
                            {{ $ticket->name }}
                        </span>
                        @if($ticket->available_seats > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            <i class='bx bx-check-circle mr-1'></i>
                            Available
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                            <i class='bx bx-x-circle mr-1'></i>
                            Sold Out
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
                    </div>

                    @if($ticket->total_seats > 0)
                    <div class="mb-6">
                        <div class="flex justify-between text-xs text-wwc-neutral-500 mb-2">
                            <span>Availability</span>
                            <span>{{ round(($ticket->available_seats / $ticket->total_seats) * 100, 1) }}%</span>
                        </div>
                        <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-wwc-primary to-green-500 h-2 rounded-full transition-all duration-300" 
                                 style="width: {{ ($ticket->available_seats / $ticket->total_seats) * 100 }}%"></div>
                        </div>
                    </div>
                    @endif

                    <div class="space-y-3">
                        @if($ticket->available_seats > 0)
                        <a href="{{ route('public.tickets.cart', $mainEvent) }}" 
                           class="inline-flex items-center justify-center w-full px-4 py-3 bg-wwc-primary text-white font-semibold rounded-lg hover:bg-wwc-primary-dark transition-colors duration-200 shadow-lg hover:shadow-xl">
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
                                data-sold="{{ $ticket->sold_seats }}">
                            <i class='bx bx-info-circle mr-2'></i>
                            View Details
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>
@endif

<!-- Call to Action Section -->
@if($mainEvent)
<div class="bg-wwc-primary text-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Get Your Tickets?</h2>
        <p class="text-lg mb-8 text-wwc-neutral-100">
            Join thousands of satisfied customers who trust Warzone Ticketing for the {{ $mainEvent->name }} experience
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('public.tickets.cart', $mainEvent) }}" 
               class="inline-flex items-center justify-center px-8 py-4 bg-white text-wwc-primary font-semibold rounded-lg hover:bg-wwc-neutral-100 transition-colors duration-200 shadow-lg">
                <i class='bx bx-ticket mr-2'></i>
                Get {{ $mainEvent->name }} Tickets
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
    function showTicketDetails(ticketName, ticketPrice, ticketAvailable, ticketDescription, ticketTotal, ticketSold) {
        const ticketImage = ticketImages[ticketName] || 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=800&h=600&fit=crop&crop=center';
        
        // Calculate availability percentage
        const availabilityPercentage = ticketTotal > 0 ? Math.round((ticketAvailable / ticketTotal) * 100) : 0;
        
        // Show SweetAlert with ticket details
        Swal.fire({
            title: ticketName,
            html: `
                <div class="text-left">
                    <div class="mb-4">
                        <img src="${ticketImage}" alt="${ticketName}" class="w-full h-48 object-cover rounded-lg mb-4">
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-700">Price:</span>
                            <span class="text-2xl font-bold text-red-600">RM${parseFloat(ticketPrice).toLocaleString()}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-700">Available Seats:</span>
                            <span class="text-lg font-semibold text-green-600">${ticketAvailable} seats</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-700">Total Seats:</span>
                            <span class="text-lg font-semibold text-gray-600">${ticketTotal} seats</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-700">Sold:</span>
                            <span class="text-lg font-semibold text-gray-600">${ticketSold} seats</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-700">Availability:</span>
                            <span class="text-lg font-semibold text-blue-600">${availabilityPercentage}%</span>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 mt-4">
                            <h4 class="font-semibold text-gray-900 mb-2">Ticket Description</h4>
                            <p class="text-gray-600 text-sm">${ticketDescription || 'Premium seating with excellent views and great value for money.'}</p>
                        </div>
                    </div>
                </div>
            `,
            width: '600px',
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonText: 'Get This Ticket',
            cancelButtonText: 'Close',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            customClass: {
                popup: 'rounded-2xl',
                title: 'text-2xl font-bold text-gray-900',
                confirmButton: 'px-6 py-3 rounded-lg font-semibold',
                cancelButton: 'px-6 py-3 rounded-lg font-semibold'
            },
            didOpen: () => {
                // Add click handler to confirm button
                const confirmButton = document.querySelector('.swal2-confirm');
                if (confirmButton) {
                    confirmButton.addEventListener('click', () => {
                        // Redirect to cart page
                        window.location.href = '{{ route("public.tickets.cart", $mainEvent) }}';
                    });
                }
            }
        });
    }

    // Add click event to each ticket card
    const ticketCards = document.querySelectorAll('.ticket-card');
    ticketCards.forEach(card => {
        card.addEventListener('click', function() {
            const ticketName = this.dataset.ticket;
            const ticketPrice = this.dataset.price;
            const ticketAvailable = this.dataset.available;
            const ticketDescription = this.dataset.description;
            const ticketTotal = this.dataset.total;
            const ticketSold = this.dataset.sold;
            
            showTicketDetails(ticketName, ticketPrice, ticketAvailable, ticketDescription, ticketTotal, ticketSold);
        });
    });

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
            
            showTicketDetails(ticketName, ticketPrice, ticketAvailable, ticketDescription, ticketTotal, ticketSold);
        });
    });
});
</script>
@endpush
@endsection
