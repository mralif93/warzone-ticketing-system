@extends('layouts.admin')

@section('title', 'Edit Price Zone')
@section('page-title', 'Edit Price Zone')

@section('content')
<!-- Professional Price Zone Editing with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <a href="{{ route('admin.price-zones.show', $priceZone) }}" 
                    class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                    Back to Price Zone
                </a>
            </div>

            <!-- Form Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Edit Price Zone Details</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-edit text-sm'></i>
                            <span>Update price zone information</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.price-zones.update', $priceZone) }}" method="POST">
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
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Price Zone Name <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" required
                                           value="{{ old('name', $priceZone->name) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('name') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter price zone name">
                                    @error('name')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Code -->
                                <div>
                                    <label for="code" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Code <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="text" name="code" id="code" required
                                           value="{{ old('code', $priceZone->code) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('code') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="e.g., VIP, PREMIUM, STANDARD">
                                    @error('code')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Base Price -->
                                <div>
                                    <label for="base_price" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Base Price (RM) <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="number" name="base_price" id="base_price" required step="0.01" min="0"
                                           value="{{ old('base_price', $priceZone->base_price) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('base_price') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter base price">
                                    @error('base_price')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div>
                                    <label for="category" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Category <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="category" id="category" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('category') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category }}" {{ old('category', $priceZone->category) == $category ? 'selected' : '' }}>{{ $category }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Color -->
                                <div>
                                    <label for="color" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Color <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="color" id="color" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('color') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select Color</option>
                                        <option value="text-blue-600" {{ old('color', $priceZone->color) == 'text-blue-600' ? 'selected' : '' }}>Blue</option>
                                        <option value="text-green-600" {{ old('color', $priceZone->color) == 'text-green-600' ? 'selected' : '' }}>Green</option>
                                        <option value="text-yellow-600" {{ old('color', $priceZone->color) == 'text-yellow-600' ? 'selected' : '' }}>Yellow</option>
                                        <option value="text-red-600" {{ old('color', $priceZone->color) == 'text-red-600' ? 'selected' : '' }}>Red</option>
                                        <option value="text-purple-600" {{ old('color', $priceZone->color) == 'text-purple-600' ? 'selected' : '' }}>Purple</option>
                                        <option value="text-indigo-600" {{ old('color', $priceZone->color) == 'text-indigo-600' ? 'selected' : '' }}>Indigo</option>
                                        <option value="text-pink-600" {{ old('color', $priceZone->color) == 'text-pink-600' ? 'selected' : '' }}>Pink</option>
                                        <option value="text-orange-600" {{ old('color', $priceZone->color) == 'text-orange-600' ? 'selected' : '' }}>Orange</option>
                                    </select>
                                    @error('color')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Icon -->
                                <div>
                                    <label for="icon" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Icon
                                    </label>
                                    <input type="text" name="icon" id="icon"
                                           value="{{ old('icon', $priceZone->icon) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('icon') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="e.g., bx bx-crown, bx bx-star">
                                    @error('icon')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Sort Order -->
                                <div>
                                    <label for="sort_order" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Sort Order
                                    </label>
                                    <input type="number" name="sort_order" id="sort_order" min="0"
                                           value="{{ old('sort_order', $priceZone->sort_order) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('sort_order') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter sort order">
                                    @error('sort_order')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="sm:col-span-2">
                                    <label for="description" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Description
                                    </label>
                                    <textarea name="description" id="description" rows="3"
                                              class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('description') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                              placeholder="Enter price zone description">{{ old('description', $priceZone->description) }}</textarea>
                                    @error('description')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Active Status -->
                                <div class="sm:col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_active" value="1" 
                                               {{ old('is_active', $priceZone->is_active) ? 'checked' : '' }}
                                               class="rounded border-wwc-neutral-300 text-wwc-primary shadow-sm focus:border-wwc-primary focus:ring focus:ring-wwc-primary focus:ring-opacity-50">
                                        <span class="ml-2 text-sm font-semibold text-wwc-neutral-900">Active Price Zone</span>
                                    </label>
                                    <p class="text-xs text-wwc-neutral-500 mt-1">Check this box to make the price zone active and available for use</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-wwc-neutral-200 mt-6">
                            <a href="{{ route('admin.price-zones.show', $priceZone) }}" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-check text-sm mr-2'></i>
                                Update Price Zone
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection