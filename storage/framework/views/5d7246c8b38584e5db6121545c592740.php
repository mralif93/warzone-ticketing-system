<?php $__env->startSection('title', 'My Tickets'); ?>
<?php $__env->startSection('description', 'View and manage your purchased tickets in your Warzone World Championship customer portal.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional My Tickets -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Header Section -->
    <div class="bg-white border-b border-wwc-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display">My Tickets</h1>
                    <p class="text-wwc-neutral-600 mt-1">View and manage your purchased tickets</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="<?php echo e(route('customer.events')); ?>" 
                       class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white rounded-lg text-sm font-medium hover:bg-wwc-primary-dark transition-colors duration-200">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Browse Events
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
                <h2 class="text-lg font-semibold text-wwc-neutral-900">Search & Filter Tickets</h2>
                <p class="text-wwc-neutral-600 text-sm mt-1">Find specific tickets using the filters below</p>
            </div>
            <div class="p-6">
                <form method="GET" action="<?php echo e(route('customer.tickets')); ?>" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search Tickets</label>
                        <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>"
                               placeholder="Ticket ID, event name..."
                               class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Status</label>
                        <select name="status" id="status" class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                            <option value="">All Status</option>
                            <option value="Sold" <?php echo e(request('status') == 'Sold' ? 'selected' : ''); ?>>Sold</option>
                            <option value="Held" <?php echo e(request('status') == 'Held' ? 'selected' : ''); ?>>Held</option>
                            <option value="Cancelled" <?php echo e(request('status') == 'Cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                            <option value="Used" <?php echo e(request('status') == 'Used' ? 'selected' : ''); ?>>Used</option>
                        </select>
                    </div>
                    <div>
                        <label for="event" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Event</label>
                        <select name="event" id="event" class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                            <option value="">All Events</option>
                            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($event->id); ?>" <?php echo e(request('event') == $event->id ? 'selected' : ''); ?>>
                                    <?php echo e($event->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filter Tickets
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tickets List -->
        <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-wwc-neutral-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-wwc-neutral-900">All Tickets</h2>
                        <p class="text-wwc-neutral-600 text-sm mt-1">Showing <?php echo e($tickets->count()); ?> of <?php echo e($tickets->total()); ?> tickets</p>
                    </div>
                </div>
            </div>
            
            <?php if($tickets->count() > 0): ?>
                <div class="p-6">
                    <div class="space-y-6">
                        <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white border border-wwc-neutral-200 rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <!-- Left Section: Icon and Event Details -->
                                    <div class="flex items-start space-x-6 flex-1">
                                        <!-- Ticket Icon -->
                                        <div class="flex-shrink-0">
                                            <div class="h-16 w-16 bg-wwc-primary/10 rounded-2xl flex items-center justify-center group-hover:bg-wwc-primary/20 transition-colors duration-200">
                                                <i class='bx bx-receipt text-wwc-primary text-2xl'></i>
                                            </div>
                                        </div>
                                        
                                        <!-- Event Details -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-xl font-bold text-wwc-neutral-900 font-display mb-3"><?php echo e($ticket->event->name); ?></h3>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div class="flex items-center text-sm text-wwc-neutral-600">
                                                    <i class='bx bx-calendar text-wwc-info mr-3 text-lg'></i>
                                                    <span class="font-medium"><?php echo e($ticket->event->date_time->format('M j, Y \a\t g:i A')); ?></span>
                                                </div>
                                                <div class="flex items-center text-sm text-wwc-neutral-600">
                                                    <i class='bx bx-map text-wwc-accent mr-3 text-lg'></i>
                                                    <span class="font-medium"><?php echo e($ticket->event->venue ?? 'Venue TBA'); ?></span>
                                                </div>
                                                <div class="flex items-center text-sm text-wwc-neutral-600">
                                                    <i class='bx bx-layer text-wwc-primary mr-3 text-lg'></i>
                                                    <span class="font-medium">Zone: <?php echo e($ticket->zone); ?></span>
                                                </div>
                                                <div class="flex items-center text-sm text-wwc-neutral-600">
                                                    <i class='bx bx-hash text-wwc-neutral-400 mr-3 text-lg'></i>
                                                    <span class="font-medium">Ticket #<?php echo e($ticket->id); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Right Section: Price, Status, and Action -->
                                    <div class="flex flex-col items-end space-y-4 ml-6">
                                        <!-- Price -->
                                        <div class="text-right">
                                            <div class="text-3xl font-bold text-wwc-primary mb-1">RM<?php echo e(number_format($ticket->price_paid, 0)); ?></div>
                                            <div class="text-sm text-wwc-neutral-500">Per Ticket</div>
                                        </div>
                                        
                                        <!-- Status Badge -->
                                        <div>
                                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                                                <?php if($ticket->status === 'Sold'): ?> bg-wwc-success/10 text-wwc-success border border-wwc-success/20
                                                <?php elseif($ticket->status === 'Held'): ?> bg-wwc-warning/10 text-wwc-warning border border-wwc-warning/20
                                                <?php elseif($ticket->status === 'Cancelled'): ?> bg-wwc-error/10 text-wwc-error border border-wwc-error/20
                                                <?php elseif($ticket->status === 'Used'): ?> bg-wwc-info/10 text-wwc-info border border-wwc-info/20
                                                <?php else: ?> bg-wwc-neutral-100 text-wwc-neutral-600 border border-wwc-neutral-200
                                                <?php endif; ?>">
                                                <i class='bx 
                                                    <?php if($ticket->status === 'Sold'): ?> bx-check-circle
                                                    <?php elseif($ticket->status === 'Held'): ?> bx-time
                                                    <?php elseif($ticket->status === 'Cancelled'): ?> bx-x-circle
                                                    <?php elseif($ticket->status === 'Used'): ?> bx-check-double
                                                    <?php else: ?> bx-question-mark
                                                    <?php endif; ?> mr-2'></i>
                                                <?php echo e($ticket->status); ?>

                                            </span>
                                        </div>
                                        
                                        <!-- Action Button -->
                                        <div>
                                            <a href="<?php echo e(route('customer.tickets.show', $ticket)); ?>" 
                                               class="inline-flex items-center px-6 py-3 bg-wwc-primary text-white rounded-xl font-semibold hover:bg-wwc-primary-dark hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                                <i class='bx bx-show mr-2'></i>
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8 pt-6 border-t border-wwc-neutral-200">
                        <?php echo e($tickets->links()); ?>

                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="mx-auto h-16 w-16 rounded-lg bg-wwc-neutral-100 flex items-center justify-center mb-4">
                        <svg class="h-8 w-8 text-wwc-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-wwc-neutral-900 mb-2">No tickets found</h3>
                    <p class="text-sm text-wwc-neutral-600 mb-6">You haven't purchased any tickets yet or no tickets match your filters.</p>
                    <div class="space-x-4">
                        <a href="<?php echo e(route('customer.events')); ?>" 
                           class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-lg transition-colors duration-200">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Browse Events
                        </a>
                        <?php if(request()->hasAny(['search', 'status', 'event'])): ?>
                            <a href="<?php echo e(route('customer.tickets')); ?>" 
                               class="inline-flex items-center px-4 py-2 text-sm font-semibold text-wwc-neutral-600 hover:bg-wwc-neutral-100 rounded-lg transition-colors duration-200">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Clear Filters
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/customer/tickets.blade.php ENDPATH**/ ?>