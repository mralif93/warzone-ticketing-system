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
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <!-- Total Events -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $stats['total_events'] }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Events</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <i class='bx bx-trending-up text-xs mr-1'></i>
                                    {{ $stats['events_on_sale'] }} Active
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-primary-light flex items-center justify-center">
                            <i class='bx bx-calendar text-2xl text-wwc-primary'></i>
                        </div>
                    </div>
                </div>

                <!-- Total Tickets -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $stats['total_tickets_sold'] }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Tickets Sold</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-warning font-semibold">
                                    <i class='bx bx-time text-xs mr-1'></i>
                                    {{ \App\Models\Ticket::where('status', 'Held')->count() }} Pending
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-success-light flex items-center justify-center">
                            <i class='bx bx-ticket text-2xl text-wwc-success'></i>
                        </div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $stats['total_users'] }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Users</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-info font-semibold">
                                    <i class='bx bx-user text-xs mr-1'></i>
                                    {{ \App\Models\User::where('role', 'Customer')->count() }} Customers
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-info-light flex items-center justify-center">
                            <i class='bx bx-group text-2xl text-wwc-info'></i>
                        </div>
                    </div>
                </div>

                <!-- Revenue -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">${{ number_format($stats['total_revenue'], 0) }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Revenue</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <i class='bx bx-trending-up text-xs mr-1'></i>
                                    +12% this month
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-wwc-accent-light flex items-center justify-center">
                            <i class='bx bx-dollar text-2xl text-wwc-accent'></i>
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
                            <div class="space-y-3">
                                @forelse(\App\Models\Event::latest()->take(6)->get() as $event)
                                <div class="flex items-center justify-between p-4 bg-wwc-neutral-50 rounded-2xl">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="h-12 w-12 rounded-2xl bg-wwc-primary-light flex items-center justify-center">
                                                <i class='bx bx-calendar text-2xl text-wwc-primary'></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <h4 class="text-base font-semibold text-wwc-neutral-900 truncate">{{ $event->name }}</h4>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                                    @if($event->status === 'On Sale') bg-wwc-success text-white
                                                    @elseif($event->status === 'Draft') bg-wwc-neutral-200 text-wwc-neutral-800
                                                    @elseif($event->status === 'Cancelled') bg-wwc-error text-white
                                                    @else bg-wwc-warning text-white
                                                    @endif">
                                                    {{ $event->status }}
                                                </span>
                                            </div>
                                            <div class="flex items-center space-x-4 text-xs text-wwc-neutral-600">
                                                <span class="flex items-center">
                                                    <i class='bx bx-calendar text-xs mr-1'></i>
                                                    {{ $event->date_time->format('M j, Y') }}
                                                </span>
                                                <span class="flex items-center">
                                                    <i class='bx bx-ticket text-xs mr-1'></i>
                                                    {{ \App\Models\Ticket::where('event_id', $event->id)->where('status', 'Sold')->count() }} sold
                                                </span>
                                                <span class="flex items-center">
                                                    <i class='bx bx-dollar text-xs mr-1'></i>
                                                    ${{ number_format($event->tickets()->where('status', 'Sold')->sum('price_paid'), 0) }} revenue
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.events.show', $event) }}" class="inline-flex items-center px-3 py-1 text-xs font-semibold text-wwc-primary hover:text-wwc-primary-dark hover:bg-wwc-primary-light rounded-2xl transition-all duration-300">
                                            View
                                        </a>
                                        <button class="p-1 text-wwc-neutral-400 hover:text-wwc-neutral-600 hover:bg-wwc-neutral-100 rounded-2xl transition-all duration-300">
                                            <i class='bx bx-dots-vertical text-sm'></i>
                                        </button>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-12">
                                    <div class="h-16 w-16 rounded-2xl bg-wwc-primary-light flex items-center justify-center mx-auto mb-4">
                                        <i class='bx bx-calendar text-3xl text-wwc-primary'></i>
                                    </div>
                                    <h3 class="text-base font-semibold text-wwc-neutral-900 mb-2">No events yet</h3>
                                    <p class="text-sm text-wwc-neutral-600 mb-4">Get started by creating your first event.</p>
                                    <a href="{{ route('admin.events.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-xs font-semibold rounded-2xl text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                        <i class='bx bx-plus text-sm mr-2'></i>
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
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">Quick Actions</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('admin.events.create') }}" class="flex items-center p-3 text-sm font-semibold text-wwc-neutral-700 hover:bg-wwc-primary-light hover:text-wwc-primary rounded-2xl transition-colors duration-200">
                                <div class="h-8 w-8 rounded-2xl bg-wwc-primary-light flex items-center justify-center mr-3">
                                    <i class='bx bx-plus text-sm text-wwc-primary'></i>
                                </div>
                                <span class="text-sm">Create New Event</span>
                            </a>
                            <a href="{{ route('admin.users') }}" class="flex items-center p-3 text-sm font-semibold text-wwc-neutral-700 hover:bg-wwc-success-light hover:text-wwc-success rounded-2xl transition-colors duration-200">
                                <div class="h-8 w-8 rounded-2xl bg-wwc-success-light flex items-center justify-center mr-3">
                                    <i class='bx bx-group text-sm text-wwc-success'></i>
                                </div>
                                <span class="text-sm">Manage Users</span>
                            </a>
                            <a href="{{ route('admin.orders') }}" class="flex items-center p-3 text-sm font-semibold text-wwc-neutral-700 hover:bg-wwc-info-light hover:text-wwc-info rounded-2xl transition-colors duration-200">
                                <div class="h-8 w-8 rounded-2xl bg-wwc-info-light flex items-center justify-center mr-3">
                                    <i class='bx bx-shopping-bag text-sm text-wwc-info'></i>
                                </div>
                                <span class="text-sm">View Orders</span>
                            </a>
                            <a href="{{ route('admin.reports') }}" class="flex items-center p-3 text-sm font-semibold text-wwc-neutral-700 hover:bg-wwc-accent-light hover:text-wwc-accent rounded-2xl transition-colors duration-200">
                                <div class="h-8 w-8 rounded-2xl bg-wwc-accent-light flex items-center justify-center mr-3">
                                    <i class='bx bx-bar-chart text-sm text-wwc-accent'></i>
                                </div>
                                <span class="text-sm">View Reports</span>
                            </a>
                        </div>
                    </div>

                    <!-- Recent Activities with WWC Brand Theme -->
                    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                        <div class="px-6 py-4 border-b border-wwc-neutral-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-wwc-neutral-900">Recent Activities</h3>
                                <a href="{{ route('admin.audit-logs') }}" class="text-xs text-wwc-primary hover:text-wwc-primary-dark font-semibold">View all</a>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @forelse(\App\Models\AuditLog::latest()->take(5)->get() as $log)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-2xl flex items-center justify-center
                                            @if($log->action === 'CREATE') bg-wwc-success-light
                                            @elseif($log->action === 'UPDATE') bg-wwc-accent-light
                                            @elseif($log->action === 'DELETE') bg-wwc-error-light
                                            @else bg-wwc-neutral-100
                                            @endif">
                                            <svg class="h-4 w-4
                                                @if($log->action === 'CREATE') text-wwc-success
                                                @elseif($log->action === 'UPDATE') text-wwc-accent
                                                @elseif($log->action === 'DELETE') text-wwc-error
                                                @else text-wwc-neutral-600
                                                @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        <p class="text-xs font-semibold text-wwc-neutral-900 mb-1">{{ $log->description ?? $log->action . ' ' . $log->table_name }}</p>
                                        <p class="text-xs text-wwc-neutral-500">{{ $log->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-8">
                                    <div class="h-12 w-12 rounded-2xl bg-wwc-neutral-100 flex items-center justify-center mx-auto mb-3">
                                        <i class='bx bx-file text-2xl text-wwc-neutral-400'></i>
                                    </div>
                                    <p class="text-xs text-wwc-neutral-500">No recent activities</p>
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