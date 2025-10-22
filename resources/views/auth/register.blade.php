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
<div class="py-20 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Professional Registration Card -->
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark px-8 py-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class='bx bx-user-plus text-white text-xl'></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Create Account</h2>
                        <p class="text-wwc-primary-light text-sm">Join the Warzone community</p>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="px-8 py-8">
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
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

                    <!-- First Name Field -->
                    <div class="space-y-2">
                        <label for="first_name" class="block text-sm font-semibold text-gray-800">
                            First Name
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class='bx bx-user text-gray-400'></i>
                            </div>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   value="{{ old('first_name') }}"
                                   class="block w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary transition-all duration-200 bg-gray-50 focus:bg-white focus:caret-wwc-primary @error('first_name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="Enter your first name"
                                   required 
                                   autofocus>
                        </div>
                        @error('first_name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class='bx bx-error text-red-500 mr-1'></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Last Name Field -->
                    <div class="space-y-2">
                        <label for="last_name" class="block text-sm font-semibold text-gray-800">
                            Last Name
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class='bx bx-user text-gray-400'></i>
                            </div>
                            <input type="text" 
                                   id="last_name" 
                                   name="last_name" 
                                   value="{{ old('last_name') }}"
                                   class="block w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary transition-all duration-200 bg-gray-50 focus:bg-white focus:caret-wwc-primary @error('last_name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="Enter your last name"
                                   required>
                        </div>
                        @error('last_name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class='bx bx-error text-red-500 mr-1'></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

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
                                   required>
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class='bx bx-error text-red-500 mr-1'></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Phone Field -->
                    <div class="space-y-2">
                        <label for="phone" class="block text-sm font-semibold text-gray-800">
                            Phone Number
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class='bx bx-phone text-gray-400'></i>
                            </div>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   class="block w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary transition-all duration-200 bg-gray-50 focus:bg-white focus:caret-wwc-primary @error('phone') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="Enter your phone number"
                                   required>
                        </div>
                        @error('phone')
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
                                   class="block w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary transition-all duration-200 bg-gray-50 focus:bg-white focus:caret-wwc-primary @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="Create a strong password"
                                   required>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class='bx bx-error text-red-500 mr-1'></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-800">
                            Confirm Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class='bx bx-lock text-gray-400'></i>
                            </div>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation"
                                   class="block w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary transition-all duration-200 bg-gray-50 focus:bg-white focus:caret-wwc-primary"
                                   placeholder="Confirm your password"
                                   required>
                        </div>
                    </div>

                    <!-- Password Requirements -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class='bx bx-info-circle text-blue-500 text-lg mr-2 mt-0.5'></i>
                            <div class="text-sm text-blue-700">
                                <p class="font-medium mb-1">Password Requirements:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>At least 8 characters long</li>
                                    <li>Contains uppercase and lowercase letters</li>
                                    <li>Includes numbers and special characters</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="terms" 
                               name="terms" 
                               class="h-5 w-5 text-wwc-primary bg-white border-2 border-gray-300 rounded-md focus:ring-wwc-primary focus:ring-2 focus:ring-offset-0">
                        <label for="terms" class="ml-3 text-sm text-gray-700">
                            I agree to the <a href="{{ route('public.terms-of-service') }}" class="text-wwc-primary hover:text-wwc-primary-dark font-semibold">Terms of Service</a> and <a href="{{ route('public.refund-policy') }}" class="text-wwc-primary hover:text-wwc-primary-dark font-semibold">Refund Policy</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-wwc-primary to-wwc-primary-dark text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:ring-4 focus:ring-wwc-primary/30 focus:outline-none">
                            <div class="flex items-center justify-center space-x-2">
                                <i class='bx bx-user-plus text-xl'></i>
                                <span>Create Account</span>
                            </div>
                        </button>
                    </div>
                </form>

                <!-- Login Link -->
                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500 font-medium">Already have an account?</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('login') }}" 
                           class="w-full flex items-center justify-center py-4 px-6 border-2 border-gray-200 rounded-xl shadow-sm bg-white text-gray-700 font-semibold hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 group">
                            <i class='bx bx-log-in text-lg mr-2 group-hover:text-wwc-primary transition-colors duration-200'></i>
                            Sign In Instead
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection