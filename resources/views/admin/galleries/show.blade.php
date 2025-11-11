@extends('layouts.admin')

@section('title', 'Gallery Image Details')
@section('page-subtitle', 'View gallery image details for ' . $event->name)

@section('content')
<div class="min-h-screen bg-wwc-neutral-50">
    <div class="px-6 py-6">
        <div class="mx-auto max-w-4xl">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-wwc-neutral-900">Gallery Image Details</h1>
                    <p class="text-sm text-wwc-neutral-600 mt-1">{{ $event->name }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.events.galleries.index', $event) }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Gallery
                    </a>
                    <a href="{{ route('admin.events.galleries.edit', [$event, $gallery]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-red-600 transition-colors duration-200 shadow-sm">
                        <i class='bx bx-edit text-sm mr-2'></i>
                        Edit Image
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Image Display -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Image Preview</h3>
                        </div>
                        <div class="p-6">
                            <div class="relative aspect-video bg-wwc-neutral-100 rounded-lg overflow-hidden">
                                <img src="{{ $gallery->image_url }}" 
                                     alt="{{ $gallery->title ?? 'Gallery Image' }}" 
                                     class="w-full h-full object-cover">
                                @if(!$gallery->is_active)
                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                    <span class="text-white font-semibold text-lg">Inactive</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Details -->
                <div class="space-y-6">
                    <!-- Image Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Image Information</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- Title -->
                            <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i class='bx bx-image text-sm text-blue-600'></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm font-semibold text-wwc-neutral-600 block mb-1">Title</span>
                                    <span class="text-base font-medium text-wwc-neutral-900">{{ $gallery->title ?? 'Untitled Image' }}</span>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($gallery->description)
                            <div class="flex items-start py-3 border-b border-wwc-neutral-100">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                        <i class='bx bx-file text-sm text-green-600'></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm font-semibold text-wwc-neutral-600 block mb-1">Description</span>
                                    <span class="text-base text-wwc-neutral-900">{{ $gallery->description }}</span>
                                </div>
                            </div>
                            @endif

                            <!-- Display Order -->
                            <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                        <i class='bx bx-sort text-sm text-orange-600'></i>
                                    </div>
                                </div>
                                <div class="flex-1 flex items-center justify-between">
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Display Order</span>
                                    <span class="text-base font-medium text-wwc-neutral-900">{{ $gallery->order }}</span>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="h-8 w-8 rounded-lg {{ $gallery->is_active ? 'bg-green-100' : 'bg-gray-100' }} flex items-center justify-center">
                                        <i class='bx {{ $gallery->is_active ? 'bx-check-circle' : 'bx-x-circle' }} text-sm {{ $gallery->is_active ? 'text-green-600' : 'text-gray-600' }}'></i>
                                    </div>
                                </div>
                                <div class="flex-1 flex items-center justify-between">
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Status</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $gallery->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $gallery->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Event -->
                            <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                        <i class='bx bx-calendar text-sm text-purple-600'></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm font-semibold text-wwc-neutral-600 block mb-1">Event</span>
                                    <span class="text-base font-medium text-wwc-neutral-900">{{ $event->name }}</span>
                                </div>
                            </div>

                            <!-- Created At -->
                            <div class="flex items-center py-3">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <i class='bx bx-time text-sm text-gray-600'></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm font-semibold text-wwc-neutral-600 block mb-1">Created At</span>
                                    <span class="text-base text-wwc-neutral-900">{{ $gallery->created_at->format('M d, Y \a\t g:i A') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Actions</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('admin.events.galleries.edit', [$event, $gallery]) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-red-600 transition-colors duration-200">
                                <i class='bx bx-edit text-sm mr-2'></i>
                                Edit Image
                            </a>
                            
                            <form action="{{ route('admin.events.galleries.toggle-status', [$event, $gallery]) }}" 
                                  method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-wwc-neutral-300 text-sm font-semibold rounded-lg {{ $gallery->is_active ? 'text-orange-700 bg-orange-50 hover:bg-orange-100' : 'text-green-700 bg-green-50 hover:bg-green-100' }} transition-colors">
                                    <i class='bx {{ $gallery->is_active ? 'bx-hide' : 'bx-show' }} text-sm mr-2'></i>
                                    {{ $gallery->is_active ? 'Hide Image' : 'Show Image' }}
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.events.galleries.destroy', [$event, $gallery]) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this image?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 text-sm font-semibold rounded-lg text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                                    <i class='bx bx-trash text-sm mr-2'></i>
                                    Delete Image
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

