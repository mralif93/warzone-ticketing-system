@extends('layouts.public')

@section('title', 'Cart - ' . $event->name)
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
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-r from-wwc-primary to-wwc-primary-dark rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bx bx-lock-alt text-2xl text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Login Required</h2>
                    <p class="text-gray-600">Please login to continue with your ticket purchase.</p>
                </div>

                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="intended" value="{{ route('public.tickets.cart', $event) }}">
                    
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary transition-all duration-200">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary transition-all duration-200">
                </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-wwc-primary to-wwc-primary-dark text-white py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                        Login to Continue
                    </button>
                </form>
                
                <!-- Register Link -->
                <div class="text-center mt-6">
                    <p class="text-gray-600">Don't have an account? 
                        <a href="{{ route('register') }}" class="text-wwc-primary hover:text-wwc-primary-dark font-semibold">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Main Cart Content -->
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Timeout Message -->
            @if(request('timeout') == '1' || session('timeout'))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                            <i class="bx bx-time text-red-600"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-red-800">Payment Timeout</h3>
                            <p class="text-sm text-red-600">
                                Your previous order @if(session('timeout_order_id'))(Order #{{ session('timeout_order_id') }})@elseif(request('order_id'))(Order #{{ request('order_id') }})@endif has expired due to payment timeout. 
                                Please select your tickets again to proceed with a new order.
                            </p>
                        </div>
                    </div>
                </div>
            @endif


            <!-- Error Messages -->
            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="bx bx-error-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Complete Your Purchase</h1>
                <p class="text-gray-600">Select your tickets and proceed to checkout</p>
            </div>

            <div class="space-y-6">
                <!-- Event Information -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300">
                    <div class="bg-red-600 px-8 py-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                <i class="bx bx-calendar text-xl text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-white mb-1">{{ $event->name }}</h2>
                                <p class="text-white/90 text-sm">
                                    @if($event->isMultiDay())
                                        {{ $event->getEventDays()[0]['display'] }} - {{ $event->getEventDays()[1]['display'] }}
                                    @else
                                        {{ $event->getEventDays()[0]['display'] }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center space-x-4 flex-wrap gap-2">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="bx bx-map-pin mr-2 text-wwc-primary"></i>
                                    <span>{{ $event->venue }}</span>
                                </div>
                                @if($event->isMultiDay())
                                    @php $eventDays = $event->getEventDays(); @endphp
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="bx bx-group mr-2 text-wwc-primary"></i>
                                        <span>Day 1: {{ number_format($event->getTicketsAvailableForDay($eventDays[0]['date'])) }} left</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="bx bx-group mr-2 text-wwc-primary"></i>
                                        <span>Day 2: {{ number_format($event->getTicketsAvailableForDay($eventDays[1]['date'])) }} left</span>
                                    </div>
                                @else
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="bx bx-group mr-2 text-wwc-primary"></i>
                                        <span>{{ number_format($event->getRemainingTicketsCount()) }} tickets remaining</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center">
                                @if($event->hasAvailableSeats())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="bx bx-check-circle mr-1"></i>
                                        On Sale
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="bx bx-x-circle mr-1"></i>
                                        Sold Out
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Ticket Selection Form -->
                <form action="{{ route('public.tickets.checkout', $event) }}" method="POST" id="ticket-form" class="space-y-6">
                    @csrf
                    <input type="hidden" name="purchase_type" id="purchase_type_input" value="single_day">
                    <input type="hidden" name="multi_day1_enabled" id="day1_enabled_input" value="0">
                    <input type="hidden" name="multi_day2_enabled" id="day2_enabled_input" value="0">
                    <input type="hidden" name="day1_ticket_type" id="day1_ticket_type_input" value="">
                    <input type="hidden" name="day1_quantity" id="day1_quantity_input" value="">
                    <input type="hidden" name="day2_ticket_type" id="day2_ticket_type_input" value="">
                    <input type="hidden" name="day2_quantity" id="day2_quantity_input" value="">
                    <input type="hidden" name="customer_name" id="customer_name_input" value="">
                    <input type="hidden" name="customer_email" id="customer_email_input" value="">
                    <input type="hidden" name="customer_phone" id="customer_phone_input" value="">
                    <input type="hidden" name="payment_method" id="payment_method_input" value="credit_card">
                    
                    <!-- Step 1: Purchase Type Selection -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300">
                        <div class="bg-red-600 px-8 py-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                    <i class="bx bx-calendar-check text-xl text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <h2 class="text-2xl font-bold text-white mb-1">Step 1: Select Your Experience</h2>
                                    <p class="text-white/90 text-sm">Choose how you want to attend the event</p>
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
                                           class="flex flex-col p-6 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-wwc-primary peer-checked:bg-wwc-primary/5 hover:border-wwc-primary/50 hover:shadow-md transition-all duration-200">
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
                                           class="flex flex-col p-6 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-wwc-primary peer-checked:bg-wwc-primary/5 hover:border-wwc-primary/50 hover:shadow-md transition-all duration-200">
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
                
                    <!-- Step 2: Single Day Ticket Selection -->
                    <div id="single_day_selection" class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300">
                        <div class="bg-red-600 px-8 py-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                    <i class="bx bx-purchase-tag text-xl text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <h2 class="text-2xl font-bold text-white mb-1">Step 2: Select Ticket Type</h2>
                                    <p class="text-white/90 text-sm">Choose your ticket type and quantity</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-8 space-y-6">
                            @if($event->isMultiDay())
                            <!-- Day Selection for Single Day Purchase -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-gray-900">Select Day to Attend</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($event->getEventDays() as $index => $day)
                                    <div class="relative group">
                                        <input type="radio" 
                                               id="single_day{{ $index + 1 }}" 
                                               name="single_day_selection" 
                                               value="day{{ $index + 1 }}"
                                               class="sr-only peer"
                                               {{ old('single_day_selection', 'day1') == 'day' . ($index + 1) ? 'checked' : '' }}>
                                        <label for="single_day{{ $index + 1 }}" 
                                               class="flex flex-col p-6 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-wwc-primary peer-checked:bg-red-50 hover:border-wwc-primary/50 hover:shadow-md transition-all duration-200">
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-wwc-primary peer-checked:bg-wwc-primary flex items-center justify-center transition-all duration-200" id="single_day{{ $index + 1 }}_radio">
                                                    <div class="w-4 h-4 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200 shadow-lg" id="single_day{{ $index + 1 }}_dot"></div>
                                                </div>
                                                <div class="ml-3">
                                                    <h4 class="text-base text-gray-900">
                                                        <span class="font-bold">{{ $day['day_name'] }}</span> | <span class="font-normal">{{ $day['display'] }}</span>
                                                    </h4>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <!-- Ticket Type Selection -->
                            <div class="space-y-3">
                                <label for="ticket_type_id" class="block text-sm font-semibold text-gray-900">
                                    Select Ticket Type <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select id="ticket_type_id"
                                            name="ticket_type_id"
                                            required
                                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary @error('ticket_type_id') border-red-500 @enderror text-sm bg-white shadow-sm transition-all duration-200 hover:border-gray-300">
                                        <option value="">Choose your preferred ticket type</option>
                                        @php
                                            $eventDays = $event->isMultiDay() ? $event->getEventDays() : [];
                                            // For multi-day events, include 'sold_out' tickets too since they may have per-day availability
                                            $ticketStatuses = $event->isMultiDay() ? ['active', 'sold_out'] : ['active'];
                                        @endphp
                                        @foreach($event->tickets->whereIn('status', $ticketStatuses) as $ticket)
                                            @php
                                                // Calculate per-day availability for multi-day events
                                                $day1Available = $ticket->total_seats;
                                                $day2Available = $ticket->total_seats;

                                                if ($event->isMultiDay() && count($eventDays) >= 2) {
                                                    $day1Sold = \App\Models\PurchaseTicket::where('ticket_type_id', $ticket->id)
                                                        ->whereDate('event_day', $eventDays[0]['date'])
                                                        ->whereIn('status', ['pending', 'sold', 'active', 'scanned'])
                                                        ->count();
                                                    $day2Sold = \App\Models\PurchaseTicket::where('ticket_type_id', $ticket->id)
                                                        ->whereDate('event_day', $eventDays[1]['date'])
                                                        ->whereIn('status', ['pending', 'sold', 'active', 'scanned'])
                                                        ->count();
                                                    $day1Available = $ticket->total_seats - $day1Sold;
                                                    $day2Available = $ticket->total_seats - $day2Sold;
                                                }

                                                // Show ticket if at least one day has availability (for multi-day) or overall availability (for single-day)
                                                $showTicket = $event->isMultiDay() ? ($day1Available > 0 || $day2Available > 0) : ($ticket->available_seats > 0);
                                            @endphp
                                            @if($showTicket)
                                            <option value="{{ $ticket->id }}"
                                                    data-price="{{ $ticket->price }}"
                                                    data-available="{{ $ticket->available_seats }}"
                                                    data-day1-available="{{ $day1Available }}"
                                                    data-day2-available="{{ $day2Available }}"
                                                    data-total="{{ $ticket->total_seats }}"
                                                    data-name="{{ $ticket->name }}"
                                                    {{ old('ticket_type_id') == $ticket->id ? 'selected' : '' }}>
                                                {{ $ticket->name }} - RM{{ number_format($ticket->price, 2) }}
                                            </option>
                                            @endif
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
                                <div class="flex items-center justify-center space-x-3">
                                    <button type="button" 
                                            class="quantity-decrease w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-500 hover:border-wwc-primary hover:text-wwc-primary hover:bg-wwc-primary/5 transition-all duration-200">
                                        <i class="bx bx-minus text-lg"></i>
                                    </button>
                                    <input type="number" 
                                           id="quantity" 
                                           name="quantity" 
                                           value="1" 
                                           min="1" 
                                           max="{{ $maxTicketsPerOrder }}" 
                                           class="quantity w-20 px-2 py-2 text-center border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm font-semibold">
                                    <button type="button" 
                                            class="quantity-increase w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-500 hover:border-wwc-primary hover:text-wwc-primary hover:bg-wwc-primary/5 transition-all duration-200">
                                        <i class="bx bx-plus text-lg"></i>
                                    </button>
                                </div>
                                @error('quantity')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="bx bx-error-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-sm text-gray-500">Maximum {{ $maxTicketsPerOrder }} tickets per order</p>
                            </div>
                        </div>
                    </div>
                
                    <!-- Step 2: Multi-Day Ticket Selection -->
                    <div id="multi_day_selection" class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 hidden">
                        <div class="bg-red-600 px-8 py-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                    <i class="bx bx-calendar text-xl text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <h2 class="text-2xl font-bold text-white mb-1">Step 2: Multi-Day Combo Selection</h2>
                                    <p class="text-white/90 text-sm">Complete experience - Select tickets for each day</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-8 space-y-6">
                            <!-- Day Selection Checkboxes -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-gray-900">Select Days to Attend <span class="text-red-600">*</span></h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($event->getEventDays() as $index => $day)
                                    <div class="relative group">
                                        <input type="checkbox" 
                                               id="multi_day{{ $index + 1 }}_enabled" 
                                               name="multi_day{{ $index + 1 }}_enabled" 
                                               value="1"
                                               class="sr-only peer day-checkbox"
                                               data-day="{{ $index + 1 }}">
                                        <label for="multi_day{{ $index + 1 }}_enabled" 
                                               class="flex flex-col p-6 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-wwc-primary peer-checked:bg-red-50 hover:border-wwc-primary/50 hover:shadow-md transition-all duration-200">
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-wwc-primary peer-checked:bg-wwc-primary flex items-center justify-center transition-all duration-200" id="multi_day{{ $index + 1 }}_radio">
                                                    <div class="w-4 h-4 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200 shadow-lg" id="multi_day{{ $index + 1 }}_dot"></div>
                                                </div>
                                                <div class="ml-3">
                                                    <h4 class="text-base text-gray-900">
                                                        <span class="font-bold">{{ $day['day_name'] }}</span> | <span class="font-normal">{{ $day['display'] }}</span>
                                                    </h4>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Combo Discount Info -->
                            @if($event->combo_discount_enabled && $event->combo_discount_percentage > 0)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <i class="bx bx-info-circle text-blue-500 text-lg mr-2 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm text-blue-800 font-medium">Required: Both Days</p>
                                        <p class="text-xs text-blue-600 mt-1">Multi-day purchase requires selecting both Day 1 and Day 2 tickets to proceed to checkout. You must select tickets for both days to get the {{ number_format($event->combo_discount_percentage, 0) }}% combo discount!</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Day-specific Ticket Selection -->
                        @foreach($event->getEventDays() as $index => $day)
                        <div id="day{{ $index + 1 }}_ticket_section" class="border-2 border-gray-100 rounded-xl m-8 p-6 bg-gradient-to-br from-gray-50 to-white hover:shadow-md transition-all duration-200 hidden">
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
                                            @foreach($event->tickets->whereIn('status', ['active', 'sold_out']) as $ticket)
                                                @php
                                                    // Calculate day-specific availability using event_day (date)
                                                    $daySold = \App\Models\PurchaseTicket::where('ticket_type_id', $ticket->id)
                                                        ->whereDate('event_day', $day['date'])
                                                        ->whereIn('status', ['pending', 'sold', 'active', 'scanned'])
                                                        ->count();
                                                    $dayAvailable = $ticket->total_seats - $daySold;
                                                @endphp
                                                @if($dayAvailable > 0)
                                                <option value="{{ $ticket->id }}"
                                                        data-price="{{ $ticket->price }}"
                                                        data-available="{{ $dayAvailable }}"
                                                        data-total="{{ $ticket->total_seats }}"
                                                        data-sold="{{ $daySold }}">
                                                    {{ $ticket->name }} - RM{{ number_format($ticket->price, 2) }}
                                                </option>
                                                @endif
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
                                               max="{{ $maxTicketsPerOrder }}" 
                                               class="day-quantity w-20 px-2 py-2 text-center border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm font-semibold">
                                        <button type="button" 
                                                class="day-quantity-increase w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-500 hover:border-wwc-primary hover:text-wwc-primary hover:bg-wwc-primary/5 transition-all duration-200"
                                                data-day="{{ $index + 1 }}">
                                            <i class="bx bx-plus text-lg"></i>
                                        </button>
                                    </div>
                                    <p class="text-sm text-gray-500">Maximum {{ $maxTicketsPerOrder }} tickets per day</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Step 3: Order Summary -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300">
                        <div class="bg-red-600 px-8 py-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                    <i class="bx bx-receipt text-xl text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <h2 class="text-2xl font-bold text-white mb-1">Step 3: Order Summary</h2>
                                    <p class="text-white/90 text-sm">Review your selection and pricing</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-8 space-y-6">
                            <div id="order-summary-content">
                                <p class="text-gray-500 text-center py-8">Please select your tickets to see the order summary</p>
                            </div>
                            
                            <div class="space-y-3 pt-4 border-t border-gray-300">
                                <div class="flex items-center justify-between py-2">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="bx bx-receipt text-blue-600 text-sm"></i>
                                        </div>
                                        <span class="text-gray-800 font-medium">Subtotal</span>
                                    </div>
                                    <span class="font-semibold text-gray-800" id="subtotal">RM0.00</span>
                                </div>
                                <div class="flex items-center justify-between py-2" id="combo-discount-row" style="display: none;">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="bx bx-gift text-green-600 text-sm"></i>
                                        </div>
                                        <span class="text-gray-800 font-medium">Combo Discount ({{ $event->combo_discount_percentage ?? 10 }}%)</span>
                                    </div>
                                    <span class="font-semibold text-green-600" id="combo-discount">RM0.00</span>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="bx bx-cog text-orange-600 text-sm"></i>
                                        </div>
                                        <span class="text-gray-800 font-medium">Service Fee ({{ $serviceFeePercentage }}%)</span>
                                    </div>
                                    <span class="font-semibold text-gray-800" id="service-fee">RM0.00</span>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="bx bx-calculator text-purple-600 text-sm"></i>
                                        </div>
                                        <span class="text-gray-800 font-medium">Tax ({{ $taxPercentage }}%)</span>
                                    </div>
                                    <span class="font-semibold text-gray-800" id="tax">RM0.00</span>
                                </div>
                                <div class="border-t border-gray-400 pt-3 mt-3">
                                    <div class="flex items-center justify-between py-2">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="bx bx-money text-red-600 text-sm"></i>
                                            </div>
                                            <span class="font-bold text-gray-900 text-lg">Total Amount</span>
                                        </div>
                                        <span class="font-bold text-red-600 text-lg" id="total">RM0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <!-- Submit Button -->
                    <div class="w-full">
                        <button type="submit" 
                                id="purchase-button"
                                form="ticket-form"
                                class="w-full bg-green-600 text-white px-6 py-3 rounded-xl font-bold text-base hover:bg-green-700 transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:scale-105 group">
                            <i class="bx bx-arrow-right mr-2 text-xl group-hover:scale-110 transition-transform duration-200"></i>
                            <span>Proceed to Checkout</span>
                            <span class="ml-2 bg-white/20 px-3 py-1 rounded-lg font-bold" id="purchase-total">RM0</span>
                        </button>
                    </div>
                </form>
            </div>
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
    comboDiscountEnabled: {{ $event->combo_discount_enabled ? 'true' : 'false' }},
    comboDiscountPercentage: {{ $event->combo_discount_percentage ?? 0 }},
    serviceFeePercentage: {{ $serviceFeePercentage }},
    taxPercentage: {{ $taxPercentage }},
    totalCapacity: {{ $event->getTotalCapacity() }},
    ticketsSold: {{ $event->getTicketsSoldCount() }},
    remainingCapacity: {{ $event->getRemainingTicketsCount() }},
    hasAvailableSeats: {{ $event->hasAvailableSeats() ? 'true' : 'false' }},
    @if($event->isMultiDay())
    @php $eventDaysJs = $event->getEventDays(); @endphp
    day1RemainingCapacity: {{ $event->getTicketsAvailableForDay($eventDaysJs[0]['date']) }},
    day2RemainingCapacity: {{ $event->getTicketsAvailableForDay($eventDaysJs[1]['date']) }},
    day1Date: '{{ $event->start_date ? $event->start_date->format("M j, Y") : $event->date_time->format("M j, Y") }}',
    day2Date: '{{ $event->end_date ? $event->end_date->format("M j, Y") : $event->date_time->format("M j, Y") }}',
    @else
    eventDate: '{{ $event->date_time ? $event->date_time->format("M j, Y") : "Single Day" }}',
    @endif
};

// DOM elements
const purchaseTypeRadios = document.querySelectorAll('input[name="purchase_type"]');
const singleDaySection = document.getElementById('single_day_selection');
const multiDaySection = document.getElementById('multi_day_selection');
const singleDayRadios = document.querySelectorAll('input[name="single_day_selection"]');
const dayCheckboxes = document.querySelectorAll('.day-checkbox');
const ticketTypeSelect = document.getElementById('ticket_type_id');
const quantityInput = document.getElementById('quantity');

// Track AddToCart when single-day quantity input changes
if (quantityInput) {
    quantityInput.addEventListener('change', function() {
        const ticketTypeSelect = document.getElementById('ticket_type_id');
        if (ticketTypeSelect && ticketTypeSelect.value) {
            const selectedOption = ticketTypeSelect.options[ticketTypeSelect.selectedIndex];
            const price = parseFloat(selectedOption.dataset.price) || 0;
            const quantity = parseInt(this.value) || 1;
            const ticketName = selectedOption.text.split(' - ')[0];
            
            let dayInfo = null;
            if (eventData.isMultiDay) {
                const selectedDayRadio = document.querySelector('input[name="single_day_selection"]:checked');
                if (selectedDayRadio) {
                    const dayNumber = selectedDayRadio.value.replace('day', '');
                    dayInfo = `Day ${dayNumber}`;
                }
            }
            
            trackAddToCart(ticketTypeSelect.value, ticketName, quantity, price, dayInfo);
        }
    });
}
const orderSummaryContent = document.getElementById('order-summary-content');
const subtotalElement = document.getElementById('subtotal');
const serviceFeeElement = document.getElementById('service-fee');
const taxElement = document.getElementById('tax');
const totalElement = document.getElementById('total');
const purchaseTotalElement = document.getElementById('purchase-total');
const comboDiscountElement = document.getElementById('combo-discount');

// Function to update radio button visual states
function updateRadioButtonStates() {
    purchaseTypeRadios.forEach(radio => {
        const type = radio.value.replace('purchase_', '');
        const dot = document.getElementById(`${type}_dot`);
        const circle = document.getElementById(`${type}_radio`);
        
        if (radio.checked) {
            dot.style.opacity = '1';
            circle.classList.add('border-wwc-primary', 'bg-wwc-primary');
            circle.classList.remove('border-gray-300');
        } else {
            dot.style.opacity = '0';
            circle.classList.remove('border-wwc-primary', 'bg-wwc-primary');
            circle.classList.add('border-gray-300');
        }
    });
}

// Purchase type change handler
purchaseTypeRadios.forEach(radio => {
    radio.addEventListener('change', function() {
        updateRadioButtonStates();
        if (this.value === 'single_day') {
            singleDaySection.classList.remove('hidden');
            multiDaySection.classList.add('hidden');
            document.getElementById('purchase_type_input').value = 'single_day';
            
            // Make single-day fields required
            const singleDayTicketSelect = document.getElementById('ticket_type_id');
            const singleDayQuantity = document.getElementById('quantity');
            if (singleDayTicketSelect) singleDayTicketSelect.required = true;
            if (singleDayQuantity) singleDayQuantity.required = true;
            
            // Make multi-day fields not required
            document.querySelectorAll('.day-ticket-select').forEach(select => {
                select.required = false;
            });
            document.querySelectorAll('.day-quantity').forEach(input => {
                input.required = false;
            });
        } else {
            singleDaySection.classList.add('hidden');
            multiDaySection.classList.remove('hidden');
            document.getElementById('purchase_type_input').value = 'multi_day';
            
            // Make single-day fields not required
            const singleDayTicketSelect = document.getElementById('ticket_type_id');
            const singleDayQuantity = document.getElementById('quantity');
            if (singleDayTicketSelect) singleDayTicketSelect.required = false;
            if (singleDayQuantity) singleDayQuantity.required = false;
            
            // Make multi-day fields required based on selection
            updateMultiDayRequiredFields();
        }
        calculatePricing();
    });
});

// Day selection handlers for single-day
singleDayRadios.forEach(radio => {
    radio.addEventListener('change', function() {
        updateSingleDayRadioStates();
        calculatePricing();
    });
});

// Day selection handlers for multi-day - BOTH DAYS REQUIRED
dayCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const day = this.dataset.day;
        const section = document.getElementById(`day${day}_ticket_section`);
        
        if (this.checked) {
            section.classList.remove('hidden');
        } else {
            section.classList.add('hidden');
            // Reset values when unchecked
            const ticketSelect = section.querySelector(`#day${day}_ticket_type`);
            const quantityInput = section.querySelector(`#day${day}_quantity`);
            if (ticketSelect) ticketSelect.value = '';
            if (quantityInput) quantityInput.value = 1;
        }
        
        updateMultiDayCheckboxStates();
        calculatePricing();
    });
});

// Function to update single day radio button visual states
function updateSingleDayRadioStates() {
    singleDayRadios.forEach(radio => {
        const day = radio.value.replace('day', '');
        const dot = document.getElementById(`single_day${day}_dot`);
        const circle = document.getElementById(`single_day${day}_radio`);

        if (radio.checked) {
            dot.style.opacity = '1';
            circle.classList.add('border-wwc-primary', 'bg-wwc-primary');
            circle.classList.remove('border-gray-300');
        } else {
            dot.style.opacity = '0';
            circle.classList.remove('border-wwc-primary', 'bg-wwc-primary');
            circle.classList.add('border-gray-300');
        }
    });

    // Update ticket dropdown based on selected day (for multi-day events)
    updateTicketDropdownForSelectedDay();
}

// Store original ticket options for rebuilding dropdown
let originalTicketOptions = [];

// Initialize original options on page load
function initializeTicketOptions() {
    const ticketSelect = document.getElementById('ticket_type_id');
    if (!ticketSelect) {
        console.log('initializeTicketOptions: ticket_type_id select not found');
        return;
    }

    const options = ticketSelect.querySelectorAll('option');
    console.log('initializeTicketOptions: Found ' + options.length + ' options');
    options.forEach(option => {
        if (option.value) { // Skip placeholder
            const ticketData = {
                value: option.value,
                price: option.dataset.price || '0',
                day1Available: parseInt(option.dataset.day1Available) || 0,
                day2Available: parseInt(option.dataset.day2Available) || 0,
                total: option.dataset.total || '0',
                name: option.dataset.name || option.textContent.split(' - ')[0].trim()
            };
            console.log('initializeTicketOptions: Adding ticket:', ticketData);
            originalTicketOptions.push(ticketData);
        }
    });
    console.log('initializeTicketOptions: Total stored:', originalTicketOptions.length);
}

// Function to update ticket dropdown options based on selected day
function updateTicketDropdownForSelectedDay() {
    console.log('updateTicketDropdownForSelectedDay called, isMultiDay:', eventData.isMultiDay);
    if (!eventData.isMultiDay) return;

    const ticketSelect = document.getElementById('ticket_type_id');
    if (!ticketSelect) {
        console.log('updateTicketDropdownForSelectedDay: ticket_type_id not found');
        return;
    }

    // Find selected day
    const selectedDayRadio = document.querySelector('input[name="single_day_selection"]:checked');
    if (!selectedDayRadio) {
        console.log('updateTicketDropdownForSelectedDay: no day radio selected');
        return;
    }

    const selectedDay = selectedDayRadio.value; // 'day1' or 'day2'
    const dayNumber = selectedDay === 'day1' ? 1 : 2;
    console.log('updateTicketDropdownForSelectedDay: selectedDay=' + selectedDay + ', dayNumber=' + dayNumber);
    console.log('updateTicketDropdownForSelectedDay: originalTicketOptions count=' + originalTicketOptions.length);

    // Store current selection
    const currentSelection = ticketSelect.value;

    // Clear all options except placeholder
    while (ticketSelect.options.length > 1) {
        ticketSelect.remove(1);
    }

    // Re-add only options with availability for selected day
    let addedCount = 0;
    originalTicketOptions.forEach(ticket => {
        const dayAvailable = dayNumber === 1 ? ticket.day1Available : ticket.day2Available;
        console.log('  Ticket: ' + ticket.name + ', day' + dayNumber + 'Available=' + dayAvailable);

        if (dayAvailable > 0) {
            const option = document.createElement('option');
            option.value = ticket.value;
            option.dataset.price = ticket.price;
            option.dataset.available = dayAvailable;
            option.dataset.day1Available = ticket.day1Available;
            option.dataset.day2Available = ticket.day2Available;
            option.dataset.total = ticket.total;
            option.dataset.name = ticket.name;
            option.textContent = `${ticket.name} - RM${parseFloat(ticket.price).toFixed(2)}`;

            // Restore selection if this was previously selected
            if (ticket.value === currentSelection) {
                option.selected = true;
            }

            ticketSelect.appendChild(option);
            addedCount++;
        }
    });
    console.log('updateTicketDropdownForSelectedDay: Added ' + addedCount + ' options to dropdown');

    // If previous selection is no longer available, reset
    if (currentSelection && ticketSelect.value !== currentSelection) {
        ticketSelect.value = '';
    }
}

