<?php $__env->startSection('title', 'User Management'); ?>
<?php $__env->startSection('page-subtitle', 'Manage system users, roles, and permissions'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional User Management with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <!-- Total Users -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1"><?php echo e($users->total()); ?></div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Users</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-info font-semibold">
                                    <i class='bx bx-group text-xs mr-1'></i>
                                    <?php echo e($users->where('role', 'Customer')->count()); ?> Customers
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-group text-2xl text-blue-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Administrators -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1"><?php echo e($users->where('role', 'Administrator')->count()); ?></div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Administrators</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-primary font-semibold">
                                    <i class='bx bx-shield text-xs mr-1'></i>
                                    System Admins
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-red-100 flex items-center justify-center">
                            <i class='bx bx-shield text-2xl text-red-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Staff Members -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1"><?php echo e($users->whereIn('role', ['Gate Staff', 'Counter Staff', 'Support Staff'])->count()); ?></div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Staff Members</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <i class='bx bx-user-check text-xs mr-1'></i>
                                    Active Staff
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class='bx bx-user-check text-2xl text-green-600'></i>
            </div>
            </div>
        </div>

                <!-- Active Users -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                    <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1"><?php echo e($users->where('is_active', true)->count()); ?></div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Active Users</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-accent font-semibold">
                                    <i class='bx bx-check-circle text-xs mr-1'></i>
                                    <?php echo e(round(($users->where('is_active', true)->count() / max($users->total(), 1)) * 100)); ?>% Active
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-orange-100 flex items-center justify-center">
                            <i class='bx bx-check-circle text-2xl text-orange-600'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Search & Filter Users</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-search text-sm'></i>
                            <span>Find specific users</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search Users</label>
                        <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>"
                                   placeholder="Search by name, email, or role..."
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                    </div>
                    <div>
                            <label for="role" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Role</label>
                            <select name="role" id="role" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                            <option value="">All Roles</option>
                            <option value="Administrator" <?php echo e(request('role') == 'Administrator' ? 'selected' : ''); ?>>Administrator</option>
                            <option value="Gate Staff" <?php echo e(request('role') == 'Gate Staff' ? 'selected' : ''); ?>>Gate Staff</option>
                            <option value="Counter Staff" <?php echo e(request('role') == 'Counter Staff' ? 'selected' : ''); ?>>Counter Staff</option>
                            <option value="Support Staff" <?php echo e(request('role') == 'Support Staff' ? 'selected' : ''); ?>>Support Staff</option>
                            <option value="Customer" <?php echo e(request('role') == 'Customer' ? 'selected' : ''); ?>>Customer</option>
                        </select>
                    </div>
                    <div>
                            <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Status</label>
                            <select name="status" id="status" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Statuses</option>
                            <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                            <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
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
                                Search Users
                        </button>
                            <a href="<?php echo e(route('admin.users.index')); ?>" 
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
                <a href="<?php echo e(route('admin.users.create')); ?>" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-plus text-sm mr-2'></i>
                    Create New User
                </a>
            </div>

            <!-- Users List -->
                <?php if($users->count() > 0): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">All Users</h3>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-group text-sm'></i>
                                    <span>Showing <?php echo e($users->count()); ?> of <?php echo e($users->total()); ?> users</span>
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
                                        User
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-center">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Orders
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Tickets
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-right">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-wwc-neutral-100">
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-wwc-neutral-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-lg bg-wwc-primary-light flex items-center justify-center">
                                                    <i class='bx bx-user text-lg text-wwc-primary'></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-wwc-neutral-900"><?php echo e($user->name); ?></div>
                                                <div class="text-xs text-wwc-neutral-500"><?php echo e($user->email); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            <?php if($user->role === 'Administrator'): ?> bg-red-100 text-red-800
                                            <?php elseif($user->role === 'Gate Staff'): ?> bg-green-100 text-green-800
                                            <?php elseif($user->role === 'Counter Staff'): ?> bg-blue-100 text-blue-800
                                            <?php elseif($user->role === 'Support Staff'): ?> bg-purple-100 text-purple-800
                                            <?php else: ?> bg-gray-100 text-gray-800
                                            <?php endif; ?>">
                                            <?php echo e($user->role); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            <?php if($user->is_active): ?> bg-green-100 text-green-800
                                            <?php else: ?> bg-red-100 text-red-800
                                            <?php endif; ?>">
                                            <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-wwc-neutral-900"><?php echo e($user->orders_count); ?></div>
                                        <div class="text-xs text-wwc-neutral-500">orders</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-wwc-neutral-900"><?php echo e($user->customer_tickets_count); ?></div>
                                        <div class="text-xs text-wwc-neutral-500">tickets</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-1">
                                            <a href="<?php echo e(route('admin.users.show', $user)); ?>" 
                                               class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                               title="View user details">
                                                <i class='bx bx-show text-xs mr-1.5'></i>
                                                View
                                            </a>
                                            <a href="<?php echo e(route('admin.users.edit', $user)); ?>" 
                                               class="inline-flex items-center px-3 py-2 text-xs font-semibold text-wwc-neutral-700 bg-white border border-wwc-neutral-300 hover:bg-wwc-neutral-50 hover:border-wwc-neutral-400 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                               title="Edit user">
                                                <i class='bx bx-edit text-xs mr-1.5'></i>
                                                Edit
                                            </a>
                                            <div class="relative" x-data="{ open<?php echo e($user->id); ?>: false }">
                                                <button @click="open<?php echo e($user->id); ?> = !open<?php echo e($user->id); ?>" 
                                                        class="inline-flex items-center px-3 py-2 text-xs font-semibold text-wwc-neutral-700 bg-white border border-wwc-neutral-300 hover:bg-wwc-neutral-50 hover:border-wwc-neutral-400 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                                        title="More actions">
                                                    <i class='bx bx-dots-vertical text-xs mr-1.5'></i>
                                                    More
                                                </button>
                                                <div x-show="open<?php echo e($user->id); ?>" 
                                                     @click.away="open<?php echo e($user->id); ?> = false"
                                                     x-transition:enter="transition ease-out duration-100"
                                                     x-transition:enter-start="transform opacity-0 scale-95"
                                                     x-transition:enter-end="transform opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="transform opacity-100 scale-100"
                                                     x-transition:leave-end="transform opacity-0 scale-95"
                                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-wwc-neutral-200 z-10"
                                                     style="display: none;">
                                                    <div class="py-1">
                                                        <?php if($user->is_active): ?>
                                                            <form action="<?php echo e(route('admin.users.update-status', $user)); ?>" method="POST" class="block">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="is_active" value="0">
                                                                <button type="submit" 
                                                                        class="flex items-center w-full px-4 py-2 text-xs text-wwc-neutral-700 hover:bg-red-50 transition-colors duration-200">
                                                                    <i class='bx bx-user-x text-xs mr-2'></i>
                                                                    Deactivate User
                                                                </button>
                                                            </form>
                                                        <?php else: ?>
                                                            <form action="<?php echo e(route('admin.users.update-status', $user)); ?>" method="POST" class="block">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="is_active" value="1">
                                                                <button type="submit" 
                                                                        class="flex items-center w-full px-4 py-2 text-xs text-wwc-neutral-700 hover:bg-green-50 transition-colors duration-200">
                                                                    <i class='bx bx-user-check text-xs mr-2'></i>
                                                                    Activate User
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                        <a href="<?php echo e(route('admin.users.update-password', $user)); ?>" 
                                                           class="flex items-center px-4 py-2 text-xs text-wwc-neutral-700 hover:bg-wwc-neutral-100 transition-colors duration-200">
                                                            <i class='bx bx-key text-xs mr-2'></i>
                                                            Reset Password
                                                        </a>
                                                        <div class="border-t border-wwc-neutral-100 my-1"></div>
                                                        <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" 
                                                              onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')" 
                                                              class="block">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" 
                                                                    class="flex items-center w-full px-4 py-2 text-xs text-red-600 hover:bg-red-50 transition-colors duration-200">
                                                                <i class='bx bx-trash text-xs mr-2'></i>
                                                                Delete User
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
                        <?php echo e($users->links()); ?>

                    </div>
                    </div>
                <?php else: ?>
                <div class="bg-white shadow-sm rounded-2xl border border-wwc-neutral-200">
                    <div class="text-center py-12">
                        <div class="mx-auto h-16 w-16 rounded-full bg-wwc-neutral-100 flex items-center justify-center mb-4">
                            <i class='bx bx-group text-3xl text-wwc-neutral-400'></i>
                        </div>
                        <h3 class="text-xl font-semibold text-wwc-neutral-900 font-display mb-2">No users found</h3>
                        <p class="text-sm text-wwc-neutral-600 mb-6">Get started by creating your first user to begin managing the system.</p>
                        <div>
                            <a href="<?php echo e(route('admin.users.create')); ?>" 
                               class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-2xl text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-plus text-sm mr-2'></i>
                                Add New User
                            </a>
                        </div>
                        </div>
                    </div>
                <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/admin/users/index.blade.php ENDPATH**/ ?>