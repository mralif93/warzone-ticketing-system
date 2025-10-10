@extends('layouts.admin')

@section('title', 'Edit Event')
@section('page-title', 'Edit Event')

@section('content')
<!-- Professional Event Editing with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-2xl bg-wwc-primary-light flex items-center justify-center mr-4">
                        <i class='bx bx-edit text-2xl text-wwc-primary'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display">Edit Event</h1>
                        <p class="mt-1 text-sm text-wwc-neutral-600 font-medium">Update event information</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('admin.events.show', $event) }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Event
                    </a>
                </div>
            </div>


            <!-- Form Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Edit Event Details</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-edit text-sm'></i>
                            <span>Update event information</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.events.update', $event) }}" method="POST">
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
                                <!-- Event Name -->
                                <div class="sm:col-span-2">
                                    <label for="name" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Event Name <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" required
                                           value="{{ old('name', $event->name) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('name') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter event name">
                                    @error('name')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Date & Time -->
                                <div>
                                    <label for="date_time" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Date & Time <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="datetime-local" name="date_time" id="date_time" required
                                           value="{{ old('date_time', $event->date_time->format('Y-m-d\TH:i')) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('date_time') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                    @error('date_time')
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
                                            <option value="{{ $i }}" {{ old('max_tickets_per_order', $event->max_tickets_per_order) == $i ? 'selected' : '' }}>
                                                {{ $i }} ticket{{ $i > 1 ? 's' : '' }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('max_tickets_per_order')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Venue -->
                                <div>
                                    <label for="venue" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Venue
                                    </label>
                                    <input type="text" name="venue" id="venue"
                                           value="{{ old('venue', $event->venue) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('venue') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter venue name">
                                    @error('venue')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="sm:col-span-2">
                                    <label for="description" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Description
                                    </label>
                                    <textarea name="description" id="description" rows="4"
                                              class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('description') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                              placeholder="Enter event description">{{ old('description', $event->description) }}</textarea>
                                    @error('description')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-wwc-neutral-200 mt-6">
                            <a href="{{ route('admin.events.show', $event) }}" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-check text-sm mr-2'></i>
                                Update Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Set minimum date to today
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date_time');
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    
    const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
    dateInput.min = minDateTime;
});
</script>
@endsection
