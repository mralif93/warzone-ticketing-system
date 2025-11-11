@extends('layouts.admin')

@section('title', 'Event Gallery')
@section('page-subtitle', 'Manage gallery images for ' . $event->name)

@section('content')
<div class="min-h-screen bg-wwc-neutral-50">
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-wwc-neutral-900">Event Gallery</h1>
                    <p class="text-sm text-wwc-neutral-600 mt-1">{{ $event->name }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.events.show', $event) }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Event
                    </a>
                    <a href="{{ route('admin.events.galleries.trashed', $event) }}" 
                       class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-semibold rounded-lg text-red-700 bg-red-50 hover:bg-red-100 transition-colors duration-200">
                        <i class='bx bx-trash text-sm mr-2'></i>
                        View Trashed
                    </a>
                    <a href="{{ route('admin.events.galleries.create', $event) }}" 
                       class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-red-600 transition-colors duration-200 shadow-sm">
                        <i class='bx bx-plus text-sm mr-2'></i>
                        Add Image
                    </a>
                </div>
            </div>

            <!-- Gallery Grid -->
            @if($galleries->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($galleries as $gallery)
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <!-- Image -->
                    <div class="relative aspect-video bg-wwc-neutral-100">
                        <img src="{{ $gallery->image_url }}" 
                             alt="{{ $gallery->title ?? 'Gallery Image' }}" 
                             class="w-full h-full object-cover">
                        @if(!$gallery->is_active)
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">Inactive</span>
                        </div>
                        @endif
                        <div class="absolute top-2 right-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $gallery->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $gallery->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-wwc-neutral-900 mb-1 truncate">
                            {{ $gallery->title ?? 'Untitled Image' }}
                        </h3>
                        @if($gallery->description)
                        <p class="text-xs text-wwc-neutral-600 mb-2 line-clamp-2">
                            {{ Str::limit($gallery->description, 60) }}
                        </p>
                        @endif
                        <div class="flex items-center justify-between text-xs text-wwc-neutral-500 mb-3">
                            <span>Order: {{ $gallery->order }}</span>
                            <span>{{ $gallery->created_at->format('M d, Y') }}</span>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.events.galleries.edit', [$event, $gallery]) }}" 
                               class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-wwc-neutral-300 text-xs font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 transition-colors">
                                <i class='bx bx-edit text-xs mr-1'></i>
                                Edit
                            </a>
                            <form action="{{ route('admin.events.galleries.toggle-status', [$event, $gallery]) }}" 
                                  method="POST" 
                                  class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-3 py-2 border border-wwc-neutral-300 text-xs font-semibold rounded-lg {{ $gallery->is_active ? 'text-orange-700 bg-orange-50 hover:bg-orange-100' : 'text-green-700 bg-green-50 hover:bg-green-100' }} transition-colors">
                                    <i class='bx {{ $gallery->is_active ? 'bx-hide' : 'bx-show' }} text-xs mr-1'></i>
                                    {{ $gallery->is_active ? 'Hide' : 'Show' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.events.galleries.destroy', [$event, $gallery]) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this image?');"
                                  class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-3 py-2 border border-red-300 text-xs font-semibold rounded-lg text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                                    <i class='bx bx-trash text-xs mr-1'></i>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-12 text-center">
                <i class='bx bx-image text-6xl text-wwc-neutral-400 mb-4'></i>
                <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">No Gallery Images</h3>
                <p class="text-sm text-wwc-neutral-600 mb-6">Get started by adding your first gallery image for this event.</p>
                <a href="{{ route('admin.events.galleries.create', $event) }}" 
                   class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-red-600 transition-colors duration-200 shadow-sm">
                    <i class='bx bx-plus text-sm mr-2'></i>
                    Add First Image
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

