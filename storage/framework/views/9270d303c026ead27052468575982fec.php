<?php $__env->startSection('title', 'Event Details'); ?>
<?php $__env->startSection('page-title', 'Event Details'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional Event Details with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <div class="flex items-center">
                    <a href="<?php echo e(route('admin.events.index')); ?>" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Events
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Event Details -->
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Event Details</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-info-circle text-sm'></i>
                                    <span>Event information</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Event Name -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-red-100 flex items-center justify-center">
                                            <i class='bx bx-calendar text-sm text-red-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Event Name</span>
                                        <span class="text-base font-medium text-wwc-neutral-900"><?php echo e($event->name); ?></span>
                                    </div>
                                </div>

                                <!-- Date & Time -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-time text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">
                                            <?php if($event->isMultiDay()): ?>
                                                Event Duration
                                            <?php else: ?>
                                                Date & Time
                                            <?php endif; ?>
                                        </span>
                                        <span class="text-base font-medium text-wwc-neutral-900">
                                            <?php echo e($event->getFormattedDateRange()); ?>

                                        </span>
                                    </div>
                                </div>

                                <?php if($event->isMultiDay()): ?>
                                <!-- Event Duration Info -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-calendar text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Duration</span>
                                        <span class="text-base font-medium text-wwc-neutral-900"><?php echo e($event->getDurationInDays()); ?> day<?php echo e($event->getDurationInDays() > 1 ? 's' : ''); ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Venue -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                            <i class='bx bx-map text-sm text-orange-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Venue</span>
                                        <span class="text-base font-medium text-wwc-neutral-900"><?php echo e($event->venue); ?></span>
                                    </div>
                                </div>

                                <!-- Total Seats -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-chair text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Total Seats</span>
                                        <span class="text-base font-medium text-wwc-neutral-900"><?php echo e(number_format($event->total_seats)); ?></span>
                                    </div>
                                </div>

                                <!-- Max Tickets Per Order -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-receipt text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Max Tickets Per Order</span>
                                        <span class="text-base font-medium text-wwc-neutral-900"><?php echo e($event->max_tickets_per_order); ?></span>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-detail text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Description</span>
                                        <span class="text-base font-medium text-wwc-neutral-900 leading-relaxed text-right max-w-md"><?php echo e($event->description ?: 'No description provided'); ?></span>
                                    </div>
                                </div>

                                <?php if($event->isMultiDay()): ?>
                                <!-- Combo Discount Information -->
                                <div class="py-3">
                                    <div class="bg-wwc-accent-light rounded-lg p-4">
                                        <div class="flex items-center mb-3">
                                            <div class="h-8 w-8 rounded-lg bg-wwc-accent flex items-center justify-center mr-3">
                                                <i class='bx bx-discount text-sm text-white'></i>
                                            </div>
                                            <h4 class="text-sm font-semibold text-wwc-neutral-900">Combo Discount Settings</h4>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-medium text-wwc-neutral-600">Combo Discount Enabled</span>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                                    <?php echo e($event->combo_discount_enabled ? 'bg-wwc-success text-white' : 'bg-wwc-neutral-300 text-wwc-neutral-700'); ?>">
                                                    <?php if($event->combo_discount_enabled): ?>
                                                        <i class='bx bx-check text-xs mr-1'></i>
                                                        Enabled
                                                    <?php else: ?>
                                                        <i class='bx bx-x text-xs mr-1'></i>
                                                        Disabled
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                            
                                            <?php if($event->combo_discount_enabled): ?>
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-medium text-wwc-neutral-600">Discount Percentage</span>
                                                <span class="text-sm font-semibold text-wwc-accent"><?php echo e($event->combo_discount_percentage); ?>%</span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if($event->combo_discount_enabled): ?>
                                        <div class="mt-3 p-2 bg-white rounded text-xs text-wwc-neutral-600">
                                            <i class='bx bx-info-circle text-xs mr-1'></i>
                                            Customers purchasing tickets for both days will receive a <?php echo e($event->combo_discount_percentage); ?>% discount on their total order.
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                    </div>
                </div>

                <!-- Recent Purchase Tickets -->
                <?php if($recentTickets->count() > 0): ?>
                        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                            <div class="px-6 py-4 border-b border-wwc-neutral-100">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-bold text-wwc-neutral-900">Recent Ticket Sales</h3>
                                    <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                        <i class='bx bx-receipt text-sm'></i>
                                        <span>Latest transactions</span>
                                    </div>
                                </div>
                        </div>
                        <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-wwc-neutral-100">
                                <thead class="bg-wwc-neutral-50">
                                    <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Ticket ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Customer</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Ticket Type</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Event Day</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Price</th>
                                    </tr>
                                </thead>
                                    <tbody class="bg-white divide-y divide-wwc-neutral-100">
                                    <?php $__currentLoopData = $recentTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="hover:bg-wwc-neutral-50 transition-colors duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-wwc-neutral-900">
                                                #<?php echo e($ticket->id); ?>

                                            </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-900">
                                                <?php echo e($ticket->order->user->name); ?>

                                            </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-900">
                                                <?php echo e($ticket->zone); ?>

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
                                                    <?php elseif($ticket->status === 'Held'): ?> bg-wwc-warning text-white
                                                    <?php elseif($ticket->status === 'Scanned'): ?> bg-wwc-info text-white
                                                    <?php else: ?> bg-wwc-neutral-400 text-white
                                                    <?php endif; ?>">
                                                        <?php if($ticket->status === 'Sold'): ?>
                                                            <i class='bx bx-check-circle text-xs mr-1'></i>
                                                        <?php elseif($ticket->status === 'Held'): ?>
                                                            <i class='bx bx-time text-xs mr-1'></i>
                                                        <?php elseif($ticket->status === 'Scanned'): ?>
                                                            <i class='bx bx-check text-xs mr-1'></i>
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

                <!-- Ticket Types -->
                <?php if($event->ticketTypes->count() > 0): ?>
                <div class="mt-6 bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Available Ticket Types</h3>
                            <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                <i class='bx bx-ticket text-sm'></i>
                                <span>Ticket categories</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <?php $__currentLoopData = $event->ticketTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticketType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border border-wwc-neutral-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-sm font-semibold text-wwc-neutral-900"><?php echo e($ticketType->name); ?></h4>
                                    <?php if($ticketType->is_combo): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-wwc-accent text-white">
                                            <i class='bx bx-discount text-xs mr-1'></i>
                                            Combo
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-xs">
                                        <span class="text-wwc-neutral-600">Price</span>
                                        <span class="font-semibold text-wwc-neutral-900">RM<?php echo e(number_format($ticketType->price, 0)); ?></span>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-wwc-neutral-600">Available</span>
                                        <span class="font-semibold text-wwc-neutral-900"><?php echo e(number_format($ticketType->available_seats)); ?></span>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-wwc-neutral-600">Sold</span>
                                        <span class="font-semibold text-wwc-neutral-900"><?php echo e(number_format($ticketType->sold_seats)); ?></span>
                                    </div>
                                    <?php if($ticketType->description): ?>
                                    <div class="text-xs text-wwc-neutral-500 mt-2">
                                        <?php echo e(Str::limit($ticketType->description, 60)); ?>

                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Statistics Sidebar -->
            <div class="xl:col-span-1">
                <!-- Ticket Statistics -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                    <div class="px-6 py-4 border-b border-wwc-neutral-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Ticket Statistics</h3>
                            <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                <i class='bx bx-bar-chart text-sm'></i>
                                <span>Event metrics</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm font-semibold mb-2">
                                    <span class="text-wwc-neutral-600">Total Seats</span>
                                    <span class="text-wwc-neutral-900"><?php echo e(number_format($event->total_seats)); ?></span>
                                </div>
                                <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                    <div class="bg-wwc-primary h-2 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between text-sm font-semibold mb-2">
                                    <span class="text-wwc-neutral-600">Tickets Sold</span>
                                    <span class="text-wwc-neutral-900"><?php echo e(number_format($ticketStats['tickets_sold'])); ?></span>
                                </div>
                                <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                    <div class="bg-wwc-success h-2 rounded-full" style="width: <?php echo e($ticketStats['sold_percentage']); ?>%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between text-sm font-semibold mb-2">
                                    <span class="text-wwc-neutral-600">Available</span>
                                    <span class="text-wwc-neutral-900"><?php echo e(number_format($ticketStats['tickets_available'])); ?></span>
                                </div>
                                <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                    <div class="bg-wwc-info h-2 rounded-full" style="width: <?php echo e(100 - $ticketStats['sold_percentage']); ?>%"></div>
                                </div>
                            </div>

                            <?php if($event->isMultiDay() && $event->combo_discount_enabled): ?>
                            <div class="pt-2 border-t border-wwc-neutral-200">
                                <div class="text-center">
                                    <div class="text-lg font-bold text-wwc-accent"><?php echo e($event->combo_discount_percentage); ?>%</div>
                                    <div class="text-xs text-wwc-neutral-600 font-medium">Combo Discount</div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-6 pt-4 border-t border-wwc-neutral-200">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-wwc-neutral-900 font-display"><?php echo e($ticketStats['sold_percentage']); ?>%</div>
                                <div class="text-sm text-wwc-neutral-600 font-medium">Sold</div>
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
                                <span>Event actions</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <?php if($event->isOnSale()): ?>
                                <a href="<?php echo e(route('public.tickets.cart', $event)); ?>" 
                                    class="w-full bg-wwc-success hover:bg-wwc-success-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                        <i class='bx bx-receipt text-sm mr-2'></i>
                                    Buy Tickets
                                </a>
                            <?php endif; ?>
                            
                            <a href="<?php echo e(route('admin.events.edit', $event)); ?>" 
                                class="w-full bg-wwc-primary hover:bg-wwc-primary-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                    <i class='bx bx-edit text-sm mr-2'></i>
                                Edit Event
                            </a>
                            
                            <?php if($event->status === 'Draft'): ?>
                                <form action="<?php echo e(route('admin.events.change-status', $event)); ?>" method="POST" class="w-full">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="status" value="On Sale">
                                    <button type="submit" 
                                                class="w-full bg-wwc-success hover:bg-wwc-success-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-sm">
                                            <i class='bx bx-play text-sm mr-2'></i>
                                        Go On Sale
                                    </button>
                                </form>
                            <?php elseif($event->status === 'On Sale'): ?>
                                <form action="<?php echo e(route('admin.events.change-status', $event)); ?>" method="POST" class="w-full">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="status" value="Sold Out">
                                    <button type="submit" 
                                                class="w-full bg-wwc-error hover:bg-wwc-error-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-sm">
                                            <i class='bx bx-x text-sm mr-2'></i>
                                        Mark Sold Out
                                    </button>
                                </form>
                            <?php endif; ?>
                            
                            <a href="<?php echo e(route('admin.events.index')); ?>" 
                                class="w-full bg-wwc-neutral-600 hover:bg-wwc-neutral-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                                Back to Events
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/admin/events/show.blade.php ENDPATH**/ ?>