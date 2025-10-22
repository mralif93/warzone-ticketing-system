@extends('layouts.admin')

@section('title', 'Create Payment')
@section('page-title', 'Create Payment')

@section('content')
<!-- Professional Payment Creation with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <a href="{{ route('admin.payments.index') }}" 
                    class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                    Back to Payments
                </a>
            </div>

            <!-- Form Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Payment Details</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-edit text-sm'></i>
                            <span>Fill in the information below</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.payments.store') }}" method="POST">
                        @csrf
                        
                        @if ($errors->any())
                            <div class="bg-wwc-error-light border border-wwc-error text-wwc-error px-4 py-3 rounded-lg mb-6">
                                <div class="flex items-start">
                                    <i class='bx bx-error text-lg mr-3 mt-0.5 flex-shrink-0'></i>
                                    <div>
                                        <h3 class="font-semibold mb-2 text-sm">Please correct the following errors:</h3>
                                        <ul class="list-disc list-inside space-y-1 text-sm">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- Order -->
                                <div>
                                    <label for="order_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Order <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="order_id" id="order_id" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('order_id') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select Order</option>
                                        @foreach($orders as $order)
                                            <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                                Order #{{ $order->id }} - {{ $order->user->name ?? 'Guest' }} (RM{{ number_format($order->total_amount, 0) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('order_id')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Amount -->
                                <div>
                                    <label for="amount" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Amount (RM) <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="number" name="amount" id="amount" required step="0.01" min="0.01"
                                           value="{{ old('amount') }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('amount') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter payment amount">
                                    <div class="text-xs text-wwc-neutral-500 mt-1">
                                        <span id="order-total-info" class="hidden">Order total: RM<span id="order-total-amount">0.00</span></span>
                                    </div>
                                    @error('amount')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Payment Method -->
                                <div>
                                    <label for="method" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Payment Method <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="method" id="method" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('method') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select Payment Method</option>
                                        <option value="Credit Card" {{ old('method') == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                                        <option value="Debit Card" {{ old('method') == 'Debit Card' ? 'selected' : '' }}>Debit Card</option>
                                        <option value="Bank Transfer" {{ old('method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="E-Wallet" {{ old('method') == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                                        <option value="Cash" {{ old('method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="Other" {{ old('method') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('method')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Status <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="status" id="status" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('status') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select Status</option>
                                        <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Succeeded" {{ old('status') == 'Succeeded' ? 'selected' : '' }}>Succeeded</option>
                                        <option value="Failed" {{ old('status') == 'Failed' ? 'selected' : '' }}>Failed</option>
                                        <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        <option value="Refunded" {{ old('status') == 'Refunded' ? 'selected' : '' }}>Refunded</option>
                                    </select>
                                    @error('status')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Transaction ID -->
                                <div>
                                    <label for="transaction_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Transaction ID
                                    </label>
                                    <input type="text" name="transaction_id" id="transaction_id"
                                           value="{{ old('transaction_id') }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('transaction_id') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Leave blank to auto-generate">
                                    @error('transaction_id')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Payment Date -->
                                <div>
                                    <label for="payment_date" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Payment Date
                                    </label>
                                    <input type="datetime-local" name="payment_date" id="payment_date"
                                           value="{{ old('payment_date') }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('payment_date') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                    @error('payment_date')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Notes -->
                                <div class="sm:col-span-2">
                                    <label for="notes" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Notes
                                    </label>
                                    <textarea name="notes" id="notes" rows="3"
                                              class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('notes') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                              placeholder="Enter any additional notes about this payment">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-wwc-neutral-200 mt-6">
                            <a href="{{ route('admin.payments.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-plus text-sm mr-2'></i>
                                Create Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderSelect = document.getElementById('order_id');
    const amountInput = document.getElementById('amount');
    const orderTotalInfo = document.getElementById('order-total-info');
    const orderTotalAmount = document.getElementById('order-total-amount');
    const paymentDateInput = document.getElementById('payment_date');
    const transactionIdInput = document.getElementById('transaction_id');
    
    // Order data for dynamic loading
    const orderData = @json($orderData);
    
    // Set minimum date to today for payment date
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    
    const minDateTime = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
    paymentDateInput.min = minDateTime;
    
    // Set default payment date to now if not provided
    if (!paymentDateInput.value) {
        paymentDateInput.value = minDateTime;
    }
    
    // Auto-generate transaction ID if not provided
    if (!transactionIdInput.value) {
        const generateTransactionId = () => {
            const timestamp = Date.now().toString(36);
            const random = Math.random().toString(36).substring(2, 7);
            return 'TXN_' + timestamp + '_' + random;
        };
        
        transactionIdInput.value = generateTransactionId();
    }
    
    // Handle order selection change
    orderSelect.addEventListener('change', function() {
        const selectedOrderId = this.value;
        
        if (selectedOrderId && orderData[selectedOrderId]) {
            const order = orderData[selectedOrderId];
            
            // Auto-populate amount with order total
            amountInput.value = parseFloat(order.total_amount).toFixed(2);
            
            // Show order total info
            orderTotalAmount.textContent = parseFloat(order.total_amount).toFixed(2);
            orderTotalInfo.classList.remove('hidden');
            
            // Add visual feedback
            amountInput.classList.add('bg-green-50', 'border-green-300');
            setTimeout(() => {
                amountInput.classList.remove('bg-green-50', 'border-green-300');
            }, 2000);
        } else {
            // Clear amount and hide info
            amountInput.value = '';
            orderTotalInfo.classList.add('hidden');
        }
    });
    
    // Handle amount input change
    amountInput.addEventListener('input', function() {
        const value = parseFloat(this.value);
        const orderId = orderSelect.value;
        
        if (orderId && orderData[orderId]) {
            const orderTotal = parseFloat(orderData[orderId].total_amount);
            
            if (value > orderTotal) {
                this.classList.add('border-yellow-400', 'bg-yellow-50');
                this.classList.remove('border-green-300', 'bg-green-50');
            } else if (value === orderTotal) {
                this.classList.add('border-green-300', 'bg-green-50');
                this.classList.remove('border-yellow-400', 'bg-yellow-50');
            } else {
                this.classList.remove('border-yellow-400', 'bg-yellow-50', 'border-green-300', 'bg-green-50');
            }
        }
    });
    
    // Form validation enhancement
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const orderId = orderSelect.value;
        const amount = parseFloat(amountInput.value);
        
        if (orderId && orderData[orderId]) {
            const orderTotal = parseFloat(orderData[orderId].total_amount);
            
            if (amount > orderTotal) {
                e.preventDefault();
                alert('Payment amount (RM' + amount.toFixed(2) + ') cannot exceed order total (RM' + orderTotal.toFixed(2) + '). Please adjust the amount.');
                amountInput.focus();
                return false;
            }
        }
    });
});
</script>
@endsection
