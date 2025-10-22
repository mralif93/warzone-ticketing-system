@extends('layouts.customer')

@section('title', 'Order Details')
@section('description', 'View detailed information about your order in your Warzone World Championship customer portal.')

@section('content')
<!-- Professional Order Details -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Header Section -->
    <div class="bg-white border-b border-wwc-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('customer.orders') }}" class="text-wwc-neutral-400 hover:text-wwc-neutral-600 mr-4">
                        <i class='bx bx-chevron-left text-2xl'></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display">Order Details</h1>
                        <p class="text-wwc-neutral-600 mt-1">Order #{{ $order->order_number }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('customer.tickets') }}" 
                       class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white rounded-lg text-sm font-semibold hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-receipt text-sm mr-2'></i>
                        My Tickets
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Information -->
            <div class="lg:col-span-2">
                <!-- Order Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 mb-8">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900">Order Summary</h2>
                        <p class="text-wwc-neutral-600 text-sm mt-1">Complete order information</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-4">Order Details</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">Order Number</p>
                                        <p class="text-wwc-neutral-900 font-mono">{{ $order->order_number }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">Order Date</p>
                                        <p class="text-wwc-neutral-900">{{ $order->created_at->format('M j, Y \a\t g:i A') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">Customer Email</p>
                                        <p class="text-wwc-neutral-900">{{ $order->customer_email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">Payment Method</p>
                                        <p class="text-wwc-neutral-900">{{ $order->payment_method }}</p>
                                    </div>
                                    @if($order->qrcode)
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">QR Code</p>
                                        <p class="text-wwc-neutral-900 font-mono">{{ $order->qrcode }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-4">Order Status</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">Status</p>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                            @if($order->status === 'Completed') bg-wwc-success text-white
                                            @elseif($order->status === 'Pending') bg-wwc-warning text-white
                                            @elseif($order->status === 'Cancelled') bg-wwc-error text-white
                                            @elseif($order->status === 'Refunded') bg-wwc-info text-white
                                            @else bg-wwc-neutral-200 text-wwc-neutral-800
                                            @endif">
                                            {{ $order->status }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">Total Amount</p>
                                        <p class="text-2xl font-bold text-wwc-neutral-900 font-display">RM{{ number_format($order->total_amount, 0) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">Number of Tickets</p>
                                        <p class="text-wwc-neutral-900">{{ $order->tickets->count() }} ticket(s)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tickets in Order -->
                <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 mb-8">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900">Tickets in This Order</h2>
                        <p class="text-wwc-neutral-600 text-sm mt-1">{{ $order->tickets->count() }} ticket(s) included</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($order->tickets as $ticket)
                            <div class="border border-wwc-neutral-200 rounded-lg p-4 hover:border-wwc-primary-light transition-colors duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="h-12 w-12 rounded-lg bg-wwc-primary flex items-center justify-center">
                                            <i class='bx bx-receipt text-2xl text-white'></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-wwc-neutral-900">{{ $ticket->event->name }}</h3>
                                            <div class="space-y-1">
                                                <p class="text-sm text-wwc-neutral-600">
                                                    <i class='bx bx-calendar text-sm inline mr-2 text-wwc-neutral-400'></i>
                                                    {{ $ticket->event->date_time->format('M j, Y \a\t g:i A') }}
                                                </p>
                                                <p class="text-sm text-wwc-neutral-600">
                                                    <i class='bx bx-map text-sm inline mr-2 text-wwc-neutral-400'></i>
                                                    {{ $ticket->event->venue ?? 'Venue TBA' }}
                                                </p>
                                                <p class="text-sm text-wwc-neutral-600">
                                                    <i class='bx bx-building text-sm inline mr-2 text-wwc-neutral-400'></i>
                                                    Ticket: {{ $ticket->ticket_identifier }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xl font-bold text-wwc-neutral-900 mb-2">RM{{ number_format($ticket->price_paid, 0) }}</div>
                                        <div class="text-sm text-wwc-neutral-500 mb-2">Ticket #{{ $ticket->id }}</div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            @if($ticket->status === 'Sold') bg-wwc-success text-white
                                            @elseif($ticket->status === 'Held') bg-wwc-warning text-white
                                            @elseif($ticket->status === 'Cancelled') bg-wwc-error text-white
                                            @elseif($ticket->status === 'Used') bg-wwc-info text-white
                                            @else bg-wwc-neutral-200 text-wwc-neutral-800
                                            @endif">
                                            {{ $ticket->status }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-wwc-neutral-200">
                                    <a href="{{ route('customer.tickets.show', $ticket) }}" 
                                       class="inline-flex items-center text-sm font-semibold text-wwc-primary hover:text-wwc-primary-dark transition-colors duration-200">
                                        <i class='bx bx-show text-sm mr-2'></i>
                                        View Ticket Details
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Breakdown -->
                <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900">Order Breakdown</h2>
                        <p class="text-wwc-neutral-600 text-sm mt-1">Detailed pricing information</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-wwc-neutral-600">Subtotal ({{ $order->tickets->count() }} tickets)</span>
                                <span class="font-semibold text-wwc-neutral-900">RM{{ number_format($order->subtotal, 0) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-wwc-neutral-600">Service Fee (5%)</span>
                                <span class="font-semibold text-wwc-neutral-900">RM{{ number_format($order->service_fee, 0) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-wwc-neutral-600">Tax (8%)</span>
                                <span class="font-semibold text-wwc-neutral-900">RM{{ number_format($order->tax_amount, 0) }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold border-t border-wwc-neutral-200 pt-3">
                                <span class="text-wwc-neutral-900">Total</span>
                                <span class="text-wwc-primary font-display">RM{{ number_format($order->total_amount, 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900">Quick Actions</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('customer.tickets') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-wwc-primary hover:bg-wwc-primary-light hover:text-wwc-primary-dark rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-receipt text-sm mr-2'></i>
                                View All Tickets
                            </a>
                            <a href="{{ route('customer.orders') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-wwc-neutral-600 hover:bg-wwc-neutral-100 hover:text-wwc-neutral-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-neutral transition-colors duration-200">
                                <i class='bx bx-file text-sm mr-2'></i>
                                All Orders
                            </a>
                            <a href="{{ route('customer.support') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-wwc-accent hover:bg-wwc-accent-light hover:text-wwc-accent-dark rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-accent transition-colors duration-200">
                                <i class='bx bx-help-circle text-sm mr-2'></i>
                                Get Help
                            </a>
                        </div>
                    </div>
                </div>

                @if($order->qrcode)
                <!-- QR Code Section -->
                <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 mb-8">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900">Order QR Code</h2>
                        <p class="text-wwc-neutral-600 text-sm mt-1">Present this QR code at the venue for entry</p>
                    </div>
                    <div class="p-6">
                        <div class="text-center">
                            <!-- QR Code Image -->
                            <div class="mx-auto mb-4">
                                <img src="{{ \App\Services\QRCodeService::generateQRCodeImage($order) }}" 
                                     alt="Order QR Code" 
                                     class="mx-auto h-32 w-32 border border-gray-200 rounded-lg">
                            </div>
                            
                            <!-- QR Code Text -->
                            <div class="text-center">
                                <p class="text-sm font-mono text-gray-600 mb-2">QR Code:</p>
                                <p class="text-lg font-bold text-gray-900 font-mono">{{ $order->qrcode }}</p>
                            </div>
                            
                            <!-- Instructions -->
                            <div class="mt-4 text-sm text-gray-600">
                                <p class="font-medium mb-2">How to use your QR code:</p>
                                <ul class="list-disc list-inside space-y-1 text-left max-w-md mx-auto">
                                    <li>Save this QR code to your phone</li>
                                    <li>Show it to staff at the venue entrance</li>
                                    <li>Keep your ID ready for verification</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Order Status Info -->
                <div class="bg-wwc-info-light border border-wwc-info rounded-xl p-6">
                    <div class="flex items-start">
                        <i class='bx bx-info-circle text-lg text-wwc-info mr-3 mt-0.5'></i>
                        <div>
                            <h3 class="text-sm font-semibold text-wwc-info mb-2">Order Status</h3>
                            <p class="text-xs text-wwc-info">
                                @if($order->status === 'Completed')
                                    Your order has been completed successfully. All tickets have been issued and sent to your email.
                                @elseif($order->status === 'Pending')
                                    Your order is being processed. You will receive confirmation once completed.
                                @elseif($order->status === 'Cancelled')
                                    This order has been cancelled. If you believe this is an error, please contact support.
                                @elseif($order->status === 'Refunded')
                                    This order has been refunded. The refund should appear in your account within 3-5 business days.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection