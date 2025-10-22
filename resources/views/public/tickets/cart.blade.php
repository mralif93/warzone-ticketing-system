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
                </div>
                
                <!-- Ticket Selection Form -->
                <form action="{{ route('public.tickets.purchase', $event) }}" method="POST" id="ticket-form" class="space-y-6">
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
                                               class="flex flex-col p-5 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-wwc-primary peer-checked:bg-red-50 hover:border-wwc-primary/50 hover:shadow-md transition-all duration-200">
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
                                                {{ $ticket->name }} - RM{{ number_format($ticket->price, 2) }} 
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
                                       max="10" 
                                           class="quantity w-20 px-2 py-2 text-center border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm font-semibold">
                                    <button type="button" 
                                            class="quantity-increase w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-500 hover:border-wwc-primary hover:text-wwc-primary hover:bg-wwc-primary/5 transition-all duration-200">
                                    <i class="bx bx-plus text-lg"></i>
                                </button>
                            </div>
                                <p class="text-sm text-gray-500">Maximum 10 tickets per order</p>
                        </div>
                    </div>
                </div>
                
                    <!-- Step 3: Multi-Day Ticket Selection -->
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
                                <h3 class="text-lg font-semibold text-gray-900">Select Days to Attend</h3>
                                <p class="text-sm text-gray-600">Choose Day 1 only, Day 2 only, or both days for maximum savings!</p>
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
                                               class="flex flex-col p-5 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-wwc-primary peer-checked:bg-red-50 hover:border-wwc-primary/50 hover:shadow-md transition-all duration-200">
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
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <i class="bx bx-info-circle text-blue-500 text-lg mr-2 mt-0.5"></i>
                        <div>
                                            <p class="text-sm text-blue-800 font-medium">Flexible Day Selection</p>
                                            <p class="text-xs text-blue-600 mt-1">You can choose Day 1 only, Day 2 only, or both days. Combo discount applies only when both days are selected.</p>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        
                            <!-- Day-specific Ticket Selection -->
                            @foreach($event->getEventDays() as $index => $day)
                            <div id="day{{ $index + 1 }}_ticket_section" class="border-2 border-gray-100 rounded-xl p-6 bg-gradient-to-br from-gray-50 to-white hover:shadow-md transition-all duration-200 hidden">
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
                                                        $daySold = \App\Models\PurchaseTicket::where('ticket_type_id', $ticket->id)
                                                            ->where('event_day_name', $day['day_name'])
                                                            ->whereIn('status', ['sold', 'pending'])
                                                            ->count();
                                                        $dayAvailable = $ticket->total_seats - $daySold;
                                                    @endphp
                                                    <option value="{{ $ticket->id }}" 
                                                            data-price="{{ $ticket->price }}"
                                                            data-available="{{ $dayAvailable }}"
                                                            data-total="{{ $ticket->total_seats }}"
                                                            data-sold="{{ $daySold }}">
                                                        {{ $ticket->name }} - RM{{ number_format($ticket->price, 2) }} 
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
                            
                            <div class="space-y-2 pt-4 border-t border-gray-300">
                                <div class="flex items-center justify-between py-1">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 bg-blue-100 rounded flex items-center justify-center mr-2">
                                            <i class="bx bx-receipt text-blue-600 text-xs"></i>
                                        </div>
                                        <span class="text-gray-700 font-medium text-sm">Subtotal</span>
                                    </div>
                                    <span class="font-bold text-gray-900 text-sm" id="subtotal">RM0</span>
                                </div>
                                <div class="flex items-center justify-between py-1">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 bg-green-100 rounded flex items-center justify-center mr-2">
                                            <i class="bx bx-gift text-green-600 text-xs"></i>
                                        </div>
                                        <span class="text-gray-700 font-medium text-sm">Combo Discount ({{ $event->combo_discount_percentage }}%)</span>
                                    </div>
                                    <span class="font-bold text-green-600 text-sm" id="combo-discount">RM0.00</span>
                                </div>
                                <div class="flex items-center justify-between py-1">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 bg-orange-100 rounded flex items-center justify-center mr-2">
                                            <i class="bx bx-cog text-orange-600 text-xs"></i>
                                        </div>
                                        <span class="text-gray-700 font-medium text-sm">Service Fee ({{ $serviceFeePercentage }}%)</span>
                                    </div>
                                    <span class="font-bold text-gray-900 text-sm" id="service-fee">RM0</span>
                                </div>
                                <div class="flex items-center justify-between py-1">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 bg-purple-100 rounded flex items-center justify-center mr-2">
                                            <i class="bx bx-calculator text-purple-600 text-xs"></i>
                                        </div>
                                        <span class="text-gray-700 font-medium text-sm">Tax ({{ $taxPercentage }}%)</span>
                                    </div>
                                    <span class="font-bold text-gray-900 text-sm" id="tax">RM0</span>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-gray-400">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 bg-red-100 rounded flex items-center justify-center mr-2">
                                            <i class="bx bx-money text-red-600 text-xs"></i>
                                        </div>
                                        <span class="font-bold text-gray-900 text-sm">Total Amount</span>
                                    </div>
                                    <span class="font-bold text-red-600 text-sm" id="total">RM0</span>
                                </div>
                            </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                    <div class="w-full">
                    <button type="submit" 
                            id="purchase-button"
                            form="ticket-form"
                                class="w-full bg-wwc-primary text-white px-6 py-3 rounded-xl font-bold text-base hover:bg-wwc-primary-dark transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:scale-105 group">
                            <i class="bx bx-arrow-right mr-2 text-xl group-hover:scale-110 transition-transform duration-200"></i>
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
    comboDiscountEnabled: {{ $event->combo_discount_enabled ? 'true' : 'false' }},
    comboDiscountPercentage: {{ $event->combo_discount_percentage ?? 0 }},
    serviceFeePercentage: {{ $serviceFeePercentage }},
    taxPercentage: {{ $taxPercentage }}
};