// Function to update multi-day checkbox visual states
function updateMultiDayCheckboxStates() {
    dayCheckboxes.forEach(checkbox => {
        const day = checkbox.dataset.day;
        const dot = document.getElementById(`multi_day${day}_dot`);
        const circle = document.getElementById(`multi_day${day}_radio`);
        
        if (checkbox.checked) {
            dot.style.opacity = '1';
            circle.classList.add('border-wwc-primary', 'bg-wwc-primary');
            circle.classList.remove('border-gray-300');
        } else {
            dot.style.opacity = '0';
            circle.classList.remove('border-wwc-primary', 'bg-wwc-primary');
            circle.classList.add('border-gray-300');
        }
    });
}

// Quantity controls
document.querySelectorAll('.quantity-increase').forEach(button => {
    button.addEventListener('click', function() {
        const input = this.parentElement.querySelector('.quantity');
        const currentValue = parseInt(input.value);
        // Check max tickets per order, event/day remaining capacity, AND selected ticket's available seats
        let capacityLimit = eventData.remainingCapacity;
        let ticketAvailable = 999999; // Default high value if no ticket selected

        // Get selected ticket's available seats for the selected day
        const ticketTypeSelect = document.getElementById('ticket_type_id');
        if (ticketTypeSelect && ticketTypeSelect.value) {
            const selectedOption = ticketTypeSelect.options[ticketTypeSelect.selectedIndex];
            if (eventData.isMultiDay) {
                const selectedDayRadio = document.querySelector('input[name="single_day_selection"]:checked');
                if (selectedDayRadio) {
                    ticketAvailable = selectedDayRadio.value === 'day1'
                        ? parseInt(selectedOption.dataset.day1Available) || 0
                        : parseInt(selectedOption.dataset.day2Available) || 0;
                }
            } else {
                ticketAvailable = parseInt(selectedOption.dataset.available) || 0;
            }
        }

        if (eventData.isMultiDay) {
            // For multi-day events, check the selected day's capacity
            const selectedDayRadio = document.querySelector('input[name="single_day_selection"]:checked');
            if (selectedDayRadio) {
                capacityLimit = selectedDayRadio.value === 'day1' ? eventData.day1RemainingCapacity : eventData.day2RemainingCapacity;
            }
        }
        // Use the minimum of max per order, event capacity, and ticket available seats
        const maxAllowed = Math.min({{ $maxTicketsPerOrder }}, capacityLimit, ticketAvailable);
        if (currentValue < maxAllowed) {
            input.value = currentValue + 1;
            calculatePricing();

            // Track AddToCart when quantity increases (if ticket is selected)
            const ticketTypeSelect = document.getElementById('ticket_type_id');
            if (ticketTypeSelect && ticketTypeSelect.value) {
                const selectedOption = ticketTypeSelect.options[ticketTypeSelect.selectedIndex];
                const price = parseFloat(selectedOption.dataset.price) || 0;
                const quantity = parseInt(input.value) || 1;
                const ticketName = selectedOption.text.split(' - ')[0];

                let dayInfo = null;
                if (eventData.isMultiDay) {
                    const selectedDayRadio = document.querySelector('input[name="single_day_selection"]:checked');
                    if (selectedDayRadio) {
                        const dayNumber = selectedDayRadio.value.replace('day', '');
                        dayInfo = `Day ${dayNumber}`;
                    }
                }

                trackAddToCart(ticketTypeSelect.value, ticketName, quantity, price, dayInfo);
            }
        }
    });
});

document.querySelectorAll('.quantity-decrease').forEach(button => {
    button.addEventListener('click', function() {
        const input = this.parentElement.querySelector('.quantity');
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
            calculatePricing();
            
            // Track AddToCart when quantity decreases (if ticket is selected)
            const ticketTypeSelect = document.getElementById('ticket_type_id');
            if (ticketTypeSelect && ticketTypeSelect.value) {
                const selectedOption = ticketTypeSelect.options[ticketTypeSelect.selectedIndex];
                const price = parseFloat(selectedOption.dataset.price) || 0;
                const quantity = parseInt(input.value) || 1;
                const ticketName = selectedOption.text.split(' - ')[0];
                
                let dayInfo = null;
                if (eventData.isMultiDay) {
                    const selectedDayRadio = document.querySelector('input[name="single_day_selection"]:checked');
                    if (selectedDayRadio) {
                        const dayNumber = selectedDayRadio.value.replace('day', '');
                        dayInfo = `Day ${dayNumber}`;
                    }
                }
                
                trackAddToCart(ticketTypeSelect.value, ticketName, quantity, price, dayInfo);
            }
        }
    });
});

// Day quantity controls
document.querySelectorAll('.day-quantity-increase').forEach(button => {
    button.addEventListener('click', function() {
        const day = this.dataset.day;
        const input = document.getElementById(`day${day}_quantity`);
        const currentValue = parseInt(input.value);
        // Check max tickets per order, day's remaining capacity, AND selected ticket's available seats
        const dayCapacity = day === '1' ? eventData.day1RemainingCapacity : eventData.day2RemainingCapacity;

        // Get selected ticket's available seats for this day
        let ticketAvailable = 999999; // Default high value if no ticket selected
        const ticketSelect = document.getElementById(`day${day}_ticket_type`);
        if (ticketSelect && ticketSelect.value) {
            const selectedOption = ticketSelect.options[ticketSelect.selectedIndex];
            ticketAvailable = parseInt(selectedOption.dataset.available) || 0;
        }

        // Use the minimum of max per order, day capacity, and ticket available seats
        const maxAllowed = Math.min({{ $maxTicketsPerOrder }}, dayCapacity, ticketAvailable);
        if (currentValue < maxAllowed) {
            input.value = currentValue + 1;
            calculatePricing();

            // Track AddToCart when day quantity increases (if ticket is selected)
            if (ticketSelect && ticketSelect.value) {
                const selectedOption = ticketSelect.options[ticketSelect.selectedIndex];
                const price = parseFloat(selectedOption.dataset.price) || 0;
                const quantity = parseInt(input.value) || 1;
                const ticketName = selectedOption.text.split(' - ')[0];
                const dayInfo = `Day ${day}`;

                trackAddToCart(ticketSelect.value, ticketName, quantity, price, dayInfo);
            }
        }
    });
});

