@extends('layouts.public')

@section('title', 'Processing Payment - Warzone World Championship')
@section('description', 'Your order is being processed. Please wait while we redirect you to our secure payment gateway.')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-wwc-primary to-wwc-primary-dark">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white font-display mb-6">
                Processing Payment
            </h1>
            <p class="text-xl text-wwc-primary-light max-w-3xl mx-auto">
                Please wait while we prepare your secure payment gateway.
            </p>
        </div>
    </div>
</div>

<!-- Loading Section -->
<div class="py-20 bg-white">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg border border-wwc-neutral-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-wwc-primary px-8 py-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class='bx bx-loader-alt text-white text-xl animate-spin'></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white font-display">Preparing Payment</h2>
                        <p class="text-wwc-primary-light text-sm">Setting up secure payment gateway</p>
                    </div>
                </div>
            </div>

            <!-- Loading Content -->
            <div class="px-8 py-12 text-center">
                <!-- Loading Animation -->
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-wwc-primary-light rounded-full mb-6">
                        <i class='bx bx-credit-card text-3xl text-wwc-primary animate-pulse'></i>
                    </div>
                    
                    <!-- Spinning Loader -->
                    <div class="relative">
                        <div class="w-16 h-16 border-4 border-wwc-primary-light border-t-wwc-primary rounded-full animate-spin mx-auto"></div>
                    </div>
                </div>

                <!-- Loading Message -->
                <div class="space-y-4">
                    <h3 class="text-2xl font-bold text-wwc-neutral-800">Creating Your Order</h3>
                    <p class="text-wwc-neutral-600 text-lg">
                        We're preparing your secure payment gateway. This will only take a moment.
                    </p>
                </div>

                <!-- Progress Steps -->
                <div class="mt-8 space-y-4">
                    <div class="flex items-center justify-center space-x-3 text-sm text-wwc-neutral-600">
                        <i class='bx bx-check-circle text-green-500'></i>
                        <span>Order created successfully</span>
                    </div>
                    <div class="flex items-center justify-center space-x-3 text-sm text-wwc-neutral-600">
                        <i class='bx bx-loader-alt text-wwc-primary animate-spin'></i>
                        <span>Preparing payment gateway...</span>
                    </div>
                    <div class="flex items-center justify-center space-x-3 text-sm text-wwc-neutral-400">
                        <i class='bx bx-credit-card'></i>
                        <span>Redirecting to secure payment</span>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="mt-8 bg-wwc-primary-light border border-wwc-primary rounded-lg p-4">
                    <div class="flex items-start">
                        <i class='bx bx-shield-check text-wwc-primary text-lg mr-3 mt-0.5'></i>
                        <div class="text-sm text-wwc-neutral-700">
                            <h3 class="font-medium mb-2">Secure Payment Processing</h3>
                            <p>Your payment information is encrypted and processed securely. You will be redirected to our trusted payment partner.</p>
                        </div>
                    </div>
                </div>

                <!-- Manual Redirect Button (fallback) -->
                <div class="mt-8">
                    <p class="text-sm text-wwc-neutral-500 mb-4">
                        If you're not redirected automatically, click the button below:
                    </p>
                    <button onclick="redirectToPayment()" 
                            class="bg-wwc-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-wwc-primary-dark transition-colors duration-200">
                        Continue to Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-redirect to payment page after a short delay
setTimeout(function() {
    redirectToPayment();
}, 2000);

function redirectToPayment() {
    // Show loading state on button
    const button = document.querySelector('button');
    if (button) {
        button.disabled = true;
        button.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i>Redirecting...';
    }
    
    // Redirect to payment page
    window.location.href = '{{ route("public.tickets.payment", $order) }}';
}

// Show a message if user tries to leave
window.addEventListener('beforeunload', function(e) {
    e.preventDefault();
    e.returnValue = 'Your order is being processed. Are you sure you want to leave?';
});
</script>
@endsection
