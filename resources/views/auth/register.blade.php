@extends('layouts.public')

@section('title', 'Create Account')
@section('description', 'Create your Warzone World Championship account to purchase tickets and manage your profile.')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white font-display mb-6">
                Create Account
            </h1>
            <p class="text-xl text-wwc-primary-light max-w-3xl mx-auto">
                Join the Warzone World Championship community and get access to exclusive events and tickets.
            </p>
        </div>
    </div>
</div>

<!-- Registration Form Section -->
<div class="py-16 bg-white">
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg border border-wwc-neutral-200 p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                            First Name
                        </label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               value="{{ old('first_name') }}"
                               class="w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent @error('first_name') border-wwc-error @enderror"
                               placeholder="First name"
                               required 
                               autofocus>
                        @error('first_name')
                            <p class="mt-1 text-sm text-wwc-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                            Last Name
                        </label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               value="{{ old('last_name') }}"
                               class="w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent @error('last_name') border-wwc-error @enderror"
                               placeholder="Last name"
                               required>
                        @error('last_name')
                            <p class="mt-1 text-sm text-wwc-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

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
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-wwc-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                        Phone Number
                    </label>
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone') }}"
                           class="w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent @error('phone') border-wwc-error @enderror"
                           placeholder="Enter your phone number"
                           required>
                    @error('phone')
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
                           placeholder="Create a strong password"
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
                        Confirm Password
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation"
                           class="w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent"
                           placeholder="Confirm your password"
                           required>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" 
                           id="terms" 
                           name="terms" 
                           class="h-4 w-4 text-wwc-primary focus:ring-wwc-primary border-wwc-neutral-300 rounded"
                           required>
                    <label for="terms" class="ml-2 block text-sm text-wwc-neutral-600">
                        I agree to the <a href="{{ route('public.terms-of-service') }}" class="text-wwc-primary hover:text-wwc-primary-dark font-medium">Terms of Service</a> and <a href="{{ route('public.refund-policy') }}" class="text-wwc-primary hover:text-wwc-primary-dark font-medium">Refund Policy</a>
                    </label>
                </div>

                <button type="submit" 
                        class="w-full bg-wwc-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-wwc-primary-dark transition-colors duration-200 focus:ring-2 focus:ring-wwc-primary focus:ring-offset-2">
                    Create Account
                </button>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-wwc-neutral-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-wwc-neutral-500">Already have an account?</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('login') }}" 
                       class="w-full flex justify-center py-3 px-4 border border-wwc-neutral-300 rounded-lg shadow-sm bg-white text-sm font-medium text-wwc-neutral-700 hover:bg-wwc-neutral-50 transition-colors duration-200">
                        Sign In Instead
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection