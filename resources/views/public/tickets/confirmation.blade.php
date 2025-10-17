@extends('layouts.customer')

@section('title', 'Purchase Confirmation')
@section('description', 'Your ticket purchase has been confirmed. View your order details and QR codes.')

@section('content')
<!-- Professional Purchase Confirmation -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Header Section -->
    <div class="bg-white border-b border-wwc-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <!-- Back Navigation -->
                <div class="flex items-center">
                    <a href="{{ route('customer.tickets') }}" 
                       class="flex items-center text-wwc-neutral-600 hover:text-wwc-primary transition-colors duration-200 group">
                        <div class="h-8 w-8 bg-wwc-neutral-100 rounded-lg flex items-center justify-center group-hover:bg-wwc-primary/10 transition-colors duration-200">
                            <i class="bx bx-chevron-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                        </div>
                        <span class="font-semibold ml-3">Back to Tickets</span>
                    </a>
                </div>
                
                <!-- Purchase Details -->
                <div class="text-center flex-1 mx-8">
                    <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display mb-1">Purchase Successful!</h1>
                    <p class="text-sm text-wwc-neutral-600">Your tickets have been confirmed and are ready for use</p>
                </div>
                
                <!-- Success Status -->
                <div class="flex items-center">
                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-wwc-success/10 text-wwc-success border border-wwc-success/20">
                        <i class="bx bx-check-circle mr-2"></i>
                        Confirmed
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-wwc-success/10 border border-wwc-success/20 text-wwc-success px-6 py-4 rounded-lg mb-8">
                <div class="flex items-center">
                    <i class='bx bx-check-circle text-lg mr-2'></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        
        <!-- Order Details Card -->
        <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 mb-8">
            <div class="px-6 py-4 border-b border-wwc-neutral-200">
                <h2 class="text-xl font-semibold text-wwc-neutral-900">Order Details</h2>
                <p class="text-wwc-neutral-600 text-sm mt-1">Complete information about your purchase</p>
            </div>
            <div class="p-6">
                <!-- Single Column Layout -->
                <div class="space-y-6">
                    <!-- Order Number -->
                    <div class="flex items-center justify-between py-4 border-b border-wwc-neutral-100">
                        <div class="flex items-center">
                            <div class="h-12 w-12 bg-wwc-primary/10 rounded-xl flex items-center justify-center mr-4">
                                <i class='bx bx-receipt text-wwc-primary text-xl'></i>
                            </div>
                            <dt class="text-sm font-medium text-wwc-neutral-500 uppercase tracking-wide">Order Number</dt>
                        </div>
                        <dd class="text-lg font-semibold text-wwc-neutral-900">{{ $order->order_number }}</dd>
                    </div>
                    
                    <!-- Purchase Date -->
                    <div class="flex items-center justify-between py-4 border-b border-wwc-neutral-100">
                        <div class="flex items-center">
                            <div class="h-12 w-12 bg-wwc-info/10 rounded-xl flex items-center justify-center mr-4">
                                <i class='bx bx-calendar text-wwc-info text-xl'></i>
                            </div>
                            <dt class="text-sm font-medium text-wwc-neutral-500 uppercase tracking-wide">Purchase Date</dt>
                        </div>
                        <dd class="text-lg font-semibold text-wwc-neutral-900">{{ $order->created_at->format('M j, Y \a\t g:i A') }}</dd>
                    </div>
                    
                    <!-- Status -->
                    <div class="flex items-center justify-between py-4 border-b border-wwc-neutral-100">
                        <div class="flex items-center">
                            <div class="h-12 w-12 bg-wwc-success/10 rounded-xl flex items-center justify-center mr-4">
                                <i class='bx bx-check-circle text-wwc-success text-xl'></i>
                            </div>
                            <dt class="text-sm font-medium text-wwc-neutral-500 uppercase tracking-wide">Status</dt>
                        </div>
                        <dd>
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-wwc-success/10 text-wwc-success border border-wwc-success/20">
                                <i class='bx bx-check-circle mr-2'></i>
                                {{ $order->status }}
                            </span>
                        </dd>
                    </div>
                    
                    <!-- Event -->
                    <div class="flex items-center justify-between py-4 border-b border-wwc-neutral-100">
                        <div class="flex items-center">
                            <div class="h-12 w-12 bg-wwc-accent/10 rounded-xl flex items-center justify-center mr-4">
                                <i class='bx bx-trophy text-wwc-accent text-xl'></i>
                            </div>
                            <dt class="text-sm font-medium text-wwc-neutral-500 uppercase tracking-wide">Event</dt>
                        </div>
                        <dd class="text-lg font-semibold text-wwc-neutral-900">{{ $order->tickets->first()->event->name }}</dd>
                    </div>
                    
                    <!-- Total Amount -->
                    <div class="flex items-center justify-between py-4">
                        <div class="flex items-center">
                            <div class="h-12 w-12 bg-wwc-primary/10 rounded-xl flex items-center justify-center mr-4">
                                <i class='bx bx-money text-wwc-primary text-xl'></i>
                            </div>
                            <dt class="text-sm font-medium text-wwc-neutral-500 uppercase tracking-wide">Total Amount</dt>
                        </div>
                        <dd class="text-3xl font-bold text-wwc-primary">RM{{ number_format($order->total_amount, 0) }}</dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code Section -->
        <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 mb-8">
            <div class="px-6 py-4 border-b border-wwc-neutral-200">
                <h2 class="text-xl font-semibold text-wwc-neutral-900">Order QR Code</h2>
                <p class="text-wwc-neutral-600 text-sm mt-1">Present this QR code at the venue for entry</p>
            </div>
            <div class="p-6">
                <div class="text-center">
                    <!-- QR Code Display -->
                    <div class="inline-block p-8 bg-wwc-neutral-50 rounded-xl border-2 border-dashed border-wwc-neutral-300">
                        <div class="text-center">
                            <!-- QR Code Image -->
                            <div class="mx-auto mb-6">
                                <img src="{{ \App\Services\QRCodeService::generateQRCodeImage($order) }}" 
                                     alt="Order QR Code" 
                                     class="mx-auto h-56 w-56 border border-wwc-neutral-200 rounded-xl shadow-sm">
                            </div>
                            
                            <!-- QR Code Text -->
                            <div class="text-center">
                                <p class="text-sm font-semibold text-wwc-neutral-600 mb-2">QR Code:</p>
                                <p class="text-xl font-bold text-wwc-neutral-900 font-mono">{{ $order->qrcode }}</p>
                            </div>
                            
                            <!-- Order Info -->
                            <div class="mt-6 grid grid-cols-3 gap-4 text-xs text-wwc-neutral-500">
                                <div class="bg-white rounded-lg p-3">
                                    <p class="font-semibold text-wwc-neutral-700">Order</p>
                                    <p class="font-mono">{{ $order->order_number }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-3">
                                    <p class="font-semibold text-wwc-neutral-700">Amount</p>
                                    <p class="font-mono">RM{{ number_format($order->total_amount, 0) }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-3">
                                    <p class="font-semibold text-wwc-neutral-700">Tickets</p>
                                    <p class="font-mono">{{ $order->getTicketsCount() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Instructions -->
                    <div class="mt-8 bg-wwc-info/5 rounded-xl p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-wwc-info/10 rounded-lg flex items-center justify-center">
                                    <i class='bx bx-info-circle text-wwc-info text-lg'></i>
                                </div>
                            </div>
                            <div class="ml-4 text-left">
                                <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-3">How to use your QR code:</h3>
                                <ul class="space-y-2 text-wwc-neutral-700">
                                    <li class="flex items-center">
                                        <i class='bx bx-check text-wwc-success mr-2'></i>
                                        Save this QR code to your phone
                                    </li>
                                    <li class="flex items-center">
                                        <i class='bx bx-check text-wwc-success mr-2'></i>
                                        Show it to staff at the venue entrance
                                    </li>
                                    <li class="flex items-center">
                                        <i class='bx bx-check text-wwc-success mr-2'></i>
                                        Keep your ID ready for verification
                                    </li>
                                    <li class="flex items-center">
                                        <i class='bx bx-check text-wwc-success mr-2'></i>
                                        QR code is valid for this event only
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets Section -->
        <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 mb-8">
            <div class="px-6 py-4 border-b border-wwc-neutral-200">
                <h2 class="text-xl font-semibold text-wwc-neutral-900">Your Tickets</h2>
                <p class="text-wwc-neutral-600 text-sm mt-1">Individual ticket details for this order</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($order->tickets as $ticket)
                        <div class="bg-wwc-neutral-50 rounded-xl p-6 border border-wwc-neutral-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-16 w-16 bg-wwc-primary/10 rounded-xl flex items-center justify-center">
                                            <i class='bx bx-receipt text-3xl text-wwc-primary'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-wwc-neutral-900">{{ $ticket->ticket_identifier }}</h4>
                                        <div class="mt-1 space-y-1">
                                            <p class="text-sm text-wwc-neutral-600">
                                                <span class="font-medium">Zone:</span> {{ $ticket->zone }}
                                            </p>
                                            <p class="text-sm text-wwc-neutral-600">
                                                <span class="font-medium">QR Code:</span> 
                                                <span class="font-mono">{{ $ticket->qrcode }}</span>
                                            </p>
                                            <p class="text-sm text-wwc-neutral-600">
                                                <span class="font-medium">Ticket #:</span> {{ $ticket->id }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-wwc-primary">RM{{ number_format($ticket->price_paid, 0) }}</div>
                                    <div class="text-sm text-wwc-neutral-500 mt-1">Per Ticket</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Important Information -->
        <div class="bg-wwc-info/5 border border-wwc-info/20 rounded-xl p-6 mb-8">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 bg-wwc-info/10 rounded-lg flex items-center justify-center">
                        <i class='bx bx-info-circle text-wwc-info text-lg'></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-wwc-neutral-900">Important Information</h3>
                    <div class="mt-3 text-wwc-neutral-700">
                        <ul class="space-y-2">
                            <li class="flex items-center">
                                <i class='bx bx-check text-wwc-success mr-2'></i>
                                Your tickets have been sent to your email address
                            </li>
                            <li class="flex items-center">
                                <i class='bx bx-check text-wwc-success mr-2'></i>
                                Please arrive at the venue 30 minutes before the event starts
                            </li>
                            <li class="flex items-center">
                                <i class='bx bx-check text-wwc-success mr-2'></i>
                                Bring a valid ID and the confirmation email
                            </li>
                            <li class="flex items-center">
                                <i class='bx bx-check text-wwc-success mr-2'></i>
                                QR codes will be scanned at the entrance
                            </li>
                            <li class="flex items-center">
                                <i class='bx bx-check text-wwc-success mr-2'></i>
                                No refunds or exchanges after purchase
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('customer.tickets') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-wwc-primary text-white rounded-lg font-semibold hover:bg-wwc-primary-dark transition-colors duration-200">
                <i class='bx bx-receipt mr-2'></i>
                View My Tickets
            </a>
            {{-- <a href="{{ route('public.events') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-wwc-neutral-600 text-white rounded-lg font-semibold hover:bg-wwc-neutral-700 transition-colors duration-200">
                <i class='bx bx-calendar mr-2'></i>
                Browse More Events
            </a> --}}
        </div>
    </div>
</div>
@endsection
