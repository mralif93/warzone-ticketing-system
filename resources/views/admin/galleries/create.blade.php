@extends('layouts.admin')

@section('title', 'Add Gallery Image')
@section('page-subtitle', 'Add new image to ' . $event->name . ' gallery')

@section('content')
<div class="min-h-screen bg-wwc-neutral-50">
    <div class="px-6 py-6">
        <div class="mx-auto max-w-3xl">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-wwc-neutral-900">Add Gallery Image</h1>
                    <p class="text-sm text-wwc-neutral-600 mt-1">{{ $event->name }}</p>
                </div>
                <a href="{{ route('admin.events.galleries.index', $event) }}" 
                   class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 transition-colors duration-200">
                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                    Back to Gallery
                </a>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <form action="{{ route('admin.events.galleries.store', $event) }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf

                    <div class="p-6 space-y-6">
                        <!-- Image Upload -->
                        <div>
                            <label for="image" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                Image <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-wwc-neutral-300 border-dashed rounded-lg hover:border-wwc-primary transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class='bx bx-cloud-upload text-4xl text-wwc-neutral-400 mb-2'></i>
                                    <div class="flex text-sm text-wwc-neutral-600">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-wwc-primary hover:text-red-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-wwc-primary">
                                            <span>Upload an image</span>
                                            <input id="image" name="image" type="file" accept="image/*" class="sr-only" required onchange="previewImage(this)">
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

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                Title
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   class="w-full px-4 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent"
                                   placeholder="Enter image title (optional)">
                            @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                Description
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4" 
                                      class="w-full px-4 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent"
                                      placeholder="Enter image description (optional)">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Order -->
                        <div>
                            <label for="order" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                Display Order
                            </label>
                            <input type="number" 
                                   id="order" 
                                   name="order" 
                                   value="{{ old('order', 0) }}" 
                                   min="0" 
                                   class="w-full px-4 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent"
                                   placeholder="0">
                            <p class="mt-1 text-xs text-wwc-neutral-500">Lower numbers appear first in the gallery</p>
                            @error('order')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-wwc-primary focus:ring-wwc-primary border-wwc-neutral-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm font-semibold text-wwc-neutral-900">
                                Active (Show in gallery)
                            </label>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-6 py-4 bg-wwc-neutral-50 border-t border-wwc-neutral-200 flex justify-end space-x-3">
                        <a href="{{ route('admin.events.galleries.index', $event) }}" 
                           class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 transition-colors duration-200">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-red-600 transition-colors duration-200 shadow-sm">
                            <i class='bx bx-save text-sm mr-2'></i>
                            Add Image
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection

