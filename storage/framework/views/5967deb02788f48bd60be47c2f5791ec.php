<?php $__env->startSection('title', $event->name . ' - Event Details'); ?>
<?php $__env->startSection('description', $event->description ?: 'Get tickets for ' . $event->name . ' at ' . ($event->venue ?? 'TBA venue') . ' on ' . $event->date_time->format('M j, Y') . '.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional Event Details -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Header Section -->
    <div class="bg-white border-b border-wwc-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <!-- Back Navigation -->
                <div class="flex items-center">
                    <a href="<?php echo e(route('public.events')); ?>" 
                       class="flex items-center text-wwc-neutral-600 hover:text-wwc-primary transition-colors duration-200 group">
                        <div class="h-8 w-8 bg-wwc-neutral-100 rounded-lg flex items-center justify-center group-hover:bg-wwc-primary/10 transition-colors duration-200">
                            <i class="bx bx-chevron-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                        </div>
                        <span class="font-semibold ml-3">Back to Events</span>
                    </a>
                </div>
                
                <!-- Event Details -->
                <div class="text-center flex-1 mx-8">
                    <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display mb-1"><?php echo e($event->name); ?></h1>
                    <p class="text-sm text-wwc-neutral-600">
                        <?php echo e($event->getFormattedDateRange()); ?>

                        <?php if($event->venue): ?>
                            • <?php echo e($event->venue); ?>

                        <?php endif; ?>
                    </p>
                </div>
                
                <!-- Event Status -->
                <div class="flex items-center">
                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold
                        <?php if($event->status === 'On Sale'): ?> bg-wwc-success/10 text-wwc-success border border-wwc-success/20
                        <?php elseif($event->status === 'Sold Out'): ?> bg-wwc-error/10 text-wwc-error border border-wwc-error/20
                        <?php else: ?> bg-wwc-neutral-100 text-wwc-neutral-600 border border-wwc-neutral-200
                        <?php endif; ?>">
                        <i class="bx bx-check-circle mr-2"></i>
                        <?php echo e($event->status); ?>

                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        

        <!-- 1. ABOUT & INFORMATION SECTION -->
        <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-8 mb-12">
            <!-- About Section -->
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-wwc-neutral-900 font-display mb-4">About This Event</h2>
                <div class="w-24 h-1 bg-wwc-primary mx-auto rounded-full mb-8"></div>
                <div class="max-w-4xl mx-auto">
                    <p class="text-lg text-wwc-neutral-700 leading-relaxed text-center">
                        <?php echo e($event->description ?: 'Join us for an amazing event! More details coming soon.'); ?>

                    </p>
                </div>
            </div>

            <!-- Information Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center p-6 bg-wwc-neutral-50 rounded-xl">
                    <div class="h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-calendar text-blue-600 text-2xl'></i>
                    </div>
                    <h3 class="text-lg font-bold text-wwc-neutral-900 mb-2">Date & Time</h3>
                    <p class="text-wwc-neutral-700"><?php echo e($event->getFormattedDateRange()); ?></p>
                </div>
                
                <div class="text-center p-6 bg-wwc-neutral-50 rounded-xl">
                    <div class="h-16 w-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-map text-orange-600 text-2xl'></i>
                    </div>
                    <h3 class="text-lg font-bold text-wwc-neutral-900 mb-2">Venue</h3>
                    <p class="text-wwc-neutral-700"><?php echo e($event->venue ?? 'Venue TBA'); ?></p>
                </div>
                
                <div class="text-center p-6 bg-wwc-neutral-50 rounded-xl">
                    <div class="h-16 w-16 bg-wwc-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-user text-wwc-primary text-2xl'></i>
                    </div>
                    <h3 class="text-lg font-bold text-wwc-neutral-900 mb-2">Max per Order</h3>
                    <p class="text-wwc-neutral-700"><?php echo e($event->max_tickets_per_order); ?> tickets</p>
                </div>
                
                <div class="text-center p-6 bg-wwc-neutral-50 rounded-xl">
                    <div class="h-16 w-16 bg-wwc-accent/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-group text-wwc-accent text-2xl'></i>
                    </div>
                    <h3 class="text-lg font-bold text-wwc-neutral-900 mb-2">Capacity</h3>
                    <p class="text-wwc-neutral-700"><?php echo e(number_format($event->total_seats ?? 0)); ?> seats</p>
                </div>
            </div>
        </div>

        <!-- 2. TICKETS SECTION -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <!-- Main Content -->
            <div class="lg:col-span-2">

                <!-- Price Zones -->
                <?php if(count($zones) > 0): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-wwc-neutral-900 font-display mb-4">Available Tickets</h2>
                        <div class="w-24 h-1 bg-wwc-primary mx-auto rounded-full"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zoneName => $zoneData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="group bg-white border-2 border-wwc-neutral-200 rounded-xl p-6 hover:border-wwc-primary hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-wwc-neutral-900 group-hover:text-wwc-primary transition-colors duration-200"><?php echo e($zoneName); ?></h3>
                                <div class="text-right">
                                    <span class="text-3xl font-bold text-wwc-primary font-display">RM<?php echo e(number_format($zoneData['price'], 0)); ?></span>
                                    <p class="text-sm text-wwc-neutral-500">per ticket</p>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-wwc-neutral-600">Available</span>
                                    <span class="font-semibold text-wwc-neutral-900"><?php echo e($zoneData['available']); ?> tickets</span>
                                </div>
                                
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-wwc-neutral-600">Sold</span>
                                    <span class="font-semibold text-wwc-neutral-900"><?php echo e($zoneData['sold']); ?> tickets</span>
                                </div>
                                
                                <div class="w-full bg-wwc-neutral-200 rounded-full h-3">
                                    <div class="bg-wwc-primary h-3 rounded-full transition-all duration-500" 
                                         style="width: <?php echo e($zoneData['availability_percentage']); ?>%"></div>
                                </div>
                                
                                <div class="text-center">
                                    <span class="text-sm font-semibold text-wwc-neutral-700"><?php echo e($zoneData['availability_percentage']); ?>% available</span>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-wwc-neutral-200">
                                <div class="flex items-center text-sm text-wwc-neutral-600">
                                    <i class='bx bx-check-circle text-wwc-success mr-2'></i>
                                    <span>Best value for money</span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <?php if($event->status === 'On Sale'): ?>
                    <div class="text-center mt-10">
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('public.tickets.cart', $event)); ?>" 
                               class="inline-flex items-center px-12 py-4 bg-wwc-primary text-white rounded-xl text-xl font-bold hover:bg-wwc-primary-dark hover:shadow-lg transform hover:-translate-y-1 transition-all duration-200">
                                <i class='bx bx-ticket mr-3 text-2xl'></i>
                                Get Tickets Now
                            </a>
                        <?php else: ?>
                            <div class="inline-flex flex-col items-center space-y-4">
                                <a href="<?php echo e(route('login')); ?>" 
                                   class="inline-flex items-center px-12 py-4 bg-wwc-primary text-white rounded-xl text-xl font-bold hover:bg-wwc-primary-dark hover:shadow-lg transform hover:-translate-y-1 transition-all duration-200">
                                    <i class='bx bx-log-in mr-3 text-2xl'></i>
                                    Login to Get Tickets
                                </a>
                                <div class="text-center">
                                    <span class="text-sm text-wwc-neutral-500">Don't have an account?</span>
                                    <a href="<?php echo e(route('register')); ?>" class="text-sm text-wwc-primary hover:text-wwc-primary-dark font-medium ml-1">
                                        Create Account
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Ticket Availability -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-6">
                    <h3 class="text-xl font-bold text-wwc-neutral-900 font-display mb-6 text-center">Ticket Availability</h3>
                    <div class="space-y-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-wwc-primary mb-2"><?php echo e(number_format($availabilityStats['tickets_available'])); ?></div>
                            <div class="text-sm text-wwc-neutral-600">Available Tickets</div>
                        </div>
                        
                        <div class="w-full bg-wwc-neutral-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-wwc-success to-wwc-primary h-3 rounded-full transition-all duration-500" 
                                 style="width: <?php echo e($availabilityStats['availability_percentage']); ?>%"></div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div>
                                <div class="text-lg font-bold text-wwc-neutral-900"><?php echo e(number_format($availabilityStats['total_capacity'])); ?></div>
                                <div class="text-xs text-wwc-neutral-600">Total Capacity</div>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-wwc-accent"><?php echo e(number_format($availabilityStats['tickets_sold'])); ?></div>
                                <div class="text-xs text-wwc-neutral-600">Sold</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Event Highlights -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-6">
                    <h3 class="text-xl font-bold text-wwc-neutral-900 mb-6 text-center">Why Choose Us?</h3>
                    <div class="space-y-4">
                        <div class="flex items-center text-sm text-wwc-neutral-700">
                            <div class="h-8 w-8 bg-wwc-success/20 rounded-full flex items-center justify-center mr-3">
                                <i class='bx bx-check text-wwc-success text-sm'></i>
                            </div>
                            <span class="font-medium">Secure online booking</span>
                        </div>
                        <div class="flex items-center text-sm text-wwc-neutral-700">
                            <div class="h-8 w-8 bg-wwc-success/20 rounded-full flex items-center justify-center mr-3">
                                <i class='bx bx-check text-wwc-success text-sm'></i>
                            </div>
                            <span class="font-medium">Instant confirmation</span>
                        </div>
                        <div class="flex items-center text-sm text-wwc-neutral-700">
                            <div class="h-8 w-8 bg-wwc-success/20 rounded-full flex items-center justify-center mr-3">
                                <i class='bx bx-check text-wwc-success text-sm'></i>
                            </div>
                            <span class="font-medium">Mobile-friendly tickets</span>
                        </div>
                        <div class="flex items-center text-sm text-wwc-neutral-700">
                            <div class="h-8 w-8 bg-wwc-success/20 rounded-full flex items-center justify-center mr-3">
                                <i class='bx bx-check text-wwc-success text-sm'></i>
                            </div>
                            <span class="font-medium">24/7 customer support</span>
                        </div>
                    </div>
                </div>

                <!-- Need Help -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-6">
                    <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4 text-center">Need Help?</h3>
                    <p class="text-sm text-wwc-neutral-600 mb-6 text-center">Our support team is here to help you with any questions.</p>
                    <div class="space-y-3">
                        <a href="mailto:support@warzone-ticketing.com" 
                           class="flex items-center justify-center px-4 py-3 bg-wwc-neutral-50 hover:bg-wwc-primary hover:text-white rounded-xl transition-colors duration-200 border border-wwc-neutral-200">
                            <i class='bx bx-envelope mr-2'></i>
                            <span class="text-sm font-medium">support@warzone-ticketing.com</span>
                        </a>
                        <a href="tel:+60123456789" 
                           class="flex items-center justify-center px-4 py-3 bg-wwc-neutral-50 hover:bg-wwc-primary hover:text-white rounded-xl transition-colors duration-200 border border-wwc-neutral-200">
                            <i class='bx bx-phone mr-2'></i>
                            <span class="text-sm font-medium">+60 12-345 6789</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. ADDITIONAL INFORMATION SECTION -->
        <?php if($event->isMultiDay()): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-wwc-neutral-900 font-display mb-4">Event Schedule</h2>
                <div class="w-24 h-1 bg-wwc-primary mx-auto rounded-full"></div>
            </div>
            <div class="max-w-4xl mx-auto text-center">
                <div class="bg-wwc-info/10 rounded-xl p-6">
                    <div class="flex items-center justify-center mb-4">
                        <div class="h-12 w-12 bg-wwc-info/20 rounded-full flex items-center justify-center mr-4">
                            <i class='bx bx-calendar-check text-wwc-info text-xl'></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-wwc-neutral-900"><?php echo e($event->getFormattedDateRange()); ?></h3>
                            <p class="text-wwc-neutral-600"><?php echo e($event->getDurationInDays()); ?> day<?php echo e($event->getDurationInDays() > 1 ? 's' : ''); ?> event</p>
                        </div>
                    </div>
                    <p class="text-wwc-neutral-700">This is a multi-day event. Please check the specific schedule for each day's activities and timings.</p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/public/event-show.blade.php ENDPATH**/ ?>