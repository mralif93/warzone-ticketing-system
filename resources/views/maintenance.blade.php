<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode - Warzone World Championship</title>
    <meta name="description" content="We're currently performing maintenance. We'll be back soon!">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Custom CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        .font-display {
            font-family: 'Inter', sans-serif;
        }
        
        .wwc-primary {
            color: #dc2626;
        }
        
        .bg-wwc-primary {
            background-color: #dc2626;
        }
        
        .wwc-primary-light {
            color: #fecaca;
        }
        
        .wwc-primary-dark {
            color: #991b1b;
        }
        
        .bg-wwc-primary-dark {
            background-color: #991b1b;
        }
        
        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        .animate-bounce-slow {
            animation: bounce 2s infinite;
        }
        
        .animate-spin-slow {
            animation: spin 3s linear infinite;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #dc2626, #991b1b, #7c2d12);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-red-900 to-gray-900 min-h-screen font-display">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="absolute inset-0 opacity-30">
        <div class="absolute top-10 left-10">
            <i class='bx bx-trophy text-6xl text-white opacity-20 animate-pulse-slow'></i>
        </div>
        <div class="absolute top-20 right-20">
            <i class='bx bx-medal text-5xl text-white opacity-15 animate-bounce-slow'></i>
        </div>
        <div class="absolute top-40 left-1/4">
            <i class='bx bx-star text-4xl text-white opacity-25 animate-spin-slow'></i>
        </div>
        <div class="absolute top-60 right-1/3">
            <i class='bx bx-crown text-5xl text-white opacity-20 animate-pulse-slow'></i>
        </div>
        <div class="absolute bottom-20 left-20">
            <i class='bx bx-trophy text-4xl text-white opacity-15 animate-bounce-slow'></i>
        </div>
        <div class="absolute bottom-40 right-10">
            <i class='bx bx-medal text-6xl text-white opacity-25 animate-spin-slow'></i>
        </div>
        <div class="absolute top-1/2 left-10">
            <i class='bx bx-star text-3xl text-white opacity-20 animate-pulse-slow'></i>
        </div>
        <div class="absolute top-1/3 right-10">
            <i class='bx bx-crown text-4xl text-white opacity-15 animate-bounce-slow'></i>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="relative min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Logo/Brand Icon -->
            <div class="mb-12">
                <div class="inline-flex items-center justify-center w-32 h-32 bg-white bg-opacity-10 rounded-full backdrop-blur-sm border border-white border-opacity-20 animate-pulse-slow">
                    <i class='bx bx-trophy text-6xl text-white'></i>
                </div>
            </div>
            
            <!-- Main Message -->
            <h1 class="text-6xl md:text-8xl font-bold text-white mb-8 tracking-tight">
                <span class="gradient-text">Maintenance</span>
            </h1>
            <h2 class="text-3xl md:text-4xl font-semibold text-wwc-primary-light mb-6">
                We're Working Hard
            </h2>
            <p class="text-xl md:text-2xl text-white text-opacity-90 max-w-3xl mx-auto leading-relaxed mb-12">
                We're currently performing scheduled maintenance to improve your experience. 
                We'll be back online shortly with exciting updates!
            </p>
            
            <!-- Progress Indicator -->
            <div class="mb-12">
                <div class="flex items-center justify-center space-x-4 mb-4">
                    <div class="w-3 h-3 bg-wwc-primary rounded-full animate-pulse"></div>
                    <div class="w-3 h-3 bg-wwc-primary rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
                    <div class="w-3 h-3 bg-wwc-primary rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
                </div>
                <p class="text-wwc-primary-light text-lg">System maintenance in progress...</p>
            </div>
            
            <!-- Features Grid -->
            <div class="grid md:grid-cols-3 gap-8 mb-16">
                <div class="bg-white bg-opacity-10 backdrop-blur-sm p-8 rounded-2xl border border-white border-opacity-20 hover:bg-opacity-20 transition-all duration-300">
                    <div class="text-wwc-primary text-5xl mb-4 animate-bounce-slow">
                        <i class='bx bx-cog'></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">System Updates</h3>
                    <p class="text-wwc-primary-light">Enhancing performance and security</p>
                </div>
                
                <div class="bg-white bg-opacity-10 backdrop-blur-sm p-8 rounded-2xl border border-white border-opacity-20 hover:bg-opacity-20 transition-all duration-300">
                    <div class="text-wwc-primary text-5xl mb-4 animate-bounce-slow" style="animation-delay: 0.2s;">
                        <i class='bx bx-shield-check'></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Security Enhancements</h3>
                    <p class="text-wwc-primary-light">Protecting your data and privacy</p>
                </div>
                
                <div class="bg-white bg-opacity-10 backdrop-blur-sm p-8 rounded-2xl border border-white border-opacity-20 hover:bg-opacity-20 transition-all duration-300">
                    <div class="text-wwc-primary text-5xl mb-4 animate-bounce-slow" style="animation-delay: 0.4s;">
                        <i class='bx bx-rocket'></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">New Features</h3>
                    <p class="text-wwc-primary-light">Exciting improvements coming soon</p>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="bg-white bg-opacity-5 backdrop-blur-sm p-8 rounded-2xl border border-white border-opacity-10">
                <h3 class="text-2xl font-bold text-white mb-4">Need Immediate Assistance?</h3>
                <p class="text-wwc-primary-light mb-6">
                    If you have urgent questions or need support, please contact us:
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-8">
                    <div class="flex items-center space-x-2">
                        <i class='bx bx-envelope text-wwc-primary text-xl'></i>
                        <span class="text-white">support@warzonechampionship.com</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class='bx bx-phone text-wwc-primary text-xl'></i>
                        <span class="text-white">+1 (555) 123-4567</span>
                    </div>
                </div>
            </div>
            
            <!-- Auto Refresh Notice -->
            <div class="mt-12">
                <p class="text-wwc-primary-light text-sm">
                    <i class='bx bx-refresh text-lg animate-spin-slow'></i>
                    This page will automatically refresh when maintenance is complete
                </p>
            </div>
        </div>
    </div>
    
    <!-- Auto Refresh Script -->
    <script>
        // Auto refresh every 30 seconds
        setTimeout(function() {
            location.reload();
        }, 30000);
        
        // Show loading animation
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Maintenance mode active - checking for updates...');
        });
    </script>
</body>
</html>
