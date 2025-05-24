

<?php $__env->startSection('title', 'Edit Donation'); ?>

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
                    <a href="<?php echo e(route('finance.donations.index')); ?>" class="text-gray-700 hover:text-blue-600">
                        Donations
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-500">Edit Donation</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Donation</h2>

            <form action="<?php echo e(route('finance.donations.update', $donation)); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <!-- Member Selection -->
                <div>
                    <label for="member_id" class="block text-sm font-medium text-gray-700">Member</label>
                    <select name="member_id" id="member_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring-teal sm:text-sm">
                        <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($member->id); ?>" <?php echo e($donation->member_id == $member->id ? 'selected' : ''); ?>>
                                <?php echo e($member->full_name); ?>

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

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="amount" id="amount" step="0.01" min="0" 
                               value="<?php echo e(old('amount', $donation->amount)); ?>"
                               class="pl-7 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring-teal sm:text-sm">
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

                <!-- Payment Method -->
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring-teal sm:text-sm">
                        <option value="cash" <?php echo e($donation->payment_method == 'cash' ? 'selected' : ''); ?>>Cash</option>
                        <option value="card" <?php echo e($donation->payment_method == 'card' ? 'selected' : ''); ?>>Card</option>
                        <option value="bank_transfer" <?php echo e($donation->payment_method == 'bank_transfer' ? 'selected' : ''); ?>>Bank Transfer</option>
                        <option value="check" <?php echo e($donation->payment_method == 'check' ? 'selected' : ''); ?>>Check</option>
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

                <!-- Donation Type -->
                <div>
                    <label for="donation_type" class="block text-sm font-medium text-gray-700">Donation Type</label>
                    <select name="donation_type" id="donation_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring-teal sm:text-sm">
                        <option value="tithe" <?php echo e($donation->donation_type == 'tithe' ? 'selected' : ''); ?>>Tithe</option>
                        <option value="offering" <?php echo e($donation->donation_type == 'offering' ? 'selected' : ''); ?>>Offering</option>
                        <option value="building_fund" <?php echo e($donation->donation_type == 'building_fund' ? 'selected' : ''); ?>>Building Fund</option>
                        <option value="missions" <?php echo e($donation->donation_type == 'missions' ? 'selected' : ''); ?>>Missions</option>
                        <option value="other" <?php echo e($donation->donation_type == 'other' ? 'selected' : ''); ?>>Other</option>
                    </select>
                    <?php $__errorArgs = ['donation_type'];
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

                <!-- Campaign -->
                <div>
                    <label for="campaign_id" class="block text-sm font-medium text-gray-700">Campaign (Optional)</label>
                    <select name="campaign_id" id="campaign_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring-teal sm:text-sm">
                        <option value="">No Campaign</option>
                        <?php $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($campaign->id); ?>" <?php echo e($donation->campaign_id == $campaign->id ? 'selected' : ''); ?>>
                                <?php echo e($campaign->name); ?>

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
                </div>

                <!-- Donation Date -->
                <div>
                    <label for="donation_date" class="block text-sm font-medium text-gray-700">Donation Date</label>
                    <input type="date" name="donation_date" id="donation_date" 
                           value="<?php echo e(old('donation_date', $donation->donation_date->format('Y-m-d'))); ?>"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring-teal sm:text-sm">
                    <?php $__errorArgs = ['donation_date'];
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

                <!-- Is Recurring -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="is_recurring" id="is_recurring" 
                               <?php echo e($donation->is_recurring ? 'checked' : ''); ?>

                               class="h-4 w-4 text-teal focus:ring-teal border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_recurring" class="font-medium text-gray-700">Recurring Donation</label>
                        <p class="text-gray-500">Check if this is a recurring donation</p>
                    </div>
                </div>

                <!-- Gift Aid -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="is_gift_aid_eligible" id="is_gift_aid_eligible" 
                               <?php echo e($donation->is_gift_aid_eligible ? 'checked' : ''); ?>

                               class="h-4 w-4 text-teal focus:ring-teal border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_gift_aid_eligible" class="font-medium text-gray-700">Gift Aid Eligible</label>
                        <p class="text-gray-500">Check if this donation is eligible for Gift Aid</p>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring-teal sm:text-sm"><?php echo e(old('notes', $donation->notes)); ?></textarea>
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

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3">
                    <a href="<?php echo e(route('finance.donations.show', $donation)); ?>" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal hover:bg-teal-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal">
                        Update Donation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\finance\donations\edit.blade.php ENDPATH**/ ?>