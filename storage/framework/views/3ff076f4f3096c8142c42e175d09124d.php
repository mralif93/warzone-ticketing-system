<?php $__env->startSection('title', 'Browse Events - Warzone Ticketing'); ?>
<?php $__env->startSection('description', 'Browse all available events and find the perfect tickets for concerts, sports, and entertainment.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header Section -->
<section class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold font-display mb-4">
                Browse Events
            </h1>
            <p class="text-xl text-wwc-primary-light max-w-2xl mx-auto">
                Discover amazing events and get your tickets today
            </p>
        </div>
    </div>
</section>

<!-- Search and Filters -->
<section class="py-8 bg-wwc-neutral-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-6">
            <form method="GET" action="<?php echo e(route('public.events')); ?>" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search Events</label>
                    <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>"
                           placeholder="Event name, venue, description..."
                           class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                </div>
                <div>
                    <label for="venue" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Venue</label>
                    <select name="venue" id="venue" class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        <option value="">All Venues</option>
                        <?php $__currentLoopData = $venues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($venue); ?>" <?php echo e(request('venue') == $venue ? 'selected' : ''); ?>>
                                <?php echo e($venue); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label for="date_from" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">From Date</label>
                    <input type="date" name="date_from" id="date_from" value="<?php echo e(request('date_from')); ?>"
                           class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-2xl text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-search text-sm mr-2'></i>
                        Search Events
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Events Grid -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-wwc-neutral-900 font-display">All Events</h2>
                <p class="text-wwc-neutral-600">Showing <?php echo e($events->count()); ?> of <?php echo e($events->total()); ?> events</p>
            </div>
        </div>

        <?php if($events->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                        <p class="text-wwc-neutral-600 text-sm mb-4"><?php echo e(Str::limit($event->description, 100)); ?></p>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-wwc-neutral-600">
                                <i class='bx bx-calendar text-sm mr-2 text-wwc-neutral-400'></i>
                                <?php echo e($event->date_time->format('M j, Y \a\t g:i A')); ?>

                            </div>
                            <div class="flex items-center text-wwc-neutral-600">
                                <i class='bx bx-map text-sm mr-2 text-wwc-neutral-400'></i>
                                <?php echo e($event->venue ?? 'Venue TBA'); ?>

                            </div>
                        </div>

                        <!-- Status and Tickets -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                <?php if($event->status === 'On Sale'): ?> bg-wwc-success text-white
                                <?php elseif($event->status === 'Sold Out'): ?> bg-wwc-error text-white
                                <?php else: ?> bg-wwc-neutral-400 text-white
                                <?php endif; ?>">
                                <?php echo e($event->status); ?>

                            </span>
                            <div class="text-sm text-wwc-neutral-500">
                                <?php echo e($event->tickets_count ?? 0); ?> tickets sold
                            </div>
                        </div>

                        <!-- Action Button -->
                        <?php if($event->status === 'On Sale'): ?>
                            <a href="<?php echo e(route('public.events.show', $event)); ?>" 
                               class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-2xl text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-receipt text-sm mr-2'></i>
                                Get Tickets
                            </a>
                        <?php elseif($event->status === 'Sold Out'): ?>
                            <button disabled class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-2xl text-white bg-wwc-neutral-400 cursor-not-allowed">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Sold Out
                            </button>
                        <?php else: ?>
                            <button disabled class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-2xl text-white bg-wwc-neutral-400 cursor-not-allowed">
                                <i class='bx bx-time text-sm mr-2'></i>
                                <?php echo e($event->status); ?>

                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                <?php echo e($events->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <div class="mx-auto h-16 w-16 rounded-2xl bg-wwc-neutral-100 flex items-center justify-center mb-4">
                    <i class='bx bx-calendar text-3xl text-wwc-neutral-400'></i>
                </div>
                <h3 class="text-xl font-semibold text-wwc-neutral-900 mb-2">No events found</h3>
                <p class="text-wwc-neutral-600 mb-6">No events match your current filters.</p>
                <a href="<?php echo e(route('public.events')); ?>" 
                   class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-2xl transition-colors duration-200">
                    <i class='bx bx-refresh text-sm mr-2'></i>
                    Clear Filters
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/public/events.blade.php ENDPATH**/ ?>