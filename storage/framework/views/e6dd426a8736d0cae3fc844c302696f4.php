<?php $__env->startSection('title', 'Seat Management'); ?>
<?php $__env->startSection('page-subtitle', 'Manage all venue seats and pricing'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional Seat Management with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">
    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <!-- Total Seats -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1"><?php echo e($seats->total()); ?></div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Seats</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    <?php echo e($seats->where('is_accessible', true)->count()); ?> Accessible
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-chair text-2xl text-blue-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Available Seats -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1"><?php echo e($seats->where('is_accessible', true)->count()); ?></div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Accessible Seats</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <i class='bx bx-check text-xs mr-1'></i>
                                    <?php echo e($seats->where('is_accessible', false)->count()); ?> Standard
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class='bx bx-check-circle text-2xl text-green-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Price Zones -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1"><?php echo e($seats->pluck('price_zone')->unique()->count()); ?></div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Price Zones</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-accent font-semibold">
                                    <i class='bx bx-tag text-xs mr-1'></i>
                                    <?php echo e($seats->pluck('seat_type')->unique()->count()); ?> Types
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-orange-100 flex items-center justify-center">
                            <i class='bx bx-tag text-2xl text-orange-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue Potential -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">RM<?php echo e(number_format($seats->sum('base_price'), 0)); ?></div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Revenue Potential</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-accent font-semibold">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    +12% this month
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-purple-100 flex items-center justify-center">
                            <i class='bx bx-dollar text-2xl text-purple-600'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Search & Filter Seats</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-search text-sm'></i>
                            <span>Find specific seats</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form method="GET" action="<?php echo e(route('admin.seats.index')); ?>" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search Seats</label>
                            <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>"
                                   placeholder="Seat number, section, or row..."
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div>
                            <label for="section" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Section</label>
                            <select name="section" id="section" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Sections</option>
                                <?php $__currentLoopData = $seats->pluck('section')->unique()->sort(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($section); ?>" <?php echo e(request('section') == $section ? 'selected' : ''); ?>><?php echo e($section); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label for="price_zone" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Price Zone</label>
                            <select name="price_zone" id="price_zone" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Price Zones</option>
                                <?php $__currentLoopData = $seats->pluck('price_zone')->unique()->sort(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($zone); ?>" <?php echo e(request('price_zone') == $zone ? 'selected' : ''); ?>><?php echo e($zone); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label for="is_accessible" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Accessibility</label>
                            <select name="is_accessible" id="is_accessible" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Types</option>
                                <option value="1" <?php echo e(request('is_accessible') == '1' ? 'selected' : ''); ?>>Accessible</option>
                                <option value="0" <?php echo e(request('is_accessible') == '0' ? 'selected' : ''); ?>>Standard</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2 lg:col-span-4 flex justify-end gap-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-search text-sm mr-2'></i>
                                Search Seats
                            </button>
                            <a href="<?php echo e(route('admin.seats.index')); ?>" 
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
                <div class="flex items-center space-x-3">
                    <button onclick="openBulkCreateModal()" 
                            class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-plus text-sm mr-2'></i>
                        Bulk Create
                    </button>
                    <a href="<?php echo e(route('admin.seats.create')); ?>" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-plus text-sm mr-2'></i>
                        Create New Seat
                    </a>
                </div>
            </div>

            <!-- Seats List -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">All Seats</h3>
                        <div class="flex items-center space-x-4 text-sm font-medium">
                            <span class="text-wwc-neutral-600">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                Available (<?php echo e($seats->where('is_accessible', true)->count()); ?>)
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                Standard (<?php echo e($seats->where('is_accessible', false)->count()); ?>)
                            </span>
                        </div>
                    </div>
                </div>
                <div class="overflow-hidden">
                    <?php if($seats->count() > 0): ?>
                        <table class="min-w-full divide-y divide-wwc-neutral-100">
                            <thead class="bg-wwc-neutral-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Seat
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Location
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Price Zone
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-right">
                                        Price
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-wwc-neutral-100">
                                <?php $__currentLoopData = $seats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-wwc-neutral-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                                    <i class='bx bx-chair text-lg text-blue-600'></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-wwc-neutral-900"><?php echo e($seat->row); ?><?php echo e($seat->number); ?></div>
                                                <div class="text-xs text-wwc-neutral-500"><?php echo e($seat->section); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-wwc-neutral-900"><?php echo e($seat->section); ?></div>
                                        <div class="text-xs text-wwc-neutral-500">Row <?php echo e($seat->row); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                            <?php echo e($seat->price_zone); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-wwc-neutral-900">
                                        RM<?php echo e(number_format($seat->base_price, 0)); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            <?php if($seat->is_accessible): ?> bg-green-100 text-green-800
                                            <?php else: ?> bg-blue-100 text-blue-800
                                            <?php endif; ?>">
                                            <?php echo e($seat->is_accessible ? 'Accessible' : 'Standard'); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-1">
                                            <a href="<?php echo e(route('admin.seats.show', $seat)); ?>" 
                                               class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                               title="View seat details">
                                                <i class='bx bx-show text-xs mr-1.5'></i>
                                                View
                                            </a>
                                            <a href="<?php echo e(route('admin.seats.edit', $seat)); ?>" 
                                               class="inline-flex items-center px-3 py-2 text-xs font-semibold text-wwc-neutral-700 bg-white border border-wwc-neutral-300 hover:bg-wwc-neutral-50 hover:border-wwc-neutral-400 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                               title="Edit seat">
                                                <i class='bx bx-edit text-xs mr-1.5'></i>
                                                Edit
                                            </a>
                                            <div class="relative" x-data="{ open<?php echo e($seat->id); ?>: false }">
                                                <button @click="open<?php echo e($seat->id); ?> = !open<?php echo e($seat->id); ?>" 
                                                        class="inline-flex items-center px-3 py-2 text-xs font-semibold text-wwc-neutral-700 bg-white border border-wwc-neutral-300 hover:bg-wwc-neutral-50 hover:border-wwc-neutral-400 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                                        title="More actions">
                                                    <i class='bx bx-dots-vertical text-xs mr-1.5'></i>
                                                    More
                                                </button>
                                                <div x-show="open<?php echo e($seat->id); ?>" 
                                                     @click.away="open<?php echo e($seat->id); ?> = false"
                                                     x-transition:enter="transition ease-out duration-100"
                                                     x-transition:enter-start="transform opacity-0 scale-95"
                                                     x-transition:enter-end="transform opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="transform opacity-100 scale-100"
                                                     x-transition:leave-end="transform opacity-0 scale-95"
                                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-wwc-neutral-200 z-10"
                                                     style="display: none;">
                                                    <div class="py-1">
                                                        <form action="<?php echo e(route('admin.seats.update-status', $seat)); ?>" method="POST" class="block">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="is_accessible" value="<?php echo e($seat->is_accessible ? '0' : '1'); ?>">
                                                            <button type="submit" 
                                                                    class="flex items-center w-full px-4 py-2 text-xs text-wwc-neutral-700 hover:bg-wwc-neutral-100 transition-colors duration-200">
                                                                <i class='bx bx-<?php echo e($seat->is_accessible ? 'x' : 'check'); ?> text-xs mr-2'></i>
                                                                <?php echo e($seat->is_accessible ? 'Mark Standard' : 'Mark Accessible'); ?>

                                                            </button>
                                                        </form>
                                                        <div class="border-t border-wwc-neutral-100 my-1"></div>
                                                        <form action="<?php echo e(route('admin.seats.destroy', $seat)); ?>" method="POST" 
                                                              onsubmit="return confirm('Are you sure you want to delete this seat? This action cannot be undone.')" 
                                                              class="block">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" 
                                                                    class="flex items-center w-full px-4 py-2 text-xs text-red-600 hover:bg-red-50 transition-colors duration-200">
                                                                <i class='bx bx-trash text-xs mr-2'></i>
                                                                Delete Seat
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
                        <?php echo e($seats->links()); ?>

                    </div>
                <?php else: ?>
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div class="mx-auto h-24 w-24 rounded-full bg-wwc-neutral-100 flex items-center justify-center mb-4">
                            <i class='bx bx-chair text-6xl text-wwc-neutral-300'></i>
                        </div>
                        <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">No seats found.</h3>
                        <p class="text-wwc-neutral-600 mb-6">Get started by creating your first seat or adjust your search criteria.</p>
                        <a href="<?php echo e(route('admin.seats.create')); ?>" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <i class='bx bx-plus text-sm mr-2'></i>
                            Create New Seat
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Create Modal -->
<div id="bulkCreateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Bulk Create Seats</h3>
                <button onclick="closeBulkCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <i class='bx bx-x text-xl'></i>
                </button>
            </div>
            <form action="<?php echo e(route('admin.seats.bulk-create')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="space-y-4">
                    <div>
                        <label for="section" class="block text-sm font-semibold text-gray-900 mb-2">Section</label>
                        <input type="text" name="section" id="section" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm"
                               placeholder="e.g., A, B, VIP">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_row" class="block text-sm font-semibold text-gray-900 mb-2">Start Row</label>
                            <input type="text" name="start_row" id="start_row" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm"
                                   placeholder="e.g., 1">
                        </div>
                        <div>
                            <label for="end_row" class="block text-sm font-semibold text-gray-900 mb-2">End Row</label>
                            <input type="text" name="end_row" id="end_row" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm"
                                   placeholder="e.g., 10">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="seats_per_row" class="block text-sm font-semibold text-gray-900 mb-2">Seats per Row</label>
                            <input type="number" name="seats_per_row" id="seats_per_row" required min="1"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm"
                                   placeholder="e.g., 20">
                        </div>
                        <div>
                            <label for="price_zone" class="block text-sm font-semibold text-gray-900 mb-2">Price Zone</label>
                            <select name="price_zone" id="price_zone" required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">Select Price Zone</option>
                                <option value="VIP">VIP</option>
                                <option value="Premium">Premium</option>
                                <option value="Standard">Standard</option>
                                <option value="Economy">Economy</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="base_price" class="block text-sm font-semibold text-gray-900 mb-2">Base Price (RM)</label>
                        <input type="number" name="base_price" id="base_price" required step="0.01" min="0"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm"
                               placeholder="e.g., 50.00">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeBulkCreateModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-wwc-primary text-white rounded-lg text-sm font-semibold hover:bg-wwc-primary-dark">
                        Create Seats
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openBulkCreateModal() {
    document.getElementById('bulkCreateModal').classList.remove('hidden');
}

function closeBulkCreateModal() {
    document.getElementById('bulkCreateModal').classList.add('hidden');
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/admin/seats/index.blade.php ENDPATH**/ ?>