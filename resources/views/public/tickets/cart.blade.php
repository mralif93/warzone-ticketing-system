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
                            <i class='bx bx-calendar mr-2 text-wwc-primary'></i>
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
            <div class="flex items-center justify-between">
                <!-- Back Navigation -->
                <div class="flex items-center">
                    <a href="{{ route('public.events.show', $event) }}" 
                       class="flex items-center text-wwc-neutral-600 hover:text-wwc-primary transition-colors duration-200 group">
                        <div class="h-8 w-8 bg-wwc-neutral-100 rounded-lg flex items-center justify-center group-hover:bg-wwc-primary/10 transition-colors duration-200">
                            <i class="bx bx-chevron-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                        </div>
                        <span class="font-semibold ml-3">Back to Event</span>
                    </a>
                </div>
                
                <!-- Event Details -->
                <div class="text-center flex-1 mx-8">
                    <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display mb-1">{{ $event->name }}</h1>
                    <p class="text-sm text-wwc-neutral-600">
                        {{ $event->getFormattedDateRange() }}
                        @if($event->venue)
                            • {{ $event->venue }}
                        @endif
                    </p>
                </div>
                
                <!-- Event Status -->
                <div class="flex items-center">
                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-wwc-success/10 text-wwc-success border border-wwc-success/20">
                        <i class="bx bx-check-circle mr-2"></i>
                        On Sale
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="space-y-8">
            
            <!-- Checkout Form -->
            <form action="{{ route('public.tickets.purchase', $event) }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Ticket Selection -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Select Your Tickets</h2>
                        <p class="text-sm text-gray-600 mt-1">Choose your zone and quantity</p>
                    </div>
                    
                    <div class="px-6 py-6 space-y-6">
                        <!-- Zone Selection -->
                        <div>
                            <label for="zone" class="block text-sm font-medium text-gray-700 mb-3">
                                Select Zone <span class="text-red-500">*</span>
                            </label>
                            <select id="zone" name="zone" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary @error('zone') border-red-500 @enderror">
                                <option value="">Choose a zone</option>
                                <option value="Warzone Exclusive" {{ old('zone') == 'Warzone Exclusive' ? 'selected' : '' }}>Warzone Exclusive - RM500</option>
                                <option value="Warzone VIP" {{ old('zone') == 'Warzone VIP' ? 'selected' : '' }}>Warzone VIP - RM250</option>
                                <option value="Warzone Grandstand" {{ old('zone') == 'Warzone Grandstand' ? 'selected' : '' }}>Warzone Grandstand - RM199</option>
                                <option value="Warzone Premium Ringside" {{ old('zone') == 'Warzone Premium Ringside' ? 'selected' : '' }}>Warzone Premium Ringside - RM150</option>
                                <option value="Level 1 Zone A/B/C/D" {{ old('zone') == 'Level 1 Zone A/B/C/D' ? 'selected' : '' }}>Level 1 Zone A/B/C/D - RM100</option>
                                <option value="Level 2 Zone A/B/C/D" {{ old('zone') == 'Level 2 Zone A/B/C/D' ? 'selected' : '' }}>Level 2 Zone A/B/C/D - RM75</option>
                                <option value="Standing Zone A/B" {{ old('zone') == 'Standing Zone A/B' ? 'selected' : '' }}>Standing Zone A/B - RM50</option>
                            </select>
                            @error('zone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Quantity Selection -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-3">
                                Quantity <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center justify-center space-x-4">
                                <button type="button" id="quantity-decrease" 
                                        class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-wwc-primary transition-colors">
                                    <i class="bx bx-minus text-lg"></i>
                                </button>
                                <input type="number" 
                                       id="quantity" 
                                       name="quantity" 
                                       min="1" 
                                       max="10" 
                                       value="{{ old('quantity', 1) }}" 
                                       required
                                       class="w-24 text-center px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary @error('quantity') border-red-500 @enderror text-lg font-semibold">
                                <button type="button" id="quantity-increase" 
                                        class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-wwc-primary transition-colors">
                                    <i class="bx bx-plus text-lg"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 text-center">Maximum 10 tickets per order</p>
                            @error('quantity')
                                <p class="text-red-500 text-sm mt-1 text-center">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-2" id="order-summary">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium" id="subtotal">RM0</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Service Fee (5%)</span>
                                <span class="font-medium" id="service-fee">RM0</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax (6%)</span>
                                <span class="font-medium" id="tax">RM0</span>
                            </div>
                            <div class="flex justify-between text-lg font-semibold border-t border-gray-200 pt-2">
                                <span>Total</span>
                                <span class="text-wwc-primary" id="total">RM0</span>
                            </div>
                        </div>
                    </div>
                </div>
                    
                <!-- Customer Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Customer Information</h2>
                    </div>
                    
                    <div class="px-6 py-6 space-y-4">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                    id="customer_name" 
                                    name="customer_name" 
                                    value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary @error('customer_name') border-red-500 @enderror">
                            @error('customer_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                    id="customer_email" 
                                    name="customer_email" 
                                    value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary @error('customer_email') border-red-500 @enderror">
                            @error('customer_email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number
                            </label>
                            <input type="tel" 
                                    id="customer_phone" 
                                    name="customer_phone" 
                                    value="{{ old('customer_phone', auth()->user()->phone ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary @error('customer_phone') border-red-500 @enderror">
                            @error('customer_phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Payment Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Payment Information</h2>
                    </div>
                    
                    <div class="px-6 py-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="bx bx-info-circle text-blue-600 mr-2"></i>
                                <div class="text-sm text-blue-800">
                                    <strong>Payment Processing:</strong> Payment will be processed securely after form submission. 
                                    You will receive a confirmation email with your tickets.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Terms and Conditions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4">
                        <div class="flex items-start">
                            <input type="checkbox" 
                                    id="terms_agreement" 
                                    name="terms_agreement" 
                                    required
                                    class="mt-1 h-4 w-4 text-wwc-primary border-gray-300 rounded focus:ring-wwc-primary">
                            <label for="terms_agreement" class="ml-3 text-sm text-gray-700">
                                I agree to the <a href="#" class="text-wwc-primary hover:underline">Terms and Conditions</a> 
                                and <a href="#" class="text-wwc-primary hover:underline">Privacy Policy</a>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                            id="purchase-button"
                            class="bg-wwc-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-wwc-primary-dark transition-colors duration-200 flex items-center">
                        <i class="bx bx-credit-card mr-2"></i>
                        Complete Purchase - <span id="purchase-total">RM0</span>
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
// Zone pricing data
const zonePrices = {
    'Warzone Exclusive': 500,
    'Warzone VIP': 250,
    'Warzone Grandstand': 199,
    'Warzone Premium Ringside': 150,
    'Level 1 Zone A/B/C/D': 100,
    'Level 2 Zone A/B/C/D': 75,
    'Standing Zone A/B': 50
};

// DOM elements
const zoneSelect = document.getElementById('zone');
const quantityInput = document.getElementById('quantity');
const quantityDecrease = document.getElementById('quantity-decrease');
const quantityIncrease = document.getElementById('quantity-increase');
const subtotalElement = document.getElementById('subtotal');
const serviceFeeElement = document.getElementById('service-fee');
const taxElement = document.getElementById('tax');
const totalElement = document.getElementById('total');
const purchaseTotalElement = document.getElementById('purchase-total');

// Calculate pricing
function calculatePricing() {
    const selectedZone = zoneSelect.value;
    const quantity = parseInt(quantityInput.value) || 1;
    
    if (!selectedZone) {
        subtotalElement.textContent = 'RM0';
        serviceFeeElement.textContent = 'RM0';
        taxElement.textContent = 'RM0';
        totalElement.textContent = 'RM0';
        purchaseTotalElement.textContent = 'RM0';
        return;
    }
    
    const basePrice = zonePrices[selectedZone] || 0;
    const subtotal = basePrice * quantity;
    const serviceFee = Math.round(subtotal * 0.05);
    const tax = Math.round((subtotal + serviceFee) * 0.06);
    const total = subtotal + serviceFee + tax;
    
    subtotalElement.textContent = `RM${subtotal.toLocaleString()}`;
    serviceFeeElement.textContent = `RM${serviceFee.toLocaleString()}`;
    taxElement.textContent = `RM${tax.toLocaleString()}`;
    totalElement.textContent = `RM${total.toLocaleString()}`;
    purchaseTotalElement.textContent = `RM${total.toLocaleString()}`;
}

// Quantity controls
quantityDecrease.addEventListener('click', function() {
    const currentValue = parseInt(quantityInput.value) || 1;
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
        calculatePricing();
    }
});

quantityIncrease.addEventListener('click', function() {
    const currentValue = parseInt(quantityInput.value) || 1;
    if (currentValue < 10) {
        quantityInput.value = currentValue + 1;
        calculatePricing();
    }
});

// Event listeners
zoneSelect.addEventListener('change', calculatePricing);
quantityInput.addEventListener('input', calculatePricing);

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