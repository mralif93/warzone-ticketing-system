<?php $__env->startSection('title', 'Browse Events'); ?>
<?php $__env->startSection('description', 'Discover amazing events and get your tickets in your Warzone World Championship customer portal.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional Events Listing -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Header Section -->
    <div class="bg-white border-b border-wwc-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display">Browse Events</h1>
                    <p class="text-wwc-neutral-600 mt-1">Discover amazing events and get your tickets</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="<?php echo e(route('customer.tickets')); ?>" 
                       class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white rounded-lg text-sm font-semibold hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        My Tickets
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Search and Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 mb-8">
            <div class="px-6 py-4 border-b border-wwc-neutral-200">
                <h2 class="text-lg font-semibold text-wwc-neutral-900">Search & Filter Events</h2>
                <p class="text-wwc-neutral-600 text-sm mt-1">Find the perfect event for you</p>
            </div>
            <div class="p-6">
                <form method="GET" action="<?php echo e(route('customer.events')); ?>" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search Events</label>
                        <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>"
                               placeholder="Event name, venue, description..."
                               class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Status</label>
                        <select name="status" id="status" class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                            <option value="">All Status</option>
                            <option value="On Sale" <?php echo e(request('status') == 'On Sale' ? 'selected' : ''); ?>>On Sale</option>
                            <option value="Sold Out" <?php echo e(request('status') == 'Sold Out' ? 'selected' : ''); ?>>Sold Out</option>
                            <option value="Draft" <?php echo e(request('status') == 'Draft' ? 'selected' : ''); ?>>Draft</option>
                            <option value="Cancelled" <?php echo e(request('status') == 'Cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label for="date_from" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">From Date</label>
                        <input type="date" name="date_from" id="date_from" value="<?php echo e(request('date_from')); ?>"
                               class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search Events
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-wwc-neutral-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-wwc-neutral-900">All Events</h2>
                        <p class="text-wwc-neutral-600 text-sm mt-1">Showing <?php echo e($events->count()); ?> of <?php echo e($events->total()); ?> events</p>
                    </div>
                </div>
            </div>
            
            <?php if($events->count() > 0): ?>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-wwc-neutral-50 border border-wwc-neutral-200 rounded-lg hover:bg-wwc-neutral-100 transition-colors duration-200 overflow-hidden">
                            <!-- Event Image Placeholder -->
                            <div class="h-48 bg-gradient-to-br from-wwc-primary-light to-wwc-accent-light flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="h-12 w-12 text-wwc-primary mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm font-semibold text-wwc-primary"><?php echo e($event->name); ?></p>
                                </div>
                            </div>

                            <!-- Event Details -->
                            <div class="p-6">
                                <div class="mb-4">
                                    <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2"><?php echo e($event->name); ?></h3>
                                    <p class="text-sm text-wwc-neutral-600 mb-3"><?php echo e(Str::limit($event->description, 100)); ?></p>
                                    
                                    <!-- Event Info -->
                                    <div class="space-y-2">
                                        <div class="flex items-center text-sm text-wwc-neutral-600">
                                            <svg class="h-4 w-4 mr-2 text-wwc-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <?php echo e($event->date_time->format('M j, Y \a\t g:i A')); ?>

                                        </div>
                                        <div class="flex items-center text-sm text-wwc-neutral-600">
                                            <svg class="h-4 w-4 mr-2 text-wwc-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <?php echo e($event->venue ?? 'Venue TBA'); ?>

                                        </div>
                                    </div>
                                </div>

                                <!-- Status and Tickets -->
                                <div class="flex items-center justify-between mb-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        <?php if($event->status === 'On Sale'): ?> bg-wwc-success text-white
                                        <?php elseif($event->status === 'Sold Out'): ?> bg-wwc-error text-white
                                        <?php elseif($event->status === 'Cancelled'): ?> bg-wwc-neutral-400 text-white
                                        <?php else: ?> bg-wwc-warning text-white
                                        <?php endif; ?>">
                                        <?php echo e($event->status); ?>

                                    </span>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-wwc-neutral-900"><?php echo e($event->customer_tickets_count ?? 0); ?> / 7,000</div>
                                        <div class="text-xs text-wwc-neutral-500">tickets sold</div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="pt-4 border-t border-wwc-neutral-200">
                                        <?php if($event->status === 'On Sale'): ?>
                                            <a href="<?php echo e(route('public.events.show', $event)); ?>" 
                                               class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                            </svg>
                                            Get Tickets
                                        </a>
                                    <?php elseif($event->status === 'Sold Out'): ?>
                                        <button disabled class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-neutral-400 cursor-not-allowed">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Sold Out
                                        </button>
                                    <?php else: ?>
                                        <button disabled class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-neutral-400 cursor-not-allowed">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <?php echo e($event->status); ?>

                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8 pt-6 border-t border-wwc-neutral-200">
                        <?php echo e($events->links()); ?>

                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="mx-auto h-16 w-16 rounded-lg bg-wwc-neutral-100 flex items-center justify-center mb-4">
                        <svg class="h-8 w-8 text-wwc-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-wwc-neutral-900 mb-2">No events found</h3>
                    <p class="text-sm text-wwc-neutral-600 mb-6">No events match your current filters.</p>
                    <a href="<?php echo e(route('customer.events')); ?>" 
                       class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Clear Filters
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/customer/events.blade.php ENDPATH**/ ?>