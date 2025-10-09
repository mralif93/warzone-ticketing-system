@extends('layouts.admin')

@section('title', 'Edit Event')
@section('page-title', 'Edit Event')

@section('content')
<div class="flex-1">
    <!-- Header -->
    <div class="bg-white shadow-lg border-b border-wwc-neutral-200">
        <div class="px-8 py-6">
            <div class="flex items-center">
                <a href="{{ route('admin.events.show', $event) }}" class="text-wwc-neutral-400 hover:text-wwc-neutral-600 mr-6 transition-colors duration-200">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-wwc-neutral-900 font-display">Edit Event</h1>
                    <p class="mt-2 text-lg text-wwc-neutral-600 font-medium">Update event information</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="px-8 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white shadow-lg rounded-2xl border border-wwc-neutral-200">
                <div class="px-8 py-6 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                    <h2 class="text-xl font-semibold text-wwc-neutral-900 font-display">Event Details</h2>
                    <p class="text-wwc-neutral-600 mt-1">Update the information below to modify your event</p>
                </div>
                
                <form action="{{ route('admin.events.update', $event) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')
                    
                    @if ($errors->any())
                        <div class="bg-wwc-error-light border border-wwc-error text-wwc-error px-6 py-4 rounded-xl mb-8">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h3 class="font-semibold mb-2">Please correct the following errors:</h3>
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-8">
                        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                            <!-- Event Name -->
                            <div class="sm:col-span-2">
                                <label for="name" class="block text-base font-semibold text-wwc-neutral-900 mb-3">
                                    Event Name <span class="text-wwc-error">*</span>
                                </label>
                                <input type="text" name="name" id="name" required
                                       value="{{ old('name', $event->name) }}"
                                       class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-xl shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-base @error('name') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                       placeholder="Enter event name">
                                @error('name')
                                    <div class="text-wwc-error text-sm mt-2 font-medium">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date & Time -->
                            <div>
                                <label for="date_time" class="block text-base font-semibold text-wwc-neutral-900 mb-3">
                                    Date & Time <span class="text-wwc-error">*</span>
                                </label>
                                <input type="datetime-local" name="date_time" id="date_time" required
                                       value="{{ old('date_time', $event->date_time->format('Y-m-d\TH:i')) }}"
                                       class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-xl shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-base @error('date_time') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                @error('date_time')
                                    <div class="text-wwc-error text-sm mt-2 font-medium">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Max Tickets Per Order -->
                            <div>
                                <label for="max_tickets_per_order" class="block text-base font-semibold text-wwc-neutral-900 mb-3">
                                    Max Tickets Per Order <span class="text-wwc-error">*</span>
                                </label>
                                <select name="max_tickets_per_order" id="max_tickets_per_order" required
                                        class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-xl shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-base @error('max_tickets_per_order') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                    <option value="">Select limit</option>
                                    @for($i = 1; $i <= 20; $i++)
                                        <option value="{{ $i }}" {{ old('max_tickets_per_order', $event->max_tickets_per_order) == $i ? 'selected' : '' }}>
                                            {{ $i }} ticket{{ $i > 1 ? 's' : '' }}
                                        </option>
                                    @endfor
                                </select>
                                @error('max_tickets_per_order')
                                    <div class="text-wwc-error text-sm mt-2 font-medium">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Venue -->
                            <div>
                                <label for="venue" class="block text-base font-semibold text-wwc-neutral-900 mb-3">
                                    Venue
                                </label>
                                <input type="text" name="venue" id="venue"
                                       value="{{ old('venue', $event->venue) }}"
                                       class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-xl shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-base @error('venue') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                       placeholder="Enter venue name">
                                @error('venue')
                                    <div class="text-wwc-error text-sm mt-2 font-medium">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="sm:col-span-2">
                                <label for="description" class="block text-base font-semibold text-wwc-neutral-900 mb-3">
                                    Description
                                </label>
                                <textarea name="description" id="description" rows="5"
                                          class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-xl shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-base @error('description') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                          placeholder="Enter event description">{{ old('description', $event->description) }}</textarea>
                                @error('description')
                                    <div class="text-wwc-error text-sm mt-2 font-medium">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4 pt-8 border-t border-wwc-neutral-200 mt-8">
                        <a href="{{ route('admin.events.show', $event) }}" 
                           class="inline-flex items-center px-6 py-3 border border-wwc-neutral-300 rounded-xl shadow-sm text-base font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-8 py-3 border border-transparent rounded-xl shadow-sm text-base font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Event
                        </button>
                    </div>
                </form>
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
