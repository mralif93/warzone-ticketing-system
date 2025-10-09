@extends('layouts.admin')
@section('page-title', 'Settings')
@section('title', 'System Settings')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                System Settings
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Configure system parameters and preferences
            </p>
        </div>

        <!-- Settings Form -->
        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                <div class="px-4 py-5 sm:p-6">
                    <!-- Ticket Settings -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Ticket Settings</h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="max_tickets_per_order" class="block text-sm font-medium text-gray-700">
                                    Maximum Tickets per Order
                                </label>
                                <input type="number" name="max_tickets_per_order" id="max_tickets_per_order" 
                                       value="{{ old('max_tickets_per_order', $settings['max_tickets_per_order']) }}" 
                                       min="1" max="20" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('max_tickets_per_order') border-red-300 @enderror">
                                <p class="mt-2 text-sm text-gray-500">Maximum number of tickets a customer can purchase in a single order</p>
                                @error('max_tickets_per_order')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="seat_hold_duration_minutes" class="block text-sm font-medium text-gray-700">
                                    Seat Hold Duration (Minutes)
                                </label>
                                <input type="number" name="seat_hold_duration_minutes" id="seat_hold_duration_minutes" 
                                       value="{{ old('seat_hold_duration_minutes', $settings['seat_hold_duration_minutes']) }}" 
                                       min="1" max="30" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('seat_hold_duration_minutes') border-red-300 @enderror">
                                <p class="mt-2 text-sm text-gray-500">How long to hold seats during the purchase process</p>
                                @error('seat_hold_duration_minutes')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- System Settings -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">System Settings</h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="maintenance_mode" class="block text-sm font-medium text-gray-700">
                                    Maintenance Mode
                                </label>
                                <select name="maintenance_mode" id="maintenance_mode" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="0" {{ old('maintenance_mode', '0') == '0' ? 'selected' : '' }}>Disabled</option>
                                    <option value="1" {{ old('maintenance_mode', '0') == '1' ? 'selected' : '' }}>Enabled</option>
                                </select>
                                <p class="mt-2 text-sm text-gray-500">Enable maintenance mode to temporarily disable the system</p>
                            </div>

                            <div>
                                <label for="auto_release_holds" class="block text-sm font-medium text-gray-700">
                                    Auto Release Holds
                                </label>
                                <select name="auto_release_holds" id="auto_release_holds" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="1" {{ old('auto_release_holds', '1') == '1' ? 'selected' : '' }}>Enabled</option>
                                    <option value="0" {{ old('auto_release_holds', '1') == '0' ? 'selected' : '' }}>Disabled</option>
                                </select>
                                <p class="mt-2 text-sm text-gray-500">Automatically release held seats when they expire</p>
                            </div>
                        </div>
                    </div>

                    <!-- Email Settings -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Email Settings</h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="email_notifications" class="block text-sm font-medium text-gray-700">
                                    Email Notifications
                                </label>
                                <select name="email_notifications" id="email_notifications" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="1" {{ old('email_notifications', '1') == '1' ? 'selected' : '' }}>Enabled</option>
                                    <option value="0" {{ old('email_notifications', '1') == '0' ? 'selected' : '' }}>Disabled</option>
                                </select>
                                <p class="mt-2 text-sm text-gray-500">Send email notifications for orders and tickets</p>
                            </div>

                            <div>
                                <label for="admin_email" class="block text-sm font-medium text-gray-700">
                                    Admin Email
                                </label>
                                <input type="email" name="admin_email" id="admin_email" 
                                       value="{{ old('admin_email', 'admin@warzone.com') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('admin_email') border-red-300 @enderror">
                                <p class="mt-2 text-sm text-gray-500">Email address for system notifications</p>
                                @error('admin_email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Security Settings -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Security Settings</h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="session_timeout" class="block text-sm font-medium text-gray-700">
                                    Session Timeout (Minutes)
                                </label>
                                <input type="number" name="session_timeout" id="session_timeout" 
                                       value="{{ old('session_timeout', '120') }}" 
                                       min="15" max="480" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('session_timeout') border-red-300 @enderror">
                                <p class="mt-2 text-sm text-gray-500">How long before user sessions expire</p>
                                @error('session_timeout')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="max_login_attempts" class="block text-sm font-medium text-gray-700">
                                    Max Login Attempts
                                </label>
                                <input type="number" name="max_login_attempts" id="max_login_attempts" 
                                       value="{{ old('max_login_attempts', '5') }}" 
                                       min="3" max="10" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('max_login_attempts') border-red-300 @enderror">
                                <p class="mt-2 text-sm text-gray-500">Maximum failed login attempts before account lockout</p>
                                @error('max_login_attempts')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 sm:rounded-b-lg">
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Reset to Defaults
                        </button>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- System Information -->
        <div class="mt-8 bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">System Information</h3>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Laravel Version</dt>
                        <dd class="text-sm text-gray-900">{{ app()->version() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">PHP Version</dt>
                        <dd class="text-sm text-gray-900">{{ PHP_VERSION }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Database Driver</dt>
                        <dd class="text-sm text-gray-900">{{ config('database.default') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Environment</dt>
                        <dd class="text-sm text-gray-900">{{ app()->environment() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Application URL</dt>
                        <dd class="text-sm text-gray-900">{{ config('app.url') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Debug Mode</dt>
                        <dd class="text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ config('app.debug') ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
