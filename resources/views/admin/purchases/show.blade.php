@extends('layouts.admin')

@section('title', 'Purchase Details')
@section('page-title', 'Purchase Details')

@section('content')
<!-- Professional Purchase Details with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <div class="flex items-center">
                    <a href="{{ route('admin.purchases.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Purchases
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Purchase Details -->
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Purchase Details</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-info-circle text-sm'></i>
                                    <span>Purchase information</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- QR Code -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-qr-scan text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">QR Code</span>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-base font-medium text-wwc-neutral-900 font-mono">{{ $purchase->qrcode }}</span>
                                            <button type="button" onclick="copyToClipboard('{{ $purchase->qrcode }}')" 
                                                    class="text-wwc-neutral-400 hover:text-wwc-neutral-600">
                                                <i class='bx bx-copy text-sm'></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-check-circle text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Status</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            @if($purchase->status === 'scanned') bg-green-100 text-green-800
                                            @elseif($purchase->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($purchase->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($purchase->status) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Customer -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-user text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Customer</span>
                                        <div class="text-right">
                                            <div class="text-base font-medium text-wwc-neutral-900">{{ $purchase->order->user->name }}</div>
                                            <div class="text-sm text-wwc-neutral-500">{{ $purchase->order->user->email }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Event -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-red-100 flex items-center justify-center">
                                            <i class='bx bx-calendar text-sm text-red-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Event</span>
                                        <div class="text-right">
                                            <div class="text-base font-medium text-wwc-neutral-900">{{ $purchase->event->name }}</div>
                                            <div class="text-sm text-wwc-neutral-500">{{ $purchase->event->date_time->format('M d, Y H:i') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ticket Type -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                            <i class='bx bx-ticket text-sm text-orange-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Ticket Type</span>
                                        <div class="text-right">
                                            <div class="text-base font-medium text-wwc-neutral-900">{{ $purchase->ticketType->name ?? 'N/A' }}</div>
                                            @if($purchase->is_combo_purchase)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-800 mt-1">
                                                    <i class='bx bx-discount text-xs mr-1'></i>
                                                    Combo Purchase
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Price Paid -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-money text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Price Paid</span>
                                        <div class="text-right">
                                            <div class="text-base font-medium text-wwc-neutral-900">RM{{ number_format($purchase->price_paid, 2) }}</div>
                                            @if($purchase->discount_amount > 0)
                                                <div class="text-sm text-green-600">-RM{{ number_format($purchase->discount_amount, 2) }} discount</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Purchase Date -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-time text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Purchase Date</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $purchase->created_at->format('M d, Y H:i:s') }}</span>
                                    </div>
                                </div>

                                @if($purchase->scanned_at)
                                <!-- Scanned Date -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-check text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Scanned Date</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $purchase->scanned_at->format('M d, Y H:i:s') }}</span>
                                    </div>
                                </div>
                                @endif

                                @if($purchase->event_day)
                                <!-- Event Day -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                            <i class='bx bx-calendar-event text-sm text-indigo-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Event Day</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $purchase->event_day_name ?: \Carbon\Carbon::parse($purchase->event_day)->format('M j, Y') }}</span>
                                    </div>
                                </div>
                                @endif

                                @if($purchase->combo_group_id)
                                <!-- Combo Group ID -->
                                <div class="flex items-center py-3">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-yellow-100 flex items-center justify-center">
                                            <i class='bx bx-group text-sm text-yellow-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Combo Group ID</span>
                                        <span class="text-base font-medium text-wwc-neutral-900 font-mono">{{ $purchase->combo_group_id }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Sidebar -->
                <div class="xl:col-span-1">
                    <!-- Purchase Statistics -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Purchase Statistics</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-bar-chart text-sm'></i>
                                    <span>Purchase metrics</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between text-sm font-semibold mb-2">
                                        <span class="text-wwc-neutral-600">Original Price</span>
                                        <span class="text-wwc-neutral-900">RM{{ number_format($purchase->original_price, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                        <div class="bg-wwc-primary h-2 rounded-full" style="width: 100%"></div>
                                    </div>
                                </div>
                                
                                @if($purchase->discount_amount > 0)
                                <div>
                                    <div class="flex justify-between text-sm font-semibold mb-2">
                                        <span class="text-wwc-neutral-600">Discount</span>
                                        <span class="text-green-600">-RM{{ number_format($purchase->discount_amount, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($purchase->discount_amount / $purchase->original_price) * 100 }}%"></div>
                                    </div>
                                </div>
                                @endif
                                
                                <div>
                                    <div class="flex justify-between text-sm font-semibold mb-2">
                                        <span class="text-wwc-neutral-600">Final Price</span>
                                        <span class="text-wwc-neutral-900">RM{{ number_format($purchase->price_paid, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                        <div class="bg-wwc-success h-2 rounded-full" style="width: {{ ($purchase->price_paid / $purchase->original_price) * 100 }}%"></div>
                                    </div>
                                </div>

                                @if($purchase->discount_amount > 0)
                                <div class="pt-2 border-t border-wwc-neutral-200">
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-green-600">{{ number_format(($purchase->discount_amount / $purchase->original_price) * 100, 1) }}%</div>
                                        <div class="text-xs text-wwc-neutral-600 font-medium">Savings</div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="mt-6 pt-4 border-t border-wwc-neutral-200">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-wwc-neutral-900 font-display">RM{{ number_format($purchase->price_paid, 0) }}</div>
                                    <div class="text-sm text-wwc-neutral-600 font-medium">Total Paid</div>
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
                                    <span>Purchase actions</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <a href="{{ route('admin.purchases.edit', $purchase) }}" 
                                   class="w-full bg-wwc-primary hover:bg-wwc-primary-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                    <i class='bx bx-edit text-sm mr-2'></i>
                                    Edit Purchase
                                </a>
                                
                                @if($purchase->status === 'pending')
                                <form action="{{ route('admin.purchases.mark-scanned', $purchase) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full bg-wwc-success hover:bg-wwc-success-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-sm">
                                        <i class='bx bx-check text-sm mr-2'></i>
                                        Mark as Scanned
                                    </button>
                                </form>
                                @endif
                                
                                @if($purchase->status !== 'cancelled')
                                <form action="{{ route('admin.purchases.cancel', $purchase) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full bg-wwc-error hover:bg-wwc-error-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-sm"
                                            onclick="return confirm('Are you sure you want to cancel this purchase?')">
                                        <i class='bx bx-x text-sm mr-2'></i>
                                        Cancel Purchase
                                    </button>
                                </form>
                                @endif
                                
                                <a href="{{ route('admin.purchases.index') }}" 
                                   class="w-full bg-wwc-neutral-600 hover:bg-wwc-neutral-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                                    Back to Purchases
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
// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        toast.textContent = 'QR Code copied to clipboard!';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 3000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endsection