<?php $__env->startSection('title', $event->name . ' - Warzone Ticketing'); ?>
<?php $__env->startSection('description', $event->description ?: 'Get tickets for ' . $event->name . ' at ' . ($event->venue ?? 'TBA venue') . ' on ' . $event->date_time->format('M j, Y') . '.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Event Header -->
<section class="bg-wwc-primary text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center mb-6">
            <a href="<?php echo e(route('public.events')); ?>" class="text-wwc-primary-light hover:text-white mr-4">
                <i class='bx bx-chevron-left text-2xl'></i>
            </a>
            <span class="text-wwc-primary-light">Back to Events</span>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold font-display mb-4"><?php echo e($event->name); ?></h1>
                <div class="space-y-3 mb-6">
                    <div class="flex items-center text-xl">
                        <i class='bx bx-calendar text-2xl mr-3 text-wwc-primary-light'></i>
                        <?php echo e($event->date_time->format('l, M j, Y \a\t g:i A')); ?>

                    </div>
                    <div class="flex items-center text-xl">
                        <i class='bx bx-map text-2xl mr-3 text-wwc-primary-light'></i>
                        <?php echo e($event->venue ?? 'Venue TBA'); ?>

                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                        <?php if($event->status === 'On Sale'): ?> bg-wwc-success text-white
                        <?php elseif($event->status === 'Sold Out'): ?> bg-wwc-error text-white
                        <?php else: ?> bg-wwc-neutral-400 text-white
                        <?php endif; ?>">
                        <?php echo e($event->status); ?>

                    </span>
                    <?php if($event->status === 'On Sale'): ?>
                        <a href="<?php echo e(route('public.tickets.select', $event)); ?>" 
                           class="bg-white text-wwc-primary hover:bg-wwc-neutral-100 px-8 py-3 rounded-xl text-lg font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-colors duration-200">
                            Get Tickets Now
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Event Image Placeholder -->
            <div class="flex justify-center">
                <div class="w-full max-w-md h-64 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                    <div class="text-center">
                        <i class='bx bx-calendar text-6xl text-wwc-primary-light mx-auto mb-4'></i>
                        <p class="text-lg font-semibold text-wwc-primary-light"><?php echo e($event->name); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Event Details -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Event Description -->
                <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 p-8 mb-8">
                    <h2 class="text-2xl font-bold text-wwc-neutral-900 font-display mb-4">About This Event</h2>
                    <div class="prose max-w-none">
                        <p class="text-wwc-neutral-700 leading-relaxed">
                            <?php echo e($event->description ?: 'Join us for an amazing event! More details coming soon.'); ?>

                        </p>
                    </div>
                </div>

                <!-- Price Zones -->
                <?php if($priceZones->count() > 0): ?>
                <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 p-8 mb-8">
                    <h2 class="text-2xl font-bold text-wwc-neutral-900 font-display mb-6">Ticket Prices</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php $__currentLoopData = $priceZones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border border-wwc-neutral-200 rounded-xl p-6 hover:border-wwc-primary-light transition-colors duration-200">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-lg font-semibold text-wwc-neutral-900"><?php echo e($zone['zone']); ?></h3>
                                <span class="text-2xl font-bold text-wwc-primary font-display">RM<?php echo e(number_format($zone['price'], 0)); ?></span>
                            </div>
                            <div class="text-sm text-wwc-neutral-600 mb-3">
                                <?php echo e($zone['available']); ?> of <?php echo e($zone['total']); ?> tickets available
                            </div>
                            <div class="w-full bg-wwc-neutral-200 rounded-full h-2 mb-2">
                                <div class="bg-wwc-primary h-2 rounded-full" style="width: <?php echo e(100 - $zone['sold_percentage']); ?>%"></div>
                            </div>
                            <div class="text-xs text-wwc-neutral-500">
                                <?php echo e($zone['sold_percentage']); ?>% sold
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Event Information -->
                <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 p-8">
                    <h2 class="text-2xl font-bold text-wwc-neutral-900 font-display mb-6">Event Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-3">Date & Time</h3>
                            <p class="text-wwc-neutral-700"><?php echo e($event->date_time->format('l, F j, Y')); ?></p>
                            <p class="text-wwc-neutral-700"><?php echo e($event->date_time->format('g:i A')); ?></p>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-3">Venue</h3>
                            <p class="text-wwc-neutral-700"><?php echo e($event->venue ?? 'Venue TBA'); ?></p>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-3">Max Tickets per Order</h3>
                            <p class="text-wwc-neutral-700"><?php echo e($event->max_tickets_per_order); ?> tickets</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-3">Event Status</h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                <?php if($event->status === 'On Sale'): ?> bg-wwc-success text-white
                                <?php elseif($event->status === 'Sold Out'): ?> bg-wwc-error text-white
                                <?php else: ?> bg-wwc-neutral-400 text-white
                                <?php endif; ?>">
                                <?php echo e($event->status); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Ticket Availability -->
                <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 p-6">
                    <h3 class="text-lg font-semibold text-wwc-neutral-900 font-display mb-4">Ticket Availability</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-wwc-neutral-600">Total Capacity</span>
                                <span class="font-semibold text-wwc-neutral-900"><?php echo e(number_format($availabilityStats['total_capacity'])); ?></span>
                            </div>
                            <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                <div class="bg-wwc-primary h-2 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-wwc-neutral-600">Available</span>
                                <span class="font-semibold text-wwc-neutral-900"><?php echo e(number_format($availabilityStats['tickets_available'])); ?></span>
                            </div>
                            <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                <div class="bg-wwc-success h-2 rounded-full" style="width: <?php echo e($availabilityStats['availability_percentage']); ?>%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-wwc-neutral-600">Sold</span>
                                <span class="font-semibold text-wwc-neutral-900"><?php echo e(number_format($availabilityStats['tickets_sold'])); ?></span>
                            </div>
                            <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                                <div class="bg-wwc-accent h-2 rounded-full" style="width: <?php echo e($availabilityStats['sold_percentage']); ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Get Tickets CTA -->
                <?php if($event->status === 'On Sale'): ?>
                <div class="bg-wwc-primary text-white rounded-xl p-6 text-center">
                    <h3 class="text-lg font-semibold mb-2">Ready to Get Tickets?</h3>
                    <p class="text-wwc-primary-light text-sm mb-4">Don't miss out on this amazing event!</p>
                    <a href="<?php echo e(route('public.tickets.select', $event)); ?>" 
                       class="w-full inline-flex justify-center items-center px-6 py-3 bg-white text-wwc-primary hover:bg-wwc-neutral-100 rounded-xl text-lg font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-colors duration-200">
                        <i class='bx bx-receipt text-lg mr-2'></i>
                        Buy Tickets
                    </a>
                </div>
                <?php elseif($event->status === 'Sold Out'): ?>
                <div class="bg-wwc-error text-white rounded-xl p-6 text-center">
                    <h3 class="text-lg font-semibold mb-2">Sold Out</h3>
                    <p class="text-red-100 text-sm mb-4">This event is completely sold out.</p>
                    <button disabled class="w-full inline-flex justify-center items-center px-6 py-3 bg-red-600 text-white rounded-xl text-lg font-semibold cursor-not-allowed">
                        <i class='bx bx-x text-lg mr-2'></i>
                        Sold Out
                    </button>
                </div>
                <?php endif; ?>

                <!-- Need Help -->
                <div class="bg-wwc-neutral-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-3">Need Help?</h3>
                    <p class="text-sm text-wwc-neutral-600 mb-4">Our support team is here to help you with any questions.</p>
                    <div class="space-y-2">
                        <a href="mailto:support@warzone-ticketing.com" 
                           class="block text-sm text-wwc-primary hover:text-wwc-primary-dark transition-colors duration-200">
                            support@warzone-ticketing.com
                        </a>
                        <a href="tel:+15551234567" 
                           class="block text-sm text-wwc-primary hover:text-wwc-primary-dark transition-colors duration-200">
                            +1 (555) 123-4567
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/public/event-show.blade.php ENDPATH**/ ?>