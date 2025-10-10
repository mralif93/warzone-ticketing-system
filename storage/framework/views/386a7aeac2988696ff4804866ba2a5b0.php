<?php $__env->startSection('title', 'Ticket Details'); ?>
<?php $__env->startSection('description', 'View detailed information about your ticket in your Warzone World Championship customer portal.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional Ticket Details -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Header Section -->
    <div class="bg-white border-b border-wwc-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="<?php echo e(route('customer.tickets')); ?>" class="text-wwc-neutral-400 hover:text-wwc-neutral-600 mr-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display">Ticket Details</h1>
                        <p class="text-wwc-neutral-600 mt-1">Ticket #<?php echo e($ticket->id); ?></p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="<?php echo e(route('customer.orders')); ?>" 
                       class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white rounded-lg text-sm font-semibold hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        My Orders
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Ticket Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 mb-8">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900">Ticket Information</h2>
                        <p class="text-wwc-neutral-600 text-sm mt-1">Your ticket details and QR code</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Event Details -->
                            <div>
                                <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-4">Event Details</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">Event Name</p>
                                        <p class="text-lg text-wwc-neutral-900 font-display"><?php echo e($ticket->event->name); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">Date & Time</p>
                                        <p class="text-wwc-neutral-900"><?php echo e($ticket->event->date_time->format('M j, Y \a\t g:i A')); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">Venue</p>
                                        <p class="text-wwc-neutral-900"><?php echo e($ticket->event->venue ?? 'Venue TBA'); ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Seat Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-4">Seat Information</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">Seat Number</p>
                                        <p class="text-2xl font-bold text-wwc-primary font-display"><?php echo e($ticket->seat_identifier); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">Price Zone</p>
                                        <p class="text-wwc-neutral-900"><?php echo e($ticket->seat->price_zone); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">Price Paid</p>
                                        <p class="text-2xl font-bold text-wwc-neutral-900 font-display">RM<?php echo e(number_format($ticket->price_paid, 0)); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- QR Code -->
                        <div class="mt-8 pt-6 border-t border-wwc-neutral-200">
                            <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-4">QR Code</h3>
                            <div class="flex items-center justify-center p-8 bg-wwc-neutral-50 rounded-lg">
                                <div class="text-center">
                                    <div class="w-48 h-48 bg-white border-2 border-wwc-neutral-200 rounded-lg flex items-center justify-center mb-4">
                                        <div class="text-center">
                                            <div class="w-32 h-32 bg-wwc-neutral-900 rounded-lg flex items-center justify-center mb-2">
                                                <div class="text-white text-xs font-mono">QR CODE</div>
                                            </div>
                                            <p class="text-xs text-wwc-neutral-500">Scan at venue</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-wwc-neutral-600">Present this QR code at the venue entrance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Information -->
                <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900">Order Information</h2>
                        <p class="text-wwc-neutral-600 text-sm mt-1">Details about your purchase</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-4">Order Details</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">Order Number</p>
                                        <p class="text-wwc-neutral-900 font-mono"><?php echo e($ticket->order->order_number); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">Purchase Date</p>
                                        <p class="text-wwc-neutral-900"><?php echo e($ticket->order->created_at->format('M j, Y \a\t g:i A')); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">Payment Method</p>
                                        <p class="text-wwc-neutral-900"><?php echo e($ticket->order->payment_method); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-4">Order Status</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">Order Status</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            <?php if($ticket->order->status === 'Completed'): ?> bg-wwc-success text-white
                                            <?php elseif($ticket->order->status === 'Pending'): ?> bg-wwc-warning text-white
                                            <?php elseif($ticket->order->status === 'Cancelled'): ?> bg-wwc-error text-white
                                            <?php else: ?> bg-wwc-neutral-200 text-wwc-neutral-800
                                            <?php endif; ?>">
                                            <?php echo e($ticket->order->status); ?>

                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-wwc-neutral-600">Ticket Status</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            <?php if($ticket->status === 'Sold'): ?> bg-wwc-success text-white
                                            <?php elseif($ticket->status === 'Held'): ?> bg-wwc-warning text-white
                                            <?php elseif($ticket->status === 'Cancelled'): ?> bg-wwc-error text-white
                                            <?php elseif($ticket->status === 'Used'): ?> bg-wwc-info text-white
                                            <?php else: ?> bg-wwc-neutral-200 text-wwc-neutral-800
                                            <?php endif; ?>">
                                            <?php echo e($ticket->status); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900">Quick Actions</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="<?php echo e(route('customer.orders.show', $ticket->order)); ?>" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-wwc-primary hover:bg-wwc-primary-light hover:text-wwc-primary-dark rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                View Order
                            </a>
                            <a href="<?php echo e(route('customer.tickets')); ?>" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-wwc-neutral-600 hover:bg-wwc-neutral-100 hover:text-wwc-neutral-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-neutral transition-colors duration-200">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                                All Tickets
                            </a>
                            <a href="<?php echo e(route('customer.support')); ?>" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-wwc-accent hover:bg-wwc-accent-light hover:text-wwc-accent-dark rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-accent transition-colors duration-200">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Get Help
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Important Information -->
                <div class="bg-wwc-warning-light border border-wwc-warning rounded-xl p-6">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-wwc-warning mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-semibold text-wwc-warning mb-2">Important Information</h3>
                            <ul class="text-xs text-wwc-warning space-y-1">
                                <li>• Arrive at least 30 minutes before the event</li>
                                <li>• Bring a valid ID matching the ticket holder</li>
                                <li>• Keep your phone charged for QR code display</li>
                                <li>• Contact support if you have any issues</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/customer/ticket-details.blade.php ENDPATH**/ ?>