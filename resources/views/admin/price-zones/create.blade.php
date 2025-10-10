@extends('layouts.admin')

@section('title', 'Create Price Zone')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display">Create Price Zone</h1>
                        <p class="text-wwc-neutral-600 mt-1">Add a new pricing zone for tickets</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.price-zones.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-wwc-neutral-100 text-wwc-neutral-700 text-sm font-semibold rounded-lg hover:bg-wwc-neutral-200 transition-colors duration-200">
                            <i class='bx bx-arrow-back text-lg mr-2'></i>
                            Back to Price Zones
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
            <form method="POST" action="{{ route('admin.price-zones.store') }}" class="space-y-6">
                @csrf
                
                <!-- Basic Information -->
                <div class="px-6 py-4 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                    <h2 class="text-lg font-semibold text-wwc-neutral-900 font-display">Basic Information</h2>
                    <p class="text-sm text-wwc-neutral-600 mt-1">Enter the basic details for the price zone</p>
                </div>
                
                <div class="px-6 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                                Price Zone Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
                                   placeholder="e.g., Warzone Exclusive"
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Code -->
                        <div>
                            <label for="code" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                                Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="code" 
                                   id="code" 
                                   value="{{ old('code') }}"
                                   placeholder="e.g., WZ_EXCLUSIVE"
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('code') border-red-500 @enderror"
                                   required>
                            @error('code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-wwc-neutral-500 mt-1">Use uppercase letters, numbers, and underscores only</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="3"
                                  placeholder="Describe this price zone..."
                                  class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pricing & Category -->
                <div class="px-6 py-4 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                    <h2 class="text-lg font-semibold text-wwc-neutral-900 font-display">Pricing & Category</h2>
                    <p class="text-sm text-wwc-neutral-600 mt-1">Set the price and categorize this zone</p>
                </div>
                
                <div class="px-6 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Base Price -->
                        <div>
                            <label for="base_price" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                                Base Price (RM) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-wwc-neutral-500 text-sm">RM</span>
                                </div>
                                <input type="number" 
                                       name="base_price" 
                                       id="base_price" 
                                       value="{{ old('base_price') }}"
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00"
                                       class="block w-full pl-10 pr-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('base_price') border-red-500 @enderror"
                                       required>
                            </div>
                            @error('base_price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category" 
                                    id="category" 
                                    class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('category') border-red-500 @enderror"
                                    required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Visual Settings -->
                <div class="px-6 py-4 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                    <h2 class="text-lg font-semibold text-wwc-neutral-900 font-display">Visual Settings</h2>
                    <p class="text-sm text-wwc-neutral-600 mt-1">Customize how this price zone appears in the UI</p>
                </div>
                
                <div class="px-6 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Color -->
                        <div>
                            <label for="color" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                                Color <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center space-x-3">
                                <input type="color" 
                                       name="color" 
                                       id="color" 
                                       value="{{ old('color', '#DC2626') }}"
                                       class="h-10 w-16 border border-wwc-neutral-300 rounded-lg cursor-pointer @error('color') border-red-500 @enderror">
                                <input type="text" 
                                       value="{{ old('color', '#DC2626') }}"
                                       class="flex-1 px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm"
                                       readonly>
                            </div>
                            @error('color')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Icon -->
                        <div>
                            <label for="icon" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                                Icon Class
                            </label>
                            <input type="text" 
                                   name="icon" 
                                   id="icon" 
                                   value="{{ old('icon') }}"
                                   placeholder="e.g., bx-crown"
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('icon') border-red-500 @enderror">
                            @error('icon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-wwc-neutral-500 mt-1">Boxicons class name (optional)</p>
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                                Sort Order
                            </label>
                            <input type="number" 
                                   name="sort_order" 
                                   id="sort_order" 
                                   value="{{ old('sort_order', 0) }}"
                                   min="0"
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('sort_order') border-red-500 @enderror">
                            @error('sort_order')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-wwc-neutral-500 mt-1">Lower numbers appear first</p>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   id="is_active" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-wwc-primary focus:ring-wwc-primary border-wwc-neutral-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-wwc-neutral-700">
                                Active (price zone is available for use)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 bg-wwc-neutral-50 border-t border-wwc-neutral-200">
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('admin.price-zones.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-wwc-neutral-100 text-wwc-neutral-700 text-sm font-semibold rounded-lg hover:bg-wwc-neutral-200 transition-colors duration-200">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-wwc-primary-dark transition-colors duration-200">
                            <i class='bx bx-save text-lg mr-2'></i>
                            Create Price Zone
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Color picker sync
    const colorInput = document.getElementById('color');
    const colorTextInput = colorInput.nextElementSibling;
    
    colorInput.addEventListener('input', function() {
        colorTextInput.value = this.value;
    });
    
    colorTextInput.addEventListener('input', function() {
        if (this.value.match(/^#[0-9A-F]{6}$/i)) {
            colorInput.value = this.value;
        }
    });
    
    // Auto-generate code from name
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');
    
    nameInput.addEventListener('input', function() {
        if (!codeInput.value) {
            const code = this.value
                .toUpperCase()
                .replace(/[^A-Z0-9\s]/g, '')
                .replace(/\s+/g, '_');
            codeInput.value = code;
        }
    });
});
</script>
@endpush
@endsection
