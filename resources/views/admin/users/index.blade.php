@extends('layouts.admin')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<!-- Professional User Management with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display">User Management</h1>
                    <p class="mt-1 text-sm text-wwc-neutral-600 font-medium">Manage system users, roles, and permissions</p>
                </div>
                <div>
                    <a href="{{ route('admin.users.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <i class='bx bx-plus text-sm mr-2'></i>
                        Add New User
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <!-- Total Users -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $users->total() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Users</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-info font-semibold">
                                    <i class='bx bx-group text-xs mr-1'></i>
                                    {{ $users->where('role', 'Customer')->count() }} Customers
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-info-light flex items-center justify-center">
                            <i class='bx bx-group text-2xl text-wwc-info'></i>
                        </div>
                    </div>
                </div>

                <!-- Administrators -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $users->where('role', 'Administrator')->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Administrators</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-primary font-semibold">
                                    <i class='bx bx-shield text-xs mr-1'></i>
                                    System Admins
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-primary-light flex items-center justify-center">
                            <i class='bx bx-shield text-2xl text-wwc-primary'></i>
                        </div>
                    </div>
                </div>

                <!-- Staff Members -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $users->whereIn('role', ['Gate Staff', 'Counter Staff', 'Support Staff'])->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Staff Members</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <i class='bx bx-user-check text-xs mr-1'></i>
                                    Active Staff
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-success-light flex items-center justify-center">
                            <i class='bx bx-user-check text-2xl text-wwc-success'></i>
                        </div>
                    </div>
                </div>

                <!-- Active Users -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $users->where('is_active', true)->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Active Users</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-accent font-semibold">
                                    <i class='bx bx-check-circle text-xs mr-1'></i>
                                    {{ round(($users->where('is_active', true)->count() / max($users->total(), 1)) * 100) }}% Active
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-accent-light flex items-center justify-center">
                            <i class='bx bx-check-circle text-2xl text-wwc-accent'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white shadow-sm rounded-2xl border border-wwc-neutral-200 mb-6">
                <div class="px-6 py-4 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                    <h2 class="text-lg font-semibold text-wwc-neutral-900 font-display">Search & Filter Users</h2>
                    <p class="text-wwc-neutral-600 text-sm">Find specific users using the filters below</p>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search Users</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Name, email..."
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div>
                            <label for="role" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Role</label>
                            <select name="role" id="role" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Roles</option>
                                <option value="Administrator" {{ request('role') == 'Administrator' ? 'selected' : '' }}>Administrator</option>
                                <option value="Gate Staff" {{ request('role') == 'Gate Staff' ? 'selected' : '' }}>Gate Staff</option>
                                <option value="Counter Staff" {{ request('role') == 'Counter Staff' ? 'selected' : '' }}>Counter Staff</option>
                                <option value="Support Staff" {{ request('role') == 'Support Staff' ? 'selected' : '' }}>Support Staff</option>
                                <option value="Customer" {{ request('role') == 'Customer' ? 'selected' : '' }}>Customer</option>
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Status</label>
                            <select name="status" id="status" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-search text-sm mr-2'></i>
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users List -->
            @if($users->count() > 0)
                <div class="bg-white shadow-sm rounded-2xl border border-wwc-neutral-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-wwc-neutral-200 bg-wwc-neutral-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-wwc-neutral-900 font-display">All Users</h2>
                                <p class="text-wwc-neutral-600 text-sm">Showing {{ $users->count() }} of {{ $users->total() }} users</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="text-xs text-wwc-neutral-600">
                                    <span class="font-semibold">{{ $users->where('role', 'Administrator')->count() }}</span> Admins
                                </div>
                                <div class="text-xs text-wwc-neutral-600">
                                    <span class="font-semibold">{{ $users->whereIn('role', ['Gate Staff', 'Counter Staff', 'Support Staff'])->count() }}</span> Staff
                                </div>
                                <div class="text-xs text-wwc-neutral-600">
                                    <span class="font-semibold">{{ $users->where('role', 'Customer')->count() }}</span> Customers
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-wwc-neutral-200">
                            <thead class="bg-wwc-neutral-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        User
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Joined
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-wwc-neutral-200">
                                @foreach($users as $user)
                                <tr class="hover:bg-wwc-neutral-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-2xl bg-wwc-primary-light flex items-center justify-center shadow-sm">
                                                    <span class="text-sm font-semibold text-wwc-primary">
                                                        {{ substr($user->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-wwc-neutral-900 font-display">{{ $user->name }}</div>
                                                <div class="text-xs text-wwc-neutral-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            @if($user->role === 'Administrator') bg-wwc-primary text-white
                                            @elseif($user->role === 'Gate Staff') bg-wwc-info text-white
                                            @elseif($user->role === 'Counter Staff') bg-wwc-success text-white
                                            @elseif($user->role === 'Support Staff') bg-wwc-warning text-white
                                            @else bg-wwc-neutral-200 text-wwc-neutral-800
                                            @endif">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            @if($user->is_active) bg-wwc-success text-white
                                            @else bg-wwc-error text-white
                                            @endif">
                                            @if($user->is_active) Active @else Inactive @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-500">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.users.show', $user) }}" 
                                               class="inline-flex items-center px-2 py-1 text-xs font-semibold text-wwc-primary hover:bg-wwc-primary-light hover:text-wwc-primary-dark rounded-2xl transition-colors duration-200">
                                                <i class='bx bx-show text-xs mr-1'></i>
                                                View
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="inline-flex items-center px-2 py-1 text-xs font-semibold text-wwc-neutral-600 hover:bg-wwc-neutral-100 hover:text-wwc-neutral-900 rounded-2xl transition-colors duration-200">
                                                <i class='bx bx-edit text-xs mr-1'></i>
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                                                  class="inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-2 py-1 text-xs font-semibold text-wwc-error hover:bg-wwc-error-light hover:text-wwc-error-dark rounded-2xl transition-colors duration-200">
                                                    <i class='bx bx-trash text-xs mr-1'></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-wwc-neutral-200 bg-wwc-neutral-50">
                        {{ $users->links() }}
                    </div>
                </div>
            @else
                <div class="bg-white shadow-sm rounded-2xl border border-wwc-neutral-200">
                    <div class="text-center py-12">
                        <div class="mx-auto h-16 w-16 rounded-full bg-wwc-neutral-100 flex items-center justify-center mb-4">
                            <i class='bx bx-group text-3xl text-wwc-neutral-400'></i>
                        </div>
                        <h3 class="text-xl font-semibold text-wwc-neutral-900 font-display mb-2">No users found</h3>
                        <p class="text-sm text-wwc-neutral-600 mb-6">Get started by creating your first user to begin managing the system.</p>
                        <div>
                            <a href="{{ route('admin.users.create') }}" 
                               class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-2xl text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-plus text-sm mr-2'></i>
                                Add New User
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
