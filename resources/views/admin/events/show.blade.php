@extends('layouts.admin')

@section('title', 'Event Details')
@section('page-title', 'Event Details')

@section('content')
<div class="min-h-screen bg-wwc-neutral-50">
    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <div class="flex items-center">
                    <a href="{{ route('admin.events.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Events
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Event Details -->
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Event Details</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-info-circle text-sm'></i>
                                    <span>Event information</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Event Name -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-red-100 flex items-center justify-center">
                                            <i class='bx bx-calendar text-sm text-red-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Event Name</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $event->name }}</span>
                                    </div>
                                </div>

                                <!-- Date & Time -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-time text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">
                                            @if($event->isMultiDay())
                                                Event Duration
                                            @else
                                                Date & Time
                                            @endif
                                        </span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $event->getFormattedDateRange() }}</span>
                                    </div>
                                </div>

                                @if($event->isMultiDay())
                                <!-- Event Duration Info -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-calendar text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Duration</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $event->getDurationInDays() }} day{{ $event->getDurationInDays() > 1 ? 's' : '' }}</span>
                                    </div>
                                </div>
                                @endif

                                <!-- Venue -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                            <i class='bx bx-map text-sm text-orange-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Venue</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $event->venue }}</span>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-check-circle text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Status</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($event->status === 'on_sale') bg-green-100 text-green-800
                                            @elseif($event->status === 'sold_out') bg-red-100 text-red-800
                                            @elseif($event->status === 'cancelled') bg-gray-100 text-gray-800
                                            @elseif($event->status === 'draft') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucwords(str_replace('_', ' ', $event->status)) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Total Seats -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                            <i class='bx bx-group text-sm text-indigo-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Total Capacity</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ number_format($event->total_seats) }} seats</span>
                                    </div>
                                </div>

                                <!-- Description -->
                                @if($event->description)
                                <div class="flex items-center py-3">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <i class='bx bx-file text-sm text-gray-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Description</span>
                                        <p class="text-base font-medium text-wwc-neutral-900 mt-1">{{ $event->description }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Combo Discount Settings -->
                    @if($event->isMultiDay())
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mt-6">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Combo Discount Settings</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-discount text-sm'></i>
                                    <span>Multi-day benefits</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-wwc-accent flex items-center justify-center">
                                            <i class='bx bx-gift text-sm text-white'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Combo Discount Enabled</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class='bx bx-check text-xs mr-1'></i>
                                            Enabled
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center py-3">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-wwc-accent/20 flex items-center justify-center">
                                            <i class='bx bx-tag text-sm text-wwc-accent'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Discount Percentage</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ number_format($event->combo_discount_percentage, 2) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Ticket Types Overview -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mt-6">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Ticket Types Overview</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-purchase-tag text-sm'></i>
                                    <span>Available tickets</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($event->ticketTypes->count() > 0)
                                <div class="space-y-1">
                                    @foreach($event->ticketTypes as $ticketType)
                                    <div class="bg-white border-b border-wwc-neutral-100 py-4 px-6 hover:bg-wwc-neutral-50 transition-colors duration-150">
                                        <div class="flex items-center justify-between">
                                            <!-- Left Side: Ticket Info -->
                                            <div class="flex items-center space-x-3">
                                                <div class="h-8 w-8 rounded bg-wwc-primary flex items-center justify-center flex-shrink-0">
                                                    <i class='bx bx-purchase-tag text-sm text-white'></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-bold text-wwc-neutral-900">{{ $ticketType->name }}</h4>
                                                    <p class="text-xs text-wwc-neutral-600">RM{{ number_format($ticketType->price, 0) }} per ticket</p>
                                                </div>
                                            </div>

                                            <!-- Right Side: Combo Button and All Statistics -->
                                            <div class="flex items-center space-x-6">
                                                @if($ticketType->is_combo)
                                                <div class="flex-shrink-0">
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-wwc-accent/20 text-wwc-accent">
                                                        <i class='bx bx-discount text-xs mr-1'></i>
                                                        Combo
                                                    </span>
                                                </div>
                                                @endif
                                                <div class="text-center min-w-[60px]">
                                                    <p class="text-lg font-bold text-wwc-neutral-900">{{ number_format($ticketType->available_seats) }}</p>
                                                    <p class="text-xs text-wwc-neutral-500">Available</p>
                                                </div>
                                                <div class="text-center min-w-[60px]">
                                                    <p class="text-lg font-bold text-wwc-accent">{{ number_format($ticketType->sold_seats) }}</p>
                                                    <p class="text-xs text-wwc-neutral-500">Sold</p>
                                                </div>
                                                <div class="text-center min-w-[60px]">
                                                    <p class="text-lg font-bold text-wwc-info">{{ number_format($ticketType->total_seats) }}</p>
                                                    <p class="text-xs text-wwc-neutral-500">Total</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="h-12 w-12 rounded-full bg-wwc-neutral-100 flex items-center justify-center mx-auto mb-3">
                                        <i class='bx bx-purchase-tag text-xl text-wwc-neutral-400'></i>
                                    </div>
                                    <h4 class="text-sm font-semibold text-wwc-neutral-900 mb-1">No Ticket Types</h4>
                                    <p class="text-xs text-wwc-neutral-600">This event doesn't have any ticket types configured yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Quick Stats</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-bar-chart text-sm'></i>
                                    <span>Statistics</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                        <i class='bx bx-check-circle text-sm text-green-600'></i>
                                    </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Tickets Sold</span>
                                </div>
                                <span class="text-base font-medium text-wwc-neutral-900">{{ \App\Models\PurchaseTicket::where('event_id', $event->id)->whereIn('status', ['sold', 'active', 'scanned'])->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i class='bx bx-dollar text-sm text-blue-600'></i>
                                    </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Revenue</span>
                                </div>
                                <span class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($event->purchaseTickets()->whereIn('status', ['sold', 'active', 'scanned'])->sum('price_paid'), 0) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                        <i class='bx bx-group text-sm text-orange-600'></i>
                                    </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Capacity</span>
                                </div>
                                <span class="text-base font-medium text-wwc-neutral-900">{{ number_format($event->total_seats) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Actions</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-cog text-sm'></i>
                                    <span>Quick actions</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('admin.events.edit', $event) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-edit text-sm mr-2'></i>
                                Edit Event
                            </a>
                            
                            @if($event->status === 'draft')
                                <form action="{{ route('admin.events.change-status', $event) }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="status" value="on_sale">
                                    <button type="submit" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-success text-white text-sm font-semibold rounded-lg hover:bg-wwc-success-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-success transition-colors duration-200">
                                        <i class='bx bx-play text-sm mr-2'></i>
                                        Go On Sale
                                    </button>
                                </form>
                            @elseif($event->status === 'on_sale')
                                <form action="{{ route('admin.events.change-status', $event) }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="status" value="sold_out">
                                    <button type="submit" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-warning text-white text-sm font-semibold rounded-lg hover:bg-wwc-warning-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-warning transition-colors duration-200">
                                        <i class='bx bx-pause text-sm mr-2'></i>
                                        Mark as Sold Out
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection