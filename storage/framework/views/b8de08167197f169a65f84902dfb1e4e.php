<?php $__env->startSection('title', 'Create Ticket Type'); ?>
<?php $__env->startSection('page-title', 'Create Ticket Type'); ?>

<?php $__env->startSection('content'); ?>
<!-- Professional Ticket Type Creation with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Header Section -->
            <div class="flex justify-end items-center mb-6">
                <a href="<?php echo e(route('admin.tickets.index')); ?>" 
                    class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-arrow-back text-sm mr-2'></i>
                    Back to Ticket Types
                </a>
            </div>

            <!-- Form Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Ticket Type Details</h3>
                        <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                            <i class='bx bx-map text-sm'></i>
                            <span>Fill in the ticket type information below</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form action="<?php echo e(route('admin.tickets.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
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
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-1">
                                <!-- Event -->
                                <div>
                                    <label for="event_id" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Event <span class="text-wwc-error">*</span>
                                    </label>
                                    <select name="event_id" id="event_id" required
                                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['event_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">Select an event</option>
                                        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($event->id); ?>" <?php echo e(old('event_id') == $event->id ? 'selected' : ''); ?>>
                                                <?php echo e($event->name); ?> - <?php echo e($event->date_time->format('M j, Y')); ?> (<?php echo e(ucfirst(str_replace('_', ' ', $event->status))); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['event_id'];
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

                                <!-- Ticket Type Name -->
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Ticket Type Name <span class="text-wwc-error">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" required
                                           value="<?php echo e(old('name')); ?>"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           placeholder="Enter ticket type name (e.g., VIP, General, Student)">
                                    <p class="text-xs text-wwc-neutral-500 mt-1">Enter the name for this ticket type/category</p>
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

                                <!-- Price -->
                                <div>
                                    <label for="price" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                                        Price <span class="text-wwc-error">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-wwc-neutral-500 text-sm">RM</span>
                                        </div>
                                        <input type="number" name="price" id="price" required step="0.01" min="0"
                                               value="<?php echo e(old('price')); ?>"
                                               class="block w-full pl-10 pr-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               placeholder="0.00">
                                    </div>
                                    <p class="text-xs text-wwc-neutral-500 mt-1">Enter the price per seat for this ticket type</p>
                                    <?php $__errorArgs = ['price'];
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
                                    <input type="number" name="total_seats" id="total_seats" required min="1" max="10000"
                                           value="<?php echo e(old('total_seats')); ?>"
                                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm <?php $__errorArgs = ['total_seats'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error focus:ring-wwc-error focus:border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           placeholder="Enter total number of seats">
                                    <p class="text-xs text-wwc-neutral-500 mt-1">Enter the total number of seats available for this ticket type</p>
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
                                        <option value="">Select status</option>
                                        <option value="active" <?php echo e(old('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                                        <option value="inactive" <?php echo e(old('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                                        <option value="sold_out" <?php echo e(old('status') == 'sold_out' ? 'selected' : ''); ?>>Sold Out</option>
                                    </select>
                                    <p class="text-xs text-wwc-neutral-500 mt-1">Set the initial status for this ticket type</p>
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

                                <!-- Description -->
                                <div>
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
                                              placeholder="Enter ticket type description (e.g., Premium seating with exclusive access)"><?php echo e(old('description')); ?></textarea>
                                    <p class="text-xs text-wwc-neutral-500 mt-1">Optional description for this ticket type</p>
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

                            <!-- Combo Ticket Settings -->
                            <div class="border-t border-wwc-neutral-200 pt-6">
                                <div class="flex items-center mb-4">
                                    <h4 class="text-lg font-semibold text-wwc-neutral-900">Combo Ticket Settings</h4>
                                    <span class="ml-2 text-xs text-wwc-neutral-500">(Optional)</span>
                                </div>
                                
                                <div class="grid grid-cols-1 gap-6">
                                    <!-- Combo Ticket Checkbox -->
                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_combo" id="is_combo" value="1"
                                                   <?php echo e(old('is_combo') ? 'checked' : ''); ?>

                                                   class="h-4 w-4 text-wwc-primary focus:ring-wwc-primary border-wwc-neutral-300 rounded <?php $__errorArgs = ['is_combo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-wwc-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                            <span class="ml-2 text-sm font-semibold text-wwc-neutral-900">This is a Combo Ticket Type</span>
                                        </label>
                                        <p class="text-xs text-wwc-neutral-500 mt-1">Check if this ticket type is for multi-day combo purchases</p>
                                        <?php $__errorArgs = ['is_combo'];
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

                        <!-- Preview Section -->
                        <div class="border-t border-wwc-neutral-200 pt-6">
                            <div class="flex items-center mb-4">
                                <h4 class="text-lg font-semibold text-wwc-neutral-900">Preview</h4>
                                <span class="ml-2 text-xs text-wwc-neutral-500">(Live preview)</span>
                            </div>
                            
                            <div class="bg-wwc-neutral-50 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-sm font-semibold text-wwc-neutral-600 mb-1">Ticket Type Name</div>
                                        <div id="preview-name" class="text-base font-medium text-wwc-neutral-900">-</div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-wwc-neutral-600 mb-1">Price</div>
                                        <div id="preview-price" class="text-base font-medium text-wwc-neutral-900">-</div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-wwc-neutral-600 mb-1">Total Seats</div>
                                        <div id="preview-seats" class="text-base font-medium text-wwc-neutral-900">-</div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-wwc-neutral-600 mb-1">Type</div>
                                        <div id="preview-type" class="text-base font-medium text-wwc-neutral-900">-</div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="text-sm font-semibold text-wwc-neutral-600 mb-1">Description</div>
                                    <div id="preview-description" class="text-sm text-wwc-neutral-700">-</div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-wwc-neutral-200 mt-6">
                            <a href="<?php echo e(route('admin.tickets.index')); ?>" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 rounded-lg shadow-sm text-sm font-semibold text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-plus text-sm mr-2'></i>
                                Create Ticket Type
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
    const nameInput = document.getElementById('name');
    const priceInput = document.getElementById('price');
    const totalSeatsInput = document.getElementById('total_seats');
    const isComboCheckbox = document.getElementById('is_combo');
    
    // Focus on price input when name is entered
    nameInput.addEventListener('blur', function() {
        if (this.value.trim() && !priceInput.value) {
            priceInput.focus();
        }
    });
    
    // Focus on total seats when price is entered
    priceInput.addEventListener('blur', function() {
        if (this.value && !totalSeatsInput.value) {
            totalSeatsInput.focus();
        }
    });

    // Handle combo checkbox change
    isComboCheckbox.addEventListener('change', function() {
        const isCombo = this.checked;
        
        // Update name placeholder based on combo status
        if (isCombo) {
            nameInput.placeholder = 'Enter combo ticket type name (e.g., 2-Day Combo Pass, Weekend Combo)';
            // Suggest combo-related names
            if (!nameInput.value) {
                nameInput.value = 'Combo Pass';
            }
        } else {
            nameInput.placeholder = 'Enter ticket type name (e.g., VIP, General, Student)';
        }
        
        // Update description placeholder
        const descriptionTextarea = document.getElementById('description');
        if (isCombo) {
            descriptionTextarea.placeholder = 'Enter combo ticket description (e.g., Access to both days of the event with exclusive benefits)';
        } else {
            descriptionTextarea.placeholder = 'Enter ticket type description (e.g., Premium seating with exclusive access)';
        }
    });

    // Auto-suggest combo names when typing
    nameInput.addEventListener('input', function() {
        if (isComboCheckbox.checked) {
            const value = this.value.toLowerCase();
            if (value.includes('combo') || value.includes('pass') || value.includes('day')) {
                // Already contains combo-related terms
                return;
            }
            
            // Suggest combo-related names
            if (value.includes('vip')) {
                this.value = 'VIP Combo Pass';
            } else if (value.includes('general')) {
                this.value = 'General Combo Pass';
            } else if (value.includes('student')) {
                this.value = 'Student Combo Pass';
            }
        }
        
        // Update preview
        updatePreview();
    });

    // Update preview function
    function updatePreview() {
        const name = nameInput.value || '-';
        const price = priceInput.value ? `RM${parseFloat(priceInput.value).toLocaleString()}` : '-';
        const seats = totalSeatsInput.value || '-';
        const isCombo = isComboCheckbox.checked;
        const type = isCombo ? 'Combo Ticket' : 'Regular Ticket';
        const description = document.getElementById('description').value || '-';
        
        document.getElementById('preview-name').textContent = name;
        document.getElementById('preview-price').textContent = price;
        document.getElementById('preview-seats').textContent = seats;
        document.getElementById('preview-type').textContent = type;
        document.getElementById('preview-description').textContent = description;
        
        // Add visual indicator for combo
        const typeElement = document.getElementById('preview-type');
        if (isCombo) {
            typeElement.className = 'text-base font-medium text-wwc-accent';
        } else {
            typeElement.className = 'text-base font-medium text-wwc-neutral-900';
        }
    }

    // Add event listeners for preview updates
    priceInput.addEventListener('input', updatePreview);
    totalSeatsInput.addEventListener('input', updatePreview);
    isComboCheckbox.addEventListener('change', updatePreview);
    document.getElementById('description').addEventListener('input', updatePreview);
    
    // Initial preview update
    updatePreview();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/administrator/Desktop/Project/Github/warzone-ticketing-system/resources/views/admin/tickets/create.blade.php ENDPATH**/ ?>