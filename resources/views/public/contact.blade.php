@extends('layouts.public')

@section('title', 'Contact Us')
@section('description', 'Get in touch with Warzone World Championship SDN BHD. We\'re here to help with any questions or support you need.')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white font-display mb-6">
                Contact Us
            </h1>
            <p class="text-xl text-wwc-primary-light max-w-3xl mx-auto">
                Have questions? We're here to help! Get in touch with our team and we'll get back to you as soon as possible.
            </p>
        </div>
    </div>
</div>

<!-- Contact Form & Info Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div>
                <h2 class="text-2xl font-bold text-wwc-neutral-900 font-display mb-6">
                    Send us a Message
                </h2>
                
                @if(session('success'))
                    <div class="bg-wwc-success-light border border-wwc-success text-wwc-success px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('public.contact.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                                Full Name *
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent @error('name') border-wwc-error @enderror"
                                   placeholder="Your full name"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-wwc-error">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                                Email Address *
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   class="w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent @error('email') border-wwc-error @enderror"
                                   placeholder="your.email@example.com"
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-wwc-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                            Subject *
                        </label>
                        <input type="text" 
                               id="subject" 
                               name="subject" 
                               value="{{ old('subject') }}"
                               class="w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent @error('subject') border-wwc-error @enderror"
                               placeholder="What's this about?"
                               required>
                        @error('subject')
                            <p class="mt-1 text-sm text-wwc-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-wwc-neutral-700 mb-2">
                            Message *
                        </label>
                        <textarea id="message" 
                                  name="message" 
                                  rows="6"
                                  class="w-full px-4 py-3 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-transparent @error('message') border-wwc-error @enderror"
                                  placeholder="Tell us how we can help you..."
                                  required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-wwc-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-wwc-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-wwc-primary-dark transition-colors duration-200 focus:ring-2 focus:ring-wwc-primary focus:ring-offset-2">
                        Send Message
                    </button>
                </form>
            </div>
            
            <!-- Contact Information -->
            <div>
                <h2 class="text-2xl font-bold text-wwc-neutral-900 font-display mb-6">
                    Get in Touch
                </h2>
                
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="bg-wwc-primary-light rounded-lg p-3 mr-4">
                            <svg class="w-6 h-6 text-wwc-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-wwc-neutral-900 mb-1">Email Us</h3>
                            <p class="text-wwc-neutral-600">warzoneworldchampion@gmail.com</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-wwc-primary-light rounded-lg p-3 mr-4">
                            <svg class="w-6 h-6 text-wwc-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-wwc-neutral-900 mb-1">Call Us</h3>
                            <p class="text-wwc-neutral-600">+60326941614</p>
                            <p class="text-sm text-wwc-neutral-500">Mon-Fri 9AM-6PM MYT</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-wwc-primary-light rounded-lg p-3 mr-4">
                            <svg class="w-6 h-6 text-wwc-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-wwc-neutral-900 mb-1">Visit Us</h3>
                            <p class="text-wwc-neutral-600">Warzone STUDIO, No 81 G, Jalan SPU</p>
                            <p class="text-wwc-neutral-600">1, Bandar Saujana Putra, 42610</p>
                            <p class="text-wwc-neutral-600">Jenjarom, Selangor, Malaysia</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-wwc-primary-light rounded-lg p-3 mr-4">
                            <svg class="w-6 h-6 text-wwc-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-wwc-neutral-900 mb-1">Operating Hours</h3>
                            <p class="text-wwc-neutral-600">Monday – Saturday: 9:00 AM – 5:00 PM</p>
                            <p class="text-sm text-wwc-neutral-500 italic mt-2">Operating hours may differ on event days.</p>
                            <p class="text-sm text-wwc-neutral-500 italic">For customer support, we'll respond as soon as possible.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div class="mt-8">
                    <h3 class="font-semibold text-wwc-neutral-900 mb-4">Follow Us</h3>
                    <div class="flex flex-wrap gap-3">
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/p/Warzone-World-Championship-61577035808058/" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="bg-wwc-neutral-100 text-wwc-neutral-600 p-3 rounded-lg hover:bg-blue-600 hover:text-white transition-colors duration-200 flex items-center justify-center"
                           title="Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        
                        <!-- Instagram -->
                        <a href="https://www.instagram.com/wwcchampionship/" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="bg-wwc-neutral-100 text-wwc-neutral-600 p-3 rounded-lg hover:bg-gradient-to-r hover:from-purple-600 hover:via-pink-600 hover:to-orange-500 hover:text-white transition-colors duration-200 flex items-center justify-center"
                           title="Instagram">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        
                        <!-- TikTok -->
                        <a href="https://www.tiktok.com/@wwc.championship" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="bg-wwc-neutral-100 text-wwc-neutral-600 p-3 rounded-lg hover:bg-wwc-primary hover:text-white transition-colors duration-200 flex items-center justify-center"
                           title="TikTok">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.18 6.18 0 0 0-1-.05A6.07 6.07 0 0 0 5 20.1a6.07 6.07 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                            </svg>
                        </a>
                        
                        <!-- YouTube -->
                        <a href="https://www.youtube.com/@WarzoneWorldChampionship" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="bg-wwc-neutral-100 text-wwc-neutral-600 p-3 rounded-lg hover:bg-red-600 hover:text-white transition-colors duration-200 flex items-center justify-center"
                           title="YouTube">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
