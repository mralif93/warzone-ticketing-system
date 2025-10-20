@extends('layouts.admin')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
<div class="min-h-screen bg-wwc-neutral-50">
    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <div class="flex items-center">
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Users
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- User Details -->
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">User Details</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-info-circle text-sm'></i>
                                    <span>User information</span>
        </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Full Name -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-red-100 flex items-center justify-center">
                                            <i class='bx bx-user text-sm text-red-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Full Name</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $user->name }}</span>
                                    </div>
                                </div>

                                <!-- Email Address -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-envelope text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Email Address</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $user->email }}</span>
                                    </div>
                                </div>

                                <!-- Role -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                            <i class='bx bx-shield text-sm text-orange-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Role</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ ucwords(str_replace('_', ' ', $user->role)) }}</span>
                                    </div>
                            </div>

                            <!-- Status -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-check-circle text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Status</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                                    </div>
                                </div>

                                <!-- Phone Number -->
                                @if($user->phone_number)
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-phone text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Phone Number</span>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $user->phone_number }}</span>
                                    </div>
                                </div>
                                @endif

                                <!-- Address -->
                                @if($user->address_line_1)
                                <div class="flex items-center py-3">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <i class='bx bx-map text-sm text-gray-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-wwc-neutral-600">Address</span>
                                        <span class="text-base font-medium text-wwc-neutral-900 leading-relaxed text-right max-w-md">
                                            {{ $user->address_line_1 }}
                                            @if($user->city)
                                                <br>{{ $user->city }}, {{ $user->state }} {{ $user->postcode }}
                                            @endif
                                            @if($user->country)
                                                <br>{{ $user->country }}
                                    @endif
                                </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Statistics -->
                <div class="xl:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">User Statistics</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-bar-chart text-sm'></i>
                                    <span>Activity overview</span>
                            </div>
                            </div>
                    </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <!-- Orders Count -->
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-wwc-neutral-900 font-display">{{ $user->orders_count ?? 0 }}</div>
                                    <div class="text-sm text-wwc-neutral-600 font-medium">Total Orders</div>
            </div>

                                <!-- Tickets Count -->
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-wwc-neutral-900 font-display">{{ $user->customer_tickets_count ?? 0 }}</div>
                                    <div class="text-sm text-wwc-neutral-600 font-medium">Tickets Purchased</div>
                </div>

                                <!-- Member Since -->
                            <div class="text-center">
                                    <div class="text-3xl font-bold text-wwc-neutral-900 font-display">{{ $user->created_at->format('M Y') }}</div>
                                    <div class="text-sm text-wwc-neutral-600 font-medium">Member Since</div>
                            </div>
                        </div>
                    </div>
                </div>

                    <!-- Quick Actions -->
                    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Quick Actions</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-cog text-sm'></i>
                                    <span>User actions</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="w-full bg-wwc-primary hover:bg-wwc-primary-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                    <i class='bx bx-edit text-sm mr-2'></i>
                                    Edit User
                                </a>
                                
                                @if($user->is_active)
                                    <form action="{{ route('admin.users.update-status', $user) }}" method="POST" class="block">
                                        @csrf
                                        <input type="hidden" name="is_active" value="0">
                                        <button type="submit" 
                                                class="w-full bg-wwc-warning hover:bg-wwc-warning-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                            <i class='bx bx-user-x text-sm mr-2'></i>
                                            Deactivate User
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.update-status', $user) }}" method="POST" class="block">
                                        @csrf
                                        <input type="hidden" name="is_active" value="1">
                                        <button type="submit" 
                                                class="w-full bg-wwc-success hover:bg-wwc-success-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                            <i class='bx bx-user-check text-sm mr-2'></i>
                                            Activate User
                                        </button>
                                    </form>
                            @endif

                                <a href="{{ route('admin.users.update-password', $user) }}" 
                                   class="w-full bg-wwc-neutral-600 hover:bg-wwc-neutral-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200 text-center block text-sm">
                                    <i class='bx bx-key text-sm mr-2'></i>
                                    Reset Password
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection