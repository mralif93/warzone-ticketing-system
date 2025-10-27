@extends('layouts.customer')

@section('title', 'Ticket QR Code - ' . $ticket->event->name)
@section('description', 'QR code for your Warzone World Championship ticket.')

@section('content')
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Header Section -->
    <div class="bg-white border-b border-wwc-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display">Ticket QR Code</h1>
                    <p class="text-wwc-neutral-600 mt-1">{{ $ticket->event->name }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('customer.dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 text-sm font-medium rounded-md text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to My Tickets
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- QR Code Card -->
        <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
            <div class="bg-wwc-primary px-8 py-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                        <i class="bx bx-qr-scan text-2xl text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-white mb-1">QR Code</h2>
                        <p class="text-white/90 text-sm">Present this QR code at the event entrance</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Side: All Information -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Event Information Section -->
                        <div>
                            <h2 class="text-xl font-bold text-wwc-neutral-900 mb-2">Event Information</h2>
                            <p class="text-sm text-wwc-neutral-600 mb-6">Complete event and ticket details</p>
                            
                            <div class="space-y-4">
                                <!-- Event Name -->
                                <div class="border-b border-wwc-neutral-100 pb-4">
                                    <label class="block text-xs font-semibold text-wwc-neutral-500 uppercase tracking-wide mb-1">Event</label>
                                    <p class="text-base text-wwc-neutral-900 font-medium">{{ $ticket->event->name }}</p>
                                </div>
                                
                                <!-- Date & Time -->
                                <div class="border-b border-wwc-neutral-100 pb-4">
                                    <label class="block text-xs font-semibold text-wwc-neutral-500 uppercase tracking-wide mb-1">Date & Time</label>
                                    <p class="text-base text-wwc-neutral-900 font-medium">
                                        @if($ticket->event_day_name)
                                            {{ $ticket->event_day_name }} - {{ $ticket->event->date_time->format('M j, Y \a\t g:i A') }}
                                        @else
                                            {{ $ticket->event->date_time->format('M j, Y \a\t g:i A') }}
                                        @endif
                                    </p>
                                </div>
                                
                                <!-- Venue -->
                                <div class="border-b border-wwc-neutral-100 pb-4">
                                    <label class="block text-xs font-semibold text-wwc-neutral-500 uppercase tracking-wide mb-1">Venue</label>
                                    <p class="text-base text-wwc-neutral-900 font-medium">{{ $ticket->event->venue }}</p>
                                </div>
                                
                                <!-- Ticket Type -->
                                <div class="border-b border-wwc-neutral-100 pb-4">
                                    <label class="block text-xs font-semibold text-wwc-neutral-500 uppercase tracking-wide mb-1">Ticket Type</label>
                                    <p class="text-base text-wwc-neutral-900 font-medium">{{ $ticket->ticketType->name ?? 'General' }}</p>
                                </div>
                                
                                <!-- Zone -->
                                <div class="border-b border-wwc-neutral-100 pb-4">
                                    <label class="block text-xs font-semibold text-wwc-neutral-500 uppercase tracking-wide mb-1">Zone</label>
                                    <p class="text-base text-wwc-neutral-900 font-medium">{{ $ticket->zone }}</p>
                                </div>
                                
                                <!-- Price Paid -->
                                <div class="border-b border-wwc-neutral-100 pb-4">
                                    <label class="block text-xs font-semibold text-wwc-neutral-500 uppercase tracking-wide mb-1">Price Paid</label>
                                    <p class="text-base text-wwc-neutral-900 font-medium">RM{{ number_format($ticket->price_paid, 2) }}</p>
                                </div>
                                
                                <!-- Status -->
                                <div class="pb-2">
                                    <label class="block text-xs font-semibold text-wwc-neutral-500 uppercase tracking-wide mb-2">Status</label>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($ticket->status === 'active') bg-green-100 text-green-800
                                        @elseif($ticket->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($ticket->status === 'scanned') bg-blue-100 text-blue-800
                                        @elseif($ticket->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucwords($ticket->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="bg-wwc-primary-light border border-wwc-primary rounded-lg p-6">
                            <div class="flex items-start">
                                <i class="bx bx-info-circle text-wwc-primary text-xl mr-3 mt-0.5"></i>
                                <div>
                                    <h3 class="text-sm font-medium text-wwc-primary mb-2">How to Use Your QR Code</h3>
                                    <ul class="text-sm text-wwc-neutral-700 space-y-1">
                                        <li>• Present this QR code at the event entrance</li>
                                        <li>• Make sure your phone screen is bright and clear</li>
                                        <li>• The QR code will be scanned by event staff</li>
                                        <li>• Keep this page open or take a screenshot for offline access</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: QR Code -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-8">
                            <div class="bg-white border-2 border-wwc-neutral-200 rounded-xl p-6">
                                @if($ticket->qrcode)
                                    <div class="text-center">
                                        <div class="mb-4">
                                            <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">Your QR Code</h3>
                                            <p class="text-sm text-wwc-neutral-600">Scan this code at the event entrance</p>
                                        </div>
                                        
                                        <!-- QR Code Image -->
                                        <div class="inline-block p-4 bg-white border border-wwc-neutral-300 rounded-lg">
                                            <div class="w-64 h-64 bg-white rounded-lg flex items-center justify-center">
                                                @if($ticket->qrcode)
                                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=256x256&data={{ urlencode($ticket->qrcode) }}" 
                                                         alt="QR Code" 
                                                         class="w-full h-full object-contain"
                                                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'text-center\'><i class=\'bx bx-qr-scan text-6xl text-wwc-neutral-400 mb-2\'></i><p class=\'text-sm text-wwc-neutral-500\'>QR Code</p><p class=\'text-xs text-wwc-neutral-400 mt-1\'>{{ $ticket->qrcode }}</p></div>';">
                                                @else
                                                    <div class="text-center">
                                                        <i class="bx bx-qr-scan text-6xl text-wwc-neutral-400 mb-2"></i>
                                                        <p class="text-sm text-wwc-neutral-500">QR Code</p>
                                                        <p class="text-xs text-wwc-neutral-400 mt-1">Not available</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <p class="text-xs text-wwc-neutral-500 break-all">QR Code ID: {{ $ticket->qrcode }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <i class="bx bx-error-circle text-4xl text-wwc-error mb-4"></i>
                                        <h3 class="text-lg font-medium text-wwc-neutral-900 mb-2">QR Code Not Available</h3>
                                        <p class="text-sm text-wwc-neutral-500">This ticket doesn't have a QR code generated yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
