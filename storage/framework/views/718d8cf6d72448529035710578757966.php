<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', 'Admin Dashboard'); ?> - <?php echo e(config('app.name', 'Warzone Ticketing System')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Alpine.js CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- SweetAlert2 for beautiful alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                        'display': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        // WWC Brand Color Palette
                        'wwc': {
                            'primary': '#DC2626',      // Red-600 - Main brand color
                            'primary-dark': '#B91C1C', // Red-700 - Darker shade
                            'primary-light': '#FEE2E2', // Red-100 - Light shade
                            'secondary': '#1F2937',    // Gray-800 - Secondary text
                            'accent': '#F59E0B',       // Amber-500 - Accent color
                            'success': '#059669',     // Emerald-600 - Success states
                            'warning': '#D97706',     // Amber-600 - Warning states
                            'error': '#DC2626',       // Red-600 - Error states
                            'info': '#2563EB',        // Blue-600 - Info states
                            'neutral': {
                                50: '#F9FAFB',
                                100: '#F3F4F6',
                                200: '#E5E7EB',
                                300: '#D1D5DB',
                                400: '#9CA3AF',
                                500: '#6B7280',
                                600: '#4B5563',
                                700: '#374151',
                                800: '#1F2937',
                                900: '#111827',
                            }
                        },
                    },
                    fontSize: {
                        'xs': ['0.75rem', { lineHeight: '1rem' }],
                        'sm': ['0.875rem', { lineHeight: '1.25rem' }],
                        'base': ['1rem', { lineHeight: '1.5rem' }],
                        'lg': ['1.125rem', { lineHeight: '1.75rem' }],
                        'xl': ['1.25rem', { lineHeight: '1.75rem' }],
                        '2xl': ['1.5rem', { lineHeight: '2rem' }],
                        '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
                        '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
                        '5xl': ['3rem', { lineHeight: '1' }],
                        '6xl': ['3.75rem', { lineHeight: '1' }],
                    },
                    fontWeight: {
                        'light': '300',
                        'normal': '400',
                        'medium': '500',
                        'semibold': '600',
                        'bold': '700',
                        'extrabold': '800',
                    }
                }
            }
        }
    </script>
    
    <!-- Vite Assets -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="hidden lg:flex lg:flex-shrink-0">
            <div class="flex flex-col w-80 bg-white shadow-xl border-r border-gray-100">
                <!-- Logo Section -->
                <div class="px-8 py-10 bg-gradient-to-br from-wwc-secondary to-wwc-neutral-900">
                    <div class="flex flex-col items-center">
                        <div class="flex-shrink-0 mb-6">
                            <img src="<?php echo e(asset('images/warzone-logo.png')); ?>" alt="Warzone Tickets" class="h-20 w-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div class="h-20 w-20 rounded-2xl bg-wwc-primary flex items-center justify-center shadow-lg" style="display: none;">
                                <span class="text-white font-bold text-3xl">W</span>
                            </div>
                        </div>
                        <div class="w-full p-4 bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl">
                            <div class="flex items-center justify-center mb-2">
                                <div class="h-2 w-2 bg-wwc-success rounded-full mr-3 animate-pulse"></div>
                                <span class="text-white text-sm font-semibold">System Online</span>
                            </div>
                            <div class="text-white text-xs text-center opacity-90">
                                Last Login <?php echo e(auth()->user()->updated_at->format('M j, Y')); ?> at <?php echo e(auth()->user()->updated_at->format('g:i A')); ?>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-6 py-8 space-y-2">
                    <!-- Dashboard -->
                    <a href="<?php echo e(route('admin.dashboard')); ?>" 
                       class="group flex items-center px-4 py-4 text-sm font-semibold rounded-xl transition-all duration-300 <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-wwc-primary-light to-red-100 text-wwc-primary shadow-lg border border-red-200' : 'text-wwc-neutral-600 hover:bg-wwc-neutral-50 hover:text-wwc-neutral-900 hover:shadow-md'); ?>">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-wwc-primary-light' : 'bg-wwc-neutral-100 group-hover:bg-wwc-neutral-200'); ?> transition-colors duration-300">
                                <i class='bx bx-home text-lg <?php echo e(request()->routeIs('admin.dashboard') ? 'text-wwc-primary' : 'text-wwc-neutral-500 group-hover:text-wwc-neutral-700'); ?>'></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold">Dashboard</div>
                            <div class="text-xs text-wwc-neutral-500 mt-0.5">Overview & Analytics</div>
                        </div>
                        <?php if(request()->routeIs('admin.dashboard')): ?>
                        <div class="h-2 w-2 bg-wwc-primary rounded-full"></div>
                        <?php endif; ?>
                    </a>

                    <!-- Users -->
                    <a href="<?php echo e(route('admin.users.index')); ?>" 
                       class="group flex items-center px-4 py-4 text-sm font-semibold rounded-xl transition-all duration-300 <?php echo e(request()->routeIs('admin.users*') ? 'bg-gradient-to-r from-wwc-primary-light to-red-100 text-wwc-primary shadow-lg border border-red-200' : 'text-wwc-neutral-600 hover:bg-wwc-neutral-50 hover:text-wwc-neutral-900 hover:shadow-md'); ?>">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center <?php echo e(request()->routeIs('admin.users*') ? 'bg-wwc-primary-light' : 'bg-wwc-neutral-100 group-hover:bg-wwc-neutral-200'); ?> transition-colors duration-300">
                                <i class='bx bx-group text-lg <?php echo e(request()->routeIs('admin.users*') ? 'text-wwc-primary' : 'text-wwc-neutral-500 group-hover:text-wwc-neutral-700'); ?>'></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold">Users</div>
                            <div class="text-xs text-wwc-neutral-500 mt-0.5">User Management</div>
                        </div>
                        <?php if(request()->routeIs('admin.users*')): ?>
                        <div class="h-2 w-2 bg-wwc-primary rounded-full"></div>
                        <?php endif; ?>
                    </a>

                    <!-- Events -->
                    <a href="<?php echo e(route('admin.events.index')); ?>" 
                       class="group flex items-center px-4 py-4 text-sm font-semibold rounded-xl transition-all duration-300 <?php echo e(request()->routeIs('admin.events*') ? 'bg-gradient-to-r from-wwc-primary-light to-red-100 text-wwc-primary shadow-lg border border-red-200' : 'text-wwc-neutral-600 hover:bg-wwc-neutral-50 hover:text-wwc-neutral-900 hover:shadow-md'); ?>">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center <?php echo e(request()->routeIs('admin.events*') ? 'bg-wwc-primary-light' : 'bg-wwc-neutral-100 group-hover:bg-wwc-neutral-200'); ?> transition-colors duration-300">
                                <i class='bx bx-calendar text-lg <?php echo e(request()->routeIs('admin.events*') ? 'text-wwc-primary' : 'text-wwc-neutral-500 group-hover:text-wwc-neutral-700'); ?>'></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold">Events</div>
                            <div class="text-xs text-wwc-neutral-500 mt-0.5">Manage Events</div>
                        </div>
                        <?php if(request()->routeIs('admin.events*')): ?>
                        <div class="h-2 w-2 bg-wwc-primary rounded-full"></div>
                        <?php endif; ?>
                    </a>

                    <!-- Tickets -->
                    <a href="<?php echo e(route('admin.tickets.index')); ?>" 
                       class="group flex items-center px-4 py-4 text-sm font-semibold rounded-xl transition-all duration-300 <?php echo e(request()->routeIs('admin.tickets*') ? 'bg-gradient-to-r from-wwc-primary-light to-red-100 text-wwc-primary shadow-lg border border-red-200' : 'text-wwc-neutral-600 hover:bg-wwc-neutral-50 hover:text-wwc-neutral-900 hover:shadow-md'); ?>">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center <?php echo e(request()->routeIs('admin.tickets*') ? 'bg-wwc-primary-light' : 'bg-wwc-neutral-100 group-hover:bg-wwc-neutral-200'); ?> transition-colors duration-300">
                                <i class='bx bx-receipt text-lg <?php echo e(request()->routeIs('admin.tickets*') ? 'text-wwc-primary' : 'text-wwc-neutral-500 group-hover:text-wwc-neutral-700'); ?>'></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold">Tickets</div>
                            <div class="text-xs text-wwc-neutral-500 mt-0.5">Ticket Management</div>
                        </div>
                        <?php if(request()->routeIs('admin.tickets*')): ?>
                        <div class="h-2 w-2 bg-wwc-primary rounded-full"></div>
                        <?php endif; ?>
                    </a>

                    <!-- Orders -->
                    <a href="<?php echo e(route('admin.orders.index')); ?>" 
                       class="group flex items-center px-4 py-4 text-sm font-semibold rounded-xl transition-all duration-300 <?php echo e(request()->routeIs('admin.orders*') ? 'bg-gradient-to-r from-wwc-primary-light to-red-100 text-wwc-primary shadow-lg border border-red-200' : 'text-wwc-neutral-600 hover:bg-wwc-neutral-50 hover:text-wwc-neutral-900 hover:shadow-md'); ?>">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center <?php echo e(request()->routeIs('admin.orders*') ? 'bg-wwc-primary-light' : 'bg-wwc-neutral-100 group-hover:bg-wwc-neutral-200'); ?> transition-colors duration-300">
                                <i class='bx bx-shopping-bag text-lg <?php echo e(request()->routeIs('admin.orders*') ? 'text-wwc-primary' : 'text-wwc-neutral-500 group-hover:text-wwc-neutral-700'); ?>'></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold">Orders</div>
                            <div class="text-xs text-wwc-neutral-500 mt-0.5">Order Processing</div>
                        </div>
                        <?php if(request()->routeIs('admin.orders*')): ?>
                        <div class="h-2 w-2 bg-wwc-primary rounded-full"></div>
                        <?php endif; ?>
                    </a>

                    <!-- Purchases -->
                    <a href="<?php echo e(route('admin.purchases.index')); ?>" 
                       class="group flex items-center px-4 py-4 text-sm font-semibold rounded-xl transition-all duration-300 <?php echo e(request()->routeIs('admin.purchases*') ? 'bg-gradient-to-r from-wwc-primary-light to-red-100 text-wwc-primary shadow-lg border border-red-200' : 'text-wwc-neutral-600 hover:bg-wwc-neutral-50 hover:text-wwc-neutral-900 hover:shadow-md'); ?>">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center <?php echo e(request()->routeIs('admin.purchases*') ? 'bg-wwc-primary-light' : 'bg-wwc-neutral-100 group-hover:bg-wwc-neutral-200'); ?> transition-colors duration-300">
                                <i class='bx bx-purchase-tag text-lg <?php echo e(request()->routeIs('admin.purchases*') ? 'text-wwc-primary' : 'text-wwc-neutral-500 group-hover:text-wwc-neutral-700'); ?>'></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold">Purchases</div>
                            <div class="text-xs text-wwc-neutral-500 mt-0.5">Ticket Purchases</div>
                        </div>
                        <?php if(request()->routeIs('admin.purchases*')): ?>
                        <div class="h-2 w-2 bg-wwc-primary rounded-full"></div>
                        <?php endif; ?>
                    </a>

                    <!-- Payments -->
                    <a href="<?php echo e(route('admin.payments.index')); ?>" 
                       class="group flex items-center px-4 py-4 text-sm font-semibold rounded-xl transition-all duration-300 <?php echo e(request()->routeIs('admin.payments*') ? 'bg-gradient-to-r from-wwc-primary-light to-red-100 text-wwc-primary shadow-lg border border-red-200' : 'text-wwc-neutral-600 hover:bg-wwc-neutral-50 hover:text-wwc-neutral-900 hover:shadow-md'); ?>">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center <?php echo e(request()->routeIs('admin.payments*') ? 'bg-wwc-primary-light' : 'bg-wwc-neutral-100 group-hover:bg-wwc-neutral-200'); ?> transition-colors duration-300">
                                <i class='bx bx-credit-card text-lg <?php echo e(request()->routeIs('admin.payments*') ? 'text-wwc-primary' : 'text-wwc-neutral-500 group-hover:text-wwc-neutral-700'); ?>'></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold">Payments</div>
                            <div class="text-xs text-wwc-neutral-500 mt-0.5">Payment Management</div>
                        </div>
                        <?php if(request()->routeIs('admin.payments*')): ?>
                        <div class="h-2 w-2 bg-wwc-primary rounded-full"></div>
                        <?php endif; ?>
                    </a>


                    <!-- Reports -->
                    <a href="<?php echo e(route('admin.reports')); ?>" 
                       class="group flex items-center px-4 py-4 text-sm font-semibold rounded-xl transition-all duration-300 <?php echo e(request()->routeIs('admin.reports*') ? 'bg-gradient-to-r from-wwc-primary-light to-red-100 text-wwc-primary shadow-lg border border-red-200' : 'text-wwc-neutral-600 hover:bg-wwc-neutral-50 hover:text-wwc-neutral-900 hover:shadow-md'); ?>">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center <?php echo e(request()->routeIs('admin.reports*') ? 'bg-wwc-primary-light' : 'bg-wwc-neutral-100 group-hover:bg-wwc-neutral-200'); ?> transition-colors duration-300">
                                <i class='bx bx-bar-chart text-lg <?php echo e(request()->routeIs('admin.reports*') ? 'text-wwc-primary' : 'text-wwc-neutral-500 group-hover:text-wwc-neutral-700'); ?>'></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold">Reports</div>
                            <div class="text-xs text-wwc-neutral-500 mt-0.5">Analytics & Insights</div>
                        </div>
                        <?php if(request()->routeIs('admin.reports*')): ?>
                        <div class="h-2 w-2 bg-wwc-primary rounded-full"></div>
                        <?php endif; ?>
                    </a>

                    <!-- Settings -->
                    <a href="<?php echo e(route('admin.settings')); ?>" 
                       class="group flex items-center px-4 py-4 text-sm font-semibold rounded-xl transition-all duration-300 <?php echo e(request()->routeIs('admin.settings*') ? 'bg-gradient-to-r from-wwc-primary-light to-red-100 text-wwc-primary shadow-lg border border-red-200' : 'text-wwc-neutral-600 hover:bg-wwc-neutral-50 hover:text-wwc-neutral-900 hover:shadow-md'); ?>">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center <?php echo e(request()->routeIs('admin.settings*') ? 'bg-wwc-primary-light' : 'bg-wwc-neutral-100 group-hover:bg-wwc-neutral-200'); ?> transition-colors duration-300">
                                <i class='bx bx-cog text-lg <?php echo e(request()->routeIs('admin.settings*') ? 'text-wwc-primary' : 'text-wwc-neutral-500 group-hover:text-wwc-neutral-700'); ?>'></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold">Settings</div>
                            <div class="text-xs text-wwc-neutral-500 mt-0.5">System Configuration</div>
                        </div>
                        <?php if(request()->routeIs('admin.settings*')): ?>
                        <div class="h-2 w-2 bg-wwc-primary rounded-full"></div>
                        <?php endif; ?>
                    </a>

                    <?php if(auth()->user()->isGateStaff()): ?>
                    <!-- Gate Staff Section -->
                    <div class="border-t border-wwc-neutral-200 my-4"></div>
                    
                    <!-- QR Scanner -->
                    <a href="<?php echo e(route('gate-staff.scanner')); ?>" 
                       class="group flex items-center px-4 py-4 text-sm font-semibold rounded-xl transition-all duration-300 <?php echo e(request()->routeIs('gate-staff.scanner') ? 'bg-gradient-to-r from-wwc-primary-light to-red-100 text-wwc-primary shadow-lg border border-red-200' : 'text-wwc-neutral-600 hover:bg-wwc-neutral-50 hover:text-wwc-neutral-900 hover:shadow-md'); ?>">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center <?php echo e(request()->routeIs('gate-staff.scanner') ? 'bg-wwc-primary-light' : 'bg-wwc-neutral-100 group-hover:bg-wwc-neutral-200'); ?> transition-colors duration-300">
                                <i class='bx bx-qr-scan text-lg <?php echo e(request()->routeIs('gate-staff.scanner') ? 'text-wwc-primary' : 'text-wwc-neutral-500 group-hover:text-wwc-neutral-700'); ?>'></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold">QR Scanner</div>
                            <div class="text-xs text-wwc-neutral-500 mt-0.5">Scan Tickets</div>
                        </div>
                        <?php if(request()->routeIs('gate-staff.scanner')): ?>
                        <div class="h-2 w-2 bg-wwc-primary rounded-full"></div>
                        <?php endif; ?>
                    </a>

                    <!-- Gate Staff Dashboard -->
                    <a href="<?php echo e(route('gate-staff.dashboard')); ?>" 
                       class="group flex items-center px-4 py-4 text-sm font-semibold rounded-xl transition-all duration-300 <?php echo e(request()->routeIs('gate-staff.dashboard') ? 'bg-gradient-to-r from-wwc-primary-light to-red-100 text-wwc-primary shadow-lg border border-red-200' : 'text-wwc-neutral-600 hover:bg-wwc-neutral-50 hover:text-wwc-neutral-900 hover:shadow-md'); ?>">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center <?php echo e(request()->routeIs('gate-staff.dashboard') ? 'bg-wwc-primary-light' : 'bg-wwc-neutral-100 group-hover:bg-wwc-neutral-200'); ?> transition-colors duration-300">
                                <i class='bx bx-tachometer text-lg <?php echo e(request()->routeIs('gate-staff.dashboard') ? 'text-wwc-primary' : 'text-wwc-neutral-500 group-hover:text-wwc-neutral-700'); ?>'></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold">Gate Dashboard</div>
                            <div class="text-xs text-wwc-neutral-500 mt-0.5">Staff Overview</div>
                        </div>
                        <?php if(request()->routeIs('gate-staff.dashboard')): ?>
                        <div class="h-2 w-2 bg-wwc-primary rounded-full"></div>
                        <?php endif; ?>
                    </a>

                    <!-- Scan History -->
                    <a href="<?php echo e(route('gate-staff.scan-history')); ?>" 
                       class="group flex items-center px-4 py-4 text-sm font-semibold rounded-xl transition-all duration-300 <?php echo e(request()->routeIs('gate-staff.scan-history') ? 'bg-gradient-to-r from-wwc-primary-light to-red-100 text-wwc-primary shadow-lg border border-red-200' : 'text-wwc-neutral-600 hover:bg-wwc-neutral-50 hover:text-wwc-neutral-900 hover:shadow-md'); ?>">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center <?php echo e(request()->routeIs('gate-staff.scan-history') ? 'bg-wwc-primary-light' : 'bg-wwc-neutral-100 group-hover:bg-wwc-neutral-200'); ?> transition-colors duration-300">
                                <i class='bx bx-history text-lg <?php echo e(request()->routeIs('gate-staff.scan-history') ? 'text-wwc-primary' : 'text-wwc-neutral-500 group-hover:text-wwc-neutral-700'); ?>'></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold">Scan History</div>
                            <div class="text-xs text-wwc-neutral-500 mt-0.5">View All Scans</div>
                        </div>
                        <?php if(request()->routeIs('gate-staff.scan-history')): ?>
                        <div class="h-2 w-2 bg-wwc-primary rounded-full"></div>
                        <?php endif; ?>
                    </a>
                    <?php endif; ?>
                </nav>

            </div>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top bar -->
            <header class="bg-white shadow-sm border-b border-wwc-neutral-200">
                <div class="flex items-center justify-between h-16 px-8 w-full">
                    <!-- Mobile menu button -->
                    <div class="lg:hidden">
                        <button type="button" class="p-2 rounded-md text-wwc-neutral-400 hover:text-wwc-neutral-500 hover:bg-wwc-neutral-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-wwc-primary" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                            <i class='bx bx-menu text-2xl'></i>
                        </button>
                    </div>

                    <!-- Page Title and Subtitle -->
                    <div class="flex-1 lg:flex-none">
                        <?php if (! empty(trim($__env->yieldContent('page-header')))): ?>
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-lg bg-wwc-primary-light flex items-center justify-center mr-3">
                                    <i class='bx bx-calendar-event text-xl text-wwc-primary'></i>
                                </div>
                                <div class="flex flex-col">
                                    <?php echo $__env->yieldContent('page-header'); ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-lg bg-wwc-primary-light flex items-center justify-center mr-3">
                                    <i class='bx bx-calendar-event text-xl text-wwc-primary'></i>
                                </div>
                                <div class="flex flex-col">
                                    <h1 class="text-xl font-bold text-wwc-neutral-900 font-display"><?php echo $__env->yieldContent('title', 'Admin Dashboard'); ?></h1>
                                    <p class="text-sm text-wwc-neutral-600 font-medium"><?php echo $__env->yieldContent('page-subtitle', 'Manage your system'); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Page Actions -->
                    <?php if (! empty(trim($__env->yieldContent('page-actions')))): ?>
                        <div class="hidden lg:block mr-4">
                            <?php echo $__env->yieldContent('page-actions'); ?>
                        </div>
                    <?php endif; ?>

                    <!-- User Profile Dropdown -->
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-wwc-primary to-wwc-primary-dark flex items-center justify-center">
                                <span class="text-sm font-semibold text-white"><?php echo e(substr(auth()->user()->name, 0, 1)); ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-wwc-neutral-900"><?php echo e(auth()->user()->name); ?></span>
                                <span class="text-xs text-wwc-neutral-500 capitalize font-medium"><?php echo e(auth()->user()->role); ?></span>
                            </div>
                        </div>
                        <div class="relative">
                            <button class="flex items-center space-x-1 p-2 text-wwc-neutral-400 hover:text-wwc-neutral-600 hover:bg-wwc-neutral-100 rounded-lg transition-colors duration-200" onclick="document.getElementById('user-dropdown').classList.toggle('hidden')">
                                <i class='bx bx-chevron-down text-sm'></i>
                            </button>
                            
                            <!-- User Dropdown Menu -->
                            <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class='bx bx-home text-sm mr-3'></i>
                                    Dashboard
                                </a>
                                <a href="<?php echo e(route('admin.settings')); ?>" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class='bx bx-cog text-sm mr-3'></i>
                                    Settings
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class='bx bx-log-out text-sm mr-3'></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div id="mobile-menu" class="hidden lg:hidden border-t border-gray-200 bg-white">
                    <!-- Mobile Page Actions -->
                    <?php if (! empty(trim($__env->yieldContent('page-actions')))): ?>
                        <div class="px-4 py-3 border-b border-gray-200">
                            <?php echo $__env->yieldContent('page-actions'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="px-2 pt-2 pb-3 space-y-1">
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md">Dashboard</a>
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md">Users</a>
                        <a href="<?php echo e(route('admin.events.index')); ?>" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md">Events</a>
                        <a href="<?php echo e(route('admin.tickets.index')); ?>" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md">Tickets</a>
                        <a href="<?php echo e(route('admin.orders.index')); ?>" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md">Orders</a>
                        <a href="<?php echo e(route('admin.payments.index')); ?>" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md">Payments</a>
                        <a href="<?php echo e(route('admin.reports')); ?>" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md">Reports</a>
                        <a href="<?php echo e(route('admin.settings')); ?>" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md">Settings</a>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <!-- Flash messages -->

                <?php if(session('error')): ?>
                    <div class="bg-red-50 border-l-4 border-red-400 px-6 py-5 mb-6 rounded-r-lg m-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class='bx bx-x-circle text-lg text-red-400'></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-red-700 font-medium"><?php echo e(session('error')); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- SweetAlert Success Message -->
                <?php if(session('success')): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            console.log('SweetAlert: Success message detected:', '<?php echo e(session('success')); ?>');
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

                <!-- Debug: Check if session success exists -->
                <?php if(config('app.debug')): ?>
                    <script>
                        console.log('Debug: Session success =', '<?php echo e(session('success')); ?>');
                        console.log('Debug: Session data =', <?php echo json_encode(session()->all(), 15, 512) ?>);
                    </script>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="bg-red-50 border-l-4 border-red-400 px-6 py-5 mb-6 rounded-r-lg m-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class='bx bx-x-circle text-lg text-red-400'></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm text-red-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <!-- QR Code Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qr-scanner@1.4.2/qr-scanner.umd.min.js"></script>
    
    <!-- Scripts Stack -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/layouts/admin.blade.php ENDPATH**/ ?>