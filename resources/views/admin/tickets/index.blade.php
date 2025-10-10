@extends('layouts.admin')

@section('title', 'Ticket Management')
@section('page-subtitle', 'Manage all tickets and sales')

@section('content')
<!-- Professional Ticket Management with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">
    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <!-- Total Tickets -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $tickets->total() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Tickets</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    {{ $tickets->where('status', 'Sold')->count() }} Sold
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-receipt text-2xl text-blue-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Sold Tickets -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $tickets->where('status', 'Sold')->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Sold Tickets</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <i class='bx bx-time text-xs mr-1'></i>
                                    {{ $tickets->where('status', 'Held')->count() }} Held
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class='bx bx-check-circle text-2xl text-green-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Used Tickets -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $tickets->where('status', 'Used')->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Used Tickets</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-error font-semibold">
                                    <i class='bx bx-x text-xs mr-1'></i>
                                    {{ $tickets->where('status', 'Cancelled')->count() }} Cancelled
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-orange-100 flex items-center justify-center">
                            <i class='bx bx-qr-scan text-2xl text-orange-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">RM{{ number_format($tickets->where('status', 'Sold')->sum('price_paid'), 0) }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Revenue</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <i class='bx bx-trending-up text-xs mr-1'></i>
                                    {{ $tickets->where('status', 'Sold')->count() > 0 ? number_format(($tickets->where('status', 'Sold')->sum('price_paid') / $tickets->where('status', 'Sold')->count()), 0) : 0 }} Avg
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-purple-100 flex items-center justify-center">
                            <i class='bx bx-dollar text-2xl text-purple-600'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Search & Filter Tickets</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-search text-sm'></i>
                            <span>Find specific tickets</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search Tickets</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Ticket ID, QR code..."
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Status</label>
                            <select name="status" id="status" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Statuses</option>
                                <option value="Sold" {{ request('status') == 'Sold' ? 'selected' : '' }}>Sold</option>
                                <option value="Held" {{ request('status') == 'Held' ? 'selected' : '' }}>Held</option>
                                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="Used" {{ request('status') == 'Used' ? 'selected' : '' }}>Used</option>
                            </select>
                        </div>
                        <div>
                            <label for="event_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Event</label>
                            <select name="event_id" id="event_id" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Events</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="price_zone" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Price Zone</label>
                            <select name="price_zone" id="price_zone" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Price Zones</option>
                                @foreach($priceZones as $zone)
                                    <option value="{{ $zone }}" {{ request('price_zone') == $zone ? 'selected' : '' }}>
                                        {{ $zone }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2 lg:col-span-4 flex justify-end gap-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-search text-sm mr-2'></i>
                                Search Tickets
                            </button>
                            <a href="{{ route('admin.tickets.index') }}" 
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
                <a href="{{ route('admin.tickets.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-plus text-sm mr-2'></i>
                    Create New Ticket
                </a>
            </div>

            <!-- Tickets List -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Tickets List</h3>
                        <div class="flex items-center space-x-4 text-xs text-wwc-neutral-500">
                            <span class="flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                {{ $tickets->where('status', 'Sold')->count() }} Sold
                            </span>
                            <span class="flex items-center">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                {{ $tickets->where('status', 'Held')->count() }} Held
                            </span>
                            <span class="flex items-center">
                                <div class="w-2 h-2 bg-orange-500 rounded-full mr-2"></div>
                                {{ $tickets->where('status', 'Used')->count() }} Used
                            </span>
                            <span class="flex items-center">
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                {{ $tickets->where('status', 'Cancelled')->count() }} Cancelled
                            </span>
                        </div>
                    </div>
                </div>

                @if($tickets->count() > 0)
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-wwc-neutral-100">
                            <thead class="bg-wwc-neutral-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Ticket
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Event
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Seat
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Customer
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Price
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-center">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-right">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-wwc-neutral-100">
                                @foreach($tickets as $ticket)
                                <tr class="hover:bg-wwc-neutral-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                                    <i class='bx bx-receipt text-lg text-blue-600'></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-wwc-neutral-900">#{{ $ticket->id }}</div>
                                                <div class="text-xs text-wwc-neutral-500">{{ Str::limit($ticket->qrcode, 20) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-wwc-neutral-900">{{ $ticket->event->name }}</div>
                                        <div class="text-xs text-wwc-neutral-500">{{ $ticket->event->date_time->format('M j, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-wwc-neutral-900">{{ $ticket->seat->row }}{{ $ticket->seat->number }}</div>
                                        <div class="text-xs text-wwc-neutral-500">{{ $ticket->seat->price_zone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($ticket->order && $ticket->order->user)
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                                        <span class="text-xs font-semibold text-gray-600">
                                                            {{ substr($ticket->order->user->name, 0, 1) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-semibold text-wwc-neutral-900">{{ $ticket->order->user->name }}</div>
                                                    <div class="text-xs text-wwc-neutral-500">{{ $ticket->order->user->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-sm text-wwc-neutral-500">No customer</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-wwc-neutral-900">RM{{ number_format($ticket->price_paid, 0) }}</div>
                                        <div class="text-xs text-wwc-neutral-500">{{ $ticket->seat->price_zone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            @if($ticket->status === 'Sold') bg-green-100 text-green-800
                                            @elseif($ticket->status === 'Held') bg-yellow-100 text-yellow-800
                                            @elseif($ticket->status === 'Used') bg-orange-100 text-orange-800
                                            @elseif($ticket->status === 'Cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-1">
                                            <a href="{{ route('admin.tickets.show', $ticket) }}" 
                                               class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                               title="View ticket details">
                                                <i class='bx bx-show text-xs mr-1.5'></i>
                                                View
                                            </a>
                                            <a href="{{ route('admin.tickets.edit', $ticket) }}" 
                                               class="inline-flex items-center px-3 py-2 text-xs font-semibold text-wwc-neutral-700 bg-white border border-wwc-neutral-300 hover:bg-wwc-neutral-50 hover:border-wwc-neutral-400 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                               title="Edit ticket">
                                                <i class='bx bx-edit text-xs mr-1.5'></i>
                                                Edit
                                            </a>
                                            <div class="relative" x-data="{ open{{ $ticket->id }}: false }">
                                                <button @click="open{{ $ticket->id }} = !open{{ $ticket->id }}" 
                                                        class="inline-flex items-center px-3 py-2 text-xs font-semibold text-wwc-neutral-700 bg-white border border-wwc-neutral-300 hover:bg-wwc-neutral-50 hover:border-wwc-neutral-400 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                                        title="More actions">
                                                    <i class='bx bx-dots-vertical text-xs mr-1.5'></i>
                                                    More
                                                </button>
                                                <div x-show="open{{ $ticket->id }}" 
                                                     @click.away="open{{ $ticket->id }} = false"
                                                     x-transition:enter="transition ease-out duration-100"
                                                     x-transition:enter-start="transform opacity-0 scale-95"
                                                     x-transition:enter-end="transform opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="transform opacity-100 scale-100"
                                                     x-transition:leave-end="transform opacity-0 scale-95"
                                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-wwc-neutral-200 z-10"
                                                     style="display: none;">
                                                    <div class="py-1">
                                                        @if($ticket->status === 'Sold')
                                                            <form action="{{ route('admin.tickets.mark-used', $ticket) }}" method="POST" class="block">
                                                                @csrf
                                                                <button type="submit" 
                                                                        class="flex items-center w-full px-4 py-2 text-xs text-wwc-neutral-700 hover:bg-orange-50 transition-colors duration-200">
                                                                    <i class='bx bx-qr-scan text-xs mr-2'></i>
                                                                    Mark as Used
                                                                </button>
                                                            </form>
                                                        @elseif($ticket->status === 'Held')
                                                            <form action="{{ route('admin.tickets.update-status', $ticket) }}" method="POST" class="block">
                                                                @csrf
                                                                <input type="hidden" name="status" value="Sold">
                                                                <button type="submit" 
                                                                        class="flex items-center w-full px-4 py-2 text-xs text-wwc-neutral-700 hover:bg-green-50 transition-colors duration-200">
                                                                    <i class='bx bx-check text-xs mr-2'></i>
                                                                    Mark as Sold
                                                                </button>
                                                            </form>
                                                        @endif
                                                        @if($ticket->order)
                                                            <a href="{{ route('admin.orders.show', $ticket->order) }}" 
                                                               class="flex items-center px-4 py-2 text-xs text-wwc-neutral-700 hover:bg-wwc-neutral-100 transition-colors duration-200">
                                                                <i class='bx bx-receipt text-xs mr-2'></i>
                                                                View Order
                                                            </a>
                                                        @endif
                                                        <div class="border-t border-wwc-neutral-100 my-1"></div>
                                                        <form action="{{ route('admin.tickets.cancel', $ticket) }}" method="POST" 
                                                              onsubmit="return confirm('Are you sure you want to cancel this ticket? This action cannot be undone.')" 
                                                              class="block">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="flex items-center w-full px-4 py-2 text-xs text-red-600 hover:bg-red-50 transition-colors duration-200">
                                                                <i class='bx bx-x text-xs mr-2'></i>
                                                                Cancel Ticket
                                                            </button>
                                                        </form>
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
                        {{ $tickets->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div class="mx-auto h-24 w-24 rounded-full bg-wwc-neutral-100 flex items-center justify-center mb-4">
                            <i class='bx bx-receipt text-6xl text-wwc-neutral-300'></i>
                        </div>
                        <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">No tickets found</h3>
                        <p class="text-wwc-neutral-600 mb-6">Get started by creating your first ticket or adjust your search criteria.</p>
                        <a href="{{ route('admin.tickets.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <i class='bx bx-plus text-sm mr-2'></i>
                            Create New Ticket
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection