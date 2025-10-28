<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Warzone World Championship') - Premium Event Tickets</title>
    <meta name="description" content="@yield('description', 'Get premium tickets for the best events. Secure, fast, and reliable ticketing system for concerts, sports, and entertainment.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Fallback Boxicons CDN -->
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-wwc-neutral-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo and Brand -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center">
                            <img src="{{ asset('images/warzone-logo.png') }}" alt="Warzone" class="h-8 w-auto">
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('home') }}" 
                           class="text-wwc-neutral-600 hover:text-wwc-primary px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('home') ? 'text-wwc-primary bg-wwc-primary-light' : '' }}">
                            Home
                        </a>
                        <a href="{{ route('public.about') }}" 
                           class="text-wwc-neutral-600 hover:text-wwc-primary px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('public.about') ? 'text-wwc-primary bg-wwc-primary-light' : '' }}">
                            About
                        </a>
                        <a href="{{ route('public.contact') }}" 
                           class="text-wwc-neutral-600 hover:text-wwc-primary px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('public.contact*') ? 'text-wwc-primary bg-wwc-primary-light' : '' }}">
                            Contact
                        </a>
                        <a href="{{ route('public.faq') }}" 
                           class="text-wwc-neutral-600 hover:text-wwc-primary px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('public.faq') ? 'text-wwc-primary bg-wwc-primary-light' : '' }}">
                            FAQ
                        </a>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="flex items-center space-x-4">
                        @auth
                            <!-- Authenticated User Menu -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-3 text-wwc-neutral-700 hover:text-wwc-primary px-3 py-2 rounded-lg transition-colors duration-200 hover:bg-gray-50">
                                    <!-- Avatar -->
                                    <div class="w-8 h-8 bg-wwc-primary rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                    <!-- User Name -->
                                    <span class="font-medium text-sm">{{ Auth::user()->name }}</span>
                                    <!-- Dropdown Indicator -->
                                    <i class='bx bx-chevron-down text-wwc-neutral-500 text-sm'></i>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-wwc-neutral-200">
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-wwc-neutral-700 hover:bg-wwc-primary-light hover:text-wwc-primary">Dashboard</a>
                                    <a href="{{ route('customer.orders') }}" class="block px-4 py-2 text-sm text-wwc-neutral-700 hover:bg-wwc-primary-light hover:text-wwc-primary">My Orders</a>
                                    <div class="border-t border-wwc-neutral-200"></div>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-wwc-neutral-700 hover:bg-wwc-primary-light hover:text-wwc-primary">
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- Guest User Buttons -->
                            <a href="{{ route('login') }}" 
                               class="text-wwc-neutral-600 hover:text-wwc-primary px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                Sign In
                            </a>
                            <a href="{{ route('register') }}" 
                               class="bg-wwc-primary hover:bg-wwc-primary-dark text-white px-4 py-2 rounded-2xl text-sm font-semibold transition-colors duration-200">
                                Sign Up
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- SweetAlert Success Message -->
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    console.log('SweetAlert: Success message detected:', '{{ session('success') }}');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session('success') }}',
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
        @endif

        <!-- Footer -->
        <footer class="bg-wwc-neutral-900 text-white">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <!-- Left Column - Brand -->
                    <div>
                        <div class="text-2xl font-bold text-wwc-primary mb-2">WARZONE</div>
                        <p class="text-wwc-neutral-300 text-sm">
                            Your premier destination for event tickets and entertainment experiences.
                        </p>
                    </div>
                    
                    <!-- Middle Column - Quick Links -->
                    <div class="flex flex-col md:flex-row gap-8">
                        <div>
                            <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-3">QUICK LINKS</h3>
                            <ul class="space-y-1">
                                <li><a href="{{ route('public.faq') }}" class="text-sm text-white hover:text-wwc-primary transition-colors duration-200">FAQ</a></li>
                                <li><a href="{{ route('public.about') }}" class="text-sm text-white hover:text-wwc-primary transition-colors duration-200">About Us</a></li>
                                <li><a href="{{ route('public.contact') }}" class="text-sm text-white hover:text-wwc-primary transition-colors duration-200">Contact</a></li>
                            </ul>
                        </div>
                        
                        <!-- Right Column - Legal -->
                        <div>
                            <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-3">LEGAL</h3>
                            <ul class="space-y-1">
                                <li><a href="{{ route('public.terms-of-service') }}" class="text-sm text-white hover:text-wwc-primary transition-colors duration-200">Terms of Service</a></li>
                                <li><a href="{{ route('public.refund-policy') }}" class="text-sm text-white hover:text-wwc-primary transition-colors duration-200">Refund Policy</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Copyright -->
                <div class="border-t border-wwc-neutral-800 mt-6 pt-4">
                    <p class="text-center text-white text-sm">
                        © 2025 Warzone World Championship SDN BHD. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <!-- SweetAlert2 for beautiful alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Alpine.js for interactivity -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('scripts')
</body>
</html>
