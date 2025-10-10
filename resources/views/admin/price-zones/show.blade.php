@extends('layouts.admin')

@section('title', 'Price Zone Details')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display">{{ $priceZone->name }}</h1>
                        <p class="text-wwc-neutral-600 mt-1">Price zone details and statistics</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.price-zones.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-wwc-neutral-100 text-wwc-neutral-700 text-sm font-semibold rounded-lg hover:bg-wwc-neutral-200 transition-colors duration-200">
                            <i class='bx bx-arrow-back text-lg mr-2'></i>
                            Back to Price Zones
                        </a>
                        <a href="{{ route('admin.price-zones.edit', $priceZone) }}" 
                           class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-wwc-primary-dark transition-colors duration-200">
                            <i class='bx bx-edit text-lg mr-2'></i>
                            Edit Price Zone
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Details -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900 font-display">Basic Details</h2>
                    </div>
                    <div class="px-6 py-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-wwc-neutral-500">Name</dt>
                                <dd class="mt-1 text-sm text-wwc-neutral-900 font-semibold">{{ $priceZone->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-wwc-neutral-500">Code</dt>
                                <dd class="mt-1 text-sm text-wwc-neutral-900 font-mono">{{ $priceZone->code }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-wwc-neutral-500">Category</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if($priceZone->category === 'Premium') bg-amber-100 text-amber-800
                                        @elseif($priceZone->category === 'Level') bg-blue-100 text-blue-800
                                        @elseif($priceZone->category === 'Standing') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $priceZone->category }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-wwc-neutral-500">Base Price</dt>
                                <dd class="mt-1 text-sm text-wwc-neutral-900 font-bold text-wwc-primary">{{ $priceZone->formatted_price }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-wwc-neutral-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if($priceZone->is_active) bg-wwc-success text-white
                                        @else bg-wwc-neutral-200 text-wwc-neutral-800
                                        @endif">
                                        {{ $priceZone->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-wwc-neutral-500">Sort Order</dt>
                                <dd class="mt-1 text-sm text-wwc-neutral-900">{{ $priceZone->sort_order }}</dd>
                            </div>
                        </dl>
                        
                        @if($priceZone->description)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-wwc-neutral-500 mb-2">Description</dt>
                            <dd class="text-sm text-wwc-neutral-900">{{ $priceZone->description }}</dd>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Visual Settings -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900 font-display">Visual Settings</h2>
                    </div>
                    <div class="px-6 py-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-wwc-neutral-500">Color</dt>
                                <dd class="mt-1 flex items-center space-x-3">
                                    <div class="h-6 w-6 rounded border border-wwc-neutral-300" style="background-color: {{ $priceZone->color }};"></div>
                                    <span class="text-sm text-wwc-neutral-900 font-mono">{{ $priceZone->color }}</span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-wwc-neutral-500">Icon</dt>
                                <dd class="mt-1 flex items-center space-x-3">
                                    @if($priceZone->icon)
                                        <i class="{{ $priceZone->icon }} text-lg" style="color: {{ $priceZone->color }};"></i>
                                        <span class="text-sm text-wwc-neutral-900">{{ $priceZone->icon }}</span>
                                    @else
                                        <i class='bx bx-tag text-lg text-wwc-neutral-400'></i>
                                        <span class="text-sm text-wwc-neutral-500">No icon set</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Seats Information -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900 font-display">Seats Information</h2>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-wwc-primary mb-2">{{ $priceZone->seat_count }}</div>
                                <div class="text-sm text-wwc-neutral-600">Total Seats</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-wwc-success mb-2">{{ $priceZone->seats->where('is_accessible', true)->count() }}</div>
                                <div class="text-sm text-wwc-neutral-600">Accessible Seats</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-wwc-accent mb-2">{{ $priceZone->seats->groupBy('seat_type')->count() }}</div>
                                <div class="text-sm text-wwc-neutral-600">Seat Types</div>
                            </div>
                        </div>
                        
                        @if($priceZone->seat_count > 0)
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-wwc-neutral-700 mb-3">Seat Types Distribution</h3>
                            <div class="space-y-2">
                                @foreach($priceZone->seats->groupBy('seat_type') as $type => $seats)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-wwc-neutral-600">{{ $type }}</span>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-32 bg-wwc-neutral-200 rounded-full h-2">
                                            <div class="bg-wwc-primary h-2 rounded-full" style="width: {{ ($seats->count() / $priceZone->seat_count) * 100 }}%"></div>
                                        </div>
                                        <span class="text-sm text-wwc-neutral-900 font-medium">{{ $seats->count() }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900 font-display">Quick Actions</h2>
                    </div>
                    <div class="px-6 py-6 space-y-3">
                        <a href="{{ route('admin.price-zones.edit', $priceZone) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-wwc-primary-dark transition-colors duration-200">
                            <i class='bx bx-edit text-lg mr-2'></i>
                            Edit Price Zone
                        </a>
                        
                        <form method="POST" action="{{ route('admin.price-zones.toggle-status', $priceZone) }}" class="w-full">
                            @csrf
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 {{ $priceZone->is_active ? 'bg-wwc-warning text-white hover:bg-wwc-warning-dark' : 'bg-wwc-success text-white hover:bg-wwc-success-dark' }} text-sm font-semibold rounded-lg transition-colors duration-200">
                                <i class='bx {{ $priceZone->is_active ? 'bx-pause' : 'bx-play' }} text-lg mr-2'></i>
                                {{ $priceZone->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('admin.price-zones.destroy', $priceZone) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this price zone? This action cannot be undone.')" 
                              class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-error text-white text-sm font-semibold rounded-lg hover:bg-wwc-error-dark transition-colors duration-200">
                                <i class='bx bx-trash text-lg mr-2'></i>
                                Delete Price Zone
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900 font-display">Statistics</h2>
                    </div>
                    <div class="px-6 py-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-wwc-neutral-600">Created</span>
                            <span class="text-sm text-wwc-neutral-900">{{ $priceZone->created_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-wwc-neutral-600">Last Updated</span>
                            <span class="text-sm text-wwc-neutral-900">{{ $priceZone->updated_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-wwc-neutral-600">Tickets Sold</span>
                            <span class="text-sm text-wwc-neutral-900">{{ $priceZone->tickets->where('status', 'Sold')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-wwc-neutral-600">Revenue Generated</span>
                            <span class="text-sm text-wwc-neutral-900 font-semibold text-wwc-primary">
                                RM{{ number_format($priceZone->tickets->where('status', 'Sold')->sum('price_paid'), 0) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Recent Tickets -->
                @if($priceZone->tickets->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900 font-display">Recent Tickets</h2>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-3">
                            @foreach($priceZone->tickets->take(5) as $ticket)
                            <div class="flex items-center justify-between py-2 border-b border-wwc-neutral-100 last:border-b-0">
                                <div>
                                    <div class="text-sm font-medium text-wwc-neutral-900">Ticket #{{ $ticket->id }}</div>
                                    <div class="text-xs text-wwc-neutral-500">{{ $ticket->event->name ?? 'N/A' }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                        @if($ticket->status === 'Sold') bg-wwc-success text-white
                                        @elseif($ticket->status === 'Held') bg-wwc-warning text-white
                                        @elseif($ticket->status === 'Cancelled') bg-wwc-error text-white
                                        @else bg-wwc-neutral-200 text-wwc-neutral-800
                                        @endif">
                                        {{ $ticket->status }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        @if($priceZone->tickets->count() > 5)
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.tickets.index', ['price_zone' => $priceZone->id]) }}" 
                               class="text-sm text-wwc-primary hover:text-wwc-primary-dark font-medium">
                                View all {{ $priceZone->tickets->count() }} tickets
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
