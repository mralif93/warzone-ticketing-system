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
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg border border-wwc-neutral-200 p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
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

                <div>
                    <label for="password" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                        Password
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password"
                           class="w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent @error('password') border-wwc-error @enderror"
                           placeholder="Enter your password"
                           required>
                    @error('password')
                        <p class="mt-1 text-sm text-wwc-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="remember" 
                               name="remember" 
                               class="h-4 w-4 text-wwc-primary focus:ring-wwc-primary border-wwc-neutral-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-wwc-neutral-600">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="{{ route('forgot-password') }}" class="text-wwc-primary hover:text-wwc-primary-dark font-medium">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-wwc-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-wwc-primary-dark transition-colors duration-200 focus:ring-2 focus:ring-wwc-primary focus:ring-offset-2">
                    Sign In
                </button>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-wwc-neutral-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-wwc-neutral-500">Don't have an account?</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('register') }}" 
                       class="w-full flex justify-center py-3 px-4 border border-wwc-neutral-300 rounded-lg shadow-sm bg-white text-sm font-medium text-wwc-neutral-700 hover:bg-wwc-neutral-50 transition-colors duration-200">
                        Create New Account
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection