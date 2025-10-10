<?php $__env->startSection('title', 'Seat Details'); ?>
<?php $__env->startSection('page-title', 'Seat Details'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional Seat Details with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <div class="flex items-center">
                    <a href="<?php echo e(route('admin.seats.index')); ?>" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Seats
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Seat Details -->
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Seat Details</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-info-circle text-sm'></i>
                                    <span>Seat information</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Seat Identifier -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-chair text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Seat Identifier</span>
                                        <span class="text-base font-medium text-wwc-neutral-900"><?php echo e($seat->seat_identifier); ?></span>
                                    </div>
                                </div>

                                <!-- Section -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-grid-alt text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Section</span>
                                        <span class="text-base font-medium text-wwc-neutral-900"><?php echo e($seat->section); ?></span>
                                    </div>
                                </div>

                                <!-- Row -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-list-ul text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Row</span>
                                        <span class="text-base font-medium text-wwc-neutral-900"><?php echo e($seat->row); ?></span>
                                    </div>
                                </div>

                                <!-- Number -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                            <i class='bx bx-hash text-sm text-orange-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Seat Number</span>
                                        <span class="text-base font-medium text-wwc-neutral-900"><?php echo e($seat->number); ?></span>
                                    </div>
                                </div>

                                <!-- Price Zone -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-yellow-100 flex items-center justify-center">
                                            <i class='bx bx-tag text-sm text-yellow-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Price Zone</span>
                                        <span class="text-base font-medium text-wwc-neutral-900"><?php echo e($seat->price_zone); ?></span>
                                    </div>
                                </div>

                                <!-- Base Price -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                                            <i class='bx bx-dollar text-sm text-emerald-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Base Price</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">RM<?php echo e(number_format($seat->base_price, 0)); ?></span>
                                    </div>
                                </div>

                                <!-- Seat Type -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                            <i class='bx bx-category text-sm text-indigo-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Seat Type</span>
                                        <span class="text-base font-medium text-wwc-neutral-900"><?php echo e($seat->seat_type); ?></span>
                                    </div>
                                </div>

                                <!-- Accessibility -->
                                <div class="flex items-center py-3">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-teal-100 flex items-center justify-center">
                                            <i class='bx bx-accessibility text-sm text-teal-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Accessibility</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">
                                            <?php if($seat->is_accessible): ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class='bx bx-check text-xs mr-1'></i>
                                                    Accessible
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class='bx bx-x text-xs mr-1'></i>
                                                    Standard
                                                </span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Seat Statistics -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Seat Statistics</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Created Date -->
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-wwc-neutral-900"><?php echo e($seat->created_at->format('M d, Y')); ?></div>
                                    <div class="text-sm text-wwc-neutral-500">Created Date</div>
                                </div>
                                <!-- Updated Date -->
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-wwc-neutral-900"><?php echo e($seat->updated_at->format('M d, Y')); ?></div>
                                    <div class="text-sm text-wwc-neutral-500">Last Updated</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Quick Actions</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <a href="<?php echo e(route('admin.seats.edit', $seat)); ?>" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                    <i class='bx bx-edit text-sm mr-2'></i>
                                    Edit Seat
                                </a>
                                <a href="<?php echo e(route('admin.seats.index')); ?>" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                    <i class='bx bx-list-ul text-sm mr-2'></i>
                                    View All Seats
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/admin/seats/show.blade.php ENDPATH**/ ?>