document.querySelectorAll('.day-quantity-decrease').forEach(button => {
    button.addEventListener('click', function() {
        const day = this.dataset.day;
        const input = document.getElementById(`day${day}_quantity`);
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
            calculatePricing();
            
            // Track AddToCart when day quantity decreases (if ticket is selected)
            const ticketSelect = document.getElementById(`day${day}_ticket_type`);
            if (ticketSelect && ticketSelect.value) {
                const selectedOption = ticketSelect.options[ticketSelect.selectedIndex];
                const price = parseFloat(selectedOption.dataset.price) || 0;
                const quantity = parseInt(input.value) || 1;
                const ticketName = selectedOption.text.split(' - ')[0];
                const dayInfo = `Day ${day}`;
                
                trackAddToCart(ticketSelect.value, ticketName, quantity, price, dayInfo);
            }
        }
    });
});

// Function to track AddToCart event
function trackAddToCart(ticketId, ticketName, quantity, price, dayInfo = null) {
    if (typeof fbq !== 'undefined' && ticketId && quantity > 0) {
        const contentName = dayInfo ? `${ticketName} - ${dayInfo}` : ticketName;
        
        fbq('track', 'AddToCart', {
            value: price * quantity,
            currency: 'MYR',
            content_ids: [ticketId],
            content_name: contentName,
            content_type: 'product',
            num_items: quantity
        });
        
        console.log('Meta Pixel AddToCart event tracked:', {
            ticket_id: ticketId,
            ticket_name: contentName,
            quantity: quantity,
            value: price * quantity,
            currency: 'MYR'
        });
    }
}

// Ticket type change handler
ticketTypeSelect.addEventListener('change', function() {
    calculatePricing();
    
    // Track AddToCart when ticket is selected
    if (this.value) {
        const selectedOption = this.options[this.selectedIndex];
        const price = parseFloat(selectedOption.dataset.price) || 0;
        const quantity = parseInt(quantityInput.value) || 1;
        const ticketName = selectedOption.text.split(' - ')[0];
        
        // Determine day info for single day purchase
        let dayInfo = null;
        if (eventData.isMultiDay) {
            const selectedDayRadio = document.querySelector('input[name="single_day_selection"]:checked');
            if (selectedDayRadio) {
                const dayNumber = selectedDayRadio.value.replace('day', '');
                dayInfo = `Day ${dayNumber}`;
            }
        }
        
        trackAddToCart(this.value, ticketName, quantity, price, dayInfo);
    }
});

// Day ticket type change handlers
document.querySelectorAll('.day-ticket-select').forEach(select => {
    select.addEventListener('change', function() {
        calculatePricing();
        
        // Track AddToCart when day ticket is selected
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            const price = parseFloat(selectedOption.dataset.price) || 0;
            const dayNumber = this.id.replace('day', '').replace('_ticket_type', '');
            const quantityInput = document.getElementById(`day${dayNumber}_quantity`);
            const quantity = parseInt(quantityInput ? quantityInput.value : 1) || 1;
            const ticketName = selectedOption.text.split(' - ')[0];
            const dayInfo = `Day ${dayNumber}`;
            
            trackAddToCart(this.value, ticketName, quantity, price, dayInfo);
        }
    });
});

// Day checkbox change handlers
document.querySelectorAll('.day-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        updateMultiDayRequiredFields();
        calculatePricing();
    });
});

// Day quantity change handlers
document.querySelectorAll('.day-quantity').forEach(input => {
    input.addEventListener('change', function() {
        calculatePricing();
        
        // Track AddToCart when day quantity changes (if ticket is selected)
        const dayNumber = this.id.replace('day', '').replace('_quantity', '');
        const ticketSelect = document.getElementById(`day${dayNumber}_ticket_type`);
        if (ticketSelect && ticketSelect.value) {
            const selectedOption = ticketSelect.options[ticketSelect.selectedIndex];
            const price = parseFloat(selectedOption.dataset.price) || 0;
            const quantity = parseInt(this.value) || 1;
            const ticketName = selectedOption.text.split(' - ')[0];
            const dayInfo = `Day ${dayNumber}`;
            
            trackAddToCart(ticketSelect.value, ticketName, quantity, price, dayInfo);
        }
    });
    input.addEventListener('input', calculatePricing);
});

// Update multi-day required fields function
function updateMultiDayRequiredFields() {
    const day1Checkbox = document.getElementById('multi_day1_enabled');
    const day2Checkbox = document.getElementById('multi_day2_enabled');
    
    // Day 1 fields
    const day1TicketSelect = document.getElementById('day1_ticket_type');
    const day1Quantity = document.getElementById('day1_quantity');
    if (day1TicketSelect && day1Quantity) {
        const isRequired = day1Checkbox && day1Checkbox.checked;
        day1TicketSelect.required = isRequired;
        day1Quantity.required = isRequired;
    }
    
    // Day 2 fields
    const day2TicketSelect = document.getElementById('day2_ticket_type');
    const day2Quantity = document.getElementById('day2_quantity');
    if (day2TicketSelect && day2Quantity) {
        const isRequired = day2Checkbox && day2Checkbox.checked;
        day2TicketSelect.required = isRequired;
        day2Quantity.required = isRequired;
    }
}

