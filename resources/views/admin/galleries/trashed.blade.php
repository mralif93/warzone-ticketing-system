@extends('layouts.admin')

@section('title', 'Trashed Gallery Images')
@section('page-subtitle', 'Manage deleted gallery images for ' . $event->name)

@section('content')
<div class="min-h-screen bg-wwc-neutral-50">
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-wwc-neutral-900">Trashed Gallery Images</h1>
                    <p class="text-sm text-wwc-neutral-600 mt-1">{{ $event->name }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.events.galleries.index', $event) }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Gallery
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">
                <!-- Total Trashed -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $totalTrashed }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Trashed</div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-red-100 flex items-center justify-center">
                            <i class='bx bx-trash text-2xl text-red-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Recently Deleted -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $recentlyDeleted }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Recently Deleted</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-warning font-semibold">
                                    <i class='bx bx-time text-xs mr-1'></i>
                                    Last 7 days
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                            <i class='bx bx-time text-2xl text-yellow-600'></i>
                        </div>
                    </div>
                </div>

                <!-- On This Page -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $galleries->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">On This Page</div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-list-ul text-2xl text-blue-600'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gallery Grid -->
            @if($galleries->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($galleries as $gallery)
                <div class="bg-white rounded-2xl shadow-sm border border-red-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <!-- Image -->
                    <div class="relative aspect-video bg-wwc-neutral-100">
                        <img src="{{ $gallery->image_url }}" 
                             alt="{{ $gallery->title ?? 'Gallery Image' }}" 
                             class="w-full h-full object-cover opacity-75">
                        <div class="absolute inset-0 bg-red-500 bg-opacity-20 flex items-center justify-center">
                            <span class="text-red-700 font-semibold text-sm bg-white px-3 py-1 rounded-full">Deleted</span>
                        </div>
                        <div class="absolute top-2 right-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                Trashed
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
                            <span>Deleted: {{ $gallery->deleted_at->format('M d, Y') }}</span>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2">
                            <form action="{{ route('admin.events.galleries.restore', [$event, $gallery]) }}" 
                                  method="POST" 
                                  class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-3 py-2 border border-green-300 text-xs font-semibold rounded-lg text-green-700 bg-green-50 hover:bg-green-100 transition-colors">
                                    <i class='bx bx-undo text-xs mr-1'></i>
                                    Restore
                                </button>
                            </form>
                            <form action="{{ route('admin.events.galleries.force-delete', [$event, $gallery]) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to permanently delete this image? This action cannot be undone!');"
                                  class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-3 py-2 border border-red-300 text-xs font-semibold rounded-lg text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                                    <i class='bx bx-trash text-xs mr-1'></i>
                                    Delete Forever
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-12 text-center">
                <i class='bx bx-trash text-6xl text-wwc-neutral-400 mb-4'></i>
                <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">No Trashed Images</h3>
                <p class="text-sm text-wwc-neutral-600 mb-6">There are no deleted gallery images for this event.</p>
                <a href="{{ route('admin.events.galleries.index', $event) }}" 
                   class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-red-600 transition-colors duration-200 shadow-sm">
                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                    Back to Gallery
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

