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
                    1. How do I purchase tickets?
                </h3>
                <p class="text-wwc-neutral-600 leading-relaxed">
                    Simply visit our ticketing page, choose your preferred seat zone, and proceed to checkout. Once your payment is confirmed, you will receive your ticket via email and it will also appear in your account under "My Tickets."
                </p>
            </div>

            <!-- FAQ Item 2 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                    2. Can I get a refund for my tickets?
                </h3>
                <p class="text-wwc-neutral-600 leading-relaxed">
                    All ticket sales are non-refundable. Please ensure your purchase details are accurate before confirming your order.
                </p>
            </div>

            <!-- FAQ Item 3 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                    3. What if I lose my tickets?
                </h3>
                <p class="text-wwc-neutral-600 leading-relaxed">
                    No worries! You can re-access your tickets anytime. Just log into your account and go to "My Tickets" to view, download, or reprint them.
                </p>
            </div>

            <!-- FAQ Item 4 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                    4. How do I change my account information?
                </h3>
                <p class="text-wwc-neutral-600 leading-relaxed">
                    You may update your details in your Profile Settings. Click your account name at the top right, select "Profile Settings," and make the necessary changes.
                </p>
            </div>

            <!-- FAQ Item 5 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                    5. What payment methods do you accept?
                </h3>
                <p class="text-wwc-neutral-600 leading-relaxed">
                    We accept major credit cards, debit cards, and FPX (Visa / MasterCard supported).<br>
                    All payments are processed securely through our authorized payment gateway partners.
                </p>
            </div>

            <!-- FAQ Item 6 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                    6. How do I contact customer support?
                </h3>
                <p class="text-wwc-neutral-600 leading-relaxed mb-4">
                    Need help? We're here for you.
                </p>
                <div class="space-y-2 text-wwc-neutral-600">
                    <p><strong class="text-wwc-neutral-900">Email:</strong> <a href="mailto:ticketsupport@wwcworld.com" class="text-wwc-primary hover:text-wwc-primary-dark">ticketsupport@wwcworld.com</a></p>
                    <p><strong class="text-wwc-neutral-900">Phone:</strong> <a href="tel:+60326941614" class="text-wwc-primary hover:text-wwc-primary-dark">+603 2694 1614</a></p>
                    <p><strong class="text-wwc-neutral-900">WhatsApp:</strong> <a href="https://wa.me/601160740656" target="_blank" rel="noopener noreferrer" class="text-wwc-primary hover:text-wwc-primary-dark">+6011 6074 0656</a></p>
                    <p class="mt-4"><strong class="text-wwc-neutral-900">Support Hours:</strong> Monday – Saturday, 9:00 AM – 5:00 PM</p>
                    <p class="text-sm italic">Operating hours may vary on event days. We'll do our best to respond as quickly as possible.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional FAQ Section -->
<div class="py-16 bg-wwc-neutral-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-2xl font-bold text-wwc-neutral-900 font-display mb-4">
                More Questions?
            </h2>
            <p class="text-base text-wwc-neutral-600 max-w-2xl mx-auto">
                Can't find what you're looking for? We're here to help!
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <div class="flex items-center mb-3">
                    <div class="bg-wwc-primary-light rounded-lg p-2.5 mr-3">
                        <svg class="w-5 h-5 text-wwc-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-wwc-neutral-900">Email Support</h3>
                </div>
                <p class="text-sm text-wwc-neutral-600 mb-3">
                    Send us an email and we'll get back to you as quickly as possible.
                </p>
                <a href="mailto:ticketsupport@wwcworld.com" 
                   class="text-sm text-wwc-primary font-semibold hover:text-wwc-primary-dark transition-colors duration-200">
                    ticketsupport@wwcworld.com
                </a>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <div class="flex items-center mb-3">
                    <div class="bg-wwc-primary-light rounded-lg p-2.5 mr-3">
                        <svg class="w-5 h-5 text-wwc-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-wwc-neutral-900">Phone Support</h3>
                </div>
                <p class="text-sm text-wwc-neutral-600 mb-3">
                    Call us directly for immediate assistance.
                </p>
                <div class="space-y-1.5">
                    <a href="tel:+60326941614" 
                       class="block text-sm text-wwc-primary font-semibold hover:text-wwc-primary-dark transition-colors duration-200">
                        +603 2694 1614
                    </a>
                    <p class="text-xs text-wwc-neutral-600">Support Hours: Monday – Saturday, 9:00 AM – 5:00 PM</p>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <div class="flex items-center mb-3">
                    <div class="bg-wwc-primary-light rounded-lg p-2.5 mr-3">
                        <svg class="w-5 h-5 text-wwc-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-wwc-neutral-900">WhatsApp Support</h3>
                </div>
                <p class="text-sm text-wwc-neutral-600 mb-3">
                    Chat with us on WhatsApp for quick assistance.
                </p>
                <a href="https://wa.me/601160740656" target="_blank" rel="noopener noreferrer"
                   class="text-sm text-wwc-primary font-semibold hover:text-wwc-primary-dark transition-colors duration-200">
                    +6011 6074 0656
                </a>
            </div>
        </div>
        
        <div class="text-center mt-10">
            <a href="{{ route('public.contact') }}" 
               class="bg-wwc-primary text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-wwc-primary-dark transition-colors duration-200">
                Contact Us
            </a>
        </div>
    </div>
</div>

@endsection
