@extends('layouts.admin')

@section('title', 'System Settings')
@section('page-title', 'Settings')

@section('content')
<!-- Professional Settings Page with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Statistics Cards with WWC Brand Colors -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <!-- System Status -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'Offline' : 'Online' }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">System Status</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'text-wwc-warning' : 'text-wwc-success' }} font-semibold">
                                    <i class='bx {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'bx-error' : 'bx-check-circle' }} text-xs mr-1'></i>
                                    {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'Maintenance Mode' : 'Operational' }}
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'bg-wwc-warning-light' : 'bg-wwc-success-light' }} flex items-center justify-center">
                            <i class='bx {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'bx-error' : 'bx-check-circle' }} text-2xl {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'text-wwc-warning' : 'text-wwc-success' }}'></i>
                        </div>
                    </div>
                </div>

                <!-- Max Tickets -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $settings['max_tickets_per_order'] ?? '10' }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Max Tickets/Order</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-info font-semibold">
                                    <i class='bx bx-receipt text-xs mr-1'></i>
                                    Per Customer
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-info-light flex items-center justify-center">
                            <i class='bx bx-receipt text-2xl text-wwc-info'></i>
                        </div>
                    </div>
                </div>

                <!-- Seat Hold Duration -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $settings['seat_hold_duration_minutes'] ?? '15' }}m</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Seat Hold Time</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-warning font-semibold">
                                    <i class='bx bx-time text-xs mr-1'></i>
                                    Auto Release
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-warning-light flex items-center justify-center">
                            <i class='bx bx-time text-2xl text-wwc-warning'></i>
                        </div>
                    </div>
                </div>

                <!-- Session Timeout -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $settings['session_timeout'] ?? '60' }}m</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Session Timeout</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-accent font-semibold">
                                    <i class='bx bx-lock text-xs mr-1'></i>
                                    Security
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-accent-light flex items-center justify-center">
                            <i class='bx bx-lock text-2xl text-wwc-accent'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Statistics Row -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-2 mb-6">
                <!-- Service Fee -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $settings['service_fee_percentage'] ?? '5.0' }}%</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Service Fee</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-info font-semibold">
                                    <i class='bx bx-percentage text-xs mr-1'></i>
                                    Per Order
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-info-light flex items-center justify-center">
                            <i class='bx bx-percentage text-2xl text-wwc-info'></i>
                        </div>
                    </div>
                </div>

                <!-- Tax Rate -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $settings['tax_percentage'] ?? '6.0' }}%</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Tax Rate</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-warning font-semibold">
                                    <i class='bx bx-receipt text-xs mr-1'></i>
                                    Government
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-warning-light flex items-center justify-center">
                            <i class='bx bx-receipt text-2xl text-wwc-warning'></i>
                        </div>
                    </div>
                </div>
        </div>

        <!-- Settings Form -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">System Configuration</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-cog text-sm'></i>
                            <span>Configure system parameters</span>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.settings.update') }}" class="p-6">
                @csrf
                    <!-- Ticket Settings -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-wwc-neutral-900 mb-4 flex items-center">
                            <i class='bx bx-receipt text-sm mr-2 text-wwc-primary'></i>
                            Ticket Settings
                        </h3>
                        <p class="text-sm text-wwc-neutral-500 mb-4">Configure the settings for the ticket system</p>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                            <div>
                                <label for="max_tickets_per_order" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Maximum Tickets per Order
                                </label>
                                <input type="number" name="max_tickets_per_order" id="max_tickets_per_order" 
                                       value="{{ old('max_tickets_per_order', $settings['max_tickets_per_order'] ?? '10') }}" 
                                       min="1" max="20" required
                                       class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('max_tickets_per_order') border-wwc-error @enderror">
                                <p class="mt-2 text-sm text-wwc-neutral-500">Maximum number of tickets a customer can purchase in a single order</p>
                                @error('max_tickets_per_order')
                                    <p class="mt-2 text-sm text-wwc-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="seat_hold_duration_minutes" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Seat Hold Duration (Minutes)
                                </label>
                                <input type="number" name="seat_hold_duration_minutes" id="seat_hold_duration_minutes" 
                                       value="{{ old('seat_hold_duration_minutes', $settings['seat_hold_duration_minutes'] ?? '15') }}" 
                                       min="1" max="30" required
                                       class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('seat_hold_duration_minutes') border-wwc-error @enderror">
                                <p class="mt-2 text-sm text-wwc-neutral-500">How long to hold seats during the purchase process</p>
                                @error('seat_hold_duration_minutes')
                                    <p class="mt-2 text-sm text-wwc-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Additional Ticket Settings Row -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="service_fee_percentage" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Service Fee Percentage
                                </label>
                                <input type="number" name="service_fee_percentage" id="service_fee_percentage" 
                                       value="{{ old('service_fee_percentage', $settings['service_fee_percentage'] ?? '5.0') }}" 
                                       min="0" max="100" step="0.1" required
                                       class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('service_fee_percentage') border-wwc-error @enderror">
                                <p class="mt-2 text-sm text-wwc-neutral-500">Service fee percentage applied to each order (0-100%)</p>
                                @error('service_fee_percentage')
                                    <p class="mt-2 text-sm text-wwc-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tax_percentage" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Tax Percentage
                                </label>
                                <input type="number" name="tax_percentage" id="tax_percentage" 
                                       value="{{ old('tax_percentage', $settings['tax_percentage'] ?? '6.0') }}" 
                                       min="0" max="100" step="0.1" required
                                       class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('tax_percentage') border-wwc-error @enderror">
                                <p class="mt-2 text-sm text-wwc-neutral-500">Tax percentage applied to each order (0-100%)</p>
                                @error('tax_percentage')
                                    <p class="mt-2 text-sm text-wwc-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- System Settings -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-wwc-neutral-900 mb-4 flex items-center">
                            <i class='bx bx-cog text-sm mr-2 text-wwc-primary'></i>
                            System Settings
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="maintenance_mode" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Maintenance Mode
                                </label>
                                <select name="maintenance_mode" id="maintenance_mode" 
                                        class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                    <option value="0" {{ old('maintenance_mode', $settings['maintenance_mode'] ?? '0') == '0' ? 'selected' : '' }}>Disabled</option>
                                    <option value="1" {{ old('maintenance_mode', $settings['maintenance_mode'] ?? '0') == '1' ? 'selected' : '' }}>Enabled</option>
                                </select>
                                <p class="mt-2 text-sm text-wwc-neutral-500">Enable maintenance mode to temporarily disable the system</p>
                            </div>

                            <div>
                                <label for="auto_release_holds" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Auto Release Holds
                                </label>
                                <select name="auto_release_holds" id="auto_release_holds" 
                                        class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                    <option value="1" {{ old('auto_release_holds', $settings['auto_release_holds'] ?? '1') == '1' ? 'selected' : '' }}>Enabled</option>
                                    <option value="0" {{ old('auto_release_holds', $settings['auto_release_holds'] ?? '1') == '0' ? 'selected' : '' }}>Disabled</option>
                                </select>
                                <p class="mt-2 text-sm text-wwc-neutral-500">Automatically release held seats when they expire</p>
                            </div>
                        </div>
                    </div>

                    <!-- Email Settings -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-wwc-neutral-900 mb-4 flex items-center">
                            <i class='bx bx-envelope text-sm mr-2 text-wwc-primary'></i>
                            Email Settings
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="email_notifications" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Email Notifications
                                </label>
                                <select name="email_notifications" id="email_notifications" 
                                        class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                    <option value="1" {{ old('email_notifications', $settings['email_notifications'] ?? '1') == '1' ? 'selected' : '' }}>Enabled</option>
                                    <option value="0" {{ old('email_notifications', $settings['email_notifications'] ?? '1') == '0' ? 'selected' : '' }}>Disabled</option>
                                </select>
                                <p class="mt-2 text-sm text-wwc-neutral-500">Send email notifications for orders and tickets</p>
                            </div>

                            <div>
                                <label for="admin_email" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Admin Email
                                </label>
                                <input type="email" name="admin_email" id="admin_email" 
                                       value="{{ old('admin_email', $settings['admin_email'] ?? '') }}" 
                                       class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('admin_email') border-wwc-error @enderror">
                                <p class="mt-2 text-sm text-wwc-neutral-500">Email address for system notifications</p>
                                @error('admin_email')
                                    <p class="mt-2 text-sm text-wwc-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Security Settings -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-wwc-neutral-900 mb-4 flex items-center">
                            <i class='bx bx-shield text-sm mr-2 text-wwc-primary'></i>
                            Security Settings
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="session_timeout" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Session Timeout (Minutes)
                                </label>
                                <input type="number" name="session_timeout" id="session_timeout" 
                                       value="{{ old('session_timeout', $settings['session_timeout'] ?? '60') }}" 
                                       min="15" max="480" required
                                       class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('session_timeout') border-wwc-error @enderror">
                                <p class="mt-2 text-sm text-wwc-neutral-500">How long before user sessions expire</p>
                                @error('session_timeout')
                                    <p class="mt-2 text-sm text-wwc-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="max_login_attempts" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                    Max Login Attempts
                                </label>
                                <input type="number" name="max_login_attempts" id="max_login_attempts" 
                                       value="{{ old('max_login_attempts', $settings['max_login_attempts'] ?? '5') }}" 
                                       min="3" max="10" required
                                       class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('max_login_attempts') border-wwc-error @enderror">
                                <p class="mt-2 text-sm text-wwc-neutral-500">Maximum failed login attempts before account lockout</p>
                                @error('max_login_attempts')
                                    <p class="mt-2 text-sm text-wwc-error">{{ $message }}</p>
                                @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-wwc-neutral-200">
                        <button type="button" 
                                class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <i class='bx bx-refresh text-sm mr-2'></i>
                            Reset to Defaults
                        </button>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                            <i class='bx bx-save text-sm mr-2'></i>
                            Save Settings
                        </button>
                </div>
            </form>
        </div>

        <!-- System Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">System Information</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-info-circle text-sm'></i>
                            <span>Technical details</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-lg bg-wwc-primary-light flex items-center justify-center">
                                <i class='bx bx-code-alt text-sm text-wwc-primary'></i>
                            </div>
                            <div>
                                <dt class="text-sm font-semibold text-wwc-neutral-900">Laravel Version</dt>
                                <dd class="text-sm text-wwc-neutral-600">{{ app()->version() }}</dd>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-lg bg-wwc-success-light flex items-center justify-center">
                                <i class='bx bx-code text-sm text-wwc-success'></i>
                            </div>
                            <div>
                                <dt class="text-sm font-semibold text-wwc-neutral-900">PHP Version</dt>
                                <dd class="text-sm text-wwc-neutral-600">{{ PHP_VERSION }}</dd>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-lg bg-wwc-info-light flex items-center justify-center">
                                <i class='bx bx-data text-sm text-wwc-info'></i>
                            </div>
                            <div>
                                <dt class="text-sm font-semibold text-wwc-neutral-900">Database Driver</dt>
                                <dd class="text-sm text-wwc-neutral-600">{{ config('database.default') }}</dd>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-lg bg-wwc-warning-light flex items-center justify-center">
                                <i class='bx bx-world text-sm text-wwc-warning'></i>
                            </div>
                            <div>
                                <dt class="text-sm font-semibold text-wwc-neutral-900">Environment</dt>
                                <dd class="text-sm text-wwc-neutral-600">{{ app()->environment() }}</dd>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-lg bg-wwc-accent-light flex items-center justify-center">
                                <i class='bx bx-link text-sm text-wwc-accent'></i>
                            </div>
                            <div>
                                <dt class="text-sm font-semibold text-wwc-neutral-900">Application URL</dt>
                                <dd class="text-sm text-wwc-neutral-600">{{ config('app.url') }}</dd>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-lg {{ config('app.debug') ? 'bg-wwc-error-light' : 'bg-wwc-success-light' }} flex items-center justify-center">
                                <i class='bx {{ config('app.debug') ? 'bx-error' : 'bx-check-circle' }} text-sm {{ config('app.debug') ? 'text-wwc-error' : 'text-wwc-success' }}'></i>
                            </div>
                            <div>
                                <dt class="text-sm font-semibold text-wwc-neutral-900">Debug Mode</dt>
                                <dd class="text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ config('app.debug') ? 'bg-wwc-error-light text-wwc-error' : 'bg-wwc-success-light text-wwc-success' }}">
                                        {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                                    </span>
                                </dd>
                            </div>
                    </div>
                </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
