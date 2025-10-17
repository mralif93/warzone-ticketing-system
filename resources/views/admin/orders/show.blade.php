@extends('layouts.admin')

@section('title', 'Order Details')
@section('page-title', 'Order Details')

@section('content')
<!-- Professional Order Details with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <div class="flex items-center">
                    <a href="{{ route('admin.orders.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Orders
                    </a>
            </div>
        </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Order Details -->
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Order Details</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-info-circle text-sm'></i>
                                    <span>Order information</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Order Number -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-receipt text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Order Number</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $order->order_number }}</span>
                                    </div>
                                </div>

                                <!-- Customer Name -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-user text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Customer Name</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $order->user->name ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <!-- Customer Email -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-envelope text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Customer Email</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $order->customer_email }}</span>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                            <i class='bx bx-credit-card text-sm text-orange-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Payment Method</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $order->payment_method ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <!-- Event -->
                                @if($order->tickets->count() > 0)
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-calendar-event text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Event</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $order->tickets->first()->event->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                @endif

                                <!-- Pricing Information -->
                                <div class="py-3 border-b border-wwc-neutral-100">
                                    <div class="flex items-center mb-3">
                                        <div class="flex-shrink-0 mr-4">
                                            <div class="h-8 w-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                                                <i class='bx bx-calculator text-sm text-emerald-600'></i>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <span class="text-sm font-semibold text-wwc-neutral-600">Pricing (Calculated from Tickets)</span>
                                            <div class="text-xs text-wwc-neutral-500 mt-1">Service fee: 5% | Tax: 6%</div>
                                        </div>
                                    </div>
                                    
                                    <div class="ml-12 space-y-2">
                                        <!-- Subtotal -->
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-wwc-neutral-600">Subtotal</span>
                                            <span class="text-sm font-medium text-wwc-neutral-900">RM{{ number_format($order->subtotal, 2) }}</span>
                                        </div>
                                        
                                        <!-- Service Fee -->
                                        @if($order->service_fee > 0)
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-wwc-neutral-600">Service Fee (5%)</span>
                                            <span class="text-sm font-medium text-wwc-neutral-900">RM{{ number_format($order->service_fee, 2) }}</span>
                                        </div>
                                        @endif
                                        
                                        <!-- Tax Amount -->
                                        @if($order->tax_amount > 0)
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-wwc-neutral-600">Tax (6%)</span>
                                            <span class="text-sm font-medium text-wwc-neutral-900">RM{{ number_format($order->tax_amount, 2) }}</span>
                                        </div>
                                        @endif
                                        
                                        <!-- Total Amount -->
                                        <div class="flex items-center justify-between pt-2 border-t border-wwc-neutral-200">
                                            <span class="text-sm font-semibold text-wwc-neutral-900">Total Amount</span>
                                            <span class="text-base font-bold text-wwc-primary">RM{{ number_format($order->total_amount, 2) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <i class='bx bx-check-circle text-sm text-gray-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Status</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">
                                            @if($order->status === 'Paid')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class='bx bx-check text-xs mr-1'></i>
                                                    Paid
                                                </span>
                                            @elseif($order->status === 'Pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class='bx bx-time text-xs mr-1'></i>
                                                    Pending
                                                </span>
                                            @elseif($order->status === 'Cancelled')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class='bx bx-x text-xs mr-1'></i>
                                                    Cancelled
                                                </span>
                                            @elseif($order->status === 'Refunded')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class='bx bx-undo text-xs mr-1'></i>
                                                    Refunded
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <!-- Notes -->
                                @if($order->notes)
                                <div class="flex items-center py-3">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <i class='bx bx-note text-sm text-gray-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Notes</span>
                                        <span class="text-base font-medium text-wwc-neutral-900 leading-relaxed text-right max-w-md">{{ $order->notes }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Recent Purchase Tickets -->
                    @if($order->tickets->count() > 0)
                    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Order Tickets</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-receipt text-sm'></i>
                                    <span>{{ $order->tickets->count() }} ticket(s)</span>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-wwc-neutral-100">
                                <thead class="bg-wwc-neutral-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Ticket ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Zone</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Event Day</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-wwc-neutral-100">
                                    @foreach($order->tickets as $ticket)
                                    <tr class="hover:bg-wwc-neutral-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-wwc-neutral-900 {{ $loop->last ? 'rounded-bl-2xl' : '' }}">
                                            #{{ $ticket->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-900">
                                            {{ $ticket->zone }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-900">
                                            @if($ticket->event_day)
                                                {{ $ticket->event_day_name ?: \Carbon\Carbon::parse($ticket->event_day)->format('M j, Y') }}
                                            @else
                                                <span class="text-wwc-neutral-500">Single Day</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            @if($ticket->status === 'Sold') bg-green-500 text-white
                                            @elseif($ticket->status === 'Pending') bg-yellow-500 text-white
                                            @elseif($ticket->status === 'Cancelled') bg-red-500 text-white
                                            @elseif($ticket->status === 'Refunded') bg-blue-500 text-white
                                            @elseif($ticket->status === 'Held') bg-orange-500 text-white
                                            @elseif($ticket->status === 'Scanned') bg-purple-500 text-white
                                            @else bg-gray-500 text-white
                                            @endif">
                                                @if($ticket->status === 'Sold')
                                                    <i class='bx bx-check-circle text-xs mr-1'></i>
                                                @elseif($ticket->status === 'Pending')
                                                    <i class='bx bx-time text-xs mr-1'></i>
                                                @elseif($ticket->status === 'Cancelled')
                                                    <i class='bx bx-x-circle text-xs mr-1'></i>
                                                @elseif($ticket->status === 'Refunded')
                                                    <i class='bx bx-refresh text-xs mr-1'></i>
                                                @elseif($ticket->status === 'Held')
                                                    <i class='bx bx-time text-xs mr-1'></i>
                                                @elseif($ticket->status === 'Scanned')
                                                    <i class='bx bx-check text-xs mr-1'></i>
                                                @else
                                                    <i class='bx bx-x text-xs mr-1'></i>
                                                @endif
                                                {{ $ticket->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-wwc-neutral-900">
                                            RM{{ number_format($ticket->price_paid, 0) }}
                                            @if($ticket->is_combo_purchase)
                                                <span class="text-xs text-wwc-accent ml-1">(Combo)</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-500 {{ $loop->last ? 'rounded-br-2xl' : '' }}">
                                            <button type="button" 
                                                    onclick="toggleQRCode('{{ $ticket->id }}')"
                                                    class="inline-flex items-center px-3 py-1.5 bg-wwc-primary text-white rounded-lg text-xs font-semibold hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-all duration-200">
                                                <i class='bx bx-qr-scan text-xs mr-1'></i>
                                                <span id="qr-toggle-text-{{ $ticket->id }}">Show QR</span>
                                            </button>
                                        </td>
                                    </tr>
                                    
                                    <!-- Hidden QR Code Row -->
                                    <tr id="qr-section-{{ $ticket->id }}" class="hidden">
                                        <td colspan="6" class="px-6 py-4 bg-wwc-primary-light/5 {{ $loop->last ? 'rounded-b-2xl' : '' }}">
                                            <div class="bg-white rounded-lg p-4 border border-wwc-primary/20">
                                                <div class="flex items-start space-x-4">
                                                    <!-- QR Code Image -->
                                                    <div class="flex-shrink-0">
                                                        <div class="bg-white rounded-lg p-3 shadow-sm border border-wwc-neutral-200">
                                                            <div id="qr-code-{{ $ticket->id }}" class="w-24 h-24 flex items-center justify-center" data-qr="{{ $ticket->qrcode }}">
                                                                <!-- QR code will be generated here -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- QR Code Info -->
                                                    <div class="flex-1">
                                                        <div class="space-y-3">
                                                            <div>
                                                                <label class="text-xs font-semibold text-wwc-neutral-600 mb-1 block">QR Code Data:</label>
                                                                <div class="p-2 bg-wwc-neutral-50 rounded border font-mono text-xs text-wwc-neutral-700 break-all">
                                                                    {{ $ticket->qrcode }}
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="flex space-x-2">
                                                                <button type="button" 
                                                                        onclick="copyQRCode('{{ $ticket->qrcode }}')"
                                                                        class="inline-flex items-center px-3 py-1.5 border border-wwc-neutral-300 rounded text-xs font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary">
                                                                    <i class='bx bx-copy text-xs mr-1'></i>
                                                                    Copy
                                                                </button>
                                                                <button type="button" 
                                                                        onclick="scanQRCode()"
                                                                        class="inline-flex items-center px-3 py-1.5 bg-wwc-primary text-white rounded text-xs font-semibold hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary">
                                                                    <i class='bx bx-scan text-xs mr-1'></i>
                                                                    Scan
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>

            <!-- Statistics Sidebar -->
            <div class="xl:col-span-1">
                <!-- Order Statistics -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                    <div class="px-6 py-4 border-b border-wwc-neutral-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Order Statistics</h3>
                            <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                <i class='bx bx-bar-chart text-sm'></i>
                                <span>Order metrics</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm font-semibold mb-2">
                                    <span class="text-wwc-neutral-600">Total Amount</span>
                                    <span class="text-wwc-neutral-900">RM{{ number_format($order->total_amount, 0) }}</span>
                                </div>
                                <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                    <div class="bg-wwc-primary h-2 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between text-sm font-semibold mb-2">
                                    <span class="text-wwc-neutral-600">Tickets Count</span>
                                    <span class="text-wwc-neutral-900">{{ $order->tickets->count() }}</span>
                                </div>
                                <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                    <div class="bg-wwc-success h-2 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between text-sm font-semibold mb-2">
                                    <span class="text-wwc-neutral-600">Zones</span>
                                    <span class="text-wwc-neutral-900">{{ $order->tickets->groupBy('zone')->count() }}</span>
                                </div>
                                <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                    <div class="bg-wwc-info h-2 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-wwc-neutral-200">
                                <div class="text-center">
                                    <div class="text-lg font-bold text-wwc-neutral-900">{{ $order->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-wwc-neutral-600 font-medium">Created Date</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-wwc-neutral-200">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-wwc-neutral-900 font-display">{{ $order->status }}</div>
                                <div class="text-sm text-wwc-neutral-600 font-medium">Order Status</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Quick Actions</h3>
                            <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                <i class='bx bx-cog text-sm'></i>
                                <span>Order actions</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('admin.orders.edit', $order) }}" 
                                class="w-full bg-wwc-primary hover:bg-wwc-primary-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                    <i class='bx bx-edit text-sm mr-2'></i>
                                Edit Order
                            </a>
                            
                            @if($order->status === 'Pending')
                                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="status" value="Paid">
                                    <button type="submit" 
                                            class="w-full bg-wwc-success hover:bg-wwc-success-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-sm">
                                        <i class='bx bx-check text-sm mr-2'></i>
                                        Mark as Paid
                                    </button>
                                </form>
                            @endif
                            
                            @if($order->status === 'Paid')
                                <a href="{{ route('admin.orders.refund', $order) }}" 
                                   class="w-full bg-wwc-error hover:bg-wwc-error-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm"
                                   onclick="return confirm('Are you sure you want to refund this order?')">
                                    <i class='bx bx-undo text-sm mr-2'></i>
                                    Refund Order
                                </a>
                            @endif
                            
                            @if($order->status !== 'Paid')
                                <a href="{{ route('admin.orders.cancel', $order) }}" 
                                   class="w-full bg-wwc-neutral-600 hover:bg-wwc-neutral-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm"
                                   onclick="return confirm('Are you sure you want to cancel this order?')">
                                    <i class='bx bx-x text-sm mr-2'></i>
                                    Cancel Order
                                </a>
                            @endif
                            
                            <a href="{{ route('admin.orders.index') }}" 
                                class="w-full bg-wwc-neutral-600 hover:bg-wwc-neutral-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                                Back to Orders
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

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
        width: 96,
        height: 96,
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
@endsection