// Update form data function
function updateFormData() {
    const purchaseType = document.getElementById('purchase_type_input').value;
    
    if (purchaseType === 'multi_day') {
        // Update day enabled status
        const day1Checkbox = document.getElementById('multi_day1_enabled');
        const day2Checkbox = document.getElementById('multi_day2_enabled');
        document.getElementById('day1_enabled_input').value = day1Checkbox && day1Checkbox.checked ? '1' : '0';
        document.getElementById('day2_enabled_input').value = day2Checkbox && day2Checkbox.checked ? '1' : '0';
        
        // Update day 1 data
        const day1TicketSelect = document.getElementById('day1_ticket_type');
        const day1Quantity = document.getElementById('day1_quantity');
        if (day1TicketSelect && day1Quantity) {
            document.getElementById('day1_ticket_type_input').value = day1TicketSelect.value || '';
            document.getElementById('day1_quantity_input').value = day1Quantity.value || '';
        }
        
        // Update day 2 data
        const day2TicketSelect = document.getElementById('day2_ticket_type');
        const day2Quantity = document.getElementById('day2_quantity');
        if (day2TicketSelect && day2Quantity) {
            document.getElementById('day2_ticket_type_input').value = day2TicketSelect.value || '';
            document.getElementById('day2_quantity_input').value = day2Quantity.value || '';
        }
    }
    
    // Update customer information (use default values for now)
    document.getElementById('customer_name_input').value = '{{ Auth::user()->name ?? "Customer" }}';
    document.getElementById('customer_email_input').value = '{{ Auth::user()->email ?? "customer@example.com" }}';
    document.getElementById('customer_phone_input').value = '{{ Auth::user()->phone ?? "" }}';
}

// Calculate pricing function
function calculatePricing() {
    const purchaseType = document.getElementById('purchase_type_input').value;
    let originalSubtotal = 0;
    let subtotal = 0;
    let discountAmount = 0;
    let orderSummary = '';
    
    // Update form data before calculating
    updateFormData();
    
    if (purchaseType === 'single_day') {
        const selectedTicket = ticketTypeSelect.options[ticketTypeSelect.selectedIndex];
        const quantity = parseInt(quantityInput.value) || 0;
        
        if (selectedTicket && selectedTicket.value) {
            const price = parseFloat(selectedTicket.dataset.price);
            originalSubtotal = price * quantity;
            subtotal = originalSubtotal;
            discountAmount = 0; // No combo discount for single day
            
            // Determine which day is selected for single day purchase
            let dayLabel = 'Event Day';
            let dayDate = eventData.eventDate || 'Single Day';
            
            if (eventData.isMultiDay) {
                const selectedDayRadio = document.querySelector('input[name="single_day_selection"]:checked');
                if (selectedDayRadio) {
                    const dayNumber = selectedDayRadio.value.replace('day', '');
                    dayLabel = `Day ${dayNumber}`;
                    dayDate = dayNumber === '1' ? eventData.day1Date : eventData.day2Date;
                }
            }
            
            orderSummary = `
                <div class="py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="bx bx-purchase-tag text-red-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">${selectedTicket.text.split(' - ')[0]} <span class="text-sm text-gray-600">x ${quantity}</span></h4>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-bold text-gray-900">RM${(price * quantity).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600 ml-11">${dayLabel} - ${dayDate}</div>
                        <div class="text-sm text-gray-600">RM${price.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} each</div>
                    </div>
                </div>
            `;
        }
    } else {
        // Multi-day calculation
        const day1Enabled = document.getElementById('multi_day1_enabled')?.checked || false;
        const day2Enabled = document.getElementById('multi_day2_enabled')?.checked || false;
        
        let day1Total = 0;
        let day2Total = 0;
        
        if (day1Enabled) {
            const day1TicketSelect = document.getElementById('day1_ticket_type');
            const day1Quantity = parseInt(document.getElementById('day1_quantity').value) || 0;
            
            if (day1TicketSelect && day1TicketSelect.value) {
                const selectedOption = day1TicketSelect.options[day1TicketSelect.selectedIndex];
                const price = parseFloat(selectedOption.dataset.price);
                day1Total = price * day1Quantity;
                originalSubtotal += day1Total;
                
                orderSummary += `
                    <div class="py-4 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="bx bx-purchase-tag text-red-600 text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">${selectedOption.text.split(' - ')[0]} <span class="text-sm text-gray-600">x ${day1Quantity}</span></h4>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-gray-900">RM${day1Total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600 ml-11">Day 1 - ${eventData.day1Date || 'Nov 20, 2025'}</div>
                            <div class="text-sm text-gray-600">RM${price.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} each</div>
                        </div>
                    </div>
                `;
            }
        }
        
        if (day2Enabled) {
            const day2TicketSelect = document.getElementById('day2_ticket_type');
            const day2Quantity = parseInt(document.getElementById('day2_quantity').value) || 0;
            
            if (day2TicketSelect && day2TicketSelect.value) {
                const selectedOption = day2TicketSelect.options[day2TicketSelect.selectedIndex];
                const price = parseFloat(selectedOption.dataset.price);
                day2Total = price * day2Quantity;
                originalSubtotal += day2Total;
                
                orderSummary += `
                    <div class="py-4 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="bx bx-purchase-tag text-red-600 text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">${selectedOption.text.split(' - ')[0]} <span class="text-sm text-gray-600">x ${day2Quantity}</span></h4>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-gray-900">RM${day2Total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600 ml-11">Day 2 - ${eventData.day2Date || 'Nov 21, 2025'}</div>
                            <div class="text-sm text-gray-600">RM${price.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} each</div>
                        </div>
                    </div>
                `;
            }
        }
        
        // Set subtotal to original amount first
        subtotal = originalSubtotal;
        
        // Apply combo discount if both days are enabled and have tickets
        if (day1Enabled && day2Enabled && eventData.comboDiscountEnabled && originalSubtotal > 0) {
            discountAmount = originalSubtotal * (eventData.comboDiscountPercentage / 100);
            subtotal = originalSubtotal - discountAmount;
        } else {
            discountAmount = 0;
        }
    }
    
    // Calculate service fee and tax with proper rounding
    const serviceFee = Math.round(subtotal * (eventData.serviceFeePercentage / 100) * 100) / 100;
    const tax = Math.round((subtotal + serviceFee) * (eventData.taxPercentage / 100) * 100) / 100;
    const total = Math.round((subtotal + serviceFee + tax) * 100) / 100;
    
    // Update display
    orderSummaryContent.innerHTML = orderSummary || '<p class="text-gray-500 text-center py-8">Please select your tickets to see the order summary</p>';
    subtotalElement.textContent = `RM${originalSubtotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    serviceFeeElement.textContent = `RM${serviceFee.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    taxElement.textContent = `RM${tax.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    totalElement.textContent = `RM${total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    purchaseTotalElement.textContent = `RM${total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    
    // Update combo discount display
    const comboDiscountRow = document.getElementById('combo-discount-row');
    if (comboDiscountElement && comboDiscountRow) {
        if (discountAmount > 0) {
            comboDiscountElement.textContent = `-RM${discountAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
            comboDiscountRow.style.display = 'flex';
        } else {
            comboDiscountElement.textContent = 'RM0.00';
            comboDiscountRow.style.display = 'none';
        }
    }
    
    // Ensure summary section is always visible
    const summarySection = document.querySelector('.space-y-2.pt-4.border-t.border-gray-300');
    if (summarySection) {
        summarySection.style.display = 'block';
    }
}

// Form validation and submission
function validateForm() {
    const purchaseType = document.getElementById('purchase_type_input').value;
    const errors = [];
    
    if (purchaseType === 'single_day') {
        const ticketType = document.getElementById('ticket_type_id').value;
        const quantity = parseInt(document.getElementById('quantity').value) || 0;
        
        if (!ticketType) {
            errors.push('Please select a ticket type');
        }
        
        if (quantity < 1) {
            errors.push('Please select at least 1 ticket');
        }
        
        if (quantity > {{ $maxTicketsPerOrder }}) {
            errors.push('Maximum {{ $maxTicketsPerOrder }} tickets per purchase');
        }
    } else if (purchaseType === 'multi_day') {
        const day1Enabled = document.getElementById('day1_enabled_input').value === '1';
        const day2Enabled = document.getElementById('day2_enabled_input').value === '1';
        
        // BOTH DAYS REQUIRED FOR MULTI-DAY PURCHASE
        if (!day1Enabled || !day2Enabled) {
            errors.push('Multi-day purchase requires selecting both Day 1 and Day 2. Please select both days to proceed.');
        }
        
        if (day1Enabled) {
            const day1TicketType = document.getElementById('day1_ticket_type_input').value;
            const day1Quantity = parseInt(document.getElementById('day1_quantity_input').value) || 0;
            
            if (!day1TicketType) {
                errors.push('Please select a ticket type for Day 1');
            }
            
            if (day1Quantity < 1) {
                errors.push('Please select at least 1 ticket for Day 1');
            }
        }
        
        if (day2Enabled) {
            const day2TicketType = document.getElementById('day2_ticket_type_input').value;
            const day2Quantity = parseInt(document.getElementById('day2_quantity_input').value) || 0;
            
            if (!day2TicketType) {
                errors.push('Please select a ticket type for Day 2');
            }
            
            if (day2Quantity < 1) {
                errors.push('Please select at least 1 ticket for Day 2');
            }
        }
    }
    
    return errors;
}

// Form submission handler
document.getElementById('ticket-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Update form data before validation
    updateFormData();
    
    // Validate form
    const errors = validateForm();
    
    if (errors.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Missing Required Information',
            html: errors.map(error => `<p>${error}</p>`).join(''),
            confirmButtonText: 'I understand',
            confirmButtonColor: '#dc2626',
            showCancelButton: false,
            allowOutsideClick: false,
            allowEscapeKey: true
        });
        return;
    }
    
    // Show loading state
    const submitButton = document.getElementById('purchase-button');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i>Processing...';
    submitButton.disabled = true;
    
    // Submit form
    this.submit();
});

