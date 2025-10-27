@extends('layouts.admin')

@section('title', 'Audit Log Details')
@section('page-title', 'Audit Log Details')

@section('content')
<div class="min-h-screen bg-wwc-neutral-50">
    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <div class="flex items-center">
                    <a href="{{ route('admin.audit-logs.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-arrow-back text-sm mr-2'></i>
                        Back to Audit Trail
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Audit Log Details -->
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Log Details</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-info-circle text-sm'></i>
                                    <span>Audit information</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Log ID -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-red-100 flex items-center justify-center">
                                            <i class='bx bx-hash text-sm text-red-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="text-sm font-semibold text-wwc-neutral-600">Log ID</span>
                                        </div>
                                        <span class="text-base font-medium text-wwc-neutral-900">#{{ $auditLog->id }}</span>
                                    </div>
                                </div>

                                <!-- Action -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-check text-sm text-green-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="text-sm font-semibold text-wwc-neutral-600">Action</span>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($auditLog->action === 'login') bg-green-100 text-green-800
                                            @elseif($auditLog->action === 'logout') bg-gray-100 text-gray-800
                                            @elseif($auditLog->action === 'create') bg-blue-100 text-blue-800
                                            @elseif($auditLog->action === 'update') bg-yellow-100 text-yellow-800
                                            @elseif($auditLog->action === 'delete') bg-red-100 text-red-800
                                            @elseif($auditLog->action === 'purchase') bg-purple-100 text-purple-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($auditLog->action) }}
                                        </span>
                                    </div>
                                </div>

                                @if($auditLog->table_name)
                                <!-- Table Name -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-table text-sm text-blue-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="text-sm font-semibold text-wwc-neutral-600">Table</span>
                                        </div>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $auditLog->table_name }}</span>
                                    </div>
                                </div>
                                @endif

                                @if($auditLog->model_id)
                                <!-- Record ID -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                            <i class='bx bx-id-card text-sm text-orange-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="text-sm font-semibold text-wwc-neutral-600">Record ID</span>
                                        </div>
                                        <span class="text-base font-medium text-wwc-neutral-900">#{{ $auditLog->model_id }}</span>
                                    </div>
                                </div>
                                @endif

                                @if($auditLog->model_type)
                                <!-- Model Type -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class='bx bx-cube text-sm text-purple-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="text-sm font-semibold text-wwc-neutral-600">Model</span>
                                        </div>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ class_basename($auditLog->model_type) }}</span>
                                    </div>
                                </div>
                                @endif

                                <!-- IP Address -->
                                <div class="flex items-center py-3 border-b border-wwc-neutral-100">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                            <i class='bx bx-map text-sm text-indigo-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="text-sm font-semibold text-wwc-neutral-600">IP Address</span>
                                        </div>
                                        <span class="text-base font-medium text-wwc-neutral-900 font-mono">{{ $auditLog->ip_address }}</span>
                                    </div>
                                </div>

                                <!-- Timestamp -->
                                <div class="flex items-center py-3">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <i class='bx bx-time-five text-sm text-gray-600'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="text-sm font-semibold text-wwc-neutral-600">Timestamp</span>
                                        </div>
                                        <span class="text-base font-medium text-wwc-neutral-900">{{ $auditLog->created_at->format('F j, Y \a\t g:i:s A') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($auditLog->description)
                    <!-- Description -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mt-6">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Description</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-note text-sm'></i>
                                    <span>Details</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-base text-wwc-neutral-900">{{ $auditLog->description }}</p>
                        </div>
                    </div>
                    @endif

                    @if($auditLog->old_values || $auditLog->new_values)
                    <!-- Data Changes -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mt-6">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Data Changes</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-code text-sm'></i>
                                    <span>Value changes</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Old Values (Left Side) -->
                                <div>
                                    <div class="flex items-center mb-3">
                                        <i class='bx bx-history text-sm text-red-600 mr-2'></i>
                                        <h4 class="text-sm font-bold text-wwc-neutral-700">Previous Values</h4>
                                    </div>
                                    @if($auditLog->old_values)
                                        <pre class="bg-red-50 text-red-900 p-4 rounded-lg text-xs overflow-x-auto border border-red-200 max-h-96 overflow-y-auto">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                                    @else
                                        <div class="bg-wwc-neutral-50 border border-wwc-neutral-200 rounded-lg p-4 text-center">
                                            <p class="text-xs text-wwc-neutral-500">No previous values</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- New Values (Right Side) -->
                                <div>
                                    <div class="flex items-center mb-3">
                                        <i class='bx bx-check-circle text-sm text-green-600 mr-2'></i>
                                        <h4 class="text-sm font-bold text-wwc-neutral-700">New Values</h4>
                                    </div>
                                    @if($auditLog->new_values)
                                        <pre class="bg-green-50 text-green-900 p-4 rounded-lg text-xs overflow-x-auto border border-green-200 max-h-96 overflow-y-auto">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</pre>
                                    @else
                                        <div class="bg-wwc-neutral-50 border border-wwc-neutral-200 rounded-lg p-4 text-center">
                                            <p class="text-xs text-wwc-neutral-500">No new values</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- User Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">User</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-user text-sm'></i>
                                    <span>Performed by</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($auditLog->user)
                            <div class="text-center">
                                <div class="mx-auto h-16 w-16 rounded-full bg-wwc-primary flex items-center justify-center mb-4">
                                    <span class="text-xl font-bold text-white">{{ substr($auditLog->user->name, 0, 1) }}</span>
                                </div>
                                <h4 class="text-base font-bold text-wwc-neutral-900 mb-1">{{ $auditLog->user->name }}</h4>
                                <p class="text-sm text-wwc-neutral-600 mb-2">{{ $auditLog->user->email }}</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    @if($auditLog->user->role === 'administrator') bg-red-100 text-red-800
                                    @elseif($auditLog->user->role === 'gate_staff') bg-green-100 text-green-800
                                    @elseif($auditLog->user->role === 'counter_staff') bg-blue-100 text-blue-800
                                    @elseif($auditLog->user->role === 'support_staff') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucwords(str_replace('_', ' ', $auditLog->user->role)) }}
                                </span>
                            </div>
                            @else
                            <div class="text-center">
                                <div class="mx-auto h-16 w-16 rounded-full bg-wwc-neutral-200 flex items-center justify-center mb-4">
                                    <i class='bx bx-server text-2xl text-wwc-neutral-600'></i>
                                </div>
                                <h4 class="text-base font-bold text-wwc-neutral-900 mb-1">System</h4>
                                <p class="text-sm text-wwc-neutral-600">Automated action</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Request Details -->
                    @if($auditLog->user_agent)
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Request</h3>
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-globe text-sm'></i>
                                    <span>Details</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-wwc-neutral-600 mb-1">User Agent</p>
                                    <p class="text-xs text-wwc-neutral-600 break-words">{{ Str::limit($auditLog->user_agent, 100) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
