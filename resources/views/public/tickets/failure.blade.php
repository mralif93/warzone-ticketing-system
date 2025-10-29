@extends('layouts.public')

@section('title', 'Purchase Failed - ' . $event->name)
@section('description', 'Your ticket purchase could not be completed. Please try again or contact support.')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-rose-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Failure Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-red-500 rounded-full mb-6 shadow-lg">
                <i class="bx bx-x text-4xl text-white"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Purchase Failed</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">We're sorry, but your ticket purchase could not be completed. Please try again or contact our support team.</p>
        </div>

        <!-- Failure Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8">
            <div class="bg-red-600 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                            <i class="bx bx-x text-2xl text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-1">Transaction Failed</h2>
                            <p class="text-white/90 text-sm">{{ now()->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-white/80 text-sm">Error Code: {{ $error_code ?? 'PAYMENT_FAILED' }}</div>
                        <div class="text-white/60 text-xs">Transaction: {{ $transaction_id ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <!-- Event Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="bx bx-calendar mr-2 text-red-500"></i>
                        Event Details
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">{{ $event->name }}</h4>
                                <p class="text-gray-600">{{ $event->venue ?? $event->location }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="bx bx-x-circle mr-1"></i>
                                    Payment Failed
                                </span>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="bx bx-calendar mr-2 text-red-500"></i>
                                <span>
                                    @if($event->isMultiDay())
                                        {{ $event->getEventDays()[0]['display'] }} - {{ $event->getEventDays()[1]['display'] }}
                                    @else
                                        {{ $event->date_time->format('M j, Y') }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center">
                                <i class="bx bx-time mr-2 text-red-500"></i>
                                <span>{{ $event->start_time }} - {{ $event->end_time }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Error Details -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="bx bx-info-circle mr-2 text-red-500"></i>
                        Error Details
                    </h3>
                    <div class="bg-red-50 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">Payment Processing Error</h4>
                                <p class="text-gray-600">
                                    @if(isset($error_message))
                                        {{ $error_message }}
                                    @else
                                        We encountered an unexpected error while processing your payment.
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="bx bx-error-circle mr-1"></i>
                                    Error
                                </span>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="bx bx-hash mr-2 text-red-500"></i>
                                <span><strong>Error Code:</strong> {{ $error_code ?? 'PAYMENT_FAILED' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="bx bx-receipt mr-2 text-red-500"></i>
                                <span><strong>Transaction:</strong> {{ $transaction_id ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="bx bx-help-circle mr-2 text-red-500"></i>
                        Need Help?
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">Support & Solutions</h4>
                                <p class="text-gray-600">Our support team is here to help you complete your purchase.</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="bx bx-help-circle mr-1"></i>
                                    Support
                                </span>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="bx bx-phone mr-2 text-red-500"></i>
                                <span><strong>Phone:</strong> +1 (555) 123-4567</span>
                            </div>
                            <div class="flex items-center">
                                <i class="bx bx-envelope mr-2 text-red-500"></i>
                                <span><strong>Email:</strong> support@warzone.com</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">What's Next?</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bx bx-cart text-2xl text-red-600"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Return to Cart</h4>
                    <p class="text-sm text-gray-600">Go back to your cart to review your selection and try again with a different payment method.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bx bx-refresh text-2xl text-blue-600"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Retry Payment</h4>
                    <p class="text-sm text-gray-600">Try processing your payment again with the same or a different payment method.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bx bx-help-circle text-2xl text-purple-600"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Contact Support</h4>
                    <p class="text-sm text-gray-600">Get help from our support team if you're experiencing payment issues.</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col gap-4 px-2">
            <a href="{{ route('public.tickets.cart', $event) }}" 
               class="w-full inline-flex items-center justify-center px-8 py-4 bg-gray-600 text-white rounded-xl font-bold text-lg hover:bg-gray-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <i class="bx bx-cart mr-3 text-xl"></i>
                Back to Cart
            </a>
            @auth
            <a href="{{ route('customer.dashboard') }}" 
               class="w-full inline-flex items-center justify-center px-8 py-4 bg-wwc-primary text-white rounded-xl font-bold text-lg hover:bg-wwc-primary-dark transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <i class="bx bx-home mr-3 text-xl"></i>
                Go to Dashboard
            </a>
            @endauth
            <a href="mailto:support@warzone.com?subject=Payment%20Failed%20-%20Event%20{{ $event->id }}" 
               class="w-full inline-flex items-center justify-center px-8 py-4 bg-red-600 text-white rounded-xl font-bold text-lg hover:bg-red-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <i class="bx bx-support mr-3 text-xl"></i>
                Contact Support
            </a>
        </div>
    </div>
</div>
@endsection