// DOM elements
const purchaseTypeRadios = document.querySelectorAll('input[name="purchase_type"]');
const singleDaySection = document.getElementById('single_day_selection');
const multiDaySection = document.getElementById('multi_day_selection');
const singleDayRadios = document.querySelectorAll('input[name="single_day_selection"]');
const dayCheckboxes = document.querySelectorAll('.day-checkbox');
const ticketTypeSelect = document.getElementById('ticket_type_id');
const quantityInput = document.getElementById('quantity');
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

// Day selection handlers for multi-day
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
        if (currentValue < 10) {
            input.value = currentValue + 1;
            calculatePricing();
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
        }
    });
});

// Day quantity controls
document.querySelectorAll('.day-quantity-increase').forEach(button => {
    button.addEventListener('click', function() {
        const day = this.dataset.day;
        const input = document.getElementById(`day${day}_quantity`);
        const currentValue = parseInt(input.value);
        if (currentValue < 10) {
            input.value = currentValue + 1;
            calculatePricing();
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
        }
    });
});

// Ticket type change handler
ticketTypeSelect.addEventListener('change', calculatePricing);

// Day ticket type change handlers
document.querySelectorAll('.day-ticket-select').forEach(select => {
    select.addEventListener('change', calculatePricing);
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
    input.addEventListener('change', calculatePricing);
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
                        <div class="text-sm text-gray-600 ml-11">Single Day Experience</div>
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
                            <div class="text-sm text-gray-600 ml-11">Day 1 - ${eventData.isMultiDay ? 'Nov 20, 2025' : 'Single Day'}</div>
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
                            <div class="text-sm text-gray-600 ml-11">Day 2 - Nov 21, 2025</div>
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
    
    // Calculate service fee and tax
    const serviceFee = subtotal * (eventData.serviceFeePercentage / 100);
    const tax = (subtotal + serviceFee) * (eventData.taxPercentage / 100);
    const total = subtotal + serviceFee + tax;
    
    // Update display
    orderSummaryContent.innerHTML = orderSummary || '<p class="text-gray-500 text-center py-8">Please select your tickets to see the order summary</p>';
    subtotalElement.textContent = `RM${originalSubtotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    serviceFeeElement.textContent = `RM${serviceFee.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    taxElement.textContent = `RM${tax.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    totalElement.textContent = `RM${total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    purchaseTotalElement.textContent = `RM${total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    
    // Update combo discount display
    if (comboDiscountElement) {
        if (discountAmount > 0) {
            comboDiscountElement.textContent = `-RM${discountAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
            comboDiscountElement.parentElement.parentElement.style.display = 'flex';
        } else {
            comboDiscountElement.textContent = 'RM0.00';
            comboDiscountElement.parentElement.parentElement.style.display = 'none';
        }
    }
    
    // Ensure summary section is always visible
    const summarySection = document.querySelector('.space-y-2.pt-4.border-t.border-gray-300');
    if (summarySection) {
        summarySection.style.display = 'block';
    }
}

// Initialize visual states and pricing on page load
updateRadioButtonStates();
updateSingleDayRadioStates();
updateMultiDayCheckboxStates();

// Ensure summary section is visible on page load
const summarySection = document.querySelector('.space-y-2.pt-4.border-t.border-gray-300');
if (summarySection) {
    summarySection.style.display = 'block';
}

calculatePricing();
</script>
@endpush