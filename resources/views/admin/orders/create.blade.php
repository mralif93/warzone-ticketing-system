@extends('layouts.admin')

@section('title', 'Create Order')
@section('page-title', 'Create Order')

@section('content')
<!-- Professional Order Creation with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <a href="{{ route('admin.orders.index') }}" 
                    class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                    Back to Orders
                </a>
            </div>

            <!-- Form Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Create New Order</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-plus-circle text-sm'></i>
                            <span>Fill in the information below</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.orders.store') }}" method="POST">
                        @csrf
                        
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
                            <!-- Order Information Section -->
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- Event -->
                                <div>
                                    <label for="event_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Event <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="event_id" id="event_id" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('event_id') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select Event</option>
                                        @foreach($events as $event)
                                            <option value="{{ $event->id }}" 
                                                    data-is-multi-day="{{ $event->isMultiDay() ? 'true' : 'false' }}"
                                                    data-combo-enabled="{{ $event->combo_discount_enabled ? 'true' : 'false' }}"
                                                    data-combo-percentage="{{ $event->combo_discount_percentage }}"
                                                    {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                                {{ $event->name }} - {{ $event->getFormattedDateRange() }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('event_id')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Customer -->
                                <div>
                                    <label for="user_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Customer <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="user_id" id="user_id" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('user_id') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select Customer</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
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
                                        <div class="inline-block relative group ml-1">
                                            <i class='bx bx-info-circle text-wwc-neutral-400 hover:text-wwc-primary cursor-help text-sm'></i>
                                            <!-- Tooltip -->
                                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-wwc-neutral-900 text-white text-xs rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50 w-64">
                                                <div class="space-y-1">
                                                    <div class="flex items-center">
                                                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                                                        <span><strong>Pending:</strong> Order created but payment not yet confirmed</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                                        <span><strong>Paid:</strong> Payment confirmed, tickets are valid</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                                        <span><strong>Cancelled:</strong> Order cancelled, tickets invalid</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                                        <span><strong>Refunded:</strong> Payment refunded, tickets invalid</span>
                                                    </div>
                                                </div>
                                                <!-- Arrow -->
                                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-wwc-neutral-900"></div>
                                            </div>
                                        </div>
                                    </label>
                                    <select name="status" id="status" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('status') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select Status</option>
                                        <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Paid" {{ old('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        <option value="Refunded" {{ old('status') == 'Refunded' ? 'selected' : '' }}>Refunded</option>
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
                                           value="{{ old('customer_email') }}"
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
                                        <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="Online Banking" {{ old('payment_method') == 'Online Banking' ? 'selected' : '' }}>Online Banking</option>
                                        <option value="QR Code / E-Wallet" {{ old('payment_method') == 'QR Code / E-Wallet' ? 'selected' : '' }}>QR Code / E-Wallet</option>
                                        <option value="Debit / Credit Card" {{ old('payment_method') == 'Debit / Credit Card' ? 'selected' : '' }}>Debit / Credit Card</option>
                                        <option value="Others" {{ old('payment_method') == 'Others' ? 'selected' : '' }}>Others</option>
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
                                              placeholder="Enter order notes">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Ticket Purchase Process -->
                            <div class="border-t border-wwc-neutral-200 pt-6">
                                <div class="flex items-center mb-6">
                                    <h4 class="text-lg font-semibold text-wwc-neutral-900">Ticket Purchase Process</h4>
                                    <span class="ml-2 text-xs text-wwc-neutral-500">(Step-by-step ticket selection)</span>
                                </div>

                                <!-- Step 1: Purchase Type Selection -->
                                <div class="mb-6">
                                    <div class="flex items-center mb-4">
                                        <div class="flex items-center justify-center w-8 h-8 bg-wwc-primary text-white rounded-full text-sm font-semibold mr-3">1</div>
                                        <h5 class="text-base font-semibold text-wwc-neutral-900">Choose Purchase Type</h5>
                                    </div>
                                    
                                    <div class="bg-wwc-neutral-50 p-6 rounded-lg border border-wwc-neutral-200">
                                        <div class="flex flex-col sm:flex-row gap-6">
                                            <!-- Single Day Option -->
                                            <div class="flex items-start">
                                                <input type="radio" name="purchase_type" value="single_day" id="single_day" 
                                                       class="h-4 w-4 text-wwc-primary focus:ring-wwc-primary border-wwc-neutral-300 mt-1" checked>
                                                <label for="single_day" class="ml-3 flex flex-col">
                                                    <span class="text-sm font-semibold text-wwc-neutral-900">Single Day Purchase</span>
                                                    <span class="text-xs text-wwc-neutral-600">Purchase tickets for one specific day of the event</span>
                                                </label>
                                            </div>

                                            <!-- Multi-Day Option -->
                                            <div class="flex items-start">
                                                <input type="radio" name="purchase_type" value="multi_day" id="multi_day" 
                                                       class="h-4 w-4 text-wwc-primary focus:ring-wwc-primary border-wwc-neutral-300 mt-1">
                                                <label for="multi_day" class="ml-3 flex flex-col">
                                                    <span class="text-sm font-semibold text-wwc-neutral-900">Multi-Day Purchase (Day 1 & Day 2)</span>
                                                    <span class="text-xs text-wwc-neutral-600">Purchase tickets for both days with combo discount</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Ticket Selection -->
                                <div class="mb-6">
                                    <div class="flex items-center mb-4">
                                        <div class="flex items-center justify-center w-8 h-8 bg-wwc-primary text-white rounded-full text-sm font-semibold mr-3">2</div>
                                        <h5 class="text-base font-semibold text-wwc-neutral-900">Select Your Tickets</h5>
                                    </div>

                                    <!-- Single Day Ticket Selection -->
                                    <div id="single-day-section" class="bg-wwc-neutral-50 p-6 rounded-lg border border-wwc-neutral-200">
                                        <div class="flex items-center mb-4">
                                            <div class="w-6 h-6 bg-wwc-primary text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</div>
                                            <h6 class="text-lg font-semibold text-wwc-neutral-900">Single Day Tickets</h6>
                                        </div>
                                        <p class="text-sm text-wwc-neutral-600 mb-6">Choose your preferred ticket type and quantity for single day attendance.</p>
                                        
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <!-- Ticket Type Selection -->
                                    <div>
                                                <label for="ticket_type_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                                    Ticket Type <span class="text-wwc-error">*</span>
                                        </label>
                                                <select name="ticket_type_id" id="ticket_type_id" required disabled
                                                        class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('ticket_type_id') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                                    <option value="">Please select an event first</option>
                                        </select>
                                                <p class="text-xs text-wwc-neutral-500 mt-2">Choose your preferred ticket category</p>
                                                @error('ticket_type_id')
                                            <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                        @enderror
                                    </div>

                                            <!-- Quantity Selection -->
                                    <div>
                                        <label for="quantity" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                            Quantity <span class="text-wwc-error">*</span>
                                        </label>
                                                <div class="flex items-center space-x-4">
                                                    <button type="button" id="quantity-decrease" class="w-10 h-10 bg-wwc-neutral-200 hover:bg-wwc-neutral-300 rounded-lg flex items-center justify-center transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                        </svg>
                                                    </button>
                                                    <input type="number" name="quantity" id="quantity" min="1" max="10" value="1" required disabled
                                                           class="w-20 px-3 py-2 text-center border border-gray-300 bg-white text-gray-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('quantity') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                                    <button type="button" id="quantity-increase" class="w-10 h-10 bg-wwc-neutral-200 hover:bg-wwc-neutral-300 rounded-lg flex items-center justify-center transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <p class="text-xs text-wwc-neutral-500 mt-2">Maximum 10 tickets per order</p>
                                        @error('quantity')
                                            <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                        @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Multi-Day Ticket Selection -->
                                    <div id="multi-day-section" class="hidden bg-wwc-neutral-50 p-6 rounded-lg border border-wwc-neutral-200">
                                        <div class="flex items-center mb-4">
                                            <div class="w-6 h-6 bg-wwc-primary text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">2</div>
                                            <h6 class="text-lg font-semibold text-wwc-neutral-900">Multi-Day Tickets</h6>
                                        </div>
                                        <p class="text-sm text-wwc-neutral-600 mb-6">Select different ticket types and quantities for each day. Save with combo discount!</p>
                                        
                                        <div class="space-y-6">
                                            <!-- Day 1 Selection -->
                                            <div class="bg-white p-6 rounded-lg border border-wwc-neutral-200">
                                                <div class="flex items-center mb-4">
                                                    <div class="w-8 h-8 bg-wwc-primary text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</div>
                                                    <h6 class="text-lg font-semibold text-wwc-neutral-900">Day 1 Tickets</h6>
                                                </div>
                                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                                    <div>
                                                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Ticket Type</label>
                                                        <select name="day1_ticket_type" id="day1_ticket_type"
                                                                class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                                            <option value="">Select Day 1 Ticket</option>
                                                        </select>
                                                        <p class="text-xs text-wwc-neutral-500 mt-2">Choose ticket type for Day 1</p>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Quantity</label>
                                                        <div class="flex items-center space-x-4">
                                                            <button type="button" id="day1-quantity-decrease" class="w-10 h-10 bg-wwc-neutral-200 hover:bg-wwc-neutral-300 rounded-lg flex items-center justify-center transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                                </svg>
                                                            </button>
                                                            <input type="number" name="day1_quantity" id="day1_quantity" min="1" max="10" value="1"
                                                                   class="w-20 px-3 py-2 text-center border border-gray-300 bg-white text-gray-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                            <button type="button" id="day1-quantity-increase" class="w-10 h-10 bg-wwc-neutral-200 hover:bg-wwc-neutral-300 rounded-lg flex items-center justify-center transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <p class="text-xs text-wwc-neutral-500 mt-2">Maximum 10 tickets</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Day 2 Selection -->
                                            <div class="bg-white p-6 rounded-lg border border-wwc-neutral-200">
                                                <div class="flex items-center mb-4">
                                                    <div class="w-8 h-8 bg-wwc-primary text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">2</div>
                                                    <h6 class="text-lg font-semibold text-wwc-neutral-900">Day 2 Tickets</h6>
                                                </div>
                                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                                    <div>
                                                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Ticket Type</label>
                                                        <select name="day2_ticket_type" id="day2_ticket_type"
                                                                class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                                            <option value="">Select Day 2 Ticket</option>
                                                        </select>
                                                        <p class="text-xs text-wwc-neutral-500 mt-2">Choose ticket type for Day 2</p>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Quantity</label>
                                                        <div class="flex items-center space-x-4">
                                                            <button type="button" id="day2-quantity-decrease" class="w-10 h-10 bg-wwc-neutral-200 hover:bg-wwc-neutral-300 rounded-lg flex items-center justify-center transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                                </svg>
                                                            </button>
                                                            <input type="number" name="day2_quantity" id="day2_quantity" min="1" max="10" value="1"
                                                                   class="w-20 px-3 py-2 text-center border border-gray-300 bg-white text-gray-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                            <button type="button" id="day2-quantity-increase" class="w-10 h-10 bg-wwc-neutral-200 hover:bg-wwc-neutral-300 rounded-lg flex items-center justify-center transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <p class="text-xs text-wwc-neutral-500 mt-2">Maximum 10 tickets</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Combo Discount Option -->
                                        <div class="mt-6 bg-wwc-primary/5 p-4 rounded-lg border border-wwc-primary/20">
                                            <div class="flex items-center">
                                                <input type="checkbox" name="is_combo_purchase" id="is_combo_purchase" value="1"
                                                       class="h-5 w-5 text-wwc-primary focus:ring-wwc-primary border-wwc-neutral-300 rounded">
                                                <label for="is_combo_purchase" class="ml-3 flex items-center">
                                                    <svg class="w-5 h-5 text-wwc-primary mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="text-sm font-semibold text-wwc-neutral-900">Apply Combo Discount</span>
                                                </label>
                                            </div>
                                            <p class="text-xs text-wwc-neutral-600 mt-2 ml-8">
                                                Save money by purchasing tickets for both days together. Discount applies to the total price of all tickets.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pricing Preview -->
                                <div class="mt-4 p-4 bg-wwc-neutral-50 rounded-lg border border-wwc-neutral-200">
                                    <h4 class="text-sm font-semibold text-wwc-neutral-900 mb-2">Pricing Preview</h4>
                                    <div id="pricing-preview" class="text-sm text-wwc-neutral-600">
                                        <p>Select a ticket type and quantity to see pricing preview.</p>
                                        <p class="mt-1">Service fee: 5% of subtotal | Tax: 6% of (subtotal + service fee)</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-wwc-neutral-200 mt-6">
                            <a href="{{ route('admin.orders.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-plus text-sm mr-2'></i>
                                Create Order
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
            const eventSelect = document.getElementById('event_id');
            const ticketTypeSelect = document.getElementById('ticket_type_id');
    const quantityInput = document.getElementById('quantity');
    const pricingPreview = document.getElementById('pricing-preview');
            // Event day section removed - no longer needed
            const eventDaySelect = document.getElementById('event_day');
            const comboCheckbox = document.getElementById('is_combo_purchase');
            
            // New elements for different purchase types
            const purchaseTypeRadios = document.querySelectorAll('input[name="purchase_type"]');
            const singleDaySection = document.getElementById('single-day-section');
            const multiDaySection = document.getElementById('multi-day-section');
            const day1TicketSelect = document.getElementById('day1_ticket_type');
            const day2TicketSelect = document.getElementById('day2_ticket_type');
            const day1QuantityInput = document.getElementById('day1_quantity');
            const day2QuantityInput = document.getElementById('day2_quantity');
    
    if (!eventSelect || !ticketTypeSelect || !quantityInput || !pricingPreview) {
        console.error('Required elements not found');
        return;
    }
    
    // Event data from server
    const events = @json($events->keyBy('id'));
    
            function updateTicketTypes() {
                const selectedEventId = eventSelect.value;
                
                // Clear existing options and disable
                ticketTypeSelect.innerHTML = '<option value="">Loading ticket types...</option>';
                ticketTypeSelect.disabled = true;
                day1TicketSelect.innerHTML = '<option value="">Loading ticket types...</option>';
                day1TicketSelect.disabled = true;
                day2TicketSelect.innerHTML = '<option value="">Loading ticket types...</option>';
                day2TicketSelect.disabled = true;
                
                if (selectedEventId && events[selectedEventId]) {
                    const event = events[selectedEventId];
                    console.log('Selected event:', event);
                    console.log('Is multi-day:', event.is_multi_day);
                    
                    // Multi-day events are handled by purchase type selection
                    if (event.is_multi_day) {
                        console.log('Event is multi-day, purchase type selection will handle display');
                    } else {
                        console.log('Event is single-day');
                    }
                    
                    // Update purchase type availability based on event type
                    updatePurchaseTypeAvailability(event);
                    
                    // Fetch ticket types for this event
                    fetchTicketTypes(selectedEventId);
                } else {
                    ticketTypeSelect.innerHTML = '<option value="">Please select an event first</option>';
                    ticketTypeSelect.disabled = true;
                    day1TicketSelect.innerHTML = '<option value="">Please select an event first</option>';
                    day1TicketSelect.disabled = true;
                    day2TicketSelect.innerHTML = '<option value="">Please select an event first</option>';
                    day2TicketSelect.disabled = true;
                    // No event day section to hide
                    
                    // Reset purchase type availability when no event selected
                    updatePurchaseTypeAvailability(null);
                }
                
                updatePricingPreview();
            }
    
    function fetchTicketTypes(eventId) {
        console.log('Fetching ticket types for event:', eventId);
        
        fetch(`/admin/events/${eventId}/ticket-types`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
                    .then(data => {
                        console.log('Received data:', data);
                        ticketTypeSelect.innerHTML = '<option value="">Select Ticket Type</option>';
                        day1TicketSelect.innerHTML = '<option value="">Select Day 1 Ticket</option>';
                        day2TicketSelect.innerHTML = '<option value="">Select Day 2 Ticket</option>';
                        
                        if (data.ticketTypes && data.ticketTypes.length > 0) {
                            console.log('Found', data.ticketTypes.length, 'ticket types');
                            data.ticketTypes.forEach(ticketType => {
                                const option = document.createElement('option');
                                option.value = ticketType.id;
                                option.setAttribute('data-price', ticketType.price);
                                option.setAttribute('data-available', ticketType.available_seats);
                                option.textContent = `${ticketType.name} - RM${parseFloat(ticketType.price).toFixed(2)}${ticketType.available_seats > 0 ? ` (${ticketType.available_seats} available)` : ' (Sold Out)'}`;
                                
                                // Add to main ticket type select
                                ticketTypeSelect.appendChild(option.cloneNode(true));
                                
                                // Add to day 1 and day 2 selects
                                if (day1TicketSelect) {
                                    day1TicketSelect.appendChild(option.cloneNode(true));
                                    console.log('Added option to day1TicketSelect:', ticketType.name);
                                }
                                if (day2TicketSelect) {
                                    day2TicketSelect.appendChild(option.cloneNode(true));
                                    console.log('Added option to day2TicketSelect:', ticketType.name);
                                }
                            });
                            
                            console.log('Day1 dropdown options count:', day1TicketSelect ? day1TicketSelect.options.length : 'null');
                            console.log('Day2 dropdown options count:', day2TicketSelect ? day2TicketSelect.options.length : 'null');
                            // Enable ticket selects based on current purchase type
                            const selectedType = document.querySelector('input[name="purchase_type"]:checked')?.value;
                            console.log('Selected purchase type:', selectedType);
                            if (selectedType === 'single_day') {
                                ticketTypeSelect.disabled = false;
                                console.log('Enabled single day ticket select');
                            } else if (selectedType === 'multi_day') {
                                if (day1TicketSelect) {
                                    day1TicketSelect.disabled = false;
                                    console.log('Enabled day1TicketSelect, disabled:', day1TicketSelect.disabled);
                                }
                                if (day2TicketSelect) {
                                    day2TicketSelect.disabled = false;
                                    console.log('Enabled day2TicketSelect, disabled:', day2TicketSelect.disabled);
                                }
                                console.log('Enabled multi-day ticket selects');
                            }
                        } else {
                            console.log('No ticket types found');
                            ticketTypeSelect.innerHTML = '<option value="">No ticket types available for this event</option>';
                            ticketTypeSelect.disabled = true;
                            day1TicketSelect.innerHTML = '<option value="">No ticket types available for this event</option>';
                            day1TicketSelect.disabled = true;
                            day2TicketSelect.innerHTML = '<option value="">No ticket types available for this event</option>';
                            day2TicketSelect.disabled = true;
                        }
                        
                        updatePricingPreview();
                    })
            .catch(error => {
                console.error('Error fetching ticket types:', error);
                ticketTypeSelect.innerHTML = '<option value="">Error loading ticket types</option>';
                ticketTypeSelect.disabled = true;
            });
    }
    
    function updateEventDays(event) {
        eventDaySelect.innerHTML = '<option value="">Select Event Day</option>';
        
        if (event.event_days) {
            event.event_days.forEach(day => {
                const option = document.createElement('option');
                option.value = day.date;
                option.textContent = `${day.day_name} (${day.display})`;
                eventDaySelect.appendChild(option);
            });
        }
    }
    
    function updatePurchaseTypeSections() {
        const selectedRadio = document.querySelector('input[name="purchase_type"]:checked');
        const selectedType = selectedRadio ? selectedRadio.value : null;
        const quantityNote = document.getElementById('multi-day-quantity-note');
        
        // If no purchase type is selected, don't proceed
        if (!selectedType) {
            console.log('No purchase type selected, skipping section update');
            return;
        }
        
        console.log('Updating purchase type sections for:', selectedType);
        
        // Hide all sections
        singleDaySection.classList.add('hidden');
        multiDaySection.classList.add('hidden');
        
        // Show selected section
        switch(selectedType) {
            case 'single_day':
                singleDaySection.classList.remove('hidden');
                multiDaySection.classList.add('hidden');
                // Enable single day fields
                ticketTypeSelect.disabled = false;
                quantityInput.disabled = false;
                quantityInput.value = 1;
                // Make ticket type required for single day
                ticketTypeSelect.required = true;
                if (quantityNote) quantityNote.classList.add('hidden');
                break;
            case 'multi_day':
                singleDaySection.classList.add('hidden');
                multiDaySection.classList.remove('hidden');
                // Enable multi-day fields
                if (day1TicketSelect) day1TicketSelect.disabled = false;
                if (day2TicketSelect) day2TicketSelect.disabled = false;
                if (day1QuantityInput) day1QuantityInput.disabled = false;
                if (day2QuantityInput) day2QuantityInput.disabled = false;
                // Make ticket type not required for multi-day
                ticketTypeSelect.required = false;
                if (quantityNote) quantityNote.classList.add('hidden');
                break;
        }
        
        updatePricingPreview();
        
        // Re-enable appropriate ticket type dropdowns if event is already selected
        const eventId = eventSelect.value;
        if (eventId && events[eventId]) {
            const selectedType = document.querySelector('input[name="purchase_type"]:checked')?.value;
            console.log('Re-enabling dropdowns for purchase type:', selectedType);
            if (selectedType === 'single_day') {
                ticketTypeSelect.disabled = false;
                console.log('Re-enabled single day ticket select');
            } else if (selectedType === 'multi_day') {
                day1TicketSelect.disabled = false;
                day2TicketSelect.disabled = false;
                console.log('Re-enabled multi-day ticket selects');
            }
        }
    }
    
    function updatePurchaseTypeAvailability(event) {
        const singleDayRadio = document.getElementById('single_day');
        const multiDayRadio = document.getElementById('multi_day');
        const singleDayContainer = singleDayRadio?.closest('div');
        const multiDayContainer = multiDayRadio?.closest('div');
        
        if (event && event.is_multi_day) {
            console.log('Multi-day event detected, auto-selecting multi-day option');
            // Multi-day event: Enable both options and auto-select multi-day
            if (singleDayRadio) singleDayRadio.disabled = false;
            if (multiDayRadio) {
                multiDayRadio.disabled = false;
                multiDayRadio.checked = true; // Auto-select multi-day for multi-day events
                console.log('Multi-day radio button checked:', multiDayRadio.checked);
            }
            if (singleDayContainer) {
                singleDayContainer.classList.remove('opacity-50', 'cursor-not-allowed');
                singleDayContainer.classList.add('cursor-pointer');
            }
            if (multiDayContainer) {
                multiDayContainer.classList.remove('opacity-50', 'cursor-not-allowed');
                multiDayContainer.classList.add('cursor-pointer');
            }
            // Update sections after auto-selecting multi-day
            updatePurchaseTypeSections();
        } else if (event) {
            // Single-day event: Disable multi-day option
            if (singleDayRadio) singleDayRadio.disabled = false;
            if (multiDayRadio) {
                multiDayRadio.disabled = true;
                multiDayRadio.checked = false;
            }
            if (singleDayContainer) {
                singleDayContainer.classList.remove('opacity-50', 'cursor-not-allowed');
                singleDayContainer.classList.add('cursor-pointer');
            }
            if (multiDayContainer) {
                multiDayContainer.classList.add('opacity-50', 'cursor-not-allowed');
                multiDayContainer.classList.remove('cursor-pointer');
            }
            // Auto-select single day for single-day events
            if (singleDayRadio) singleDayRadio.checked = true;
            updatePurchaseTypeSections();
        } else {
            // No event selected: Disable both options
            if (singleDayRadio) singleDayRadio.disabled = true;
            if (multiDayRadio) multiDayRadio.disabled = true;
            if (singleDayContainer) {
                singleDayContainer.classList.add('opacity-50', 'cursor-not-allowed');
                singleDayContainer.classList.remove('cursor-pointer');
            }
            if (multiDayContainer) {
                multiDayContainer.classList.add('opacity-50', 'cursor-not-allowed');
                multiDayContainer.classList.remove('cursor-pointer');
            }
        }
    }
    
    function updatePricingPreview() {
        const selectedType = document.querySelector('input[name="purchase_type"]:checked').value;
        let pricingHTML = '';
        
        if (selectedType === 'multi_day') {
            // Handle multi-day purchase (Day 1 & Day 2) with individual quantities
            const day1Ticket = day1TicketSelect.options[day1TicketSelect.selectedIndex];
            const day2Ticket = day2TicketSelect.options[day2TicketSelect.selectedIndex];
            const day1Quantity = parseInt(day1QuantityInput ? day1QuantityInput.value : 1) || 1;
            const day2Quantity = parseInt(day2QuantityInput ? day2QuantityInput.value : 1) || 1;
            const isCombo = comboCheckbox ? comboCheckbox.checked : false;
            
            if (day1Ticket && day1Ticket.value && day2Ticket && day2Ticket.value) {
                const day1Price = parseFloat(day1Ticket.getAttribute('data-price')) || 0;
                const day2Price = parseFloat(day2Ticket.getAttribute('data-price')) || 0;
                const day1Available = parseInt(day1Ticket.getAttribute('data-available')) || 0;
                const day2Available = parseInt(day2Ticket.getAttribute('data-available')) || 0;
                
                if (day1Available < day1Quantity || day2Available < day2Quantity) {
                    pricingHTML = `
                        <div class="text-wwc-error">
                            <p class="font-semibold">Insufficient tickets available!</p>
                            <p class="text-sm">Day 1: ${day1Available} available (need ${day1Quantity}) | Day 2: ${day2Available} available (need ${day2Quantity})</p>
                        </div>
                    `;
                } else {
                    const day1Subtotal = day1Price * day1Quantity;
                    const day2Subtotal = day2Price * day2Quantity;
                    let subtotal = day1Subtotal + day2Subtotal;
                    let discountAmount = 0;
                    
                    // Apply combo discount if applicable
                    const eventId = eventSelect.value;
                    const isMultiDayEvent = eventId && events[eventId] && events[eventId].is_multi_day;
                    const isComboEligible = isMultiDayEvent && isCombo && events[eventId].combo_discount_enabled;
                    
                    if (isComboEligible) {
                        const discountPercentage = events[eventId].combo_discount_percentage || 10;
                        discountAmount = subtotal * (discountPercentage / 100);
                        subtotal = subtotal - discountAmount;
                    }
                    
            const serviceFee = Math.round(subtotal * 0.05 * 100) / 100;
            const taxAmount = Math.round((subtotal + serviceFee) * 0.06 * 100) / 100;
            const total = subtotal + serviceFee + taxAmount;
            
                    pricingHTML = `
                        <div class="space-y-1">
                            <div class="flex justify-between">
                                <span>Day 1 (${day1Ticket.textContent.split(' - ')[0]}) × ${day1Quantity}:</span>
                                <span class="font-semibold">RM${day1Subtotal.toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Day 2 (${day2Ticket.textContent.split(' - ')[0]}) × ${day2Quantity}:</span>
                                <span class="font-semibold">RM${day2Subtotal.toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between border-t border-wwc-neutral-300 pt-1">
                                <span>Subtotal:</span>
                                <span class="font-semibold">RM${(day1Subtotal + day2Subtotal).toFixed(2)}</span>
                            </div>
                            ${discountAmount > 0 ? `
                            <div class="flex justify-between text-wwc-success">
                                <span>Combo Discount (${events[eventId]?.combo_discount_percentage || 10}%):</span>
                                <span class="font-semibold">-RM${discountAmount.toFixed(2)}</span>
                            </div>
                            ` : isMultiDayEvent && !isCombo ? `
                            <div class="flex justify-between text-wwc-neutral-500">
                                <span>Combo Discount:</span>
                                <span class="text-xs">Check "Apply Combo Discount" to save</span>
                            </div>
                            ` : ''}
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
                            ${discountAmount > 0 ? `
                            <div class="flex justify-between text-wwc-success text-sm">
                                <span>You Save:</span>
                                <span class="font-semibold">RM${discountAmount.toFixed(2)}</span>
                            </div>
                            ` : ''}
                        </div>
                    `;
                }
            } else {
                pricingHTML = `
                    <p>Select ticket types and quantities for both days to see pricing preview.</p>
                    <p class="mt-1">Service fee: 5% of subtotal | Tax: 6% of (subtotal + service fee)</p>
                `;
            }
        } else {
            // Handle single day purchase
            const selectedTicketType = ticketTypeSelect.options[ticketTypeSelect.selectedIndex];
            const quantity = parseInt(quantityInput.value) || 1;
            
            if (selectedTicketType && selectedTicketType.value && quantity > 0) {
                const basePrice = parseFloat(selectedTicketType.getAttribute('data-price')) || 0;
                const availableSeats = parseInt(selectedTicketType.getAttribute('data-available')) || 0;
                
                if (quantity > availableSeats) {
                    pricingHTML = `
                        <div class="text-wwc-error">
                            <p class="font-semibold">Insufficient tickets available!</p>
                            <p class="text-sm">Only ${availableSeats} tickets available for this type.</p>
                        </div>
                    `;
                } else {
                    let subtotal = basePrice * quantity;
                    const serviceFee = Math.round(subtotal * 0.05 * 100) / 100;
                    const taxAmount = Math.round((subtotal + serviceFee) * 0.06 * 100) / 100;
                    const total = subtotal + serviceFee + taxAmount;
                    
                    pricingHTML = `
                <div class="space-y-1">
                    <div class="flex justify-between">
                                <span>Subtotal (${quantity} × RM${basePrice.toFixed(2)}):</span>
                                <span class="font-semibold">RM${(basePrice * quantity).toFixed(2)}</span>
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
                }
        } else {
                pricingHTML = `
                    <p>Select a ticket type and quantity to see pricing preview.</p>
                <p class="mt-1">Service fee: 5% of subtotal | Tax: 6% of (subtotal + service fee)</p>
            `;
        }
        }
        
        pricingPreview.innerHTML = pricingHTML;
    }
    
    // Add event listeners
    eventSelect.addEventListener('change', updateTicketTypes);
    ticketTypeSelect.addEventListener('change', updatePricingPreview);
    quantityInput.addEventListener('input', function() {
        // Auto-check combo if quantity is 2 and event supports it
        const eventId = eventSelect.value;
        const isMultiDayEvent = eventId && events[eventId] && events[eventId].is_multi_day;
        const isComboEligible = isMultiDayEvent && parseInt(quantityInput.value) === 2 && events[eventId].combo_discount_enabled;
        
        if (isComboEligible && comboCheckbox) {
            comboCheckbox.checked = true;
        } else if (parseInt(quantityInput.value) !== 2 && comboCheckbox) {
            comboCheckbox.checked = false;
        }
        
        updatePricingPreview();
    });
    
    // Purchase type radio buttons
    purchaseTypeRadios.forEach(radio => {
        radio.addEventListener('change', updatePurchaseTypeSections);
    });
    
    // Initialize purchase type sections on page load
    updatePurchaseTypeSections();
    
    // Day 1 and Day 2 ticket type selects
    if (day1TicketSelect) {
        day1TicketSelect.addEventListener('change', updatePricingPreview);
    }
    if (day2TicketSelect) {
        day2TicketSelect.addEventListener('change', updatePricingPreview);
    }
    
    // Day 1 and Day 2 quantity inputs
    if (day1QuantityInput) {
        day1QuantityInput.addEventListener('input', updatePricingPreview);
    }
    if (day2QuantityInput) {
        day2QuantityInput.addEventListener('input', updatePricingPreview);
    }

    // Quantity control buttons
    const quantityDecrease = document.getElementById('quantity-decrease');
    const quantityIncrease = document.getElementById('quantity-increase');
    const day1QuantityDecrease = document.getElementById('day1-quantity-decrease');
    const day1QuantityIncrease = document.getElementById('day1-quantity-increase');
    const day2QuantityDecrease = document.getElementById('day2-quantity-decrease');
    const day2QuantityIncrease = document.getElementById('day2-quantity-increase');

    // Single day quantity controls
    if (quantityDecrease) {
        quantityDecrease.addEventListener('click', function() {
            if (quantityInput.value > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
                updatePricingPreview();
            }
        });
    }
    if (quantityIncrease) {
        quantityIncrease.addEventListener('click', function() {
            if (quantityInput.value < 10) {
                quantityInput.value = parseInt(quantityInput.value) + 1;
                updatePricingPreview();
            }
        });
    }

    // Day 1 quantity controls
    if (day1QuantityDecrease) {
        day1QuantityDecrease.addEventListener('click', function() {
            if (day1QuantityInput.value > 1) {
                day1QuantityInput.value = parseInt(day1QuantityInput.value) - 1;
                updatePricingPreview();
            }
        });
    }
    if (day1QuantityIncrease) {
        day1QuantityIncrease.addEventListener('click', function() {
            if (day1QuantityInput.value < 10) {
                day1QuantityInput.value = parseInt(day1QuantityInput.value) + 1;
                updatePricingPreview();
            }
        });
    }

    // Day 2 quantity controls
    if (day2QuantityDecrease) {
        day2QuantityDecrease.addEventListener('click', function() {
            if (day2QuantityInput.value > 1) {
                day2QuantityInput.value = parseInt(day2QuantityInput.value) - 1;
                updatePricingPreview();
            }
        });
    }
    if (day2QuantityIncrease) {
        day2QuantityIncrease.addEventListener('click', function() {
            if (day2QuantityInput.value < 10) {
                day2QuantityInput.value = parseInt(day2QuantityInput.value) + 1;
                updatePricingPreview();
            }
        });
    }
    
    // Combo checkboxes
    if (comboCheckbox) {
        comboCheckbox.addEventListener('change', function() {
            // Auto-check combo if quantity is 2 and event supports it
            const eventId = eventSelect.value;
            const isMultiDayEvent = eventId && events[eventId] && events[eventId].is_multi_day;
            const isComboEligible = isMultiDayEvent && parseInt(quantityInput.value) === 2 && events[eventId].combo_discount_enabled;
            
            if (isComboEligible && !comboCheckbox.checked) {
                comboCheckbox.checked = true;
            }
            
    updatePricingPreview();
        });
    }
    
    // Initial setup
    updateTicketTypes();
    
    // Status selection handling
    const statusSelect = document.getElementById('status');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            const selectedStatus = this.value;
            const paymentMethodField = document.querySelector('select[name="payment_method"]');
            const paymentMethodContainer = paymentMethodField?.closest('div');
            
            // Show/hide payment method based on status
            if (selectedStatus === 'Paid') {
                if (paymentMethodContainer) {
                    paymentMethodContainer.classList.remove('opacity-50');
                    paymentMethodField.disabled = false;
                }
            } else if (selectedStatus === 'Pending') {
                if (paymentMethodContainer) {
                    paymentMethodContainer.classList.remove('opacity-50');
                    paymentMethodField.disabled = false;
                }
            } else if (selectedStatus === 'Cancelled' || selectedStatus === 'Refunded') {
                if (paymentMethodContainer) {
                    paymentMethodContainer.classList.add('opacity-50');
                    paymentMethodField.disabled = true;
                    paymentMethodField.value = '';
                }
            }
        });
    }
    
    // Add form submission debugging
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submission debug:');
            console.log('Event ID:', eventSelect.value);
            console.log('Purchase Type:', document.querySelector('input[name="purchase_type"]:checked')?.value);
            console.log('Ticket Type ID:', ticketTypeSelect.value);
            console.log('Day 1 Ticket Type:', day1TicketSelect?.value);
            console.log('Day 2 Ticket Type:', day2TicketSelect?.value);
            console.log('Quantity:', quantityInput.value);
            console.log('Day 1 Quantity:', day1QuantityInput?.value);
            console.log('Day 2 Quantity:', day2QuantityInput?.value);
            
            // Check if dropdowns have options
            console.log('Day1 dropdown options:', day1TicketSelect ? Array.from(day1TicketSelect.options).map(o => o.value + ':' + o.text) : 'null');
            console.log('Day2 dropdown options:', day2TicketSelect ? Array.from(day2TicketSelect.options).map(o => o.value + ':' + o.text) : 'null');
            
            // Check if required fields are filled
            const selectedType = document.querySelector('input[name="purchase_type"]:checked')?.value;
            console.log('Ticket type select disabled:', ticketTypeSelect.disabled);
            console.log('Ticket type select value:', ticketTypeSelect.value);
            
            if (selectedType === 'single_day') {
                if (ticketTypeSelect.disabled) {
                    e.preventDefault();
                    alert('Please select an event first to enable ticket type selection.');
                    return false;
                }
                if (!ticketTypeSelect.value) {
                    e.preventDefault();
                    alert('Please select a ticket type for single day purchase.');
                    return false;
                }
            }
            if (selectedType === 'multi_day') {
                if (!day1TicketSelect?.value || !day2TicketSelect?.value) {
                    e.preventDefault();
                    alert('Please select ticket types for both Day 1 and Day 2.');
                    return false;
                }
            }
        });
    }
});
</script>
@endsection
