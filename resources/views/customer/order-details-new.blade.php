@extends('layouts.customer')

@section('title', 'Order Details')

@section('content')
<div class="min-h-screen bg-wwc-neutral-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-wwc-neutral-900">Order Details</h1>
                    <p class="text-wwc-neutral-600 mt-2">Order #{{ $order->order_number }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('customer.orders') }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 rounded-lg text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Orders
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Order Information -->
                <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900">Order Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-semibold text-wwc-neutral-600">Order Number</label>
                                <p class="text-lg font-mono text-wwc-neutral-900 mt-1">{{ $order->order_number }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-wwc-neutral-600">Order Date</label>
                                <p class="text-lg text-wwc-neutral-900 mt-1">{{ $order->created_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-wwc-neutral-600">Status</label>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                        @if($order->status === 'paid') bg-green-100 text-green-800
                                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @elseif($order->status === 'refunded') bg-gray-100 text-gray-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        <i class='bx bx-check-circle text-sm mr-1'></i>
                                        {{ ucwords($order->status) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-wwc-neutral-600">Payment Method</label>
                                <p class="text-lg text-wwc-neutral-900 mt-1">{{ $order->payment_method }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ticket Details -->
                <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900">Ticket Details</h2>
                        <p class="text-wwc-neutral-600 text-sm mt-1">{{ $order->tickets->count() }} ticket(s) included</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($order->tickets as $ticket)
                            <div class="bg-white border border-wwc-neutral-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center justify-between">
                                    <!-- Ticket Info -->
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
                                                    Zone: {{ $ticket->zone }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Price and Actions -->
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <div class="text-xl font-bold text-wwc-neutral-900 mb-1">RM{{ number_format($ticket->price_paid, 0) }}</div>
                                            <div class="text-sm text-wwc-neutral-500 mb-2">Ticket #{{ $ticket->id }}</div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                @if($ticket->status === 'sold') bg-green-100 text-green-800
                                                @elseif($ticket->status === 'held') bg-yellow-100 text-yellow-800
                                                @elseif($ticket->status === 'cancelled') bg-red-100 text-red-800
                                                @elseif($ticket->status === 'scanned') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                <i class='bx bx-check-circle text-xs mr-1'></i>
                                                {{ ucwords($ticket->status) }}
                                            </span>
                                        </div>
                                        
                                        <!-- QR Code Toggle Button -->
                                        <button type="button" 
                                                onclick="toggleQRCode('{{ $ticket->id }}')"
                                                class="inline-flex items-center px-4 py-2 border border-wwc-primary text-wwc-primary rounded-lg text-sm font-medium bg-wwc-primary-light hover:bg-wwc-primary hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                            <i class='bx bx-qr-scan text-sm mr-2'></i>
                                            <span id="qr-toggle-text-{{ $ticket->id }}">Show QR</span>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Hidden QR Code Section -->
                                <div id="qr-section-{{ $ticket->id }}" class="hidden mt-6 pt-6 border-t border-wwc-neutral-200">
                                    <div class="flex items-start space-x-6">
                                        <!-- QR Code Image -->
                                        <div class="flex-shrink-0">
                                            <div id="qr-code-{{ $ticket->id }}" class="w-32 h-32 bg-white border border-wwc-neutral-200 rounded-lg flex items-center justify-center" data-qr="{{ $ticket->qrcode }}">
                                                <!-- QR code will be generated here -->
                                            </div>
                                        </div>
                                        
                                        <!-- QR Code Info -->
                                        <div class="flex-1">
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="text-sm font-semibold text-wwc-neutral-600">QR Code Data:</label>
                                                    <div class="mt-2 p-3 bg-wwc-neutral-50 rounded-lg border font-mono text-sm text-wwc-neutral-700 break-all">
                                                        {{ $ticket->qrcode }}
                                                    </div>
                                                </div>
                                                
                                                <div class="flex space-x-3">
                                                    <button type="button" 
                                                            onclick="copyQRCode('{{ $ticket->qrcode }}')"
                                                            class="inline-flex items-center px-3 py-2 border border-wwc-neutral-300 rounded-lg text-sm font-medium text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary">
                                                        <i class='bx bx-copy text-sm mr-2'></i>
                                                        Copy QR Code
                                                    </button>
                                                    <button type="button" 
                                                            onclick="scanQRCode()"
                                                            class="inline-flex items-center px-3 py-2 border border-wwc-primary text-wwc-primary rounded-lg text-sm font-medium bg-wwc-primary-light hover:bg-wwc-primary hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                                        <i class='bx bx-scan text-sm mr-2'></i>
                                                        Scan QR Code
                                                    </button>
                                                </div>
                                                
                                                <!-- Instructions -->
                                                <div class="bg-wwc-primary-light rounded-lg p-4">
                                                    <h4 class="text-sm font-semibold text-wwc-primary mb-2">How to use your QR code:</h4>
                                                    <ul class="text-xs text-wwc-primary space-y-1">
                                                        <li>• Save this QR code to your phone</li>
                                                        <li>• Show it to staff at the venue entrance</li>
                                                        <li>• Keep your ID ready for verification</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Order Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200">
                        <h3 class="text-lg font-semibold text-wwc-neutral-900">Order Summary</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-wwc-neutral-600">Subtotal</span>
                                <span class="text-sm font-semibold text-wwc-neutral-900">RM{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-wwc-neutral-600">Service Fee (5%)</span>
                                <span class="text-sm font-semibold text-wwc-neutral-900">RM{{ number_format($order->service_fee, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-wwc-neutral-600">Tax (6%)</span>
                                <span class="text-sm font-semibold text-wwc-neutral-900">RM{{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                            <div class="border-t border-wwc-neutral-200 pt-3">
                                <div class="flex justify-between">
                                    <span class="text-base font-semibold text-wwc-neutral-900">Total</span>
                                    <span class="text-xl font-bold text-wwc-primary">RM{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200">
                        <h3 class="text-lg font-semibold text-wwc-neutral-900">Quick Actions</h3>
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
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// QR Code Generation and Management
let qrCodeInstances = {};

// Toggle QR Code visibility
function toggleQRCode(ticketId) {
    const qrSection = document.getElementById(`qr-section-${ticketId}`);
    const toggleText = document.getElementById(`qr-toggle-text-${ticketId}`);
    
    if (qrSection.classList.contains('hidden')) {
        // Show QR code
        qrSection.classList.remove('hidden');
        toggleText.textContent = 'Hide QR';
        
        // Generate QR code if not already generated
        if (!qrCodeInstances[ticketId]) {
            generateQRCode(ticketId);
        }
    } else {
        // Hide QR code
        qrSection.classList.add('hidden');
        toggleText.textContent = 'Show QR';
    }
}

// Generate QR Code
function generateQRCode(ticketId) {
    const qrCodeElement = document.getElementById(`qr-code-${ticketId}`);
    const qrData = qrCodeElement.getAttribute('data-qr') || `Ticket ID: ${ticketId}`;
    
    // Clear previous QR code
    qrCodeElement.innerHTML = '';
    
    // Generate new QR code
    QRCode.toCanvas(qrCodeElement, qrData, {
        width: 128,
        height: 128,
        margin: 1,
        color: {
            dark: '#1f2937',
            light: '#ffffff'
        },
        errorCorrectionLevel: 'M'
    }, function (error) {
        if (error) {
            console.error('QR Code generation error:', error);
            qrCodeElement.innerHTML = '<div class="text-xs text-red-500">QR Error</div>';
        } else {
            console.log('QR Code generated successfully for ticket', ticketId);
            qrCodeInstances[ticketId] = true;
        }
    });
}

// Copy QR Code to clipboard
function copyQRCode(qrCode) {
    navigator.clipboard.writeText(qrCode).then(function() {
        // Show success message
        Swal.fire({
            icon: 'success',
            title: 'Copied!',
            text: 'QR Code data copied to clipboard',
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to copy QR Code data',
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    });
}

// Scan QR Code
function scanQRCode() {
    Swal.fire({
        title: 'QR Code Scanner',
        html: `
            <div class="text-center">
                <div id="qr-scanner-container" class="w-full max-w-md mx-auto">
                    <video id="qr-video" class="w-full rounded-lg border border-gray-300"></video>
                </div>
                <p class="text-sm text-gray-600 mt-2">Point your camera at a QR code to scan</p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Start Scanner',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return new Promise((resolve) => {
                const video = document.getElementById('qr-video');
                const qrScanner = new QrScanner(video, result => {
                    qrScanner.stop();
                    resolve(result);
                });
                
                qrScanner.start().catch(err => {
                    console.error('QR Scanner error:', err);
                    Swal.showValidationMessage('Camera access denied or not available');
                });
            });
        },
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            const scannedData = result.value;
            Swal.fire({
                title: 'QR Code Scanned!',
                html: `
                    <div class="text-left">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Scanned Data:</p>
                        <div class="p-3 bg-gray-100 rounded border font-mono text-xs break-all">
                            ${scannedData}
                        </div>
                    </div>
                `,
                confirmButtonText: 'OK',
                showCancelButton: true,
                cancelButtonText: 'Scan Again',
                preConfirm: () => {
                    // You can add logic here to process the scanned QR code
                    console.log('Scanned QR Code:', scannedData);
                }
            });
        }
    });
}

// Initialize QR codes for visible tickets on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set QR data attributes for all ticket QR code elements
    const qrElements = document.querySelectorAll('[id^="qr-code-"]');
    qrElements.forEach(element => {
        const ticketId = element.id.replace('qr-code-', '');
        element.setAttribute('data-qr', `Ticket ID: ${ticketId}`);
    });
    
    console.log('QR Code functionality initialized');
});
</script>
@endpush
@endsection




