<?php $__env->startSection('title', 'System Settings'); ?>
<?php $__env->startSection('page-title', 'Settings'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional Settings Page with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Statistics Cards with WWC Brand Colors -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <!-- System Status -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1"><?php echo e($settings['maintenance_mode'] == '1' ? 'Offline' : 'Online'); ?></div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">System Status</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs <?php echo e($settings['maintenance_mode'] == '1' ? 'text-wwc-warning' : 'text-wwc-success'); ?> font-semibold">
                                    <i class='bx <?php echo e($settings['maintenance_mode'] == '1' ? 'bx-error' : 'bx-check-circle'); ?> text-xs mr-1'></i>
                                    <?php echo e($settings['maintenance_mode'] == '1' ? 'Maintenance Mode' : 'Operational'); ?>

                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg <?php echo e($settings['maintenance_mode'] == '1' ? 'bg-wwc-warning-light' : 'bg-wwc-success-light'); ?> flex items-center justify-center">
                            <i class='bx <?php echo e($settings['maintenance_mode'] == '1' ? 'bx-error' : 'bx-check-circle'); ?> text-2xl <?php echo e($settings['maintenance_mode'] == '1' ? 'text-wwc-warning' : 'text-wwc-success'); ?>'></i>
                        </div>
                    </div>
                </div>

                <!-- Max Tickets -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1"><?php echo e($settings['max_tickets_per_order']); ?></div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Max Tickets/Order</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-info font-semibold">
                                    <i class='bx bx-receipt text-xs mr-1'></i>
                                    Per Customer
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-info-light flex items-center justify-center">
                            <i class='bx bx-receipt text-2xl text-wwc-info'></i>
                        </div>
                    </div>
                </div>

                <!-- Seat Hold Duration -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1"><?php echo e($settings['seat_hold_duration_minutes']); ?>m</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Seat Hold Time</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-warning font-semibold">
                                    <i class='bx bx-time text-xs mr-1'></i>
                                    Auto Release
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-warning-light flex items-center justify-center">
                            <i class='bx bx-time text-2xl text-wwc-warning'></i>
                        </div>
                    </div>
                </div>

                <!-- Session Timeout -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1"><?php echo e($settings['session_timeout']); ?>m</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Session Timeout</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-accent font-semibold">
                                    <i class='bx bx-lock text-xs mr-1'></i>
                                    Security
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-accent-light flex items-center justify-center">
                            <i class='bx bx-lock text-2xl text-wwc-accent'></i>
                        </div>
                    </div>
                </div>
        </div>

        <!-- Settings Form -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">System Configuration</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-cog text-sm'></i>
                            <span>Configure system parameters</span>
                        </div>
                    </div>
                </div>
                <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="p-6">
                <?php echo csrf_field(); ?>
                    <!-- Ticket Settings -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-wwc-neutral-900 mb-4 flex items-center">
                            <i class='bx bx-receipt text-sm mr-2 text-wwc-primary'></i>
                            Ticket Settings
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="max_tickets_per_order" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Maximum Tickets per Order
                                </label>
                                <input type="number" name="max_tickets_per_order" id="max_tickets_per_order" 
                                       value="<?php echo e(old('max_tickets_per_order', $settings['max_tickets_per_order'])); ?>" 
                                       min="1" max="20" required
                                       class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['max_tickets_per_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <p class="mt-2 text-sm text-wwc-neutral-500">Maximum number of tickets a customer can purchase in a single order</p>
                                <?php $__errorArgs = ['max_tickets_per_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-wwc-error"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="seat_hold_duration_minutes" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Seat Hold Duration (Minutes)
                                </label>
                                <input type="number" name="seat_hold_duration_minutes" id="seat_hold_duration_minutes" 
                                       value="<?php echo e(old('seat_hold_duration_minutes', $settings['seat_hold_duration_minutes'])); ?>" 
                                       min="1" max="30" required
                                       class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['seat_hold_duration_minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <p class="mt-2 text-sm text-wwc-neutral-500">How long to hold seats during the purchase process</p>
                                <?php $__errorArgs = ['seat_hold_duration_minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-wwc-error"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <!-- System Settings -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-wwc-neutral-900 mb-4 flex items-center">
                            <i class='bx bx-cog text-sm mr-2 text-wwc-primary'></i>
                            System Settings
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="maintenance_mode" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Maintenance Mode
                                </label>
                                <select name="maintenance_mode" id="maintenance_mode" 
                                        class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                    <option value="0" <?php echo e(old('maintenance_mode', $settings['maintenance_mode']) == '0' ? 'selected' : ''); ?>>Disabled</option>
                                    <option value="1" <?php echo e(old('maintenance_mode', $settings['maintenance_mode']) == '1' ? 'selected' : ''); ?>>Enabled</option>
                                </select>
                                <p class="mt-2 text-sm text-wwc-neutral-500">Enable maintenance mode to temporarily disable the system</p>
                            </div>

                            <div>
                                <label for="auto_release_holds" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Auto Release Holds
                                </label>
                                <select name="auto_release_holds" id="auto_release_holds" 
                                        class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                    <option value="1" <?php echo e(old('auto_release_holds', $settings['auto_release_holds']) == '1' ? 'selected' : ''); ?>>Enabled</option>
                                    <option value="0" <?php echo e(old('auto_release_holds', $settings['auto_release_holds']) == '0' ? 'selected' : ''); ?>>Disabled</option>
                                </select>
                                <p class="mt-2 text-sm text-wwc-neutral-500">Automatically release held seats when they expire</p>
                            </div>
                        </div>
                    </div>

                    <!-- Email Settings -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-wwc-neutral-900 mb-4 flex items-center">
                            <i class='bx bx-envelope text-sm mr-2 text-wwc-primary'></i>
                            Email Settings
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="email_notifications" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Email Notifications
                                </label>
                                <select name="email_notifications" id="email_notifications" 
                                        class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                    <option value="1" <?php echo e(old('email_notifications', $settings['email_notifications']) == '1' ? 'selected' : ''); ?>>Enabled</option>
                                    <option value="0" <?php echo e(old('email_notifications', $settings['email_notifications']) == '0' ? 'selected' : ''); ?>>Disabled</option>
                                </select>
                                <p class="mt-2 text-sm text-wwc-neutral-500">Send email notifications for orders and tickets</p>
                            </div>

                            <div>
                                <label for="admin_email" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Admin Email
                                </label>
                                <input type="email" name="admin_email" id="admin_email" 
                                       value="<?php echo e(old('admin_email', $settings['admin_email'])); ?>" 
                                       class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['admin_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <p class="mt-2 text-sm text-wwc-neutral-500">Email address for system notifications</p>
                                <?php $__errorArgs = ['admin_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-wwc-error"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Security Settings -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-wwc-neutral-900 mb-4 flex items-center">
                            <i class='bx bx-shield text-sm mr-2 text-wwc-primary'></i>
                            Security Settings
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="session_timeout" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Session Timeout (Minutes)
                                </label>
                                <input type="number" name="session_timeout" id="session_timeout" 
                                       value="<?php echo e(old('session_timeout', $settings['session_timeout'])); ?>" 
                                       min="15" max="480" required
                                       class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['session_timeout'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <p class="mt-2 text-sm text-wwc-neutral-500">How long before user sessions expire</p>
                                <?php $__errorArgs = ['session_timeout'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-wwc-error"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="max_login_attempts" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Max Login Attempts
                                </label>
                                <input type="number" name="max_login_attempts" id="max_login_attempts" 
                                       value="<?php echo e(old('max_login_attempts', $settings['max_login_attempts'])); ?>" 
                                       min="3" max="10" required
                                       class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['max_login_attempts'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <p class="mt-2 text-sm text-wwc-neutral-500">Maximum failed login attempts before account lockout</p>
                                <?php $__errorArgs = ['max_login_attempts'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-wwc-error"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-wwc-neutral-200">
                        <button type="button" 
                                class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <i class='bx bx-refresh text-sm mr-2'></i>
                            Reset to Defaults
                        </button>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <i class='bx bx-save text-sm mr-2'></i>
                            Save Settings
                        </button>
                </div>
            </form>
        </div>

        <!-- System Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">System Information</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-info-circle text-sm'></i>
                            <span>Technical details</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-lg bg-wwc-primary-light flex items-center justify-center">
                                <i class='bx bx-code-alt text-sm text-wwc-primary'></i>
                            </div>
                            <div>
                                <dt class="text-sm font-semibold text-wwc-neutral-900">Laravel Version</dt>
                                <dd class="text-sm text-wwc-neutral-600"><?php echo e(app()->version()); ?></dd>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-lg bg-wwc-success-light flex items-center justify-center">
                                <i class='bx bx-code text-sm text-wwc-success'></i>
                    </div>
                    <div>
                                <dt class="text-sm font-semibold text-wwc-neutral-900">PHP Version</dt>
                                <dd class="text-sm text-wwc-neutral-600"><?php echo e(PHP_VERSION); ?></dd>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-lg bg-wwc-info-light flex items-center justify-center">
                                <i class='bx bx-data text-sm text-wwc-info'></i>
                    </div>
                    <div>
                                <dt class="text-sm font-semibold text-wwc-neutral-900">Database Driver</dt>
                                <dd class="text-sm text-wwc-neutral-600"><?php echo e(config('database.default')); ?></dd>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-lg bg-wwc-warning-light flex items-center justify-center">
                                <i class='bx bx-world text-sm text-wwc-warning'></i>
                    </div>
                    <div>
                                <dt class="text-sm font-semibold text-wwc-neutral-900">Environment</dt>
                                <dd class="text-sm text-wwc-neutral-600"><?php echo e(app()->environment()); ?></dd>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-lg bg-wwc-accent-light flex items-center justify-center">
                                <i class='bx bx-link text-sm text-wwc-accent'></i>
                    </div>
                    <div>
                                <dt class="text-sm font-semibold text-wwc-neutral-900">Application URL</dt>
                                <dd class="text-sm text-wwc-neutral-600"><?php echo e(config('app.url')); ?></dd>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-lg <?php echo e(config('app.debug') ? 'bg-wwc-error-light' : 'bg-wwc-success-light'); ?> flex items-center justify-center">
                                <i class='bx <?php echo e(config('app.debug') ? 'bx-error' : 'bx-check-circle'); ?> text-sm <?php echo e(config('app.debug') ? 'text-wwc-error' : 'text-wwc-success'); ?>'></i>
                    </div>
                    <div>
                                <dt class="text-sm font-semibold text-wwc-neutral-900">Debug Mode</dt>
                                <dd class="text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        <?php echo e(config('app.debug') ? 'bg-wwc-error-light text-wwc-error' : 'bg-wwc-success-light text-wwc-success'); ?>">
                                <?php echo e(config('app.debug') ? 'Enabled' : 'Disabled'); ?>

                            </span>
                        </dd>
                            </div>
                    </div>
                </dl>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/admin/settings/index.blade.php ENDPATH**/ ?>