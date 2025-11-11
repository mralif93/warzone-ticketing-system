@extends('layouts.admin')

@section('title', 'Create Event')
@section('page-title', 'Create Event')

@section('content')
<!-- Professional Event Creation with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <a href="{{ route('admin.events.index') }}" 
                    class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                    Back to Events
                </a>
            </div>

            <!-- Form Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Event Details</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-edit text-sm'></i>
                            <span>Fill in the information below</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
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
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- Event Name -->
                                <div class="sm:col-span-2">
                                    <label for="name" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Event Name <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" required
                                           value="{{ old('name') }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('name') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter event name">
                                    @error('name')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Event Date & Time -->
                                <div>
                                    <label for="date_time" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Event Date & Time <span class="text-wwc-error">*</span>
                                        <span class="text-xs text-wwc-neutral-500 font-normal">(Main event date)</span>
                                    </label>
                                    <input type="datetime-local" name="date_time" id="date_time" required
                                           value="{{ old('date_time') }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('date_time') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                    @error('date_time')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Start Date & Time -->
                                <div>
                                    <label for="start_date" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Start Date & Time
                                        <span class="text-xs text-wwc-neutral-500 font-normal">(Optional - for multi-day events)</span>
                                    </label>
                                    <input type="datetime-local" name="start_date" id="start_date"
                                           value="{{ old('start_date') }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('start_date') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                    @error('start_date')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- End Date & Time -->
                                <div>
                                    <label for="end_date" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        End Date & Time
                                        <span class="text-xs text-wwc-neutral-500 font-normal">(Optional - for multi-day events, max 2 days)</span>
                                    </label>
                                    <input type="datetime-local" name="end_date" id="end_date"
                                           value="{{ old('end_date') }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('end_date') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                    @error('end_date')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Max Tickets Per Order -->
                                <div>
                                    <label for="max_tickets_per_order" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Max Tickets Per Order <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="max_tickets_per_order" id="max_tickets_per_order" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('max_tickets_per_order') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select limit</option>
                                        @for($i = 1; $i <= 20; $i++)
                                            <option value="{{ $i }}" {{ old('max_tickets_per_order') == $i ? 'selected' : '' }}>
                                                {{ $i }} ticket{{ $i > 1 ? 's' : '' }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('max_tickets_per_order')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Total Seats -->
                                <div>
                                    <label for="total_seats" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Total Seats <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="number" name="total_seats" id="total_seats" required min="1"
                                           value="{{ old('total_seats', 7000) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('total_seats') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter total number of seats">
                                    @error('total_seats')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Event Status -->
                                <div>
                                    <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Event Status <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="status" id="status" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('status') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select status</option>
                                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="on_sale" {{ old('status') == 'on_sale' ? 'selected' : '' }}>On Sale</option>
                                        <option value="sold_out" {{ old('status') == 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Venue -->
                                <div>
                                    <label for="venue" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Venue
                                    </label>
                                    <input type="text" name="venue" id="venue"
                                           value="{{ old('venue', 'Warzone Arena') }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('venue') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter venue name">
                                    @error('venue')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Event Image -->
                                <div class="sm:col-span-2">
                                    <label for="image" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Event Image
                                        <span class="text-xs text-wwc-neutral-500 font-normal">(Optional)</span>
                                    </label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-wwc-neutral-300 border-dashed rounded-lg hover:border-wwc-primary transition-colors">
                                        <div class="space-y-1 text-center">
                                            <i class='bx bx-cloud-upload text-4xl text-wwc-neutral-400 mb-2'></i>
                                            <div class="flex text-sm text-wwc-neutral-600">
                                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-wwc-primary hover:text-red-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-wwc-primary">
                                                    <span>Upload an image</span>
                                                    <input id="image" name="image" type="file" accept="image/*" class="sr-only" onchange="previewEventImage(this)">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-wwc-neutral-500">PNG, JPG, GIF, WEBP up to 10MB</p>
                                        </div>
                                    </div>
                                    <div id="imagePreview" class="mt-4 hidden">
                                        <img id="previewImg" src="" alt="Preview" class="max-w-full h-64 object-cover rounded-lg border border-wwc-neutral-200">
                                    </div>
                                    @error('image')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="sm:col-span-2">
                                    <label for="description" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Description
                                    </label>
                                    <textarea name="description" id="description" rows="4"
                                              class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('description') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                              placeholder="Enter event description">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Combo Discount Section -->
                            <div class="border-t border-wwc-neutral-200 pt-6">
                                <div class="flex items-center mb-4">
                                    <h4 class="text-lg font-semibold text-wwc-neutral-900">Combo Discount Settings</h4>
                                    <span class="ml-2 text-xs text-wwc-neutral-500">(For multi-day events)</span>
                                </div>
                                
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <!-- Combo Discount Enabled -->
                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="combo_discount_enabled" id="combo_discount_enabled" value="1"
                                                   {{ old('combo_discount_enabled', true) ? 'checked' : '' }}
                                                   class="h-4 w-4 text-wwc-primary focus:ring-wwc-primary border-wwc-neutral-300 rounded @error('combo_discount_enabled') border-wwc-error @enderror">
                                            <span class="ml-2 text-sm font-semibold text-wwc-neutral-900">Enable Combo Discount</span>
                                        </label>
                                        <p class="text-xs text-wwc-neutral-500 mt-1">Allow customers to purchase tickets for multiple days with a discount</p>
                                        @error('combo_discount_enabled')
                                            <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Combo Discount Percentage -->
                                    <div>
                                        <label for="combo_discount_percentage" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                            Combo Discount Percentage
                                        </label>
                                        <div class="relative">
                                            <input type="number" name="combo_discount_percentage" id="combo_discount_percentage" 
                                                   min="0" max="100" step="0.01"
                                                   value="{{ old('combo_discount_percentage', 10.00) }}"
                                                   class="block w-full px-3 py-2 pr-8 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('combo_discount_percentage') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                                   placeholder="10.00">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="text-wwc-neutral-500 text-sm">%</span>
                                            </div>
                                        </div>
                                        <p class="text-xs text-wwc-neutral-500 mt-1">Discount percentage for 2-day combo purchases (0-100%)</p>
                                        @error('combo_discount_percentage')
                                            <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Default Event Section -->
                        <div class="bg-wwc-neutral-50 rounded-lg p-4 border border-wwc-neutral-200 mt-6">
                            <h3 class="text-sm font-semibold text-wwc-neutral-900 mb-3 flex items-center">
                                <i class='bx bx-star text-wwc-primary mr-2'></i>
                                Default Event Settings
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="default" id="default" value="1" 
                                               {{ old('default') ? 'checked' : '' }}
                                               class="h-4 w-4 text-wwc-primary focus:ring-wwc-primary border-wwc-neutral-300 rounded">
                                        <span class="ml-2 text-sm text-wwc-neutral-700">
                                            Set as default event
                                        </span>
                                    </label>
                                    <p class="text-xs text-wwc-neutral-500 mt-1">
                                        Only one event can be set as default. Setting this will unset any existing default event.
                                    </p>
                                    @error('default')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-wwc-neutral-200 mt-6">
                            <a href="{{ route('admin.events.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-plus text-sm mr-2'></i>
                                Create Event
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
    const dateInput = document.getElementById('date_time');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const comboDiscountEnabled = document.getElementById('combo_discount_enabled');
    const comboDiscountPercentage = document.getElementById('combo_discount_percentage');
    
    // Set minimum date to today
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    
    const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
    dateInput.min = minDateTime;
    startDateInput.min = minDateTime;
    endDateInput.min = minDateTime;
    
    // Handle combo discount checkbox
    function toggleComboDiscount() {
        const isEnabled = comboDiscountEnabled.checked;
        comboDiscountPercentage.disabled = !isEnabled;
        comboDiscountPercentage.style.opacity = isEnabled ? '1' : '0.5';
    }
    
    comboDiscountEnabled.addEventListener('change', toggleComboDiscount);
    toggleComboDiscount(); // Initial state
    
    // Image preview function
    function previewEventImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Validate multi-day events (max 2 days)
    function validateMultiDayEvent() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays > 2) {
                endDateInput.setCustomValidity('Multi-day events can only span a maximum of 2 days');
                endDateInput.reportValidity();
            } else {
                endDateInput.setCustomValidity('');
            }
        }
    }
    
    startDateInput.addEventListener('change', validateMultiDayEvent);
    endDateInput.addEventListener('change', validateMultiDayEvent);
    
    // Auto-set start_date when date_time changes
    dateInput.addEventListener('change', function() {
        if (!startDateInput.value) {
            startDateInput.value = dateInput.value;
        }
    });
});
</script>
@endsection
