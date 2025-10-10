@extends('layouts.admin')

@section('title', 'Price Zones Management')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display">Price Zones</h1>
                        <p class="text-wwc-neutral-600 mt-1">Manage ticket pricing zones and categories</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.price-zones.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-wwc-primary-dark transition-colors duration-200">
                            <i class='bx bx-plus text-lg mr-2'></i>
                            Add Price Zone
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-wwc-neutral-700 mb-2">Search</label>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by name, code, or description..."
                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category" class="block text-sm font-medium text-wwc-neutral-700 mb-2">Category</label>
                    <select name="category" id="category" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-wwc-neutral-700 mb-2">Status</label>
                    <select name="status" id="status" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-wwc-primary-dark transition-colors duration-200">
                        <i class='bx bx-search text-lg mr-2'></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Price Zones Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-wwc-neutral-900 font-display">All Price Zones</h2>
                        <p class="text-wwc-neutral-600 text-sm">Showing {{ $priceZones->count() }} of {{ $priceZones->total() }} price zones</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <!-- Bulk Actions -->
                        <form id="bulk-action-form" method="POST" action="{{ route('admin.price-zones.bulk-action') }}" class="hidden">
                            @csrf
                            <input type="hidden" name="action" id="bulk-action">
                            <input type="hidden" name="price_zones" id="bulk-price-zones">
                        </form>
                        
                        <select id="bulk-action-select" class="text-sm border border-wwc-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary">
                            <option value="">Bulk Actions</option>
                            <option value="activate">Activate Selected</option>
                            <option value="deactivate">Deactivate Selected</option>
                            <option value="delete">Delete Selected</option>
                        </select>
                        
                        <button type="button" id="apply-bulk-action" 
                                class="inline-flex items-center px-3 py-2 bg-wwc-neutral-100 text-wwc-neutral-700 text-sm font-semibold rounded-lg hover:bg-wwc-neutral-200 transition-colors duration-200">
                            Apply
                        </button>
                    </div>
                </div>
            </div>

            @if($priceZones->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-wwc-neutral-200">
                        <thead class="bg-wwc-neutral-50">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input type="checkbox" id="select-all" class="rounded border-wwc-neutral-300 text-wwc-primary focus:ring-wwc-primary">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Price Zone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Seats</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Sort Order</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-wwc-neutral-200">
                            @foreach($priceZones as $priceZone)
                            <tr class="hover:bg-wwc-neutral-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" 
                                           class="price-zone-checkbox rounded border-wwc-neutral-300 text-wwc-primary focus:ring-wwc-primary" 
                                           value="{{ $priceZone->id }}">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-lg flex items-center justify-center" style="background-color: {{ $priceZone->color }}20;">
                                                @if($priceZone->icon)
                                                    <i class="{{ $priceZone->icon }} text-lg" style="color: {{ $priceZone->color }};"></i>
                                                @else
                                                    <i class='bx bx-tag text-lg' style="color: {{ $priceZone->color }};"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-wwc-neutral-900 font-display">{{ $priceZone->name }}</div>
                                            <div class="text-xs text-wwc-neutral-500">{{ $priceZone->code }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if($priceZone->category === 'Premium') bg-amber-100 text-amber-800
                                        @elseif($priceZone->category === 'Level') bg-blue-100 text-blue-800
                                        @elseif($priceZone->category === 'Standing') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $priceZone->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-wwc-primary">{{ $priceZone->formatted_price }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-wwc-neutral-900">{{ $priceZone->seat_count }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if($priceZone->is_active) bg-wwc-success text-white
                                        @else bg-wwc-neutral-200 text-wwc-neutral-800
                                        @endif">
                                        {{ $priceZone->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-wwc-neutral-900">{{ $priceZone->sort_order }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.price-zones.show', $priceZone) }}" 
                                           class="inline-flex items-center px-2 py-1 text-xs font-semibold text-wwc-primary hover:bg-wwc-primary-light hover:text-wwc-primary-dark rounded-2xl transition-colors duration-200">
                                            <i class='bx bx-show text-xs mr-1'></i>
                                            View
                                        </a>
                                        <a href="{{ route('admin.price-zones.edit', $priceZone) }}" 
                                           class="inline-flex items-center px-2 py-1 text-xs font-semibold text-wwc-neutral-600 hover:bg-wwc-neutral-100 hover:text-wwc-neutral-900 rounded-2xl transition-colors duration-200">
                                            <i class='bx bx-edit text-xs mr-1'></i>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.price-zones.toggle-status', $priceZone) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="inline-flex items-center px-2 py-1 text-xs font-semibold {{ $priceZone->is_active ? 'text-wwc-warning hover:bg-wwc-warning-light' : 'text-wwc-success hover:bg-wwc-success-light' }} rounded-2xl transition-colors duration-200">
                                                <i class='bx {{ $priceZone->is_active ? 'bx-pause' : 'bx-play' }} text-xs mr-1'></i>
                                                {{ $priceZone->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.price-zones.destroy', $priceZone) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this price zone?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-2 py-1 text-xs font-semibold text-wwc-error hover:bg-wwc-error-light rounded-2xl transition-colors duration-200">
                                                <i class='bx bx-trash text-xs mr-1'></i>
                                                Delete
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
                <div class="px-6 py-4 border-t border-wwc-neutral-200 bg-wwc-neutral-50">
                    {{ $priceZones->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class='bx bx-tag text-6xl text-wwc-neutral-300 mb-4'></i>
                    <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">No Price Zones Found</h3>
                    <p class="text-wwc-neutral-600 mb-6">Get started by creating your first price zone.</p>
                    <a href="{{ route('admin.price-zones.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-wwc-primary-dark transition-colors duration-200">
                        <i class='bx bx-plus text-lg mr-2'></i>
                        Add Price Zone
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox
    const selectAllCheckbox = document.getElementById('select-all');
    const priceZoneCheckboxes = document.querySelectorAll('.price-zone-checkbox');
    
    selectAllCheckbox.addEventListener('change', function() {
        priceZoneCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    // Individual checkboxes
    priceZoneCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedBoxes = document.querySelectorAll('.price-zone-checkbox:checked');
            selectAllCheckbox.checked = checkedBoxes.length === priceZoneCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < priceZoneCheckboxes.length;
        });
    });
    
    // Bulk actions
    const bulkActionButton = document.getElementById('apply-bulk-action');
    const bulkActionSelect = document.getElementById('bulk-action-select');
    const bulkActionForm = document.getElementById('bulk-action-form');
    const bulkActionInput = document.getElementById('bulk-action');
    const bulkPriceZonesInput = document.getElementById('bulk-price-zones');
    
    bulkActionButton.addEventListener('click', function() {
        const selectedAction = bulkActionSelect.value;
        const selectedPriceZones = Array.from(document.querySelectorAll('.price-zone-checkbox:checked')).map(cb => cb.value);
        
        if (!selectedAction) {
            alert('Please select an action');
            return;
        }
        
        if (selectedPriceZones.length === 0) {
            alert('Please select at least one price zone');
            return;
        }
        
        if (selectedAction === 'delete' && !confirm('Are you sure you want to delete the selected price zones?')) {
            return;
        }
        
        bulkActionInput.value = selectedAction;
        bulkPriceZonesInput.value = JSON.stringify(selectedPriceZones);
        bulkActionForm.submit();
    });
});
</script>
@endpush
@endsection
