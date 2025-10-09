@extends('layouts.app')

@section('title', 'QR Code Scanner')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('gate-staff.dashboard') }}" 
                       class="flex items-center text-gray-600 hover:text-wwc-primary transition-colors">
                        <i class="bx bx-arrow-back text-xl mr-2"></i>
                        <span class="font-medium">Back to Dashboard</span>
                    </a>
                </div>
                <div class="text-right">
                    <h1 class="text-2xl font-bold text-gray-900">QR Code Scanner</h1>
                    <p class="text-sm text-gray-600">Scan customer tickets for entry</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Event Selection -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Event & Gate Selection</h3>
                <form id="event-form" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="event_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Event
                        </label>
                        <select id="event_id" name="event_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-wwc-primary focus:border-wwc-primary">
                            <option value="">Choose an event...</option>
                            @foreach($todayEvents as $event)
                                <option value="{{ $event->id }}" 
                                        {{ $event && $event->id == $event->id ? 'selected' : '' }}>
                                    {{ $event->name }} - {{ \Carbon\Carbon::parse($event->event_date)->format('M j, Y g:i A') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="gate_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Gate Location
                        </label>
                        <select id="gate_id" name="gate_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-wwc-primary focus:border-wwc-primary">
                            <option value="GATE-1" {{ $gateId == 'GATE-1' ? 'selected' : '' }}>Gate 1 (Main Entrance)</option>
                            <option value="GATE-2" {{ $gateId == 'GATE-2' ? 'selected' : '' }}>Gate 2 (VIP Entrance)</option>
                            <option value="GATE-3" {{ $gateId == 'GATE-3' ? 'selected' : '' }}>Gate 3 (Side Entrance)</option>
                            <option value="GATE-4" {{ $gateId == 'GATE-4' ? 'selected' : '' }}>Gate 4 (Emergency Exit)</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Scanner Interface -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ticket Scanner</h3>
                
                <!-- QR Code Input -->
                <div class="mb-6">
                    <label for="qrcode_input" class="block text-sm font-medium text-gray-700 mb-2">
                        QR Code / Ticket ID
                    </label>
                    <div class="flex space-x-2">
                        <input type="text" id="qrcode_input" name="qrcode" 
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-lg font-mono"
                               placeholder="Scan or enter QR code..."
                               autocomplete="off">
                        <button type="button" id="scan_button" 
                                class="bg-wwc-primary hover:bg-wwc-primary-dark text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            <i class="bx bx-qr-scan mr-2"></i>
                            Scan
                        </button>
                    </div>
                </div>

                <!-- Scan Result Display -->
                <div id="scan_result" class="hidden">
                    <div class="p-6 rounded-lg border-2">
                        <div class="flex items-center justify-center mb-4">
                            <div id="result_icon" class="w-16 h-16 rounded-full flex items-center justify-center text-3xl"></div>
                        </div>
                        <h4 id="result_title" class="text-xl font-bold text-center mb-2"></h4>
                        <p id="result_message" class="text-center text-gray-600 mb-4"></p>
                        
                        <!-- Ticket Details (for successful scans) -->
                        <div id="ticket_details" class="hidden bg-gray-50 rounded-lg p-4">
                            <h5 class="font-medium text-gray-900 mb-3">Ticket Details</h5>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Event:</span>
                                    <span id="ticket_event" class="font-medium"></span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Zone:</span>
                                    <span id="ticket_zone" class="font-medium"></span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Seat:</span>
                                    <span id="ticket_seat" class="font-medium"></span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Price:</span>
                                    <span id="ticket_price" class="font-medium"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Performance Info -->
                        <div class="text-center text-xs text-gray-500 mt-4">
                            <span id="performance_time"></span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-6 flex justify-center space-x-4">
                    <button type="button" id="clear_button" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        <i class="bx bx-refresh mr-2"></i>
                        Clear
                    </button>
                    <a href="{{ route('gate-staff.scan-history') }}" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors">
                        <i class="bx bx-history mr-2"></i>
                        View History
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const qrcodeInput = document.getElementById('qrcode_input');
    const scanButton = document.getElementById('scan_button');
    const clearButton = document.getElementById('clear_button');
    const scanResult = document.getElementById('scan_result');
    const eventSelect = document.getElementById('event_id');
    const gateSelect = document.getElementById('gate_id');

    // Auto-focus on QR code input
    qrcodeInput.focus();

    // Handle scan button click
    scanButton.addEventListener('click', function() {
        const qrcode = qrcodeInput.value.trim();
        const eventId = eventSelect.value;
        const gateId = gateSelect.value;

        if (!qrcode) {
            alert('Please enter a QR code or ticket ID');
            return;
        }

        if (!eventId) {
            alert('Please select an event');
            return;
        }

        scanTicket(qrcode, eventId, gateId);
    });

    // Handle Enter key in QR code input
    qrcodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            scanButton.click();
        }
    });

    // Handle clear button
    clearButton.addEventListener('click', function() {
        qrcodeInput.value = '';
        scanResult.classList.add('hidden');
        qrcodeInput.focus();
    });

    // Auto-clear result after 5 seconds
    let clearTimeout;

    function scanTicket(qrcode, eventId, gateId) {
        // Show loading state
        scanButton.disabled = true;
        scanButton.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i>Scanning...';
        scanResult.classList.add('hidden');

        // Make API call
        fetch('{{ route("gate-staff.scan-ticket") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                qrcode: qrcode,
                event_id: eventId,
                gate_id: gateId
            })
        })
        .then(response => response.json())
        .then(data => {
            displayScanResult(data);
            
            // Clear input and focus for next scan
            qrcodeInput.value = '';
            qrcodeInput.focus();
            
            // Auto-clear result after 5 seconds
            clearTimeout = setTimeout(() => {
                scanResult.classList.add('hidden');
            }, 5000);
        })
        .catch(error => {
            console.error('Error:', error);
            displayScanResult({
                status: 'ERROR',
                message: 'Network error occurred',
                performance_time: 0
            });
        })
        .finally(() => {
            // Reset button state
            scanButton.disabled = false;
            scanButton.innerHTML = '<i class="bx bx-qr-scan mr-2"></i>Scan';
        });
    }

    function displayScanResult(data) {
        const resultIcon = document.getElementById('result_icon');
        const resultTitle = document.getElementById('result_title');
        const resultMessage = document.getElementById('result_message');
        const ticketDetails = document.getElementById('ticket_details');
        const performanceTime = document.getElementById('performance_time');

        // Clear previous timeout
        if (clearTimeout) {
            clearTimeout();
        }

        // Set result styling based on status
        let iconClass, titleClass, messageClass, borderClass;

        switch(data.status) {
            case 'SUCCESS':
                iconClass = 'bx-check-circle bg-green-100 text-green-600';
                titleClass = 'text-green-600';
                messageClass = 'text-green-700';
                borderClass = 'border-green-200 bg-green-50';
                break;
            case 'DUPLICATE':
                iconClass = 'bx-x-circle bg-red-100 text-red-600';
                titleClass = 'text-red-600';
                messageClass = 'text-red-700';
                borderClass = 'border-red-200 bg-red-50';
                break;
            case 'INVALID':
            case 'ERROR':
                iconClass = 'bx-error bg-red-100 text-red-600';
                titleClass = 'text-red-600';
                messageClass = 'text-red-700';
                borderClass = 'border-red-200 bg-red-50';
                break;
            case 'WRONG_GATE':
            case 'WRONG_EVENT':
                iconClass = 'bx-error-circle bg-yellow-100 text-yellow-600';
                titleClass = 'text-yellow-600';
                messageClass = 'text-yellow-700';
                borderClass = 'border-yellow-200 bg-yellow-50';
                break;
            default:
                iconClass = 'bx-question-mark bg-gray-100 text-gray-600';
                titleClass = 'text-gray-600';
                messageClass = 'text-gray-700';
                borderClass = 'border-gray-200 bg-gray-50';
        }

        // Update result display
        resultIcon.className = `w-16 h-16 rounded-full flex items-center justify-center text-3xl ${iconClass}`;
        resultTitle.className = `text-xl font-bold text-center mb-2 ${titleClass}`;
        resultMessage.className = `text-center mb-4 ${messageClass}`;
        
        // Update content
        resultTitle.textContent = data.status;
        resultMessage.textContent = data.message;
        performanceTime.textContent = `Scan completed in ${data.performance_time || 0}ms`;

        // Show ticket details for successful scans
        if (data.status === 'SUCCESS' && data.ticket) {
            document.getElementById('ticket_event').textContent = data.ticket.event_name || 'N/A';
            document.getElementById('ticket_zone').textContent = data.ticket.seat_price_zone || 'N/A';
            document.getElementById('ticket_seat').textContent = `${data.ticket.seat_section || ''}${data.ticket.seat_row || ''}-${data.ticket.seat_number || ''}`;
            document.getElementById('ticket_price').textContent = `$${data.ticket.price_paid || '0.00'}`;
            ticketDetails.classList.remove('hidden');
        } else {
            ticketDetails.classList.add('hidden');
        }

        // Update border styling
        const resultContainer = scanResult.querySelector('.p-6');
        resultContainer.className = `p-6 rounded-lg border-2 ${borderClass}`;

        // Show result
        scanResult.classList.remove('hidden');
    }
});
</script>
@endsection
