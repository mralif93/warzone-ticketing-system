<?php $__env->startSection('title', 'Payment Management'); ?>
<?php $__env->startSection('page-subtitle', 'Manage all payment transactions and refunds'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional Payment Management with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">
    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <!-- Total Payments -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1"><?php echo e($payments->total()); ?></div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Payments</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    <?php echo e($payments->where('status', 'Completed')->count()); ?> Completed
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-credit-card text-2xl text-blue-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Completed Payments -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1"><?php echo e($payments->where('status', 'Completed')->count()); ?></div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Completed</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <i class='bx bx-check text-xs mr-1'></i>
                                    <?php echo e($payments->where('status', 'Pending')->count()); ?> Pending
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class='bx bx-check-circle text-2xl text-green-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Pending Payments -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1"><?php echo e($payments->where('status', 'Pending')->count()); ?></div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Pending</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-warning font-semibold">
                                    <i class='bx bx-time text-xs mr-1'></i>
                                    <?php echo e($payments->where('status', 'Failed')->count()); ?> Failed
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                            <i class='bx bx-time text-2xl text-yellow-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">RM<?php echo e(number_format($payments->where('status', 'Completed')->sum('amount'), 0)); ?></div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Revenue</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <i class='bx bx-trending-up text-xs mr-1'></i>
                                    RM<?php echo e(number_format($payments->where('status', 'Refunded')->sum('refund_amount') ?? 0, 0)); ?> Refunded
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-emerald-100 flex items-center justify-center">
                            <i class='bx bx-dollar text-2xl text-emerald-600'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Search & Filter Payments</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-search text-sm'></i>
                            <span>Find specific payments</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search Payments</label>
                            <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>"
                                   placeholder="Search by transaction ID, customer..."
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
                            <label for="payment_method" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Methods</option>
                                <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($method); ?>" <?php echo e(request('payment_method') == $method ? 'selected' : ''); ?>><?php echo e($method); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label for="date_from" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">From Date</label>
                            <input type="date" name="date_from" id="date_from" value="<?php echo e(request('date_from')); ?>"
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div class="sm:col-span-2 lg:col-span-4 flex justify-end gap-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-search text-sm mr-2'></i>
                                Search Payments
                            </button>
                            <a href="<?php echo e(route('admin.payments.index')); ?>" 
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
                <div class="flex space-x-3">
                    <a href="<?php echo e(route('admin.payments.create')); ?>" 
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-plus text-sm mr-2'></i>
                        Create New Payment
                    </a>
                    <a href="<?php echo e(route('admin.payments.export')); ?>" 
                        class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-download text-sm mr-2'></i>
                        Export CSV
                    </a>
                </div>
            </div>

            <!-- Payments List -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <!-- Table Header -->
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Payments</h3>
                        <div class="flex items-center space-x-4 text-sm text-wwc-neutral-500">
                            <span class="flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                <?php echo e($payments->where('status', 'Completed')->count()); ?> Completed
                            </span>
                            <span class="flex items-center">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                                <?php echo e($payments->where('status', 'Pending')->count()); ?> Pending
                            </span>
                            <span class="flex items-center">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                <?php echo e($payments->where('status', 'Failed')->count()); ?> Failed
                            </span>
                            <span class="flex items-center">
                                <span class="w-2 h-2 bg-gray-500 rounded-full mr-2"></span>
                                <?php echo e($payments->where('status', 'Refunded')->count()); ?> Refunded
                            </span>
                        </div>
                    </div>
                </div>

                <?php if($payments->count() > 0): ?>
                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-wwc-neutral-200">
                            <thead class="bg-wwc-neutral-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Payment</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Method</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-wwc-neutral-200">
                                <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-wwc-neutral-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                                    <i class='bx bx-credit-card text-lg text-blue-600'></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-wwc-neutral-900"><?php echo e($payment->transaction_id); ?></div>
                                                    <div class="text-xs text-wwc-neutral-500">#<?php echo e($payment->id); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-wwc-neutral-900"><?php echo e($payment->order->user->name ?? 'Guest'); ?></div>
                                            <div class="text-xs text-wwc-neutral-500"><?php echo e($payment->order->customer_email ?? 'N/A'); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-wwc-neutral-900">RM<?php echo e(number_format($payment->amount, 0)); ?></div>
                                            <?php if($payment->refund_amount): ?>
                                                <div class="text-xs text-red-600">Refunded: RM<?php echo e(number_format($payment->refund_amount, 0)); ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-wwc-neutral-900"><?php echo e($payment->payment_method); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <?php switch($payment->status):
                                                case ('Completed'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Completed
                                                    </span>
                                                    <?php break; ?>
                                                <?php case ('Pending'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Pending
                                                    </span>
                                                    <?php break; ?>
                                                <?php case ('Failed'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Failed
                                                    </span>
                                                    <?php break; ?>
                                                <?php case ('Refunded'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        Refunded
                                                    </span>
                                                    <?php break; ?>
                                            <?php endswitch; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-wwc-neutral-900"><?php echo e($payment->created_at->format('M d, Y')); ?></div>
                                            <div class="text-xs text-wwc-neutral-500"><?php echo e($payment->created_at->format('h:i A')); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="<?php echo e(route('admin.payments.show', $payment)); ?>"
                                                   class="inline-flex items-center px-3 py-1.5 border border-wwc-neutral-300 rounded-lg text-xs font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200"
                                                   title="View Payment">
                                                    <i class='bx bx-show text-sm'></i>
                                                </a>
                                                <a href="<?php echo e(route('admin.payments.edit', $payment)); ?>"
                                                   class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-lg text-xs font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200"
                                                   title="Edit Payment">
                                                    <i class='bx bx-edit text-sm'></i>
                                                </a>
                                                <div class="relative" x-data="{ open: false }">
                                                    <button @click="open = !open"
                                                            class="inline-flex items-center px-3 py-1.5 border border-wwc-neutral-300 rounded-lg text-xs font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200"
                                                            title="More Actions">
                                                        <i class='bx bx-dots-horizontal text-sm'></i>
                                                    </button>
                                                    <div x-show="open" @click.away="open = false" x-transition
                                                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-wwc-neutral-200 z-10">
                                                        <div class="py-1">
                                                            <?php if($payment->status === 'Completed'): ?>
                                                                <form method="POST" action="<?php echo e(route('admin.payments.refund', $payment)); ?>" class="block">
                                                                    <?php echo csrf_field(); ?>
                                                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-wwc-neutral-700 hover:bg-wwc-neutral-100">
                                                                        <i class='bx bx-undo text-sm mr-2'></i>
                                                                        Process Refund
                                                                    </button>
                                                                </form>
                                                            <?php endif; ?>
                                                            <?php if($payment->status !== 'Completed'): ?>
                                                                <form method="POST" action="<?php echo e(route('admin.payments.destroy', $payment)); ?>" class="block" onsubmit="return confirm('Are you sure you want to delete this payment?')">
                                                                    <?php echo csrf_field(); ?>
                                                                    <?php echo method_field('DELETE'); ?>
                                                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                                        <i class='bx bx-trash text-sm mr-2'></i>
                                                                        Delete Payment
                                                                    </button>
                                                                </form>
                                                            <?php endif; ?>
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
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-wwc-neutral-700">
                                Showing <?php echo e($payments->firstItem()); ?> to <?php echo e($payments->lastItem()); ?> of <?php echo e($payments->total()); ?> results
                            </div>
                            <div class="flex items-center space-x-2">
                                <?php echo e($payments->links('vendor.pagination.wwc-tailwind')); ?>

                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div class="mx-auto h-24 w-24 rounded-full bg-wwc-neutral-100 flex items-center justify-center mb-4">
                            <i class='bx bx-credit-card text-4xl text-wwc-neutral-400'></i>
                        </div>
                        <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">No payments found</h3>
                        <p class="text-wwc-neutral-500 mb-6">Get started by creating a new payment or adjusting your search criteria.</p>
                        <a href="<?php echo e(route('admin.payments.create')); ?>"
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <i class='bx bx-plus text-sm mr-2'></i>
                            Create Payment
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/admin/payments/index.blade.php ENDPATH**/ ?>