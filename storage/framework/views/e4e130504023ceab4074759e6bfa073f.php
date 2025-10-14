<?php $__env->startSection('title', 'Support & Help'); ?>
<?php $__env->startSection('description', 'Get help with your tickets, orders, and account in your Warzone World Championship customer portal.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional Support & Help -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Header Section -->
    <div class="bg-white border-b border-wwc-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-wwc-neutral-900 font-display">Support & Help</h1>
                    <p class="text-wwc-neutral-600 mt-1">Get help with your tickets, orders, and account</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="<?php echo e(route('customer.tickets')); ?>" 
                       class="inline-flex items-center px-4 py-2 bg-wwc-primary text-white rounded-lg text-sm font-semibold hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        My Tickets
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Quick Help Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Contact Support -->
            <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center mb-4">
                    <div class="h-12 w-12 rounded-lg bg-wwc-primary flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-wwc-neutral-900">Contact Support</h3>
                        <p class="text-sm text-wwc-neutral-600">Get help from our support team</p>
                    </div>
                </div>
                <p class="text-sm text-wwc-neutral-600 mb-4">Need immediate assistance? Our support team is here to help you with any questions or issues.</p>
                <a href="mailto:support@warzone-ticketing.com" 
                   class="inline-flex items-center px-4 py-2 text-sm font-semibold text-wwc-primary hover:bg-wwc-primary-light hover:text-wwc-primary-dark rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Email Support
                </a>
            </div>

            <!-- FAQ -->
            <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center mb-4">
                    <div class="h-12 w-12 rounded-lg bg-wwc-accent flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-wwc-neutral-900">Frequently Asked Questions</h3>
                        <p class="text-sm text-wwc-neutral-600">Find answers to common questions</p>
                    </div>
                </div>
                <p class="text-sm text-wwc-neutral-600 mb-4">Browse our comprehensive FAQ section to find quick answers to the most common questions.</p>
                <button onclick="scrollToFAQ()" 
                        class="inline-flex items-center px-4 py-2 text-sm font-semibold text-wwc-accent hover:bg-wwc-accent-light hover:text-wwc-accent-dark rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-accent transition-colors duration-200">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    View FAQ
                </button>
            </div>

            <!-- Ticket Issues -->
            <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center mb-4">
                    <div class="h-12 w-12 rounded-lg bg-wwc-info flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-wwc-neutral-900">Ticket Issues</h3>
                        <p class="text-sm text-wwc-neutral-600">Problems with your tickets?</p>
                    </div>
                </div>
                <p class="text-sm text-wwc-neutral-600 mb-4">Having trouble with your tickets? We can help with lost tickets, refunds, and more.</p>
                <a href="<?php echo e(route('customer.tickets')); ?>" 
                   class="inline-flex items-center px-4 py-2 text-sm font-semibold text-wwc-info hover:bg-wwc-info-light hover:text-wwc-info-dark rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-info transition-colors duration-200">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                    View My Tickets
                </a>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200 mb-8">
            <div class="px-6 py-4 border-b border-wwc-neutral-200">
                <h2 class="text-lg font-semibold text-wwc-neutral-900">Contact Information</h2>
                <p class="text-wwc-neutral-600 text-sm mt-1">Get in touch with our support team</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-4">Support Hours</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-wwc-neutral-600">Monday - Friday</span>
                                <span class="text-sm font-semibold text-wwc-neutral-900">9:00 AM - 6:00 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-wwc-neutral-600">Saturday</span>
                                <span class="text-sm font-semibold text-wwc-neutral-900">10:00 AM - 4:00 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-wwc-neutral-600">Sunday</span>
                                <span class="text-sm font-semibold text-wwc-neutral-900">Closed</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-4">Contact Methods</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-wwc-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-wwc-neutral-900">Email</p>
                                    <p class="text-sm text-wwc-neutral-600">support@warzone-ticketing.com</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-wwc-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-wwc-neutral-900">Phone</p>
                                    <p class="text-sm text-wwc-neutral-600">+1 (555) 123-4567</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-wwc-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-wwc-neutral-900">Address</p>
                                    <p class="text-sm text-wwc-neutral-600">123 Event Street, City, State 12345</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div id="faq" class="bg-white rounded-xl shadow-sm border border-wwc-neutral-200">
            <div class="px-6 py-4 border-b border-wwc-neutral-200">
                <h2 class="text-lg font-semibold text-wwc-neutral-900">Frequently Asked Questions</h2>
                <p class="text-wwc-neutral-600 text-sm mt-1">Find answers to the most common questions</p>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    <!-- FAQ Item 1 -->
                    <div class="border-b border-wwc-neutral-200 pb-6">
                        <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-3">How do I purchase tickets?</h3>
                        <p class="text-sm text-wwc-neutral-600 mb-3">To purchase tickets, simply browse our events, select the event you want to attend, choose your seats, and complete the checkout process. You'll receive your tickets via email immediately after purchase.</p>
                        <div class="bg-wwc-primary-light rounded-lg p-4">
                            <p class="text-sm text-wwc-primary font-semibold">💡 Tip: Make sure to check the event date and time before purchasing!</p>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="border-b border-wwc-neutral-200 pb-6">
                        <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-3">Can I get a refund for my tickets?</h3>
                        <p class="text-sm text-wwc-neutral-600 mb-3">Refund policies vary by event. Generally, refunds are available up to 48 hours before the event. Please check the specific event details for refund information.</p>
                        <div class="bg-wwc-warning-light rounded-lg p-4">
                            <p class="text-sm text-wwc-warning font-semibold">⚠️ Note: Service fees are non-refundable in most cases.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="border-b border-wwc-neutral-200 pb-6">
                        <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-3">What if I lose my tickets?</h3>
                        <p class="text-sm text-wwc-neutral-600 mb-3">Don't worry! You can access your tickets anytime through your account. Simply log in and go to "My Tickets" to view and download your tickets again.</p>
                        <div class="bg-wwc-info-light rounded-lg p-4">
                            <p class="text-sm text-wwc-info font-semibold">ℹ️ Your tickets are also sent to your email address for backup.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="border-b border-wwc-neutral-200 pb-6">
                        <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-3">How do I change my account information?</h3>
                        <p class="text-sm text-wwc-neutral-600 mb-3">You can update your account information by going to your profile settings. Click on your name in the top right corner and select "Profile Settings" to make changes.</p>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div class="border-b border-wwc-neutral-200 pb-6">
                        <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-3">What payment methods do you accept?</h3>
                        <p class="text-sm text-wwc-neutral-600 mb-3">We accept all major credit cards (Visa, MasterCard, American Express) and PayPal. All payments are processed securely through our payment partners.</p>
                    </div>

                    <!-- FAQ Item 6 -->
                    <div class="pb-6">
                        <h3 class="text-lg font-semibold text-wwc-neutral-900 mb-3">How do I contact customer support?</h3>
                        <p class="text-sm text-wwc-neutral-600 mb-3">You can contact our support team via email at support@warzone-ticketing.com or by phone at +1 (555) 123-4567. We're available Monday through Friday, 9 AM to 6 PM.</p>
                        <div class="bg-wwc-success-light rounded-lg p-4">
                            <p class="text-sm text-wwc-success font-semibold">✅ We typically respond to emails within 24 hours during business days.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function scrollToFAQ() {
    document.getElementById('faq').scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/customer/support.blade.php ENDPATH**/ ?>