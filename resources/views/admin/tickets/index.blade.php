@extends('layouts.admin')

@section('title', 'Ticket Management')
@section('page-title', 'Tickets')

@section('content')
<!-- Professional Ticket Management with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display">Ticket Management</h1>
                    <p class="mt-1 text-sm text-wwc-neutral-600 font-medium">View and manage all tickets</p>
                </div>
            </div>

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
                        <div class="h-12 w-12 rounded-lg bg-wwc-primary-light flex items-center justify-center">
                            <i class='bx bx-receipt text-2xl text-wwc-primary'></i>
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
                                    <i class='bx bx-check text-xs mr-1'></i>
                                    {{ $tickets->where('status', 'Held')->count() }} Held
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-success-light flex items-center justify-center">
                            <i class='bx bx-check-circle text-2xl text-wwc-success'></i>
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
                                <div class="flex items-center text-xs text-wwc-accent font-semibold">
                                    <i class='bx bx-qr-scan text-xs mr-1'></i>
                                    {{ $tickets->where('status', 'Cancelled')->count() }} Cancelled
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-accent-light flex items-center justify-center">
                            <i class='bx bx-qr-scan text-2xl text-wwc-accent'></i>
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
                                <div class="flex items-center text-xs text-wwc-accent font-semibold">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    +8% this month
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-accent-light flex items-center justify-center">
                            <i class='bx bx-dollar text-2xl text-wwc-accent'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white shadow-sm rounded-2xl border border-wwc-neutral-200 mb-6">
                <div class="px-6 py-4 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                    <h2 class="text-lg font-semibold text-wwc-neutral-900 font-display">Search & Filter Tickets</h2>
                    <p class="text-wwc-neutral-600 text-sm">Find specific tickets using the filters below</p>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.tickets.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                        <div>
                            <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search Tickets</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Ticket ID, QR code..."
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Status</label>
                            <select name="status" id="status" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Status</option>
                                <option value="Sold" {{ request('status') == 'Sold' ? 'selected' : '' }}>Sold</option>
                                <option value="Held" {{ request('status') == 'Held' ? 'selected' : '' }}>Held</option>
                                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="Used" {{ request('status') == 'Used' ? 'selected' : '' }}>Used</option>
                            </select>
                        </div>
                        <div>
                            <label for="event" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Event</label>
                            <select name="event" id="event" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Events</option>
                                @foreach(\App\Models\Event::all() as $event)
                                    <option value="{{ $event->id }}" {{ request('event') == $event->id ? 'selected' : '' }}>
                                        {{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="price_zone" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Price Zone</label>
                            <select name="price_zone" id="price_zone" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Price Zones</option>
                                @foreach(\App\Models\PriceZone::active()->ordered()->get() as $zone)
                                    <option value="{{ $zone->name }}" {{ request('price_zone') == $zone->name ? 'selected' : '' }}>
                                        {{ $zone->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-2xl text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-search text-sm mr-2'></i>
                                Filter Tickets
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tickets Table -->
            <div class="bg-white shadow-sm rounded-2xl border border-wwc-neutral-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-wwc-neutral-900 font-display">All Tickets</h2>
                            <p class="text-wwc-neutral-600 text-sm">Showing {{ $tickets->count() }} of {{ $tickets->total() }} tickets</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="text-xs text-wwc-neutral-600">
                                <span class="font-semibold">{{ $tickets->where('status', 'Sold')->count() }}</span> Sold
                            </div>
                            <div class="text-xs text-wwc-neutral-600">
                                <span class="font-semibold">{{ $tickets->where('status', 'Held')->count() }}</span> Held
                            </div>
                            <div class="text-xs text-wwc-neutral-600">
                                <span class="font-semibold">{{ $tickets->where('status', 'Used')->count() }}</span> Used
                            </div>
                            <div class="text-xs text-wwc-neutral-600">
                                <span class="font-semibold">{{ $tickets->where('status', 'Cancelled')->count() }}</span> Cancelled
                            </div>
                        </div>
                    </div>
                </div>
                @if($tickets->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-wwc-neutral-200">
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-wwc-neutral-200">
                                @foreach($tickets as $ticket)
                                <tr class="hover:bg-wwc-neutral-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-2xl bg-wwc-primary-light flex items-center justify-center shadow-sm">
                                                    <i class='bx bx-receipt text-lg text-wwc-primary'></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-wwc-neutral-900 font-display">#{{ $ticket->id }}</div>
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
                                                    <div class="h-8 w-8 rounded-2xl bg-wwc-accent-light flex items-center justify-center">
                                                        <span class="text-xs font-semibold text-wwc-accent">
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
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            @if($ticket->status === 'Sold') bg-wwc-success text-white
                                            @elseif($ticket->status === 'Held') bg-wwc-warning text-white
                                            @elseif($ticket->status === 'Cancelled') bg-wwc-error text-white
                                            @elseif($ticket->status === 'Used') bg-wwc-info text-white
                                            @else bg-wwc-neutral-200 text-wwc-neutral-800
                                            @endif">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.tickets.show', $ticket) }}" 
                                           class="inline-flex items-center px-2 py-1 text-xs font-semibold text-wwc-primary hover:bg-wwc-primary-light hover:text-wwc-primary-dark rounded-2xl transition-colors duration-200">
                                            <i class='bx bx-show text-xs mr-1'></i>
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-wwc-neutral-200 bg-wwc-neutral-50">
                        {{ $tickets->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class='bx bx-receipt text-6xl text-wwc-neutral-300 mb-4'></i>
                        <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">No Tickets Found</h3>
                        <p class="text-wwc-neutral-600 mb-6">No tickets match your current filters.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
