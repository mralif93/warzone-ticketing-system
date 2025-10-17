<?php $__env->startSection('title', 'Edit Payment'); ?>
<?php $__env->startSection('page-title', 'Edit Payment'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional Payment Editing with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <a href="<?php echo e(route('admin.payments.show', $payment)); ?>" 
                    class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                    Back to Payment
                </a>
            </div>

            <!-- Form Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Edit Payment Details</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-edit text-sm'></i>
                            <span>Update payment information</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form action="<?php echo e(route('admin.payments.update', $payment)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <?php if($errors->any()): ?>
                            <div class="bg-wwc-error-light border border-wwc-error text-wwc-error px-4 py-3 rounded-lg mb-6">
                                <div class="flex items-start">
                                    <i class='bx bx-error text-lg mr-3 mt-0.5 flex-shrink-0'></i>
                                    <div>
                                        <h3 class="font-semibold mb-2 text-sm">Please correct the following errors:</h3>
                                        <ul class="list-disc list-inside space-y-1 text-sm">
                                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><?php echo e($error); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- Order -->
                                <div>
                                    <label for="order_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Order <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="order_id" id="order_id" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['order_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">Select Order</option>
                                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($order->id); ?>" <?php echo e(old('order_id', $payment->order_id) == $order->id ? 'selected' : ''); ?>>
                                                Order #<?php echo e($order->id); ?> - <?php echo e($order->user->name ?? 'Guest'); ?> (RM<?php echo e(number_format($order->total_amount, 0)); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['order_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-wwc-error text-xs mt-1 font-medium"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Amount -->
                                <div>
                                    <label for="amount" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Amount (RM) <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="number" name="amount" id="amount" required step="0.01" min="0.01"
                                           value="<?php echo e(old('amount', $payment->amount)); ?>"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           placeholder="Enter payment amount">
                                    <div class="text-xs text-wwc-neutral-500 mt-1">
                                        <span id="order-total-info" class="hidden">Order total: RM<span id="order-total-amount">0.00</span></span>
                                    </div>
                                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-wwc-error text-xs mt-1 font-medium"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Payment Method -->
                                <div>
                                    <label for="method" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Payment Method <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="method" id="method" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">Select Payment Method</option>
                                        <option value="Credit Card" <?php echo e(old('method', $payment->method) == 'Credit Card' ? 'selected' : ''); ?>>Credit Card</option>
                                        <option value="Debit Card" <?php echo e(old('method', $payment->method) == 'Debit Card' ? 'selected' : ''); ?>>Debit Card</option>
                                        <option value="Bank Transfer" <?php echo e(old('method', $payment->method) == 'Bank Transfer' ? 'selected' : ''); ?>>Bank Transfer</option>
                                        <option value="E-Wallet" <?php echo e(old('method', $payment->method) == 'E-Wallet' ? 'selected' : ''); ?>>E-Wallet</option>
                                        <option value="Cash" <?php echo e(old('method', $payment->method) == 'Cash' ? 'selected' : ''); ?>>Cash</option>
                                        <option value="Other" <?php echo e(old('method', $payment->method) == 'Other' ? 'selected' : ''); ?>>Other</option>
                                    </select>
                                    <?php $__errorArgs = ['method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-wwc-error text-xs mt-1 font-medium"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Status <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="status" id="status" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">Select Status</option>
                                        <option value="Pending" <?php echo e(old('status', $payment->status) == 'Pending' ? 'selected' : ''); ?>>Pending</option>
                                        <option value="Succeeded" <?php echo e(old('status', $payment->status) == 'Succeeded' ? 'selected' : ''); ?>>Succeeded</option>
                                        <option value="Failed" <?php echo e(old('status', $payment->status) == 'Failed' ? 'selected' : ''); ?>>Failed</option>
                                        <option value="Cancelled" <?php echo e(old('status', $payment->status) == 'Cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                                        <option value="Refunded" <?php echo e(old('status', $payment->status) == 'Refunded' ? 'selected' : ''); ?>>Refunded</option>
                                    </select>
                                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-wwc-error text-xs mt-1 font-medium"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Transaction ID -->
                                <div>
                                    <label for="transaction_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Transaction ID
                                    </label>
                                    <input type="text" name="transaction_id" id="transaction_id"
                                           value="<?php echo e(old('transaction_id', $payment->transaction_id)); ?>"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['transaction_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           placeholder="Enter transaction ID">
                                    <?php $__errorArgs = ['transaction_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-wwc-error text-xs mt-1 font-medium"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Payment Date -->
                                <div>
                                    <label for="payment_date" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Payment Date
                                    </label>
                                    <input type="datetime-local" name="payment_date" id="payment_date"
                                           value="<?php echo e(old('payment_date', $payment->payment_date ? $payment->payment_date->format('Y-m-d\TH:i') : '')); ?>"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['payment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['payment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-wwc-error text-xs mt-1 font-medium"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Notes -->
                                <div class="sm:col-span-2">
                                    <label for="notes" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Notes
                                    </label>
                                    <textarea name="notes" id="notes" rows="3"
                                              class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                              placeholder="Enter any additional notes about this payment"><?php echo e(old('notes', $payment->notes)); ?></textarea>
                                    <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-wwc-error text-xs mt-1 font-medium"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-wwc-neutral-200 mt-6">
                            <a href="<?php echo e(route('admin.payments.show', $payment)); ?>" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-check text-sm mr-2'></i>
                                Update Payment
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
    
    // Order data for dynamic loading
    const orderData = <?php echo json_encode($orderData, 15, 512) ?>;
    
    // Set minimum date to today for payment date
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    
    const minDateTime = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
    paymentDateInput.min = minDateTime;
    
    // Handle order selection change
    orderSelect.addEventListener('change', function() {
        const selectedOrderId = this.value;
        
        if (selectedOrderId && orderData[selectedOrderId]) {
            const order = orderData[selectedOrderId];
            
            // Show order total info
            orderTotalAmount.textContent = parseFloat(order.total_amount).toFixed(2);
            orderTotalInfo.classList.remove('hidden');
            
            // Add visual feedback
            amountInput.classList.add('bg-green-50', 'border-green-300');
            setTimeout(() => {
                amountInput.classList.remove('bg-green-50', 'border-green-300');
            }, 2000);
        } else {
            // Hide info
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
    
    // Initialize order total info if order is already selected
    if (orderSelect.value && orderData[orderSelect.value]) {
        const order = orderData[orderSelect.value];
        orderTotalAmount.textContent = parseFloat(order.total_amount).toFixed(2);
        orderTotalInfo.classList.remove('hidden');
    }
    
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/admin/payments/edit.blade.php ENDPATH**/ ?>