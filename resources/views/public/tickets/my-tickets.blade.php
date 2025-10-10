@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Tickets</h1>
                    <p class="mt-1 text-sm text-gray-600">View and manage your purchased tickets</p>
                </div>
                <div>
                    <a href="{{ route('events.index') }}" 
                       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition duration-150 ease-in-out">
                        Browse Events
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets List -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            @if($tickets->count() > 0)
                <div class="space-y-6">
                    @foreach($tickets as $ticket)
                        <div class="bg-white shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="h-16 w-16 bg-indigo-100 rounded-lg flex items-center justify-center">
                                                <i class='bx bx-receipt text-3xl text-indigo-600'></i>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-medium text-gray-900">{{ $ticket->event->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $ticket->event->date_time->format('M j, Y \a\t g:i A') }} • {{ $ticket->event->venue }}</p>
                                            <p class="text-sm text-gray-500">Seat: {{ $ticket->seat_identifier }} • {{ $ticket->seat->price_zone }}</p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-indigo-600">RM{{ number_format($ticket->price_paid, 0) }}</div>
                                            <div class="text-sm text-gray-500">Ticket #{{ $ticket->id }}</div>
                                            <div class="mt-1">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($ticket->status === 'Sold') bg-green-100 text-green-800
                                                    @elseif($ticket->status === 'Held') bg-yellow-100 text-yellow-800
                                                    @elseif($ticket->status === 'Scanned') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ $ticket->status }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('public.tickets.show', $ticket) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $tickets->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class='bx bx-receipt text-4xl text-gray-400 mx-auto'></i>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No tickets found</h3>
                    <p class="mt-1 text-sm text-gray-500">You haven't purchased any tickets yet.</p>
                    <div class="mt-6">
                        <a href="{{ route('events.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Browse Events
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
