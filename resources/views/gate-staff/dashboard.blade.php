@extends('layouts.app')

@section('title', 'Gate Staff Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Gate Staff Dashboard</h1>
                    <p class="text-sm text-gray-600">Welcome back, {{ Auth::user()->name }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Current Time</p>
                        <p class="text-lg font-semibold text-gray-900" id="current-time"></p>
                    </div>
                    <a href="{{ route('gate-staff.scanner') }}" 
                       class="bg-wwc-primary hover:bg-wwc-primary-dark text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        <i class="bx bx-qr-scan mr-2"></i>
                        Start Scanning
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Today's Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="bx bx-qr-scan text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Scans</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $todayStats['total_scans'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="bx bx-check-circle text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Successful</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $todayStats['successful_scans'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="bx bx-x-circle text-red-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Duplicates</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $todayStats['duplicate_scans'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="bx bx-trending-up text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Success Rate</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $todayStats['success_rate'] }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Today's Events -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Today's Events</h3>
                </div>
                <div class="p-6">
                    @if($todayEvents->count() > 0)
                        <div class="space-y-4">
                            @foreach($todayEvents as $event)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $event->name }}</h4>
                                        <p class="text-sm text-gray-600">
                                            <i class="bx bx-time mr-1"></i>
                                            {{ \Carbon\Carbon::parse($event->event_date)->format('g:i A') }}
                                        </p>
                                    </div>
                                    <a href="{{ route('gate-staff.scanner', ['event_id' => $event->id]) }}" 
                                       class="bg-wwc-primary hover:bg-wwc-primary-dark text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        Scan Tickets
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="bx bx-calendar-x text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500">No events scheduled for today</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Scans -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Scans</h3>
                </div>
                <div class="p-6">
                    @if($recentScans->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentScans as $scan)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3
                                            @if($scan->scan_result === 'SUCCESS') bg-green-100 text-green-600
                                            @elseif($scan->scan_result === 'DUPLICATE') bg-red-100 text-red-600
                                            @elseif($scan->scan_result === 'INVALID') bg-red-100 text-red-600
                                            @else bg-yellow-100 text-yellow-600
                                            @endif">
                                            @if($scan->scan_result === 'SUCCESS')
                                                <i class="bx bx-check text-sm"></i>
                                            @elseif($scan->scan_result === 'DUPLICATE')
                                                <i class="bx bx-x text-sm"></i>
                                            @else
                                                <i class="bx bx-error text-sm"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $scan->ticket->event->name ?? 'Unknown Event' }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $scan->scan_time->format('g:i A') }} • {{ $scan->gate_id }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-medium px-2 py-1 rounded-full
                                        @if($scan->scan_result === 'SUCCESS') bg-green-100 text-green-800
                                        @elseif($scan->scan_result === 'DUPLICATE') bg-red-100 text-red-800
                                        @elseif($scan->scan_result === 'INVALID') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ $scan->scan_result }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('gate-staff.scan-history') }}" 
                               class="text-wwc-primary hover:text-wwc-primary-dark text-sm font-medium">
                                View All Scans
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="bx bx-qr-scan text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500">No scans yet today</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update current time every second
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', {
        hour12: true,
        hour: 'numeric',
        minute: '2-digit',
        second: '2-digit'
    });
    document.getElementById('current-time').textContent = timeString;
}

updateTime();
setInterval(updateTime, 1000);
</script>
@endsection
