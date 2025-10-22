<?php $__env->startSection('title', 'Available Tickets'); ?>
<?php $__env->startSection('description', 'Browse and purchase available tickets for upcoming events in your Warzone World Championship customer portal.'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Header Section -->
    <div class="bg-white border-b border-wwc-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display">Available Tickets</h1>
                    <p class="text-wwc-neutral-600 mt-1">Browse and purchase tickets for upcoming events</p>
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
            </div>
            <div class="p-6">
                <form method="GET" action="<?php echo e(route('customer.tickets')); ?>" class="space-y-4">
                    <!-- Form Fields Row -->
                    <div class="flex gap-4 items-end">
                        <div class="flex-1">
                            <label for="search" class="block text-sm font-medium text-wwc-neutral-700 mb-2">Search Tickets</label>
                            <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>"
                                   placeholder="Ticket ID, event name..."
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div class="w-1/3">
                            <label for="status" class="block text-sm font-medium text-wwc-neutral-700 mb-2">Status</label>
                            <select name="status" id="status" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Status</option>
                                <option value="sold" <?php echo e(request('status') == 'sold' ? 'selected' : ''); ?>>Sold</option>
                                <option value="held" <?php echo e(request('status') == 'held' ? 'selected' : ''); ?>>Held</option>
                                <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                                <option value="scanned" <?php echo e(request('status') == 'scanned' ? 'selected' : ''); ?>>Used</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Buttons Row -->
                    <div class="flex justify-end gap-3">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary">
                            Search Tickets
                        </button>
                        <a href="<?php echo e(route('customer.tickets')); ?>" 
                           class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 text-sm font-medium rounded-md text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tickets List -->
        <?php if($tickets->count() > 0): ?>
            <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-wwc-neutral-900">Available Tickets</h2>
                        <span class="text-sm text-wwc-neutral-500">Showing <?php echo e($tickets->count()); ?> of <?php echo e($tickets->total()); ?> tickets</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-wwc-neutral-200">
                        <thead class="bg-wwc-neutral-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Ticket Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-wwc-neutral-200">
                            <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-wwc-neutral-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 bg-wwc-primary rounded-lg flex items-center justify-center">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-wwc-neutral-900"><?php echo e($ticket->event->name); ?></div>
                                            <div class="text-sm text-wwc-neutral-500"><?php echo e($ticket->event->venue); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-wwc-neutral-900"><?php echo e($ticket->name); ?></div>
                                    <div class="text-sm text-wwc-neutral-500"><?php echo e($ticket->zone); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-wwc-neutral-900"><?php echo e($ticket->event->date_time->format('M j, Y')); ?></div>
                                    <div class="text-sm text-wwc-neutral-500"><?php echo e($ticket->event->date_time->format('g:i A')); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-wwc-neutral-900">RM<?php echo e(number_format($ticket->price, 0)); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        <?php if($ticket->status === 'active'): ?> bg-wwc-success text-white
                                        <?php elseif($ticket->status === 'sold_out'): ?> bg-wwc-error text-white
                                        <?php elseif($ticket->status === 'inactive'): ?> bg-wwc-neutral-200 text-wwc-neutral-800
                                        <?php else: ?> bg-wwc-neutral-200 text-wwc-neutral-800
                                        <?php endif; ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $ticket->status))); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-1">
                                        <a href="<?php echo e(route('public.events.show', $ticket->event->id)); ?>" 
                                           class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                           title="View event details">
                                            <i class='bx bx-show text-xs mr-1.5'></i>
                                            View Event
                                        </a>
                                        <a href="<?php echo e(route('public.tickets.cart', $ticket->event->id)); ?>" 
                                           class="inline-flex items-center px-3 py-2 text-xs font-semibold text-wwc-neutral-700 bg-wwc-neutral-100 hover:bg-wwc-neutral-200 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                           title="Purchase tickets">
                                            <i class='bx bx-cart text-xs mr-1.5'></i>
                                            Purchase
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-wwc-neutral-200">
                    <?php echo e($tickets->links()); ?>

                </div>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-wwc-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-wwc-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-wwc-neutral-900 mb-2">No tickets found</h3>
                    <p class="text-sm text-wwc-neutral-500 mb-6">No active tickets are available for purchase or no tickets match your filters.</p>
                    <div>
                        <a href="<?php echo e(route('public.events')); ?>" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary">
                            Browse Events
                        </a>
                        <?php if(request()->hasAny(['search', 'status', 'event'])): ?>
                            <a href="<?php echo e(route('customer.tickets')); ?>" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 text-sm font-medium rounded-md text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary ml-3">
                                Clear Filters
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/customer/tickets.blade.php ENDPATH**/ ?>