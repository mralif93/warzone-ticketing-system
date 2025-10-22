<?php $__env->startSection('title', 'Edit Order'); ?>
<?php $__env->startSection('page-title', 'Edit Order'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional Order Editing with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <a href="<?php echo e(route('admin.orders.show', $order)); ?>" 
                    class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                    Back to Order
                </a>
            </div>

            <!-- Form Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Edit Order #<?php echo e($order->order_number); ?></h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-edit text-sm'></i>
                            <span>Update order information</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form action="<?php echo e(route('admin.orders.update', $order)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <?php if($errors->any()): ?>
                            <div class="bg-wwc-error-light border border-wwc-error text-wwc-error px-4 py-3 rounded-lg mb-6">
                                <div class="flex items-start">
                                    <i class='bx bx-error text-lg mr-3 mt-0.5 flex-shrink-0'></i>
                                    <div>
                                        <h3 class="font-semibold mb-2 text-sm">Please correct the following errors:</h3>
                                        <ul class="list-disc list-inside space-y-1 text-sm">
                                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><?php echo e($error); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="space-y-6">
                            <!-- Order Information Section -->
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- Event (Read-only for edit) -->
                                <div>
                                    <label for="event_display" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Event
                                    </label>
                                    <div class="px-3 py-2 bg-wwc-neutral-50 border border-wwc-neutral-300 rounded-lg text-sm text-wwc-neutral-700">
                                        <?php echo e($order->purchaseTickets->first()->event->name ?? 'Unknown Event'); ?> - <?php echo e($order->purchaseTickets->first()->event->getFormattedDateRange() ?? ''); ?>

                                    </div>
                                    <p class="text-xs text-wwc-neutral-500 mt-1">Event cannot be changed after order creation</p>
                                </div>

                                <!-- Customer -->
                                <div>
                                    <label for="user_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Customer <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="user_id" id="user_id" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">Select Customer</option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>" <?php echo e(old('user_id', $order->user_id) == $user->id ? 'selected' : ''); ?>><?php echo e($user->name); ?> (<?php echo e($user->email); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-wwc-error text-xs mt-1 font-medium"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Status <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="status" id="status" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">Select Status</option>
                                        <option value="pending" <?php echo e(old('status', $order->status) == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                        <option value="paid" <?php echo e(old('status', $order->status) == 'paid' ? 'selected' : ''); ?>>Paid</option>
                                        <option value="cancelled" <?php echo e(old('status', $order->status) == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                                        <option value="refunded" <?php echo e(old('status', $order->status) == 'refunded' ? 'selected' : ''); ?>>Refunded</option>
                                    </select>
                                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-wwc-error text-xs mt-1 font-medium"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Customer Email -->
                                <div>
                                    <label for="customer_email" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Customer Email <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="email" name="customer_email" id="customer_email" required
                                           value="<?php echo e(old('customer_email', $order->customer_email)); ?>"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['customer_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           placeholder="Enter customer email">
                                    <?php $__errorArgs = ['customer_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-wwc-error text-xs mt-1 font-medium"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Payment Method -->
                                <div>
                                    <label for="payment_method" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Payment Method <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="payment_method" id="payment_method" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">Select Payment Method</option>
                                        <option value="bank_transfer" <?php echo e(old('payment_method', $order->payment_method) == 'bank_transfer' ? 'selected' : ''); ?>>Bank Transfer</option>
                                        <option value="online_banking" <?php echo e(old('payment_method', $order->payment_method) == 'online_banking' ? 'selected' : ''); ?>>Online Banking</option>
                                        <option value="qr_code_ewallet" <?php echo e(old('payment_method', $order->payment_method) == 'qr_code_ewallet' ? 'selected' : ''); ?>>QR Code / E-Wallet</option>
                                        <option value="debit_credit_card" <?php echo e(old('payment_method', $order->payment_method) == 'debit_credit_card' ? 'selected' : ''); ?>>Debit / Credit Card</option>
                                        <option value="others" <?php echo e(old('payment_method', $order->payment_method) == 'others' ? 'selected' : ''); ?>>Others</option>
                                    </select>
                                    <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-wwc-error text-xs mt-1 font-medium"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Notes -->
                                <div class="sm:col-span-2">
                                    <label for="notes" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Notes
                                    </label>
                                    <textarea name="notes" id="notes" rows="3"
                                              class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                              placeholder="Enter order notes"><?php echo e(old('notes', $order->notes)); ?></textarea>
                                    <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-wwc-error text-xs mt-1 font-medium"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- Current Tickets Display -->
                            <div class="border-t border-wwc-neutral-200 pt-6">
                                <div class="flex items-center mb-4">
                                    <h4 class="text-lg font-semibold text-wwc-neutral-900">Current Tickets</h4>
                                    <span class="ml-2 text-xs text-wwc-neutral-500">(<?php echo e($order->purchaseTickets->count()); ?> tickets)</span>
                                </div>
                                
                                <?php if($order->purchaseTickets->count() > 0): ?>
                                    <div class="space-y-3">
                                        <?php $__currentLoopData = $order->purchaseTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex items-center justify-between p-3 bg-wwc-neutral-50 rounded-lg border border-wwc-neutral-200">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-2 h-2 rounded-full bg-wwc-primary"></div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-wwc-neutral-900"><?php echo e($ticket->ticketType->name ?? 'Unknown Type'); ?></p>
                                                        <p class="text-xs text-wwc-neutral-600">
                                                            Zone: <?php echo e($ticket->zone); ?> | 
                                                            Price: RM<?php echo e(number_format($ticket->original_price, 2)); ?> | 
                                                            Status: <?php echo e($ticket->status); ?>

                                                            <?php if($ticket->event_day): ?>
                                                                | Day: <?php echo e($ticket->event_day_name ?? $ticket->event_day); ?>

                                                            <?php endif; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm font-semibold text-wwc-neutral-900">RM<?php echo e(number_format($ticket->original_price, 2)); ?></p>
                                                    <p class="text-xs text-wwc-neutral-600"><?php echo e($ticket->qrcode); ?></p>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-8 text-wwc-neutral-500">
                                        <i class='bx bx-ticket text-4xl mb-2'></i>
                                        <p>No tickets found for this order</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Order Summary -->
                            <div class="border-t border-wwc-neutral-200 pt-6">
                                <div class="flex items-center mb-4">
                                    <h4 class="text-lg font-semibold text-wwc-neutral-900">Order Summary</h4>
                                </div>
                                
                                <?php
                                    // Calculate combo discount logic like other templates
                                    $comboDiscountAmount = 0;
                                    $originalSubtotal = $order->subtotal;
                                    
                                    // Check if this was a combo purchase by checking if tickets span multiple days
                                    $dayNumbers = [];
                                    foreach ($order->purchaseTickets as $purchaseTicket) {
                                        if ($purchaseTicket->event_day_name) {
                                            preg_match('/Day (\d+)/', $purchaseTicket->event_day_name, $matches);
                                            if (isset($matches[1])) {
                                                $dayNumbers[] = (int)$matches[1];
                                            }
                                        }
                                    }
                                    $uniqueDays = array_unique($dayNumbers);
                                    
                                    // If we have tickets for multiple days and combo discount is enabled, calculate the discount
                                    if (count($uniqueDays) > 1 && $order->event && $order->event->combo_discount_enabled) {
                                        // Calculate original subtotal before discount using original_price
                                        $originalSubtotal = $order->purchaseTickets->sum('original_price');
                                        
                                        // Calculate what the discount would have been
                                        $comboDiscountAmount = $order->event->calculateComboDiscount($originalSubtotal);
                                    }
                                    
                                    // Calculate the corrected total amount
                                    $correctedTotal = $originalSubtotal - $comboDiscountAmount + ($serviceFeePercentage == 0 ? 0 : $order->service_fee) + ($taxPercentage == 0 ? 0 : $order->tax_amount);
                                ?>
                                
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                    <div class="p-4 bg-wwc-neutral-50 rounded-lg border border-wwc-neutral-200">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-lg bg-wwc-primary/20 flex items-center justify-center mr-3">
                                                <i class='bx bx-receipt text-wwc-primary text-sm'></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-wwc-neutral-900">Subtotal</p>
                                                <p class="text-lg font-bold text-wwc-neutral-900">RM<?php echo e(number_format($originalSubtotal, 2)); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="p-4 bg-wwc-neutral-50 rounded-lg border border-wwc-neutral-200">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-lg bg-wwc-primary/20 flex items-center justify-center mr-3">
                                                <i class='bx bx-gift text-wwc-primary text-sm'></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-wwc-neutral-900">Combo Discount</p>
                                                <p class="text-lg font-bold text-wwc-neutral-900">-RM<?php echo e(number_format($comboDiscountAmount, 2)); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="p-4 bg-wwc-neutral-50 rounded-lg border border-wwc-neutral-200">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-lg bg-wwc-primary/20 flex items-center justify-center mr-3">
                                                <i class='bx bx-credit-card text-wwc-primary text-sm'></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-wwc-neutral-900">Service Fee</p>
                                                <p class="text-lg font-bold text-wwc-neutral-900">RM<?php echo e(number_format($serviceFeePercentage == 0 ? 0 : $order->service_fee, 2)); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="p-4 bg-wwc-neutral-50 rounded-lg border border-wwc-neutral-200">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-lg bg-wwc-primary/20 flex items-center justify-center mr-3">
                                                <i class='bx bx-calculator text-wwc-primary text-sm'></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-wwc-neutral-900">Tax</p>
                                                <p class="text-lg font-bold text-wwc-neutral-900">RM<?php echo e(number_format($taxPercentage == 0 ? 0 : $order->tax_amount, 2)); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 p-4 bg-wwc-neutral-50 rounded-lg border border-wwc-neutral-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-lg bg-wwc-primary/20 flex items-center justify-center mr-3">
                                                <i class='bx bx-calculator text-wwc-primary text-sm'></i>
                                            </div>
                                            <span class="text-lg font-semibold text-wwc-neutral-900">Total Amount</span>
                                        </div>
                                        <span class="text-2xl font-bold text-wwc-neutral-900">RM<?php echo e(number_format($correctedTotal, 2)); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Ticket Quantity Management -->
                            <div class="border-t border-wwc-neutral-200 pt-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-semibold text-wwc-neutral-900">Adjust Ticket Quantity</h4>
                                    <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                        <i class='bx bx-edit text-sm'></i>
                                        <span>Modify ticket count</span>
                                    </div>
                                </div>

                                <!-- Current Order Summary -->
                                <div class="bg-wwc-neutral-50 rounded-lg p-4 mb-4">
                                    <h5 class="font-semibold text-wwc-neutral-900 mb-3">Current Order Summary</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="text-wwc-neutral-600">Total Tickets:</span>
                                            <span class="font-semibold text-wwc-neutral-900 ml-2"><?php echo e($order->purchaseTickets->count()); ?></span>
                                        </div>
                                        <div>
                                            <span class="text-wwc-neutral-600">Order Total:</span>
                                            <span class="font-semibold text-wwc-primary ml-2">RM<?php echo e(number_format($correctedTotal, 2)); ?></span>
                                        </div>
                                        <div>
                                            <span class="text-wwc-neutral-600">Status:</span>
                                            <span class="font-semibold text-wwc-neutral-900 ml-2"><?php echo e($order->status); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantity Adjustment -->
                                <div class="space-y-4">
                                    <div>
                                        <label for="ticket_quantity" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                            New Total Ticket Quantity
                                        </label>
                                        <div class="flex items-center space-x-4">
                                            <button type="button" id="decrease-quantity" class="w-10 h-10 bg-wwc-neutral-200 hover:bg-wwc-neutral-300 rounded-lg flex items-center justify-center transition-colors">
                                                <i class='bx bx-minus text-lg'></i>
                                            </button>
                                            <input type="number" name="ticket_quantity" id="ticket_quantity" 
                                                value="<?php echo e($order->purchaseTickets->count()); ?>" min="1" max="20"
                                                class="w-20 px-3 py-2 text-center border border-gray-300 bg-white text-gray-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                            <button type="button" id="increase-quantity" class="w-10 h-10 bg-wwc-neutral-200 hover:bg-wwc-neutral-300 rounded-lg flex items-center justify-center transition-colors">
                                                <i class='bx bx-plus text-lg'></i>
                                            </button>
                                        </div>
                                        <p class="text-xs text-wwc-neutral-500 mt-2">Current: <?php echo e($order->purchaseTickets->count()); ?> tickets | Adjust between 1-20 tickets</p>
                                    </div>

                                    <!-- Ticket Type Selection (if increasing quantity) -->
                                    <div id="ticket-type-selection" class="hidden">
                                        <label for="ticket_type_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                            Select Ticket Type for Additional Tickets
                                        </label>
                                        <select name="ticket_type_id" id="ticket_type_id"
                                                class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                            <option value="">Select Ticket Type</option>
                                            <?php if($order->purchaseTickets->isNotEmpty()): ?>
                                                <?php
                                                    $event = $order->purchaseTickets->first()->event;
                                                    $ticketTypes = $event->ticketTypes()->where('status', 'active')->get();
                                                ?>
                                                <?php $__currentLoopData = $ticketTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticketType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($ticketType->id); ?>" 
                                                            data-price="<?php echo e($ticketType->price); ?>"
                                                            data-available="<?php echo e($ticketType->available_seats); ?>">
                                                        <?php echo e($ticketType->name); ?> - RM<?php echo e(number_format($ticketType->price, 2)); ?>

                                                        <?php if($ticketType->available_seats > 0): ?>
                                                            (<?php echo e($ticketType->available_seats); ?> available)
                                                        <?php else: ?>
                                                            (Sold Out)
                                                        <?php endif; ?>
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                        <p class="text-xs text-wwc-neutral-500 mt-2">Choose the ticket type for any additional tickets</p>
                                    </div>

                                    <!-- Pricing Preview -->
                                    <div id="pricing-preview" class="bg-wwc-primary/5 border border-wwc-primary/20 rounded-lg p-4 hidden">
                                        <h6 class="font-semibold text-wwc-primary mb-2">Pricing Preview</h6>
                                        <div class="space-y-1 text-sm">
                                            <div class="flex justify-between">
                                                <span>Current Total:</span>
                                                <span class="font-semibold">RM<?php echo e(number_format($order->total_amount, 2)); ?></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>New Total:</span>
                                                <span class="font-semibold text-wwc-primary" id="new-total">RM0.00</span>
                                            </div>
                                            <div class="flex justify-between border-t border-wwc-primary/20 pt-1">
                                                <span>Difference:</span>
                                                <span class="font-semibold" id="price-difference">RM0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Warning Message -->
                                <div class="bg-wwc-warning-light border border-wwc-warning text-wwc-warning px-4 py-3 rounded-lg mt-4">
                                    <div class="flex items-start">
                                        <i class='bx bx-info-circle text-lg mr-3 mt-0.5 flex-shrink-0'></i>
                                        <div>
                                            <h4 class="font-semibold mb-1 text-sm">Important Notes:</h4>
                                            <ul class="list-disc list-inside space-y-1 text-xs">
                                                <li>Reducing quantity will permanently delete excess tickets</li>
                                                <li>Increasing quantity will create new tickets at the selected ticket type price</li>
                                                <li>Order total will be recalculated based on new quantity</li>
                                                <li>This action cannot be undone</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-wwc-neutral-200 mt-6">
                            <a href="<?php echo e(route('admin.orders.show', $order)); ?>" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-check text-sm mr-2'></i>
                                Update Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Order edit page doesn't need complex pricing calculations
    // since we're just editing order details, not creating new tickets
    
    console.log('Order edit page loaded successfully');
    
    // Add any specific functionality for order editing here
    // For example, status change notifications, etc.
    
    const statusSelect = document.getElementById('status');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            const newStatus = this.value;
            const currentStatus = '<?php echo e($order->status); ?>';
            
            if (newStatus !== currentStatus) {
                // Show confirmation for status changes
                if (confirm(`Are you sure you want to change the order status from "${currentStatus}" to "${newStatus}"?`)) {
                    // Status change will be processed on form submission
                    console.log(`Order status will be changed to: ${newStatus}`);
                } else {
                    // Revert to original status
                    this.value = currentStatus;
                }
            }
        });
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('ticket_quantity');
    const decreaseBtn = document.getElementById('decrease-quantity');
    const increaseBtn = document.getElementById('increase-quantity');
    const ticketTypeSelect = document.getElementById('ticket_type_id');
    const ticketTypeSelection = document.getElementById('ticket-type-selection');
    const pricingPreview = document.getElementById('pricing-preview');
    const newTotalSpan = document.getElementById('new-total');
    const priceDifferenceSpan = document.getElementById('price-difference');
    
    const currentQuantity = <?php echo e($order->purchaseTickets->count()); ?>;
    const currentTotal = <?php echo e($correctedTotal); ?>;
    
    // Settings
    const serviceFeePercentage = <?php echo e($serviceFeePercentage); ?>;
    const taxPercentage = <?php echo e($taxPercentage); ?>;
    
    // Combo discount info
    const comboDiscountAmount = <?php echo e($comboDiscountAmount); ?>;
    const originalSubtotal = <?php echo e($originalSubtotal); ?>;
    
    // Quantity control buttons
    decreaseBtn.addEventListener('click', function() {
        if (quantityInput.value > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1;
            updatePricingPreview();
        }
    });
    
    increaseBtn.addEventListener('click', function() {
        if (quantityInput.value < 20) {
            quantityInput.value = parseInt(quantityInput.value) + 1;
            updatePricingPreview();
        }
    });
    
    // Quantity input change
    quantityInput.addEventListener('input', function() {
        updatePricingPreview();
    });
    
    // Ticket type selection change
    ticketTypeSelect.addEventListener('change', function() {
        updatePricingPreview();
    });
    
    function updatePricingPreview() {
        const newQuantity = parseInt(quantityInput.value);
        const selectedTicketType = ticketTypeSelect.options[ticketTypeSelect.selectedIndex];
        
        // Show/hide ticket type selection based on quantity change
        if (newQuantity > currentQuantity) {
            ticketTypeSelection.classList.remove('hidden');
            if (!selectedTicketType.value) {
                pricingPreview.classList.add('hidden');
                return;
            }
        } else {
            ticketTypeSelection.classList.add('hidden');
        }
        
        // Calculate new total using the same logic as other templates
        let newTotal = currentTotal;
        
        if (newQuantity > currentQuantity && selectedTicketType.value) {
            const additionalTickets = newQuantity - currentQuantity;
            const ticketPrice = parseFloat(selectedTicketType.getAttribute('data-price'));
            const additionalCost = additionalTickets * ticketPrice;
            
            // Calculate service fee and tax on additional cost
            const serviceFee = additionalCost * (serviceFeePercentage / 100);
            const tax = (additionalCost + serviceFee) * (taxPercentage / 100);
            const totalAdditional = additionalCost + serviceFee + tax;
            
            newTotal = currentTotal + totalAdditional;
        } else if (newQuantity < currentQuantity) {
            // For reducing quantity, calculate based on original ticket price
            const averageTicketPrice = originalSubtotal / currentQuantity;
            const removedTickets = currentQuantity - newQuantity;
            const removedCost = removedTickets * averageTicketPrice;
            
            // Calculate new subtotal after removal
            const newSubtotal = originalSubtotal - removedCost;
            
            // Calculate new combo discount if applicable
            let newComboDiscount = 0;
            if (comboDiscountAmount > 0) {
                // Maintain the same discount percentage
                const discountPercentage = comboDiscountAmount / originalSubtotal;
                newComboDiscount = newSubtotal * discountPercentage;
            }
            
            // Calculate service fee and tax on new subtotal
            const newServiceFee = newSubtotal * (serviceFeePercentage / 100);
            const newTax = (newSubtotal + newServiceFee) * (taxPercentage / 100);
            
            // Calculate new total
            newTotal = newSubtotal - newComboDiscount + newServiceFee + newTax;
        }
        
        // Update pricing preview
        newTotalSpan.textContent = 'RM' + newTotal.toFixed(2);
        const difference = newTotal - currentTotal;
        priceDifferenceSpan.textContent = 'RM' + difference.toFixed(2);
        
        if (difference > 0) {
            priceDifferenceSpan.className = 'font-semibold text-wwc-success';
        } else if (difference < 0) {
            priceDifferenceSpan.className = 'font-semibold text-wwc-error';
        } else {
            priceDifferenceSpan.className = 'font-semibold text-wwc-neutral-600';
        }
        
        pricingPreview.classList.remove('hidden');
    }
    
    // Form submission validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const newQuantity = parseInt(quantityInput.value);
        const selectedTicketType = ticketTypeSelect.options[ticketTypeSelect.selectedIndex];
        
        if (newQuantity > currentQuantity && !selectedTicketType.value) {
            e.preventDefault();
            alert('Please select a ticket type for additional tickets.');
            return false;
        }
        
        if (newQuantity !== currentQuantity) {
            if (!confirm('Are you sure you want to change the ticket quantity? This action cannot be undone.')) {
                e.preventDefault();
                return false;
            }
        }
    });
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/admin/orders/edit.blade.php ENDPATH**/ ?>