@extends('layouts.admin')

@section('title', 'Gallery Management')
@section('page-subtitle', 'Manage all event gallery images')

@section('content')
<div class="min-h-screen bg-wwc-neutral-50">
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-wwc-neutral-900">Gallery Management</h1>
                    <p class="text-sm text-wwc-neutral-600 mt-1">Manage gallery images across all events</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.events.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 transition-colors duration-200">
                        <i class='bx bx-calendar text-sm mr-2'></i>
                        View Events
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">
                <!-- Total Galleries -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $totalGalleries }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Images</div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-images text-2xl text-blue-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Active Galleries -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $activeGalleries }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Active Images</div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class='bx bx-check-circle text-2xl text-green-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Events with Galleries -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $eventsWithGalleries }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Events with Images</div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-purple-100 flex items-center justify-center">
                            <i class='bx bx-calendar-event text-2xl text-purple-600'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <h3 class="text-lg font-bold text-wwc-neutral-900">Search & Filter</h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.galleries.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Search -->
                            <div>
                                <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search</label>
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search by title, description, or event name..."
                                       class="w-full px-4 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent">
                            </div>

                            <!-- Filter by Event -->
                            <div>
                                <label for="event_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Filter by Event</label>
                                <select id="event_id" 
                                        name="event_id" 
                                        class="w-full px-4 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent">
                                    <option value="">All Events</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                            {{ $event->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter by Status -->
                            <div>
                                <label for="is_active" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Filter by Status</label>
                                <select id="is_active" 
                                        name="is_active" 
                                        class="w-full px-4 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent">
                                    <option value="">All Status</option>
                                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <label for="limit" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Items per page</label>
                                <select id="limit" 
                                        name="limit" 
                                        onchange="this.form.submit()"
                                        class="px-4 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent">
                                    <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="15" {{ request('limit') == 15 ? 'selected' : '' }}>15</option>
                                    <option value="20" {{ request('limit') == 20 || !request('limit') ? 'selected' : '' }}>20</option>
                                    <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.galleries.index') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 transition-colors">
                                    <i class='bx bx-refresh text-sm mr-2'></i>
                                    Reset
                                </a>
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-red-600 transition-colors shadow-sm">
                                    <i class='bx bx-search text-sm mr-2'></i>
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Gallery Grid -->
            @if($galleries->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
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
                        <div class="mb-2">
                            <span class="text-xs text-wwc-neutral-500 font-medium">Event:</span>
                            <a href="{{ route('admin.events.show', $gallery->event) }}" 
                               class="text-xs font-semibold text-wwc-primary hover:text-red-600 ml-1">
                                {{ $gallery->event->name }}
                            </a>
                        </div>
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
                            <a href="{{ route('admin.events.galleries.edit', [$gallery->event, $gallery]) }}" 
                               class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-wwc-neutral-300 text-xs font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 transition-colors">
                                <i class='bx bx-edit text-xs mr-1'></i>
                                Edit
                            </a>
                            <form action="{{ route('admin.events.galleries.toggle-status', [$gallery->event, $gallery]) }}" 
                                  method="POST" 
                                  class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-3 py-2 border border-wwc-neutral-300 text-xs font-semibold rounded-lg {{ $gallery->is_active ? 'text-orange-700 bg-orange-50 hover:bg-orange-100' : 'text-green-700 bg-green-50 hover:bg-green-100' }} transition-colors">
                                    <i class='bx {{ $gallery->is_active ? 'bx-hide' : 'bx-show' }} text-xs mr-1'></i>
                                    {{ $gallery->is_active ? 'Hide' : 'Show' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.events.galleries.destroy', [$gallery->event, $gallery]) }}" 
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

            <!-- Pagination -->
            <div class="mt-6">
                {{ $galleries->links() }}
            </div>
            @else
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-12 text-center">
                <i class='bx bx-images text-6xl text-wwc-neutral-400 mb-4'></i>
                <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">No Gallery Images</h3>
                <p class="text-sm text-wwc-neutral-600 mb-6">Get started by adding gallery images to your events.</p>
                <a href="{{ route('admin.events.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-red-600 transition-colors duration-200 shadow-sm">
                    <i class='bx bx-calendar text-sm mr-2'></i>
                    Go to Events
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

