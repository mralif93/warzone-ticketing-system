@extends('layouts.admin')

@section('title', 'Price Zone Details')
@section('page-title', 'Price Zone Details')

@section('content')
<!-- Professional Price Zone Details with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <div class="flex items-center">
                    <a href="{{ route('admin.price-zones.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Price Zones
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Price Zone Details -->
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Price Zone Details</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-info-circle text-sm'></i>
                                    <span>Price zone information</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Name -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-tag text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Name</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $priceZone->name }}</span>
                                    </div>
                                </div>

                                <!-- Code -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-code text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Code</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $priceZone->code }}</span>
                                    </div>
                                </div>

                                <!-- Base Price -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                                            <i class='bx bx-dollar text-sm text-emerald-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Base Price</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $priceZone->formatted_price }}</span>
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-category text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Category</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priceZone->category_badge_color }}">
                                                {{ $priceZone->category }}
                                            </span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Color -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                            <i class='bx bx-palette text-sm text-orange-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Color</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 rounded-full {{ str_replace('text-', 'bg-', $priceZone->color) }} mr-2"></div>
                                                {{ ucfirst(str_replace(['text-', '-600'], '', $priceZone->color)) }}
                                            </div>
                                        </span>
                                    </div>
                                </div>

                                <!-- Icon -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-yellow-100 flex items-center justify-center">
                                            <i class='bx bx-image text-sm text-yellow-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Icon</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">
                                            @if($priceZone->icon)
                                                <i class='{{ $priceZone->icon }} {{ $priceZone->color }} text-lg'></i>
                                                <span class="ml-2">{{ $priceZone->icon }}</span>
                                            @else
                                                <span class="text-wwc-neutral-500">No icon set</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <!-- Sort Order -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                            <i class='bx bx-sort text-sm text-indigo-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Sort Order</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $priceZone->sort_order }}</span>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-teal-100 flex items-center justify-center">
                                            <i class='bx bx-check-circle text-sm text-teal-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Status</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">
                                            @if($priceZone->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class='bx bx-check text-xs mr-1'></i>
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class='bx bx-x text-xs mr-1'></i>
                                                    Inactive
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <!-- Description -->
                                @if($priceZone->description)
                                    <div class="flex items-center py-3">
                                        <div class="flex-shrink-0 mr-4">
                                            <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <i class='bx bx-note text-sm text-gray-600'></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 flex items-center justify-between">
                                            <span class="text-sm font-semibold text-wwc-neutral-600">Description</span>
                                            <span class="text-base font-medium text-wwc-neutral-900">{{ $priceZone->description }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Price Zone Statistics -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Price Zone Statistics</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Created Date -->
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-wwc-neutral-900">{{ $priceZone->created_at->format('M d, Y') }}</div>
                                    <div class="text-sm text-wwc-neutral-500">Created Date</div>
                                </div>
                                <!-- Updated Date -->
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-wwc-neutral-900">{{ $priceZone->updated_at->format('M d, Y') }}</div>
                                    <div class="text-sm text-wwc-neutral-500">Last Updated</div>
                                </div>
                                <!-- Seats Count -->
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-wwc-neutral-900">{{ $priceZone->seats_count ?? 0 }}</div>
                                    <div class="text-sm text-wwc-neutral-500">Assigned Seats</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Quick Actions</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <a href="{{ route('admin.price-zones.edit', $priceZone) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                    <i class='bx bx-edit text-sm mr-2'></i>
                                    Edit Price Zone
                                </a>
                                <a href="{{ route('admin.price-zones.toggle-status', $priceZone) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                    <i class='bx bx-{{ $priceZone->is_active ? 'x' : 'check' }} text-sm mr-2'></i>
                                    {{ $priceZone->is_active ? 'Deactivate' : 'Activate' }}
                                </a>
                                <a href="{{ route('admin.price-zones.index') }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                    <i class='bx bx-list-ul text-sm mr-2'></i>
                                    View All Price Zones
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection