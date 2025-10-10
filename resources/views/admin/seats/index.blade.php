@extends('layouts.admin')

@section('title', 'Seats Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-wwc-neutral-900 font-display">Seats Management</h1>
            <p class="text-wwc-neutral-600 mt-1">Manage all venue seats and pricing</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="openBulkCreateModal()" 
                    class="inline-flex items-center px-4 py-2 bg-wwc-accent text-white rounded-xl text-sm font-semibold hover:bg-wwc-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-accent transition-colors duration-200">
                <i class='bx bx-plus text-lg mr-2'></i>
                Bulk Create
            </a>
            <a href="{{ route('admin.seats.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white rounded-xl text-sm font-semibold hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                <i class='bx bx-plus text-lg mr-2'></i>
                Add Seat
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Total Seats -->
        <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $seats->total() }}</div>
                    <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Seats</div>
                    <div class="flex items-center">
                        <div class="flex items-center text-xs text-wwc-success font-semibold">
                            <i class='bx bx-check text-xs mr-1'></i>
                            {{ $seats->where('status', 'Available')->count() }} Available
                        </div>
                    </div>
                </div>
                <div class="h-12 w-12 rounded-lg bg-wwc-primary-light flex items-center justify-center">
                    <i class='bx bx-chair text-2xl text-wwc-primary'></i>
                </div>
            </div>
        </div>

        <!-- Available Seats -->
        <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $seats->where('status', 'Available')->count() }}</div>
                    <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Available</div>
                    <div class="flex items-center">
                        <div class="flex items-center text-xs text-wwc-warning font-semibold">
                            <i class='bx bx-time text-xs mr-1'></i>
                            {{ $seats->where('status', 'Occupied')->count() }} Occupied
                        </div>
                    </div>
                </div>
                <div class="h-12 w-12 rounded-lg bg-wwc-success-light flex items-center justify-center">
                    <i class='bx bx-check-circle text-2xl text-wwc-success'></i>
                </div>
            </div>
        </div>

        <!-- Occupied Seats -->
        <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $seats->where('status', 'Occupied')->count() }}</div>
                    <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Occupied</div>
                    <div class="flex items-center">
                        <div class="flex items-center text-xs text-wwc-error font-semibold">
                            <i class='bx bx-wrench text-xs mr-1'></i>
                            {{ $seats->where('status', 'Maintenance')->count() }} Maintenance
                        </div>
                    </div>
                </div>
                <div class="h-12 w-12 rounded-lg bg-wwc-warning-light flex items-center justify-center">
                    <i class='bx bx-user text-2xl text-wwc-warning'></i>
                </div>
            </div>
        </div>

        <!-- Price Zones -->
        <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $priceZones->count() }}</div>
                    <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Price Zones</div>
                    <div class="flex items-center">
                        <div class="flex items-center text-xs text-wwc-accent font-semibold">
                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            Active zones
                        </div>
                    </div>
                </div>
                <div class="h-12 w-12 rounded-lg bg-wwc-accent-light flex items-center justify-center">
                    <i class='bx bx-tag text-2xl text-wwc-accent'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class='bx bx-search text-wwc-neutral-400'></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               class="block w-full pl-10 pr-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm"
                               placeholder="Search by seat identifier, price zone...">
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Status</label>
                    <select name="status" id="status" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Zone Filter -->
                <div>
                    <label for="price_zone" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Price Zone</label>
                    <select name="price_zone" id="price_zone" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        <option value="">All Price Zones</option>
                        @foreach($priceZones as $zone)
                            <option value="{{ $zone }}" {{ request('price_zone') == $zone ? 'selected' : '' }}>
                                {{ $zone }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Seat Type Filter -->
                <div>
                    <label for="seat_type" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Seat Type</label>
                    <select name="seat_type" id="seat_type" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        <option value="">All Types</option>
                        @foreach($seatTypes as $type)
                            <option value="{{ $type }}" {{ request('seat_type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white rounded-xl text-sm font-semibold hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-search text-lg mr-2'></i>
                        Search
                    </button>
                    <a href="{{ route('admin.seats.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-wwc-neutral-100 text-wwc-neutral-700 rounded-xl text-sm font-semibold hover:bg-wwc-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-neutral-500 transition-colors duration-200">
                        <i class='bx bx-refresh text-lg mr-2'></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Seats Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
        @if($seats->count() > 0)
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-wwc-neutral-900 font-display">All Seats</h2>
                        <p class="text-wwc-neutral-600 text-sm">Showing {{ $seats->count() }} of {{ $seats->total() }} seats</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="text-xs text-wwc-neutral-600">
                            <span class="font-semibold">{{ $seats->where('status', 'Available')->count() }}</span> Available
                        </div>
                        <div class="text-xs text-wwc-neutral-600">
                            <span class="font-semibold">{{ $seats->where('status', 'Occupied')->count() }}</span> Occupied
                        </div>
                        <div class="text-xs text-wwc-neutral-600">
                            <span class="font-semibold">{{ $seats->where('status', 'Maintenance')->count() }}</span> Maintenance
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-wwc-neutral-200">
                    <thead class="bg-wwc-neutral-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Seat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Price Zone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-wwc-neutral-200">
                        @foreach($seats as $seat)
                        <tr class="hover:bg-wwc-neutral-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-2xl bg-wwc-primary-light flex items-center justify-center shadow-sm">
                                            <i class='bx bx-chair text-lg text-wwc-primary'></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-wwc-neutral-900 font-display">{{ $seat->seat_identifier }}</div>
                                        <div class="text-xs text-wwc-neutral-500">ID: {{ $seat->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-wwc-accent-light text-wwc-accent">
                                    {{ $seat->price_zone }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-900">
                                {{ $seat->seat_type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-wwc-neutral-900">RM{{ number_format($seat->base_price, 0) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($seat->status)
                                    @case('Available')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-wwc-success-light text-wwc-success">
                                            <i class='bx bx-check text-xs mr-1'></i>
                                            Available
                                        </span>
                                        @break
                                    @case('Occupied')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-wwc-warning-light text-wwc-warning">
                                            <i class='bx bx-user text-xs mr-1'></i>
                                            Occupied
                                        </span>
                                        @break
                                    @case('Maintenance')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-wwc-error-light text-wwc-error">
                                            <i class='bx bx-wrench text-xs mr-1'></i>
                                            Maintenance
                                        </span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-500">
                                {{ $seat->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.seats.show', $seat) }}" 
                                       class="text-wwc-primary hover:text-wwc-primary-dark">
                                        <i class='bx bx-show text-lg'></i>
                                    </a>
                                    <a href="{{ route('admin.seats.edit', $seat) }}" 
                                       class="text-wwc-accent hover:text-wwc-accent-dark">
                                        <i class='bx bx-edit text-lg'></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.seats.destroy', $seat) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this seat?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-wwc-error hover:text-wwc-error-dark">
                                            <i class='bx bx-trash text-lg'></i>
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
                {{ $seats->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class='bx bx-chair text-6xl text-wwc-neutral-300 mb-4'></i>
                <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">No Seats Found</h3>
                <p class="text-wwc-neutral-600 mb-6">No seats match your current filters.</p>
                <a href="{{ route('admin.seats.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white rounded-xl text-sm font-semibold hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-plus text-lg mr-2'></i>
                    Create First Seat
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Bulk Create Modal -->
<div id="bulkCreateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-4">Bulk Create Seats</h3>
            <form method="POST" action="{{ route('admin.seats.bulk-create') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="price_zone" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Price Zone</label>
                        <select name="price_zone" id="price_zone" required class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                            <option value="">Select Price Zone</option>
                            @foreach($priceZones as $zone)
                                <option value="{{ $zone }}">{{ $zone }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="seat_type" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Seat Type</label>
                        <input type="text" name="seat_type" id="seat_type" required class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm" placeholder="e.g., Standard, VIP, Premium">
                    </div>
                    <div>
                        <label for="base_price" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Base Price (RM)</label>
                        <input type="number" name="base_price" id="base_price" step="0.01" min="0" required class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                    </div>
                    <div>
                        <label for="section" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Section</label>
                        <input type="text" name="section" id="section" required class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm" placeholder="e.g., A, B, C">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="row_start" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Start Row</label>
                            <input type="number" name="row_start" id="row_start" min="1" required class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div>
                            <label for="row_end" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">End Row</label>
                            <input type="number" name="row_end" id="row_end" min="1" required class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="seats_per_row" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Seats per Row</label>
                        <input type="number" name="seats_per_row" id="seats_per_row" min="1" max="50" required class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeBulkCreateModal()" 
                            class="px-4 py-2 bg-wwc-neutral-100 text-wwc-neutral-700 rounded-xl text-sm font-semibold hover:bg-wwc-neutral-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-wwc-primary text-white rounded-xl text-sm font-semibold hover:bg-wwc-primary-dark">
                        Create Seats
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openBulkCreateModal() {
    document.getElementById('bulkCreateModal').classList.remove('hidden');
}

function closeBulkCreateModal() {
    document.getElementById('bulkCreateModal').classList.add('hidden');
}
</script>
@endsection
