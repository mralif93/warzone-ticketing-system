<?php $__env->startSection('title', $event->name . ' - Event Details'); ?>
<?php $__env->startSection('description', $event->description ?: 'Get tickets for ' . $event->name . ' at ' . ($event->venue ?? 'TBA venue') . ' on ' . $event->date_time->format('M j, Y') . '.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional Event Details -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Header Section -->
    <div class="bg-white border-b border-wwc-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <!-- Back Navigation - REMOVED -->
                
                
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
                <!-- Ticket Selection -->
                <?php if(count($ticketTypes) > 0): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-8">
                    <!-- Header with Event Info -->
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-wwc-neutral-900 font-display mb-2">Choose Your Tickets</h2>
                        <p class="text-wwc-neutral-600">Select from <?php echo e(count($ticketTypes)); ?> available ticket types</p>
                    </div>

                    <!-- Ticket Type Selection Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <?php $__currentLoopData = $ticketTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticketName => $ticketData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="group bg-wwc-neutral-50 border-2 border-wwc-neutral-200 rounded-xl p-6 hover:border-wwc-primary hover:bg-white hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 cursor-pointer ticket-card" 
                             data-ticket="<?php echo e($ticketName); ?>" 
                             data-price="<?php echo e($ticketData['price']); ?>" 
                             data-available="<?php echo e($ticketData['available']); ?>">
                            <div class="flex justify-between items-center">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-wwc-neutral-900 group-hover:text-wwc-primary transition-colors duration-200 mb-1"><?php echo e($ticketName); ?></h3>
                                    <p class="text-sm text-wwc-neutral-500"><?php echo e($ticketData['available']); ?> seats available</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-2xl font-bold text-wwc-primary font-display">RM<?php echo e(number_format($ticketData['price'], 0)); ?></span>
                                    <p class="text-xs text-wwc-neutral-500">per ticket</p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Action Button -->
                    <?php if($event->status === 'On Sale' && $availabilityStats['tickets_available'] > 0): ?>
                    <div class="text-center">
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('public.tickets.cart', $event)); ?>" 
                               class="inline-flex items-center px-8 py-4 bg-wwc-primary text-white rounded-xl text-lg font-bold hover:bg-wwc-primary-dark hover:shadow-lg transform hover:-translate-y-1 transition-all duration-200">
                                <i class='bx bx-ticket mr-3 text-xl'></i>
                                Get Tickets Now
                            </a>
                        <?php else: ?>
                            <div class="inline-flex flex-col items-center space-y-4">
                                <a href="<?php echo e(route('login')); ?>" 
                                   class="inline-flex items-center px-8 py-4 bg-wwc-primary text-white rounded-xl text-lg font-bold hover:bg-wwc-primary-dark hover:shadow-lg transform hover:-translate-y-1 transition-all duration-200">
                                    <i class='bx bx-log-in mr-3 text-xl'></i>
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
                    <?php elseif($event->status === 'Sold Out' || $availabilityStats['tickets_available'] <= 0): ?>
                    <div class="text-center">
                        <div class="inline-flex items-center px-8 py-4 bg-wwc-neutral-400 text-white rounded-xl text-lg font-bold cursor-not-allowed">
                            <i class='bx bx-x mr-3 text-xl'></i>
                            Sold Out
                        </div>
                    </div>
                    <?php elseif(count($ticketTypes) === 0): ?>
                    <div class="text-center">
                        <div class="inline-flex items-center px-8 py-4 bg-wwc-neutral-400 text-white rounded-xl text-lg font-bold cursor-not-allowed">
                            <i class='bx bx-time mr-3 text-xl'></i>
                            Tickets Not Available
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-6">
                    <h3 class="text-lg font-bold text-wwc-neutral-900 font-display mb-4 text-center">Event Summary</h3>
                    <div class="space-y-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-wwc-primary mb-1"><?php echo e(number_format($availabilityStats['tickets_available'])); ?></div>
                            <div class="text-sm text-wwc-neutral-600">Tickets Available</div>
                        </div>
                        
                        <div class="w-full bg-wwc-neutral-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-wwc-success to-wwc-primary h-2 rounded-full transition-all duration-500" 
                                 style="width: <?php echo e($availabilityStats['availability_percentage']); ?>%"></div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 text-center">
                            <div class="bg-wwc-neutral-50 rounded-lg p-3">
                                <div class="text-lg font-bold text-wwc-neutral-900"><?php echo e(number_format($availabilityStats['total_capacity'])); ?></div>
                                <div class="text-xs text-wwc-neutral-600">Total Capacity</div>
                            </div>
                            <div class="bg-wwc-neutral-50 rounded-lg p-3">
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

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Zone images mapping
    const zoneImages = {
        'Exclusive': 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=800&h=600&fit=crop&crop=center',
        'VIP': 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop&crop=center',
        'Grandstand': 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800&h=600&fit=crop&crop=center',
        'Premium Ringside': 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=800&h=600&fit=crop&crop=center',
        'Level 1 Zone A/B/C/D': 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?w=800&h=600&fit=crop&crop=center',
        'Level 2 Zone A/B/C/D': 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800&h=600&fit=crop&crop=center',
        'Standing Zone A/B': 'https://images.unsplash.com/photo-1571266028243-e4732b0a0a6e?w=800&h=600&fit=crop&crop=center'
    };
    
    // Zone descriptions from database
    const zoneDescriptions = {
        <?php $__currentLoopData = $ticketTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticketName => $ticketData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        '<?php echo e($ticketName); ?>': '<?php echo e($ticketData['description'] ?? 'Premium seating with excellent views and great value for money.'); ?>',
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    };
    
    // Add click event to each ticket card
    const ticketCards = document.querySelectorAll('.ticket-card');
    ticketCards.forEach(card => {
        card.addEventListener('click', function() {
            const ticketName = this.dataset.ticket;
            const ticketPrice = this.dataset.price;
            const ticketAvailable = this.dataset.available;
            const ticketImage = zoneImages[ticketName] || 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=800&h=600&fit=crop&crop=center';
            const ticketDescription = zoneDescriptions[ticketName] || 'Premium seating with excellent views.';
            
            // Show SweetAlert with ticket details
            Swal.fire({
                title: ticketName,
                html: `
                    <div class="text-left">
                        <div class="mb-4">
                            <img src="${ticketImage}" alt="${ticketName}" class="w-full h-48 object-cover rounded-lg mb-4">
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-700">Price:</span>
                                <span class="text-2xl font-bold text-red-600">RM${parseFloat(ticketPrice).toLocaleString()}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-700">Available Seats:</span>
                                <span class="text-lg font-semibold text-green-600">${ticketAvailable} seats</span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 mt-4">
                                <h4 class="font-semibold text-gray-900 mb-2">Ticket Description</h4>
                                <p class="text-gray-600 text-sm">${ticketDescription}</p>
                            </div>
                        </div>
                    </div>
                `,
                width: '600px',
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonText: 'Get Tickets',
                cancelButtonText: 'Close',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                customClass: {
                    popup: 'rounded-2xl',
                    title: 'text-2xl font-bold text-gray-900',
                    confirmButton: 'px-6 py-2 rounded-lg font-semibold',
                    cancelButton: 'px-6 py-2 rounded-lg font-semibold'
                },
                didOpen: () => {
                    // Add custom styling to the modal
                    const popup = document.querySelector('.swal2-popup');
                    if (popup) {
                        popup.style.borderRadius = '1rem';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to cart page
                    window.location.href = '<?php echo e(route("public.tickets.cart", $event)); ?>';
                }
            });
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/public/event-show.blade.php ENDPATH**/ ?>