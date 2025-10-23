@extends('layouts.public')

@section('title', 'Sign In')
@section('description', 'Sign in to your Warzone World Championship account to access your tickets and manage your profile.')

@section('content')
<!-- Hero Section with Enhanced Design -->
<div class="bg-gradient-to-br from-wwc-primary via-wwc-primary-dark to-red-800 relative overflow-hidden">
    <!-- Background Pattern with Boxicons -->
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-10 left-10">
            <i class='bx bx-trophy text-4xl text-white opacity-30'></i>
        </div>
        <div class="absolute top-20 right-20">
            <i class='bx bx-medal text-3xl text-white opacity-20'></i>
        </div>
        <div class="absolute top-40 left-1/4">
            <i class='bx bx-star text-2xl text-white opacity-25'></i>
        </div>
        <div class="absolute top-60 right-1/3">
            <i class='bx bx-crown text-3xl text-white opacity-20'></i>
        </div>
        <div class="absolute bottom-20 left-20">
            <i class='bx bx-trophy text-2xl text-white opacity-30'></i>
        </div>
        <div class="absolute bottom-40 right-10">
            <i class='bx bx-medal text-4xl text-white opacity-25'></i>
        </div>
        <div class="absolute top-1/2 left-10">
            <i class='bx bx-star text-xl text-white opacity-20'></i>
        </div>
        <div class="absolute top-1/3 right-10">
            <i class='bx bx-crown text-2xl text-white opacity-25'></i>
        </div>
    </div>
    
    <div class="relative max-w-7xl mx-auto py-20 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <!-- Logo/Brand Icon -->
            <div class="mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 rounded-full backdrop-blur-sm">
                    <i class='bx bx-trophy text-4xl text-white'></i>
                </div>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-bold text-white font-display mb-6 tracking-tight">
                Welcome Back
            </h1>
            <p class="text-xl md:text-2xl text-wwc-primary-light max-w-4xl mx-auto leading-relaxed">
                Sign in to your Warzone World Championship account to access your tickets and manage your profile.
            </p>
            
            <!-- Trust Indicators -->
            <div class="mt-12 flex flex-wrap justify-center items-center gap-8 text-wwc-primary-light">
                <div class="flex items-center space-x-2">
                    <i class='bx bx-shield text-lg'></i>
                    <span class="text-sm font-medium">Secure Login</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class='bx bx-time text-lg'></i>
                    <span class="text-sm font-medium">24/7 Access</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class='bx bx-support text-lg'></i>
                    <span class="text-sm font-medium">Live Support</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Login Form Section -->
<div class="py-20 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Professional Login Card -->
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark px-8 py-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class='bx bx-log-in text-white text-xl'></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Welcome Back</h2>
                        <p class="text-wwc-primary-light text-sm">Sign in to continue your journey</p>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="px-8 py-8">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    @if ($errors->any())
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
                                        @foreach ($errors->all() as $error)
                                            <li class="flex items-center">
                                                <i class='bx bx-x text-red-500 mr-2'></i>
                                                {{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

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
                                   value="{{ old('email') }}"
                                   class="block w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary transition-all duration-200 bg-gray-50 focus:bg-white focus:caret-wwc-primary @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="Enter your email address"
                                   required 
                                   autofocus>
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class='bx bx-error text-red-500 mr-1'></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-800">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class='bx bx-lock text-gray-400'></i>
                            </div>
                            <input type="password" 
                                   id="password" 
                                   name="password"
                                   class="block w-full pl-12 pr-12 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary transition-all duration-200 bg-gray-50 focus:bg-white focus:caret-wwc-primary @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="Enter your password"
                                   required>
                            <button type="button" 
                                    onclick="togglePassword()" 
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center group">
                                <i id="password-toggle-icon" class='bx bx-hide text-gray-400 group-hover:text-gray-600 transition-colors duration-200'></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class='bx bx-error text-red-500 mr-1'></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between pt-2">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="remember" 
                                   name="remember" 
                                   class="h-5 w-5 text-wwc-primary bg-white border-2 border-gray-300 rounded-md focus:ring-wwc-primary focus:ring-2 focus:ring-offset-0">
                            <label for="remember" class="ml-3 text-sm font-medium text-gray-700">
                                Remember me
                            </label>
                        </div>
                        <div class="text-sm">
                            <a href="{{ route('forgot-password') }}" 
                               class="font-semibold text-wwc-primary hover:text-wwc-primary-dark transition-colors duration-200">
                                Forgot password?
                            </a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-wwc-primary to-wwc-primary-dark text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:ring-4 focus:ring-wwc-primary/30 focus:outline-none">
                            <div class="flex items-center justify-center space-x-2">
                                <i class='bx bx-log-in text-xl'></i>
                                <span>Sign In</span>
                            </div>
                        </button>
                    </div>
                </form>

                <!-- Registration Link -->
                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500 font-medium">New to Warzone?</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('register') }}" 
                           class="w-full flex items-center justify-center py-4 px-6 border-2 border-gray-200 rounded-xl shadow-sm bg-white text-gray-700 font-semibold hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 group">
                            <i class='bx bx-user-plus text-lg mr-2 group-hover:text-wwc-primary transition-colors duration-200'></i>
                            Create New Account
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Password Toggle JavaScript -->
<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('password-toggle-icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bx-hide');
        toggleIcon.classList.add('bx-show');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bx-show');
        toggleIcon.classList.add('bx-hide');
    }
}


</script>
@endsection