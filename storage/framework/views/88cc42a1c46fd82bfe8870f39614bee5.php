<?php $__env->startSection('title', 'Payment Details'); ?>
<?php $__env->startSection('page-title', 'Payment Details'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional Payment Details with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <div class="flex items-center">
                    <a href="<?php echo e(route('admin.payments.index')); ?>" 
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
                                        <span class="text-base font-medium text-wwc-neutral-900 font-mono"><?php echo e($payment->transaction_id); ?></span>
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
                                            <div class="text-base font-medium text-wwc-neutral-900">#<?php echo e($payment->order_id); ?></div>
                                            <div class="text-xs text-wwc-neutral-500">Total: RM<?php echo e(number_format($payment->order->total_amount ?? 0, 0)); ?></div>
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
                                            <div class="text-base font-medium text-wwc-neutral-900"><?php echo e($payment->order->user->name ?? 'Guest'); ?></div>
                                            <div class="text-xs text-wwc-neutral-500"><?php echo e($payment->order->user->email ?? 'N/A'); ?></div>
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
                                        <span class="text-2xl font-bold text-wwc-neutral-900">RM<?php echo e(number_format($payment->amount, 2)); ?></span>
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
                                        <span class="text-base font-medium text-wwc-neutral-900"><?php echo e($payment->method); ?></span>
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
                                            <?php switch($payment->status):
                                                case ('Succeeded'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-wwc-success text-white">
                                                        <i class='bx bx-check-circle text-xs mr-1'></i>
                                                        Succeeded
                                                    </span>
                                                    <?php break; ?>
                                                <?php case ('Pending'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-wwc-warning text-white">
                                                        <i class='bx bx-time text-xs mr-1'></i>
                                                        Pending
                                                    </span>
                                                    <?php break; ?>
                                                <?php case ('Failed'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-wwc-error text-white">
                                                        <i class='bx bx-x text-xs mr-1'></i>
                                                        Failed
                                                    </span>
                                                    <?php break; ?>
                                                <?php case ('Cancelled'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-wwc-neutral-400 text-white">
                                                        <i class='bx bx-x text-xs mr-1'></i>
                                                        Cancelled
                                                    </span>
                                                    <?php break; ?>
                                                <?php case ('Refunded'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-wwc-info text-white">
                                                        <i class='bx bx-undo text-xs mr-1'></i>
                                                        Refunded
                                                    </span>
                                                    <?php break; ?>
                                            <?php endswitch; ?>
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
                                            <?php echo e($payment->payment_date ? $payment->payment_date->format('M d, Y h:i A') : 'Not set'); ?>

                                        </span>
                                    </div>
                                </div>

                                <!-- Processed Date -->
                                <?php if($payment->processed_at): ?>
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-teal-100 flex items-center justify-center">
                                            <i class='bx bx-check text-sm text-teal-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Processed Date</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">
                                            <?php echo e($payment->processed_at->format('M d, Y h:i A')); ?>

                                        </span>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Refund Information -->
                                <?php if($payment->refund_amount): ?>
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
                                                <span class="text-sm font-semibold text-wwc-error">RM<?php echo e(number_format($payment->refund_amount, 2)); ?></span>
                                            </div>
                                            
                                            <?php if($payment->refund_date): ?>
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-medium text-wwc-neutral-600">Refund Date</span>
                                                <span class="text-sm font-semibold text-wwc-neutral-900"><?php echo e($payment->refund_date->format('M d, Y')); ?></span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if($payment->refund_reason): ?>
                                        <div class="mt-3 p-2 bg-white rounded text-xs text-wwc-neutral-600">
                                            <i class='bx bx-info-circle text-xs mr-1'></i>
                                            <?php echo e($payment->refund_reason); ?>

                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Notes -->
                                <?php if($payment->notes): ?>
                                <div class="flex items-center py-3">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-teal-100 flex items-center justify-center">
                                            <i class='bx bx-note text-sm text-teal-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Notes</span>
                                        <span class="text-base font-medium text-wwc-neutral-900 leading-relaxed text-right max-w-md"><?php echo e($payment->notes); ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Order Tickets -->
                    <?php if($payment->order && $payment->order->purchaseTickets->count() > 0): ?>
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
                                    <?php $__currentLoopData = $payment->order->purchaseTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-wwc-neutral-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-wwc-neutral-900">
                                            #<?php echo e($ticket->id); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-900">
                                            <?php echo e($ticket->event->name); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-900">
                                            <?php echo e($ticket->ticketType->name); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-900">
                                            <?php if($ticket->event_day): ?>
                                                <?php echo e($ticket->event_day_name ?: \Carbon\Carbon::parse($ticket->event_day)->format('M j, Y')); ?>

                                            <?php else: ?>
                                                <span class="text-wwc-neutral-500">Single Day</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            <?php if($ticket->status === 'Sold'): ?> bg-wwc-success text-white
                                            <?php elseif($ticket->status === 'Pending'): ?> bg-wwc-warning text-white
                                            <?php elseif($ticket->status === 'Cancelled'): ?> bg-wwc-error text-white
                                            <?php elseif($ticket->status === 'Refunded'): ?> bg-wwc-info text-white
                                            <?php else: ?> bg-wwc-neutral-400 text-white
                                            <?php endif; ?>">
                                                <?php if($ticket->status === 'Sold'): ?>
                                                    <i class='bx bx-check-circle text-xs mr-1'></i>
                                                <?php elseif($ticket->status === 'Pending'): ?>
                                                    <i class='bx bx-time text-xs mr-1'></i>
                                                <?php elseif($ticket->status === 'Cancelled'): ?>
                                                    <i class='bx bx-x text-xs mr-1'></i>
                                                <?php elseif($ticket->status === 'Refunded'): ?>
                                                    <i class='bx bx-undo text-xs mr-1'></i>
                                                <?php else: ?>
                                                    <i class='bx bx-x text-xs mr-1'></i>
                                                <?php endif; ?>
                                                <?php echo e($ticket->status); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-wwc-neutral-900">
                                            RM<?php echo e(number_format($ticket->price_paid, 0)); ?>

                                            <?php if($ticket->is_combo_purchase): ?>
                                                <span class="text-xs text-wwc-accent ml-1">(Combo)</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
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
                                        <span class="text-wwc-neutral-900">RM<?php echo e(number_format($payment->amount, 2)); ?></span>
                                    </div>
                                    <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                        <div class="bg-wwc-primary h-2 rounded-full" style="width: 100%"></div>
                                    </div>
                                </div>
                                
                                <!-- Order Total -->
                                <div>
                                    <div class="flex justify-between text-sm font-semibold mb-2">
                                        <span class="text-wwc-neutral-600">Order Total</span>
                                        <span class="text-wwc-neutral-900">RM<?php echo e(number_format($payment->order->total_amount ?? 0, 2)); ?></span>
                                    </div>
                                    <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                        <div class="bg-wwc-success h-2 rounded-full" style="width: 100%"></div>
                                    </div>
                                </div>
                                
                                <?php if($payment->refund_amount): ?>
                                <!-- Refund Amount -->
                                <div>
                                    <div class="flex justify-between text-sm font-semibold mb-2">
                                        <span class="text-wwc-neutral-600">Refund Amount</span>
                                        <span class="text-wwc-error">RM<?php echo e(number_format($payment->refund_amount, 2)); ?></span>
                                    </div>
                                    <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                        <div class="bg-wwc-error h-2 rounded-full" style="width: <?php echo e(($payment->refund_amount / $payment->amount) * 100); ?>%"></div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="mt-6 pt-4 border-t border-wwc-neutral-200">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-wwc-neutral-900 font-display">
                                        <?php if($payment->status === 'Succeeded'): ?>
                                            100%
                                        <?php elseif($payment->status === 'Pending'): ?>
                                            0%
                                        <?php elseif($payment->status === 'Failed'): ?>
                                            0%
                                        <?php elseif($payment->status === 'Refunded'): ?>
                                            0%
                                        <?php else: ?>
                                            0%
                                        <?php endif; ?>
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
                                <a href="<?php echo e(route('admin.payments.edit', $payment)); ?>" 
                                   class="w-full bg-wwc-primary hover:bg-wwc-primary-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                    <i class='bx bx-edit text-sm mr-2'></i>
                                    Edit Payment
                                </a>
                                
                                <?php if($payment->status === 'Succeeded' && !$payment->refund_amount): ?>
                                <form method="POST" action="<?php echo e(route('admin.payments.refund', $payment)); ?>" class="w-full">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" 
                                            class="w-full bg-wwc-error hover:bg-wwc-error-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-sm">
                                        <i class='bx bx-undo text-sm mr-2'></i>
                                        Process Refund
                                    </button>
                                </form>
                                <?php endif; ?>
                                
                                <?php if($payment->status === 'Pending'): ?>
                                <form method="POST" action="<?php echo e(route('admin.payments.change-status', $payment)); ?>" class="w-full">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="status" value="Succeeded">
                                    <button type="submit" 
                                            class="w-full bg-wwc-success hover:bg-wwc-success-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-sm">
                                        <i class='bx bx-check text-sm mr-2'></i>
                                        Mark as Succeeded
                                    </button>
                                </form>
                                <?php endif; ?>
                                
                                <a href="<?php echo e(route('admin.orders.show', $payment->order)); ?>" 
                                   class="w-full bg-wwc-info hover:bg-wwc-info-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                    <i class='bx bx-receipt text-sm mr-2'></i>
                                    View Order
                                </a>
                                
                                <a href="<?php echo e(route('admin.payments.index')); ?>" 
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/admin/payments/show.blade.php ENDPATH**/ ?>