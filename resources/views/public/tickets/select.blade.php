@extends('layouts.public')

@section('title', 'Select Seats - ' . $event->name)
@section('description', 'Select your seats for ' . $event->name . ' on ' . $event->date_time->format('M j, Y') . ' at ' . ($event->venue ?? 'TBA venue') . '.')

@section('content')
<div class="min-h-screen bg-gray-50">
    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 text-red-700 px-6 py-4 mx-4 mt-4">
            <div class="flex items-center">
                <i class='bx bx-error-circle text-xl mr-3'></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Authentication Required Notice -->
    @guest
    <div class="bg-amber-50 border-l-4 border-amber-400 text-amber-800 px-6 py-4 mx-4 mt-4">
        <div class="flex items-center">
            <i class='bx bx-info-circle text-xl mr-3'></i>
            <div>
                <span class="font-medium">Authentication Required:</span>
                <span class="ml-2">You need to <a href="{{ route('login') }}" class="underline hover:no-underline font-medium">sign in</a> or <a href="{{ route('register') }}" class="underline hover:no-underline font-medium">create an account</a> to purchase tickets.</span>
            </div>
        </div>
    </div>
    @endguest

    <!-- Simple Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('public.events.show', $event) }}" class="text-gray-500 hover:text-gray-700 mr-6 transition-colors duration-200">
                        <div class="flex items-center">
                            <i class='bx bx-chevron-left text-lg mr-2'></i>
                            <span class="font-medium">Back to Event</span>
                        </div>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $event->name }}</h1>
                        <p class="text-gray-600 text-sm">{{ $event->date_time->format('M j, Y \a\t g:i A') }} • {{ $event->venue ?? 'Venue TBA' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Status</div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($event->status === 'On Sale') bg-green-100 text-green-800
                        @elseif($event->status === 'Sold Out') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $event->status }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Price Zone Selection -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Choose Your Price Zone</h2>
                        <p class="text-sm text-gray-600 mt-1">Select your preferred seating zone. Specific seats will be assigned when you arrive at the venue.</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($priceZoneAvailability as $zone => $stats)
                                <div class="price-zone-option group border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-wwc-primary hover:shadow-md transition-all duration-200" 
                                     data-zone="{{ $zone }}" 
                                     data-available="{{ $stats['available'] }}"
                                     data-price="{{ \App\Models\Seat::where('price_zone', $zone)->first()->base_price ?? 0 }}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-wwc-primary transition-colors duration-200">{{ $zone }}</h3>
                                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">{{ number_format($stats['available']) }} available</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                                <div class="bg-wwc-primary h-2 rounded-full transition-all duration-300" style="width: {{ 100 - $stats['sold_percentage'] }}%"></div>
                                            </div>
                                            <p class="text-xs text-gray-500">{{ $stats['sold_percentage'] }}% sold</p>
                                        </div>
                                        <div class="text-right ml-4">
                                            <div class="text-2xl font-bold text-wwc-primary group-hover:text-wwc-primary-dark transition-colors duration-200">${{ number_format(\App\Models\Seat::where('price_zone', $zone)->first()->base_price ?? 0, 2) }}</div>
                                            <div class="text-xs text-gray-500">per ticket</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Selected Zone Info -->
                <div id="selected-zone-info" class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Selected Zone</h2>
                        <p class="text-sm text-gray-600 mt-1">You have selected the following price zone</p>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-semibold text-gray-900 text-lg" id="selected-zone-name">No zone selected</h3>
                                <p class="text-sm text-gray-600" id="selected-zone-price">Please select a price zone above</p>
                            </div>
                            <button id="change-zone" class="text-wwc-primary hover:text-wwc-primary-dark text-sm font-medium">
                                Change Zone
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Quantity Selection -->
                <div id="quantity-selection" class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Number of Tickets</h2>
                        <p class="text-sm text-gray-600 mt-1">Select how many tickets you need</p>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-center space-x-4">
                            <button type="button" id="decrease-qty" class="h-10 w-10 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-wwc-primary focus:ring-offset-2">
                                <i class='bx bx-minus text-lg'></i>
                            </button>
                            <div class="text-center">
                                <input type="number" id="quantity" min="1" max="{{ $event->max_tickets_per_order }}" value="1" 
                                       class="w-16 text-center text-2xl font-bold text-gray-900 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary">
                                <div class="text-xs text-gray-500 mt-1">Max {{ $event->max_tickets_per_order }} per order</div>
                            </div>
                            <button type="button" id="increase-qty" class="h-10 w-10 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-wwc-primary focus:ring-offset-2">
                                <i class='bx bx-plus text-lg'></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Proceed to Checkout -->
                <div id="checkout-section" class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Ready to Checkout?</h2>
                        <p class="text-sm text-gray-600 mt-1">Review your zone selection and proceed to checkout. Specific seats will be assigned at the venue.</p>
                    </div>
                    <div class="p-6">
                        <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium text-gray-900">Selected Zone:</span>
                                <span class="font-semibold text-gray-900" id="checkout-zone-name">No zone selected</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium text-gray-900">Number of Tickets:</span>
                                <span class="font-semibold text-gray-900" id="checkout-quantity">1</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-900">Total Price:</span>
                                <span class="font-bold text-wwc-primary text-lg" id="checkout-total-price">$0.00</span>
                            </div>
                        </div>
                        <button id="proceed-to-checkout" 
                                class="w-full bg-gray-400 text-white font-semibold py-3 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors duration-200 cursor-not-allowed" disabled>
                            <div class="flex items-center justify-center">
                                <i class='bx bx-shopping-cart text-lg mr-2'></i>
                                <span>Select a Price Zone First</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Event Details -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Event Details</h3>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                                <dd class="text-sm font-semibold text-gray-900 mt-1">{{ $event->date_time->format('M j, Y \a\t g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Venue</dt>
                                <dd class="text-sm font-semibold text-gray-900 mt-1">{{ $event->venue ?? 'Venue TBA' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Max per Order</dt>
                                <dd class="text-sm font-semibold text-gray-900 mt-1">{{ $event->max_tickets_per_order }} tickets</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Availability -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Availability</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="font-medium text-gray-600">Total Capacity</span>
                                    <span class="font-semibold text-gray-900">{{ number_format($availabilityStats['total_capacity']) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gray-400 h-2 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="font-medium text-gray-600">Available</span>
                                    <span class="font-semibold text-gray-900">{{ number_format($availabilityStats['available']) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $availabilityStats['availability_percentage'] }}%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="font-medium text-gray-600">Sold</span>
                                    <span class="font-semibold text-gray-900">{{ number_format($availabilityStats['sold']) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-wwc-primary h-2 rounded-full" style="width: {{ $availabilityStats['sold_percentage'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Need Help -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Need Help?</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 text-sm mb-4">Our support team is here to help you with any questions about seat selection or ticket purchasing.</p>
                        <div class="space-y-3">
                            <a href="mailto:support@warzone-ticketing.com" 
                               class="flex items-center text-wwc-primary hover:text-wwc-primary-dark transition-colors duration-200 font-medium">
                                <i class='bx bx-envelope text-lg mr-3'></i>
                                <span class="text-sm">support@warzone-ticketing.com</span>
                            </a>
                            <a href="tel:+15551234567" 
                               class="flex items-center text-wwc-primary hover:text-wwc-primary-dark transition-colors duration-200 font-medium">
                                <i class='bx bx-phone text-lg mr-3'></i>
                                <span class="text-sm">+1 (555) 123-4567</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedZone = null;
    let selectedPrice = null;
    let selectedQuantity = 1;

    // Price zone selection
    document.querySelectorAll('.price-zone-option').forEach(option => {
        option.addEventListener('click', function() {
            // Remove previous selection
            document.querySelectorAll('.price-zone-option').forEach(opt => {
                opt.classList.remove('border-wwc-primary', 'bg-wwc-primary/5', 'ring-2', 'ring-wwc-primary');
                opt.classList.add('border-gray-200');
            });
            
            // Add selection to clicked option
            this.classList.remove('border-gray-200');
            this.classList.add('border-wwc-primary', 'bg-wwc-primary/5', 'ring-2', 'ring-wwc-primary');
            
            selectedZone = this.dataset.zone;
            selectedPrice = this.dataset.price;
            
            // Show selected zone info and quantity selection
            showSelectedZoneInfo();
            showQuantitySelection();
        });
    });

    // Change zone button
    document.getElementById('change-zone').addEventListener('click', function() {
        // Remove all selections
        document.querySelectorAll('.price-zone-option').forEach(opt => {
            opt.classList.remove('border-wwc-primary', 'bg-wwc-primary/5', 'ring-2', 'ring-wwc-primary');
            opt.classList.add('border-gray-200');
        });
        
        selectedZone = null;
        selectedPrice = null;
        selectedQuantity = 1;
        
        // Reset quantity input
        document.getElementById('quantity').value = 1;
        
        // Reset UI to initial state
        resetToInitialState();
    });

    // Quantity controls
    document.getElementById('decrease-qty').addEventListener('click', function() {
        if (selectedQuantity > 1) {
            selectedQuantity--;
            document.getElementById('quantity').value = selectedQuantity;
            updateCheckoutInfo();
        }
    });

    document.getElementById('increase-qty').addEventListener('click', function() {
        const maxQty = parseInt(document.getElementById('quantity').max);
        if (selectedQuantity < maxQty) {
            selectedQuantity++;
            document.getElementById('quantity').value = selectedQuantity;
            updateCheckoutInfo();
        }
    });

    document.getElementById('quantity').addEventListener('change', function() {
        const value = parseInt(this.value) || 1;
        const maxQty = parseInt(this.max);
        
        if (value < 1) {
            selectedQuantity = 1;
            this.value = 1;
        } else if (value > maxQty) {
            selectedQuantity = maxQty;
            this.value = maxQty;
        } else {
            selectedQuantity = value;
        }
        
        updateCheckoutInfo();
    });

    // Proceed to checkout button
    document.getElementById('proceed-to-checkout').addEventListener('click', function() {
        if (!selectedZone || !selectedQuantity) return;
        
        // Disable button to prevent multiple clicks
        const button = document.getElementById('proceed-to-checkout');
        button.disabled = true;
        button.textContent = 'Finding seats...';
        
        // First, find and hold seats
        fetch(`/events/{{ $event->id }}/find-seats`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            credentials: 'same-origin', // Include cookies for session persistence
            body: JSON.stringify({
                price_zone: selectedZone,
                quantity: selectedQuantity
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Redirect to cart page
                window.location.href = `/events/{{ $event->id }}/cart`;
            } else {
                alert(data.message || 'Failed to find seats. Please try again.');
                // Re-enable button
                button.disabled = false;
                button.textContent = 'Proceed to Checkout';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(`Error: ${error.message}. Please check the console for details.`);
            // Re-enable button
            button.disabled = false;
            button.textContent = 'Proceed to Checkout';
        });
    });

    function showSelectedZoneInfo() {
        const zoneName = selectedZone;
        const zonePrice = `$${parseFloat(selectedPrice).toFixed(2)} per ticket`;
        
        document.getElementById('selected-zone-name').textContent = zoneName;
        document.getElementById('selected-zone-price').textContent = zonePrice;
    }

    function showQuantitySelection() {
        updateCheckoutInfo();
    }

    function updateCheckoutInfo() {
        if (selectedZone && selectedQuantity) {
            // Update checkout section info
            document.getElementById('checkout-zone-name').textContent = selectedZone;
            document.getElementById('checkout-quantity').textContent = selectedQuantity;
            
            const totalPrice = parseFloat(selectedPrice) * selectedQuantity;
            document.getElementById('checkout-total-price').textContent = `$${totalPrice.toFixed(2)}`;
            
            // Enable checkout button
            const button = document.getElementById('proceed-to-checkout');
            button.disabled = false;
            button.className = 'w-full bg-wwc-primary hover:bg-wwc-primary-dark text-white font-semibold py-3 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-wwc-primary focus:ring-offset-2 transition-colors duration-200';
            button.innerHTML = `
                <div class="flex items-center justify-center">
                <i class='bx bx-shopping-cart text-lg mr-2'></i>
                    <span>Proceed to Checkout</span>
                </div>
            `;
        } else {
            // Disable checkout button
            const button = document.getElementById('proceed-to-checkout');
            button.disabled = true;
            button.className = 'w-full bg-gray-400 text-white font-semibold py-3 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors duration-200 cursor-not-allowed';
            button.innerHTML = `
                <div class="flex items-center justify-center">
                <i class='bx bx-shopping-cart text-lg mr-2'></i>
                    <span>Select a Price Zone First</span>
                </div>
            `;
        }
    }

    function resetToInitialState() {
        document.getElementById('selected-zone-name').textContent = 'No zone selected';
        document.getElementById('selected-zone-price').textContent = 'Please select a price zone above';
        document.getElementById('checkout-zone-name').textContent = 'No zone selected';
        document.getElementById('checkout-quantity').textContent = '1';
        document.getElementById('checkout-total-price').textContent = '$0.00';
        
        // Disable checkout button
        const button = document.getElementById('proceed-to-checkout');
        button.disabled = true;
        button.className = 'w-full bg-gray-400 text-white font-semibold py-3 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors duration-200 cursor-not-allowed';
        button.innerHTML = `
            <div class="flex items-center justify-center">
                <i class='bx bx-shopping-cart text-lg mr-2'></i>
                <span>Select a Price Zone First</span>
            </div>
        `;
    }
    });

</script>
@endsection