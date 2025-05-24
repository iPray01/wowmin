<?php $__env->startSection('title', 'Create New Pledge'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-5" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="<?php echo e(route('dashboard')); ?>" class="text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="<?php echo e(route('finance.pledges.index')); ?>" class="text-gray-700 hover:text-blue-600">
                        Pledges
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-500">Create New Pledge</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Create New Pledge</h2>

            <form action="<?php echo e(route('finance.pledges.store')); ?>" method="POST" class="space-y-8">
                <?php echo csrf_field(); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Member Information -->
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Member Information</h3>
                            
                            <!-- Member Selection -->
                            <div class="space-y-4">
                                <div>
                                    <label for="member_id" class="block text-sm font-medium text-gray-700">Select Member</label>
                                    <select name="member_id" id="member_id" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            required>
                                        <option value="">Choose a member...</option>
                                        <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($member->id); ?>" <?php echo e(old('member_id') == $member->id ? 'selected' : ''); ?>>
                                                <?php echo e($member->full_name); ?> <!-- (<?php echo e($member->member_id); ?>) -->
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['member_id'];
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

                                <!-- Contact Preference -->
                                <div>
                                    <label for="contact_preference" class="block text-sm font-medium text-gray-700">Preferred Contact Method</label>
                                    <select name="contact_preference" id="contact_preference" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="email" <?php echo e(old('contact_preference') == 'email' ? 'selected' : ''); ?>>Email</option>
                                        <option value="sms" <?php echo e(old('contact_preference') == 'sms' ? 'selected' : ''); ?>>SMS</option>
                                        <option value="both" <?php echo e(old('contact_preference', 'both') == 'both' ? 'selected' : ''); ?>>Both</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Campaign Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Campaign Information</h3>
                            
                            <div>
                                <label for="campaign_id" class="block text-sm font-medium text-gray-700">Select Campaign</label>
                                <select name="campaign_id" id="campaign_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Choose a campaign (optional)...</option>
                                    <?php $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($campaign->id); ?>" 
                                                <?php echo e(old('campaign_id') == $campaign->id ? 'selected' : ''); ?>

                                                data-target="<?php echo e($campaign->target_amount); ?>"
                                                data-end-date="<?php echo e($campaign->end_date); ?>">
                                            <?php echo e($campaign->name); ?> 
                                            (Target: GH₵<?php echo e(number_format($campaign->target_amount, 2)); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['campaign_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div id="campaign_info" class="mt-2 text-sm text-gray-500"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Pledge Details -->
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pledge Details</h3>
                            
                            <!-- Amount -->
                            <div class="space-y-4">
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700">Pledge Amount</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">GH₵</span>
                                        </div>
                                        <input type="number" name="amount" id="amount" 
                                               value="<?php echo e(old('amount')); ?>"
                                               class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                               step="0.01" min="0" required>
                                    </div>
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

                                <!-- Payment Schedule -->
                                <div>
                                    <label for="payment_schedule" class="block text-sm font-medium text-gray-700">Payment Schedule</label>
                                    <select name="payment_schedule" id="payment_schedule" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            required>
                                        <option value="weekly" <?php echo e(old('payment_schedule') == 'weekly' ? 'selected' : ''); ?>>Weekly</option>
                                        <option value="biweekly" <?php echo e(old('payment_schedule') == 'biweekly' ? 'selected' : ''); ?>>Bi-weekly</option>
                                        <option value="monthly" <?php echo e(old('payment_schedule') == 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                                        <option value="quarterly" <?php echo e(old('payment_schedule') == 'quarterly' ? 'selected' : ''); ?>>Quarterly</option>
                                        <option value="custom" <?php echo e(old('payment_schedule') == 'custom' ? 'selected' : ''); ?>>Custom Schedule</option>
                                    </select>
                                </div>

                                <!-- Pledge Date -->
                                <div>
                                    <label for="pledge_date" class="block text-sm font-medium text-gray-700">Pledge Date</label>
                                    <input type="date" name="pledge_date" id="pledge_date" 
                                           value="<?php echo e(old('pledge_date', date('Y-m-d'))); ?>"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           required>
                                    <?php $__errorArgs = ['pledge_date'];
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

                                <!-- Start Date -->
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" 
                                           value="<?php echo e(old('start_date', date('Y-m-d'))); ?>"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           required>
                                    <?php $__errorArgs = ['start_date'];
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

                                <!-- End Date -->
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                    <input type="date" name="end_date" id="end_date" 
                                           value="<?php echo e(old('end_date')); ?>"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <?php $__errorArgs = ['end_date'];
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

                                <!-- Initial Payment -->
                                <div>
                                    <label for="initial_payment" class="block text-sm font-medium text-gray-700">Initial Payment (Optional)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">GH₵</span>
                                        </div>
                                        <input type="number" name="initial_payment" id="initial_payment" 
                                               value="<?php echo e(old('initial_payment')); ?>"
                                               class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                               step="0.01" min="0">
                                        <?php $__errorArgs = ['initial_payment'];
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
                                </div>
                            </div>
                        </div>

                        <!-- Additional Options -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Additional Options</h3>
                            
                            <div class="space-y-4">
                                <!-- Payment Method Preference -->
                                <div>
                                    <label for="preferred_payment_method" class="block text-sm font-medium text-gray-700">Preferred Payment Method</label>
                                    <select name="preferred_payment_method" id="preferred_payment_method" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="cash" <?php echo e(old('preferred_payment_method') == 'cash' ? 'selected' : ''); ?>>Cash</option>
                                        <option value="bank_transfer" <?php echo e(old('preferred_payment_method') == 'bank_transfer' ? 'selected' : ''); ?>>Bank Transfer</option>
                                        <option value="mobile_money" <?php echo e(old('preferred_payment_method') == 'mobile_money' ? 'selected' : ''); ?>>Mobile Money</option>
                                        <option value="check" <?php echo e(old('preferred_payment_method') == 'check' ? 'selected' : ''); ?>>Check</option>
                                    </select>
                                </div>

                                <!-- Reminders -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="send_reminders" id="send_reminders" 
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                               <?php echo e(old('send_reminders', true) ? 'checked' : ''); ?>>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="send_reminders" class="font-medium text-gray-700">Enable Payment Reminders</label>
                                        <p class="text-gray-500">Send automated reminders before payment due dates</p>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Additional Notes</label>
                                    <textarea name="notes" id="notes" rows="3" 
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                              placeholder="Any additional information about this pledge..."><?php echo e(old('notes')); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="<?php echo e(route('finance.pledges.index')); ?>" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create Pledge
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const campaignSelect = document.getElementById('campaign_id');
    const campaignInfo = document.getElementById('campaign_info');
    const endDateInput = document.getElementById('end_date');
    
    // Update campaign info when selection changes
    campaignSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const targetAmount = selectedOption.dataset.target;
        const campaignEndDate = selectedOption.dataset.endDate;
        
        if (targetAmount && campaignEndDate) {
            campaignInfo.innerHTML = `Campaign target: GH₵${parseFloat(targetAmount).toLocaleString()} <br> Ends on: ${new Date(campaignEndDate).toLocaleDateString()}`;
            endDateInput.max = campaignEndDate;
        } else {
            campaignInfo.innerHTML = '';
            endDateInput.removeAttribute('max');
        }
    });

    // Initialize custom payment schedule fields
    const paymentScheduleSelect = document.getElementById('payment_schedule');
    paymentScheduleSelect.addEventListener('change', function() {
        // Add logic for custom schedule if needed
    });

    // Initialize date validation
    const startDateInput = document.getElementById('start_date');
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\finance\pledges\create.blade.php ENDPATH**/ ?>