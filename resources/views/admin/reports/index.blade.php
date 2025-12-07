@extends('layouts.admin')

@section('title', 'Reports & Analytics')
@section('page-subtitle', 'Business insights and performance metrics')

@section('content')
<!-- Professional Reports & Analytics with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">
    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <!-- Total Revenue -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">RM{{ number_format($totalRevenue, 0) }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Revenue</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    {{ $revenueData->count() }} days
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class='bx bx-dollar text-2xl text-green-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Tickets Sold -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $ticketSalesByEvent->sum('count') }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Tickets Sold</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <i class='bx bx-check text-xs mr-1'></i>
                                    {{ $ticketSalesByEvent->count() }} events
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-receipt text-2xl text-blue-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $usersByRole->sum('count') }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Users</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-warning font-semibold">
                                    <i class='bx bx-time text-xs mr-1'></i>
                                    {{ $userRegistrations->sum('count') }} new
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-purple-100 flex items-center justify-center">
                            <i class='bx bx-user text-2xl text-purple-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Active Events -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ \App\Models\Event::where('status', 'on_sale')->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Active Events</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-accent font-semibold">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    {{ \App\Models\Event::count() }} total
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                            <i class='bx bx-calendar text-2xl text-yellow-600'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Search & Filter Reports</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-search text-sm'></i>
                            <span>Find specific report data</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label for="date_from" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">From Date</label>
                            <input type="date" name="date_from" id="date_from" value="{{ $dateFrom }}"
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div>
                            <label for="date_to" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">To Date</label>
                            <input type="date" name="date_to" id="date_to" value="{{ $dateTo }}"
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div>
                            <label for="export_type" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Export Type</label>
                            <select name="export_type" id="export_type" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="revenue">Revenue Report</option>
                                <option value="tickets">Ticket Sales</option>
                                <option value="users">User Registrations</option>
                                <option value="events">Event Performance</option>
                            </select>
                        </div>
                        <div>
                            <label for="report_type" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Report Type</label>
                            <select name="report_type" id="report_type" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="overview">Overview</option>
                                <option value="detailed">Detailed</option>
                                <option value="summary">Summary</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2 lg:col-span-4 flex justify-end gap-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-search text-sm mr-2'></i>
                                Search Reports
                            </button>
                            <a href="{{ route('admin.reports') }}" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Clear Filters
                            </a>
                            <button type="button" id="exportBtn"
                                    class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-download text-sm mr-2'></i>
                                Export
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Revenue and User Registration Charts -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
                <!-- Revenue Chart -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-100">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Revenue Over Time</h3>
                    </div>
                    <div class="p-6">
                        @if($revenueData->count() > 0)
                            <div class="space-y-3">
                                @foreach($revenueData->take(10) as $data)
                                <div class="flex items-center justify-between py-2 border-b border-wwc-neutral-100 last:border-b-0">
                                    <div class="text-sm text-wwc-neutral-900">{{ \Carbon\Carbon::parse($data->date)->format('M d, Y') }}</div>
                                    <div class="text-sm font-semibold text-wwc-neutral-900">RM{{ number_format($data->total, 0) }}</div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="mx-auto h-12 w-12 rounded-full bg-wwc-neutral-100 flex items-center justify-center mb-4">
                                    <i class='bx bx-trending-up text-2xl text-wwc-neutral-400'></i>
                                </div>
                                <p class="text-sm text-wwc-neutral-500">No revenue data for the selected period</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- User Registrations Chart -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-100">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">User Registrations</h3>
                    </div>
                    <div class="p-6">
                        @if($userRegistrations->count() > 0)
                            <div class="space-y-3">
                                @foreach($userRegistrations->take(10) as $data)
                                <div class="flex items-center justify-between py-2 border-b border-wwc-neutral-100 last:border-b-0">
                                    <div class="text-sm text-wwc-neutral-900">{{ \Carbon\Carbon::parse($data->date)->format('M d, Y') }}</div>
                                    <div class="text-sm font-semibold text-wwc-neutral-900">{{ $data->count }} users</div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="mx-auto h-12 w-12 rounded-full bg-wwc-neutral-100 flex items-center justify-center mb-4">
                                    <i class='bx bx-user-plus text-2xl text-wwc-neutral-400'></i>
                                </div>
                                <p class="text-sm text-wwc-neutral-500">No registration data for the selected period</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ticket Sales by Event -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Ticket Sales by Event</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-receipt text-sm'></i>
                            <span>{{ $ticketSalesByEvent->count() }} events</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @if($ticketSalesByEvent->count() > 0)
                        <div class="space-y-4">
                            @foreach($ticketSalesByEvent as $event)
                            <div class="flex items-center justify-between py-3 border-b border-wwc-neutral-100 last:border-b-0">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-lg bg-wwc-primary-light flex items-center justify-center mr-3">
                                        <i class='bx bx-calendar text-lg text-wwc-primary'></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-wwc-neutral-900">{{ $event->title }}</div>
                                        <div class="text-xs text-wwc-neutral-500">{{ $event->event_date->format('M d, Y') }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-semibold text-wwc-neutral-900">{{ $event->count }} tickets</div>
                                    <div class="text-xs text-wwc-neutral-500">RM{{ number_format($event->revenue, 0) }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto h-12 w-12 rounded-full bg-wwc-neutral-100 flex items-center justify-center mb-4">
                                <i class='bx bx-receipt text-2xl text-wwc-neutral-400'></i>
                            </div>
                            <p class="text-sm text-wwc-neutral-500">No ticket sales data for the selected period</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- User Distribution and Top Customers -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
                <!-- User Distribution -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-100">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">User Distribution by Role</h3>
                    </div>
                    <div class="p-6">
                        @if($usersByRole->count() > 0)
                            <div class="space-y-4">
                                @foreach($usersByRole as $role)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-3 w-3 rounded-full
                                            @if($role->role === 'administrator') bg-red-400
                                            @elseif($role->role === 'gate_staff') bg-blue-400
                                            @elseif($role->role === 'counter_staff') bg-green-400
                                            @elseif($role->role === 'support_staff') bg-yellow-400
                                            @else bg-gray-400
                                            @endif">
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-semibold text-wwc-neutral-900">{{ ucwords(str_replace('_', ' ', $role->role)) }}</div>
                                        </div>
                                    </div>
                                    <div class="text-sm font-semibold text-wwc-neutral-900">{{ $role->count }} users</div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="mx-auto h-12 w-12 rounded-full bg-wwc-neutral-100 flex items-center justify-center mb-4">
                                    <i class='bx bx-user text-2xl text-wwc-neutral-400'></i>
                                </div>
                                <p class="text-sm text-wwc-neutral-500">No user data available</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Top Customers -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-100">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Top Customers by Spending</h3>
                    </div>
                    <div class="p-6">
                        @if($topCustomers->count() > 0)
                            <div class="space-y-4">
                                @foreach($topCustomers->take(5) as $customer)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-wwc-primary-light flex items-center justify-center mr-3">
                                            <i class='bx bx-user text-sm text-wwc-primary'></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-wwc-neutral-900">{{ $customer->name }}</div>
                                            <div class="text-xs text-wwc-neutral-500">{{ $customer->orders_count }} orders</div>
                                        </div>
                                    </div>
                                    <div class="text-sm font-semibold text-wwc-neutral-900">RM{{ number_format($customer->orders_sum_total_amount, 0) }}</div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="mx-auto h-12 w-12 rounded-full bg-wwc-neutral-100 flex items-center justify-center mb-4">
                                    <i class='bx bx-star text-2xl text-wwc-neutral-400'></i>
                                </div>
                                <p class="text-sm text-wwc-neutral-500">No customer data available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Monthly Trends -->
            @if(isset($monthlyTrends) && $monthlyTrends->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-100">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Monthly Trends (Last 12 Months)</h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-wwc-neutral-200">
                                <thead class="bg-wwc-neutral-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Month</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Revenue (RM)</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">Tickets</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">New Users</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-wwc-neutral-200">
                                    @foreach($monthlyTrends as $trend)
                                    <tr class="hover:bg-wwc-neutral-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-wwc-neutral-900">{{ $trend['month'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-900 text-right">RM{{ number_format($trend['revenue'], 0) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-900 text-right">{{ $trend['tickets'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-wwc-neutral-900 text-right">{{ $trend['users'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const exportBtn = document.getElementById('exportBtn');
    const exportTypeSelect = document.getElementById('export_type');
    const dateFromInput = document.getElementById('date_from');
    const dateToInput = document.getElementById('date_to');
    
    exportBtn.addEventListener('click', function() {
        const exportType = exportTypeSelect.value;
        const dateFrom = dateFromInput.value;
        const dateTo = dateToInput.value;
        
        const exportUrl = "{{ route('admin.reports.export') }}" + 
            "?type=" + encodeURIComponent(exportType) + 
            "&date_from=" + encodeURIComponent(dateFrom) + 
            "&date_to=" + encodeURIComponent(dateTo);
        
        window.location.href = exportUrl;
    });
});
</script>
@endsection