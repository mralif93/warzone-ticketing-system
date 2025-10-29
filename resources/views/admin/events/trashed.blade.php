@extends('layouts.admin')

@section('title', 'Trashed Events')
@section('page-subtitle', 'Manage deleted events')

@section('content')
<!-- Professional Trashed Events Management with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">
    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-wwc-neutral-900">Trashed Events</h1>
                        <p class="text-wwc-neutral-600 mt-1">Manage deleted events and restore or permanently delete them</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.events.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <i class='bx bx-arrow-back text-sm mr-2'></i>
                            Back to Events
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-6">
                <!-- Total Trashed -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $totalTrashed }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Trashed</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-error font-semibold">
                                    <i class='bx bx-trash text-xs mr-1'></i>
                                    Deleted Events
                                </div>
                            </div>
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

                <!-- Actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $events->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">On This Page</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-info font-semibold">
                                    <i class='bx bx-list-ul text-xs mr-1'></i>
                                    Displayed
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-list-ul text-2xl text-blue-600'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Search & Filter Trashed Events</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-search text-sm'></i>
                            <span>Find specific events</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search Events</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Search by name, venue, or description..."
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Status</label>
                            <select name="status" id="status" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Statuses</option>
                                <option value="on_sale" {{ request('status') == 'on_sale' ? 'selected' : '' }}>On Sale</option>
                                <option value="sold_out" {{ request('status') == 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label for="date_from" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">From Date</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div>
                            <label for="date_to" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">To Date</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div class="sm:col-span-2 lg:col-span-4 flex justify-end gap-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-search text-sm mr-2'></i>
                                Search Events
                            </button>
                            <a href="{{ route('admin.events.trashed') }}" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Events Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Trashed Events</h3>
                        <div class="flex items-center space-x-3">
                            <div class="text-sm text-wwc-neutral-500">
                                Showing {{ $events->firstItem() ?? 0 }} to {{ $events->lastItem() ?? 0 }} of {{ $events->total() }} results
                            </div>
                        </div>
                    </div>
                </div>

                @if($events->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-wwc-neutral-200">
                            <thead class="bg-wwc-neutral-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-500 uppercase tracking-wider">Event</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-500 uppercase tracking-wider">Date & Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-500 uppercase tracking-wider">Venue</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-wwc-neutral-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-wwc-neutral-500 uppercase tracking-wider">Deleted At</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-wwc-neutral-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-wwc-neutral-200">
                                @foreach($events as $event)
                                    <tr class="hover:bg-wwc-neutral-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-lg bg-red-100 flex items-center justify-center">
                                                        <i class='bx bx-calendar text-red-600'></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-wwc-neutral-900">{{ $event->name }}</div>
                                                    <div class="text-sm text-wwc-neutral-500">{{ Str::limit($event->description, 50) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-wwc-neutral-900">{{ $event->date_time->format('M j, Y') }}</div>
                                            <div class="text-sm text-wwc-neutral-500">{{ $event->date_time->format('g:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-wwc-neutral-900">{{ $event->venue ?? 'No venue' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                @if($event->status === 'on_sale') bg-green-100 text-green-800
                                                @elseif($event->status === 'sold_out') bg-orange-100 text-orange-800
                                                @elseif($event->status === 'cancelled') bg-red-100 text-red-800
                                                @elseif($event->status === 'draft') bg-gray-100 text-gray-800
                                                @elseif($event->status === 'inactive') bg-yellow-100 text-yellow-800
                                                @else bg-blue-100 text-blue-800
                                                @endif">
                                                {{ str_replace('_', '', ucwords(str_replace('_', ' ', $event->status))) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="text-sm text-wwc-neutral-900">{{ $event->deleted_at->format('M j, Y') }}</div>
                                            <div class="text-sm text-wwc-neutral-500">{{ $event->deleted_at->format('g:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center justify-center space-x-2">
                                                <form action="{{ route('admin.events.restore', $event) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-green-600 bg-green-50 hover:bg-green-100 rounded-lg transition-colors duration-200"
                                                            onclick="return confirm('Are you sure you want to restore this event?')">
                                                        <i class='bx bx-undo text-xs mr-1'></i>
                                                        Restore
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.events.force-delete', $event) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors duration-200"
                                                            onclick="return confirm('Are you sure you want to permanently delete this event? This action cannot be undone.')">
                                                        <i class='bx bx-trash text-xs mr-1'></i>
                                                        Delete Forever
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-wwc-neutral-100">
                        {{ $events->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto h-24 w-24 bg-red-100 rounded-full flex items-center justify-center mb-4">
                            <i class='bx bx-trash text-4xl text-red-600'></i>
                        </div>
                        <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">No Trashed Events</h3>
                        <p class="text-wwc-neutral-600 mb-6">There are no deleted events to display.</p>
                        <a href="{{ route('admin.events.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <i class='bx bx-arrow-back text-sm mr-2'></i>
                            Back to Events
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
