@extends('layouts.public')

@section('title', 'Terms of Service')
@section('description', 'Read our terms of service for Warzone World Championship events and ticket purchases.')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white font-display mb-6">
                Terms of Service
            </h1>
            <p class="text-xl text-wwc-primary-light max-w-3xl mx-auto">
                Please read these terms carefully before using our services or purchasing tickets.
            </p>
        </div>
    </div>
</div>

<!-- Terms of Service Content -->
<div class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-8">
            <!-- Term Item 1 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <div class="flex items-start">
                    <div class="bg-wwc-primary text-white rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0 mt-1">
                        <span class="text-sm font-bold">1</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                            Ticket Validity
                        </h3>
                        <p class="text-wwc-neutral-600 leading-relaxed">
                            Each ticket admits one person and must be shown at the entrance along with a valid ID.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Term Item 2 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <div class="flex items-start">
                    <div class="bg-wwc-primary text-white rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0 mt-1">
                        <span class="text-sm font-bold">2</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                            Behavior Policy
                        </h3>
                        <p class="text-wwc-neutral-600 leading-relaxed">
                            Disruptive, violent, or inappropriate behavior may result in removal from the venue without a refund.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Term Item 3 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <div class="flex items-start">
                    <div class="bg-wwc-primary text-white rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0 mt-1">
                        <span class="text-sm font-bold">3</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                            Media Usage
                        </h3>
                        <p class="text-wwc-neutral-600 leading-relaxed">
                            By attending, you consent to being filmed, photographed, and recorded for promotional and archival use.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Term Item 4 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <div class="flex items-start">
                    <div class="bg-wwc-primary text-white rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0 mt-1">
                        <span class="text-sm font-bold">4</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                            Limitation of Liability
                        </h3>
                        <p class="text-wwc-neutral-600 leading-relaxed">
                            WARZONE and its affiliates are not responsible for any personal injury, loss, or property damage during the event.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Term Item 5 -->
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <div class="flex items-start">
                    <div class="bg-wwc-primary text-white rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0 mt-1">
                        <span class="text-sm font-bold">5</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-wwc-neutral-900 mb-4">
                            Event Changes
                        </h3>
                        <p class="text-wwc-neutral-600 leading-relaxed">
                            WARZONE reserves the right to make changes to the event schedule, lineup, or policies without prior notice.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Terms Section -->
<div class="py-16 bg-wwc-neutral-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-wwc-neutral-900 font-display mb-4">
                Additional Terms
            </h2>
            <p class="text-lg text-wwc-neutral-600 max-w-2xl mx-auto">
                Additional important information about our services and your rights.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl p-8 shadow-sm">
                <h3 class="text-xl font-semibold text-wwc-neutral-900 mb-4">Privacy Policy</h3>
                <p class="text-wwc-neutral-600 mb-4">
                    We respect your privacy and are committed to protecting your personal information. 
                    Please review our privacy policy for details on how we collect, use, and protect your data.
                </p>
                <a href="#" class="text-wwc-primary font-semibold hover:text-wwc-primary-dark transition-colors duration-200">
                    Read Privacy Policy →
                </a>
            </div>
            
            <div class="bg-white rounded-2xl p-8 shadow-sm">
                <h3 class="text-xl font-semibold text-wwc-neutral-900 mb-4">Refund Policy</h3>
                <p class="text-wwc-neutral-600 mb-4">
                    All ticket sales are final and non-refundable, except in specific circumstances. 
                    Please review our refund policy for complete details.
                </p>
                <a href="{{ route('public.refund-policy') }}" class="text-wwc-primary font-semibold hover:text-wwc-primary-dark transition-colors duration-200">
                    Read Refund Policy →
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Contact Information Section -->
<div class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-wwc-neutral-900 font-display mb-4">
                Questions About These Terms?
            </h2>
            <p class="text-lg text-wwc-neutral-600 max-w-2xl mx-auto">
                If you have any questions about these terms of service, please don't hesitate to contact us.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <div class="flex items-center mb-4">
                    <div class="bg-wwc-primary-light rounded-lg p-3 mr-4">
                        <svg class="w-6 h-6 text-wwc-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-wwc-neutral-900">Email Support</h3>
                </div>
                <p class="text-wwc-neutral-600 mb-4">
                    Send us an email for questions about our terms of service.
                </p>
                <a href="mailto:warzoneworldchampion@gmail.com" 
                   class="text-wwc-primary font-semibold hover:text-wwc-primary-dark transition-colors duration-200">
                    warzoneworldchampion@gmail.com
                </a>
            </div>
            
            <div class="bg-wwc-neutral-50 rounded-2xl p-8">
                <div class="flex items-center mb-4">
                    <div class="bg-wwc-primary-light rounded-lg p-3 mr-4">
                        <svg class="w-6 h-6 text-wwc-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-wwc-neutral-900">Phone Support</h3>
                </div>
                <p class="text-wwc-neutral-600 mb-4">
                    Call us directly for immediate assistance with any questions.
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

<!-- Legal Notice Section -->
<div class="py-16 bg-wwc-neutral-900 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-wwc-neutral-800 rounded-2xl p-8">
            <div class="flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-wwc-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-xl font-bold">Legal Notice</h3>
            </div>
            <p class="text-wwc-neutral-300 leading-relaxed">
                By using our services or purchasing tickets, you acknowledge that you have read, understood, 
                and agree to be bound by these terms of service. These terms constitute a legally binding 
                agreement between you and Warzone World Championship SDN BHD.
            </p>
            <p class="text-wwc-neutral-400 text-sm mt-4">
                Last updated: December 2024
            </p>
        </div>
    </div>
</div>
@endsection
