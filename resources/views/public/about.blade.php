@extends('layouts.public')

@section('title', 'About Us')
@section('description', 'Learn about Warzone World Championship SDN BHD - your premier destination for event tickets and entertainment experiences.')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white font-display mb-6">
                WARZONE
            </h1>
            <p class="text-xl text-wwc-primary-light max-w-3xl mx-auto">
                Your premier destination for thrilling fight events and unforgettable experiences. 
                Join us in creating memories that last a lifetime.
            </p>
        </div>
    </div>
</div>

<!-- Our Story Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-bold text-wwc-neutral-900 font-display mb-6">
                    Our Story
                </h2>
                <div class="space-y-4 text-wwc-neutral-600 text-justify">
                    <p>
                        Warzone 2025, taking place on December 6th and 7th at N9 Arena, Negeri Sembilan, will feature 
                        54 fighters representing Malaysia, Indonesia, and select international guests from India, China, 
                        and Thailand. Each bout is designed to ignite national pride and push every contender to their limits.
                    </p>
                    <p>
                        This isn't just a fight, it's a battle of national pride, where experience meets ambition, 
                        and only one will emerge victorious. Expect high-octane exchanges, raw emotion, and championship-level 
                        intensity as these warriors go head-to-head at the Warzone World Championship 2025.
                    </p>
                    <p>
                        Secure your seats today and witness history in the making as 54 elite fighters showcase their skills 
                        in an electrifying display of martial arts excellence.
                    </p>
                </div>
            </div>
            <div class="bg-wwc-primary-light rounded-2xl p-8 overflow-hidden">
                <img src="{{ asset('images/warzone poster sand.jpg') }}" 
                     alt="Warzone Championship 2025" 
                     class="w-full h-auto rounded-lg object-cover">
            </div>
        </div>
    </div>
</div>
@endsection