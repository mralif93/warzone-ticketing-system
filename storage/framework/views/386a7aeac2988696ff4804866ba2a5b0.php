<?php $__env->startSection('title', 'Ticket Details - ' . $ticket->event->name); ?>
<?php $__env->startSection('description', 'View your ticket details and QR code for ' . $ticket->event->name . '.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Standard Ticket Details -->
<div class="min-h-screen bg-gray-50">

    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="<?php echo e(route('customer.tickets')); ?>" 
                       class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200">
                        <div class="h-9 w-9 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-blue-50">
                            <i class="bx bx-chevron-left text-lg"></i>
                        </div>
                    </a>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">Ticket Details</h1>
                        <p class="text-gray-500 text-sm">Ticket #<?php echo e($ticket->id); ?></p>
                    </div>
                </div>
                <a href="<?php echo e(route('customer.orders.show', $ticket->order)); ?>" 
                    class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white rounded-lg text-sm font-medium hover:bg-wwc-primary-dark transition-colors duration-200">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    My Orders
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Event Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900"><?php echo e($ticket->event->name); ?></h2>
                                <p class="text-gray-500 text-sm mt-1">Event Information</p>
                            </div>
                            <div class="text-right">
                                <div class="text-xl font-semibold text-gray-900">RM<?php echo e(number_format($ticket->price_paid, 0)); ?></div>
                                <div class="text-gray-500 text-sm">Price Paid</div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-calendar text-blue-600'></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Date & Time</p>
                                </div>
                                <p class="text-gray-900 font-medium"><?php echo e($ticket->event->getFormattedDateRange()); ?></p>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 bg-green-50 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-map text-green-600'></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Venue</p>
                                </div>
                                <p class="text-gray-900 font-medium"><?php echo e($ticket->event->venue ?? 'Venue TBA'); ?></p>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-check-circle text-emerald-600'></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Status</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                                    <?php echo e($ticket->status); ?>

                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 bg-purple-50 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-time text-purple-600'></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Duration</p>
                                </div>
                                <p class="text-gray-900 font-medium">Full Event</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ticket Information Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">Ticket Information</h3>
                        <p class="text-gray-500 text-sm">Your ticket details for entry</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 bg-orange-50 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-receipt text-orange-600'></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Ticket ID</p>
                                </div>
                                <p class="text-gray-900 font-medium"><?php echo e($ticket->ticket_identifier ?? 'TKT-' . $ticket->id); ?></p>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 bg-indigo-50 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-layer text-indigo-600'></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Zone</p>
                                </div>
                                <p class="text-gray-900 font-medium"><?php echo e($ticket->zone ?? 'General'); ?></p>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 bg-cyan-50 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-hash text-cyan-600'></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Ticket Number</p>
                                </div>
                                <p class="text-gray-900 font-medium">#<?php echo e($ticket->id); ?></p>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 bg-pink-50 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-file text-pink-600'></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Order Number</p>
                                </div>
                                <p class="text-gray-900 font-medium"><?php echo e($ticket->order->order_number); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Important Guidelines -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">Important Guidelines</h3>
                        <p class="text-gray-500 text-sm">Essential information for your event</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <div class="h-5 w-5 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class='bx bx-check text-amber-600 text-xs'></i>
                                </div>
                                <p class="text-sm text-gray-700">Arrive 30 minutes early</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="h-5 w-5 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class='bx bx-check text-amber-600 text-xs'></i>
                                </div>
                                <p class="text-sm text-gray-700">Bring valid ID</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="h-5 w-5 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class='bx bx-check text-amber-600 text-xs'></i>
                                </div>
                                <p class="text-sm text-gray-700">Keep phone charged</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="h-5 w-5 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class='bx bx-check text-amber-600 text-xs'></i>
                                </div>
                                <p class="text-sm text-gray-700">Contact support if needed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                
                <!-- QR Code Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">QR Code</h3>
                        <p class="text-gray-500 text-sm">Present at venue entrance</p>
                    </div>
                    <div class="p-6">
                        <div class="text-center">
                            <!-- QR Code Image -->
                            <div class="w-48 h-48 bg-white border-2 border-gray-200 rounded-lg flex items-center justify-center mb-4 mx-auto">
                                <img src="<?php echo e(\App\Services\QRCodeService::generateQRCodeImage($ticket->order)); ?>" 
                                     alt="Ticket QR Code" 
                                     class="w-44 h-44 rounded-lg">
                            </div>
                            
                            <!-- QR Code Text -->
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                <p class="text-xs text-gray-600 break-all"><?php echo e($ticket->qrcode); ?></p>
                            </div>
                            <p class="text-sm text-gray-500 mt-3">Scan at venue entrance</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="<?php echo e(route('customer.orders.show', $ticket->order)); ?>" 
                               class="flex items-center p-3 bg-gray-50 hover:bg-blue-50 rounded-lg transition-colors duration-200 group">
                                <div class="h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200">
                                    <i class='bx bx-receipt text-blue-600 text-lg'></i>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900 group-hover:text-blue-600">View Order</span>
                                    <p class="text-sm text-gray-600">See order details</p>
                                </div>
                            </a>
                            
                            <a href="<?php echo e(route('customer.tickets')); ?>" 
                               class="flex items-center p-3 bg-gray-50 hover:bg-green-50 rounded-lg transition-colors duration-200 group">
                                <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200">
                                    <i class='bx bx-receipt text-green-600 text-lg'></i>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900 group-hover:text-green-600">All Tickets</span>
                                    <p class="text-sm text-gray-600">View all tickets</p>
                                </div>
                            </a>
                            
                            <a href="<?php echo e(route('customer.support')); ?>" 
                               class="flex items-center p-3 bg-gray-50 hover:bg-orange-50 rounded-lg transition-colors duration-200 group">
                                <div class="h-10 w-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-orange-200">
                                    <i class='bx bx-help-circle text-orange-600 text-lg'></i>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900 group-hover:text-orange-600">Get Help</span>
                                    <p class="text-sm text-gray-600">Contact support</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/customer/ticket-details.blade.php ENDPATH**/ ?>