// Initialize visual states and pricing on page load
initializeTicketOptions(); // Store original ticket options for day-based filtering
updateRadioButtonStates();
updateSingleDayRadioStates();
updateMultiDayCheckboxStates();

// Ensure summary section is visible on page load
const summarySection = document.querySelector('.space-y-2.pt-4.border-t.border-gray-300');
if (summarySection) {
    summarySection.style.display = 'block';
}

calculatePricing();

// Show pending order popup if session data exists
@if(session('pending_order'))
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        title: 'Pending Order Detected',
        html: `
            <div class="text-center">
                <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="bx bx-credit-card text-red-600 text-4xl"></i>
                </div>
                <p class="text-gray-700 text-lg mb-6 leading-relaxed">
                    You have a pending order <span class="font-semibold text-red-600">(Order #{{ session('pending_order_number') }})</span> that needs to be completed first. 
                    Please complete your existing order before creating a new one.
                </p>
            </div>
        `,
        icon: false, // Disable default icon to use custom one
        showCancelButton: true,
        confirmButtonText: '<i class="bx bx-credit-card mr-2"></i> Complete Payment',
        cancelButtonText: '<i class="bx bx-x mr-2"></i> Dismiss',
        confirmButtonColor: '#dc2626', // Red-600 (project standard)
        cancelButtonColor: '#6b7280', // Gray-500
        reverseButtons: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showCloseButton: false,
        width: '500px', // Make it wider
        padding: '2rem', // Add more padding
        customClass: {
            popup: 'rounded-3xl shadow-2xl',
            title: 'text-2xl font-bold text-gray-900 mb-2',
            htmlContainer: 'text-gray-700 text-lg',
            confirmButton: 'px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-200 hover:scale-105',
            cancelButton: 'px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-200 hover:scale-105'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Check if order is still valid before redirecting
            fetch('{{ route('public.tickets.check-order-status', session('pending_order_id')) }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    // Order is still valid, redirect to payment
                    window.location.href = '{{ session('pending_payment_url') }}';
                } else {
                    // Order is no longer valid, show error and refresh page
                    Swal.fire({
                        title: 'Order Expired',
                        text: 'This order has expired and is no longer available for payment. Please create a new order.',
                        icon: 'error',
                        confirmButtonText: 'Create New Order',
                        confirmButtonColor: '#dc2626'
                    }).then(() => {
                        // Refresh the page to clear the pending order session
                        window.location.reload();
                    });
                }
            })
            .catch(error => {
                console.error('Error checking order status:', error);
                // If there's an error, still try to redirect (fallback)
                window.location.href = '{{ session('pending_payment_url') }}';
            });
        }
        // If dismissed, just close the alert (no action needed)
    });
});
@endif
</script>
@endpush