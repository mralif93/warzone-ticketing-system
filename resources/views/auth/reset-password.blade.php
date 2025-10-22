@extends('layouts.public')

@section('title', 'Reset Password')
@section('description', 'Reset your Warzone World Championship account password with a secure new password.')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white font-display mb-6">
                Reset Password
            </h1>
            <p class="text-xl text-wwc-primary-light max-w-3xl mx-auto">
                Enter your new password below to complete the reset process.
            </p>
        </div>
    </div>
</div>

<!-- Reset Password Form Section -->
<div class="py-16 bg-white">
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg border border-wwc-neutral-200 p-8">
            <form method="POST" action="{{ route('password.reset') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                @if ($errors->any())
                    <div class="bg-wwc-error-light border border-wwc-error text-wwc-error px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label for="email" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                        Email Address
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ $email ?? old('email') }}"
                           class="w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent @error('email') border-wwc-error @enderror"
                           placeholder="Enter your email address"
                           required 
                           autofocus>
                    @error('email')
                        <p class="mt-1 text-sm text-wwc-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                        New Password
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password"
                           class="w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent @error('password') border-wwc-error @enderror"
                           placeholder="Enter your new password"
                           required>
                    @error('password')
                        <p class="mt-1 text-sm text-wwc-error">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-wwc-neutral-500">
                        Password must be at least 8 characters with uppercase, lowercase, number, and special character.
                    </p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                        Confirm New Password
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation"
                           class="w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent"
                           placeholder="Confirm your new password"
                           required>
                </div>

                <div class="bg-wwc-neutral-50 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-wwc-primary" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-wwc-neutral-800">
                                Password Requirements
                            </h3>
                            <div class="mt-2 text-sm text-wwc-neutral-600">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>At least 8 characters long</li>
                                    <li>Contains uppercase and lowercase letters</li>
                                    <li>Contains at least one number</li>
                                    <li>Contains at least one special character</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-wwc-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-wwc-primary-dark transition-colors duration-200 focus:ring-2 focus:ring-wwc-primary focus:ring-offset-2">
                    Reset Password
                </button>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-wwc-neutral-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-wwc-neutral-500">Remember your password?</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('login') }}" 
                       class="w-full flex justify-center py-3 px-4 border border-wwc-neutral-300 rounded-lg shadow-sm bg-white text-sm font-medium text-wwc-neutral-700 hover:bg-wwc-neutral-50 transition-colors duration-200">
                        Back to Sign In
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection