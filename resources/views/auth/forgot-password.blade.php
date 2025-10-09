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
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg border border-wwc-neutral-200 p-8">
            <form method="POST" action="{{ route('forgot-password') }}" class="space-y-6">
                @csrf
                
                @if (session('status'))
                    <div class="bg-wwc-success-light border border-wwc-success text-wwc-success px-4 py-3 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-wwc-success" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">{{ session('status') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

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
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent @error('email') border-wwc-error @enderror"
                           placeholder="Enter your email address"
                           required 
                           autofocus>
                    @error('email')
                        <p class="mt-1 text-sm text-wwc-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-wwc-neutral-50 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-wwc-primary" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-wwc-neutral-800">
                                What happens next?
                            </h3>
                            <div class="mt-2 text-sm text-wwc-neutral-600">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>We'll send a secure reset link to your email</li>
                                    <li>Click the link to create a new password</li>
                                    <li>Sign in with your new password</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-wwc-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-wwc-primary-dark transition-colors duration-200 focus:ring-2 focus:ring-wwc-primary focus:ring-offset-2">
                    Send Reset Link
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