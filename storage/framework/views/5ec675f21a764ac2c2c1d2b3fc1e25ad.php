<?php $__env->startSection('title', 'Order Details'); ?>
<?php $__env->startSection('page-title', 'Order Details'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional Order Details with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <div class="flex items-center">
                    <a href="<?php echo e(route('admin.orders.index')); ?>" 
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
                                        <span class="text-base font-medium text-wwc-neutral-900"><?php echo e($order->order_number); ?></span>
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
                                        <span class="text-base font-medium text-wwc-neutral-900"><?php echo e($order->customer_name); ?></span>
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
                                        <span class="text-base font-medium text-wwc-neutral-900"><?php echo e($order->customer_email); ?></span>
                                    </div>
                                </div>

                                <!-- Customer Phone -->
                                <?php if($order->customer_phone): ?>
                                    <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                        <div class="flex-shrink-0 mr-4">
                                            <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                                <i class='bx bx-phone text-sm text-orange-600'></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 flex items-center justify-between">
                                            <span class="text-sm font-semibold text-wwc-neutral-600">Customer Phone</span>
                                            <span class="text-base font-medium text-wwc-neutral-900"><?php echo e($order->customer_phone); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Subtotal -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                                            <i class='bx bx-dollar text-sm text-emerald-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Subtotal</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">RM<?php echo e(number_format($order->subtotal, 0)); ?></span>
                                    </div>
                                </div>

                                <!-- Service Fee -->
                                <?php if($order->service_fee > 0): ?>
                                    <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                        <div class="flex-shrink-0 mr-4">
                                            <div class="h-8 w-8 rounded-lg bg-yellow-100 flex items-center justify-center">
                                                <i class='bx bx-cog text-sm text-yellow-600'></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 flex items-center justify-between">
                                            <span class="text-sm font-semibold text-wwc-neutral-600">Service Fee</span>
                                            <span class="text-base font-medium text-wwc-neutral-900">RM<?php echo e(number_format($order->service_fee, 0)); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Tax Amount -->
                                <?php if($order->tax_amount > 0): ?>
                                    <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                        <div class="flex-shrink-0 mr-4">
                                            <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                                <i class='bx bx-calculator text-sm text-indigo-600'></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 flex items-center justify-between">
                                            <span class="text-sm font-semibold text-wwc-neutral-600">Tax Amount</span>
                                            <span class="text-base font-medium text-wwc-neutral-900">RM<?php echo e(number_format($order->tax_amount, 0)); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Total Amount -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-teal-100 flex items-center justify-center">
                                            <i class='bx bx-credit-card text-sm text-teal-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Total Amount</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">RM<?php echo e(number_format($order->total_amount, 0)); ?></span>
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
                                            <?php if($order->status === 'Paid'): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class='bx bx-check text-xs mr-1'></i>
                                                    Paid
                                                </span>
                                            <?php elseif($order->status === 'Pending'): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class='bx bx-time text-xs mr-1'></i>
                                                    Pending
                                                </span>
                                            <?php elseif($order->status === 'Cancelled'): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class='bx bx-x text-xs mr-1'></i>
                                                    Cancelled
                                                </span>
                                            <?php elseif($order->status === 'Refunded'): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class='bx bx-undo text-xs mr-1'></i>
                                                    Refunded
                                                </span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <?php if($order->notes): ?>
                                    <div class="flex items-center py-3">
                                        <div class="flex-shrink-0 mr-4">
                                            <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <i class='bx bx-note text-sm text-gray-600'></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 flex items-center justify-between">
                                            <span class="text-sm font-semibold text-wwc-neutral-600">Notes</span>
                                            <span class="text-base font-medium text-wwc-neutral-900"><?php echo e($order->notes); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Order Statistics -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Order Statistics</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Created Date -->
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-wwc-neutral-900"><?php echo e($order->created_at->format('M d, Y')); ?></div>
                                    <div class="text-sm text-wwc-neutral-500">Created Date</div>
                                </div>
                                <!-- Updated Date -->
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-wwc-neutral-900"><?php echo e($order->updated_at->format('M d, Y')); ?></div>
                                    <div class="text-sm text-wwc-neutral-500">Last Updated</div>
                                </div>
                                <!-- Tickets Count -->
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-wwc-neutral-900"><?php echo e($order->tickets->count()); ?></div>
                                    <div class="text-sm text-wwc-neutral-500">Tickets</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Quick Actions</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <a href="<?php echo e(route('admin.orders.edit', $order)); ?>" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                    <i class='bx bx-edit text-sm mr-2'></i>
                                    Edit Order
                                </a>
                                <?php if($order->status === 'Pending'): ?>
                                    <form action="<?php echo e(route('admin.orders.update-status', $order)); ?>" method="POST" class="block">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="status" value="Paid">
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                            <i class='bx bx-check text-sm mr-2'></i>
                                            Mark as Paid
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <?php if($order->status === 'Paid'): ?>
                                    <a href="<?php echo e(route('admin.orders.refund', $order)); ?>" 
                                       class="w-full inline-flex items-center justify-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200"
                                       onclick="return confirm('Are you sure you want to refund this order?')">
                                        <i class='bx bx-undo text-sm mr-2'></i>
                                        Refund Order
                                    </a>
                                <?php endif; ?>
                                <?php if($order->status !== 'Paid'): ?>
                                    <a href="<?php echo e(route('admin.orders.cancel', $order)); ?>" 
                                       class="w-full inline-flex items-center justify-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200"
                                       onclick="return confirm('Are you sure you want to cancel this order?')">
                                        <i class='bx bx-x text-sm mr-2'></i>
                                        Cancel Order
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo e(route('admin.orders.index')); ?>" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                    <i class='bx bx-list-ul text-sm mr-2'></i>
                                    View All Orders
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/admin/orders/show.blade.php ENDPATH**/ ?>