@extends('layouts.customer')

@section('title', 'Profile Settings')
@section('description', 'Manage your account information and preferences in your Warzone World Championship customer portal.')

@section('content')
<!-- Professional Profile Management -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Header Section -->
    <div class="bg-white border-b border-wwc-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display">Profile Settings</h1>
                    <p class="text-wwc-neutral-600 mt-1">Manage your account information and preferences</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @if (session('success'))
            <div class="mb-6 bg-wwc-success-light border border-wwc-success text-wwc-success px-4 py-3 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Row 1: Account Information | Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Account Information -->
            <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-200">
                    <h2 class="text-lg font-semibold text-wwc-neutral-900">Account Information</h2>
                    <p class="text-wwc-neutral-600 text-sm mt-1">Your account details and statistics</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-wwc-neutral-100">
                            <span class="text-sm font-semibold text-wwc-neutral-600">Member Since</span>
                            <span class="text-sm text-wwc-neutral-900">{{ $user->created_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-wwc-neutral-100">
                            <span class="text-sm font-semibold text-wwc-neutral-600">Last Updated</span>
                            <span class="text-sm text-wwc-neutral-900">{{ $user->updated_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-wwc-neutral-100">
                            <span class="text-sm font-semibold text-wwc-neutral-600">Total Orders</span>
                            <span class="text-sm text-wwc-neutral-900">{{ $user->orders()->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-wwc-neutral-100">
                            <span class="text-sm font-semibold text-wwc-neutral-600">Total Tickets</span>
                            <span class="text-sm text-wwc-neutral-900">{{ $user->purchaseTickets()->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3">
                            <span class="text-sm font-semibold text-wwc-neutral-600">Account Status</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-wwc-success text-white">
                                Active
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-200">
                    <h2 class="text-lg font-semibold text-wwc-neutral-900">Quick Actions</h2>
                    <p class="text-wwc-neutral-600 text-sm mt-1">Manage your account</p>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('customer.dashboard') }}" 
                           class="flex items-center p-3 text-sm text-wwc-neutral-700 hover:bg-wwc-neutral-50 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3 text-wwc-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                            View Dashboard
                        </a>
                        <a href="{{ route('customer.orders') }}" 
                           class="flex items-center p-3 text-sm text-wwc-neutral-700 hover:bg-wwc-neutral-50 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3 text-wwc-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Order History
                        </a>
                        <a href="{{ route('customer.support') }}" 
                           class="flex items-center p-3 text-sm text-wwc-neutral-700 hover:bg-wwc-neutral-50 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3 text-wwc-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Get Support
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Personal Information -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-200">
                    <h2 class="text-lg font-semibold text-wwc-neutral-900">Personal Information</h2>
                    <p class="text-wwc-neutral-600 text-sm mt-1">Update your personal details and contact information</p>
                </div>
                <div class="p-6">
                    <form action="{{ route('customer.profile.update') }}" method="POST">
                        @csrf
                        
                        @if ($errors->any())
                            <div class="mb-6 bg-wwc-error-light border border-wwc-error text-wwc-error px-4 py-3 rounded-xl">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h3 class="font-semibold">Please correct the following errors:</h3>
                                        <ul class="list-disc list-inside text-sm mt-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Full Name *
                                </label>
                                <input type="text" name="name" id="name" required
                                       value="{{ old('name', $user->name) }}"
                                       class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('name') border-wwc-error @enderror">
                                @error('name')
                                    <div class="text-wwc-error text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Email Address *
                                </label>
                                <input type="email" name="email" id="email" required
                                       value="{{ old('email', $user->email) }}"
                                       class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('email') border-wwc-error @enderror">
                                @error('email')
                                    <div class="text-wwc-error text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label for="phone_number" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Phone Number
                                </label>
                                <input type="tel" name="phone_number" id="phone_number"
                                       value="{{ old('phone_number', $user->phone_number) }}"
                                       class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('phone_number') border-wwc-error @enderror">
                                @error('phone_number')
                                    <div class="text-wwc-error text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div>
                                <label for="address" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Address
                                </label>
                                <input type="text" name="address" id="address"
                                       value="{{ old('address', $user->address) }}"
                                       class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('address') border-wwc-error @enderror">
                                @error('address')
                                    <div class="text-wwc-error text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- City -->
                            <div>
                                <label for="city" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    City
                                </label>
                                <input type="text" name="city" id="city"
                                       value="{{ old('city', $user->city) }}"
                                       class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('city') border-wwc-error @enderror">
                                @error('city')
                                    <div class="text-wwc-error text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- State -->
                            <div>
                                <label for="state" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    State
                                </label>
                                <input type="text" name="state" id="state"
                                       value="{{ old('state', $user->state) }}"
                                       class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('state') border-wwc-error @enderror">
                                @error('state')
                                    <div class="text-wwc-error text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Postal Code -->
                            <div>
                                <label for="postal_code" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Postal Code
                                </label>
                                <input type="text" name="postal_code" id="postal_code"
                                       value="{{ old('postal_code', $user->postal_code) }}"
                                       class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('postal_code') border-wwc-error @enderror">
                                @error('postal_code')
                                    <div class="text-wwc-error text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="country" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Country
                                </label>
                                <input type="text" name="country" id="country"
                                       value="{{ old('country', $user->country) }}"
                                       class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('country') border-wwc-error @enderror">
                                @error('country')
                                    <div class="text-wwc-error text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 pt-6 border-t border-wwc-neutral-200">
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Row 3: Change Password -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-200">
                    <h2 class="text-lg font-semibold text-wwc-neutral-900">Change Password</h2>
                    <p class="text-wwc-neutral-600 text-sm mt-1">Update your account password</p>
                </div>
                <div class="p-6">
                    <form action="{{ route('customer.password.update') }}" method="POST">
                        @csrf
                        
                        @if ($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
                            <div class="mb-6 bg-wwc-error-light border border-wwc-error text-wwc-error px-4 py-3 rounded-xl">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h3 class="font-semibold">Please correct the following errors:</h3>
                                        <ul class="list-disc list-inside text-sm mt-1">
                                            @if ($errors->has('current_password'))
                                                <li>{{ $errors->first('current_password') }}</li>
                                            @endif
                                            @if ($errors->has('password'))
                                                <li>{{ $errors->first('password') }}</li>
                                            @endif
                                            @if ($errors->has('password_confirmation'))
                                                <li>{{ $errors->first('password_confirmation') }}</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Current Password -->
                            <div>
                                <label for="current_password" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Current Password *
                                </label>
                                <input type="password" name="current_password" id="current_password" required
                                       class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('current_password') border-wwc-error @enderror">
                                @error('current_password')
                                    <div class="text-wwc-error text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    New Password *
                                </label>
                                <input type="password" name="password" id="password" required
                                       class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('password') border-wwc-error @enderror">
                                @error('password')
                                    <div class="text-wwc-error text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Confirm New Password *
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                       class="block w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('password_confirmation') border-wwc-error @enderror">
                                @error('password_confirmation')
                                    <div class="text-wwc-error text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 pt-6 border-t border-wwc-neutral-200">
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-accent hover:bg-wwc-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-accent transition-colors duration-200">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection