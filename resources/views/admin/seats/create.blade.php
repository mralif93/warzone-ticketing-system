@extends('layouts.admin')

@section('title', 'Create Seat')
@section('page-title', 'Create Seat')

@section('content')
<!-- Professional Seat Creation with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <a href="{{ route('admin.seats.index') }}" 
                    class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                    Back to Seats
                </a>
            </div>

            <!-- Form Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Seat Details</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-edit text-sm'></i>
                            <span>Fill in the information below</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.seats.store') }}" method="POST">
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
                                <!-- Section -->
                                <div>
                                    <label for="section" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Section <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="text" name="section" id="section" required
                                           value="{{ old('section') }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('section') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter section (e.g., A, B, VIP)">
                                    @error('section')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Row -->
                                <div>
                                    <label for="row" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Row <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="text" name="row" id="row" required
                                           value="{{ old('row') }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('row') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter row (e.g., 1, 2, A)">
                                    @error('row')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Number -->
                                <div>
                                    <label for="number" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Seat Number <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="text" name="number" id="number" required
                                           value="{{ old('number') }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('number') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter seat number (e.g., 1, 2, 15)">
                                    @error('number')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Price Zone -->
                                <div>
                                    <label for="price_zone" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Price Zone <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="price_zone" id="price_zone" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('price_zone') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select Price Zone</option>
                                        <option value="VIP" {{ old('price_zone') == 'VIP' ? 'selected' : '' }}>VIP</option>
                                        <option value="Premium" {{ old('price_zone') == 'Premium' ? 'selected' : '' }}>Premium</option>
                                        <option value="Standard" {{ old('price_zone') == 'Standard' ? 'selected' : '' }}>Standard</option>
                                        <option value="Economy" {{ old('price_zone') == 'Economy' ? 'selected' : '' }}>Economy</option>
                                    </select>
                                    @error('price_zone')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Base Price -->
                                <div>
                                    <label for="base_price" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Base Price (RM) <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="number" name="base_price" id="base_price" required step="0.01" min="0"
                                           value="{{ old('base_price') }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('base_price') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter base price">
                                    @error('base_price')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Seat Type -->
                                <div>
                                    <label for="seat_type" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Seat Type <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="seat_type" id="seat_type" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('seat_type') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select Seat Type</option>
                                        <option value="Standard" {{ old('seat_type') == 'Standard' ? 'selected' : '' }}>Standard</option>
                                        <option value="Premium" {{ old('seat_type') == 'Premium' ? 'selected' : '' }}>Premium</option>
                                        <option value="VIP" {{ old('seat_type') == 'VIP' ? 'selected' : '' }}>VIP</option>
                                    </select>
                                    @error('seat_type')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Accessibility -->
                                <div class="sm:col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_accessible" value="1" 
                                               {{ old('is_accessible') ? 'checked' : '' }}
                                               class="rounded border-wwc-neutral-300 text-wwc-primary shadow-sm focus:border-wwc-primary focus:ring focus:ring-wwc-primary focus:ring-opacity-50">
                                        <span class="ml-2 text-sm font-semibold text-wwc-neutral-900">Accessible Seat</span>
                                    </label>
                                    <p class="text-xs text-wwc-neutral-500 mt-1">Check this box if the seat is accessible for wheelchair users</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-wwc-neutral-200 mt-6">
                            <a href="{{ route('admin.seats.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-plus text-sm mr-2'></i>
                                Create Seat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection