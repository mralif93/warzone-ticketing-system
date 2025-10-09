@extends('layouts.public')

@section('title', 'Checkout - ' . $event->name)
@section('description', 'Complete your ticket purchase for ' . $event->name . ' on ' . $event->date_time->format('M j, Y') . '.')

@section('content')
@if(!$event)
    <div class="min-h-screen bg-gray-50 flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">No Event Selected</h1>
            <p class="text-gray-600 mb-4">Please select an event first.</p>
            <a href="{{ route('public.events.index') }}" class="bg-wwc-primary text-white px-6 py-3 rounded-lg hover:bg-wwc-primary-dark transition-colors">Browse Events</a>
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
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <!-- Back Navigation -->
                <div class="flex items-center">
                    <a href="{{ route('public.tickets.select', $event) }}" 
                       class="flex items-center text-gray-600 hover:text-wwc-primary transition-colors duration-200 group">
                        <i class="bx bx-chevron-left text-lg mr-1 group-hover:-translate-x-1 transition-transform"></i>
                        <span class="font-medium">Back to Zone Selection</span>
                    </a>
                </div>
                
                <!-- Event Details -->
                <div class="text-center flex-1 mx-8">
                    <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $event->name ?? 'Event' }}</h1>
                    <p class="text-sm text-gray-600">
                        {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('M j, Y') : 'TBD' }}
                        @if($event->event_date)
                            at {{ \Carbon\Carbon::parse($event->event_date)->format('g:i A') }}
                        @endif
                        @if($event->venue)
                            • {{ $event->venue }}
                        @endif
                    </p>
                </div>
                
                <!-- Status Badge -->
                <div class="text-right">
                    <p class="text-xs text-gray-500 mb-1">Status</p>
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                        On Sale
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="space-y-8">
            <!-- Order Summary -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Event Info Header -->
                <div class="bg-wwc-primary p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold">{{ $event->name ?? 'Event' }}</h2>
                            <p class="text-wwc-primary-light text-sm mt-1 flex items-center">
                                <i class="bx bx-calendar mr-2"></i>
                                {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('M d, Y') : 'TBD' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                <i class="bx bx-ticket text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Zone Selection Summary -->
                    <div class="mb-6">
                        @if(isset($selectedZone) && isset($quantity))
                            <div class="bg-wwc-primary-light rounded-xl p-5 border border-wwc-primary">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-wwc-primary rounded-full flex items-center justify-center mr-4">
                                            <i class="bx bx-map text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900 text-lg">{{ $selectedZone }} Zone</h3>
                                            <p class="text-sm text-gray-600">{{ $quantity }} {{ $quantity == 1 ? 'ticket' : 'tickets' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-wwc-primary text-2xl">${{ number_format($totalPrice, 2) }}</p>
                                        <p class="text-xs text-gray-500">${{ number_format($totalPrice / $quantity, 2) }} each</p>
                                    </div>
                                </div>

                                <div class="bg-white bg-opacity-60 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <i class="bx bx-info-circle text-wwc-primary mr-3 mt-0.5"></i>
                                        <p class="text-sm text-wwc-primary-dark">
                                            <strong>Seat Assignment:</strong> Specific seats will be assigned when you arrive at the venue. Staff will scan your QR code and assign your actual seats within the {{ $selectedZone }} zone.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <i class="bx bx-error-circle text-red-500 mr-2"></i>
                                    <p class="text-sm text-red-700">No zone selection found. Please go back and select your preferred zone.</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Hold Timer -->
                    <div class="bg-wwc-warning-light border border-wwc-warning rounded-xl p-4 mb-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-wwc-warning rounded-full flex items-center justify-center mr-4">
                                <i class="bx bx-time text-white text-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-wwc-warning-dark">Reservation expires at</p>
                                <p id="hold-timer" class="text-2xl font-bold text-wwc-warning-dark">{{ \Carbon\Carbon::parse($holdUntil)->format('g:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Breakdown -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900 text-sm uppercase tracking-wide">Order Details</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal ({{ $quantity ?? count($heldSeats) }} {{ ($quantity ?? count($heldSeats)) == 1 ? 'ticket' : 'tickets' }})</span>
                                <span class="font-medium">${{ number_format($totalPrice, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Service Fee (5%)</span>
                                <span class="font-medium">${{ number_format($serviceFee, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax (8%)</span>
                                <span class="font-medium">${{ number_format($taxAmount, 2) }}</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between text-xl font-bold">
                                    <span>Total</span>
                                    <span class="text-wwc-primary">${{ number_format($grandTotal, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Complete Your Purchase</h2>
                        <p class="text-gray-600">Enter your information to secure your tickets</p>
                    </div>
                    
                    <form action="{{ route('public.tickets.purchase', $event) }}" method="POST" id="checkout-form">
                        @csrf
                        
                        @if ($errors->any())
                            <div class="bg-red-50 border-l-4 border-red-400 text-red-700 px-6 py-4 mb-8 rounded-r-lg">
                                <div class="flex items-center">
                                    <i class="bx bx-error-circle text-xl mr-3"></i>
                                    <div>
                                        <h4 class="font-medium">Please correct the following errors:</h4>
                                        <ul class="list-disc list-inside text-sm mt-2">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Customer Information Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                                <div class="w-10 h-10 bg-wwc-primary rounded-full flex items-center justify-center mr-3">
                                    <i class="bx bx-user text-white text-sm"></i>
                                </div>
                                Customer Information
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Customer Name -->
                                <div class="md:col-span-2">
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Full Name *
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="customer_name" id="customer_name" required
                                               value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary transition-all duration-200 @error('customer_name') border-red-500 @enderror"
                                               placeholder="Enter your full name">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="bx bx-user text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('customer_name')
                                        <div class="text-red-500 text-sm mt-1 flex items-center">
                                            <i class="bx bx-error-circle mr-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Customer Email -->
                                <div>
                                    <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address *
                                    </label>
                                    <div class="relative">
                                        <input type="email" name="customer_email" id="customer_email" required
                                               value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary transition-all duration-200 @error('customer_email') border-red-500 @enderror"
                                               placeholder="Enter your email address">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="bx bx-envelope text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('customer_email')
                                        <div class="text-red-500 text-sm mt-1 flex items-center">
                                            <i class="bx bx-error-circle mr-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Customer Phone -->
                                <div>
                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Phone Number
                                    </label>
                                    <div class="relative">
                                        <input type="tel" name="customer_phone" id="customer_phone"
                                               value="{{ old('customer_phone', auth()->user()->phone_number ?? '') }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary transition-all duration-200 @error('customer_phone') border-red-500 @enderror"
                                               placeholder="Enter your phone number">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="bx bx-phone text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('customer_phone')
                                        <div class="text-red-500 text-sm mt-1 flex items-center">
                                            <i class="bx bx-error-circle mr-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information Section -->
                        <div class="border-t border-gray-200 pt-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                                <div class="w-10 h-10 bg-wwc-primary rounded-full flex items-center justify-center mr-3">
                                    <i class="bx bx-credit-card text-white text-sm"></i>
                                </div>
                                Payment Information
                            </h3>

                            <div class="bg-wwc-primary-light border border-wwc-primary rounded-xl p-6 mb-6">
                                <div class="flex items-start">
                                    <i class="bx bx-info-circle text-wwc-primary text-xl mr-3 mt-0.5"></i>
                                    <div>
                                        <h4 class="font-semibold text-wwc-primary-dark mb-2">Payment Processing</h4>
                                        <p class="text-sm text-wwc-primary-dark">
                                            Payment processing will be implemented with Stripe integration for secure transactions.
                                            For now, this is a demo checkout process to test the ticket purchasing flow.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-8">
                                <button type="submit" 
                                        class="w-full bg-wwc-primary hover:bg-wwc-primary-dark text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <div class="flex items-center justify-center">
                                        <i class="bx bx-credit-card text-xl mr-2"></i>
                                        <span>Complete Purchase - ${{ number_format($grandTotal, 2) }}</span>
                                    </div>
                                </button>
                            </div>

                            <!-- Security & Terms -->
                            <div class="mt-6 text-center">
                                <div class="flex items-center justify-center mb-3">
                                    <i class="bx bx-shield-check text-green-500 mr-2"></i>
                                    <span class="text-sm text-gray-600">Secure checkout protected by SSL encryption</span>
                                </div>
                                <p class="text-xs text-gray-500">
                                    By completing this purchase, you agree to our 
                                    <a href="#" class="text-wwc-primary hover:underline">terms of service</a> 
                                    and 
                                    <a href="#" class="text-wwc-primary hover:underline">refund policy</a>.
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if holdUntil is valid before proceeding
    const holdUntilString = '{{ $holdUntil }}';
    
    if (!holdUntilString || holdUntilString === 'null' || holdUntilString === '') {
        // No valid hold time, redirect immediately
        window.location.href = '{{ route("public.tickets.select", $event) }}';
        return;
    }
    
    // Parse the ISO date string
    const holdUntil = new Date(holdUntilString);
    let holdTimer = null;

    // Check if the date is valid
    if (isNaN(holdUntil.getTime())) {
        // Invalid date, redirect immediately
        window.location.href = '{{ route("public.tickets.select", $event) }}';
        return;
    }

    function updateHoldTimer() {
        const now = new Date();
        const timeLeft = holdUntil - now;
        
        if (timeLeft <= 0) {
            document.getElementById('hold-timer').textContent = 'Expired';
            clearInterval(holdTimer);
            
            // Redirect back to seat selection without alert
            window.location.href = '{{ route("public.tickets.select", $event) }}';
            return;
        }
        
        const minutes = Math.floor(timeLeft / 60000);
        const seconds = Math.floor((timeLeft % 60000) / 1000);
        document.getElementById('hold-timer').textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    }
    
    updateHoldTimer();
    holdTimer = setInterval(updateHoldTimer, 1000);

    // Form submission
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i>Processing...';
        
        // Submit form
        this.submit();
    });
});
</script>
@endif
@endsection