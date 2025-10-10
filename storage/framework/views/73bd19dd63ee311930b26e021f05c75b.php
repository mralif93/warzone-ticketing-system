<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', 'Customer Dashboard'); ?> - Warzone World Championship</title>
    <meta name="description" content="<?php echo $__env->yieldContent('description', 'Manage your tickets, orders, and profile in your Warzone World Championship customer portal.'); ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Custom WWC Brand Colors -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'wwc-primary': '#DC2626',
                        'wwc-primary-dark': '#B91C1C',
                        'wwc-primary-light': '#FEE2E2',
                        'wwc-accent': '#F59E0B',
                        'wwc-accent-light': '#FEF3C7',
                        'wwc-success': '#10B981',
                        'wwc-warning': '#F59E0B',
                        'wwc-error': '#EF4444',
                        'wwc-info': '#3B82F6',
                        'wwc-neutral': {
                            50: '#F9FAFB',
                            100: '#F3F4F6',
                            200: '#E5E7EB',
                            300: '#D1D5DB',
                            400: '#9CA3AF',
                            500: '#6B7280',
                            600: '#4B5563',
                            700: '#374151',
                            800: '#1F2937',
                            900: '#111827'
                        }
                    },
                    fontFamily: {
                        'display': ['Inter', 'system-ui', 'sans-serif'],
                        'sans': ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased bg-white">
    <div class="min-h-screen flex flex-col">
        <!-- Simple Navigation -->
        <nav class="bg-white border-b border-wwc-neutral-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="<?php echo e(route('customer.dashboard')); ?>" class="flex items-center">
                            <img src="<?php echo e(asset('images/warzone-logo.png')); ?>" alt="Warzone" class="h-8 w-auto">
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="<?php echo e(route('customer.dashboard')); ?>" 
                           class="text-wwc-neutral-600 hover:text-wwc-primary px-3 py-2 text-sm font-medium transition-colors duration-200 <?php echo e(request()->routeIs('customer.dashboard') ? 'text-wwc-primary' : ''); ?>">
                            Dashboard
                        </a>
                        <a href="<?php echo e(route('customer.events')); ?>" 
                           class="text-wwc-neutral-600 hover:text-wwc-primary px-3 py-2 text-sm font-medium transition-colors duration-200 <?php echo e(request()->routeIs('customer.events*') ? 'text-wwc-primary' : ''); ?>">
                            Events
                        </a>
                        <a href="<?php echo e(route('customer.tickets')); ?>" 
                           class="text-wwc-neutral-600 hover:text-wwc-primary px-3 py-2 text-sm font-medium transition-colors duration-200 <?php echo e(request()->routeIs('customer.tickets*') ? 'text-wwc-primary' : ''); ?>">
                            Tickets
                        </a>
                        <a href="<?php echo e(route('customer.orders')); ?>" 
                           class="text-wwc-neutral-600 hover:text-wwc-primary px-3 py-2 text-sm font-medium transition-colors duration-200 <?php echo e(request()->routeIs('customer.orders*') ? 'text-wwc-primary' : ''); ?>">
                            Orders
                        </a>
                        <a href="<?php echo e(route('customer.support')); ?>" 
                           class="text-wwc-neutral-600 hover:text-wwc-primary px-3 py-2 text-sm font-medium transition-colors duration-200 <?php echo e(request()->routeIs('customer.support*') ? 'text-wwc-primary' : ''); ?>">
                            Support
                        </a>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm focus:outline-none">
                                <div class="h-8 w-8 rounded-full bg-wwc-primary flex items-center justify-center">
                                    <span class="text-sm font-medium text-white"><?php echo e(substr(auth()->user()->name, 0, 1)); ?></span>
                                </div>
                                <span class="ml-2 text-wwc-neutral-700 font-medium"><?php echo e(auth()->user()->name); ?></span>
                                <i class='bx bx-chevron-down text-sm text-wwc-neutral-500'></i>
                            </button>

                            <div x-show="open" @click.away="open = false" 
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-wwc-neutral-200">
                                <a href="<?php echo e(route('customer.profile')); ?>" 
                                   class="block px-4 py-2 text-sm text-wwc-neutral-700 hover:bg-wwc-neutral-50">
                                    Profile
                                </a>
                                <a href="<?php echo e(route('customer.tickets')); ?>" 
                                   class="block px-4 py-2 text-sm text-wwc-neutral-700 hover:bg-wwc-neutral-50">
                                    My Tickets
                                </a>
                                <a href="<?php echo e(route('customer.orders')); ?>" 
                                   class="block px-4 py-2 text-sm text-wwc-neutral-700 hover:bg-wwc-neutral-50">
                                    Orders
                                </a>
                                <div class="border-t border-wwc-neutral-200"></div>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" 
                                            class="block w-full text-left px-4 py-2 text-sm text-wwc-neutral-700 hover:bg-wwc-neutral-50">
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-wwc-neutral-600 hover:text-wwc-neutral-900">
                            <i class='bx bx-menu text-2xl'></i>
                        </button>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div x-show="mobileMenuOpen" class="md:hidden" x-data="{ mobileMenuOpen: false }">
                    <div class="px-2 pt-2 pb-3 space-y-1 border-t border-wwc-neutral-200">
                        <a href="<?php echo e(route('customer.dashboard')); ?>" 
                           class="block px-3 py-2 text-base font-medium text-wwc-neutral-600 hover:text-wwc-primary">
                            Dashboard
                        </a>
                        <a href="<?php echo e(route('customer.events')); ?>" 
                           class="block px-3 py-2 text-base font-medium text-wwc-neutral-600 hover:text-wwc-primary">
                            Events
                        </a>
                        <a href="<?php echo e(route('customer.tickets')); ?>" 
                           class="block px-3 py-2 text-base font-medium text-wwc-neutral-600 hover:text-wwc-primary">
                            Tickets
                        </a>
                        <a href="<?php echo e(route('customer.orders')); ?>" 
                           class="block px-3 py-2 text-base font-medium text-wwc-neutral-600 hover:text-wwc-primary">
                            Orders
                        </a>
                        <a href="<?php echo e(route('customer.support')); ?>" 
                           class="block px-3 py-2 text-base font-medium text-wwc-neutral-600 hover:text-wwc-primary">
                            Support
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-grow">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <!-- SweetAlert Success Message -->
        <?php if(session('success')): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '<?php echo e(session('success')); ?>',
                        confirmButtonColor: '#DC2626',
                        confirmButtonText: 'Continue',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: true,
                        allowOutsideClick: false,
                        allowEscapeKey: true
                    });
                });
            </script>
        <?php endif; ?>

        <!-- Simple Footer -->
        <footer class="bg-white border-t border-wwc-neutral-200 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-wwc-neutral-500">
                    © 2025 Warzone World Championship SDN BHD. All rights reserved.
                </p>
            </div>
        </footer>
    </div>

    <!-- Alpine.js for interactivity -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- SweetAlert2 for beautiful alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/layouts/customer.blade.php ENDPATH**/ ?>