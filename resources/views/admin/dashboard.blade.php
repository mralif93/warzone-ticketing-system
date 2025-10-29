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
                                    {{ \App\Models\PurchaseTicket::where('status', 'held')->count() }} Pending
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

            <!-- Main Content Grid - Full Width -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Events Section - Takes 2/3 width on large screens -->
                <div class="xl:col-span-2">
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
                        <div class="p-6">
                            <div class="space-y-4">
                                @forelse(\App\Models\Event::latest()->take(6)->get() as $event)
                                <div class="flex items-center justify-between p-5 bg-wwc-neutral-50 rounded-2xl hover:shadow-md transition-all duration-300">
                                    <div class="flex items-center space-x-5">
                                        <div class="flex-shrink-0">
                                            <div class="h-16 w-16 rounded-2xl bg-wwc-primary flex items-center justify-center shadow-lg">
                                                <i class='bx bx-calendar text-3xl text-white'></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h4 class="text-lg font-semibold text-wwc-neutral-900 truncate">{{ $event->name }}</h4>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                                    @if($event->status === 'on_sale') bg-wwc-success text-white
                                                    @elseif($event->status === 'Draft') bg-wwc-neutral-200 text-wwc-neutral-800
                                                    @elseif($event->status === 'Cancelled') bg-wwc-error text-white
                                                    @else bg-wwc-warning text-white
                                                    @endif">
                                                    {{ str_replace('_', '', ucwords(str_replace('_', ' ', $event->status))) }}
                                                </span>
                                            </div>
                                            <div class="flex items-center space-x-6 text-sm text-wwc-neutral-600">
                                                <span class="flex items-center">
                                                    <i class='bx bx-calendar text-sm mr-2'></i>
                                                    {{ $event->date_time->format('M j, Y') }}
                                                </span>
                                                <span class="flex items-center">
                                                    <i class='bx bx-receipt text-sm mr-2'></i>
                                                    {{ \App\Models\PurchaseTicket::where('event_id', $event->id)->where('status', 'sold')->count() }} sold
                                                </span>
                                                <span class="flex items-center">
                                                    <i class='bx bx-dollar text-sm mr-2'></i>
                                                    RM{{ number_format($event->purchaseTickets()->where('status', 'sold')->sum('price_paid'), 0) }} revenue
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.events.show', $event) }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-wwc-primary hover:text-wwc-primary-dark hover:bg-wwc-primary-light rounded-2xl transition-all duration-300">
                                            View
                                        </a>
                                        <button class="p-2 text-wwc-neutral-400 hover:text-wwc-neutral-600 hover:bg-wwc-neutral-100 rounded-2xl transition-all duration-300">
                                            <i class='bx bx-dots-vertical text-lg'></i>
                                        </button>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-16">
                                    <div class="h-20 w-20 rounded-2xl bg-wwc-primary flex items-center justify-center mx-auto mb-6 shadow-lg">
                                        <i class='bx bx-calendar text-4xl text-white'></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-wwc-neutral-900 mb-3">No events yet</h3>
                                    <p class="text-base text-wwc-neutral-600 mb-6">Get started by creating your first event.</p>
                                    <a href="{{ route('admin.events.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent shadow-lg text-base font-semibold rounded-2xl text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-all duration-300 hover:shadow-xl">
                                        <i class='bx bx-plus text-lg mr-3'></i>
                                        Create New Event
                                    </a>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Takes 1/3 width on large screens -->
                <div class="space-y-6">
                    <!-- Quick Actions with WWC Brand Theme -->
                    <div class="bg-white rounded-2xl shadow-lg border border-wwc-neutral-200">
                        <div class="px-6 py-5 border-b border-wwc-neutral-100">
                            <h3 class="text-xl font-bold text-wwc-neutral-900">Quick Actions</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <a href="{{ route('admin.events.create') }}" class="flex items-center p-4 text-sm font-semibold text-wwc-neutral-700 hover:bg-wwc-primary-light hover:text-wwc-primary rounded-2xl transition-all duration-300 hover:shadow-md">
                                <div class="h-12 w-12 rounded-2xl bg-wwc-primary flex items-center justify-center mr-4 shadow-lg">
                                    <i class='bx bx-plus text-xl text-white'></i>
                                </div>
                                <span class="text-base font-medium">Create New Event</span>
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center p-4 text-sm font-semibold text-wwc-neutral-700 hover:bg-wwc-success-light hover:text-wwc-success rounded-2xl transition-all duration-300 hover:shadow-md">
                                <div class="h-12 w-12 rounded-2xl bg-wwc-success flex items-center justify-center mr-4 shadow-lg">
                                    <i class='bx bx-group text-xl text-white'></i>
                                </div>
                                <span class="text-base font-medium">Manage Users</span>
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="flex items-center p-4 text-sm font-semibold text-wwc-neutral-700 hover:bg-wwc-info-light hover:text-wwc-info rounded-2xl transition-all duration-300 hover:shadow-md">
                                <div class="h-12 w-12 rounded-2xl bg-wwc-info flex items-center justify-center mr-4 shadow-lg">
                                    <i class='bx bx-shopping-bag text-xl text-white'></i>
                                </div>
                                <span class="text-base font-medium">View Orders</span>
                            </a>
                            <a href="{{ route('admin.reports') }}" class="flex items-center p-4 text-sm font-semibold text-wwc-neutral-700 hover:bg-wwc-accent-light hover:text-wwc-accent rounded-2xl transition-all duration-300 hover:shadow-md">
                                <div class="h-12 w-12 rounded-2xl bg-wwc-accent flex items-center justify-center mr-4 shadow-lg">
                                    <i class='bx bx-bar-chart text-xl text-white'></i>
                                </div>
                                <span class="text-base font-medium">View Reports</span>
                            </a>
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