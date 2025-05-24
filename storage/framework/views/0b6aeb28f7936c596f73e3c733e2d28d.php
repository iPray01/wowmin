

<?php $__env->startSection('title', 'View Tithe Record'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">View Tithe Record</h1>
        <div class="space-x-2">
            <a href="<?php echo e(route('finance.tithe.edit', $tithe)); ?>" class="bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-4 rounded">
                Edit Record
            </a>
            <a href="<?php echo e(route('finance.tithe.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                Back to List
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Member Information -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Member Information</h2>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Member Name</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900"><?php echo e($tithe->member->name); ?></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Member ID</dt>
                        <dd class="mt-1 text-gray-900"><?php echo e($tithe->member->member_id); ?></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Income Type</dt>
                        <dd class="mt-1 text-gray-900"><?php echo e(ucfirst($tithe->income_type)); ?></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Gross Income</dt>
                        <dd class="mt-1 text-gray-900">GH₵<?php echo e(number_format($tithe->gross_income, 2)); ?></dd>
                    </div>
                </dl>
            </div>

            <!-- Tithe Details -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Tithe Details</h2>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tithe Amount</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">GH₵<?php echo e(number_format($tithe->tithe_amount, 2)); ?></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Additional Offering</dt>
                        <dd class="mt-1 text-gray-900">GH₵<?php echo e(number_format($tithe->additional_offering, 2)); ?></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                        <dd class="mt-1 text-lg font-semibold" style="color: var(--color-teal)">GH₵<?php echo e(number_format($tithe->total_amount, 2)); ?></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tithe Percentage</dt>
                        <dd class="mt-1 text-gray-900"><?php echo e($tithe->tithe_percentage); ?>%</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Payment Information</h2>
            <dl class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Payment Date</dt>
                    <dd class="mt-1 text-gray-900"><?php echo e($tithe->payment_date->format('F d, Y')); ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                    <dd class="mt-1 text-gray-900"><?php echo e(ucfirst(str_replace('_', ' ', $tithe->payment_method))); ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php echo e($tithe->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                            <?php echo e(ucfirst($tithe->status)); ?>

                        </span>
                    </dd>
                </div>
                <?php if($tithe->check_number): ?>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Check Number</dt>
                    <dd class="mt-1 text-gray-900"><?php echo e($tithe->check_number); ?></dd>
                </div>
                <?php endif; ?>
                <?php if($tithe->transaction_id): ?>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Transaction ID</dt>
                    <dd class="mt-1 text-gray-900"><?php echo e($tithe->transaction_id); ?></dd>
                </div>
                <?php endif; ?>
            </dl>
        </div>

        <!-- Recurring Information -->
        <?php if($tithe->is_recurring): ?>
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Recurring Payment Details</h2>
            <dl class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Start Month</dt>
                    <dd class="mt-1 text-gray-900"><?php echo e($tithe->start_month->format('F Y')); ?></dd>
                </div>
                <?php if($tithe->end_month): ?>
                <div>
                    <dt class="text-sm font-medium text-gray-500">End Month</dt>
                    <dd class="mt-1 text-gray-900"><?php echo e($tithe->end_month->format('F Y')); ?></dd>
                </div>
                <?php endif; ?>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Preferred Payment Day</dt>
                    <dd class="mt-1 text-gray-900"><?php echo e($tithe->preferred_day); ?><?php echo e($tithe->preferred_day == 1 ? 'st' : ($tithe->preferred_day == 2 ? 'nd' : ($tithe->preferred_day == 3 ? 'rd' : 'th'))); ?></dd>
                </div>
            </dl>
        </div>
        <?php endif; ?>

        <!-- Notes -->
        <?php if($tithe->notes): ?>
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Additional Notes</h2>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-700 whitespace-pre-wrap"><?php echo e($tithe->notes); ?></p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Actions -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex justify-between">
                <div class="flex space-x-3">
                    <?php if($tithe->status !== 'completed'): ?>
                    <form action="<?php echo e(route('finance.tithe.update', $tithe)); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-check mr-2"></i>
                            Mark as Completed
                        </button>
                    </form>
                    <?php endif; ?>
                    <?php if($tithe->status === 'completed'): ?>
                    <a href="<?php echo e(route('finance.tithe.download-receipt', $tithe)); ?>" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-download mr-2"></i>
                        Download Receipt
                    </a>
                    <?php endif; ?>
                </div>
                <?php if($tithe->status !== 'completed'): ?>
                <form action="<?php echo e(route('finance.tithe.destroy', $tithe)); ?>" method="POST" class="inline"
                      onsubmit="return confirm('Are you sure you want to delete this tithe record? This action cannot be undone.')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Delete Record
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\finance\tithe\show.blade.php ENDPATH**/ ?>