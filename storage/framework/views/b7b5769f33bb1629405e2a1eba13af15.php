<?php $__env->startSection('title', 'Event Management'); ?>
<?php $__env->startSection('page-subtitle', 'Manage all events and ticket sales'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional Event Management with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">
    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <!-- Total Events -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1"><?php echo e($events->total()); ?></div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Events</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-info font-semibold">
                                    <i class='bx bx-calendar text-xs mr-1'></i>
                                    <?php echo e($events->where('status', 'On Sale')->count()); ?> Active
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-calendar text-2xl text-blue-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Events On Sale -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1"><?php echo e($events->where('status', 'On Sale')->count()); ?></div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">On Sale</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <i class='bx bx-dollar text-xs mr-1'></i>
                                    <?php echo e($events->where('status', 'Draft')->count()); ?> Draft
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class='bx bx-dollar text-2xl text-green-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Sold Out Events -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1"><?php echo e($events->where('status', 'Sold Out')->count()); ?></div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Sold Out</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-error font-semibold">
                                    <i class='bx bx-x text-xs mr-1'></i>
                                    <?php echo e($events->where('status', 'Cancelled')->count()); ?> Cancelled
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-red-100 flex items-center justify-center">
                            <i class='bx bx-error text-2xl text-red-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">RM<?php echo e(number_format($events->sum(function($event) { return $event->purchase_tickets_count * 50; }), 0)); ?></div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Est. Revenue</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-accent font-semibold">
                                    <i class='bx bx-trending-up text-xs mr-1'></i>
                                    +12% this month
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-orange-100 flex items-center justify-center">
                            <i class='bx bx-dollar text-2xl text-orange-600'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Search & Filter Events</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-search text-sm'></i>
                            <span>Find specific events</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search Events</label>
                            <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>"
                                   placeholder="Search by name, venue, or description..."
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Status</label>
                            <select name="status" id="status" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Statuses</option>
                                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($status); ?>" <?php echo e(request('status') == $status ? 'selected' : ''); ?>><?php echo e($status); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label for="date_from" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">From Date</label>
                            <input type="date" name="date_from" id="date_from" value="<?php echo e(request('date_from')); ?>"
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div>
                            <label for="date_to" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">To Date</label>
                            <input type="date" name="date_to" id="date_to" value="<?php echo e(request('date_to')); ?>"
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div class="sm:col-span-2 lg:col-span-4 flex justify-end gap-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-search text-sm mr-2'></i>
                                Search Events
                            </button>
                            <a href="<?php echo e(route('admin.events.index')); ?>" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Header Section with Create Button -->
            <div class="flex justify-end items-center mb-6">
                <a href="<?php echo e(route('admin.events.create')); ?>" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-plus text-sm mr-2'></i>
                    Create New Event
                </a>
            </div>

            <!-- Events List -->
            <?php if($events->count() > 0): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">All Events</h3>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-calendar text-sm'></i>
                                    <span>Showing <?php echo e($events->count()); ?> of <?php echo e($events->total()); ?> events</span>
                                </div>
                                <form method="GET" class="flex items-center space-x-2">
                                    <?php $__currentLoopData = request()->except('limit'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($value); ?>">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <label for="limit" class="text-xs font-semibold text-wwc-neutral-600">Limit:</label>
                                    <select name="limit" id="limit" onchange="this.form.submit()" class="px-2 py-1 border border-wwc-neutral-300 rounded text-xs focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary">
                                        <option value="10" <?php echo e(request('limit', 10) == 10 ? 'selected' : ''); ?>>10</option>
                                        <option value="15" <?php echo e(request('limit', 10) == 15 ? 'selected' : ''); ?>>15</option>
                                        <option value="25" <?php echo e(request('limit', 10) == 25 ? 'selected' : ''); ?>>25</option>
                                        <option value="50" <?php echo e(request('limit', 10) == 50 ? 'selected' : ''); ?>>50</option>
                                        <option value="100" <?php echo e(request('limit', 10) == 100 ? 'selected' : ''); ?>>100</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-wwc-neutral-100">
                        <thead class="bg-wwc-neutral-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                    Event
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                    Date & Time
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                    Venue
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-center">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-center">
                                    Default
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Tickets Sold
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Revenue
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-right">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                            <tbody class="bg-white divide-y divide-wwc-neutral-100">
                            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-wwc-neutral-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-lg bg-wwc-primary-light flex items-center justify-center">
                                                    <i class='bx bx-calendar text-lg text-wwc-primary'></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                                <div class="text-sm font-semibold text-wwc-neutral-900"><?php echo e($event->name); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($event->isMultiDay()): ?>
                                        <div class="text-sm text-wwc-neutral-900"><?php echo e($event->getFormattedDateRange()); ?></div>
                                        <div class="text-xs text-wwc-neutral-500"><?php echo e($event->getDurationInDays()); ?> day<?php echo e($event->getDurationInDays() > 1 ? 's' : ''); ?></div>
                                    <?php else: ?>
                                    <div class="text-sm text-wwc-neutral-900"><?php echo e($event->date_time->format('M j, Y')); ?></div>
                                    <div class="text-xs text-wwc-neutral-500"><?php echo e($event->date_time->format('g:i A')); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-wwc-neutral-900"><?php echo e($event->venue ?? 'No venue'); ?></div>
                                </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            <?php if($event->status === 'On Sale'): ?> bg-green-100 text-green-800
                                            <?php elseif($event->status === 'Sold Out'): ?> bg-orange-100 text-orange-800
                                            <?php elseif($event->status === 'Cancelled'): ?> bg-red-100 text-red-800
                                            <?php elseif($event->status === 'Draft'): ?> bg-gray-100 text-gray-800
                                            <?php else: ?> bg-yellow-100 text-yellow-800
                                        <?php endif; ?>">
                                        <?php echo e($event->status); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <?php if($event->default): ?>
                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            <i class='bx bx-star text-xs mr-1'></i>
                                            Default
                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs text-wwc-neutral-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-wwc-neutral-900"><?php echo e(\App\Models\PurchaseTicket::where('event_id', $event->id)->where('status', 'Sold')->count()); ?></div>
                                        <div class="text-xs text-wwc-neutral-500">tickets sold</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-wwc-neutral-900">RM<?php echo e(number_format($event->purchaseTickets()->where('status', 'Sold')->sum('price_paid'), 0)); ?></div>
                                        <div class="text-xs text-wwc-neutral-500">total revenue</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-1">
                                        <a href="<?php echo e(route('admin.events.show', $event)); ?>" 
                                               class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                               title="View event details">
                                                <i class='bx bx-show text-xs mr-1.5'></i>
                                            View
                                        </a>
                                        <a href="<?php echo e(route('admin.events.edit', $event)); ?>" 
                                               class="inline-flex items-center px-3 py-2 text-xs font-semibold text-wwc-neutral-700 bg-white border border-wwc-neutral-300 hover:bg-wwc-neutral-50 hover:border-wwc-neutral-400 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                               title="Edit event">
                                                <i class='bx bx-edit text-xs mr-1.5'></i>
                                            Edit
                                        </a>
                                            <div class="relative" x-data="{ open<?php echo e($event->id); ?>: false }">
                                                <button @click="open<?php echo e($event->id); ?> = !open<?php echo e($event->id); ?>" 
                                                        class="inline-flex items-center px-3 py-2 text-xs font-semibold text-wwc-neutral-700 bg-white border border-wwc-neutral-300 hover:bg-wwc-neutral-50 hover:border-wwc-neutral-400 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                                        title="More actions">
                                                    <i class='bx bx-dots-vertical text-xs mr-1.5'></i>
                                                    More
                                                </button>
                                                <div x-show="open<?php echo e($event->id); ?>" 
                                                     @click.away="open<?php echo e($event->id); ?> = false"
                                                     x-transition:enter="transition ease-out duration-100"
                                                     x-transition:enter-start="transform opacity-0 scale-95"
                                                     x-transition:enter-end="transform opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="transform opacity-100 scale-100"
                                                     x-transition:leave-end="transform opacity-0 scale-95"
                                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-wwc-neutral-200 z-10"
                                                     style="display: none;">
                                                    <div class="py-1">
                                                        <?php if($event->status === 'Draft'): ?>
                                                            <form action="<?php echo e(route('admin.events.change-status', $event)); ?>" method="POST" class="block">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="status" value="On Sale">
                                                                <button type="submit" 
                                                                        class="flex items-center w-full px-4 py-2 text-xs text-wwc-neutral-700 hover:bg-wwc-success hover:text-white transition-colors duration-200">
                                                                    <i class='bx bx-play text-xs mr-2'></i>
                                                                    Go On Sale
                                                                </button>
                                                            </form>
                                                        <?php elseif($event->status === 'On Sale'): ?>
                                                            <form action="<?php echo e(route('admin.events.change-status', $event)); ?>" method="POST" class="block">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="status" value="Sold Out">
                                                                <button type="submit" 
                                                                        class="flex items-center w-full px-4 py-2 text-xs text-wwc-neutral-700 hover:bg-wwc-warning hover:text-white transition-colors duration-200">
                                                                    <i class='bx bx-pause text-xs mr-2'></i>
                                                                    Mark Sold Out
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                        <a href="<?php echo e(route('admin.tickets.index')); ?>?event=<?php echo e($event->id); ?>" 
                                                           class="flex items-center px-4 py-2 text-xs text-wwc-neutral-700 hover:bg-wwc-neutral-100 transition-colors duration-200">
                                                            <i class='bx bx-receipt text-xs mr-2'></i>
                                                            View Tickets
                                                        </a>
                                                        <div class="border-t border-wwc-neutral-100 my-1"></div>
                                                        <form action="<?php echo e(route('admin.events.destroy', $event)); ?>" method="POST" 
                                                              onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.')" 
                                                              class="block">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" 
                                                                    class="flex items-center w-full px-4 py-2 text-xs text-red-600 hover:bg-red-50 transition-colors duration-200">
                                                                <i class='bx bx-trash text-xs mr-2'></i>
                                                                Delete Event
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-wwc-neutral-100">
                    <?php echo e($events->links()); ?>

                </div>
            </div>
            <?php else: ?>
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                    <div class="text-center py-12">
                        <div class="mx-auto h-16 w-16 rounded-full bg-wwc-neutral-100 flex items-center justify-center mb-4">
                            <i class='bx bx-calendar text-3xl text-wwc-neutral-400'></i>
                        </div>
                        <h3 class="text-xl font-semibold text-wwc-neutral-900 font-display mb-2">No events found</h3>
                        <p class="text-sm text-wwc-neutral-600 mb-6">Get started by creating your first event to begin selling tickets.</p>
                        <div>
                            <a href="<?php echo e(route('admin.events.create')); ?>" 
                               class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-plus text-sm mr-2'></i>
                                Create Your First Event
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function changeEventStatus(eventId, newStatus) {
    if (!newStatus) return;
    
    if (confirm('Are you sure you want to change the event status?')) {
        fetch(`/admin/events/${eventId}/change-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to change event status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to change event status');
        });
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/admin/events/index.blade.php ENDPATH**/ ?>