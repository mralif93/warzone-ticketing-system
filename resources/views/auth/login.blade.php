@extends('layouts.public')

@section('title', 'Sign In')
@section('description', 'Sign in to your Warzone World Championship account to access your tickets and manage your profile.')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white font-display mb-6">
                Welcome Back
            </h1>
            <p class="text-xl text-wwc-primary-light max-w-3xl mx-auto">
                Sign in to your Warzone World Championship account to access your tickets and manage your profile.
            </p>
        </div>
    </div>
</div>

<!-- Login Form Section -->
<div class="py-16 bg-white">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Login Form Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-wwc-neutral-200 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-wwc-primary px-8 py-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class='bx bx-log-in text-white text-xl'></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white font-display">Sign In</h2>
                        <p class="text-wwc-primary-light text-sm">Access your account to continue</p>
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
                        <label for="email" class="block text-sm font-medium text-wwc-neutral-700">
                            Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class='bx bx-envelope text-wwc-neutral-400'></i>
                            </div>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   class="w-full pl-12 pr-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent @error('email') border-wwc-error @enderror"
                                   placeholder="Enter your email address"
                                   required 
                                   autofocus>
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-wwc-error flex items-center">
                                <i class='bx bx-error text-wwc-error mr-1'></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-wwc-neutral-700">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class='bx bx-lock text-wwc-neutral-400'></i>
                            </div>
                            <input type="password" 
                                   id="password" 
                                   name="password"
                                   class="w-full pl-12 pr-12 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent @error('password') border-wwc-error @enderror"
                                   placeholder="Enter your password"
                                   required>
                            <button type="button" 
                                    onclick="togglePassword()" 
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center group">
                                <i id="password-toggle-icon" class='bx bx-hide text-wwc-neutral-400 group-hover:text-wwc-neutral-600 transition-colors duration-200'></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-wwc-error flex items-center">
                                <i class='bx bx-error text-wwc-error mr-1'></i>
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
                                   class="h-5 w-5 text-wwc-primary bg-white border-2 border-wwc-neutral-300 rounded-md focus:ring-wwc-primary focus:ring-2 focus:ring-offset-0">
                            <label for="remember" class="ml-3 text-sm font-medium text-wwc-neutral-700">
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
                                class="w-full bg-wwc-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-wwc-primary-dark transition-colors duration-200 focus:ring-2 focus:ring-wwc-primary focus:ring-offset-2">
                            <div class="flex items-center justify-center space-x-2">
                                <i class='bx bx-log-in text-lg'></i>
                                <span>Sign In</span>
                            </div>
                        </button>
                    </div>
                </form>

                <!-- Registration Link -->
                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-wwc-neutral-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-wwc-neutral-500 font-medium">New to Warzone?</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('register') }}" 
                           class="w-full flex items-center justify-center py-3 px-6 border-2 border-wwc-neutral-300 rounded-lg bg-white text-wwc-neutral-700 font-semibold hover:bg-wwc-neutral-50 hover:border-wwc-primary transition-all duration-200 group">
                            <i class='bx bx-user-plus text-lg mr-2 group-hover:text-wwc-primary transition-colors duration-200'></i>
                            Create New Account
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
