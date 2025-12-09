<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Not Found - Warzone Ticketing System</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'wwc-primary': '#DC2626',
                        'wwc-primary-dark': '#B91C1C',
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-red-50 to-rose-100">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full text-center">
            <!-- Error Icon -->
            <div class="inline-flex items-center justify-center w-24 h-24 bg-red-500 rounded-full mb-6 shadow-lg">
                <i class="bx bx-search-alt-2 text-5xl text-white"></i>
            </div>
            
            <!-- Error Title -->
            <h1 class="text-5xl font-bold text-gray-900 mb-4">404</h1>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Page Not Found</h2>
            
            <!-- Error Message -->
            <p class="text-lg text-gray-600 mb-8">
                {{ $message ?? 'The page you are looking for could not be found. It may have been moved or deleted.' }}
            </p>
            
            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="{{ route('public.events') }}" 
                   class="inline-block w-full sm:w-auto px-6 py-3 bg-wwc-primary text-white font-semibold rounded-lg hover:bg-wwc-primary-dark transition-colors shadow-md">
                    <i class="bx bx-home mr-2"></i> Go to Homepage
                </a>
                <button onclick="window.history.back()" 
                        class="inline-block w-full sm:w-auto px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="bx bx-arrow-back mr-2"></i> Go Back
                </button>
            </div>
        </div>
    </div>
</body>
</html>



