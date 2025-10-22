@extends('layouts.public')

@section('title', 'Frequently Asked Questions')
@section('description', 'Find answers to common questions about Warzone World Championship events, tickets, and more.')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white font-display mb-6">
                Frequently Asked Questions
            </h1>
            <p class="text-xl text-wwc-primary-light max-w-3xl mx-auto">
                Find answers to the most common questions about our events, tickets, and services.
            </p>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-8">
            <!-- FAQ Item 1 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                    When and where is the event?
                </h3>
                <p class="text-wwc-neutral-600 leading-relaxed">
                    The event, Warzone 2025 Championship, will take place on 6th December 2025 at Arena 9, Nilai, starting at 6:05 PM.
                </p>
            </div>

            <!-- FAQ Item 2 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                    How do I purchase tickets?
                </h3>
                <p class="text-wwc-neutral-600 leading-relaxed">
                    Click the "Buy Now" button on the ticket listing to proceed with your purchase.
                </p>
            </div>

            <!-- FAQ Item 3 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                    Are there different ticket categories?
                </h3>
                <p class="text-wwc-neutral-600 leading-relaxed">
                    Yes, tickets are categorized by seating, including #VIP and other available tiers.
                </p>
            </div>

            <!-- FAQ Item 4 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                    Can I buy tickets at the door?
                </h3>
                <p class="text-wwc-neutral-600 leading-relaxed">
                    Online purchase is highly recommended, as seats are limited and may sell out before the event.
                </p>
            </div>

            <!-- FAQ Item 5 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                    Is there an age restriction?
                </h3>
                <p class="text-wwc-neutral-600 leading-relaxed">
                    Yes, attendees must be 18 years old and above.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Additional FAQ Section -->
<div class="py-16 bg-wwc-neutral-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-wwc-neutral-900 font-display mb-4">
                More Questions?
            </h2>
            <p class="text-lg text-wwc-neutral-600 max-w-2xl mx-auto">
                Can't find what you're looking for? We're here to help!
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
                    Send us an email and we'll get back to you within 24 hours.
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
                    Call us directly for immediate assistance.
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

@endsection
