@extends('layouts.customer')

@section('title', 'Ticket Details - ' . $ticket->event->name)
@section('description', 'View your ticket details and QR code for ' . $ticket->event->name . '.')

@section('content')
<!-- Professional Ticket Details -->
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
                
                <!-- Ticket Details -->
                <div class="text-center flex-1 mx-8">
                    <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display mb-1">Ticket Details</h1>
                    <p class="text-sm text-wwc-neutral-600">Ticket #{{ $ticket->id }}</p>
                </div>
                
                <!-- Action Button -->
            <div class="flex items-center">
                    <a href="{{ route('customer.orders.show', $ticket->order) }}" 
                       class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white rounded-xl font-semibold hover:bg-wwc-primary-dark transition-colors duration-200">
                        <i class='bx bx-receipt mr-2'></i>
                        My Orders
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Content (2 columns) -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Ticket Information Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-r from-wwc-primary to-wwc-accent px-6 py-4">
                        <div class="flex items-center justify-between">
                <div>
                                <h2 class="text-xl font-bold text-white">Ticket Information</h2>
                                <p class="text-wwc-primary-100 text-sm mt-1">Your ticket details and QR code</p>
                                </div>
                                <div class="text-right">
                                <div class="text-2xl font-bold text-white">RM{{ number_format($ticket->price_paid, 0) }}</div>
                                <div class="text-wwc-primary-100 text-sm">Per Ticket</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Event Details -->
                            <div class="space-y-6">
                                <div class="flex items-start space-x-4">
                                    <div class="h-12 w-12 bg-wwc-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class='bx bx-calendar text-wwc-primary text-xl'></i>
                                </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-wwc-neutral-500 uppercase tracking-wide mb-1">Event Name</h3>
                                        <p class="text-lg font-semibold text-wwc-neutral-900">{{ $ticket->event->name }}</p>
                            </div>
                        </div>

                                <div class="flex items-start space-x-4">
                                    <div class="h-12 w-12 bg-wwc-accent/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class='bx bx-time text-wwc-accent text-xl'></i>
                                    </div>
                                <div>
                                        <h3 class="text-sm font-medium text-wwc-neutral-500 uppercase tracking-wide mb-1">Date & Time</h3>
                                        <p class="text-lg font-semibold text-wwc-neutral-900">{{ $ticket->event->getFormattedDateRange() }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-4">
                                    <div class="h-12 w-12 bg-wwc-info/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class='bx bx-map text-wwc-info text-xl'></i>
                                </div>
                                <div>
                                        <h3 class="text-sm font-medium text-wwc-neutral-500 uppercase tracking-wide mb-1">Venue</h3>
                                        <p class="text-lg font-semibold text-wwc-neutral-900">{{ $ticket->event->venue ?? 'Venue TBA' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Ticket Details -->
                            <div class="space-y-6">
                                <div class="flex items-start space-x-4">
                                    <div class="h-12 w-12 bg-wwc-success/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class='bx bx-receipt text-wwc-success text-xl'></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-wwc-neutral-500 uppercase tracking-wide mb-1">Ticket ID</h3>
                                        <p class="text-lg font-semibold text-wwc-primary font-mono">{{ $ticket->ticket_identifier ?? 'TKT-' . $ticket->id }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-4">
                                    <div class="h-12 w-12 bg-wwc-warning/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class='bx bx-layer text-wwc-warning text-xl'></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-wwc-neutral-500 uppercase tracking-wide mb-1">Zone</h3>
                                        <p class="text-lg font-semibold text-wwc-neutral-900">{{ $ticket->zone ?? 'General' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-4">
                                    <div class="h-12 w-12 bg-wwc-error/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class='bx bx-hash text-wwc-error text-xl'></i>
                                        </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-wwc-neutral-500 uppercase tracking-wide mb-1">Ticket Number</h3>
                                        <p class="text-lg font-semibold text-wwc-neutral-900">#{{ $ticket->id }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                                </div>
                            </div>

                <!-- QR Code Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-r from-wwc-accent to-wwc-info px-6 py-4">
                        <div class="text-center">
                            <h2 class="text-xl font-bold text-white">QR Code</h2>
                            <p class="text-wwc-accent-100 text-sm mt-1">Present this QR code at the venue entrance</p>
                        </div>
                    </div>
                    
                    <!-- QR Code Display -->
                    <div class="p-8">
                        <div class="text-center">
                            <div class="inline-block bg-gradient-to-br from-wwc-neutral-50 to-wwc-neutral-100 p-8 rounded-3xl shadow-inner">
                                <div class="w-56 h-56 bg-white border-4 border-wwc-neutral-200 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                            <div class="text-center">
                                        <i class='bx bx-qr-scan text-8xl text-wwc-neutral-400 mb-3'></i>
                                        <p class="text-lg text-wwc-neutral-500 font-bold">QR CODE</p>
                                    </div>
                                </div>
                                <div class="bg-white rounded-xl p-4 border border-wwc-neutral-200">
                                    <p class="text-sm text-wwc-neutral-600 font-mono break-all">{{ $ticket->qrcode }}</p>
                                </div>
                                <p class="text-sm text-wwc-neutral-500 mt-4 font-medium">Scan at venue entrance</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Information Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-r from-wwc-info to-wwc-success px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-white">Order Information</h2>
                                <p class="text-wwc-info-100 text-sm mt-1">Details about your purchase</p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-white">RM{{ number_format($ticket->order->total_amount, 0) }}</div>
                                <div class="text-wwc-info-100 text-sm">Total Paid</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Order Details -->
                            <div class="space-y-6">
                                <div class="flex items-start space-x-4">
                                    <div class="h-12 w-12 bg-wwc-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class='bx bx-receipt text-wwc-primary text-xl'></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-wwc-neutral-500 uppercase tracking-wide mb-1">Order Number</h3>
                                        <p class="text-lg font-semibold text-wwc-neutral-900 font-mono">{{ $ticket->order->order_number }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-4">
                                    <div class="h-12 w-12 bg-wwc-accent/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class='bx bx-calendar text-wwc-accent text-xl'></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-wwc-neutral-500 uppercase tracking-wide mb-1">Purchase Date</h3>
                                        <p class="text-lg font-semibold text-wwc-neutral-900">{{ $ticket->order->created_at->format('M j, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-4">
                                    <div class="h-12 w-12 bg-wwc-info/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class='bx bx-credit-card text-wwc-info text-xl'></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-wwc-neutral-500 uppercase tracking-wide mb-1">Payment Method</h3>
                                        <p class="text-lg font-semibold text-wwc-neutral-900">{{ $ticket->order->payment_method ?? 'Credit Card' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status Information -->
                            <div class="space-y-6">
                                <div class="flex items-start space-x-4">
                                    <div class="h-12 w-12 bg-wwc-warning/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class='bx bx-package text-wwc-warning text-xl'></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-wwc-neutral-500 uppercase tracking-wide mb-1">Order Status</h3>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-wwc-neutral-100 text-wwc-neutral-600 border border-wwc-neutral-200">
                                            {{ $ticket->order->status }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-4">
                                    <div class="h-12 w-12 bg-wwc-success/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class='bx bx-check-circle text-wwc-success text-xl'></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-wwc-neutral-500 uppercase tracking-wide mb-1">Ticket Status</h3>
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                                            @if($ticket->status === 'Sold') bg-wwc-success/10 text-wwc-success border border-wwc-success/20
                                            @elseif($ticket->status === 'Held') bg-wwc-warning/10 text-wwc-warning border border-wwc-warning/20
                                            @elseif($ticket->status === 'Cancelled') bg-wwc-error/10 text-wwc-error border border-wwc-error/20
                                            @elseif($ticket->status === 'Used') bg-wwc-info/10 text-wwc-info border border-wwc-info/20
                                            @else bg-wwc-neutral-100 text-wwc-neutral-600 border border-wwc-neutral-200
                                            @endif">
                                            <i class='bx 
                                                @if($ticket->status === 'Sold') bx-check-circle
                                                @elseif($ticket->status === 'Held') bx-time
                                                @elseif($ticket->status === 'Cancelled') bx-x-circle
                                                @elseif($ticket->status === 'Used') bx-check-double
                                                @else bx-question-mark
                                                @endif mr-2'></i>
                                            {{ $ticket->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar (1 column) -->
            <div class="space-y-6">
                
                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-r from-wwc-success to-wwc-primary px-6 py-4">
                        <h3 class="text-lg font-bold text-white">Quick Actions</h3>
                        <p class="text-wwc-success-100 text-sm mt-1">Navigate and manage your tickets</p>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="p-6">
                        <div class="space-y-4">
                            <a href="{{ route('customer.orders.show', $ticket->order) }}" 
                               class="flex items-center p-4 bg-gradient-to-r from-wwc-primary/5 to-wwc-primary/10 hover:from-wwc-primary/10 hover:to-wwc-primary/20 rounded-xl transition-all duration-200 group border border-wwc-primary/20">
                                <div class="h-12 w-12 bg-wwc-primary/20 rounded-xl flex items-center justify-center mr-4 group-hover:bg-wwc-primary/30 transition-colors duration-200">
                                    <i class='bx bx-receipt text-wwc-primary text-xl'></i>
                                </div>
                                <div>
                                    <span class="font-semibold text-wwc-neutral-900 group-hover:text-wwc-primary transition-colors duration-200">View Order</span>
                                    <p class="text-sm text-wwc-neutral-600">See order details</p>
                                </div>
                            </a>
                            
                            <a href="{{ route('customer.tickets') }}" 
                               class="flex items-center p-4 bg-gradient-to-r from-wwc-accent/5 to-wwc-accent/10 hover:from-wwc-accent/10 hover:to-wwc-accent/20 rounded-xl transition-all duration-200 group border border-wwc-accent/20">
                                <div class="h-12 w-12 bg-wwc-accent/20 rounded-xl flex items-center justify-center mr-4 group-hover:bg-wwc-accent/30 transition-colors duration-200">
                                    <i class='bx bx-receipt text-wwc-accent text-xl'></i>
                                </div>
                                <div>
                                    <span class="font-semibold text-wwc-neutral-900 group-hover:text-wwc-accent transition-colors duration-200">All Tickets</span>
                                    <p class="text-sm text-wwc-neutral-600">View all your tickets</p>
                                </div>
                            </a>
                            
                            <a href="{{ route('public.contact') }}" 
                               class="flex items-center p-4 bg-gradient-to-r from-wwc-info/5 to-wwc-info/10 hover:from-wwc-info/10 hover:to-wwc-info/20 rounded-xl transition-all duration-200 group border border-wwc-info/20">
                                <div class="h-12 w-12 bg-wwc-info/20 rounded-xl flex items-center justify-center mr-4 group-hover:bg-wwc-info/30 transition-colors duration-200">
                                    <i class='bx bx-help-circle text-wwc-info text-xl'></i>
                                </div>
                                <div>
                                    <span class="font-semibold text-wwc-neutral-900 group-hover:text-wwc-info transition-colors duration-200">Get Help</span>
                                    <p class="text-sm text-wwc-neutral-600">Contact support</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Important Information -->
                <div class="bg-gradient-to-br from-wwc-warning/10 to-wwc-warning/5 border border-wwc-warning/30 rounded-2xl overflow-hidden">
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-r from-wwc-warning to-wwc-error px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                                <i class='bx bx-info-circle text-white text-xl'></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Important Information</h3>
                                <p class="text-wwc-warning-100 text-sm">Essential details for your event</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Information List -->
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3 p-3 bg-white/50 rounded-xl border border-wwc-warning/20">
                                <div class="h-8 w-8 bg-wwc-warning/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class='bx bx-time text-wwc-warning text-sm'></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-wwc-neutral-900 text-sm">Arrival Time</p>
                                    <p class="text-sm text-wwc-neutral-700">Arrive at least 30 minutes before the event</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3 p-3 bg-white/50 rounded-xl border border-wwc-warning/20">
                                <div class="h-8 w-8 bg-wwc-warning/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class='bx bx-id-card text-wwc-warning text-sm'></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-wwc-neutral-900 text-sm">Valid ID Required</p>
                                    <p class="text-sm text-wwc-neutral-700">Bring a valid ID matching the ticket holder</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3 p-3 bg-white/50 rounded-xl border border-wwc-warning/20">
                                <div class="h-8 w-8 bg-wwc-warning/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class='bx bx-battery text-wwc-warning text-sm'></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-wwc-neutral-900 text-sm">Phone Charged</p>
                                    <p class="text-sm text-wwc-neutral-700">Keep your phone charged for QR code display</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3 p-3 bg-white/50 rounded-xl border border-wwc-warning/20">
                                <div class="h-8 w-8 bg-wwc-warning/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class='bx bx-support text-wwc-warning text-sm'></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-wwc-neutral-900 text-sm">Need Help?</p>
                                    <p class="text-sm text-wwc-neutral-700">Contact support if you have any issues</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@endsection