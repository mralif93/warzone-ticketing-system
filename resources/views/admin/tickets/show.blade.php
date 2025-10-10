@extends('layouts.admin')

@section('title', 'Ticket Details')
@section('page-title', 'Ticket Details')

@section('content')
<!-- Professional Ticket Details with WWC Brand Design -->
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
                        Back to Tickets
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Ticket Details -->
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Ticket Details</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-info-circle text-sm'></i>
                                    <span>Ticket information</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Ticket ID -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-receipt text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Ticket ID</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">#{{ $ticket->id }}</span>
                                    </div>
                                </div>

                                <!-- QR Code -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-qr-scan text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">QR Code</span>
                                        <span class="text-base font-medium text-wwc-neutral-900 font-mono">{{ $ticket->qrcode }}</span>
                                    </div>
                                </div>

                                <!-- Event -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                            <i class='bx bx-calendar text-sm text-orange-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Event</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $ticket->event->name }}</span>
                                    </div>
                                </div>

                                <!-- Seat -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-chair text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Seat</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $ticket->seat->row }}{{ $ticket->seat->number }}</span>
                                    </div>
                                </div>

                                <!-- Price Zone -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-red-100 flex items-center justify-center">
                                            <i class='bx bx-tag text-sm text-red-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Price Zone</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $ticket->seat->price_zone }}</span>
                                    </div>
                                </div>

                                <!-- Price Paid -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <i class='bx bx-dollar text-sm text-gray-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Price Paid</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($ticket->price_paid, 0) }}</span>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-check-circle text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Status</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $ticket->status }}</span>
                                    </div>
                                </div>

                                <!-- Customer -->
                                @if($ticket->order && $ticket->order->user)
                                <div class="flex items-center py-3">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                            <i class='bx bx-user text-sm text-indigo-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Customer</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $ticket->order->user->name }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ticket Statistics -->
                <div class="xl:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Ticket Statistics</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-bar-chart text-sm'></i>
                                    <span>Ticket overview</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <!-- Created Date -->
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-wwc-neutral-900 font-display">{{ $ticket->created_at->format('M j') }}</div>
                                    <div class="text-sm text-wwc-neutral-600 font-medium">Created</div>
                                </div>

                                <!-- Order ID -->
                                @if($ticket->order)
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-wwc-neutral-900 font-display">#{{ $ticket->order->id }}</div>
                                    <div class="text-sm text-wwc-neutral-600 font-medium">Order ID</div>
                                </div>
                                @endif

                                <!-- Event Date -->
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-wwc-neutral-900 font-display">{{ $ticket->event->date_time->format('M j') }}</div>
                                    <div class="text-sm text-wwc-neutral-600 font-medium">Event Date</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Quick Actions</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-cog text-sm'></i>
                                    <span>Ticket actions</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <a href="{{ route('admin.tickets.edit', $ticket) }}" 
                                   class="w-full bg-wwc-primary hover:bg-wwc-primary-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                    <i class='bx bx-edit text-sm mr-2'></i>
                                    Edit Ticket
                                </a>
                                
                                @if($ticket->status === 'Sold')
                                    <form action="{{ route('admin.tickets.mark-used', $ticket) }}" method="POST" class="block">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                            <i class='bx bx-qr-scan text-sm mr-2'></i>
                                            Mark as Used
                                        </button>
                                    </form>
                                @elseif($ticket->status === 'Held')
                                    <form action="{{ route('admin.tickets.update-status', $ticket) }}" method="POST" class="block">
                                        @csrf
                                        <input type="hidden" name="status" value="Sold">
                                        <button type="submit" 
                                                class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                            <i class='bx bx-check text-sm mr-2'></i>
                                            Mark as Sold
                                        </button>
                                    </form>
                                @endif

                                @if($ticket->order)
                                    <a href="{{ route('admin.orders.show', $ticket->order) }}" 
                                       class="w-full bg-wwc-neutral-600 hover:bg-wwc-neutral-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                        <i class='bx bx-receipt text-sm mr-2'></i>
                                        View Order
                                    </a>
                                @endif

                                @if($ticket->status !== 'Used')
                                    <form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this ticket? This action cannot be undone.')" 
                                          class="block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                            <i class='bx bx-trash text-sm mr-2'></i>
                                            Delete Ticket
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
</div>
@endsection