<?php $__env->startSection('title', 'Forgot Password'); ?>
<?php $__env->startSection('description', 'Reset your Warzone World Championship account password to regain access to your account.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<div class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white font-display mb-6">
                Forgot Password?
            </h1>
            <p class="text-xl text-wwc-primary-light max-w-3xl mx-auto">
                No worries! Enter your email address and we'll send you a secure reset link.
            </p>
        </div>
    </div>
</div>

<!-- Forgot Password Form Section -->
<div class="py-20 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Professional Forgot Password Card -->
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark px-8 py-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class='bx bx-key text-white text-xl'></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Reset Password</h2>
                        <p class="text-wwc-primary-light text-sm">We'll send you a secure reset link</p>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="px-8 py-8">
                <form method="POST" action="<?php echo e(route('forgot-password')); ?>" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    
                    <?php if(session('status')): ?>
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class='bx bx-check-circle text-green-400 text-lg'></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800 mb-1">
                                        Reset Link Sent!
                                    </h3>
                                    <p class="text-sm text-green-700"><?php echo e(session('status')); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class='bx bx-error-circle text-red-400 text-lg'></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800 mb-2">
                                        Please correct the following errors:
                                    </h3>
                                    <ul class="text-sm text-red-700 space-y-1">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="flex items-center">
                                                <i class='bx bx-x text-red-500 mr-2'></i>
                                                <?php echo e($error); ?>

                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-800">
                            Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class='bx bx-envelope text-gray-400'></i>
                            </div>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo e(old('email')); ?>"
                                   class="block w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary transition-all duration-200 bg-gray-50 focus:bg-white focus:caret-wwc-primary <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="Enter your email address"
                                   required 
                                   autofocus>
                        </div>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class='bx bx-error text-red-500 mr-1'></i>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Information Card -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class='bx bx-info-circle text-blue-500 text-lg mr-3 mt-0.5'></i>
                            <div class="text-sm text-blue-700">
                                <h3 class="font-medium mb-2">What happens next?</h3>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>We'll send a secure reset link to your email</li>
                                    <li>Click the link to create a new password</li>
                                    <li>Sign in with your new password</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-wwc-primary to-wwc-primary-dark text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:ring-4 focus:ring-wwc-primary/30 focus:outline-none">
                            <div class="flex items-center justify-center space-x-2">
                                <i class='bx bx-send text-xl'></i>
                                <span>Send Reset Link</span>
                            </div>
                        </button>
                    </div>
                </form>

                <!-- Login Link -->
                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500 font-medium">Remember your password?</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="<?php echo e(route('login')); ?>" 
                           class="w-full flex items-center justify-center py-4 px-6 border-2 border-gray-200 rounded-xl shadow-sm bg-white text-gray-700 font-semibold hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 group">
                            <i class='bx bx-log-in text-lg mr-2 group-hover:text-wwc-primary transition-colors duration-200'></i>
                            Back to Sign In
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/auth/forgot-password.blade.php ENDPATH**/ ?>