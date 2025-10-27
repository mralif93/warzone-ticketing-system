@extends('layouts.admin')

@section('title', 'Audit Trail')
@section('page-title', 'Audit Trail')
@section('page-subtitle', 'System activity and user actions log')

@section('content')
<!-- Professional Audit Trail with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <!-- Total Logs -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $auditLogs->total() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Logs</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-info font-semibold">
                                    <i class='bx bx-history text-xs mr-1'></i>
                                    Activity Records
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-history text-2xl text-blue-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Today's Actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ \App\Models\AuditLog::whereDate('created_at', today())->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Today's Activity</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <i class='bx bx-time text-xs mr-1'></i>
                                    Last 24 Hours
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class='bx bx-time text-2xl text-green-600'></i>
                        </div>
                    </div>
                </div>

                <!-- This Week -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ \App\Models\AuditLog::where('created_at', '>=', now()->startOfWeek())->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">This Week</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-primary font-semibold">
                                    <i class='bx bx-calendar text-xs mr-1'></i>
                                    7 Days
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-red-100 flex items-center justify-center">
                            <i class='bx bx-calendar text-2xl text-red-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Active Users -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ \App\Models\AuditLog::distinct('user_id')->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Users Tracked</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-accent font-semibold">
                                    <i class='bx bx-user text-xs mr-1'></i>
                                    Active Users
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-orange-100 flex items-center justify-center">
                            <i class='bx bx-user text-2xl text-orange-600'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Search & Filter Logs</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-search text-sm'></i>
                            <span>Find specific activity</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 sm:col-span-6">
                            <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Search all fields..."
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div class="col-span-12 sm:col-span-3">
                            <label for="action" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Action</label>
                            <select name="action" id="action" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Actions</option>
                                <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                                <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                                <option value="scan" {{ request('action') == 'scan' ? 'selected' : '' }}>Scan</option>
                                <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                                <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                                <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                                <option value="purchase" {{ request('action') == 'purchase' ? 'selected' : '' }}>Purchase</option>
                            </select>
                        </div>
                        <div class="col-span-12 sm:col-span-3">
                            <label for="table_name" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Module</label>
                            <select name="table_name" id="table_name" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Modules</option>
                                @foreach($tables as $table)
                                    <option value="{{ $table }}" {{ request('table_name') == $table ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $table)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-12 flex justify-end gap-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-search text-sm mr-2'></i>
                                Search Logs
                            </button>
                            <a href="{{ route('admin.audit-logs.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Audit Logs List -->
            @if($auditLogs->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Activity Logs</h3>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                <i class='bx bx-history text-sm'></i>
                                <span>Showing {{ $auditLogs->count() }} of {{ $auditLogs->total() }} logs</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-wwc-neutral-100">
                            <thead class="bg-wwc-neutral-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">User</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Action</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Description</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">IP Address</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Timestamp</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-wwc-neutral-100">
                            @foreach($auditLogs as $log)
                            <tr class="hover:bg-wwc-neutral-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->user)
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-lg bg-wwc-primary-light flex items-center justify-center">
                                                    <i class='bx bx-user text-lg text-wwc-primary'></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-wwc-neutral-900">{{ $log->user->name }}</div>
                                                <div class="text-xs text-wwc-neutral-500">{{ $log->user->email }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-lg bg-wwc-neutral-200 flex items-center justify-center">
                                                    <i class='bx bx-server text-lg text-wwc-neutral-600'></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-wwc-neutral-600">System</div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if($log->action === 'login') bg-green-100 text-green-800
                                        @elseif($log->action === 'logout') bg-gray-100 text-gray-800
                                        @elseif($log->action === 'create') bg-blue-100 text-blue-800
                                        @elseif($log->action === 'update') bg-yellow-100 text-yellow-800
                                        @elseif($log->action === 'delete') bg-red-100 text-red-800
                                        @elseif($log->action === 'purchase') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-wwc-neutral-900">{{ $log->description }}</div>
                                    @if($log->model_type && $log->model_id)
                                        <div class="text-xs text-wwc-neutral-500 mt-1">
                                            {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-wwc-neutral-600">
                                    {{ $log->ip_address }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-wwc-neutral-900">{{ $log->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-wwc-neutral-500">{{ $log->created_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('admin.audit-logs.show', $log) }}" 
                                       class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                       title="View details">
                                        <i class='bx bx-show text-xs mr-1.5'></i>
                                        View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-wwc-neutral-100">
                    {{ $auditLogs->links() }}
                </div>
            </div>
            @else
            <div class="bg-white shadow-sm rounded-2xl border border-wwc-neutral-200">
                <div class="text-center py-12">
                    <div class="mx-auto h-16 w-16 rounded-full bg-wwc-neutral-100 flex items-center justify-center mb-4">
                        <i class='bx bx-history text-3xl text-wwc-neutral-400'></i>
                    </div>
                    <h3 class="text-xl font-semibold text-wwc-neutral-900 font-display mb-2">No audit logs found</h3>
                    <p class="text-sm text-wwc-neutral-600 mb-6">No logs match your current filters or no activity has been recorded yet.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
