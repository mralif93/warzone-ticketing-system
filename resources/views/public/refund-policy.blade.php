@extends('layouts.public')

@section('title', 'Refund Policy')
@section('description', 'Learn about our refund policy for Warzone World Championship events and ticket purchases.')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white font-display mb-6">
                Refund Policy
            </h1>
            <p class="text-xl text-wwc-primary-light max-w-3xl mx-auto">
                Important information about refunds and cancellations for our events.
            </p>
        </div>
    </div>
</div>

<!-- Refund Policy Content -->
<div class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-8">
            <!-- Policy Item 1 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <div class="flex items-start">
                    <div class="bg-wwc-primary text-white rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0 mt-1">
                        <span class="text-sm font-bold">1</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                            No Refunds
                        </h3>
                        <p class="text-wwc-neutral-600 leading-relaxed">
                            All ticket sales are final and non-refundable, except in the case of event cancellation or major changes.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Policy Item 2 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <div class="flex items-start">
                    <div class="bg-wwc-primary text-white rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0 mt-1">
                        <span class="text-sm font-bold">2</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                            Cancellation
                        </h3>
                        <p class="text-wwc-neutral-600 leading-relaxed">
                            If the event is canceled, full refunds will be issued to your original payment method within 14 working days.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Policy Item 3 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <div class="flex items-start">
                    <div class="bg-wwc-primary text-white rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0 mt-1">
                        <span class="text-sm font-bold">3</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                            Postponement
                        </h3>
                        <p class="text-wwc-neutral-600 leading-relaxed">
                            If the event is rescheduled, existing tickets will remain valid. Refunds may be requested within 7 days of the new date announcement.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Policy Item 4 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <div class="flex items-start">
                    <div class="bg-wwc-primary text-white rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0 mt-1">
                        <span class="text-sm font-bold">4</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                            Ticket Transfer
                        </h3>
                        <p class="text-wwc-neutral-600 leading-relaxed">
                            Tickets are non-transferable, unless written approval is granted by the event organizer.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Information Section -->
<div class="py-16 bg-wwc-neutral-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-wwc-neutral-900 font-display mb-4">
                Need More Information?
            </h2>
            <p class="text-lg text-wwc-neutral-600 max-w-2xl mx-auto">
                If you have questions about our refund policy or need assistance with a specific case, please contact us.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl p-8 shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="bg-wwc-primary-light rounded-lg p-3 mr-4">
                        <svg class="w-6 h-6 text-wwc-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-wwc-neutral-900">Email Support</h3>
                </div>
                <p class="text-wwc-neutral-600 mb-4">
                    Send us an email for refund inquiries and we'll respond within 24 hours.
                </p>
                <a href="mailto:warzoneworldchampion@gmail.com" 
                   class="text-wwc-primary font-semibold hover:text-wwc-primary-dark transition-colors duration-200">
                    warzoneworldchampion@gmail.com
                </a>
            </div>
            
            <div class="bg-white rounded-2xl p-8 shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="bg-wwc-primary-light rounded-lg p-3 mr-4">
                        <svg class="w-6 h-6 text-wwc-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-wwc-neutral-900">Phone Support</h3>
                </div>
                <p class="text-wwc-neutral-600 mb-4">
                    Call us directly for immediate assistance with refund requests.
                </p>
                <a href="tel:+60326941614" 
                   class="text-wwc-primary font-semibold hover:text-wwc-primary-dark transition-colors duration-200">
                    +60326941614
                </a>
            </div>
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('public.contact') }}" 
               class="bg-wwc-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-wwc-primary-dark transition-colors duration-200">
                Contact Us
            </a>
        </div>
    </div>
</div>

<!-- Important Notice Section -->
<div class="py-16 bg-wwc-warning-light">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-white rounded-2xl p-8 shadow-sm">
            <div class="flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-wwc-warning mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <h3 class="text-xl font-bold text-wwc-neutral-900">Important Notice</h3>
            </div>
            <p class="text-wwc-neutral-600 leading-relaxed">
                Please read this refund policy carefully before purchasing tickets. By completing your purchase, 
                you acknowledge that you have read, understood, and agree to be bound by this refund policy.
            </p>
        </div>
    </div>
</div>
@endsection
