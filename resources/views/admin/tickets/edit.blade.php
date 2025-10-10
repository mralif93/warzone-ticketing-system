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
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
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

                                <!-- Seat -->
                                <div>
                                    <label for="seat_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Seat <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="seat_id" id="seat_id" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('seat_id') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select a seat</option>
                                        @foreach($seats as $seat)
                                            <option value="{{ $seat->id }}" {{ old('seat_id', $ticket->seat_id) == $seat->id ? 'selected' : '' }}>
                                                {{ $seat->row }}{{ $seat->number }} - {{ $seat->price_zone }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('seat_id')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Order -->
                                <div>
                                    <label for="order_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Order <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="order_id" id="order_id" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('order_id') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select an order</option>
                                        @foreach($orders as $order)
                                            <option value="{{ $order->id }}" {{ old('order_id', $ticket->order_id) == $order->id ? 'selected' : '' }}>
                                                #{{ $order->id }} - {{ $order->user->name }} - RM{{ number_format($order->total_amount, 0) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('order_id')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Price Paid -->
                                <div>
                                    <label for="price_paid" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Price Paid <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="number" name="price_paid" id="price_paid" required step="0.01" min="0"
                                           value="{{ old('price_paid', $ticket->price_paid) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('price_paid') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter price paid">
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
                                        <option value="Used" {{ old('status', $ticket->status) == 'Used' ? 'selected' : '' }}>Used</option>
                                        <option value="Cancelled" {{ old('status', $ticket->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- QR Code -->
                                <div class="sm:col-span-2">
                                    <label for="qrcode" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        QR Code
                                    </label>
                                    <input type="text" name="qrcode" id="qrcode" 
                                           value="{{ old('qrcode', $ticket->qrcode) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('qrcode') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter QR code">
                                    @error('qrcode')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>
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
@endsection
