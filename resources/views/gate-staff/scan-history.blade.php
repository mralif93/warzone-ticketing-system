@extends('layouts.app')

@section('title', 'Scan History')

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
                       class="text-red-600 px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('gate-staff.scan-history') ? 'text-red-600' : '' }}">
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
                                   class="flex items-center px-4 py-2 text-sm text-red-600 bg-red-50">
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
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-red-600 bg-red-50 border border-red-200">
                            <div class="flex items-center justify-center w-8 h-8 rounded-md bg-red-100 mr-3">
                                <i class="bx bx-history text-sm text-red-600"></i>
                            </div>
                            <span>Scan History</span>
                            <div class="ml-auto w-1.5 h-1.5 bg-red-600 rounded-full"></div>
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

    <!-- Page Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Scan History</h1>
                    <p class="text-sm text-gray-600">View and manage all ticket scans</p>
                </div>
                <div class="text-left sm:text-right">
                    <p class="text-sm text-gray-500">Current Time</p>
                    <p class="text-lg font-semibold text-gray-900" id="current-time"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 sm:mb-8">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                <p class="text-sm text-gray-500">Filter scan history by date, result, and event</p>
            </div>
            <div class="p-4 sm:p-6">
                
                <form method="GET" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bx bx-calendar mr-1"></i>
                            From Date
                        </label>
                        <input type="date" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-base">
                    </div>
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bx bx-calendar mr-1"></i>
                            To Date
                        </label>
                        <input type="date" id="date_to" name="date_to" 
                               value="{{ request('date_to') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-base">
                    </div>
                    <div>
                        <label for="result" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bx bx-check-circle mr-1"></i>
                            Result
                        </label>
                        <select id="result" name="result" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-base">
                            <option value="">All Results</option>
                            @foreach($results as $result)
                                <option value="{{ $result }}" {{ request('result') == $result ? 'selected' : '' }}>
                                    {{ $result }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="event_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bx bx-calendar-event mr-1"></i>
                            Event
                        </label>
                        <select id="event_id" name="event_id" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-base">
                            <option value="">All Events</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0 sm:space-x-4">
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <i class="bx bx-info-circle"></i>
                            <span>Use filters to narrow down your scan history</span>
                        </div>
                        <div class="flex space-x-3">
                        <a href="{{ route('gate-staff.scan-history') }}" 
                               class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                <i class="bx bx-x mr-2"></i>
                            Clear Filters
                        </a>
                        <button type="submit" 
                                    class="flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                <i class="bx bx-search mr-2"></i>
                            Apply Filters
                        </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Scan History Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Scan History</h3>
                <p class="text-sm text-gray-500">{{ $scans->total() }} total scans found</p>
            </div>
            
            @if($scans->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <i class="bx bx-time text-sm"></i>
                                        <span>Time</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <i class="bx bx-calendar-event text-sm"></i>
                                        <span>Event</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <i class="bx bx-qr text-sm"></i>
                                        <span>Ticket Details</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <i class="bx bx-check-circle text-sm"></i>
                                        <span>Result</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <span>Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($scans as $scan)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $scan->scan_time->format('M j, Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ $scan->scan_time->format('g:i A') }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $scan->ticket->event->name ?? 'Unknown Event' }}</div>
                                            <div class="text-sm text-gray-500">Event ID: {{ $scan->ticket->event->id ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        @if($scan->ticket)
                                            <div>
                                                <div class="font-medium text-gray-900">Ticket #{{ $scan->ticket->ticket_identifier ?? 'N/A' }}</div>
                                                <div class="text-sm text-gray-500">RM{{ number_format($scan->ticket->price_paid ?? 0, 0) }}</div>
                                            </div>
                                        @else
                                            <span class="text-gray-500">No ticket data</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                            @if($scan->scan_result === 'SUCCESS') bg-green-100 text-green-800
                                            @elseif($scan->scan_result === 'DUPLICATE') bg-red-100 text-red-800
                                            @elseif($scan->scan_result === 'INVALID') bg-red-100 text-red-800
                                            @elseif($scan->scan_result === 'WRONG_GATE') bg-yellow-100 text-yellow-800
                                            @elseif($scan->scan_result === 'WRONG_EVENT') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $scan->scan_result }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-sm font-medium">
                                        <button onclick="showScanDetails({{ $scan->id }})" 
                                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                            <i class="bx bx-show mr-1.5"></i>
                                            View
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Showing {{ $scans->firstItem() ?? 0 }} to {{ $scans->lastItem() ?? 0 }} of {{ $scans->total() }} results
                        </div>
                        <div>
                    {{ $scans->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="p-4 sm:p-6">
                <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bx bx-qr-scan text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No scans found</h3>
                        <p class="text-gray-500 mb-6 max-w-md mx-auto">
                            No scans match your current filters. Try adjusting your search criteria or check back later.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ route('gate-staff.scan-history') }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                <i class="bx bx-x mr-2"></i>
                                Clear Filters
                            </a>
                            <a href="{{ route('gate-staff.scanner') }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                <i class="bx bx-qr-scan mr-2"></i>
                                Start Scanning
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Scan Details Modal -->
<div id="scan-details-modal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 w-full max-w-2xl">
        <div class="bg-white rounded-xl shadow-xl">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="bx bx-info-circle text-blue-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Scan Details</h3>
                            <p class="text-sm text-gray-500">Detailed information about this scan</p>
                        </div>
                    </div>
                    <button onclick="closeScanDetails()" class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="bx bx-x text-xl"></i>
                </button>
                </div>
            </div>
            <div id="scan-details-content" class="px-6 py-6">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function showScanDetails(scanId) {
    // This would typically make an AJAX call to get scan details
    // For now, we'll show a placeholder
    document.getElementById('scan-details-content').innerHTML = `
        <div class="text-center py-4">
            <p class="text-gray-500">Scan details for ID: ${scanId}</p>
            <p class="text-sm text-gray-400 mt-2">Detailed scan information would be loaded here.</p>
        </div>
    `;
    document.getElementById('scan-details-modal').classList.remove('hidden');
}

function closeScanDetails() {
    document.getElementById('scan-details-modal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('scan-details-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeScanDetails();
    }
});

// Update current time
function updateCurrentTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', {
        hour12: true,
        hour: 'numeric',
        minute: '2-digit',
        second: '2-digit'
    });
    const timeElement = document.getElementById('current-time');
    if (timeElement) {
        timeElement.textContent = timeString;
    }
}

// Update time immediately and then every second
updateCurrentTime();
setInterval(updateCurrentTime, 1000);
</script>
@endsection
