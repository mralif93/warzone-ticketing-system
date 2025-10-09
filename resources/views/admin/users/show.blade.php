@extends('layouts.admin')
@section('page-title', 'User Management')
@section('title', 'User Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('admin.users') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            User Details
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            View and manage user information
                        </p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit User
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- User Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="text-center">
                            <div class="mx-auto h-24 w-24 rounded-full bg-indigo-500 flex items-center justify-center mb-4">
                                <span class="text-2xl font-medium text-white">
                                    {{ substr($user->name, 0, 1) }}
                                </span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            
                            <!-- Role Badge -->
                            <div class="mt-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($user->role === 'Administrator') bg-red-100 text-red-800
                                    @elseif($user->role === 'Gate Staff') bg-blue-100 text-blue-800
                                    @elseif($user->role === 'Counter Staff') bg-green-100 text-green-800
                                    @elseif($user->role === 'Support Staff') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $user->role }}
                                </span>
                            </div>

                            <!-- Status -->
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($user->email_verified_at) bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    @if($user->email_verified_at) 
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"></circle>
                                        </svg>
                                        Active
                                    @else 
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"></circle>
                                        </svg>
                                        Inactive
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="mt-6 bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h4>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="text-sm text-gray-900">{{ $user->phone_number ?: 'Not provided' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Address</dt>
                                <dd class="text-sm text-gray-900">
                                    @if($user->getFullAddressAttribute())
                                        {{ $user->getFullAddressAttribute() }}
                                    @else
                                        Not provided
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- User Details -->
            <div class="lg:col-span-2">
                <!-- Account Information -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Account Information</h4>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">User ID</dt>
                                <dd class="text-sm text-gray-900">#{{ $user->id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                                <dd class="text-sm text-gray-900">
                                    @if($user->email_verified_at)
                                        {{ $user->email_verified_at->format('M d, Y g:i A') }}
                                    @else
                                        Not verified
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                                <dd class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="text-sm text-gray-900">{{ $user->updated_at->format('M d, Y g:i A') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- User Activity -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">User Activity</h4>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-indigo-600">{{ $user->orders()->count() }}</div>
                                <div class="text-sm text-gray-500">Total Orders</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $user->tickets()->count() }}</div>
                                <div class="text-sm text-gray-500">Tickets Purchased</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-yellow-600">${{ number_format($user->orders()->sum('total_amount'), 2) }}</div>
                                <div class="text-sm text-gray-500">Total Spent</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Recent Orders</h4>
                        @if($user->orders()->count() > 0)
                            <div class="space-y-3">
                                @foreach($user->orders()->latest()->take(5)->get() as $order)
                                <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-b-0">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Order #{{ $order->id }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y g:i A') }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900">${{ number_format($order->total_amount, 2) }}</div>
                                        <div class="text-sm text-gray-500">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($order->status === 'Completed') bg-green-100 text-green-800
                                                @elseif($order->status === 'Pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'Cancelled') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $order->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @if($user->orders()->count() > 5)
                                <div class="mt-4 text-center">
                                    <a href="{{ route('admin.orders') }}?user={{ $user->id }}" 
                                       class="text-sm text-indigo-600 hover:text-indigo-900">
                                        View all orders →
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-500">No orders found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
