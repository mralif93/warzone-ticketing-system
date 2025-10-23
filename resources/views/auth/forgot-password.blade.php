@extends('layouts.public')

@section('title', 'Forgot Password')
@section('description', 'Reset your Warzone World Championship account password to regain access to your account.')

@section('content')
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
<div class="py-16 bg-white">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Forgot Password Form Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-wwc-neutral-200 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-wwc-primary px-8 py-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class='bx bx-key text-white text-xl'></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white font-display">Reset Password</h2>
                        <p class="text-wwc-primary-light text-sm">We'll send you a secure reset link</p>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="px-8 py-8">
                <form method="POST" action="{{ route('forgot-password') }}" class="space-y-6">
                    @csrf
                    
                    @if (session('status'))
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class='bx bx-check-circle text-green-400 text-lg'></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800 mb-1">
                                        Reset Link Sent!
                                    </h3>
                                    <p class="text-sm text-green-700">{{ session('status') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

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

                    <!-- Information Card -->
                    <div class="bg-wwc-primary-light border border-wwc-primary rounded-lg p-4">
                        <div class="flex items-start">
                            <i class='bx bx-info-circle text-wwc-primary text-lg mr-3 mt-0.5'></i>
                            <div class="text-sm text-wwc-neutral-700">
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
                                class="w-full bg-wwc-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-wwc-primary-dark transition-colors duration-200 focus:ring-2 focus:ring-wwc-primary focus:ring-offset-2">
                            <div class="flex items-center justify-center space-x-2">
                                <i class='bx bx-send text-lg'></i>
                                <span>Send Reset Link</span>
                            </div>
                        </button>
                    </div>
                </form>

                <!-- Login Link -->
                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-wwc-neutral-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-wwc-neutral-500 font-medium">Remember your password?</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('login') }}" 
                           class="w-full flex items-center justify-center py-3 px-6 border-2 border-wwc-neutral-300 rounded-lg bg-white text-wwc-neutral-700 font-semibold hover:bg-wwc-neutral-50 hover:border-wwc-primary transition-all duration-200 group">
                            <i class='bx bx-log-in text-lg mr-2 group-hover:text-wwc-primary transition-colors duration-200'></i>
                            Back to Sign In
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection