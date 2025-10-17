@extends('layouts.admin')

@section('title', 'Edit Purchase')
@section('page-title', 'Edit Purchase')

@section('content')
<!-- Professional Purchase Editing with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <a href="{{ route('admin.purchases.show', $purchase) }}" 
                    class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                    Back to Purchase
                </a>
            </div>

            <!-- Form Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Edit Purchase Details</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-edit text-sm'></i>
                            <span>Update purchase information</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.purchases.update', $purchase) }}" method="POST">
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
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- Order Selection -->
                                <div class="sm:col-span-2">
                                    <label for="order_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Order <span class="text-wwc-error">*</span>
                                    </label>
                                    <select id="order_id" 
                                            name="order_id" 
                                            required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('order_id') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select an order...</option>
                                        @foreach($orders as $order)
                                            <option value="{{ $order->id }}" {{ old('order_id', $purchase->order_id) == $order->id ? 'selected' : '' }}>
                                                #{{ $order->id }} - {{ $order->user->name }} (RM{{ number_format($order->total_amount, 2) }}) - {{ ucfirst($order->status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('order_id')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Event Selection -->
                                <div>
                                    <label for="event_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Event <span class="text-wwc-error">*</span>
                                    </label>
                                    <select id="event_id" 
                                            name="event_id" 
                                            required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('event_id') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select an event...</option>
                                        @foreach($events as $event)
                                            <option value="{{ $event->id }}" {{ old('event_id', $purchase->event_id) == $event->id ? 'selected' : '' }}>
                                                {{ $event->name }} - {{ $event->date_time->format('M d, Y H:i') }} ({{ $event->status }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('event_id')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Ticket Type Selection -->
                                <div>
                                    <label for="ticket_type_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Ticket Type <span class="text-wwc-error">*</span>
                                    </label>
                                    <select id="ticket_type_id" 
                                            name="ticket_type_id" 
                                            required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('ticket_type_id') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select a ticket type...</option>
                                        @foreach($ticketTypes as $ticketType)
                                            <option value="{{ $ticketType->id }}" {{ old('ticket_type_id', $purchase->ticket_type_id) == $ticketType->id ? 'selected' : '' }}>
                                                {{ $ticketType->name }} - {{ $ticketType->event->name }} (RM{{ number_format($ticketType->price, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ticket_type_id')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Price Paid -->
                                <div>
                                    <label for="price_paid" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Price Paid <span class="text-wwc-error">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-wwc-neutral-500 sm:text-sm">RM</span>
                                        </div>
                                        <input type="number" 
                                               id="price_paid" 
                                               name="price_paid" 
                                               step="0.01"
                                               min="0"
                                               value="{{ old('price_paid', $purchase->price_paid) }}"
                                               required
                                               class="block w-full pl-10 pr-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('price_paid') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                               placeholder="0.00">
                                    </div>
                                    @error('price_paid')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Original Price -->
                                <div>
                                    <label for="original_price" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Original Price
                                        <span class="text-xs text-wwc-neutral-500 font-normal">(Before discount)</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-wwc-neutral-500 sm:text-sm">RM</span>
                                        </div>
                                        <input type="number" 
                                               id="original_price" 
                                               name="original_price" 
                                               step="0.01"
                                               min="0"
                                               value="{{ old('original_price', $purchase->original_price) }}"
                                               class="block w-full pl-10 pr-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('original_price') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                               placeholder="0.00">
                                    </div>
                                    @error('original_price')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Discount Amount -->
                                <div>
                                    <label for="discount_amount" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Discount Amount
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-wwc-neutral-500 sm:text-sm">RM</span>
                                        </div>
                                        <input type="number" 
                                               id="discount_amount" 
                                               name="discount_amount" 
                                               step="0.01"
                                               min="0"
                                               value="{{ old('discount_amount', $purchase->discount_amount) }}"
                                               class="block w-full pl-10 pr-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('discount_amount') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                               placeholder="0.00">
                                    </div>
                                    @error('discount_amount')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Status <span class="text-wwc-error">*</span>
                                    </label>
                                    <select id="status" 
                                            name="status" 
                                            required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('status') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="pending" {{ old('status', $purchase->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="scanned" {{ old('status', $purchase->status) == 'scanned' ? 'selected' : '' }}>Scanned</option>
                                        <option value="cancelled" {{ old('status', $purchase->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Event Day -->
                                <div>
                                    <label for="event_day" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Event Day
                                        <span class="text-xs text-wwc-neutral-500 font-normal">(For multi-day events)</span>
                                    </label>
                                    <input type="date" 
                                           id="event_day" 
                                           name="event_day" 
                                           value="{{ old('event_day', $purchase->event_day ? $purchase->event_day->format('Y-m-d') : '') }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('event_day') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                    @error('event_day')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Combo Group ID -->
                                <div>
                                    <label for="combo_group_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Combo Group ID
                                        <span class="text-xs text-wwc-neutral-500 font-normal">(For combo purchases)</span>
                                    </label>
                                    <input type="text" 
                                           id="combo_group_id" 
                                           name="combo_group_id" 
                                           value="{{ old('combo_group_id', $purchase->combo_group_id) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('combo_group_id') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter combo group ID">
                                    @error('combo_group_id')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Combo Purchase Section -->
                            <div class="border-t border-wwc-neutral-200 pt-6">
                                <div class="flex items-center mb-4">
                                    <h4 class="text-lg font-semibold text-wwc-neutral-900">Combo Purchase Settings</h4>
                                    <span class="ml-2 text-xs text-wwc-neutral-500">(For combo deals)</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="is_combo_purchase" 
                                           name="is_combo_purchase" 
                                           value="1"
                                           {{ old('is_combo_purchase', $purchase->is_combo_purchase) ? 'checked' : '' }}
                                           class="h-4 w-4 text-wwc-primary focus:ring-wwc-primary border-wwc-neutral-300 rounded @error('is_combo_purchase') border-wwc-error @enderror">
                                    <label for="is_combo_purchase" class="ml-2 text-sm font-semibold text-wwc-neutral-900">
                                        This is a combo purchase
                                    </label>
                                </div>
                                <p class="text-xs text-wwc-neutral-500 mt-1">Check if this purchase is part of a combo deal with discounts</p>
                                @error('is_combo_purchase')
                                    <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-wwc-neutral-200 mt-6">
                            <a href="{{ route('admin.purchases.show', $purchase) }}" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-check text-sm mr-2'></i>
                                Update Purchase
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
    const pricePaidField = document.getElementById('price_paid');
    const originalPriceField = document.getElementById('original_price');
    const comboPurchaseCheckbox = document.getElementById('is_combo_purchase');
    const comboGroupField = document.getElementById('combo_group_id');
    
    // Auto-fill original price when price paid changes
    pricePaidField.addEventListener('input', function() {
        if (!originalPriceField.value) {
            originalPriceField.value = this.value;
        }
    });
    
    // Auto-generate combo group ID when combo purchase is checked
    comboPurchaseCheckbox.addEventListener('change', function() {
        if (this.checked && !comboGroupField.value) {
            comboGroupField.value = 'COMBO_' + Date.now();
        } else if (!this.checked) {
            comboGroupField.value = '';
        }
    });
    
    // Auto-calculate discount when both prices are filled
    function calculateDiscount() {
        const pricePaid = parseFloat(pricePaidField.value) || 0;
        const originalPrice = parseFloat(originalPriceField.value) || 0;
        
        if (originalPrice > pricePaid) {
            const discountAmount = originalPrice - pricePaid;
            document.getElementById('discount_amount').value = discountAmount.toFixed(2);
        }
    }
    
    pricePaidField.addEventListener('input', calculateDiscount);
    originalPriceField.addEventListener('input', calculateDiscount);
});
</script>
@endsection
