@extends('layouts.customer')

@section('title', 'Order Details')

@section('content')
<!-- Professional Order Details -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.orders') }}" 
                       class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200">
                        <div class="h-9 w-9 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-blue-50">
                            <i class="bx bx-chevron-left text-lg"></i>
                        </div>
                    </a>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">Order Details</h1>
                        <p class="text-gray-500 text-sm">Order #{{ $order->order_number }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Order Information Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">{{ $order->tickets->first()->event->name ?? 'Order Details' }}</h2>
                                <p class="text-gray-500 text-sm mt-1">Order Information</p>
                            </div>
                            <div class="text-right">
                                <div class="text-xl font-semibold text-gray-900">RM{{ number_format($order->total_amount, 0) }}</div>
                                <div class="text-gray-500 text-sm">Total Amount</div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-hash text-blue-600'></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Order Number</p>
                                </div>
                                <p class="text-sm text-gray-900 font-medium">{{ $order->order_number }}</p>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 bg-green-50 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-calendar text-green-600'></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Order Date</p>
                                </div>
                                <p class="text-sm text-gray-900 font-medium">{{ $order->created_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-check-circle text-emerald-600'></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Status</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($order->status === 'Paid') bg-emerald-100 text-emerald-800
                                    @elseif($order->status === 'Pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'Cancelled') bg-red-100 text-red-800
                                    @elseif($order->status === 'Refunded') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $order->status }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 bg-purple-50 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-purchase-tag text-purple-600'></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Tickets</p>
                                </div>
                                <p class="text-sm text-gray-900 font-medium">{{ $order->tickets->count() }} ticket(s)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ticket Information Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">Ticket Information</h3>
                        <p class="text-gray-500 text-sm">Your ticket details for entry</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($order->tickets as $ticket)
                            <div class="space-y-4">
                                @if($loop->index > 0)
                                    <div class="border-t border-gray-200 pt-4"></div>
                                @endif
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-10 w-10 bg-orange-50 rounded-lg flex items-center justify-center">
                                            <i class='bx bx-receipt text-orange-600'></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500">Ticket ID</p>
                                    </div>
                                    <p class="text-sm text-gray-900 font-medium">TKT-{{ $ticket->id }}</p>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-10 w-10 bg-indigo-50 rounded-lg flex items-center justify-center">
                                            <i class='bx bx-layer text-indigo-600'></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500">Zone</p>
                                    </div>
                                    <p class="text-sm text-gray-900 font-medium">{{ $ticket->zone ?? 'General' }}</p>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-10 w-10 bg-cyan-50 rounded-lg flex items-center justify-center">
                                            <i class='bx bx-hash text-cyan-600'></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500">Ticket Number</p>
                                    </div>
                                    <p class="text-sm text-gray-900 font-medium">#{{ $ticket->id }}</p>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-10 w-10 bg-pink-50 rounded-lg flex items-center justify-center">
                                            <i class='bx bx-file text-pink-600'></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500">Order Number</p>
                                    </div>
                                    <p class="text-sm text-gray-900 font-medium">{{ $order->order_number }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('customer.orders.show', $order) }}" 
                               class="flex items-center p-3 bg-gray-50 hover:bg-blue-50 rounded-lg transition-colors duration-200 group">
                                <div class="h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200">
                                    <i class='bx bx-receipt text-blue-600 text-lg'></i>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900 group-hover:text-blue-600">View Order</span>
                                    <p class="text-sm text-gray-600">See order details</p>
                                </div>
                            </a>
                            
                            <a href="{{ route('customer.tickets') }}" 
                               class="flex items-center p-3 bg-gray-50 hover:bg-green-50 rounded-lg transition-colors duration-200 group">
                                <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200">
                                    <i class='bx bx-receipt text-green-600 text-lg'></i>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900 group-hover:text-green-600">All Tickets</span>
                                    <p class="text-sm text-gray-600">View all tickets</p>
                                </div>
                            </a>
                            
                            <a href="{{ route('customer.support') }}" 
                               class="flex items-center p-3 bg-gray-50 hover:bg-orange-50 rounded-lg transition-colors duration-200 group">
                                <div class="h-10 w-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-orange-200">
                                    <i class='bx bx-help-circle text-orange-600 text-lg'></i>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900 group-hover:text-orange-600">Get Help</span>
                                    <p class="text-sm text-gray-600">Contact support</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Important Guidelines -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Important Guidelines</h3>
                        <p class="text-gray-500 text-sm mt-1">Essential information for your event</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex items-center text-sm text-gray-600">
                                <div class="h-5 w-5 bg-orange-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class='bx bx-check text-orange-600 text-xs'></i>
                                </div>
                                <span>Arrive 30 minutes early</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <div class="h-5 w-5 bg-orange-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class='bx bx-check text-orange-600 text-xs'></i>
                                </div>
                                <span>Bring valid ID</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <div class="h-5 w-5 bg-orange-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class='bx bx-check text-orange-600 text-xs'></i>
                                </div>
                                <span>Keep phone charged</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <div class="h-5 w-5 bg-orange-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class='bx bx-check text-orange-600 text-xs'></i>
                                </div>
                                <span>Contact support if needed</span>
                            </div>
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
