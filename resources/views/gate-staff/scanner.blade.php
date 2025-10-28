@extends('layouts.app')

@section('title', 'QR Code Scanner')

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
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200">
                            <a href="{{ route('gate-staff.dashboard') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Dashboard
                            </a>
                            <a href="{{ route('gate-staff.scanner') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Scanner
                            </a>
                            <a href="{{ route('gate-staff.scan-history') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Scan History
                            </a>
                            <div class="border-t border-gray-200"></div>
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

    <!-- Page Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">QR Code Scanner</h1>
                    <p class="text-sm text-gray-600">Scan customer tickets for entry</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-left sm:text-right">
                        <p class="text-sm text-gray-500">Current Time</p>
                        <p class="text-lg font-semibold text-gray-900" id="current-time"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
        <!-- Scanner Interface Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Ticket Scanner</h3>
                <p class="text-sm text-gray-500">Scan QR codes or enter ticket IDs manually</p>
            </div>
            <div class="p-4 sm:p-6">
                <!-- QR Code Input -->
                <div class="mb-6">
                    <label for="qrcode_input" class="block text-sm font-medium text-gray-700 mb-2">
                        QR Code / Ticket ID
                    </label>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                        <input type="text" id="qrcode_input" name="qrcode" 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm"
                               placeholder="Scan or enter QR code..."
                               autocomplete="off">
                        <div class="flex space-x-2">
                            <button type="button" id="scan_button" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center flex-1 sm:flex-none">
                                <i class="bx bx-qr-scan mr-1.5 text-sm"></i>
                                <span class="text-sm">Scan</span>
                            </button>
                            <button type="button" id="camera_scan_button" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center flex-1 sm:flex-none">
                                <i class="bx bx-camera mr-1.5 text-sm"></i>
                                <span class="text-sm">Camera</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Camera Status Notice -->
                <div id="camera_status" class="hidden mt-4 p-4 rounded-md">
                    <div class="flex items-start">
                        <i id="camera_status_icon" class="text-lg mr-3 mt-0.5"></i>
                        <div class="text-sm">
                            <p id="camera_status_title" class="font-medium"></p>
                            <p id="camera_status_message"></p>
                            <div id="camera_status_actions" class="mt-3 space-y-2 hidden">
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Recommended</span>
                                    <span class="text-xs text-gray-600">Use manual input above - it's faster and more reliable!</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button id="camera_retry_btn" class="text-blue-600 hover:text-blue-800 underline text-xs">
                                        🔄 Retry Camera
                                    </button>
                                    <span class="text-xs text-gray-500">•</span>
                                    <button id="camera_help_btn" class="text-blue-600 hover:text-blue-800 underline text-xs">
                                        ❓ Get Help
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Camera Scanner Modal -->
                <div id="camera_modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-lg p-4 sm:p-6 max-w-md w-full">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Camera Scanner</h3>
                            <button type="button" id="close_camera" class="text-gray-400 hover:text-gray-600 p-2">
                                <i class="bx bx-x text-2xl"></i>
                            </button>
                        </div>
                        
                        <!-- Device Detection Info -->
                        <div id="device_info" class="mb-4 p-3 bg-gray-50 rounded-lg text-xs">
                            <div class="flex items-center space-x-4">
                                <span id="device_type" class="font-medium"></span>
                                <span id="browser_type" class="font-medium"></span>
                                <span id="camera_status_indicator" class="px-2 py-1 rounded text-xs"></span>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div id="camera_container" class="relative">
                                <video id="camera_video" class="w-full h-48 sm:h-64 bg-gray-100 rounded-lg" autoplay muted playsinline style="display: none;"></video>
                                <canvas id="camera_canvas" class="hidden"></canvas>
                                <div id="camera_overlay" class="absolute inset-0 border-2 border-red-500 rounded-lg pointer-events-none hidden">
                                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-32 h-32 sm:w-48 sm:h-48 border-2 border-red-500 rounded-lg"></div>
                                </div>
                                <div id="camera_placeholder" class="w-full h-48 sm:h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <div class="text-center text-gray-500">
                                        <i class="bx bx-camera text-4xl mb-2"></i>
                                        <p class="text-sm">Camera will appear here</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-3">Position the QR code within the frame</p>
                            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 sm:justify-center">
                                <button type="button" id="start_camera" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center">
                                    <i class="bx bx-play mr-1.5 text-sm"></i>
                                    <span class="text-sm">Start Camera</span>
                                </button>
                                <button type="button" id="stop_camera" 
                                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center hidden">
                                    <i class="bx bx-stop mr-1.5 text-sm"></i>
                                    <span class="text-sm">Stop Camera</span>
                        </button>
                            </div>
                        </div>
                        
                        <!-- Camera Help Section -->
                        <div id="camera_help" class="mt-4 p-3 bg-yellow-50 rounded-lg text-xs hidden">
                            <h4 class="font-medium text-yellow-800 mb-2">Camera Help:</h4>
                            <div id="camera_help_content"></div>
                        </div>
                    </div>
                </div>

                <!-- Scan Result Display -->
                <div id="scan_result" class="hidden">
                    <div class="p-4 sm:p-6 rounded-xl border-2 bg-gray-50">
                        <div class="flex items-center justify-center mb-4 sm:mb-6">
                            <div id="result_icon" class="w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center text-3xl sm:text-4xl"></div>
                        </div>
                        <h4 id="result_title" class="text-xl sm:text-2xl font-bold text-center mb-3"></h4>
                        <p id="result_message" class="text-center text-gray-600 mb-4 sm:mb-6 text-base sm:text-lg"></p>
                        
                        <!-- Ticket Details (for successful scans) -->
                        <div id="ticket_details" class="hidden bg-white rounded-lg p-4 sm:p-6 border border-gray-200">
                            <h5 class="font-semibold text-gray-900 mb-4 text-base sm:text-lg">Ticket Details</h5>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                                <div class="text-center p-3 sm:p-4 bg-gray-50 rounded-lg">
                                    <p class="text-xs sm:text-sm text-gray-500 mb-1">Event</p>
                                    <p id="ticket_event" class="font-semibold text-gray-900 text-sm sm:text-base"></p>
                                </div>
                                <div class="text-center p-3 sm:p-4 bg-gray-50 rounded-lg">
                                    <p class="text-xs sm:text-sm text-gray-500 mb-1">Ticket ID</p>
                                    <p id="ticket_zone" class="font-semibold text-gray-900 text-sm sm:text-base"></p>
                                </div>
                                <div class="text-center p-3 sm:p-4 bg-gray-50 rounded-lg">
                                    <p class="text-xs sm:text-sm text-gray-500 mb-1">Price</p>
                                    <p id="ticket_price" class="font-semibold text-gray-900 text-sm sm:text-base"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Performance Info -->
                        <div class="text-center text-xs sm:text-sm text-gray-500 mt-4 sm:mt-6">
                            <span id="performance_time"></span>
                        </div>
                    </div>
                </div>

                <!-- Camera Troubleshooting -->
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="bx bx-info-circle text-blue-600 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-blue-800">Camera Not Working?</h4>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Make sure to allow camera permissions when prompted</li>
                                    <li>Close other apps that might be using the camera</li>
                                    <li>Try refreshing the page if camera doesn't start</li>
                                    <li>Ensure you're using a modern browser (Chrome, Firefox, Safari)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row justify-center gap-2 sm:gap-3">
                    <button type="button" id="clear_button" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center">
                        <i class="bx bx-refresh mr-1.5 text-sm"></i>
                        <span class="text-sm">Clear</span>
                    </button>
                    <a href="{{ route('gate-staff.scan-history') }}" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center">
                        <i class="bx bx-history mr-1.5 text-sm"></i>
                        <span class="text-sm">View History</span>
                    </a>
                    <a href="{{ route('gate-staff.dashboard') }}" 
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center">
                        <i class="bx bx-home mr-1.5 text-sm"></i>
                        <span class="text-sm">Back to Dashboard</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const qrcodeInput = document.getElementById('qrcode_input');
    const scanButton = document.getElementById('scan_button');
    const clearButton = document.getElementById('clear_button');
    const scanResult = document.getElementById('scan_result');
    
    // Camera scanner elements
    const cameraScanButton = document.getElementById('camera_scan_button');
    const cameraModal = document.getElementById('camera_modal');
    const closeCameraButton = document.getElementById('close_camera');
    const startCameraButton = document.getElementById('start_camera');
    const stopCameraButton = document.getElementById('stop_camera');
    const cameraVideo = document.getElementById('camera_video');
    const cameraCanvas = document.getElementById('camera_canvas');
    
    let qrScanner = null;
    let html5QrCode = null;
    let zxingScanner = null;
    let cameraStream = null;

    // Auto-focus on QR code input
    qrcodeInput.focus();

    // Handle scan button click
    scanButton.addEventListener('click', function() {
        const qrcode = qrcodeInput.value.trim();

        if (!qrcode) {
            alert('Please enter a QR code or ticket ID');
            qrcodeInput.focus();
            return;
        }

        // Default values for event and gate
        const eventId = null;
        const gateId = 'GATE-1';
        scanTicket(qrcode, eventId, gateId);
    });

    // Handle Enter key in QR code input
    qrcodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            scanButton.click();
        }
    });

    // Handle clear button
    clearButton.addEventListener('click', function() {
        qrcodeInput.value = '';
        scanResult.classList.add('hidden');
        qrcodeInput.focus();
    });

    // Device and browser detection
    function detectDeviceAndBrowser() {
        const userAgent = navigator.userAgent;
        const isIOS = /iPad|iPhone|iPod/.test(userAgent);
        const isAndroid = /Android/.test(userAgent);
        const isSafari = /Safari/.test(userAgent) && !/Chrome/.test(userAgent);
        const isChrome = /Chrome/.test(userAgent);
        const isMobile = /Mobile/.test(userAgent);
        const isHTTPS = location.protocol === 'https:';
        const isLocalhost = location.hostname === 'localhost' || location.hostname === '127.0.0.1';
        
        return {
            isIOS,
            isAndroid,
            isSafari,
            isChrome,
            isMobile,
            isHTTPS,
            isLocalhost,
            userAgent
        };
    }

    // Update device info display
    function updateDeviceInfo() {
        const device = detectDeviceAndBrowser();
        const deviceTypeEl = document.getElementById('device_type');
        const browserTypeEl = document.getElementById('browser_type');
        const statusIndicatorEl = document.getElementById('camera_status_indicator');
        
        if (deviceTypeEl) {
            if (device.isIOS) {
                deviceTypeEl.innerHTML = '🍎 iOS';
            } else if (device.isAndroid) {
                deviceTypeEl.innerHTML = '🤖 Android';
            } else {
                deviceTypeEl.innerHTML = '💻 Desktop';
            }
        }
        
        if (browserTypeEl) {
            if (device.isSafari) {
                browserTypeEl.innerHTML = 'Safari';
            } else if (device.isChrome) {
                browserTypeEl.innerHTML = 'Chrome';
            } else {
                browserTypeEl.innerHTML = 'Other Browser';
            }
        }
        
        if (statusIndicatorEl) {
            if (device.isIOS && !device.isHTTPS && !device.isLocalhost) {
                statusIndicatorEl.innerHTML = '⚠️ Needs HTTPS';
                statusIndicatorEl.className = 'px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800';
            } else if (device.isAndroid && !device.isChrome) {
                statusIndicatorEl.innerHTML = '⚠️ Use Chrome';
                statusIndicatorEl.className = 'px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800';
            } else {
                statusIndicatorEl.innerHTML = '✅ Ready';
                statusIndicatorEl.className = 'px-2 py-1 rounded text-xs bg-green-100 text-green-800';
            }
        }
    }

    // Show camera status notice
    function showCameraStatus(type, title, message, showActions = false) {
        const statusEl = document.getElementById('camera_status');
        const iconEl = document.getElementById('camera_status_icon');
        const titleEl = document.getElementById('camera_status_title');
        const messageEl = document.getElementById('camera_status_message');
        const actionsEl = document.getElementById('camera_status_actions');
        
        if (statusEl) {
            statusEl.className = `mt-4 p-4 rounded-md ${type === 'error' ? 'bg-red-50 border border-red-200' : type === 'warning' ? 'bg-yellow-50 border border-yellow-200' : 'bg-green-50 border border-green-200'}`;
            statusEl.classList.remove('hidden');
        }
        
        if (iconEl) {
            iconEl.className = `text-lg mr-3 mt-0.5 ${type === 'error' ? 'bx bx-error-circle text-red-600' : type === 'warning' ? 'bx bx-info-circle text-yellow-600' : 'bx bx-check-circle text-green-600'}`;
        }
        
        if (titleEl) titleEl.textContent = title;
        if (messageEl) messageEl.textContent = message;
        if (actionsEl) {
            if (showActions) {
                actionsEl.classList.remove('hidden');
            } else {
                actionsEl.classList.add('hidden');
            }
        }
    }

    // Camera scanner functionality
    cameraScanButton.addEventListener('click', function() {
        const device = detectDeviceAndBrowser();
        
        // Check HTTPS requirement for camera access
        if (!device.isHTTPS && !device.isLocalhost) {
            showCameraStatus('warning', 'HTTPS Required for Camera', 'Camera access requires HTTPS or localhost. Use manual input below or try: http://localhost:8000', true);
            return;
        }
        
        // Check if any camera API is supported
        const hasModernAPI = !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
        const hasLegacyAPI = !!(navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia);
        
        if (!hasModernAPI && !hasLegacyAPI) {
            showCameraStatus('error', 'Camera Not Supported', 'Your browser does not support camera access. Please use Chrome or Safari.', true);
            return;
        }
        
        // Show camera modal
        cameraModal.classList.remove('hidden');
        updateDeviceInfo();
    });

    async function showCameraInstructions() {
        // Run camera diagnostics first
        const diagnostics = await runCameraDiagnostics();
        
        const instructionModal = document.createElement('div');
        instructionModal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
        instructionModal.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-lg w-full max-h-[90vh] overflow-y-auto">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <i class="bx bx-camera text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Camera Access Required</h3>
                </div>
                
                <!-- Diagnostics Results -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-semibold text-gray-800 mb-2">System Check:</h4>
                    <div class="space-y-1 text-xs">
                        <div class="flex justify-between">
                            <span>Browser Support:</span>
                            <span class="${diagnostics.browserSupport ? 'text-green-600' : 'text-red-600'}">${diagnostics.browserSupport ? '✓ Supported' : '✗ Not Supported'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Secure Context:</span>
                            <span class="${diagnostics.secureContext ? 'text-green-600' : 'text-red-600'}">${diagnostics.secureContext ? '✓ Secure' : '✗ Not Secure'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Camera Available:</span>
                            <span class="${diagnostics.cameraAvailable ? 'text-green-600' : 'text-red-600'}">${diagnostics.cameraAvailable ? '✓ Available' : '✗ Not Available'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Current URL:</span>
                            <span class="font-mono text-xs">${location.protocol}//${location.hostname}:${location.port}</span>
                        </div>
                    </div>
                </div>
                
                <div class="text-gray-600 mb-6">
                    <p class="mb-3">To use the camera scanner, please:</p>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>Click "Allow" when prompted for camera permission</li>
                        <li>Make sure no other app is using the camera</li>
                        <li>Ensure you're using HTTPS or localhost</li>
                        <li>Try refreshing the page if issues persist</li>
                        <li>Check browser settings if camera is blocked</li>
                    </ol>
                    
                    ${!diagnostics.secureContext ? `
                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                        <p class="text-xs text-red-800">
                            <strong>⚠️ Security Issue:</strong> Camera access requires HTTPS or localhost. 
                            Current URL is not secure. Try accessing via <code>http://localhost:8000</code>
                        </p>
                    </div>
                    ` : ''}
                    
                    ${!diagnostics.cameraAvailable ? `
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                        <p class="text-xs text-yellow-800">
                            <strong>⚠️ Camera Issue:</strong> No camera detected. Check if camera is connected and not used by other apps.
                        </p>
                    </div>
                    ` : ''}
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button onclick="this.closest('.fixed').remove()" 
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md font-medium transition-colors">
                        Cancel
                    </button>
                    <button onclick="this.closest('.fixed').remove(); startCameraWithPermission()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                        Continue
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(instructionModal);
    }

    // Run camera diagnostics
    async function runCameraDiagnostics() {
        const diagnostics = {
            browserSupport: !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia),
            secureContext: window.isSecureContext || location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1',
            cameraAvailable: false
        };
        
        // Check for camera availability
        if (diagnostics.browserSupport) {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const videoDevices = devices.filter(device => device.kind === 'videoinput');
                diagnostics.cameraAvailable = videoDevices.length > 0;
                console.log('Available video devices:', videoDevices);
                
                // If no devices found, try a different approach
                if (!diagnostics.cameraAvailable) {
                    console.log('No video devices found, trying alternative detection...');
                    // Try to detect if getUserMedia is available without actually requesting access
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({ 
                            video: { 
                                width: { ideal: 640 },
                                height: { ideal: 480 }
                            } 
                        });
                        diagnostics.cameraAvailable = true;
                        stream.getTracks().forEach(track => track.stop());
                        console.log('Camera detected via getUserMedia test');
                    } catch (testError) {
                        console.log('Camera test failed:', testError.name);
                        diagnostics.cameraAvailable = false;
                    }
                }
            } catch (error) {
                console.error('Error checking camera availability:', error);
                diagnostics.cameraAvailable = false;
            }
        }
        
        return diagnostics;
    }

    function startCameraWithPermission() {
        cameraModal.classList.remove('hidden');
        checkCameraPermissions().then(() => {
            console.log('Camera permissions granted');
            // Start the camera after permissions are granted
            startCamera();
        }).catch(err => {
            console.error('Camera permission error:', err);
            // Error is already shown in checkCameraPermissions
        });
    }

    // Check camera permissions with better error handling
    async function checkCameraPermissions() {
        try {
            // Check if getUserMedia is supported
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                throw new Error('Camera not supported on this device');
            }

            // Check if we're on HTTPS or localhost
            const isSecureContext = window.isSecureContext || 
                                  location.protocol === 'https:' || 
                                  location.hostname === 'localhost' || 
                                  location.hostname === '127.0.0.1';
            
            if (!isSecureContext) {
                throw new Error('Camera access requires HTTPS or localhost');
            }

            // Try to get camera permissions with different constraints
            let stream;
            try {
                // First try with environment camera (back camera)
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        facingMode: 'environment',
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    } 
                });
            } catch (envError) {
                console.log('Environment camera failed, trying user camera:', envError);
                try {
                    // Fallback to user camera (front camera)
                    stream = await navigator.mediaDevices.getUserMedia({ 
                        video: { 
                            facingMode: 'user',
                            width: { ideal: 1280 },
                            height: { ideal: 720 }
                        } 
                    });
                } catch (userError) {
                    console.log('User camera failed, trying any available camera:', userError);
                    // Final fallback - any available camera
                    stream = await navigator.mediaDevices.getUserMedia({ 
                        video: { 
                            width: { ideal: 640 },
                            height: { ideal: 480 }
                        } 
                    });
                }
            }
            
            // Store the stream for later use
            cameraStream = stream;
            stream.getTracks().forEach(track => track.stop());
            return true;
        } catch (err) {
            console.error('Camera permission error:', err);
            
            let errorMessage = 'Camera access denied. ';
            
            if (err.name === 'NotAllowedError') {
                errorMessage += 'Please allow camera permissions in your browser settings and try again.';
            } else if (err.name === 'NotFoundError') {
                errorMessage += 'No camera found on this device.';
            } else if (err.name === 'NotSupportedError') {
                errorMessage += 'Camera not supported on this device.';
            } else if (err.name === 'NotReadableError') {
                errorMessage += 'Camera is already in use by another application.';
            } else if (err.message.includes('HTTPS')) {
                errorMessage += 'Camera access requires HTTPS or localhost. Please use a secure connection.';
            } else {
                errorMessage += 'Please check your camera settings and try again.';
            }
            
            showError('Camera Error', errorMessage);
            throw new Error(errorMessage);
        }
    }

    // Show error message with better styling
    function showError(title, message) {
        // Remove any existing error modals to prevent duplicates
        const existingModals = document.querySelectorAll('.fixed.inset-0.bg-black.bg-opacity-50.z-50');
        existingModals.forEach(modal => modal.remove());
        
        // Create a custom error modal
        const errorModal = document.createElement('div');
        errorModal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
        errorModal.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                        <i class="bx bx-error text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">${title}</h3>
                </div>
                <p class="text-gray-600 mb-6">${message}</p>
                <div class="flex justify-end">
                    <button onclick="this.closest('.fixed').remove()" 
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                        OK
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(errorModal);
    }

    closeCameraButton.addEventListener('click', function() {
        stopCamera();
        cameraModal.classList.add('hidden');
    });

    startCameraButton.addEventListener('click', function() {
        startCamera();
    });

    stopCameraButton.addEventListener('click', function() {
        stopCamera();
    });

    // Camera retry button
    document.getElementById('camera_retry_btn').addEventListener('click', function() {
        document.getElementById('camera_status').classList.add('hidden');
        startCamera();
    });

    // Camera help button
    document.getElementById('camera_help_btn').addEventListener('click', function() {
        const device = detectDeviceAndBrowser();
        let helpContent = '';
        
        if (device.isIOS && device.isSafari) {
            helpContent = `
                <div class="space-y-2">
                    <p><strong>iOS Safari Camera Help:</strong></p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Use HTTPS: <code>https://localhost:8000</code></li>
                        <li>Allow camera permission when prompted</li>
                        <li>Make sure camera is not used by other apps</li>
                        <li>Try refreshing the page if camera doesn't start</li>
                    </ul>
                </div>
            `;
        } else if (device.isAndroid) {
            helpContent = `
                <div class="space-y-2">
                    <p><strong>Android Camera Help:</strong></p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Use Chrome browser (not Samsung Internet)</li>
                        <li>Allow camera permission in Chrome settings</li>
                        <li>Check Android camera permissions in Settings</li>
                        <li>Restart device if camera is not detected</li>
                    </ul>
                </div>
            `;
        } else {
            helpContent = `
                <div class="space-y-2">
                    <p><strong>General Camera Help:</strong></p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Allow camera permission when prompted</li>
                        <li>Make sure camera is not used by other apps</li>
                        <li>Try refreshing the page</li>
                        <li>Use manual input as alternative</li>
                    </ul>
                </div>
            `;
        }
        
        document.getElementById('camera_help_content').innerHTML = helpContent;
        document.getElementById('camera_help').classList.remove('hidden');
    });

    // Close modal when clicking outside
    cameraModal.addEventListener('click', function(e) {
        if (e.target === cameraModal) {
            stopCamera();
            cameraModal.classList.add('hidden');
        }
    });

    function startCamera() {
        const device = detectDeviceAndBrowser();
        
        if (qrScanner || html5QrCode || zxingScanner) {
            // Camera already started
            startCameraButton.classList.add('hidden');
            stopCameraButton.classList.remove('hidden');
            return;
        }

        // Show camera placeholder
        document.getElementById('camera_placeholder').style.display = 'none';
        document.getElementById('camera_video').style.display = 'block';
        document.getElementById('camera_overlay').classList.remove('hidden');

        // Try multiple QR code scanning libraries for better compatibility
        tryStartCamera();
    }

    // Show camera help
    function showCameraHelp(type, message) {
        const helpEl = document.getElementById('camera_help');
        const contentEl = document.getElementById('camera_help_content');
        
        if (helpEl && contentEl) {
            helpEl.classList.remove('hidden');
            contentEl.innerHTML = `<div class="${type === 'error' ? 'text-red-700' : type === 'warning' ? 'text-yellow-700' : 'text-green-700'}">${message}</div>`;
        }
    }

    async function tryStartCamera() {
        const device = detectDeviceAndBrowser();
        console.log('Starting camera with device-specific settings...', device);
        
        // Method 1: Try QR-Scanner library first
        if (typeof QrScanner !== 'undefined') {
            try {
                console.log('Trying QR-Scanner library...');
                const hasCamera = await QrScanner.hasCamera();
                if (hasCamera) {
                    qrScanner = new QrScanner(
                        cameraVideo,
                        result => {
                            handleQRResult(result.data);
                        },
                        {
                            highlightScanRegion: true,
                            highlightCodeOutline: true,
                            preferredCamera: 'environment',
                            maxScansPerSecond: device.isIOS ? 3 : 5, // Lower for iOS
                        }
                    );

                    await qrScanner.start();
                    startCameraButton.classList.add('hidden');
                    stopCameraButton.classList.remove('hidden');
                    console.log('QR-Scanner started successfully');
                    showCameraHelp('success', 'Camera started successfully! Point at QR code to scan.');
                    return;
                }
            } catch (err) {
                console.log('QR-Scanner failed:', err);
            }
        }

        // Method 2: Try Html5QrCode library
        if (typeof Html5Qrcode !== 'undefined') {
            try {
                console.log('Trying Html5QrCode library...');
                html5QrCode = new Html5Qrcode("camera_video");
                const config = {
                    fps: device.isIOS ? 5 : 10, // Lower FPS for iOS
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0
                };

                await html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    (decodedText, decodedResult) => {
                        handleQRResult(decodedText);
                    }
                );
                
                startCameraButton.classList.add('hidden');
                stopCameraButton.classList.remove('hidden');
                console.log('Html5QrCode started successfully');
                showCameraHelp('success', 'Camera started successfully! Point at QR code to scan.');
                return;
            } catch (err) {
                console.log('Html5QrCode failed:', err);
            }
        }

        // Method 3: Try ZXing library
        if (typeof ZXing !== 'undefined') {
            try {
                console.log('Trying ZXing library...');
                zxingScanner = new ZXing.BrowserMultiFormatReader();
                const videoInputDevices = await zxingScanner.listVideoInputDevices();
                
                if (videoInputDevices.length > 0) {
                    const selectedDeviceId = videoInputDevices[0].deviceId;
                    
                    zxingScanner.decodeFromVideoDevice(
                        selectedDeviceId,
                        cameraVideo,
                        (result, err) => {
                            if (result) {
                                handleQRResult(result.getText());
                            }
                        }
                    );
                    
                    startCameraButton.classList.add('hidden');
                    stopCameraButton.classList.remove('hidden');
                    console.log('ZXing started successfully');
                    return;
                }
            } catch (err) {
                console.log('ZXing failed:', err);
            }
        }

        // Method 4: Try basic getUserMedia as fallback
        try {
            console.log('Trying basic getUserMedia fallback...');
            const stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                } 
            });
            
            cameraVideo.srcObject = stream;
            cameraVideo.play();
            startCameraButton.classList.add('hidden');
            stopCameraButton.classList.remove('hidden');
            console.log('Basic getUserMedia started successfully');
            return;
        } catch (err) {
            console.log('Basic getUserMedia failed:', err);
        }

        // If all methods fail, show device-specific error
        console.log('All camera methods failed');
        
        let errorMessage = 'Unable to access camera. ';
        if (device.isIOS && !device.isHTTPS && !device.isLocalhost) {
            errorMessage += 'iOS Safari requires HTTPS. Please use https://localhost:8000 instead.';
        } else if (device.isAndroid && !device.isChrome) {
            errorMessage += 'Android requires Chrome browser for camera access.';
        } else if (device.isIOS && device.isSafari) {
            errorMessage += 'iOS Safari camera access failed. Please check permissions and try again.';
        } else {
            errorMessage += 'Please check camera permissions and try again.';
        }
        
        showCameraHelp('error', errorMessage);
        showError('Camera Error', errorMessage);
    }

    function handleQRResult(qrData) {
        console.log('QR Code detected:', qrData);
        qrcodeInput.value = qrData;
        stopCamera();
        cameraModal.classList.add('hidden');
        
        // Auto-scan the detected QR code
        const eventId = null;
        const gateId = 'GATE-1';
        scanTicket(qrData, eventId, gateId);
    }

    function stopCamera() {
        // Stop QR-Scanner
        if (qrScanner) {
            qrScanner.stop();
            qrScanner.destroy();
            qrScanner = null;
        }

        // Stop Html5QrCode
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                html5QrCode = null;
            }).catch(err => {
                console.log('Error stopping Html5QrCode:', err);
                html5QrCode = null;
            });
        }

        // Stop ZXing scanner
        if (zxingScanner) {
            zxingScanner.reset();
            zxingScanner = null;
        }

        startCameraButton.classList.remove('hidden');
        stopCameraButton.classList.add('hidden');
    }

    // Auto-clear result after 5 seconds
    let clearTimeout;

    function scanTicket(qrcode, eventId, gateId) {
        // Show loading state
        scanButton.disabled = true;
        scanButton.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i>Scanning...';
        scanResult.classList.add('hidden');

        // Make API call
        fetch('{{ route("gate-staff.scan-ticket") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                qrcode: qrcode,
                event_id: eventId,
                gate_id: gateId
            })
        })
        .then(response => response.json())
        .then(data => {
            displayScanResult(data);
        })
        .catch(error => {
            console.error('Error:', error);
            displayScanResult({
                status: 'ERROR',
                message: 'Network error occurred',
                performance_time: 0
            });
        })
        .finally(() => {
            // Reset button state
            scanButton.disabled = false;
            scanButton.innerHTML = '<i class="bx bx-qr-scan mr-2"></i>Scan';
        });
    }

    function displayScanResult(data) {
        // Determine icon and color based on status
        let icon, color, title, html;
        
        switch(data.status) {
            case 'SUCCESS':
                icon = 'success';
                color = '#10B981';
                title = 'Ticket Validated';
                html = `
                    <div class="text-left space-y-3">
                        <p class="mb-4 text-base font-semibold text-green-600">${data.message}</p>
                        ${data.ticket ? `
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-2">
                            <div class="flex justify-between items-start pb-2 border-b border-gray-300">
                                <span class="text-sm font-semibold text-gray-600">Event:</span>
                                <span class="text-sm text-gray-900 text-right">${data.ticket.event_name || 'N/A'}</span>
                            </div>
                            ${data.ticket.event_date ? `
                            <div class="flex justify-between items-start py-2 border-b border-gray-300">
                                <span class="text-sm font-semibold text-gray-600">Date & Time:</span>
                                <span class="text-sm text-gray-900 text-right">${data.ticket.event_date || 'N/A'}</span>
                            </div>
                            ` : ''}
                            ${data.ticket.venue && data.ticket.venue !== 'N/A' ? `
                            <div class="flex justify-between items-start py-2 border-b border-gray-300">
                                <span class="text-sm font-semibold text-gray-600">Venue:</span>
                                <span class="text-sm text-gray-900 text-right">${data.ticket.venue}</span>
                            </div>
                            ` : ''}
                            <div class="flex justify-between items-start py-2 border-b border-gray-300">
                                <span class="text-sm font-semibold text-gray-600">Ticket Type:</span>
                                <span class="text-sm text-gray-900 text-right">${data.ticket.ticket_identifier || 'N/A'}</span>
                            </div>
                            ${data.ticket.zone && data.ticket.zone !== 'N/A' ? `
                            <div class="flex justify-between items-start py-2 border-b border-gray-300">
                                <span class="text-sm font-semibold text-gray-600">Zone:</span>
                                <span class="text-sm text-gray-900 text-right">${data.ticket.zone}</span>
                            </div>
                            ` : ''}
                            <div class="flex justify-between items-start py-2 border-b border-gray-300">
                                <span class="text-sm font-semibold text-gray-600">Price Paid:</span>
                                <span class="text-sm font-semibold text-gray-900 text-right">RM${parseFloat(data.ticket.price_paid || 0).toFixed(2)}</span>
                            </div>
                            ${data.ticket.status ? `
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-sm font-semibold text-gray-600">Status:</span>
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold uppercase">${data.ticket.status}</span>
                            </div>
                            ` : ''}
                        </div>
                        ` : ''}
                    </div>
                `;
                break;
            case 'DUPLICATE':
                icon = 'error';
                color = '#EF4444';
                title = 'Already Scanned';
                html = `
                    <div class="text-left space-y-3">
                        <p class="mb-4 text-base font-semibold text-red-600">This ticket has already been scanned!</p>
                        ${data.ticket ? `
                        <div class="bg-red-50 p-4 rounded-lg border border-red-200 space-y-2">
                            <div class="flex justify-between items-start pb-2 border-b border-red-300">
                                <span class="text-sm font-semibold text-gray-600">Event:</span>
                                <span class="text-sm text-gray-900 text-right">${data.ticket.event_name || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between items-start py-2 border-b border-red-300">
                                <span class="text-sm font-semibold text-gray-600">Ticket Type:</span>
                                <span class="text-sm text-gray-900 text-right">${data.ticket.ticket_identifier || 'N/A'}</span>
                            </div>
                            ${data.ticket.scanned_at ? `
                            <div class="flex justify-between items-start py-2 border-b border-red-300">
                                <span class="text-sm font-semibold text-gray-600">Scanned At:</span>
                                <span class="text-sm text-gray-900 text-right">${data.ticket.scanned_at}</span>
                            </div>
                            ` : ''}
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-sm font-semibold text-gray-600">Status:</span>
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold uppercase">Already Scanned</span>
                            </div>
                        </div>
                        ` : ''}
                        <p class="text-xs text-gray-500">Please check if this is a duplicate entry or contact support.</p>
                    </div>
                `;
                break;
            case 'INVALID':
                icon = 'error';
                color = '#EF4444';
                title = 'Invalid Ticket';
                html = `<p>${data.message}</p>`;
                break;
            case 'WRONG_GATE':
                icon = 'warning';
                color = '#F59E0B';
                title = 'Wrong Gate';
                html = `<p>${data.message}</p>`;
                break;
            case 'WRONG_EVENT':
                icon = 'warning';
                color = '#F59E0B';
                title = 'Wrong Event';
                html = `<p>${data.message}</p>`;
                break;
            default:
                icon = 'error';
                color = '#EF4444';
                title = 'Error';
                html = `<p>${data.message}</p>`;
        }

        // Show SweetAlert popup
        Swal.fire({
            icon: icon,
            title: title,
            html: html,
            confirmButtonColor: color,
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            didClose: () => {
                // Clear input and focus for next scan
                qrcodeInput.value = '';
                qrcodeInput.focus();
            }
        });
    }
});

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
