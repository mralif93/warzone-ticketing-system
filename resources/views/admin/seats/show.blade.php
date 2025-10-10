@extends('layouts.admin')

@section('title', 'Seat Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-wwc-neutral-900 font-display">Seat Details</h1>
            <p class="text-wwc-neutral-600 mt-1">Seat: {{ $seat->seat_identifier }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.seats.edit', $seat) }}" 
               class="inline-flex items-center px-4 py-2 bg-wwc-accent text-white rounded-xl text-sm font-semibold hover:bg-wwc-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-accent transition-colors duration-200">
                <i class='bx bx-edit text-lg mr-2'></i>
                Edit Seat
            </a>
            <a href="{{ route('admin.seats.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-wwc-neutral-100 text-wwc-neutral-700 rounded-xl text-sm font-semibold hover:bg-wwc-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-neutral-500 transition-colors duration-200">
                <i class='bx bx-arrow-back text-lg mr-2'></i>
                Back to Seats
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Seat Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Details Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-wwc-neutral-900 font-display">Seat Information</h2>
                    @switch($seat->status)
                        @case('Available')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-wwc-success-light text-wwc-success">
                                <i class='bx bx-check text-sm mr-1'></i>
                                Available
                            </span>
                            @break
                        @case('Occupied')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-wwc-warning-light text-wwc-warning">
                                <i class='bx bx-user text-sm mr-1'></i>
                                Occupied
                            </span>
                            @break
                        @case('Maintenance')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-wwc-error-light text-wwc-error">
                                <i class='bx bx-wrench text-sm mr-1'></i>
                                Maintenance
                            </span>
                            @break
                    @endswitch
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Seat Identifier</label>
                        <p class="text-wwc-neutral-700 font-mono text-lg">{{ $seat->seat_identifier }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Seat ID</label>
                        <p class="text-wwc-neutral-700 font-mono text-sm">#{{ $seat->id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Price Zone</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-wwc-accent-light text-wwc-accent">
                            {{ $seat->price_zone }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Seat Type</label>
                        <p class="text-wwc-neutral-700">{{ $seat->seat_type }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Base Price</label>
                        <p class="text-2xl font-bold text-wwc-neutral-900">RM{{ number_format($seat->base_price, 0) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Created Date</label>
                        <p class="text-wwc-neutral-700">{{ $seat->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Ticket History -->
            @if($seat->tickets->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-6">
                <h2 class="text-xl font-semibold text-wwc-neutral-900 font-display mb-6">Ticket History</h2>
                
                <div class="space-y-4">
                    @foreach($seat->tickets as $ticket)
                    <div class="border border-wwc-neutral-200 rounded-xl p-4 hover:border-wwc-primary-light transition-colors duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="h-10 w-10 rounded-xl bg-wwc-primary-light flex items-center justify-center">
                                    <i class='bx bx-receipt text-lg text-wwc-primary'></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-wwc-neutral-900">Ticket #{{ $ticket->id }}</h3>
                                    <p class="text-xs text-wwc-neutral-500">{{ $ticket->event->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-wwc-neutral-900">RM{{ number_format($ticket->price_paid, 0) }}</div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                    @if($ticket->status === 'Sold') bg-wwc-success-light text-wwc-success
                                    @elseif($ticket->status === 'Held') bg-wwc-warning-light text-wwc-warning
                                    @elseif($ticket->status === 'Used') bg-wwc-accent-light text-wwc-accent
                                    @else bg-wwc-error-light text-wwc-error @endif">
                                    {{ $ticket->status }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-2 text-xs text-wwc-neutral-500">
                            {{ $ticket->created_at->format('M j, Y g:i A') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-6">
                <h3 class="text-lg font-semibold text-wwc-neutral-900 font-display mb-4">Quick Actions</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.seats.edit', $seat) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-accent text-white rounded-xl text-sm font-semibold hover:bg-wwc-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-accent transition-colors duration-200">
                        <i class='bx bx-edit text-lg mr-2'></i>
                        Edit Seat
                    </a>

                    <form method="POST" action="{{ route('admin.seats.update-status', $seat) }}" class="space-y-2">
                        @csrf
                        <select name="status" class="w-full px-3 py-2 border border-wwc-neutral-300 rounded-xl text-sm">
                            <option value="Available" {{ $seat->status === 'Available' ? 'selected' : '' }}>Available</option>
                            <option value="Occupied" {{ $seat->status === 'Occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="Maintenance" {{ $seat->status === 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-primary text-white rounded-xl text-sm font-semibold hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <i class='bx bx-check text-lg mr-2'></i>
                            Update Status
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.seats.destroy', $seat) }}" onsubmit="return confirm('Are you sure you want to delete this seat? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-error text-white rounded-xl text-sm font-semibold hover:bg-wwc-error-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-error transition-colors duration-200">
                            <i class='bx bx-trash text-lg mr-2'></i>
                            Delete Seat
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-6">
                <h3 class="text-lg font-semibold text-wwc-neutral-900 font-display mb-4">Statistics</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-wwc-neutral-600">Total Tickets</span>
                        <span class="text-sm font-semibold text-wwc-neutral-900">{{ $seat->tickets->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-wwc-neutral-600">Sold Tickets</span>
                        <span class="text-sm font-semibold text-wwc-success">{{ $seat->tickets->where('status', 'Sold')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-wwc-neutral-600">Used Tickets</span>
                        <span class="text-sm font-semibold text-wwc-accent">{{ $seat->tickets->where('status', 'Used')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-wwc-neutral-600">Total Revenue</span>
                        <span class="text-sm font-semibold text-wwc-neutral-900">RM{{ number_format($seat->tickets->where('status', 'Sold')->sum('price_paid'), 0) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
