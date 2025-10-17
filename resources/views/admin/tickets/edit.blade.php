@extends('layouts.admin')

@section('title', 'Edit Ticket Type')
@section('page-title', 'Edit Ticket Type')

@section('content')
<!-- Professional Ticket Type Editing with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <a href="{{ route('admin.tickets.show', $ticket) }}" 
                    class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                    Back to Ticket Type
                </a>
            </div>

            <!-- Form Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Edit Ticket Type Details</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-edit text-sm'></i>
                            <span>Update ticket type information</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST">
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
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-1">
                                <!-- Event -->
                                <div>
                                    <label for="event_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Event <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="event_id" id="event_id" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('event_id') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select an event</option>
                                        @foreach($events as $event)
                                            <option value="{{ $event->id }}" {{ old('event_id', $ticket->event_id) == $event->id ? 'selected' : '' }}>
                                                {{ $event->name }} - {{ $event->date_time->format('M j, Y') }} ({{ $event->status }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('event_id')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Ticket Type Name -->
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Ticket Type Name <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" required
                                           value="{{ old('name', $ticket->name) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('name') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter ticket type name (e.g., VIP, General, Student)">
                                    <p class="text-xs text-wwc-neutral-500 mt-1">Enter the name for this ticket type/category</p>
                                    @error('name')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Price -->
                                <div>
                                    <label for="price" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Price <span class="text-wwc-error">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-wwc-neutral-500 text-sm">RM</span>
                                        </div>
                                        <input type="number" name="price" id="price" required step="0.01" min="0"
                                               value="{{ old('price', $ticket->price) }}"
                                               class="block w-full pl-10 pr-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('price') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                               placeholder="0.00">
                                    </div>
                                    <p class="text-xs text-wwc-neutral-500 mt-1">Enter the price per seat for this ticket type</p>
                                    @error('price')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Total Seats -->
                                <div>
                                    <label for="total_seats" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Total Seats <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="number" name="total_seats" id="total_seats" required min="1" max="10000"
                                           value="{{ old('total_seats', $ticket->total_seats) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('total_seats') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter total number of seats">
                                    <p class="text-xs text-wwc-neutral-500 mt-1">Enter the total number of seats available for this ticket type</p>
                                    @error('total_seats')
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
                                        <option value="">Select status</option>
                                        <option value="active" {{ old('status', $ticket->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $ticket->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="sold_out" {{ old('status', $ticket->status) == 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                                    </select>
                                    <p class="text-xs text-wwc-neutral-500 mt-1">Set the status for this ticket type</p>
                                    @error('status')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Description
                                    </label>
                                    <textarea name="description" id="description" rows="4"
                                              class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('description') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                              placeholder="Enter ticket type description (e.g., Premium seating with exclusive access)">{{ old('description', $ticket->description) }}</textarea>
                                    <p class="text-xs text-wwc-neutral-500 mt-1">Optional description for this ticket type</p>
                                    @error('description')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Combo Ticket Settings -->
                            <div class="border-t border-wwc-neutral-200 pt-6">
                                <div class="flex items-center mb-4">
                                    <h4 class="text-lg font-semibold text-wwc-neutral-900">Combo Ticket Settings</h4>
                                    <span class="ml-2 text-xs text-wwc-neutral-500">(Optional)</span>
                                </div>
                                
                                <div class="grid grid-cols-1 gap-6">
                                    <!-- Combo Ticket Checkbox -->
                                    <div>
                                        <label class="flex items-center">
                                        <input type="checkbox" name="is_combo" id="is_combo" value="1"
                                               {{ old('is_combo', $ticket->is_combo) ? 'checked' : '' }}
                                               class="h-4 w-4 text-wwc-primary focus:ring-wwc-primary border-wwc-neutral-300 rounded @error('is_combo') border-wwc-error @enderror">
                                            <span class="ml-2 text-sm font-semibold text-wwc-neutral-900">This is a Combo Ticket Type</span>
                                        </label>
                                        <p class="text-xs text-wwc-neutral-500 mt-1">Check if this ticket type is for multi-day combo purchases</p>
                                        @error('is_combo')
                                            <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Current Statistics (Read-only) -->
                            <div class="grid grid-cols-3 gap-4 p-4 bg-wwc-neutral-50 rounded-lg">
                                <div class="text-center">
                                    <div class="text-lg font-bold text-wwc-neutral-900">{{ $ticket->sold_seats }}</div>
                                    <div class="text-xs text-wwc-neutral-500">Sold</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold text-green-600">{{ $ticket->available_seats }}</div>
                                    <div class="text-xs text-wwc-neutral-500">Available</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold text-blue-600">{{ $ticket->scanned_seats }}</div>
                                    <div class="text-xs text-wwc-neutral-500">Scanned</div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-wwc-neutral-200 mt-6">
                            <a href="{{ route('admin.tickets.show', $ticket) }}" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-check text-sm mr-2'></i>
                                Update Ticket Type
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
    const nameInput = document.getElementById('name');
    const priceInput = document.getElementById('price');
    const totalSeatsInput = document.getElementById('total_seats');
    const isComboCheckbox = document.getElementById('is_combo');
    
    // Handle combo checkbox change
    isComboCheckbox.addEventListener('change', function() {
        const isCombo = this.checked;
        
        // Update name placeholder based on combo status
        if (isCombo) {
            nameInput.placeholder = 'Enter combo ticket type name (e.g., 2-Day Combo Pass, Weekend Combo)';
        } else {
            nameInput.placeholder = 'Enter ticket type name (e.g., VIP, General, Student)';
        }
        
        // Update description placeholder
        const descriptionTextarea = document.getElementById('description');
        if (isCombo) {
            descriptionTextarea.placeholder = 'Enter combo ticket description (e.g., Access to both days of the event with exclusive benefits)';
        } else {
            descriptionTextarea.placeholder = 'Enter ticket type description (e.g., Premium seating with exclusive access)';
        }
    });

    // Auto-suggest combo names when typing
    nameInput.addEventListener('input', function() {
        if (isComboCheckbox.checked) {
            const value = this.value.toLowerCase();
            if (value.includes('combo') || value.includes('pass') || value.includes('day')) {
                // Already contains combo-related terms
                return;
            }
            
            // Suggest combo-related names
            if (value.includes('vip')) {
                this.value = 'VIP Combo Pass';
            } else if (value.includes('general')) {
                this.value = 'General Combo Pass';
            } else if (value.includes('student')) {
                this.value = 'Student Combo Pass';
            }
        }
    });

    // Focus management
    nameInput.addEventListener('blur', function() {
        if (this.value.trim() && !priceInput.value) {
            priceInput.focus();
        }
    });
    
    priceInput.addEventListener('blur', function() {
        if (this.value && !totalSeatsInput.value) {
            totalSeatsInput.focus();
        }
    });
});
</script>
@endsection