@extends('layouts.public')

@section('title', 'Purchase Successful - ' . $event->name)
@section('description', 'Your ticket purchase has been completed successfully. Thank you for choosing Warzone World Championship!')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Success Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-green-500 rounded-full mb-6 shadow-lg">
                <i class="bx bx-check text-4xl text-white"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Purchase Successful!</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Your tickets have been confirmed and are ready for use. Thank you for choosing Warzone World Championship!</p>
        </div>

        <!-- Success Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8">
            <div class="bg-green-600 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                            <i class="bx bx-check text-2xl text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-1">Order Confirmed</h2>
                            <p class="text-white/90 text-sm">Order #{{ $order->id }} • {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-white">RM{{ number_format($originalSubtotal - $comboDiscountAmount + ($serviceFeePercentage == 0 ? 0 : $order->service_fee) + ($taxPercentage == 0 ? 0 : $order->tax_amount), 2) }}</div>
                        <div class="text-white/80 text-sm">{{ $totalQuantity }} {{ $totalQuantity > 1 ? 'tickets' : 'ticket' }}</div>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <!-- Event Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="bx bx-calendar mr-2 text-green-500"></i>
                        Event Details
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">{{ $event->name }}</h4>
                                <p class="text-gray-600">{{ $event->venue }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="bx bx-check-circle mr-1"></i>
                                    Confirmed
                                </span>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="bx bx-calendar mr-2 text-green-500"></i>
                                <span>
                                    @if($event->isMultiDay())
                                        {{ $event->getEventDays()[0]['display'] }} - {{ $event->getEventDays()[1]['display'] }}
                                    @else
                                        {{ $event->date_time->format('M j, Y') }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center">
                                <i class="bx bx-time mr-2 text-green-500"></i>
                                <span>{{ $event->start_time }} - {{ $event->end_time }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ticket Details -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="bx bx-receipt mr-2 text-green-500"></i>
                        Your Tickets
                    </h3>
                    <div class="space-y-4">
                        @foreach($tickets as $ticketData)
                            @php
                                $ticket = $ticketData['ticket'];
                                $quantity = $ticketData['quantity'];
                                $price = $ticketData['price'];
                                $day = $ticketData['day'] ?? null;
                                $dayName = $ticketData['day_name'] ?? null;
                            @endphp
                            <div class="py-4 border-t border-gray-200">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="bx bx-purchase-tag text-red-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-gray-900">{{ $ticket->name }} <span class="text-sm text-gray-600">x {{ $quantity }}</span></h4>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-lg font-bold text-gray-900">RM{{ number_format($price * $quantity, 2) }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-600 ml-11">
                                        @if($dayName)
                                            {{ $dayName }} - {{ $event->getEventDays()[$day-1]['display'] ?? '' }}
                                        @else
                                            @if($event->isMultiDay())
                                                {{ $event->getEventDays()[0]['day_name'] }} - {{ $event->getEventDays()[0]['display'] }}
                                            @else
                                                {{ $event->getEventDays()[0]['day_name'] }} - {{ $event->getEventDays()[0]['display'] }}
                                            @endif
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600">RM{{ number_format($price, 2) }} each</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>


                <!-- Order Summary -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h3>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between py-1">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-blue-100 rounded flex items-center justify-center mr-2">
                                    <i class="bx bx-receipt text-blue-600 text-xs"></i>
                                </div>
                                <span class="text-gray-700 font-medium text-sm">Subtotal</span>
                            </div>
                            <span class="font-bold text-gray-900 text-sm">RM{{ number_format($originalSubtotal, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-green-100 rounded flex items-center justify-center mr-2">
                                    <i class="bx bx-gift text-green-600 text-xs"></i>
                                </div>
                                <span class="text-gray-700 font-medium text-sm">Combo Discount ({{ number_format($event->getComboDiscountPercentage(), 2) }}%)</span>
                            </div>
                            <span class="text-green-700 font-bold text-sm">-RM{{ number_format($comboDiscountAmount, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-orange-100 rounded flex items-center justify-center mr-2">
                                    <i class="bx bx-cog text-orange-600 text-xs"></i>
                                </div>
                                <span class="text-gray-700 font-medium text-sm">Service Fee ({{ $serviceFeePercentage }}%)</span>
                            </div>
                            <span class="font-bold text-gray-900 text-sm">RM{{ number_format($serviceFeePercentage == 0 ? 0 : $order->service_fee, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-purple-100 rounded flex items-center justify-center mr-2">
                                    <i class="bx bx-calculator text-purple-600 text-xs"></i>
                                </div>
                                <span class="text-gray-700 font-medium text-sm">Tax ({{ $taxPercentage }}%)</span>
                            </div>
                            <span class="font-bold text-gray-900 text-sm">RM{{ number_format($taxPercentage == 0 ? 0 : $order->tax_amount, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-gray-400">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-red-100 rounded flex items-center justify-center mr-2">
                                    <i class="bx bx-money text-red-600 text-xs"></i>
                                </div>
                                <span class="font-bold text-gray-900 text-sm">Total Amount</span>
                            </div>
                            <span class="font-bold text-red-600 text-sm">RM{{ number_format($originalSubtotal - $comboDiscountAmount + ($serviceFeePercentage == 0 ? 0 : $order->service_fee) + ($taxPercentage == 0 ? 0 : $order->tax_amount), 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">What's Next?</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bx bx-envelope text-2xl text-blue-600"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Check Your Email</h4>
                    <p class="text-sm text-gray-600">We've sent your ticket confirmation and QR codes to your email address.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bx bx-qr-scan text-2xl text-green-600"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Save Your QR Codes</h4>
                    <p class="text-sm text-gray-600">Your QR codes are your entry tickets. Keep them safe and ready for the event.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bx bx-calendar text-2xl text-purple-600"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Mark Your Calendar</h4>
                    <p class="text-sm text-gray-600">Don't forget to attend the event on the scheduled date and time.</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-4 px-2">
            @if($order->purchaseTickets && $order->purchaseTickets->count() > 0)
                <a href="{{ route('customer.tickets.qr', $order->purchaseTickets->first()->id) }}" 
                   class="w-full inline-flex items-center justify-center px-8 py-4 bg-green-600 text-white rounded-xl font-bold text-lg hover:bg-green-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="bx bx-qr-scan mr-3 text-xl"></i>
                    View Ticket
                </a>
            @else
                <a href="{{ route('customer.dashboard') }}" 
                   class="w-full inline-flex items-center justify-center px-8 py-4 bg-green-600 text-white rounded-xl font-bold text-lg hover:bg-green-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="bx bx-ticket mr-3 text-xl"></i>
                    My Tickets
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
