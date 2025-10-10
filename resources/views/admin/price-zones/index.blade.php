@extends('layouts.admin')

@section('title', 'Price Zone Management')
@section('page-subtitle', 'Manage ticket pricing zones and categories')

@section('content')
<!-- Professional Price Zone Management with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">
    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <!-- Total Price Zones -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $priceZones->total() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Price Zones</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    {{ $priceZones->where('is_active', true)->count() }} Active
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-tag text-2xl text-blue-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Active Price Zones -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $priceZones->where('is_active', true)->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Active</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <i class='bx bx-check text-xs mr-1'></i>
                                    {{ $priceZones->where('is_active', false)->count() }} Inactive
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class='bx bx-check-circle text-2xl text-green-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Premium Categories -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $priceZones->where('category', 'Premium')->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Premium</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-warning font-semibold">
                                    <i class='bx bx-time text-xs mr-1'></i>
                                    {{ $priceZones->where('category', 'Level')->count() }} Level
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                            <i class='bx bx-crown text-2xl text-yellow-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Average Price -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">RM{{ number_format($priceZones->avg('base_price'), 0) }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Average Price</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-accent font-semibold">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    RM{{ number_format($priceZones->max('base_price'), 0) }} Max
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-emerald-100 flex items-center justify-center">
                            <i class='bx bx-dollar text-2xl text-emerald-600'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Search & Filter Price Zones</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-search text-sm'></i>
                            <span>Find specific price zones</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search Price Zones</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Search by name, code, or description..."
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Category</label>
                            <select name="category" id="category" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Status</label>
                            <select name="status" id="status" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label for="sort" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Sort By</label>
                            <select name="sort" id="sort" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                                <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price</option>
                                <option value="category" {{ request('sort') == 'category' ? 'selected' : '' }}>Category</option>
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2 lg:col-span-4 flex justify-end gap-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-search text-sm mr-2'></i>
                                Search Price Zones
                            </button>
                            <a href="{{ route('admin.price-zones.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Header Section with Create Button -->
            <div class="flex justify-end items-center mb-6">
                <a href="{{ route('admin.price-zones.create') }}" 
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-plus text-sm mr-2'></i>
                    Create New Price Zone
                </a>
            </div>

            <!-- Price Zones List -->
            @if($priceZones->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                    <!-- Table Header -->
                    <div class="px-6 py-4 border-b border-wwc-neutral-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Price Zones</h3>
                            <div class="flex items-center space-x-4 text-sm text-wwc-neutral-500">
                                <span class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                    {{ $priceZones->where('is_active', true)->count() }} Active
                                </span>
                                <span class="flex items-center">
                                    <span class="w-2 h-2 bg-gray-500 rounded-full mr-2"></span>
                                    {{ $priceZones->where('is_active', false)->count() }} Inactive
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-wwc-neutral-200">
                            <thead class="bg-wwc-neutral-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Price Zone</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Created</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-wwc-neutral-200">
                                @foreach($priceZones as $priceZone)
                                    <tr class="hover:bg-wwc-neutral-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-lg {{ $priceZone->category_badge_color }} flex items-center justify-center mr-3">
                                                    <i class='{{ $priceZone->icon ?? 'bx bx-tag' }} text-lg {{ $priceZone->color }}'></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-wwc-neutral-900">{{ $priceZone->name }}</div>
                                                    <div class="text-xs text-wwc-neutral-500">{{ $priceZone->code }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priceZone->category_badge_color }}">
                                                {{ $priceZone->category }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-wwc-neutral-900">{{ $priceZone->formatted_price }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($priceZone->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-wwc-neutral-900">{{ $priceZone->created_at->format('M d, Y') }}</div>
                                            <div class="text-xs text-wwc-neutral-500">{{ $priceZone->created_at->format('h:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-1">
                                                <a href="{{ route('admin.price-zones.show', $priceZone) }}" 
                                                   class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                                   title="View price zone details">
                                                    <i class='bx bx-show text-xs mr-1.5'></i>
                                                    View
                                                </a>
                                                <a href="{{ route('admin.price-zones.edit', $priceZone) }}" 
                                                   class="inline-flex items-center px-3 py-2 text-xs font-semibold text-wwc-neutral-700 bg-white border border-wwc-neutral-300 hover:bg-wwc-neutral-50 hover:border-wwc-neutral-400 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                                   title="Edit price zone">
                                                    <i class='bx bx-edit text-xs mr-1.5'></i>
                                                    Edit
                                                </a>
                                                <div class="relative" x-data="{ open{{ $priceZone->id }}: false }">
                                                    <button @click="open{{ $priceZone->id }} = !open{{ $priceZone->id }}" 
                                                            class="inline-flex items-center px-3 py-2 text-xs font-semibold text-wwc-neutral-700 bg-white border border-wwc-neutral-300 hover:bg-wwc-neutral-50 hover:border-wwc-neutral-400 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                                            title="More actions">
                                                        <i class='bx bx-dots-vertical text-xs mr-1.5'></i>
                                                        More
                                                    </button>
                                                    <div x-show="open{{ $priceZone->id }}" 
                                                         @click.away="open{{ $priceZone->id }} = false"
                                                         x-transition:enter="transition ease-out duration-100"
                                                         x-transition:enter-start="transform opacity-0 scale-95"
                                                         x-transition:enter-end="transform opacity-100 scale-100"
                                                         x-transition:leave="transition ease-in duration-75"
                                                         x-transition:leave-start="transform opacity-100 scale-100"
                                                         x-transition:leave-end="transform opacity-0 scale-95"
                                                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-wwc-neutral-200 z-10"
                                                         style="display: none;">
                                                        <div class="py-1">
                                                            <a href="{{ route('admin.price-zones.toggle-status', $priceZone) }}" 
                                                               class="flex items-center px-4 py-2 text-xs text-wwc-neutral-700 hover:bg-wwc-neutral-100 transition-colors duration-200">
                                                                <i class='bx bx-{{ $priceZone->is_active ? 'x' : 'check' }} text-xs mr-2'></i>
                                                                {{ $priceZone->is_active ? 'Deactivate' : 'Activate' }}
                                                            </a>
                                                            <div class="border-t border-wwc-neutral-100 my-1"></div>
                                                            @if(!$priceZone->hasSeats())
                                                                <form action="{{ route('admin.price-zones.destroy', $priceZone) }}" method="POST" 
                                                                      onsubmit="return confirm('Are you sure you want to delete this price zone? This action cannot be undone.')" 
                                                                      class="block">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" 
                                                                            class="flex items-center w-full px-4 py-2 text-xs text-red-600 hover:bg-red-50 transition-colors duration-200">
                                                                        <i class='bx bx-trash text-xs mr-2'></i>
                                                                        Delete Price Zone
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-wwc-neutral-100">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-wwc-neutral-700">
                                Showing {{ $priceZones->firstItem() }} to {{ $priceZones->lastItem() }} of {{ $priceZones->total() }} results
                            </div>
                            <div class="flex items-center space-x-2">
                                {{ $priceZones->links('vendor.pagination.wwc-tailwind') }}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mx-auto h-24 w-24 rounded-full bg-wwc-neutral-100 flex items-center justify-center mb-4">
                        <i class='bx bx-tag text-4xl text-wwc-neutral-400'></i>
                    </div>
                    <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">No price zones found</h3>
                    <p class="text-wwc-neutral-500 mb-6">Get started by creating a new price zone or adjusting your search criteria.</p>
                    <a href="{{ route('admin.price-zones.create') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-plus text-sm mr-2'></i>
                        Create Price Zone
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection