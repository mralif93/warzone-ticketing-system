<?php $__env->startSection('title', 'Edit Event'); ?>
<?php $__env->startSection('page-title', 'Edit Event'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional Event Editing with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <a href="<?php echo e(route('admin.events.show', $event)); ?>" 
                    class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                    Back to Event
                </a>
            </div>


            <!-- Form Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Edit Event Details</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-edit text-sm'></i>
                            <span>Update event information</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form action="<?php echo e(route('admin.events.update', $event)); ?>" method="POST">
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
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- Event Name -->
                                <div class="sm:col-span-2">
                                    <label for="name" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Event Name <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" required
                                           value="<?php echo e(old('name', $event->name)); ?>"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           placeholder="Enter event name">
                                    <?php $__errorArgs = ['name'];
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

                                <!-- Event Date & Time -->
                                <div>
                                    <label for="date_time" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Event Date & Time <span class="text-wwc-error">*</span>
                                        <span class="text-xs text-wwc-neutral-500 font-normal">(Main event date)</span>
                                    </label>
                                    <input type="datetime-local" name="date_time" id="date_time" required
                                           value="<?php echo e(old('date_time', $event->date_time->format('Y-m-d\TH:i'))); ?>"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['date_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['date_time'];
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

                                <!-- Start Date & Time -->
                                <div>
                                    <label for="start_date" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Start Date & Time
                                        <span class="text-xs text-wwc-neutral-500 font-normal">(Optional - for multi-day events)</span>
                                    </label>
                                    <input type="datetime-local" name="start_date" id="start_date"
                                           value="<?php echo e(old('start_date', $event->start_date ? $event->start_date->format('Y-m-d\TH:i') : '')); ?>"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['start_date'];
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

                                <!-- End Date & Time -->
                                <div>
                                    <label for="end_date" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        End Date & Time
                                        <span class="text-xs text-wwc-neutral-500 font-normal">(Optional - for multi-day events, max 2 days)</span>
                                    </label>
                                    <input type="datetime-local" name="end_date" id="end_date"
                                           value="<?php echo e(old('end_date', $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '')); ?>"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['end_date'];
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

                                <!-- Max Tickets Per Order -->
                                <div>
                                    <label for="max_tickets_per_order" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Max Tickets Per Order <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="max_tickets_per_order" id="max_tickets_per_order" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['max_tickets_per_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">Select limit</option>
                                        <?php for($i = 1; $i <= 20; $i++): ?>
                                            <option value="<?php echo e($i); ?>" <?php echo e(old('max_tickets_per_order', $event->max_tickets_per_order) == $i ? 'selected' : ''); ?>>
                                                <?php echo e($i); ?> ticket<?php echo e($i > 1 ? 's' : ''); ?>

                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                    <?php $__errorArgs = ['max_tickets_per_order'];
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

                                <!-- Total Seats -->
                                <div>
                                    <label for="total_seats" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Total Seats <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="number" name="total_seats" id="total_seats" required min="1"
                                           value="<?php echo e(old('total_seats', $event->total_seats)); ?>"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['total_seats'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           placeholder="Enter total number of seats">
                                    <?php $__errorArgs = ['total_seats'];
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

                                <!-- Venue -->
                                <div>
                                    <label for="venue" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Venue
                                    </label>
                                    <input type="text" name="venue" id="venue"
                                           value="<?php echo e(old('venue', $event->venue)); ?>"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['venue'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           placeholder="Enter venue name">
                                    <?php $__errorArgs = ['venue'];
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

                                <!-- Description -->
                                <div class="sm:col-span-2">
                                    <label for="description" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Description
                                    </label>
                                    <textarea name="description" id="description" rows="4"
                                              class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                              placeholder="Enter event description"><?php echo e(old('description', $event->description)); ?></textarea>
                                    <?php $__errorArgs = ['description'];
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

                            <!-- Combo Discount Section -->
                            <div class="border-t border-wwc-neutral-200 pt-6">
                                <div class="flex items-center mb-4">
                                    <h4 class="text-lg font-semibold text-wwc-neutral-900">Combo Discount Settings</h4>
                                    <span class="ml-2 text-xs text-wwc-neutral-500">(For multi-day events)</span>
                                </div>
                                
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <!-- Combo Discount Enabled -->
                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="combo_discount_enabled" id="combo_discount_enabled" value="1"
                                                   <?php echo e(old('combo_discount_enabled', $event->combo_discount_enabled) ? 'checked' : ''); ?>

                                                   class="h-4 w-4 text-wwc-primary focus:ring-wwc-primary border-wwc-neutral-300 rounded <?php $__errorArgs = ['combo_discount_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                            <span class="ml-2 text-sm font-semibold text-wwc-neutral-900">Enable Combo Discount</span>
                                        </label>
                                        <p class="text-xs text-wwc-neutral-500 mt-1">Allow customers to purchase tickets for multiple days with a discount</p>
                                        <?php $__errorArgs = ['combo_discount_enabled'];
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

                                    <!-- Combo Discount Percentage -->
                                    <div>
                                        <label for="combo_discount_percentage" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                            Combo Discount Percentage
                                        </label>
                                        <div class="relative">
                                            <input type="number" name="combo_discount_percentage" id="combo_discount_percentage" 
                                                   min="0" max="100" step="0.01"
                                                   value="<?php echo e(old('combo_discount_percentage', $event->combo_discount_percentage)); ?>"
                                                   class="block w-full px-3 py-2 pr-8 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['combo_discount_percentage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                   placeholder="10.00">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="text-wwc-neutral-500 text-sm">%</span>
                                            </div>
                                        </div>
                                        <p class="text-xs text-wwc-neutral-500 mt-1">Discount percentage for 2-day combo purchases (0-100%)</p>
                                        <?php $__errorArgs = ['combo_discount_percentage'];
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
                            </div>
                        </div>

                        <!-- Default Event Section -->
                        <div class="bg-wwc-neutral-50 rounded-lg p-4 border border-wwc-neutral-200">
                            <h3 class="text-sm font-semibold text-wwc-neutral-900 mb-3 flex items-center">
                                <i class='bx bx-star text-wwc-primary mr-2'></i>
                                Default Event Settings
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="default" id="default" value="1" 
                                               <?php echo e(old('default', $event->default) ? 'checked' : ''); ?>

                                               class="h-4 w-4 text-wwc-primary focus:ring-wwc-primary border-wwc-neutral-300 rounded">
                                        <span class="ml-2 text-sm text-wwc-neutral-700">
                                            Set as default event
                                        </span>
                                    </label>
                                    <p class="text-xs text-wwc-neutral-500 mt-1">
                                        Only one event can be set as default. Setting this will unset any existing default event.
                                    </p>
                                    <?php $__errorArgs = ['default'];
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
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-wwc-neutral-200 mt-6">
                            <a href="<?php echo e(route('admin.events.show', $event)); ?>" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-check text-sm mr-2'></i>
                                Update Event
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
    const dateInput = document.getElementById('date_time');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const comboDiscountEnabled = document.getElementById('combo_discount_enabled');
    const comboDiscountPercentage = document.getElementById('combo_discount_percentage');
    
    // Set minimum date to today
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    
    const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
    dateInput.min = minDateTime;
    startDateInput.min = minDateTime;
    endDateInput.min = minDateTime;
    
    // Handle combo discount checkbox
    function toggleComboDiscount() {
        const isEnabled = comboDiscountEnabled.checked;
        comboDiscountPercentage.disabled = !isEnabled;
        comboDiscountPercentage.style.opacity = isEnabled ? '1' : '0.5';
    }
    
    comboDiscountEnabled.addEventListener('change', toggleComboDiscount);
    toggleComboDiscount(); // Initial state
    
    // Validate multi-day events (max 2 days)
    function validateMultiDayEvent() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays > 2) {
                endDateInput.setCustomValidity('Multi-day events can only span a maximum of 2 days');
                endDateInput.reportValidity();
            } else {
                endDateInput.setCustomValidity('');
            }
        }
    }
    
    startDateInput.addEventListener('change', validateMultiDayEvent);
    endDateInput.addEventListener('change', validateMultiDayEvent);
    
    // Auto-set start_date when date_time changes
    dateInput.addEventListener('change', function() {
        if (!startDateInput.value) {
            startDateInput.value = dateInput.value;
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/admin/events/edit.blade.php ENDPATH**/ ?>