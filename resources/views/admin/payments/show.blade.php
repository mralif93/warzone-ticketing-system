@extends('layouts.admin')

@section('title', 'Payment Details')
@section('page-title', 'Payment Details')

@section('content')
<!-- Professional Payment Details with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <div class="flex items-center">
                    <a href="{{ route('admin.payments.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Payments
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Payment Details -->
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Payment Details</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-info-circle text-sm'></i>
                                    <span>Payment information</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Transaction ID -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-credit-card text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Transaction ID</span>
                                        <span class="text-base font-medium text-wwc-neutral-900 font-mono">{{ $payment->transaction_id }}</span>
                                    </div>
                                </div>

                                <!-- Order Information -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-receipt text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Order</span>
                                        <div class="text-right">
                                            <div class="text-base font-medium text-wwc-neutral-900">#{{ $payment->order_id }}</div>
                                            <div class="text-xs text-wwc-neutral-500">Total: RM{{ number_format($payment->order->total_amount ?? 0, 0) }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Customer Information -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-user text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Customer</span>
                                        <div class="text-right">
                                            <div class="text-base font-medium text-wwc-neutral-900">{{ $payment->order->user->name ?? 'Guest' }}</div>
                                            <div class="text-xs text-wwc-neutral-500">{{ $payment->order->user->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Amount -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                                            <i class='bx bx-dollar text-sm text-emerald-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Amount</span>
                                        <span class="text-2xl font-bold text-wwc-neutral-900">RM{{ number_format($payment->amount, 2) }}</span>
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
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $payment->method }}</span>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-yellow-100 flex items-center justify-center">
                                            <i class='bx bx-check-circle text-sm text-yellow-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Status</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">
                                            @switch($payment->status)
                                                @case('Succeeded')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-wwc-success text-white">
                                                        <i class='bx bx-check-circle text-xs mr-1'></i>
                                                        Succeeded
                                                    </span>
                                                    @break
                                                @case('Pending')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-wwc-warning text-white">
                                                        <i class='bx bx-time text-xs mr-1'></i>
                                                        Pending
                                                    </span>
                                                    @break
                                                @case('Failed')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-wwc-error text-white">
                                                        <i class='bx bx-x text-xs mr-1'></i>
                                                        Failed
                                                    </span>
                                                    @break
                                                @case('Cancelled')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-wwc-neutral-400 text-white">
                                                        <i class='bx bx-x text-xs mr-1'></i>
                                                        Cancelled
                                                    </span>
                                                    @break
                                                @case('Refunded')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-wwc-info text-white">
                                                        <i class='bx bx-undo text-xs mr-1'></i>
                                                        Refunded
                                                    </span>
                                                    @break
                                            @endswitch
                                        </span>
                                    </div>
                                </div>

                                <!-- Payment Date -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                            <i class='bx bx-calendar text-sm text-indigo-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Payment Date</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">
                                            {{ $payment->payment_date ? $payment->payment_date->format('M d, Y h:i A') : 'Not set' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Processed Date -->
                                @if($payment->processed_at)
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-teal-100 flex items-center justify-center">
                                            <i class='bx bx-check text-sm text-teal-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Processed Date</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">
                                            {{ $payment->processed_at->format('M d, Y h:i A') }}
                                        </span>
                                    </div>
                                </div>
                                @endif

                                <!-- Refund Information -->
                                @if($payment->refund_amount)
                                <div class="py-3">
                                    <div class="bg-wwc-error-light rounded-lg p-4">
                                        <div class="flex items-center mb-3">
                                            <div class="h-8 w-8 rounded-lg bg-wwc-error flex items-center justify-center mr-3">
                                                <i class='bx bx-undo text-sm text-white'></i>
                                            </div>
                                            <h4 class="text-sm font-semibold text-wwc-neutral-900">Refund Information</h4>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-medium text-wwc-neutral-600">Refund Amount</span>
                                                <span class="text-sm font-semibold text-wwc-error">RM{{ number_format($payment->refund_amount, 2) }}</span>
                                            </div>
                                            
                                            @if($payment->refund_date)
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-medium text-wwc-neutral-600">Refund Date</span>
                                                <span class="text-sm font-semibold text-wwc-neutral-900">{{ $payment->refund_date->format('M d, Y') }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        @if($payment->refund_reason)
                                        <div class="mt-3 p-2 bg-white rounded text-xs text-wwc-neutral-600">
                                            <i class='bx bx-info-circle text-xs mr-1'></i>
                                            {{ $payment->refund_reason }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                <!-- Notes -->
                                @if($payment->notes)
                                <div class="flex items-center py-3">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-teal-100 flex items-center justify-center">
                                            <i class='bx bx-note text-sm text-teal-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Notes</span>
                                        <span class="text-base font-medium text-wwc-neutral-900 leading-relaxed text-right max-w-md">{{ $payment->notes }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Order Tickets -->
                    @if($payment->order && $payment->order->purchaseTickets->count() > 0)
                    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Order Tickets</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-ticket text-sm'></i>
                                    <span>Purchased tickets</span>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-wwc-neutral-100">
                                <thead class="bg-wwc-neutral-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Ticket ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Event</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Ticket Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Event Day</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Price</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-wwc-neutral-100">
                                    @foreach($payment->order->purchaseTickets as $ticket)
                                    <tr class="hover:bg-wwc-neutral-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-wwc-neutral-900">
                                            #{{ $ticket->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-900">
                                            {{ $ticket->event->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-900">
                                            {{ $ticket->ticketType->name }}
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
                                            @if($ticket->status === 'Sold') bg-wwc-success text-white
                                            @elseif($ticket->status === 'Pending') bg-wwc-warning text-white
                                            @elseif($ticket->status === 'Cancelled') bg-wwc-error text-white
                                            @elseif($ticket->status === 'Refunded') bg-wwc-info text-white
                                            @else bg-wwc-neutral-400 text-white
                                            @endif">
                                                @if($ticket->status === 'Sold')
                                                    <i class='bx bx-check-circle text-xs mr-1'></i>
                                                @elseif($ticket->status === 'Pending')
                                                    <i class='bx bx-time text-xs mr-1'></i>
                                                @elseif($ticket->status === 'Cancelled')
                                                    <i class='bx bx-x text-xs mr-1'></i>
                                                @elseif($ticket->status === 'Refunded')
                                                    <i class='bx bx-undo text-xs mr-1'></i>
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
                    <!-- Payment Statistics -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Payment Statistics</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-bar-chart text-sm'></i>
                                    <span>Payment metrics</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Payment Amount -->
                                <div>
                                    <div class="flex justify-between text-sm font-semibold mb-2">
                                        <span class="text-wwc-neutral-600">Payment Amount</span>
                                        <span class="text-wwc-neutral-900">RM{{ number_format($payment->amount, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                        <div class="bg-wwc-primary h-2 rounded-full" style="width: 100%"></div>
                                    </div>
                                </div>
                                
                                <!-- Order Total -->
                                <div>
                                    <div class="flex justify-between text-sm font-semibold mb-2">
                                        <span class="text-wwc-neutral-600">Order Total</span>
                                        <span class="text-wwc-neutral-900">RM{{ number_format($payment->order->total_amount ?? 0, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                        <div class="bg-wwc-success h-2 rounded-full" style="width: 100%"></div>
                                    </div>
                                </div>
                                
                                @if($payment->refund_amount)
                                <!-- Refund Amount -->
                                <div>
                                    <div class="flex justify-between text-sm font-semibold mb-2">
                                        <span class="text-wwc-neutral-600">Refund Amount</span>
                                        <span class="text-wwc-error">RM{{ number_format($payment->refund_amount, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                        <div class="bg-wwc-error h-2 rounded-full" style="width: {{ ($payment->refund_amount / $payment->amount) * 100 }}%"></div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="mt-6 pt-4 border-t border-wwc-neutral-200">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-wwc-neutral-900 font-display">
                                        @if($payment->status === 'Succeeded')
                                            100%
                                        @elseif($payment->status === 'Pending')
                                            0%
                                        @elseif($payment->status === 'Failed')
                                            0%
                                        @elseif($payment->status === 'Refunded')
                                            0%
                                        @else
                                            0%
                                        @endif
                                    </div>
                                    <div class="text-sm text-wwc-neutral-600 font-medium">Success Rate</div>
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
                                    <span>Payment actions</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <a href="{{ route('admin.payments.edit', $payment) }}" 
                                   class="w-full bg-wwc-primary hover:bg-wwc-primary-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                    <i class='bx bx-edit text-sm mr-2'></i>
                                    Edit Payment
                                </a>
                                
                                @if($payment->status === 'Succeeded' && !$payment->refund_amount)
                                <form method="POST" action="{{ route('admin.payments.refund', $payment) }}" class="w-full">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full bg-wwc-error hover:bg-wwc-error-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-sm">
                                        <i class='bx bx-undo text-sm mr-2'></i>
                                        Process Refund
                                    </button>
                                </form>
                                @endif
                                
                                @if($payment->status === 'Pending')
                                <form method="POST" action="{{ route('admin.payments.change-status', $payment) }}" class="w-full">
                                    @csrf
                                    <input type="hidden" name="status" value="Succeeded">
                                    <button type="submit" 
                                            class="w-full bg-wwc-success hover:bg-wwc-success-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-sm">
                                        <i class='bx bx-check text-sm mr-2'></i>
                                        Mark as Succeeded
                                    </button>
                                </form>
                                @endif
                                
                                <a href="{{ route('admin.orders.show', $payment->order) }}" 
                                   class="w-full bg-wwc-info hover:bg-wwc-info-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                    <i class='bx bx-receipt text-sm mr-2'></i>
                                    View Order
                                </a>
                                
                                <a href="{{ route('admin.payments.index') }}" 
                                   class="w-full bg-wwc-neutral-600 hover:bg-wwc-neutral-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                                    Back to Payments
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection