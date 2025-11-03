@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Professional Dashboard with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Statistics Cards with WWC Brand Colors -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                <!-- Total Events -->
                <div class="bg-white rounded-2xl shadow-lg border border-wwc-neutral-200 p-6 relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute top-0 right-0 w-20 h-20 opacity-5">
                        <i class='bx bx-calendar text-8xl text-wwc-primary absolute -top-2 -right-2'></i>
                    </div>
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <div class="text-3xl font-bold text-wwc-neutral-900 mb-2">{{ $stats['total_events'] }}</div>
                            <div class="text-sm text-wwc-neutral-600 mb-3 font-medium">Total Events</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-sm text-wwc-success font-semibold">
                                    <i class='bx bx-trending-up text-sm mr-2'></i>
                                    {{ $stats['events_on_sale'] }} Active
                                </div>
                            </div>
                        </div>
                        <div class="h-16 w-16 rounded-2xl bg-wwc-primary flex items-center justify-center shadow-lg">
                            <i class='bx bx-calendar text-3xl text-white'></i>
                        </div>
                    </div>
                </div>

                <!-- Total Tickets -->
                <div class="bg-white rounded-2xl shadow-lg border border-wwc-neutral-200 p-6 relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute top-0 right-0 w-20 h-20 opacity-5">
                        <i class='bx bx-receipt text-8xl text-wwc-success absolute -top-2 -right-2'></i>
                    </div>
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <div class="text-3xl font-bold text-wwc-neutral-900 mb-2">{{ $stats['total_tickets_sold'] }}</div>
                            <div class="text-sm text-wwc-neutral-600 mb-3 font-medium">Tickets Sold</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-sm text-wwc-warning font-semibold">
                                    <i class='bx bx-time text-sm mr-2'></i>
                                    {{ $stats['total_tickets_pending'] ?? 0 }} Pending
                                </div>
                            </div>
                        </div>
                        <div class="h-16 w-16 rounded-2xl bg-wwc-success flex items-center justify-center shadow-lg">
                            <i class='bx bx-receipt text-3xl text-white'></i>
                        </div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-white rounded-2xl shadow-lg border border-wwc-neutral-200 p-6 relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute top-0 right-0 w-20 h-20 opacity-5">
                        <i class='bx bx-group text-8xl text-wwc-info absolute -top-2 -right-2'></i>
                    </div>
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <div class="text-3xl font-bold text-wwc-neutral-900 mb-2">{{ $stats['total_users'] }}</div>
                            <div class="text-sm text-wwc-neutral-600 mb-3 font-medium">Total Users</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-sm text-wwc-info font-semibold">
                                    <i class='bx bx-user text-sm mr-2'></i>
                                    {{ \App\Models\User::where('role', 'customer')->count() }} Customers
                                </div>
                            </div>
                        </div>
                        <div class="h-16 w-16 rounded-2xl bg-wwc-info flex items-center justify-center shadow-lg">
                            <i class='bx bx-group text-3xl text-white'></i>
                        </div>
                    </div>
                </div>

                <!-- Revenue -->
                <div class="bg-white rounded-2xl shadow-lg border border-wwc-neutral-200 p-6 relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute top-0 right-0 w-20 h-20 opacity-5">
                        <i class='bx bx-dollar text-8xl text-wwc-accent absolute -top-2 -right-2'></i>
                    </div>
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <div class="text-3xl font-bold text-wwc-neutral-900 mb-2">RM{{ number_format($stats['total_revenue'], 0) }}</div>
                            <div class="text-sm text-wwc-neutral-600 mb-3 font-medium">Total Revenue</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-sm text-wwc-success font-semibold">
                                    <i class='bx bx-trending-up text-sm mr-2'></i>
                                    +12% this month
                                </div>
                            </div>
                        </div>
                        <div class="h-16 w-16 rounded-2xl bg-wwc-accent flex items-center justify-center shadow-lg">
                            <i class='bx bx-dollar text-3xl text-white'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid - 2 Columns with Custom Widths -->
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
                <!-- Left Column: Recent Events + Recent Orders (3/4 width) -->
                <div class="xl:col-span-3 space-y-6">
                    <!-- Recent Events Table -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Recent Events</h3>
                                <a href="{{ route('admin.events.index') }}" class="inline-flex items-center px-3 py-1 text-xs font-semibold text-wwc-primary hover:text-wwc-primary-dark hover:bg-wwc-primary-light rounded-lg transition-all duration-300">
                                    View all
                                    <i class='bx bx-chevron-right text-xs ml-1'></i>
                                </a>
                            </div>
                        </div>
                        <div class="overflow-x-auto rounded-b-2xl">
                            <table class="min-w-full divide-y divide-wwc-neutral-200">
                                <thead class="bg-wwc-neutral-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Event Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Venue</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Tickets Sold</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Revenue</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-wwc-neutral-200">
                                    @forelse(\App\Models\Event::latest()->take(6)->get() as $event)
                                    <tr class="hover:bg-wwc-neutral-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-wwc-neutral-900">{{ $event->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($event->status === 'on_sale') bg-green-100 text-green-800
                                                @elseif($event->status === 'draft') bg-gray-100 text-gray-800
                                                @elseif($event->status === 'cancelled') bg-red-100 text-red-800
                                                @elseif($event->status === 'inactive') bg-yellow-100 text-yellow-800
                                                @else bg-blue-100 text-blue-800
                                                @endif">
                                                {{ str_replace('_', '', ucwords(str_replace('_', ' ', $event->status))) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-wwc-neutral-900">{{ $event->date_time->format('M j, Y') }}</div>
                                            <div class="text-sm text-wwc-neutral-500">{{ $event->date_time->format('g:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-wwc-neutral-900">{{ $event->venue ?? 'N/A' }}</div>
                                            @if($event->location)
                                                <div class="text-xs text-wwc-neutral-500">{{ $event->location }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-wwc-neutral-900">{{ \App\Models\PurchaseTicket::where('event_id', $event->id)->whereIn('status', ['sold', 'active', 'scanned'])->count() }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-wwc-neutral-900">RM{{ number_format($event->purchaseTickets()->whereIn('status', ['sold', 'active', 'scanned'])->sum('price_paid'), 0) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.events.show', $event) }}" class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg transition-colors duration-200" title="View Event">
                                                    <i class='bx bx-show text-sm'></i>
                                                </a>
                                                <a href="{{ route('admin.events.edit', $event) }}" class="inline-flex items-center justify-center w-8 h-8 bg-orange-100 hover:bg-orange-200 text-orange-600 rounded-lg transition-colors duration-200" title="Edit Event">
                                                    <i class='bx bx-edit text-sm'></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <div class="text-center">
                                                <div class="h-16 w-16 rounded-2xl bg-wwc-primary flex items-center justify-center mx-auto mb-4 shadow-lg">
                                                    <i class='bx bx-calendar text-2xl text-white'></i>
                                                </div>
                                                <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">No events yet</h3>
                                                <p class="text-sm text-wwc-neutral-600">Get started by creating your first event.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Recent Orders Table -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Recent Orders</h3>
                                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-3 py-1 text-xs font-semibold text-wwc-primary hover:text-wwc-primary-dark hover:bg-wwc-primary-light rounded-lg transition-all duration-300">
                                    View all
                                    <i class='bx bx-chevron-right text-xs ml-1'></i>
                                </a>
                            </div>
                        </div>
                        <div class="overflow-x-auto rounded-b-2xl">
                            <table class="min-w-full divide-y divide-wwc-neutral-200">
                                <thead class="bg-wwc-neutral-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Order #</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Event</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-wwc-neutral-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-wwc-neutral-200">
                                    @forelse($recentOrders as $order)
                                    <tr class="hover:bg-wwc-neutral-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-wwc-neutral-900">#{{ $order->order_number }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-wwc-neutral-900">{{ $order->customer_name ?? $order->user->name ?? 'N/A' }}</div>
                                            <div class="text-sm text-wwc-neutral-500">{{ $order->customer_email ?? $order->user->email ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-wwc-neutral-900">{{ $order->event->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-wwc-neutral-900">RM{{ number_format($order->total_amount, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($order->status === 'paid') bg-green-100 text-green-800
                                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                                @elseif($order->status === 'refunded') bg-purple-100 text-purple-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-wwc-neutral-900">{{ $order->created_at->format('M j, Y') }}</div>
                                            <div class="text-sm text-wwc-neutral-500">{{ $order->created_at->format('g:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg transition-colors duration-200" title="View Order">
                                                    <i class='bx bx-show text-sm'></i>
                                                </a>
                                                <a href="{{ route('admin.orders.edit', $order) }}" class="inline-flex items-center justify-center w-8 h-8 bg-orange-100 hover:bg-orange-200 text-orange-600 rounded-lg transition-colors duration-200" title="Edit Order">
                                                    <i class='bx bx-edit text-sm'></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <div class="text-center">
                                                <div class="h-16 w-16 rounded-2xl bg-wwc-primary flex items-center justify-center mx-auto mb-4 shadow-lg">
                                                    <i class='bx bx-shopping-bag text-2xl text-white'></i>
                                                </div>
                                                <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-2">No orders yet</h3>
                                                <p class="text-sm text-wwc-neutral-600">Orders will appear here once customers start purchasing tickets.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Quick Actions + Recent Activities (1/4 width) -->
                <div class="xl:col-span-1 space-y-6">
                    <!-- Quick Actions with WWC Brand Theme -->
                    <div class="bg-white rounded-2xl shadow-lg border border-wwc-neutral-200">
                        <div class="px-6 py-5 border-b border-wwc-neutral-100">
                            <h3 class="text-xl font-bold text-wwc-neutral-900">Quick Actions</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 rounded-2xl bg-wwc-primary flex items-center justify-center shadow-lg">
                                            <i class='bx bx-plus text-xl text-white'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('admin.events.create') }}" class="text-sm font-semibold text-wwc-neutral-900 hover:text-wwc-primary mb-1 block">Create New Event</a>
                                        <p class="text-xs text-wwc-neutral-500">Set up a new event</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 rounded-2xl bg-wwc-success flex items-center justify-center shadow-lg">
                                            <i class='bx bx-group text-xl text-white'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-wwc-neutral-900 hover:text-wwc-primary mb-1 block">Manage Users</a>
                                        <p class="text-xs text-wwc-neutral-500">View and manage user accounts</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 rounded-2xl bg-wwc-info flex items-center justify-center shadow-lg">
                                            <i class='bx bx-shopping-bag text-xl text-white'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-wwc-neutral-900 hover:text-wwc-primary mb-1 block">View Orders</a>
                                        <p class="text-xs text-wwc-neutral-500">Monitor ticket orders</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 rounded-2xl bg-wwc-accent flex items-center justify-center shadow-lg">
                                            <i class='bx bx-bar-chart text-xl text-white'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('admin.reports') }}" class="text-sm font-semibold text-wwc-neutral-900 hover:text-wwc-primary mb-1 block">View Reports</a>
                                        <p class="text-xs text-wwc-neutral-500">Analytics and insights</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activities with WWC Brand Theme -->
                    <div class="bg-white rounded-2xl shadow-lg border border-wwc-neutral-200">
                        <div class="px-6 py-5 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-wwc-neutral-900">Recent Activities</h3>
                                <a href="{{ route('admin.audit-logs.index') }}" class="text-sm text-wwc-primary hover:text-wwc-primary-dark font-semibold">View all</a>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @forelse(\App\Models\AuditLog::latest()->take(5)->get() as $log)
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 rounded-2xl flex items-center justify-center shadow-lg
                                            @if($log->action === 'CREATE') bg-wwc-success
                                            @elseif($log->action === 'UPDATE') bg-wwc-accent
                                            @elseif($log->action === 'DELETE') bg-wwc-error
                                            @else bg-wwc-neutral-200
                                            @endif">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($log->action === 'CREATE')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                @elseif($log->action === 'UPDATE')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                @elseif($log->action === 'DELETE')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                @endif
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-wwc-neutral-900 mb-1">{{ $log->description ?? $log->action . ' ' . $log->table_name }}</p>
                                        <p class="text-xs text-wwc-neutral-500">{{ $log->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-8">
                                    <div class="h-16 w-16 rounded-2xl bg-wwc-neutral-200 flex items-center justify-center mx-auto mb-4 shadow-lg">
                                        <i class='bx bx-file text-3xl text-wwc-neutral-400'></i>
                                    </div>
                                    <p class="text-sm text-wwc-neutral-500">No recent activities</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection