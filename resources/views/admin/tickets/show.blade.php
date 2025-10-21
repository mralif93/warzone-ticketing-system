@extends('layouts.admin')

@section('title', 'Ticket Type Details - ' . $ticket->name)
@section('page-title', 'Ticket Type Details')

@section('content')
<div class="min-h-screen bg-wwc-neutral-50">
    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <div class="flex items-center">
                    <a href="{{ route('admin.tickets.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Ticket Types
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Ticket Type Details -->
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Ticket Type Details</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-info-circle text-sm'></i>
                                    <span>Ticket type information</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Ticket Type Name -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-ticket text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Ticket Type</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $ticket->name }}</span>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-dollar text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Price</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($ticket->price, 0) }}</span>
                                    </div>
                                </div>

                                <!-- Event -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-calendar text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Event</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $ticket->event->name }}</span>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                            <i class='bx bx-check-circle text-sm text-orange-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Status</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($ticket->status === 'active') bg-green-100 text-green-800
                                            @elseif($ticket->status === 'sold_out') bg-red-100 text-red-800
                                            @elseif($ticket->status === 'inactive') bg-gray-100 text-gray-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Combo Ticket Type -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-wwc-accent/20 flex items-center justify-center">
                                            <i class='bx bx-package text-sm text-wwc-accent'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Combo Ticket</span>
                                        @if($ticket->is_combo)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-wwc-accent/20 text-wwc-accent border border-wwc-accent/30">
                                                <i class='bx bx-check text-xs mr-1'></i>
                                                Yes
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                <i class='bx bx-x text-xs mr-1'></i>
                                                No
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Description -->
                                @if($ticket->description)
                                <div class="flex items-center py-3">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <i class='bx bx-file text-sm text-gray-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Description</span>
                                        <p class="text-base font-medium text-wwc-neutral-900 mt-1">{{ $ticket->description }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Statistics -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Statistics</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-bar-chart text-sm'></i>
                                    <span>Ticket statistics</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                        <i class='bx bx-check-circle text-sm text-green-600'></i>
                                    </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Sold</span>
                                </div>
                                <span class="text-base font-medium text-wwc-neutral-900">{{ number_format($ticket->sold_seats) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i class='bx bx-group text-sm text-blue-600'></i>
                                    </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Available</span>
                                </div>
                                <span class="text-base font-medium text-wwc-neutral-900">{{ number_format($ticket->available_seats) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                        <i class='bx bx-layer text-sm text-purple-600'></i>
                                    </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Total Capacity</span>
                                </div>
                                <span class="text-base font-medium text-wwc-neutral-900">{{ number_format($ticket->total_seats) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Revenue</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-dollar text-sm'></i>
                                    <span>Total revenue</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="text-center">
                                <div class="h-12 w-12 rounded-lg bg-wwc-accent/20 flex items-center justify-center mx-auto mb-3">
                                    <i class='bx bx-dollar text-lg text-wwc-accent'></i>
                                </div>
                                <p class="text-2xl font-bold text-wwc-neutral-900">RM{{ number_format($ticket->sold_seats * $ticket->price, 0) }}</p>
                                <p class="text-sm text-wwc-neutral-600 mt-1">Total Revenue Generated</p>
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
                            <a href="{{ route('admin.tickets.edit', $ticket) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-edit text-sm mr-2'></i>
                                Edit Ticket Type
                            </a>
                            
                            <a href="{{ route('admin.events.show', $ticket->event) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-info text-white text-sm font-semibold rounded-lg hover:bg-wwc-info-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-info transition-colors duration-200">
                                <i class='bx bx-calendar text-sm mr-2'></i>
                                View Event
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection