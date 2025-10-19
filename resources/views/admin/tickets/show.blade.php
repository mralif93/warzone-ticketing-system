@extends('layouts.admin')

@section('title', 'Ticket Type Details - ' . $ticket->name)
@section('page-title', 'Ticket Type Details')

@section('content')
<!-- Professional Ticket Type Details with WWC Brand Design -->
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

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <!-- Ticket Type Details -->
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

                            <!-- Price -->
                            <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                        <i class='bx bx-money text-sm text-green-600'></i>
                                    </div>
                                </div>
                                <div class="flex-1 flex items-center justify-between">
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Price</span>
                                    <span class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($ticket->price, 0) }}</span>
                                </div>
                            </div>

                            <!-- Total Seats -->
                            <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                        <i class='bx bx-group text-sm text-purple-600'></i>
                                    </div>
                                </div>
                                <div class="flex-1 flex items-center justify-between">
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Total Seats</span>
                                    <span class="text-base font-medium text-wwc-neutral-900">{{ $ticket->total_seats }}</span>
                                </div>
                            </div>

                            <!-- Available Seats -->
                            <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="h-8 w-8 rounded-lg bg-yellow-100 flex items-center justify-center">
                                        <i class='bx bx-check-circle text-sm text-yellow-600'></i>
                                    </div>
                                </div>
                                <div class="flex-1 flex items-center justify-between">
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Available Seats</span>
                                    <span class="text-base font-medium {{ $ticket->available_seats > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $ticket->available_seats }}</span>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i class='bx bx-info-circle text-sm text-blue-600'></i>
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
                                    <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                        <i class='bx bx-package text-sm text-orange-600'></i>
                                    </div>
                                </div>
                                <div class="flex-1 flex items-center justify-between">
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Combo Ticket</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $ticket->is_combo ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $ticket->is_combo ? 'Yes' : 'No' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Scanned Seats -->
                            <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                        <i class='bx bx-scan text-sm text-indigo-600'></i>
                                    </div>
                                </div>
                                <div class="flex-1 flex items-center justify-between">
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Scanned Seats</span>
                                    <span class="text-base font-medium text-wwc-neutral-900">{{ $ticket->scanned_seats }}</span>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($ticket->description)
                            <div class="py-3">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <i class='bx bx-detail text-sm text-gray-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-semibold text-wwc-neutral-600 block mb-2">Description</span>
                                        <p class="text-sm text-wwc-neutral-700">{{ $ticket->description }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Ticket Type Statistics -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Statistics</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-bar-chart text-sm'></i>
                                    <span>Ticket type overview</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <!-- Sold Seats -->
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-green-600 font-display">{{ $ticket->sold_seats }}</div>
                                    <div class="text-sm text-wwc-neutral-600 font-medium">Sold Seats</div>
                                </div>

                                <!-- Available Seats -->
                                <div class="text-center">
                                    <div class="text-3xl font-bold {{ $ticket->available_seats > 0 ? 'text-blue-600' : 'text-red-600' }} font-display">{{ $ticket->available_seats }}</div>
                                    <div class="text-sm text-wwc-neutral-600 font-medium">Available Seats</div>
                                </div>

                                <!-- Scanned Seats -->
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-indigo-600 font-display">{{ $ticket->scanned_seats }}</div>
                                    <div class="text-sm text-wwc-neutral-600 font-medium">Scanned Seats</div>
                                </div>

                                <!-- Occupancy Rate -->
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-purple-600 font-display">{{ $ticket->getOccupancyPercentage() }}%</div>
                                    <div class="text-sm text-wwc-neutral-600 font-medium">Occupancy Rate</div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-500 to-blue-500 h-2 rounded-full transition-all duration-500" 
                                         style="width: {{ $ticket->getOccupancyPercentage() }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Quick Actions</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-cog text-sm'></i>
                                    <span>Ticket type actions</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <a href="{{ route('admin.tickets.edit', $ticket) }}" 
                                   class="w-full bg-wwc-primary hover:bg-wwc-primary-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                    <i class='bx bx-edit text-sm mr-2'></i>
                                    Edit Ticket Type
                                </a>
                                
                                @if($ticket->sold_seats == 0)
                                    <button onclick="confirmDelete('{{ $ticket->id }}', '{{ $ticket->name }}')" 
                                            class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                        <i class='bx bx-trash text-sm mr-2'></i>
                                        Delete Ticket Type
                                    </button>
                                @else
                                    <div class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-center text-sm font-medium">
                                        <i class='bx bx-info-circle text-sm mr-2'></i>
                                        Cannot delete - has sold tickets
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Type Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Additional Information</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-info-circle text-sm'></i>
                                    <span>Ticket type details</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Created Date -->
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Created</span>
                                    <span class="text-sm text-wwc-neutral-900">{{ $ticket->created_at->format('M j, Y g:i A') }}</span>
                                </div>
                                
                                <!-- Last Updated -->
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Last Updated</span>
                                    <span class="text-sm text-wwc-neutral-900">{{ $ticket->updated_at->format('M j, Y g:i A') }}</span>
                                </div>
                                
                                <!-- Event Date -->
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Event Date</span>
                                    <span class="text-sm text-wwc-neutral-900">{{ $ticket->event->date_time->format('M j, Y g:i A') }}</span>
                                </div>
                                
                                <!-- Event Status -->
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Event Status</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($ticket->event->status === 'on_sale') bg-green-100 text-green-800
                                        @elseif($ticket->event->status === 'sold_out') bg-red-100 text-red-800
                                        @elseif($ticket->event->status === 'Cancelled') bg-gray-100 text-gray-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $ticket->event->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class='bx bx-trash text-2xl text-red-600'></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Ticket Type</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete the ticket type "<span id="deleteTicketName" class="font-semibold text-gray-900"></span>"?
                </p>
                <p class="text-xs text-red-600 mt-2">
                    This action cannot be undone.
                </p>
            </div>
            <div class="flex justify-center space-x-3 mt-4">
                <button onclick="closeDeleteModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(ticketId, ticketName) {
    document.getElementById('deleteTicketName').textContent = ticketName;
    document.getElementById('deleteForm').action = `/admin/tickets/${ticketId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection