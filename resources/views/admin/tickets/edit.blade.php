@extends('layouts.admin')

@section('title', 'Edit Ticket')
@section('page-title', 'Edit Ticket')

@section('content')
<!-- Professional Ticket Editing with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <a href="{{ route('admin.tickets.show', $ticket) }}" 
                    class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                    Back to Ticket
                </a>
            </div>

            <!-- Form Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Edit Ticket Details</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-edit text-sm'></i>
                            <span>Update ticket information</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        @if ($errors->any())
                            <div class="bg-wwc-error-light border border-wwc-error text-wwc-error px-4 py-3 rounded-lg mb-6">
                                <div class="flex items-start">
                                    <i class='bx bx-error text-lg mr-3 mt-0.5 flex-shrink-0'></i>
                                    <div>
                                        <h3 class="font-semibold mb-2 text-sm">Please correct the following errors:</h3>
                                        <ul class="list-disc list-inside space-y-1 text-sm">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-1">
                                <!-- Event -->
                                <div>
                                    <label for="event_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Event <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="event_id" id="event_id" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('event_id') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select an event</option>
                                        @foreach($events as $event)
                                            <option value="{{ $event->id }}" {{ old('event_id', $ticket->event_id) == $event->id ? 'selected' : '' }}>
                                                {{ $event->name }} - {{ $event->date_time->format('M j, Y') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('event_id')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Zone Name -->
                                <div>
                                    <label for="zone" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Zone Name <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="text" name="zone" id="zone" required
                                           value="{{ old('zone', $ticket->zone) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('zone') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter zone name (e.g., Warzone VIP, Level 1 Zone A, etc.)">
                                    <p class="text-xs text-wwc-neutral-500 mt-1">Enter the zone name for this ticket category</p>
                                    @error('zone')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Price Paid -->
                                <div>
                                    <label for="price_paid" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Price Paid <span class="text-wwc-error">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-wwc-neutral-500 text-sm">RM</span>
                                        </div>
                                        <input type="number" name="price_paid" id="price_paid" required step="0.01" min="0"
                                               value="{{ old('price_paid', $ticket->price_paid) }}"
                                               class="block w-full pl-10 pr-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('price_paid') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                               placeholder="0.00">
                                    </div>
                                    <p class="text-xs text-wwc-neutral-500 mt-1">Enter the price paid for this ticket</p>
                                    @error('price_paid')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Status <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="status" id="status" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('status') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select status</option>
                                        <option value="Sold" {{ old('status', $ticket->status) == 'Sold' ? 'selected' : '' }}>Sold</option>
                                        <option value="Held" {{ old('status', $ticket->status) == 'Held' ? 'selected' : '' }}>Held</option>
                                        <option value="Scanned" {{ old('status', $ticket->status) == 'Scanned' ? 'selected' : '' }}>Scanned</option>
                                        <option value="Invalid" {{ old('status', $ticket->status) == 'Invalid' ? 'selected' : '' }}>Invalid</option>
                                        <option value="Refunded" {{ old('status', $ticket->status) == 'Refunded' ? 'selected' : '' }}>Refunded</option>
                                    </select>
                                    @error('status')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- QR Code -->
                                <div>
                                    <label for="qrcode" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        QR Code
                                    </label>
                                    <input type="text" name="qrcode" id="qrcode" 
                                           value="{{ old('qrcode', $ticket->qrcode) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('qrcode') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter QR code">
                                    <p class="text-xs text-wwc-neutral-500 mt-1">Unique QR code for this ticket</p>
                                    @error('qrcode')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Scanned At (Read-only if scanned) -->
                                @if($ticket->scanned_at)
                                <div>
                                    <label class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Scanned At
                                    </label>
                                    <div class="px-3 py-2 bg-wwc-neutral-50 border border-wwc-neutral-200 rounded-lg text-sm text-wwc-neutral-700">
                                        {{ $ticket->scanned_at->format('M j, Y g:i A') }}
                                    </div>
                                    <p class="text-xs text-wwc-neutral-500 mt-1">This ticket has been scanned for entry</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-wwc-neutral-200 mt-6">
                            <a href="{{ route('admin.tickets.show', $ticket) }}" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-check text-sm mr-2'></i>
                                Update Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const zoneSelect = document.getElementById('zone');
    const priceInput = document.getElementById('price_paid');
    
    // Zone pricing mapping
    const zonePrices = {
        'Warzone Exclusive': 350.00,
        'Warzone VIP': 250.00,
        'Warzone Grandstand': 220.00,
        'Warzone Premium Ringside': 199.00,
        'Level 1 Zone A/B/C/D': 129.00,
        'Level 2 Zone A/B/C/D': 89.00,
        'Standing Zone A/B': 49.00
    };
    
    // Auto-populate price when zone changes
    zoneSelect.addEventListener('change', function() {
        const selectedZone = this.value;
        if (selectedZone && zonePrices[selectedZone]) {
            priceInput.value = zonePrices[selectedZone];
        }
    });
    
    // Auto-populate price on page load if zone is already selected
    if (zoneSelect.value && zonePrices[zoneSelect.value]) {
        priceInput.value = zonePrices[zoneSelect.value];
    }
});
</script>
@endsection
