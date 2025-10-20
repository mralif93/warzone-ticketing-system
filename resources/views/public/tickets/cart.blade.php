@extends('layouts.public')

@section('title', 'Checkout - ' . $event->name)
@section('description', 'Complete your ticket purchase for ' . $event->name . '.')

@section('content')
@if(!$event)
    <div class="min-h-screen bg-gray-50 flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">No Event Selected</h1>
            <p class="text-gray-600 mb-4">Please select an event first.</p>
            <a href="{{ route('public.events') }}" class="bg-wwc-primary text-white px-6 py-3 rounded-lg hover:bg-wwc-primary-dark transition-colors">Browse Events</a>
        </div>
    </div>
@elseif(!auth()->check())
    <!-- Login Required Section -->
    <div class="min-h-screen bg-wwc-neutral-50 flex items-center justify-center">
        <div class="max-w-md w-full mx-4">
            <div class="bg-white rounded-2xl shadow-lg border border-wwc-neutral-200 p-8">
                <!-- Login Icon -->
                <div class="text-center mb-8">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-wwc-primary/10 mb-4">
                        <i class='bx bx-lock text-3xl text-wwc-primary'></i>
                    </div>
                    <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display mb-2">Login Required</h1>
                    <p class="text-wwc-neutral-600">Please login to proceed with your ticket purchase for</p>
                    <p class="text-lg font-semibold text-wwc-primary mt-2">{{ $event->name }}</p>
                </div>

                <!-- Event Preview -->
                <div class="bg-wwc-neutral-50 rounded-xl p-6 mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-wwc-neutral-900">Event Details</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-wwc-success/10 text-wwc-success border border-wwc-success/20">
                            <i class="bx bx-check-circle mr-1"></i>
                            On Sale
                        </span>
                    </div>
                    <div class="space-y-2 text-sm text-wwc-neutral-600">
                        <div class="flex items-center">
                            <i class='bx bx-calendar-alt mr-2 text-wwc-primary'></i>
                            <span>{{ $event->getFormattedDateRange() }}</span>
                        </div>
                        @if($event->venue)
                        <div class="flex items-center">
                            <i class='bx bx-map mr-2 text-wwc-primary'></i>
                            <span>{{ $event->venue }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Login Buttons -->
                <div class="space-y-4">
                    <a href="{{ route('login') }}" 
                       class="w-full bg-wwc-primary text-white py-3 px-6 rounded-xl font-semibold hover:bg-wwc-primary-dark transition-colors duration-200 flex items-center justify-center">
                        <i class='bx bx-log-in mr-2'></i>
                        Login to Continue
                    </a>
                    
                    <div class="text-center">
                        <span class="text-sm text-wwc-neutral-500">Don't have an account?</span>
                        <a href="{{ route('register') }}" class="text-sm text-wwc-primary hover:text-wwc-primary-dark font-medium ml-1">
                            Create Account
                        </a>
                    </div>
                </div>

                <!-- Back to Event -->
                <div class="mt-6 pt-6 border-t border-wwc-neutral-200">
                    <a href="{{ route('public.events.show', $event) }}" 
                       class="w-full bg-wwc-neutral-100 text-wwc-neutral-700 py-3 px-6 rounded-xl font-medium hover:bg-wwc-neutral-200 transition-colors duration-200 flex items-center justify-center">
                        <i class='bx bx-arrow-back mr-2'></i>
                        Back to Event
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
<div class="min-h-screen bg-gray-50">
    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 text-red-700 px-6 py-4 mx-4 mt-4">
            <div class="flex items-center">
                <i class="bx bx-error-circle text-xl mr-3"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Header Section -->
    <div class="bg-white border-b border-wwc-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center">
                <!-- Event Details -->
                    <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display mb-1">{{ $event->name }}</h1>
                    <p class="text-sm text-wwc-neutral-600">
                        {{ $event->getFormattedDateRange() }}
                        @if($event->venue)
                            • {{ $event->venue }}
                        @endif
                    </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">

        <div class="lg:col-span-2 space-y-6">
            <!-- Step 1: Purchase Type Selection -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark px-6 py-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                            <i class="bx bx-calendar-check text-lg text-white"></i>
                        </div>
                        <div>
                                <h2 class="text-base font-bold text-white">Step 1: Select Your Experience</h2>
                                <p class="text-red-100 text-xs">Choose how you want to attend the event</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Single Day Option -->
                        <div class="relative group">
                            <input type="radio" 
                                    id="single_day" 
                                    name="purchase_type" 
                                    value="single_day" 
                                    class="sr-only peer"
                                    {{ old('purchase_type', 'single_day') == 'single_day' ? 'checked' : '' }}>
                            <label for="single_day" 
                                    class="flex flex-col p-5 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-wwc-primary peer-checked:bg-wwc-primary/5 hover:border-wwc-primary/50 hover:shadow-md transition-all duration-200">
                                <div class="flex items-center mb-3">
                                    <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-wwc-primary peer-checked:bg-wwc-primary flex items-center justify-center transition-all duration-200" id="single_day_radio">
                                        <div class="w-4 h-4 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200 shadow-lg" id="single_day_dot"></div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <h3 class="text-lg font-bold text-gray-900 mr-2">Single Day</h3>
                                            <span class="text-sm text-gray-600">|</span>
                                            <div class="flex items-center ml-2">
                                                <i class="bx bx-time text-wwc-primary mr-1 text-sm"></i>
                                                <span class="text-sm text-gray-600">One day experience</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-gray-600 text-sm leading-relaxed">Perfect for those who want to experience the event for one day with full access to all activities and performances.</p>
                                @if($event->isMultiDay())
                                    <div class="flex items-center text-xs text-wwc-primary bg-wwc-primary/10 px-2 py-1 rounded-lg mt-3">
                                        <i class="bx bx-calendar-alt mr-1"></i>
                                        <span class="font-medium">Choose your preferred day</span>
                                    </div>
                                @endif
                            </label>
                        </div>

                        <!-- Multi-Day Option (only show if event is multi-day) -->
                        @if($event->isMultiDay())
                        <div class="relative group">
                            <input type="radio" 
                                    id="multi_day" 
                                    name="purchase_type" 
                                    value="multi_day" 
                                    class="sr-only peer"
                                    {{ old('purchase_type') == 'multi_day' ? 'checked' : '' }}>
                            <label for="multi_day" 
                                    class="flex flex-col p-5 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-wwc-primary peer-checked:bg-wwc-primary/5 hover:border-wwc-primary/50 hover:shadow-md transition-all duration-200">
                                <div class="flex items-center mb-3">
                                    <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-wwc-primary peer-checked:bg-wwc-primary flex items-center justify-center transition-all duration-200" id="multi_day_radio">
                                        <div class="w-4 h-4 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200 shadow-lg" id="multi_day_dot"></div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <h3 class="text-lg font-bold text-gray-900 mr-2">Multi-Day Combo</h3>
                                            <span class="text-sm text-gray-600">|</span>
                                            <div class="flex items-center ml-2">
                                                <i class="bx bx-calendar-star text-wwc-primary mr-1 text-sm"></i>
                                                <span class="text-sm text-gray-600">Complete experience</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-gray-600 text-sm leading-relaxed">Get the full experience by attending both days with exclusive combo benefits and special access.</p>
                                @if($event->combo_discount_enabled && $event->combo_discount_percentage > 0)
                                    <div class="flex items-center text-xs text-white bg-gradient-to-r from-green-500 to-green-600 px-3 py-1 rounded-lg font-semibold mt-3">
                                        <i class="bx bx-gift mr-1"></i>
                                        <span>{{ $event->combo_discount_percentage }}% discount applied</span>
                                    </div>
                                @endif
                            </label>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ticket Selection Form -->
            <form action="{{ route('public.tickets.purchase', $event) }}" method="POST" id="ticket-form" class="space-y-6">
                @csrf
                <input type="hidden" name="purchase_type" id="purchase_type_input" value="single_day">
                
                <!-- Step 2: Single Day Ticket Selection -->
                <div id="single_day_selection" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                                <i class="bx bx-purchase-tag text-lg text-white"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-white">Step 2: Select Ticket Type</h2>
                                <p class="text-red-100 text-xs">Choose your ticket type and quantity</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8 space-y-6">
                        <!-- Ticket Type Selection -->
                        <div class="space-y-3">
                            <label for="ticket_type_id" class="block text-sm font-semibold text-gray-900">
                                Select Ticket Type <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="ticket_type_id" name="ticket_type_id" required
                                        class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary @error('ticket_type_id') border-red-500 @enderror text-sm bg-white shadow-sm transition-all duration-200 hover:border-gray-300">
                                    <option value="">Choose your preferred ticket type</option>
                                    @foreach($event->tickets->where('available_seats', '>', 0) as $ticket)
                                        @php
                                            // For single-day purchase, show overall availability
                                            $displayAvailable = $ticket->available_seats;
                                            $displaySold = $ticket->sold_seats;
                                        @endphp
                                        <option value="{{ $ticket->id }}" 
                                                data-price="{{ $ticket->price }}"
                                                data-available="{{ $displayAvailable }}"
                                                data-total="{{ $ticket->total_seats }}"
                                                data-sold="{{ $displaySold }}"
                                                {{ old('ticket_type_id') == $ticket->id ? 'selected' : '' }}>
                                            {{ $ticket->name }} - RM{{ number_format($ticket->price, 0) }} 
                                            @if($event->isMultiDay())
                                                @if($ticket->is_combo)
                                                    ({{ $displayAvailable }}/{{ $ticket->total_seats }} per day, {{ $ticket->total_seats * $event->getDurationInDays() }} total)
                                                @else
                                                    ({{ $displayAvailable }}/{{ $ticket->total_seats }} per day)
                                                @endif
                                            @else
                                                ({{ $displayAvailable }}/{{ $ticket->total_seats }} available)
                                            @endif
                                    </option>
                                @endforeach
                            </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="bx bx-chevron-down text-gray-400 text-lg"></i>
                                </div>
                            </div>
                            @error('ticket_type_id')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="bx bx-error-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Quantity Selection -->
                        <div class="space-y-3">
                            <label for="quantity" class="block text-sm font-semibold text-gray-900">
                                Quantity <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center justify-center space-x-4">
                                <button type="button" id="quantity-decrease" 
                                        class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center hover:bg-gray-50 hover:border-wwc-primary focus:outline-none focus:ring-2 focus:ring-wwc-primary transition-all duration-200 group">
                                    <i class="bx bx-minus text-lg text-gray-600 group-hover:text-wwc-primary"></i>
                                </button>
                                <div class="relative">
                                <input type="number" 
                                       id="quantity" 
                                       name="quantity" 
                                       min="1" 
                                       max="10" 
                                       value="{{ old('quantity', 1) }}" 
                                       required
                                            class="w-16 text-center px-2 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary @error('quantity') border-red-500 @enderror text-sm font-bold bg-gray-50 transition-all duration-200">
                                </div>
                                <button type="button" id="quantity-increase" 
                                        class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center hover:bg-gray-50 hover:border-wwc-primary focus:outline-none focus:ring-2 focus:ring-wwc-primary transition-all duration-200 group">
                                    <i class="bx bx-plus text-lg text-gray-600 group-hover:text-wwc-primary"></i>
                                </button>
                            </div>
                            <p class="text-sm text-gray-500 text-center" id="quantity-limit">Maximum 10 tickets per order</p>
                            @error('quantity')
                                <p class="text-red-500 text-sm mt-2 text-center flex items-center justify-center">
                                    <i class="bx bx-error-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                
                <!-- Step 2: Multi-Day Ticket Selection -->
                @if($event->isMultiDay())
                <div id="multi_day_selection" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hidden">
                    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                                <i class="bx bx-purchase-tag text-lg text-white"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-white">Step 2: Select Tickets for Each Day</h2>
                                <p class="text-red-100 text-xs">Choose your ticket types and quantities for each day</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8 space-y-6">
                        @foreach($event->getEventDays() as $index => $day)
                        <div class="border-2 border-gray-100 rounded-xl p-6 bg-gradient-to-br from-gray-50 to-white hover:shadow-md transition-all duration-200">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-wwc-primary to-wwc-primary-dark rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white font-bold text-sm">{{ $index + 1 }}</span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">{{ $day['day_name'] }} : {{ $day['display'] }}</h3>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-6">
                                <!-- Row 1: Ticket Type Selection for this day -->
                                <div class="space-y-3">
                                    <label for="day{{ $index + 1 }}_ticket_type" class="block text-sm font-semibold text-gray-900">
                                        Select Ticket Type <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select id="day{{ $index + 1 }}_ticket_type" 
                                                name="day{{ $index + 1 }}_ticket_type" 
                                                class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary day-ticket-select text-sm bg-white shadow-sm transition-all duration-200 hover:border-gray-300">
                                            <option value="">Choose your preferred ticket type</option>
                                            @foreach($event->tickets->where('available_seats', '>', 0) as $ticket)
                                                @php
                                                    // Calculate day-specific availability for single-day tickets
                                                    $dayAvailable = $ticket->available_seats;
                                                    $daySold = $ticket->sold_seats;
                                                    
                                                    if (!$ticket->is_combo) {
                                                        // For single-day tickets, calculate how many were sold/pending for this specific day
                                                        $daySold = \App\Models\PurchaseTicket::where('ticket_type_id', $ticket->id)
                                                            ->where('event_day_name', $day['day_name'])
                                                            ->whereIn('status', ['sold', 'pending'])
                                                            ->count();
                                                        $dayAvailable = $ticket->total_seats - $daySold;
                                                    }
                                                @endphp
                                                <option value="{{ $ticket->id }}" 
                                                        data-price="{{ $ticket->price }}"
                                                        data-available="{{ $dayAvailable }}"
                                                        data-total="{{ $ticket->total_seats }}"
                                                        data-sold="{{ $daySold }}">
                                                    {{ $ticket->name }} - RM{{ number_format($ticket->price, 0) }} 
                                                    @if($ticket->is_combo)
                                                        ({{ $dayAvailable }}/{{ $ticket->total_seats }} per day, {{ $ticket->total_seats * $event->getDurationInDays() }} total)
                                                    @else
                                                        ({{ $dayAvailable }}/{{ $ticket->total_seats }} per day)
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <i class="bx bx-chevron-down text-gray-400 text-lg"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Row 2: Quantity Selection for this day -->
                                <div class="space-y-3">
                                    <label for="day{{ $index + 1 }}_quantity" class="block text-sm font-semibold text-gray-900">
                                        Quantity <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center justify-center space-x-3">
                                        <button type="button" 
                                                class="day-quantity-decrease w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-500 hover:border-wwc-primary hover:text-wwc-primary hover:bg-wwc-primary/5 transition-all duration-200"
                                                data-day="{{ $index + 1 }}">
                                            <i class="bx bx-minus text-lg"></i>
                                        </button>
                                        <input type="number" 
                                               id="day{{ $index + 1 }}_quantity" 
                                               name="day{{ $index + 1 }}_quantity" 
                                               value="1" 
                                               min="1" 
                                               max="10" 
                                               class="day-quantity w-20 px-2 py-2 text-center border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm font-semibold">
                                        <button type="button" 
                                                class="day-quantity-increase w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-500 hover:border-wwc-primary hover:text-wwc-primary hover:bg-wwc-primary/5 transition-all duration-200"
                                                data-day="{{ $index + 1 }}">
                                            <i class="bx bx-plus text-lg"></i>
                                        </button>
                                    </div>
                                    <p class="text-sm text-gray-500">Maximum 10 tickets per day</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Step 3: Order Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark px-4 py-3">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                                <i class="bx bx-receipt text-lg text-white"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-white">Step 3: Order Summary</h2>
                                <p class="text-wwc-primary-light text-sm">Review your purchase details</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3" id="order-summary">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Subtotal</span>
                                <span class="text-base font-bold text-gray-900" id="subtotal">RM0</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100" id="combo-discount-row" style="display: none;">
                                <span class="text-sm text-gray-600">Combo Discount</span>
                                <span class="text-base font-bold text-green-600" id="combo-discount">-RM0</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Service Fee (5%)</span>
                                <span class="text-base font-bold text-gray-900" id="service-fee">RM0</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Tax (6%)</span>
                                <span class="text-base font-bold text-gray-900" id="tax">RM0</span>
                            </div>
                            <div class="flex justify-between items-center py-4">
                                <span class="text-lg font-bold text-gray-900">Total</span>
                                <span class="text-xl font-bold text-wwc-primary" id="total">RM0</span>
                            </div>
                        </div>
                    </div>
                </div>
                    
                <!-- Step 4: Customer Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                                <i class="bx bx-user text-lg text-white"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-white">Step 4: Customer Information</h2>
                                <p class="text-red-100 text-xs">Please provide your contact details</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label for="customer_name" class="block text-sm font-semibold text-gray-900">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                                <div class="relative">
                            <input type="text" 
                                    id="customer_name" 
                                    name="customer_name" 
                                    value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                    required
                                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary @error('customer_name') border-red-500 @enderror text-sm bg-white shadow-sm transition-all duration-200 hover:border-gray-300">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="bx bx-user text-gray-400 text-lg"></i>
                                    </div>
                                </div>
                            @error('customer_name')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="bx bx-error-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                            @enderror
                        </div>
                        
                            <div class="space-y-2">
                                <label for="customer_email" class="block text-sm font-semibold text-gray-900">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                                <div class="relative">
                            <input type="email" 
                                    id="customer_email" 
                                    name="customer_email" 
                                    value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                                    required
                                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary @error('customer_email') border-red-500 @enderror text-sm bg-white shadow-sm transition-all duration-200 hover:border-gray-300">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="bx bx-envelope text-gray-400 text-lg"></i>
                                    </div>
                                </div>
                            @error('customer_email')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="bx bx-error-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                            @enderror
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                                <label for="customer_phone" class="block text-sm font-semibold text-gray-900">
                                Phone Number
                            </label>
                            <div class="relative">
                            <input type="tel" 
                                    id="customer_phone" 
                                    name="customer_phone" 
                                    value="{{ old('customer_phone', auth()->user()->phone ?? '') }}"
                                        class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary @error('customer_phone') border-red-500 @enderror text-sm bg-white shadow-sm transition-all duration-200 hover:border-gray-300">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="bx bx-phone text-gray-400 text-lg"></i>
                                </div>
                            </div>
                            @error('customer_phone')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="bx bx-error-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Step 5: Payment Method Selection -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                                <i class="bx bx-credit-card text-lg text-white"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-white">Step 5: Payment Method</h2>
                                <p class="text-red-100 text-xs">Choose your preferred payment method</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="relative group h-full">
                                <input type="radio" 
                                        id="payment_credit_card" 
                                        name="payment_method" 
                                        value="credit_card" 
                                        class="sr-only peer"
                                        {{ old('payment_method', 'credit_card') == 'credit_card' ? 'checked' : '' }}>
                                <label for="payment_credit_card" 
                                        class="flex flex-col h-full justify-between p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-wwc-primary peer-checked:bg-wwc-primary/5 hover:border-wwc-primary/50 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center mb-3">
                                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-wwc-primary peer-checked:bg-wwc-primary flex items-center justify-center transition-all duration-200" id="payment_credit_card_radio">
                                            <div class="w-4 h-4 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200 shadow-lg" id="payment_credit_card_dot"></div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="flex items-center">
                                                <h3 class="text-sm font-bold text-gray-900 mr-2">Credit/Debit Card</h3>
                                                <span class="text-sm text-gray-600">|</span>
                                                <div class="flex items-center ml-2">
                                                    <i class="bx bx-shield text-wwc-primary mr-1 text-sm"></i>
                                                    <span class="text-sm text-gray-600">Secure payment</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-gray-600 text-sm leading-relaxed">Pay securely with your credit or debit card using our encrypted payment system.</p>
                                    <div class="flex items-center text-xs text-wwc-primary bg-wwc-primary/10 px-2 py-1 rounded-lg mt-3">
                                        <i class="bx bx-lock mr-1"></i>
                                        <span class="font-medium">SSL Encrypted</span>
                                    </div>
                                </label>
                            </div>

                            <div class="relative group h-full">
                                <input type="radio" 
                                        id="payment_online_banking" 
                                        name="payment_method" 
                                        value="online_banking" 
                                        class="sr-only peer"
                                        {{ old('payment_method') == 'online_banking' ? 'checked' : '' }}>
                                <label for="payment_online_banking" 
                                        class="flex flex-col h-full justify-between p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-wwc-primary peer-checked:bg-wwc-primary/5 hover:border-wwc-primary/50 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center mb-3">
                                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-wwc-primary peer-checked:bg-wwc-primary flex items-center justify-center transition-all duration-200" id="payment_online_banking_radio">
                                            <div class="w-4 h-4 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200 shadow-lg" id="payment_online_banking_dot"></div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="flex items-center">
                                                <h3 class="text-sm font-bold text-gray-900 mr-2">Online Banking</h3>
                                                <span class="text-sm text-gray-600">|</span>
                                                <div class="flex items-center ml-2">
                                                    <i class="bx bx-building text-wwc-primary mr-1 text-sm"></i>
                                                    <span class="text-sm text-gray-600">Direct transfer</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-gray-600 text-sm leading-relaxed">Transfer directly from your bank account using online banking services.</p>
                                    <div class="flex items-center text-xs text-wwc-primary bg-wwc-primary/10 px-2 py-1 rounded-lg mt-3">
                                        <i class="bx bx-time mr-1"></i>
                                        <span class="font-medium">Instant processing</span>
                                    </div>
                                </label>
                            </div>

                            <div class="relative group h-full">
                                <input type="radio" 
                                        id="payment_ewallet" 
                                        name="payment_method" 
                                        value="e_wallet" 
                                        class="sr-only peer"
                                        {{ old('payment_method') == 'e_wallet' ? 'checked' : '' }}>
                                <label for="payment_ewallet" 
                                        class="flex flex-col h-full justify-between p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-wwc-primary peer-checked:bg-wwc-primary/5 hover:border-wwc-primary/50 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center mb-3">
                                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-wwc-primary peer-checked:bg-wwc-primary flex items-center justify-center transition-all duration-200" id="payment_ewallet_radio">
                                            <div class="w-4 h-4 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200 shadow-lg" id="payment_ewallet_dot"></div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="flex items-center">
                                                <h3 class="text-sm font-bold text-gray-900 mr-2">E-Wallet/QR Code</h3>
                                                <span class="text-sm text-gray-600">|</span>
                                                <div class="flex items-center ml-2">
                                                    <i class="bx bx-qr text-wwc-primary mr-1 text-sm"></i>
                                                    <span class="text-sm text-gray-600">Digital payment</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-gray-600 text-sm leading-relaxed">Pay with popular e-wallets or scan QR code for quick and convenient payment.</p>
                                    <div class="flex items-center text-xs text-wwc-primary bg-wwc-primary/10 px-2 py-1 rounded-lg mt-3">
                                        <i class="bx bx-mobile mr-1"></i>
                                        <span class="font-medium">Mobile friendly</span>
                                    </div>
                                </label>
                            </div>

                            <div class="relative group h-full">
                                <input type="radio" 
                                        id="payment_bank_transfer" 
                                        name="payment_method" 
                                        value="bank_transfer" 
                                        class="sr-only peer"
                                        {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}>
                                <label for="payment_bank_transfer" 
                                        class="flex flex-col h-full justify-between p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-wwc-primary peer-checked:bg-wwc-primary/5 hover:border-wwc-primary/50 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center mb-3">
                                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-wwc-primary peer-checked:bg-wwc-primary flex items-center justify-center transition-all duration-200" id="payment_bank_transfer_radio">
                                            <div class="w-4 h-4 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200 shadow-lg" id="payment_bank_transfer_dot"></div>
                                        </div>
                                        <div class="ml-3">
                            <div class="flex items-center">
                                                <h3 class="text-sm font-bold text-gray-900 mr-2">Bank Transfer</h3>
                                                <span class="text-sm text-gray-600">|</span>
                                                <div class="flex items-center ml-2">
                                                    <i class="bx bx-transfer-alt text-wwc-primary mr-1 text-sm"></i>
                                                    <span class="text-sm text-gray-600">Traditional transfer</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-gray-600 text-sm leading-relaxed">Transfer funds directly from your bank account using traditional banking methods.</p>
                                    <div class="flex items-center text-xs text-wwc-primary bg-wwc-primary/10 px-2 py-1 rounded-lg mt-3">
                                        <i class="bx bx-check-circle mr-1"></i>
                                        <span class="font-medium">Reliable & secure</span>
                                </div>
                                </label>
                            </div>
                        </div>
                        @error('payment_method')
                            <p class="text-red-500 text-sm mt-4 flex items-center">
                                <i class="bx bx-error-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
                
                <!-- Terms and Conditions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-8">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 mt-1">
                            <input type="checkbox" 
                                    id="terms_agreement" 
                                    name="terms_agreement" 
                                    required
                                        class="h-5 w-5 text-wwc-primary border-2 border-gray-300 rounded focus:ring-wwc-primary focus:ring-2 transition-all duration-200">
                            </div>
                            <div class="flex-1">
                                <label for="terms_agreement" class="text-sm text-gray-700 leading-relaxed">
                                    I agree to the <a href="#" class="text-wwc-primary hover:text-wwc-primary-dark font-semibold underline transition-colors duration-200">Terms and Conditions</a> 
                                    and <a href="#" class="text-wwc-primary hover:text-wwc-primary-dark font-semibold underline transition-colors duration-200">Privacy Policy</a>
                            </label>
                                <p class="text-sm text-gray-500 mt-2">By checking this box, you confirm that you have read and agree to our terms of service and privacy policy.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="w-full">
                    <button type="submit" 
                            id="purchase-button"
                            class="w-full bg-wwc-primary text-white px-6 py-3 rounded-xl font-bold text-base hover:bg-wwc-primary-dark transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:scale-105 group">
                        <i class="bx bx-credit-card mr-2 text-xl group-hover:scale-110 transition-transform duration-200"></i>
                        <span>Proceed to Checkout</span>
                        <span class="ml-2 bg-white/20 px-3 py-1 rounded-lg font-bold" id="purchase-total">RM0</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
// Event data
const eventData = {
    isMultiDay: {{ $event->isMultiDay() ? 'true' : 'false' }},
    durationInDays: {{ $event->getDurationInDays() }},
    comboDiscountPercentage: {{ $event->combo_discount_percentage ?? 0 }},
    comboDiscountEnabled: {{ $event->combo_discount_enabled ? 'true' : 'false' }},
    ticketPrices: {
        @foreach($event->tickets->where('available_seats', '>', 0) as $ticket)
            '{{ $ticket->id }}': {{ $ticket->price }},
    @endforeach
    }
};

// DOM elements
const purchaseTypeRadios = document.querySelectorAll('input[name="purchase_type"]');
const singleDaySelection = document.getElementById('single_day_selection');
const multiDaySelection = document.getElementById('multi_day_selection');
const purchaseTypeInput = document.getElementById('purchase_type_input');
const ticketTypeSelect = document.getElementById('ticket_type_id');
const quantityInput = document.getElementById('quantity');
const quantityDecrease = document.getElementById('quantity-decrease');
const quantityIncrease = document.getElementById('quantity-increase');
const subtotalElement = document.getElementById('subtotal');
const comboDiscountRow = document.getElementById('combo-discount-row');
const comboDiscountElement = document.getElementById('combo-discount');
const serviceFeeElement = document.getElementById('service-fee');
const taxElement = document.getElementById('tax');
const totalElement = document.getElementById('total');
const purchaseTotalElement = document.getElementById('purchase-total');
const quantityLimitElement = document.getElementById('quantity-limit');

// Multi-day elements
const dayTicketSelects = document.querySelectorAll('.day-ticket-select');
const dayQuantityInputs = document.querySelectorAll('.day-quantity');
const dayQuantityDecreases = document.querySelectorAll('.day-quantity-decrease');
const dayQuantityIncreases = document.querySelectorAll('.day-quantity-increase');

// Function to update radio button visual states
function updateRadioButtonStates() {
    const singleDayRadio = document.getElementById('single_day');
    const multiDayRadio = document.getElementById('multi_day');
    const singleDayDot = document.getElementById('single_day_dot');
    const multiDayDot = document.getElementById('multi_day_dot');
    const singleDayRadioCircle = document.getElementById('single_day_radio');
    const multiDayRadioCircle = document.getElementById('multi_day_radio');
    
    if (singleDayRadio.checked) {
        singleDayDot.style.opacity = '1';
        singleDayRadioCircle.classList.add('border-wwc-primary', 'bg-wwc-primary');
        singleDayRadioCircle.classList.remove('border-gray-300');
    } else {
        singleDayDot.style.opacity = '0';
        singleDayRadioCircle.classList.remove('border-wwc-primary', 'bg-wwc-primary');
        singleDayRadioCircle.classList.add('border-gray-300');
    }
    
    if (multiDayRadio.checked) {
        multiDayDot.style.opacity = '1';
        multiDayRadioCircle.classList.add('border-wwc-primary', 'bg-wwc-primary');
        multiDayRadioCircle.classList.remove('border-gray-300');
    } else {
        multiDayDot.style.opacity = '0';
        multiDayRadioCircle.classList.remove('border-wwc-primary', 'bg-wwc-primary');
        multiDayRadioCircle.classList.add('border-gray-300');
    }
}

// Purchase type change handler
purchaseTypeRadios.forEach(radio => {
    radio.addEventListener('change', function() {
        const purchaseType = this.value;
        purchaseTypeInput.value = purchaseType;
        
        if (purchaseType === 'single_day') {
            singleDaySelection.classList.remove('hidden');
            multiDaySelection.classList.add('hidden');
        } else {
            singleDaySelection.classList.add('hidden');
            multiDaySelection.classList.remove('hidden');
        }
        
        updateRadioButtonStates();
        calculatePricing();
    });
});

// Function to update payment method radio button visual states
function updatePaymentMethodRadioStates() {
    const paymentMethods = [
        { radio: 'payment_credit_card', dot: 'payment_credit_card_dot', circle: 'payment_credit_card_radio' },
        { radio: 'payment_online_banking', dot: 'payment_online_banking_dot', circle: 'payment_online_banking_radio' },
        { radio: 'payment_ewallet', dot: 'payment_ewallet_dot', circle: 'payment_ewallet_radio' },
        { radio: 'payment_bank_transfer', dot: 'payment_bank_transfer_dot', circle: 'payment_bank_transfer_radio' }
    ];
    
    paymentMethods.forEach(method => {
        const radio = document.getElementById(method.radio);
        const dot = document.getElementById(method.dot);
        const circle = document.getElementById(method.circle);
        
        if (radio && dot && circle) {
            if (radio.checked) {
                dot.style.opacity = '1';
                circle.classList.add('border-wwc-primary', 'bg-wwc-primary');
                circle.classList.remove('border-gray-300');
            } else {
                dot.style.opacity = '0';
                circle.classList.remove('border-wwc-primary', 'bg-wwc-primary');
                circle.classList.add('border-gray-300');
            }
        }
    });
}

// Add event listeners for payment method radio buttons
const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
paymentMethodRadios.forEach(radio => {
    radio.addEventListener('change', function() {
        updatePaymentMethodRadioStates();
    });
});

// Initialize radio button states on page load
updateRadioButtonStates();
updatePaymentMethodRadioStates();

// Single day quantity controls
quantityDecrease.addEventListener('click', function() {
    const currentValue = parseInt(quantityInput.value) || 1;
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
        calculatePricing();
    }
});

quantityIncrease.addEventListener('click', function() {
    const currentValue = parseInt(quantityInput.value) || 1;
    const maxQuantity = parseInt(quantityInput.max) || 10;
    if (currentValue < maxQuantity) {
        quantityInput.value = currentValue + 1;
        calculatePricing();
    }
});

// Multi-day quantity controls
dayQuantityDecreases.forEach(button => {
    button.addEventListener('click', function() {
        const day = this.dataset.day;
        const input = document.getElementById(`day${day}_quantity`);
        const currentValue = parseInt(input.value) || 0;
        if (currentValue > 0) {
            input.value = currentValue - 1;
            calculatePricing();
        }
    });
});

dayQuantityIncreases.forEach(button => {
    button.addEventListener('click', function() {
        const day = this.dataset.day;
        const input = document.getElementById(`day${day}_quantity`);
        const currentValue = parseInt(input.value) || 0;
        const maxQuantity = parseInt(input.max) || 10;
        if (currentValue < maxQuantity) {
            input.value = currentValue + 1;
            calculatePricing();
        }
    });
});

// Event listeners
ticketTypeSelect.addEventListener('change', calculatePricing);
quantityInput.addEventListener('input', calculatePricing);

dayTicketSelects.forEach(select => {
    select.addEventListener('change', function() {
        const day = this.id.replace('day', '').replace('_ticket_type', '');
        updateDayQuantityLimit(day);
        calculatePricing();
    });
});

dayQuantityInputs.forEach(input => {
    input.addEventListener('input', calculatePricing);
});

// Update quantity limits for multi-day
function updateDayQuantityLimit(day) {
    const select = document.getElementById(`day${day}_ticket_type`);
    const input = document.getElementById(`day${day}_quantity`);
    const limitElement = document.getElementById(`day${day}-limit`);
    
    if (select.value) {
        const selectedOption = select.options[select.selectedIndex];
        const availableSeats = parseInt(selectedOption.dataset.available) || 0;
        const maxQuantity = Math.min(10, availableSeats);
        
        input.max = maxQuantity;
        limitElement.textContent = `Maximum ${maxQuantity} tickets available (${availableSeats} seats remaining)`;
        
        // Adjust quantity if it exceeds available seats
        if (parseInt(input.value) > maxQuantity) {
            input.value = maxQuantity;
        }
    } else {
        input.max = 10;
        limitElement.textContent = 'Maximum 10 tickets';
    }
}

// Calculate pricing
function calculatePricing() {
    const purchaseType = purchaseTypeInput.value;
    let subtotal = 0;
    let comboDiscount = 0;
    
    if (purchaseType === 'single_day') {
        // Single day calculation
        const selectedTicketType = ticketTypeSelect.value;
        const quantity = parseInt(quantityInput.value) || 1;
        
        if (selectedTicketType && eventData.ticketPrices[selectedTicketType]) {
            subtotal = eventData.ticketPrices[selectedTicketType] * quantity;
        }
    } else {
        // Multi-day calculation
        let day1Total = 0;
        let day2Total = 0;
        
        // Day 1
        const day1TicketType = document.getElementById('day1_ticket_type').value;
        const day1Quantity = parseInt(document.getElementById('day1_quantity').value) || 0;
        if (day1TicketType && eventData.ticketPrices[day1TicketType]) {
            day1Total = eventData.ticketPrices[day1TicketType] * day1Quantity;
        }
        
        // Day 2
        const day2TicketType = document.getElementById('day2_ticket_type').value;
        const day2Quantity = parseInt(document.getElementById('day2_quantity').value) || 0;
        if (day2TicketType && eventData.ticketPrices[day2TicketType]) {
            day2Total = eventData.ticketPrices[day2TicketType] * day2Quantity;
        }
        
        subtotal = day1Total + day2Total;
        
        // Apply combo discount if both days have tickets
        if (eventData.comboDiscountEnabled && day1Quantity > 0 && day2Quantity > 0) {
            comboDiscount = subtotal * (eventData.comboDiscountPercentage / 100);
        }
    }
    
    // Calculate fees and taxes on original subtotal (before discount)
    const serviceFee = Math.round(subtotal * 0.05);
    const taxAmount = Math.round((subtotal - comboDiscount) * 0.06);
    const total = subtotal - comboDiscount + serviceFee + taxAmount;
    
    // Update display
    subtotalElement.textContent = `RM${subtotal.toLocaleString()}`;
    
    if (comboDiscount > 0) {
        comboDiscountRow.style.display = 'flex';
        comboDiscountElement.textContent = `-RM${comboDiscount.toLocaleString()}`;
    } else {
        comboDiscountRow.style.display = 'none';
    }
    
    serviceFeeElement.textContent = `RM${serviceFee.toLocaleString()}`;
    taxElement.textContent = `RM${taxAmount.toLocaleString()}`;
    totalElement.textContent = `RM${total.toLocaleString()}`;
    purchaseTotalElement.textContent = `RM${total.toLocaleString()}`;
    
}


// Initialize pricing on page load
calculatePricing();

// Countdown timer for reservation expiry
@if($holdUntil)
function updateCountdown() {
    const holdUntil = new Date('{{ $holdUntil->toISOString() }}');
    const now = new Date();
    const diff = holdUntil - now;
    
    if (diff <= 0) {
        document.getElementById('countdown-timer').textContent = 'Expired';
        // Redirect to event page
        setTimeout(() => {
            window.location.href = '{{ route("public.events.show", $event) }}';
        }, 1000);
        return;
    }
    
    const minutes = Math.floor(diff / 60000);
    const seconds = Math.floor((diff % 60000) / 1000);
    
    document.getElementById('countdown-timer').textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
}

// Update countdown every second
setInterval(updateCountdown, 1000);
updateCountdown();
@endif
</script>
@endpush