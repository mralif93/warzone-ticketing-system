@extends('layouts.admin')

@section('title', 'Edit Order')
@section('page-title', 'Edit Order')

@section('content')
<!-- Professional Order Editing with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <a href="{{ route('admin.orders.show', $order) }}" 
                    class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                    Back to Order
                </a>
            </div>

            <!-- Form Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Edit Order Details</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-edit text-sm'></i>
                            <span>Update order information</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        @if ($errors->any())
                            <div class="bg-wwc-error-light border border-wwc-error text-wwc-error px-4 py-3 rounded-lg mb-6">
                                <div class="flex items-start">
                                    <i class='bx bx-error text-lg mr-3 mt-0.5 flex-shrink-0'></i>
                                    <div>
                                        <h3 class="font-semibold mb-2 text-sm">Please correct the following errors:</h3>
                                        <ul class="list-disc list-inside space-y-1 text-sm">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-6">
                            <!-- Order Information -->
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- User -->
                                <div>
                                    <label for="user_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Customer <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="user_id" id="user_id" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('user_id') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select Customer</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id', $order->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Status <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="status" id="status" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('status') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select Status</option>
                                        <option value="Pending" {{ old('status', $order->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Paid" {{ old('status', $order->status) == 'Paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="Cancelled" {{ old('status', $order->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        <option value="Refunded" {{ old('status', $order->status) == 'Refunded' ? 'selected' : '' }}>Refunded</option>
                                    </select>
                                    @error('status')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Customer Email -->
                                <div>
                                    <label for="customer_email" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Customer Email <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="email" name="customer_email" id="customer_email" required
                                           value="{{ old('customer_email', $order->customer_email) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('customer_email') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter customer email">
                                    @error('customer_email')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Payment Method -->
                                <div>
                                    <label for="payment_method" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Payment Method <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="payment_method" id="payment_method" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('payment_method') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select Payment Method</option>
                                        <option value="Credit Card" {{ old('payment_method', $order->payment_method) == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                                        <option value="Cash" {{ old('payment_method', $order->payment_method) == 'Cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="Bank Transfer" {{ old('payment_method', $order->payment_method) == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="Comp" {{ old('payment_method', $order->payment_method) == 'Comp' ? 'selected' : '' }}>Comp</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Notes -->
                                <div class="sm:col-span-2">
                                    <label for="notes" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Notes
                                    </label>
                                    <textarea name="notes" id="notes" rows="3"
                                              class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('notes') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                              placeholder="Enter order notes">{{ old('notes', $order->notes) }}</textarea>
                                    @error('notes')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Ticket Details -->
                            <div class="border-t border-wwc-neutral-200 pt-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-wwc-neutral-900">Ticket Details</h3>
                                    <div class="text-sm text-wwc-neutral-500">
                                        <i class='bx bx-info-circle text-sm mr-1'></i>
                                        Pricing will be calculated automatically
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <!-- Zone -->
                                    <div>
                                        <label for="zone" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                            Zone <span class="text-wwc-error">*</span>
                                        </label>
                                        <select name="zone" id="zone" required
                                                class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('zone') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                            <option value="">Select Zone</option>
                                            <option value="Warzone Exclusive" {{ old('zone', $order->tickets->first()->zone ?? '') == 'Warzone Exclusive' ? 'selected' : '' }}>Warzone Exclusive - RM500</option>
                                            <option value="Warzone VIP" {{ old('zone', $order->tickets->first()->zone ?? '') == 'Warzone VIP' ? 'selected' : '' }}>Warzone VIP - RM250</option>
                                            <option value="Warzone Grandstand" {{ old('zone', $order->tickets->first()->zone ?? '') == 'Warzone Grandstand' ? 'selected' : '' }}>Warzone Grandstand - RM199</option>
                                            <option value="Warzone Premium Ringside" {{ old('zone', $order->tickets->first()->zone ?? '') == 'Warzone Premium Ringside' ? 'selected' : '' }}>Warzone Premium Ringside - RM150</option>
                                            <option value="Level 1 Zone A/B/C/D" {{ old('zone', $order->tickets->first()->zone ?? '') == 'Level 1 Zone A/B/C/D' ? 'selected' : '' }}>Level 1 Zone A/B/C/D - RM100</option>
                                            <option value="Level 2 Zone A/B/C/D" {{ old('zone', $order->tickets->first()->zone ?? '') == 'Level 2 Zone A/B/C/D' ? 'selected' : '' }}>Level 2 Zone A/B/C/D - RM75</option>
                                            <option value="Standing Zone A/B" {{ old('zone', $order->tickets->first()->zone ?? '') == 'Standing Zone A/B' ? 'selected' : '' }}>Standing Zone A/B - RM50</option>
                                        </select>
                                        @error('zone')
                                            <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Quantity -->
                                    <div>
                                        <label for="quantity" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                            Quantity <span class="text-wwc-error">*</span>
                                        </label>
                                        <input type="number" name="quantity" id="quantity" min="1" max="10" value="{{ old('quantity', $order->tickets->count()) }}" required
                                               class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('quantity') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                               placeholder="Enter quantity">
                                        <p class="text-xs text-wwc-neutral-500 mt-1">Maximum 10 tickets per order</p>
                                        @error('quantity')
                                            <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Pricing Preview -->
                                <div class="mt-4 p-4 bg-wwc-neutral-50 rounded-lg border border-wwc-neutral-200">
                                    <h4 class="text-sm font-semibold text-wwc-neutral-900 mb-2">Pricing Preview</h4>
                                    <div id="pricing-preview" class="text-sm text-wwc-neutral-600">
                                        <p>Select a zone and quantity to see pricing preview.</p>
                                        <p class="mt-1">Service fee: 5% of subtotal | Tax: 6% of (subtotal + service fee)</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-wwc-neutral-200 mt-6">
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-check text-sm mr-2'></i>
                                Update Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Pricing preview script loaded for edit');
    
    const zoneSelect = document.getElementById('zone');
    const quantityInput = document.getElementById('quantity');
    const pricingPreview = document.getElementById('pricing-preview');
    
    // Debug: Check if elements exist
    console.log('Zone select:', zoneSelect);
    console.log('Quantity input:', quantityInput);
    console.log('Pricing preview:', pricingPreview);
    
    if (!zoneSelect || !quantityInput || !pricingPreview) {
        console.error('Required elements not found');
        return;
    }
    
    const zonePrices = {
        'Warzone Exclusive': 500,
        'Warzone VIP': 250,
        'Warzone Grandstand': 199,
        'Warzone Premium Ringside': 150,
        'Level 1 Zone A/B/C/D': 100,
        'Level 2 Zone A/B/C/D': 75,
        'Standing Zone A/B': 50
    };
    
    function updatePricingPreview() {
        console.log('Updating pricing preview');
        const selectedZone = zoneSelect.value;
        const quantity = parseInt(quantityInput.value) || 1;
        
        console.log('Selected zone:', selectedZone);
        console.log('Quantity:', quantity);
        
        if (selectedZone) {
            const basePrice = zonePrices[selectedZone] || 0;
            const subtotal = basePrice * quantity;
            const serviceFee = Math.round(subtotal * 0.05 * 100) / 100;
            const taxAmount = Math.round((subtotal + serviceFee) * 0.06 * 100) / 100;
            const total = subtotal + serviceFee + taxAmount;
            
            console.log('Calculated pricing:', { basePrice, subtotal, serviceFee, taxAmount, total });
            
            pricingPreview.innerHTML = `
                <div class="space-y-1">
                    <div class="flex justify-between">
                        <span>Subtotal (${quantity} × RM${basePrice}):</span>
                        <span class="font-semibold">RM${subtotal.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Service Fee (5%):</span>
                        <span class="font-semibold">RM${serviceFee.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tax (6%):</span>
                        <span class="font-semibold">RM${taxAmount.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between border-t border-wwc-neutral-300 pt-1">
                        <span class="font-semibold">Total:</span>
                        <span class="font-bold text-wwc-primary">RM${total.toFixed(2)}</span>
                    </div>
                </div>
            `;
        } else {
            pricingPreview.innerHTML = `
                <p>Select a zone and quantity to see pricing preview.</p>
                <p class="mt-1">Service fee: 5% of subtotal | Tax: 6% of (subtotal + service fee)</p>
            `;
        }
    }
    
    // Add event listeners
    zoneSelect.addEventListener('change', function() {
        console.log('Zone changed to:', zoneSelect.value);
        updatePricingPreview();
    });
    
    quantityInput.addEventListener('input', function() {
        console.log('Quantity changed to:', quantityInput.value);
        updatePricingPreview();
    });
    
    // Initial calculation if values are pre-filled
    console.log('Running initial calculation');
    updatePricingPreview();
});
</script>
@endpush
@endsection
