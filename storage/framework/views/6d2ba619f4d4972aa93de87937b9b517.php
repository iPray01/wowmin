<?php $__env->startSection('title', 'Tithe Records'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold">Tithe Records</h2>
        <a href="<?php echo e(route('finance.tithe.create')); ?>" class="btn btn-primary">
            Record New Tithe
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="card bg-white">
            <h3 class="text-sm font-medium text-gray-500">Total Tithes (YTD)</h3>
            <p class="mt-2 text-3xl font-bold" style="color: var(--color-teal)">
                $<?php echo e(number_format($statistics->total_tithes ?? 0, 2)); ?>

            </p>
            <p class="mt-1 text-sm text-gray-500">
                From <?php echo e($statistics->total_members ?? 0); ?> members
            </p>
        </div>

        <div class="card bg-white">
            <h3 class="text-sm font-medium text-gray-500">Average Monthly Tithe</h3>
            <p class="mt-2 text-3xl font-bold" style="color: var(--color-burgundy)">
                $<?php echo e(number_format($statistics->average_monthly ?? 0, 2)); ?>

            </p>
            <p class="mt-1 text-sm text-gray-500">
                Per active member
            </p>
        </div>

        <div class="card bg-white">
            <h3 class="text-sm font-medium text-gray-500">This Month</h3>
            <p class="mt-2 text-3xl font-bold" style="color: var(--color-crimson)">
                $<?php echo e(number_format($statistics->current_month ?? 0, 2)); ?>

            </p>
            <p class="mt-1 text-sm text-gray-500">
                <?php echo e($statistics->current_month_members ?? 0); ?> contributors
            </p>
        </div>

        <div class="card bg-white">
            <h3 class="text-sm font-medium text-gray-500">Growth Rate</h3>
            <p class="mt-2 text-3xl font-bold" style="color: var(--color-teal)">
                <?php echo e(number_format($statistics->growth_rate ?? 0, 1)); ?>%
            </p>
            <p class="mt-1 text-sm text-gray-500">
                Compared to last year
            </p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <form action="<?php echo e(route('finance.tithe.index')); ?>" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="member" class="form-label">Member</label>
                    <select name="member" id="member" class="form-input">
                        <option value="">All Members</option>
                        <?php $__currentLoopData = $members ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($member->id); ?>" <?php echo e(request('member') == $member->id ? 'selected' : ''); ?>>
                                <?php echo e($member->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label for="date_range" class="form-label">Date Range</label>
                    <select name="date_range" id="date_range" class="form-input">
                        <option value="this_month" <?php echo e(request('date_range') == 'this_month' ? 'selected' : ''); ?>>This Month</option>
                        <option value="last_month" <?php echo e(request('date_range') == 'last_month' ? 'selected' : ''); ?>>Last Month</option>
                        <option value="this_year" <?php echo e(request('date_range') == 'this_year' ? 'selected' : ''); ?>>This Year</option>
                        <option value="last_year" <?php echo e(request('date_range') == 'last_year' ? 'selected' : ''); ?>>Last Year</option>
                        <option value="custom" <?php echo e(request('date_range') == 'custom' ? 'selected' : ''); ?>>Custom Range</option>
                    </select>
                </div>

                <div id="custom_dates" class="grid grid-cols-2 gap-2 <?php echo e(request('date_range') == 'custom' ? '' : 'hidden'); ?>">
                    <div>
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-input" 
                               value="<?php echo e(request('start_date')); ?>">
                    </div>
                    <div>
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-input" 
                               value="<?php echo e(request('end_date')); ?>">
                    </div>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="btn btn-secondary w-full">Apply Filters</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tithe Records Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Income Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Payment Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $tithes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tithe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?php echo e($tithe->payment_date->format('M d, Y')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($tithe->member->name); ?></div>
                                <div class="text-sm text-gray-500">ID: <?php echo e($tithe->member->member_id); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="color: var(--color-teal)">
                                $<?php echo e(number_format($tithe->amount, 2)); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?php echo e(ucfirst($tithe->income_type)); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?php echo e(ucfirst($tithe->payment_method)); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php echo e($tithe->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                    <?php echo e(ucfirst($tithe->status)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('finance.tithe.show', $tithe)); ?>" class="text-indigo-600 hover:text-indigo-900">
                                        View
                                    </a>
                                    <a href="<?php echo e(route('finance.tithe.edit', $tithe)); ?>" class="text-yellow-600 hover:text-yellow-900">
                                        Edit
                                    </a>
                                    <?php if($tithe->status !== 'completed'): ?>
                                        <form action="<?php echo e(route('finance.tithe.delete', $tithe)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-900" 
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                Delete
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No tithe records found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if(isset($tithes) && $tithes->hasPages()): ?>
            <div class="mt-4">
                <?php echo e($tithes->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Handle date range selection
    document.getElementById('date_range').addEventListener('change', function() {
        document.getElementById('custom_dates').classList.toggle('hidden', this.value !== 'custom');
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\finance\tithe\index.blade.php ENDPATH**/ ?>