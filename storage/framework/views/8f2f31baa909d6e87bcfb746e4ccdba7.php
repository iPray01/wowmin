<?php $__env->startSection('title', 'Record Expense'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Create New Expense</h1>
        <a href="<?php echo e(route('finance.expenses.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="<?php echo e(route('finance.expenses.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <select name="category_id" id="category_id" required class="form-select w-full rounded-md border-gray-300">
                        <option value="">Select Category</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Department -->
                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Department *</label>
                    <select name="department_id" id="department_id" required class="form-select w-full rounded-md border-gray-300">
                        <option value="">Select Department</option>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($department->id); ?>" <?php echo e(old('department_id') == $department->id ? 'selected' : ''); ?>>
                                <?php echo e($department->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (₵) *</label>
                    <input type="number" name="amount" id="amount" required
                        class="form-input w-full rounded-md border-gray-300"
                        min="0.01" step="0.01" value="<?php echo e(old('amount')); ?>">
                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Expense Date -->
                <div>
                    <label for="expense_date" class="block text-sm font-medium text-gray-700 mb-1">Expense Date *</label>
                    <input type="date" name="expense_date" id="expense_date" required
                        class="form-input w-full rounded-md border-gray-300"
                        value="<?php echo e(old('expense_date', date('Y-m-d'))); ?>">
                    <?php $__errorArgs = ['expense_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <input type="text" name="description" id="description" required
                        class="form-input w-full rounded-md border-gray-300"
                        value="<?php echo e(old('description')); ?>"
                        placeholder="Brief description of the expense">
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Vendor -->
                <div>
                    <label for="vendor" class="block text-sm font-medium text-gray-700 mb-1">Vendor</label>
                    <input type="text" name="vendor" id="vendor"
                        class="form-input w-full rounded-md border-gray-300"
                        value="<?php echo e(old('vendor')); ?>"
                        placeholder="Vendor or supplier name">
                    <?php $__errorArgs = ['vendor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Payment Method -->
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method *</label>
                    <select name="payment_method" id="payment_method" required class="form-select w-full rounded-md border-gray-300">
                        <option value="">Select Payment Method</option>
                        <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(old('payment_method') == $key ? 'selected' : ''); ?>>
                                <?php echo e($value); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Reference Number -->
                <div>
                    <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-1">Reference Number</label>
                    <input type="text" name="reference_number" id="reference_number"
                        class="form-input w-full rounded-md border-gray-300"
                        value="<?php echo e(old('reference_number')); ?>"
                        placeholder="Check number, transaction ID, etc.">
                    <?php $__errorArgs = ['reference_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Receipt Upload -->
                <div>
                    <label for="receipt" class="block text-sm font-medium text-gray-700 mb-1">Receipt</label>
                    <input type="file" name="receipt" id="receipt"
                        class="form-input w-full rounded-md border-gray-300"
                        accept=".jpg,.jpeg,.png,.pdf">
                    <p class="mt-1 text-sm text-gray-500">Upload receipt (JPEG, PNG, or PDF, max 2MB)</p>
                    <?php $__errorArgs = ['receipt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="form-textarea w-full rounded-md border-gray-300"
                        placeholder="Additional notes or details"><?php echo e(old('notes')); ?></textarea>
                    <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Recurring Expense -->
                <div class="md:col-span-2">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_recurring" id="is_recurring"
                                class="form-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded"
                                <?php echo e(old('is_recurring') ? 'checked' : ''); ?>>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_recurring" class="font-medium text-gray-700">This is a recurring expense</label>
                        </div>
                    </div>
                </div>

                <!-- Recurring Options (hidden by default) -->
                <div id="recurring_options" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 <?php echo e(old('is_recurring') ? '' : 'hidden'); ?>">
                    <div>
                        <label for="recurring_frequency" class="block text-sm font-medium text-gray-700 mb-1">Frequency</label>
                        <select name="recurring_frequency" id="recurring_frequency" class="form-select w-full rounded-md border-gray-300">
                            <option value="weekly" <?php echo e(old('recurring_frequency') == 'weekly' ? 'selected' : ''); ?>>Weekly</option>
                            <option value="biweekly" <?php echo e(old('recurring_frequency') == 'biweekly' ? 'selected' : ''); ?>>Bi-weekly</option>
                            <option value="monthly" <?php echo e(old('recurring_frequency') == 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                            <option value="quarterly" <?php echo e(old('recurring_frequency') == 'quarterly' ? 'selected' : ''); ?>>Quarterly</option>
                            <option value="annually" <?php echo e(old('recurring_frequency') == 'annually' ? 'selected' : ''); ?>>Annually</option>
                        </select>
                    </div>

                    <div>
                        <label for="recurring_end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="recurring_end_date" id="recurring_end_date"
                            class="form-input w-full rounded-md border-gray-300"
                            value="<?php echo e(old('recurring_end_date')); ?>">
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" onclick="window.history.back()"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                    Create Expense
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.getElementById('is_recurring').addEventListener('change', function() {
        const recurringOptions = document.getElementById('recurring_options');
        recurringOptions.classList.toggle('hidden', !this.checked);
        
        const frequencyInput = document.getElementById('recurring_frequency');
        const endDateInput = document.getElementById('recurring_end_date');
        
        if (this.checked) {
            frequencyInput.setAttribute('required', 'required');
            endDateInput.setAttribute('required', 'required');
        } else {
            frequencyInput.removeAttribute('required');
            endDateInput.removeAttribute('required');
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\finance\expenses\create.blade.php ENDPATH**/ ?>