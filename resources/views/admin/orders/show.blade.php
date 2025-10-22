@extends('layouts.admin')

@section('title', 'Order Details - ' . $order->order_number)
@section('page-title', 'Order Details')

@section('content')
@php
    // Calculate combo discount logic like success page
    $comboDiscountAmount = 0;
    $originalSubtotal = $order->subtotal;
    
    // Check if this was a combo purchase by checking if tickets span multiple days
    $dayNumbers = [];
    foreach ($order->purchaseTickets as $purchaseTicket) {
        if ($purchaseTicket->event_day_name) {
            preg_match('/Day (\d+)/', $purchaseTicket->event_day_name, $matches);
            if (isset($matches[1])) {
                $dayNumbers[] = (int)$matches[1];
            }
        }
    }
    $uniqueDays = array_unique($dayNumbers);
    
    // If we have tickets for multiple days and combo discount is enabled, calculate the discount
    if (count($uniqueDays) > 1 && $order->event && $order->event->combo_discount_enabled) {
        // Calculate original subtotal before discount using original_price
        $originalSubtotal = $order->purchaseTickets->sum('original_price');
        
        // Calculate what the discount would have been
        $comboDiscountAmount = $order->event->calculateComboDiscount($originalSubtotal);
    }
    
    // Calculate the corrected total amount
    $correctedTotal = $originalSubtotal - $comboDiscountAmount + ($serviceFeePercentage == 0 ? 0 : $order->service_fee) + ($taxPercentage == 0 ? 0 : $order->tax_amount);
