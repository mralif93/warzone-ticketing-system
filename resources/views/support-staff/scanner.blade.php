@extends('layouts.app')

@section('title', 'Ticket Scanner & Search')

@section('content')
<div class="min-h-screen bg-gray-50" x-data="scanner()">
    <!-- Navigation Header -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('support-staff.dashboard') }}" class="flex items-center">
                        <span class="text-xl sm:text-2xl font-bold text-red-600">WARZONE</span>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('support-staff.dashboard') }}" 
                       class="text-gray-600 hover:text-red-600 px-3 py-2 text-sm font-medium transition-colors duration-200">
                        Dashboard
                    </a>
                    <a href="{{ route('support-staff.scanner') }}" 
                       class="text-gray-600 hover:text-red-600 px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('support-staff.scanner') ? 'text-red-600' : '' }}">
                        Scanner & Search
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-sm focus:outline-none">
                            <div class="h-8 w-8 rounded-full bg-red-600 flex items-center justify-center">
                                <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <span class="ml-2 text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                            <i class='bx bx-chevron-down text-sm text-gray-500'></i>
                        </button>

                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="md:hidden flex items-center space-x-2">
                    <div class="h-8 w-8 rounded-full bg-red-600 flex items-center justify-center">
                        <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Ticket Scanner & Search</h1>
            <p class="text-sm text-gray-600">Handle attendance problems and scan tickets</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Search Tickets -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Search Tickets</h2>
                    <p class="text-sm text-gray-600">Search by QR code or ticket ID</p>
                </div>
                <div class="p-6">
                    <form @submit.prevent="searchTicket" class="mb-4">
                        <div class="flex gap-3">
                            <input type="text" 
                                   x-model="searchQuery"
                                   placeholder="Enter QR code or ticket ID" 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <button type="submit" 
                                    class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                                <i class="bx bx-search mr-2"></i>
                                Search
                            </button>
                        </div>
                    </form>

                    <!-- Search Results -->
                    <div x-show="searchResult" x-transition>
                        <div class="border border-gray-200 rounded-lg p-4" :class="searchResult && searchResult.success ? 'bg-green-50' : 'bg-red-50'">
                            <template x-if="searchResult && searchResult.success">
                                <div>
                                    <div class="flex items-center mb-3">
                                        <i class="bx bx-check-circle text-green-600 text-2xl mr-2"></i>
                                        <h3 class="text-lg font-semibold text-gray-900">Ticket Found</h3>
                                    </div>
                                    <div class="space-y-2" x-show="searchResult.ticket">
                                        <p class="text-sm"><strong>Event:</strong> <span x-text="searchResult.ticket.event ? searchResult.ticket.event.name : 'N/A'"></span></p>
                                        <p class="text-sm"><strong>Ticket Type:</strong> <span x-text="searchResult.ticket.ticketType ? searchResult.ticket.ticketType.name : 'N/A'"></span></p>
                                        <p class="text-sm"><strong>Status:</strong> <span x-text="searchResult.ticket.status"></span></p>
                                        <p class="text-sm"><strong>Customer:</strong> <span x-text="searchResult.ticket.order && searchResult.ticket.order.user ? searchResult.ticket.order.user.name : 'N/A'"></span></p>
                                        
                                        <!-- Scan History -->
                                        <div class="mt-4 pt-4 border-t border-gray-300">
                                            <h4 class="text-sm font-semibold mb-2">Scan History</h4>
                                            <template x-if="searchResult.scanHistory && searchResult.scanHistory.length > 0">
                                                <div class="space-y-2">
                                                    <template x-for="scan in searchResult.scanHistory" :key="scan.id">
                                                        <div class="text-xs bg-white p-2 rounded">
                                                            <span x-text="scan.scan_time"></span> - <span x-text="scan.scan_result"></span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                            <p x-show="!searchResult.scanHistory || searchResult.scanHistory.length === 0" class="text-xs text-gray-500">No scan history</p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template x-if="searchResult && !searchResult.success">
                                <div>
                                    <div class="flex items-center mb-2">
                                        <i class="bx bx-error-circle text-red-600 text-2xl mr-2"></i>
                                        <h3 class="text-lg font-semibold text-red-900">Ticket Not Found</h3>
                                    </div>
                                    <p class="text-sm text-red-700" x-text="searchResult.message"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code Scanner -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Scan Ticket QR Code</h2>
                    <p class="text-sm text-gray-600">Scan or manually enter QR code</p>
                </div>
                <div class="p-6">
                    <!-- Event Selection -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Event</label>
                        <select x-model="selectedEventId" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">Select an event...</option>
                            @foreach($todayEvents as $todayEvent)
                                <option value="{{ $todayEvent->id }}">{{ $todayEvent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- QR Code Input -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">QR Code</label>
                        <input type="text" 
                               x-model="qrCode"
                               placeholder="Enter or scan QR code" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>

                    <button @click="scanTicket" 
                            :disabled="!selectedEventId || !qrCode"
                            class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors">
                        <i class="bx bx-qr-scan mr-2"></i>
                        Scan Ticket
                    </button>

                    <!-- Scan Result -->
                    <div x-show="scanResult" x-transition class="mt-4">
                        <div class="border rounded-lg p-4" 
                             :class="scanResult && scanResult.success ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'">
                            <template x-if="scanResult && scanResult.success">
                                <div>
                                    <div class="flex items-center mb-2">
                                        <i class="bx bx-check-circle text-green-600 text-2xl mr-2"></i>
                                        <h3 class="font-semibold text-green-900" x-text="scanResult.scan_result"></h3>
                                    </div>
                                    <p class="text-sm text-green-700" x-text="scanResult.message"></p>
                                </div>
                            </template>
                            <template x-if="scanResult && !scanResult.success">
                                <div>
                                    <div class="flex items-center mb-2">
                                        <i class="bx bx-error-circle text-red-600 text-2xl mr-2"></i>
                                        <h3 class="font-semibold text-red-900" x-text="scanResult.scan_result"></h3>
                                    </div>
                                    <p class="text-sm text-red-700" x-text="scanResult.message"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function scanner() {
            return {
                searchQuery: '',
                qrCode: '',
                selectedEventId: '',
                searchResult: null,
                scanResult: null,

                async searchTicket() {
                    if (!this.searchQuery) return;
                    
                    try {
                        const response = await fetch('{{ route("support-staff.search-ticket") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ search: this.searchQuery })
                        });

                        const data = await response.json();
                        this.searchResult = data;
                    } catch (error) {
                        console.error('Search error:', error);
                        this.searchResult = { success: false, message: 'Error searching for ticket' };
                    }
                },

                async scanTicket() {
                    if (!this.selectedEventId || !this.qrCode) return;
                    
                    try {
                        const response = await fetch('{{ route("support-staff.scan-ticket") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                qr_code: this.qrCode,
                                event_id: this.selectedEventId,
                                gate_id: 'SUPPORT-1'
                            })
                        });

                        const data = await response.json();
                        this.scanResult = data;
                        
                        // Clear QR code after successful scan
                        if (data.success) {
                            this.qrCode = '';
                        }
                    } catch (error) {
                        console.error('Scan error:', error);
                        this.scanResult = { success: false, message: 'Error scanning ticket', scan_result: 'ERROR' };
                    }
                }
            }
        }
    </script>
</div>
@endsection

