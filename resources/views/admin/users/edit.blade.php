@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<!-- Professional User Editing with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <a href="{{ route('admin.users.show', $user) }}" 
                    class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                    Back to User
                </a>
            </div>

            <!-- Form Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Edit User Details</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-edit text-sm'></i>
                            <span>Update user information</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        @if ($errors->any())
                            <div class="bg-wwc-error-light border border-wwc-error text-wwc-error px-4 py-3 rounded-lg mb-6">
                                <div class="flex items-start">
                                    <i class='bx bx-error text-lg mr-3 mt-0.5 flex-shrink-0'></i>
                                    <div>
                                        <h3 class="font-semibold mb-2 text-sm">Please correct the following errors:</h3>
                                        <ul class="list-disc list-inside space-y-1 text-sm">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- Full Name -->
                                <div class="sm:col-span-2">
                                    <label for="name" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Full Name <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" required
                                           value="{{ old('name', $user->name) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('name') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter full name">
                                    @error('name')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email Address -->
                                <div class="sm:col-span-2">
                                    <label for="email" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Email Address <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="email" name="email" id="email" required
                                           value="{{ old('email', $user->email) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('email') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter email address">
                                    @error('email')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Role -->
                                <div>
                                    <label for="role" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Role <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="role" id="role" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('role') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror">
                                        <option value="">Select a role</option>
                                        <option value="administrator" {{ old('role', $user->role) == 'administrator' ? 'selected' : '' }}>Administrator</option>
                                        <option value="gate_staff" {{ old('role', $user->role) == 'gate_staff' ? 'selected' : '' }}>Gate Staff</option>
                                        <option value="counter_staff" {{ old('role', $user->role) == 'counter_staff' ? 'selected' : '' }}>Counter Staff</option>
                                        <option value="support_staff" {{ old('role', $user->role) == 'support_staff' ? 'selected' : '' }}>Support Staff</option>
                                        <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Customer</option>
                                    </select>
                                    @error('role')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone Number -->
                                <div>
                                    <label for="phone_number" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Phone Number
                                    </label>
                                    <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('phone_number') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter phone number">
                                    @error('phone_number')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Address Line 1 -->
                                <div class="sm:col-span-2">
                                    <label for="address_line_1" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Address Line 1
                                    </label>
                                    <input type="text" name="address_line_1" id="address_line_1" value="{{ old('address_line_1', $user->address_line_1) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('address_line_1') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter address line 1">
                                    @error('address_line_1')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- City -->
                                <div>
                                    <label for="city" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        City
                                    </label>
                                    <input type="text" name="city" id="city" value="{{ old('city', $user->city) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('city') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter city">
                                    @error('city')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- State -->
                                <div>
                                    <label for="state" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        State
                                    </label>
                                    <input type="text" name="state" id="state" value="{{ old('state', $user->state) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('state') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter state">
                                    @error('state')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Postcode -->
                                <div>
                                    <label for="postcode" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Postcode
                                    </label>
                                    <input type="text" name="postcode" id="postcode" value="{{ old('postcode', $user->postcode) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('postcode') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter postcode">
                                    @error('postcode')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Country -->
                                <div>
                                    <label for="country" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Country
                                    </label>
                                    <input type="text" name="country" id="country" value="{{ old('country', $user->country) }}"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('country') border-wwc-error focus:ring-wwc-error focus:border-wwc-error @enderror"
                                           placeholder="Enter country">
                                    @error('country')
                                        <div class="text-wwc-error text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-wwc-neutral-200 mt-6">
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-check text-sm mr-2'></i>
                                Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
