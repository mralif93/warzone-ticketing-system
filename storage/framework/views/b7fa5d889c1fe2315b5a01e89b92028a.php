<?php $__env->startSection('title', 'Warzone Ticketing - Premium Event Tickets'); ?>
<?php $__env->startSection('description', 'Get premium tickets for the best events. Secure, fast, and reliable ticketing system for concerts, sports, and entertainment.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-wwc-primary to-wwc-primary-dark text-white">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold font-display mb-6">
                Premium Event Tickets
            </h1>
            <p class="text-xl md:text-2xl text-wwc-primary-light mb-8 max-w-3xl mx-auto">
                Experience the best concerts, sports, and entertainment events with our secure, fast, and reliable ticketing system.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo e(route('public.events')); ?>" 
                   class="bg-white text-wwc-primary hover:bg-wwc-neutral-100 px-8 py-4 rounded-2xl text-lg font-semibold transition-colors duration-200">
                    Browse Events
                </a>
                <a href="<?php echo e(route('register')); ?>" 
                   class="border-2 border-white text-white hover:bg-white hover:text-wwc-primary px-8 py-4 rounded-2xl text-lg font-semibold transition-colors duration-200">
                    Get Started
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-wwc-neutral-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="text-4xl font-bold text-wwc-primary font-display mb-2"><?php echo e($stats['total_events']); ?></div>
                <div class="text-wwc-neutral-600 font-semibold">Active Events</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-wwc-primary font-display mb-2"><?php echo e($stats['upcoming_events']); ?></div>
                <div class="text-wwc-neutral-600 font-semibold">Upcoming Events</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-wwc-primary font-display mb-2"><?php echo e(number_format($stats['total_tickets_sold'])); ?></div>
                <div class="text-wwc-neutral-600 font-semibold">Tickets Sold</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Events Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-wwc-neutral-900 font-display mb-4">
                Featured Events
            </h2>
            <p class="text-xl text-wwc-neutral-600 max-w-2xl mx-auto">
                Don't miss out on these amazing upcoming events. Get your tickets now!
            </p>
        </div>

        <?php if($featuredEvents->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $__currentLoopData = $featuredEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-2xl shadow-lg border border-wwc-neutral-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <!-- Event Image Placeholder -->
                    <div class="h-48 bg-gradient-to-br from-wwc-primary-light to-wwc-accent-light flex items-center justify-center">
                        <div class="text-center">
                            <i class='bx bx-calendar-event text-6xl text-wwc-primary mx-auto mb-4'></i>
                            <p class="text-lg font-semibold text-wwc-primary"><?php echo e($event->name); ?></p>
                        </div>
                    </div>

                    <!-- Event Details -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-wwc-neutral-900 font-display mb-3"><?php echo e($event->name); ?></h3>
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-wwc-neutral-600">
                                <i class='bx bx-calendar text-sm mr-2 text-wwc-neutral-400'></i>
                                <?php echo e($event->getFormattedDateRange()); ?>

                            </div>
                            <div class="flex items-center text-wwc-neutral-600">
                                <i class='bx bx-map text-sm mr-2 text-wwc-neutral-400'></i>
                                <?php echo e($event->venue ?? 'Venue TBA'); ?>

                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-wwc-success text-white">
                                On Sale
                            </span>
                            <div class="text-sm text-wwc-neutral-500">
                                <?php echo e($event->tickets_count ?? 0); ?> tickets sold
                            </div>
                        </div>

                        <a href="<?php echo e(route('public.events.show', $event)); ?>" 
                           class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-2xl text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            Get Tickets
                        </a>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="text-center mt-12">
                <a href="<?php echo e(route('public.events')); ?>" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-semibold rounded-2xl text-wwc-primary bg-wwc-primary-light hover:bg-wwc-primary hover:text-white transition-colors duration-200">
                    View All Events
                    <i class='bx bx-chevron-right text-lg ml-2'></i>
                </a>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <div class="mx-auto h-16 w-16 rounded-2xl bg-wwc-neutral-100 flex items-center justify-center mb-4">
                    <i class='bx bx-calendar text-3xl text-wwc-neutral-400'></i>
                </div>
                <h3 class="text-xl font-semibold text-wwc-neutral-900 mb-2">No events available</h3>
                <p class="text-wwc-neutral-600">Check back soon for exciting upcoming events!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-wwc-neutral-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-wwc-neutral-900 font-display mb-4">
                Why Choose Warzone Ticketing?
            </h2>
            <p class="text-xl text-wwc-neutral-600 max-w-2xl mx-auto">
                We provide the best ticketing experience with cutting-edge technology and customer service.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 rounded-2xl bg-wwc-primary-light flex items-center justify-center mb-6">
                    <i class='bx bx-shield-check text-3xl text-wwc-primary'></i>
                </div>
                <h3 class="text-xl font-semibold text-wwc-neutral-900 mb-4">Secure & Safe</h3>
                <p class="text-wwc-neutral-600">Your transactions are protected with industry-leading security measures and fraud prevention.</p>
            </div>

            <!-- Feature 2 -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 rounded-2xl bg-wwc-accent-light flex items-center justify-center mb-6">
                    <i class='bx bx-zap text-3xl text-wwc-accent'></i>
                </div>
                <h3 class="text-xl font-semibold text-wwc-neutral-900 mb-4">Lightning Fast</h3>
                <p class="text-wwc-neutral-600">Get your tickets instantly with our high-performance platform designed for speed and reliability.</p>
            </div>

            <!-- Feature 3 -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 rounded-2xl bg-wwc-success-light flex items-center justify-center mb-6">
                    <i class='bx bx-refresh text-3xl text-wwc-success'></i>
                </div>
                <h3 class="text-xl font-semibold text-wwc-neutral-900 mb-4">24/7 Support</h3>
                <p class="text-wwc-neutral-600">Our dedicated support team is always here to help you with any questions or issues.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-wwc-primary text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold font-display mb-4">
            Ready to Get Started?
        </h2>
        <p class="text-xl text-wwc-primary-light mb-8 max-w-2xl mx-auto">
            Join thousands of satisfied customers and start buying tickets for your favorite events today.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo e(route('register')); ?>" 
               class="bg-white text-wwc-primary hover:bg-wwc-neutral-100 px-8 py-4 rounded-2xl text-lg font-semibold transition-colors duration-200">
                Create Account
            </a>
            <a href="<?php echo e(route('public.events')); ?>" 
               class="border-2 border-white text-white hover:bg-white hover:text-wwc-primary px-8 py-4 rounded-2xl text-lg font-semibold transition-colors duration-200">
                Browse Events
            </a>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/public/home.blade.php ENDPATH**/ ?>