@endphp
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
                                            <i class='bx bx-file text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Order Number</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $order->order_number }}</span>
                                    </div>
                                </div>

                                <!-- Customer -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-user text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Customer</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $order->user->name ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-time-five text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Status</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($order->status === 'paid') bg-green-100 text-green-800
                                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @elseif($order->status === 'refunded') bg-gray-100 text-gray-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucwords($order->status) }}
                                        </span>
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
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $order->formatted_payment_method ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <!-- Order Date -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                            <i class='bx bx-calendar text-sm text-indigo-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Order Date</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $order->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                        
                                        <!-- Total Amount -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-wwc-accent/20 flex items-center justify-center">
                                            <i class='bx bx-dollar text-sm text-wwc-accent'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Total Amount</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($correctedTotal, 2) }}</span>
                                    </div>
                                </div>

                                <!-- Notes -->
                                @if($order->notes)
                                <div class="flex items-center py-3">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <i class='bx bx-edit text-sm text-gray-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Notes</span>
                                        <p class="text-base font-medium text-wwc-neutral-900 mt-1">{{ $order->notes }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tickets -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mt-6">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Tickets</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-purchase-tag text-sm'></i>
                                    <span>{{ $order->purchaseTickets->count() }} tickets</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($order->purchaseTickets->count() > 0)
                                @php
                                    // Group tickets by type and day for display like success page
                                    $ticketGroups = [];
                                    foreach ($order->purchaseTickets as $purchaseTicket) {
                                        $key = $purchaseTicket->ticket_type_id . '_' . ($purchaseTicket->event_day_name ?? 'default');
                                        if (!isset($ticketGroups[$key])) {
                                            // Extract day number from event_day_name (e.g., "Day 1" -> 1)
                                            $dayNumber = null;
                                            if ($purchaseTicket->event_day_name) {
                                                preg_match('/Day (\d+)/', $purchaseTicket->event_day_name, $matches);
                                                $dayNumber = isset($matches[1]) ? (int)$matches[1] : null;
                                            }
                                            
                                            $ticketGroups[$key] = [
                                                'ticket' => $purchaseTicket->ticketType,
                                                'quantity' => 0,
                                                'price' => $purchaseTicket->original_price,
                                                'day' => $dayNumber,
                                                'day_name' => $purchaseTicket->event_day_name ?? null,
                                                'event' => $purchaseTicket->event,
                                                'status' => $purchaseTicket->status
                                            ];
                                        }
                                        $ticketGroups[$key]['quantity']++;
                                    }
                                    
                                    // Convert to array for view
                                    $tickets = array_values($ticketGroups);
                                @endphp
                                
                                <div class="space-y-4">
                                    @foreach($tickets as $ticketData)
                                    <div class="bg-wwc-neutral-50 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center space-x-3">
                                                <div class="h-8 w-8 rounded-lg bg-wwc-primary flex items-center justify-center">
                                                    <i class='bx bx-purchase-tag text-sm text-white'></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-semibold text-wwc-neutral-900">
                                                        {{ $ticketData['ticket']->name ?? 'N/A' }} 
                                                        @if($ticketData['quantity'] > 1)
                                                            <span class="text-xs text-wwc-neutral-600">x {{ $ticketData['quantity'] }}</span>
                                                        @endif
                                                    </h4>
                                                    <p class="text-xs text-wwc-neutral-600">{{ $ticketData['event']->name ?? 'N/A' }}</p>
                                                    @if($ticketData['day_name'])
                                                        <p class="text-xs text-wwc-neutral-500">{{ $ticketData['day_name'] }} - {{ $ticketData['event']->getEventDays()[$ticketData['day']-1]['display'] ?? '' }}</p>
                                                    @else
                                                        <p class="text-xs text-wwc-neutral-500">{{ $ticketData['event']->getFormattedDateRange() ?? 'N/A' }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-bold text-wwc-neutral-900">RM{{ number_format($ticketData['price'] * $ticketData['quantity'], 2) }}</p>
                                                @if($ticketData['quantity'] > 1)
                                                    <p class="text-xs text-wwc-neutral-500">RM{{ number_format($ticketData['price'], 2) }} each</p>
                                                @endif
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                                    @if($ticketData['status'] === 'sold') bg-green-100 text-green-800
                                                    @elseif($ticketData['status'] === 'scanned') bg-blue-100 text-blue-800
                                                    @elseif($ticketData['status'] === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($ticketData['status'] === 'cancelled') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucwords($ticketData['status']) }}
                                                </span>
                                            </div>
                                        </div>
                                        @if($ticketData['day_name'] && $ticketData['day'])
                                        <div class="flex items-center justify-center">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-wwc-accent/20 text-wwc-accent border border-wwc-accent/30">
                                                <i class='bx bx-cart text-xs mr-1'></i>
                                                Multi-Day Combo Purchase
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="h-12 w-12 rounded-full bg-wwc-neutral-100 flex items-center justify-center mx-auto mb-3">
                                        <i class='bx bx-purchase-tag text-xl text-wwc-neutral-400'></i>
                                    </div>
                                    <h4 class="text-sm font-semibold text-wwc-neutral-900 mb-1">No Tickets</h4>
                                    <p class="text-xs text-wwc-neutral-600">This order doesn't have any tickets.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Order Summary -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-100">
                        <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Order Summary</h3>
                            <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-calculator text-sm'></i>
                                    <span>Price breakdown</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-wwc-neutral-600">Subtotal</span>
                                <span class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($originalSubtotal, 2) }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-wwc-neutral-600">Combo Discount ({{ number_format($order->event->getComboDiscountPercentage(), 2) }}%)</span>
                                <span class="text-base font-medium text-green-600">-RM{{ number_format($comboDiscountAmount, 2) }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-wwc-neutral-600">Service Fee ({{ $serviceFeePercentage }}%)</span>
                                <span class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($serviceFeePercentage == 0 ? 0 : $order->service_fee, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-wwc-neutral-600">Tax ({{ $taxPercentage }}%)</span>
                                <span class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($taxPercentage == 0 ? 0 : $order->tax_amount, 2) }}</span>
                            </div>
                            <div class="border-t border-wwc-neutral-200 pt-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-wwc-neutral-900">Total</span>
                                    <span class="text-xl font-bold text-wwc-neutral-900">RM{{ number_format($correctedTotal, 2) }}</span>
                                </div>
                            </div>
                                </div>
                            </div>
                            
                    <!-- Customer Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Customer Info</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-user text-sm'></i>
                                    <span>Customer details</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                        <i class='bx bx-user text-sm text-green-600'></i>
                                </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Name</span>
                                </div>
                                <span class="text-base font-medium text-wwc-neutral-900">{{ $order->user->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i class='bx bx-envelope text-sm text-blue-600'></i>
                                    </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Email</span>
                                </div>
                                <span class="text-base font-medium text-wwc-neutral-900">{{ $order->user->email ?? 'N/A' }}</span>
                            </div>
                            @if($order->user->phone ?? null)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                        <i class='bx bx-phone text-sm text-purple-600'></i>
                                    </div>
                                    <span class="text-sm font-semibold text-wwc-neutral-600">Phone</span>
                        </div>
                                <span class="text-base font-medium text-wwc-neutral-900">{{ $order->user->phone }}</span>
                            </div>
                            @endif
                    </div>
                </div>

                    <!-- Actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-100">
                        <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Actions</h3>
                            <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                <i class='bx bx-cog text-sm'></i>
                                    <span>Quick actions</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('admin.orders.edit', $order) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-primary text-white text-sm font-semibold rounded-lg hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                    <i class='bx bx-edit text-sm mr-2'></i>
                                Edit Order
                            </a>
                            
                            <a href="{{ route('admin.users.show', $order->user) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-wwc-info text-white text-sm font-semibold rounded-lg hover:bg-wwc-info-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-info transition-colors duration-200">
                                <i class='bx bx-user text-sm mr-2'></i>
                                View Customer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection