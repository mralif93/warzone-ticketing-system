@extends('layouts.app')

@section('title', 'Gate Staff Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50" x-data="{ mobileMenuOpen: false }">
    <!-- Navigation Header -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('gate-staff.dashboard') }}" class="flex items-center">
                        <span class="text-xl sm:text-2xl font-bold text-red-600">WARZONE</span>
                    </a>
                </div>

                <!-- Navigation Links - Desktop -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('gate-staff.dashboard') }}" 
                       class="text-gray-600 hover:text-red-600 px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('gate-staff.dashboard') ? 'text-red-600' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('gate-staff.scanner') }}" 
                       class="text-gray-600 hover:text-red-600 px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('gate-staff.scanner') ? 'text-red-600' : '' }}">
                        Scanner
                    </a>
                    <a href="{{ route('gate-staff.scan-history') }}" 
                       class="text-gray-600 hover:text-red-600 px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('gate-staff.scan-history') ? 'text-red-600' : '' }}">
                        Scan History
                    </a>
                </div>

                <!-- User Menu - Desktop -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-sm focus:outline-none">
                            <div class="h-8 w-8 rounded-full bg-red-600 flex items-center justify-center">
                                <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <span class="ml-2 text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                            <i class='bx bx-chevron-down text-sm text-gray-500'></i>
                        </button>

                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200">
                            <!-- User Info -->
                            <div class="px-4 py-2 border-b border-gray-100">
                                <div class="flex items-center space-x-2">
                                    <div class="h-8 w-8 rounded-full bg-red-600 flex items-center justify-center">
                                        <span class="text-sm font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500">Gate Staff</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Navigation Links -->
                            <div class="py-1">
                                <a href="{{ route('gate-staff.dashboard') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                                    <i class='bx bx-home text-base mr-3'></i>
                                    Dashboard
                                </a>
                                <a href="{{ route('gate-staff.scanner') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                                    <i class='bx bx-qr-scan text-base mr-3'></i>
                                    QR Scanner
                                </a>
                                <a href="{{ route('gate-staff.scan-history') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                                    <i class='bx bx-history text-base mr-3'></i>
                                    Scan History
                                </a>
                            </div>
                            
                            <!-- Sign Out -->
                            <div class="border-t border-gray-100 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                                        <i class='bx bx-log-out text-base mr-3'></i>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center space-x-2">
                    <!-- Mobile User Avatar -->
                    <div class="h-8 w-8 rounded-full bg-red-600 flex items-center justify-center">
                        <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-gray-900 p-2">
                        <i class='bx bx-menu text-2xl'></i>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200" 
                 x-transition:enter-start="opacity-0 -translate-y-1" 
                 x-transition:enter-end="opacity-100 translate-y-0" 
                 x-transition:leave="transition ease-in duration-150" 
                 x-transition:leave-start="opacity-100 translate-y-0" 
                 x-transition:leave-end="opacity-0 -translate-y-1" 
                 class="md:hidden absolute top-full left-0 right-0 bg-white border-t border-gray-200 shadow-md z-50">
                <div class="px-3 py-2 space-y-1">
                    <!-- User Profile Section -->
                    <div class="px-3 py-2 bg-gradient-to-r from-red-50 to-red-100 rounded-lg border border-red-200 mb-2">
                        <div class="flex items-center space-x-2">
                            <div class="h-8 w-8 rounded-full bg-red-600 flex items-center justify-center">
                                <span class="text-sm font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-red-600 font-medium">Gate Staff</p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="space-y-0.5">
                        <a href="{{ route('gate-staff.dashboard') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('gate-staff.dashboard') ? 'text-red-600 bg-red-50 border border-red-200' : 'text-gray-700 hover:text-red-600 hover:bg-gray-50' }}">
                            <div class="flex items-center justify-center w-8 h-8 rounded-md {{ request()->routeIs('gate-staff.dashboard') ? 'bg-red-100' : 'bg-gray-100' }} mr-3">
                                <i class="bx bx-home-alt-2 text-sm {{ request()->routeIs('gate-staff.dashboard') ? 'text-red-600' : 'text-gray-600' }}"></i>
                            </div>
                            <span>Dashboard</span>
                            @if(request()->routeIs('gate-staff.dashboard'))
                                <div class="ml-auto w-1.5 h-1.5 bg-red-600 rounded-full"></div>
                            @endif
                        </a>

                        <a href="{{ route('gate-staff.scanner') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('gate-staff.scanner') ? 'text-red-600 bg-red-50 border border-red-200' : 'text-gray-700 hover:text-red-600 hover:bg-gray-50' }}">
                            <div class="flex items-center justify-center w-8 h-8 rounded-md {{ request()->routeIs('gate-staff.scanner') ? 'bg-red-100' : 'bg-gray-100' }} mr-3">
                                <i class="bx bx-qr-scan text-sm {{ request()->routeIs('gate-staff.scanner') ? 'text-red-600' : 'text-gray-600' }}"></i>
                            </div>
                            <span>Scanner</span>
                            @if(request()->routeIs('gate-staff.scanner'))
                                <div class="ml-auto w-1.5 h-1.5 bg-red-600 rounded-full"></div>
                            @endif
                        </a>

                        <a href="{{ route('gate-staff.scan-history') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('gate-staff.scan-history') ? 'text-red-600 bg-red-50 border border-red-200' : 'text-gray-700 hover:text-red-600 hover:bg-gray-50' }}">
                            <div class="flex items-center justify-center w-8 h-8 rounded-md {{ request()->routeIs('gate-staff.scan-history') ? 'bg-red-100' : 'bg-gray-100' }} mr-3">
                                <i class="bx bx-history text-sm {{ request()->routeIs('gate-staff.scan-history') ? 'text-red-600' : 'text-gray-600' }}"></i>
                            </div>
                            <span>Scan History</span>
                            @if(request()->routeIs('gate-staff.scan-history'))
                                <div class="ml-auto w-1.5 h-1.5 bg-red-600 rounded-full"></div>
                            @endif
                        </a>
                    </div>

                    <!-- Sign Out Section -->
                    <div class="pt-2 border-t border-gray-200">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center w-full px-3 py-2.5 text-sm font-medium text-gray-700 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200 group">
                                <div class="flex items-center justify-center w-8 h-8 rounded-md bg-gray-100 group-hover:bg-red-100 mr-3 transition-colors duration-200">
                                    <i class="bx bx-log-out text-sm text-gray-600 group-hover:text-red-600 transition-colors duration-200"></i>
                                </div>
                                <span>Sign Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Dashboard Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Gate Staff Dashboard</h1>
                    <p class="text-sm text-gray-600">Welcome back, {{ Auth::user()->name }}</p>
                </div>
                <div class="text-left sm:text-right">
                        <p class="text-sm text-gray-500">Current Time</p>
                        <p class="text-lg font-semibold text-gray-900" id="current-time"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
        <!-- Today's Statistics -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="bx bx-qr-scan text-blue-600 text-sm sm:text-base"></i>
                        </div>
                    </div>
                    <div class="ml-3 sm:ml-4 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Scans</p>
                        <p class="text-lg sm:text-2xl font-semibold text-gray-900">{{ $todayStats['total_scans'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="bx bx-check-circle text-green-600 text-sm sm:text-base"></i>
                        </div>
                    </div>
                    <div class="ml-3 sm:ml-4 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Successful</p>
                        <p class="text-lg sm:text-2xl font-semibold text-gray-900">{{ $todayStats['successful_scans'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="bx bx-x-circle text-red-600 text-sm sm:text-base"></i>
                        </div>
                    </div>
                    <div class="ml-3 sm:ml-4 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Duplicates</p>
                        <p class="text-lg sm:text-2xl font-semibold text-gray-900">{{ $todayStats['duplicate_scans'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="bx bx-trending-up text-yellow-600 text-sm sm:text-base"></i>
                        </div>
                    </div>
                    <div class="ml-3 sm:ml-4 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Success Rate</p>
                        <p class="text-lg sm:text-2xl font-semibold text-gray-900">{{ $todayStats['success_rate'] }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    <p class="text-sm text-gray-500">Start scanning tickets or manage your workflow</p>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('gate-staff.scanner') }}" 
                           class="flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                            <i class="bx bx-qr-scan text-sm mr-2"></i>
                            <span class="text-sm">Start Scanning</span>
                        </a>
                        <a href="{{ route('gate-staff.scan-history') }}" 
                           class="flex items-center justify-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                            <i class="bx bx-history text-sm mr-2"></i>
                            <span class="text-sm">View Scan History</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Scans -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Scans</h3>
            </div>
            <div class="overflow-x-auto">
                @if($recentScans->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <i class="bx bx-time text-sm"></i>
                                        <span>Time</span>
                                    </div>
                                </th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <i class="bx bx-calendar-event text-sm"></i>
                                        <span>Event</span>
                                    </div>
                                </th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <i class="bx bx-purchase-tag text-sm"></i>
                                        <span>Ticket Type</span>
                                    </div>
                                </th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <i class="bx bx-check-circle text-sm"></i>
                                        <span>Result</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentScans as $scan)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-4 sm:px-6 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $scan->scan_time->format('M j, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $scan->scan_time->format('g:i A') }}</div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $scan->ticket->event->name ?? 'Unknown Event' }}</div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-3 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $scan->ticket->ticketType->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-3 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                            @if(strtolower($scan->scan_result) === 'success') bg-green-100 text-green-800
                                            @elseif(strtolower($scan->scan_result) === 'duplicate') bg-red-100 text-red-800
                                            @elseif(strtolower($scan->scan_result) === 'invalid') bg-red-100 text-red-800
                                            @elseif(strtolower($scan->scan_result) === 'wrong_gate') bg-yellow-100 text-yellow-800
                                            @elseif(strtolower($scan->scan_result) === 'wrong_event') bg-yellow-100 text-yellow-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ str_replace('_', '', ucwords(str_replace('_', ' ', strtolower($scan->scan_result)))) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50 text-center">
                        <a href="{{ route('gate-staff.scan-history') }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                            <i class="bx bx-history mr-2"></i>
                            View All Scans
                        </a>
                    </div>
                @else
                    <div class="p-4 sm:p-6 text-center py-6 sm:py-8">
                        <i class="bx bx-qr-scan text-3xl sm:text-4xl text-gray-400 mb-3 sm:mb-4"></i>
                        <p class="text-gray-500 text-sm sm:text-base">No scans yet today</p>
                    </div>
                @endif
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
