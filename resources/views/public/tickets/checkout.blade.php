@extends('layouts.public')

@section('title', 'Checkout - ' . $event->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
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
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-wwc-primary to-wwc-primary-dark rounded-full mb-4">
                <i class="bx bx-credit-card text-2xl text-white"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-3">Complete Your Purchase</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Review your order details and provide payment information to secure your tickets</p>
            
            <!-- Fallback Navigation -->
            <div class="mt-4">
                <a href="{{ route('public.tickets.cart', $event) }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <i class="bx bx-arrow-left mr-2"></i>
                    Back to Cart
                </a>
            </div>
        </div>

        <!-- Single Column Layout -->
        <div class="space-y-8">
                <!-- Event Information -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300">
                    <div class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark px-8 py-6">
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
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="bx bx-map-pin mr-2 text-wwc-primary"></i>
                                    <span>{{ $event->venue }}</span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="bx bx-check-circle mr-1"></i>
                                    On Sale
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300">
                    <div class="bg-red-600 px-8 py-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                <i class="bx bx-shopping-bag text-xl text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-white mb-1">Order Summary</h2>
                                <p class="text-white/90 text-sm">{{ $purchaseType === 'multi_day' ? 'Multi-Day Combo' : 'Single Day' }} Experience</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8 space-y-6">
                        @foreach($tickets as $ticketData)
                            @php
                                $ticket = $ticketData['ticket'];
                                $quantity = $ticketData['quantity'];
                                $price = $ticketData['price'];
                                $day = $ticketData['day'] ?? null;
                            @endphp
                            <div class="py-4 border-t border-gray-200">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="bx bx-purchase-tag text-red-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-gray-900">{{ $ticket->name }} <span class="text-sm text-gray-600">x {{ $quantity }}</span></h4>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-lg font-bold text-gray-900">RM{{ number_format($price * $quantity, 2) }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-600 ml-11">
                                        @if($day)
                                            @if($event->isMultiDay())
                                                {{ $event->getEventDays()[$day-1]['day_name'] }} - {{ $event->getEventDays()[$day-1]['display'] }}
                                            @else
                                                {{ $event->getEventDays()[0]['day_name'] }} - {{ $event->getEventDays()[0]['display'] }}
                                            @endif
                                        @else
                                            @if($event->isMultiDay())
                                                {{ $event->getEventDays()[0]['day_name'] }} - {{ $event->getEventDays()[0]['display'] }}
                                            @else
                                                {{ $event->getEventDays()[0]['day_name'] }} - {{ $event->getEventDays()[0]['display'] }}
                                            @endif
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600">RM{{ number_format($price, 2) }} each</div>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($discountAmount > 0)
                            <div class="py-2">
                                <div class="flex items-center justify-between px-4 py-2 bg-green-50 rounded-lg border border-green-200">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 bg-green-200 rounded flex items-center justify-center mr-2">
                                            <i class="bx bx-gift text-green-600 text-xs"></i>
                                        </div>
                                        <span class="text-green-700 font-medium text-sm">Combo Discount ({{ $event->combo_discount_percentage }}%)</span>
                                    </div>
                                    <span class="text-green-700 font-bold text-sm">-RM{{ number_format($discountAmount, 1) }}</span>
                                </div>
                            </div>
                        @endif
                        
                        <div class="space-y-2 pt-4 border-t border-gray-300">
                            <div class="flex items-center justify-between py-1">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 bg-blue-100 rounded flex items-center justify-center mr-2">
                                        <i class="bx bx-receipt text-blue-600 text-xs"></i>
                                    </div>
                                    <span class="text-gray-700 font-medium text-sm">Subtotal</span>
                                </div>
                                <span class="font-bold text-gray-900 text-sm">RM{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between py-1">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 bg-orange-100 rounded flex items-center justify-center mr-2">
                                        <i class="bx bx-cog text-orange-600 text-xs"></i>
                                    </div>
                                    <span class="text-gray-700 font-medium text-sm">Service Fee ({{ $serviceFeePercentage }}%)</span>
                                </div>
                                <span class="font-bold text-gray-900 text-sm">RM{{ number_format($serviceFee, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between py-1">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 bg-purple-100 rounded flex items-center justify-center mr-2">
                                        <i class="bx bx-calculator text-purple-600 text-xs"></i>
                                    </div>
                                    <span class="text-gray-700 font-medium text-sm">Tax ({{ $taxPercentage }}%)</span>
                                </div>
                                <span class="font-bold text-gray-900 text-sm">RM{{ number_format($taxAmount, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between pt-2 border-t border-gray-400">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 bg-red-100 rounded flex items-center justify-center mr-2">
                                        <i class="bx bx-money text-red-600 text-xs"></i>
                                    </div>
                                    <span class="font-bold text-gray-900 text-sm">Total Amount</span>
                                </div>
                                <span class="font-bold text-red-600 text-sm">RM{{ number_format($totalAmount, 1) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300">
                    <div class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark px-8 py-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                <i class="bx bx-user text-xl text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-white mb-1">Customer Information</h2>
                                <p class="text-white/90 text-sm">Please provide your contact details</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-6">
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

                <!-- Payment Method Selection -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300">
                    <div class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark px-8 py-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                <i class="bx bx-credit-card text-xl text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-white mb-1">Payment Method</h2>
                                <p class="text-white/90 text-sm">Choose your preferred payment method</p>
                            </div>
                        </div>
                    </div>
                    
                    <form id="checkout-form" class="p-8 space-y-8">
                        @csrf
                        
                        <!-- Hidden fields for ticket data -->
                        <input type="hidden" name="purchase_type" value="{{ $purchaseType }}">
                        @foreach($tickets as $index => $ticketData)
                            @php
                                $ticket = $ticketData['ticket'];
                                $quantity = $ticketData['quantity'];
                                $day = $ticketData['day'] ?? null;
                            @endphp
                            @if($purchaseType === 'single_day')
                                <input type="hidden" name="ticket_type_id" value="{{ $ticket->id }}">
                                <input type="hidden" name="quantity" value="{{ $quantity }}">
                                @if($day)
                                    <input type="hidden" name="single_day_selection" value="day{{ $day }}">
                                @endif
                            @else
                                @if($day == 1)
                                    <input type="hidden" name="multi_day1_enabled" value="1">
                                    <input type="hidden" name="day1_ticket_type" value="{{ $ticket->id }}">
                                    <input type="hidden" name="day1_quantity" value="{{ $quantity }}">
                                @elseif($day == 2)
                                    <input type="hidden" name="multi_day2_enabled" value="1">
                                    <input type="hidden" name="day2_ticket_type" value="{{ $ticket->id }}">
                                    <input type="hidden" name="day2_quantity" value="{{ $quantity }}">
                                @endif
                            @endif
                        @endforeach
                        
                        <!-- Customer Information Fields (Hidden) - These will be populated by JavaScript -->
                        <input type="hidden" name="customer_name" id="hidden_customer_name" value="">
                        <input type="hidden" name="customer_email" id="hidden_customer_email" value="">
                        <input type="hidden" name="customer_phone" id="hidden_customer_phone" value="">
                        
                        <!-- Stripe Payment Integration -->
                        <div class="space-y-6">
                            <!-- Payment Messages -->
                            <div id="payment-error" style="display: none;"></div>
                            <div id="payment-success" style="display: none;"></div>

                            <!-- Stripe Payment Element -->
                            <div class="space-y-4">
                                <label class="block text-sm font-medium text-gray-900">
                                    Payment Information
                                </label>
                                <div id="payment-element" class="border border-gray-300 rounded-lg p-4">
                                    <!-- Stripe Elements will be inserted here -->
                                </div>
                            </div>

                            <!-- Security Notice -->
                            <div class="bg-wwc-primary-light border border-wwc-primary rounded-lg p-4">
                                <div class="flex items-start">
                                    <i class='bx bx-shield-check text-wwc-primary text-lg mr-3 mt-0.5'></i>
                                    <div class="text-sm text-gray-700">
                                        <h3 class="font-medium mb-2">Secure Payment</h3>
                                        <p>Your payment information is encrypted and processed securely by Stripe. We never store your card details.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Methods -->
                            <div class="text-center">
                                <p class="text-sm text-gray-500 mb-4">We accept</p>
                                <div class="flex justify-center space-x-4">
                                    <div class="flex items-center space-x-2">
                                        <i class='bx bxl-visa text-2xl text-blue-600'></i>
                                        <span class="text-sm text-gray-600">Visa</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class='bx bxl-mastercard text-2xl text-red-600'></i>
                                        <span class="text-sm text-gray-600">Mastercard</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class='bx bxl-amex text-2xl text-blue-500'></i>
                                        <span class="text-sm text-gray-600">American Express</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="mt-10 pt-6 border-t border-gray-200">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 mt-1">
                                    <input type="checkbox" 
                                           id="terms_agreement" 
                                           name="terms_agreement" 
                                           value="1"
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
                        
                        <!-- Submit Button -->
                        <div class="mt-10">
                            <button type="submit" 
                                    id="purchase-button"
                                    class="w-full bg-gradient-to-r from-wwc-primary to-wwc-primary-dark text-white px-8 py-4 rounded-xl font-bold text-lg hover:from-wwc-primary-dark hover:to-wwc-primary transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:scale-105 group">
                                <i class="bx bx-credit-card mr-3 text-xl group-hover:scale-110 transition-transform duration-200"></i>
                                <span>Complete Purchase</span>
                                <span class="ml-4 bg-white/20 px-4 py-2 rounded-lg font-bold text-lg">RM{{ number_format($totalAmount, 2) }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Global error handler for checkout page
    window.addEventListener('error', function(e) {
        console.error('Checkout page error:', e.error);
        // Redirect to cart page if there's a critical error
        if (e.error && e.error.message && e.error.message.includes('checkout')) {
            window.location.href = '{{ route("public.tickets.cart", $event) }}';
        }
    });
    // Customer information field synchronization
    const customerNameInput = document.getElementById('customer_name');
    const customerEmailInput = document.getElementById('customer_email');
    const customerPhoneInput = document.getElementById('customer_phone');
    
    const hiddenCustomerName = document.getElementById('hidden_customer_name');
    const hiddenCustomerEmail = document.getElementById('hidden_customer_email');
    const hiddenCustomerPhone = document.getElementById('hidden_customer_phone');
    
    // Sync customer information fields
    function syncCustomerFields() {
        if (customerNameInput && hiddenCustomerName) {
            hiddenCustomerName.value = customerNameInput.value;
        }
        if (customerEmailInput && hiddenCustomerEmail) {
            hiddenCustomerEmail.value = customerEmailInput.value;
        }
        if (customerPhoneInput && hiddenCustomerPhone) {
            hiddenCustomerPhone.value = customerPhoneInput.value;
        }
    }
    
    // Initial sync
    syncCustomerFields();
    
    // Add event listeners for real-time sync
    if (customerNameInput) {
        customerNameInput.addEventListener('input', syncCustomerFields);
    }
    if (customerEmailInput) {
        customerEmailInput.addEventListener('input', syncCustomerFields);
    }
    if (customerPhoneInput) {
        customerPhoneInput.addEventListener('input', syncCustomerFields);
    }
    
    // Payment method radio button visual state management
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    
    function updatePaymentMethodRadioStates() {
        paymentRadios.forEach(radio => {
            // Map payment method values to their corresponding IDs
            const methodMap = {
                'credit_card': 'credit_card',
                'online_banking': 'online_banking', 
                'e_wallet': 'ewallet',
                'bank_transfer': 'bank_transfer'
            };
            
            const methodId = methodMap[radio.value] || radio.value;
            const dot = document.getElementById(`payment_${methodId}_dot`);
            const circle = document.getElementById(`payment_${methodId}_radio`);
            
            if (radio.checked) {
                if (dot) dot.style.opacity = '1';
                if (circle) {
                    circle.classList.add('border-wwc-primary', 'bg-wwc-primary');
                    circle.classList.remove('border-gray-300');
                }
            } else {
                if (dot) dot.style.opacity = '0';
                if (circle) {
                    circle.classList.remove('border-wwc-primary', 'bg-wwc-primary');
                    circle.classList.add('border-gray-300');
                }
            }
        });
    }
    
    // Add event listeners
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', updatePaymentMethodRadioStates);
        radio.addEventListener('click', updatePaymentMethodRadioStates);
    });
    
    // Add click listeners to labels for better UX
    const paymentLabels = document.querySelectorAll('label[for^="payment_"]');
    paymentLabels.forEach(label => {
        label.addEventListener('click', function() {
            // Small delay to ensure the radio button is checked before updating states
            setTimeout(updatePaymentMethodRadioStates, 10);
        });
    });
    
    // Initialize visual states
    updatePaymentMethodRadioStates();
    
    // Ensure Credit Card is selected by default
    const creditCardRadio = document.getElementById('payment_credit_card');
    if (creditCardRadio && !creditCardRadio.checked) {
        creditCardRadio.checked = true;
        updatePaymentMethodRadioStates();
    }
    
    // Form submission validation
    const checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Sync customer fields one more time before submission
            syncCustomerFields();
            
            // Collect validation errors
            const errors = [];
            
            // Validate customer name
            const customerName = document.getElementById('customer_name');
            if (!customerName || !customerName.value.trim()) {
                errors.push('Full Name is required');
                customerName?.classList.add('border-red-500');
            } else {
                customerName?.classList.remove('border-red-500');
            }
            
            // Validate customer email
            const customerEmail = document.getElementById('customer_email');
            if (!customerEmail || !customerEmail.value.trim()) {
                errors.push('Email Address is required');
                customerEmail?.classList.add('border-red-500');
            } else {
                // Validate email format
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(customerEmail.value.trim())) {
                    errors.push('Please enter a valid email address');
                    customerEmail.classList.add('border-red-500');
                } else {
                    customerEmail.classList.remove('border-red-500');
                }
            }
            
            // Validate payment method
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!paymentMethod) {
                errors.push('Please select a payment method');
            }
            
            // Validate terms agreement
            const termsCheckbox = document.getElementById('terms_agreement');
            if (!termsCheckbox || !termsCheckbox.checked) {
                errors.push('Please agree to the Terms and Conditions to proceed');
            }
            
            // If there are errors, show them
            if (errors.length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Required Information',
                    html: '<div class="text-left space-y-2">' + 
                          errors.map(error => `<p class="text-sm">• ${error}</p>`).join('') + 
                          '</div>',
                    confirmButtonText: 'I understand',
                    confirmButtonColor: '#dc2626',
                    showCancelButton: false,
                    allowOutsideClick: false,
                });
                return false;
            }
            
            // Show loading state
            const submitButton = document.getElementById('purchase-button');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-3 text-xl"></i>Processing...';
            }
            
            // Submit the form
            this.submit();
        });
    }
});
</script>
@endsection
