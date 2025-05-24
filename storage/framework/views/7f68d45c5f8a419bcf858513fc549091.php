

<?php $__env->startSection('title', 'Manage Pledges'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Pledges</h1>
        <a href="<?php echo e(route('finance.pledges.create')); ?>"
            class="bg-teal hover:bg-teal-dark text-white font-bold py-2 px-4 rounded inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            New Pledge
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form action="<?php echo e(route('finance.pledges.index')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Member Filter -->
            <div>
                <label for="member_id" class="block text-sm font-medium text-gray-700">Member</label>
                <select name="member_id" id="member_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-teal focus:border-teal sm:text-sm">
                    <option value="">All Members</option>
                    <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($member->id); ?>" <?php echo e(request('member_id') == $member->id ? 'selected' : ''); ?>>
                            <?php echo e($member->full_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Campaign Filter -->
            <div>
                <label for="campaign_id" class="block text-sm font-medium text-gray-700">Campaign</label>
                <select name="campaign_id" id="campaign_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-teal focus:border-teal sm:text-sm">
                    <option value="">All Campaigns</option>
                    <?php $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($campaign->id); ?>" <?php echo e(request('campaign_id') == $campaign->id ? 'selected' : ''); ?>>
                            <?php echo e($campaign->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-teal focus:border-teal sm:text-sm">
                    <option value="">All Status</option>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(request('status') == $key ? 'selected' : ''); ?>>
                            <?php echo e($label); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Filter Button -->
            <div class="flex items-end">
                <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Pledges Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Member
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Campaign
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Amount
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Progress
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Next Payment
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $pledges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pledge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                <?php echo e($pledge->member->full_name); ?>

                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <?php echo e($pledge->campaign ? $pledge->campaign->name : 'N/A'); ?>

                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <?php echo e(number_format($pledge->amount, 2)); ?>

                                <div class="text-xs text-gray-500">
                                    Paid: <?php echo e(number_format($pledge->amount_fulfilled, 2)); ?>

                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-teal h-2.5 rounded-full" style="width: <?php echo e($pledge->completion_percentage); ?>%"></div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                <?php echo e(number_format($pledge->completion_percentage, 1)); ?>%
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <?php if($pledge->next_payment_date && $pledge->status !== 'completed'): ?>
                                    <?php echo e($pledge->next_payment_date->format('M d, Y')); ?>

                                    <?php if($pledge->is_overdue): ?>
                                        <span class="text-xs text-red-600 font-medium">OVERDUE</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php echo e($pledge->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($pledge->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')); ?>">
                                <?php echo e(ucfirst($pledge->status)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="<?php echo e(route('finance.pledges.show', $pledge)); ?>" 
                               class="text-teal hover:text-teal-dark mr-3">View</a>
                            <a href="<?php echo e(route('finance.pledges.edit', $pledge)); ?>" 
                               class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            No pledges found
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        <?php echo e($pledges->links()); ?>

    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
        <!-- Total Pledges -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-sm font-medium text-gray-500">Total Pledges</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">
                <?php echo e(number_format($pledges->sum('amount'), 2)); ?>

            </div>
        </div>

        <!-- Total Fulfilled -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-sm font-medium text-gray-500">Total Fulfilled</div>
            <div class="mt-2 text-3xl font-bold text-green-600">
                <?php echo e(number_format($pledges->sum('amount_fulfilled'), 2)); ?>

            </div>
        </div>

        <!-- Active Pledges -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-sm font-medium text-gray-500">Active Pledges</div>
            <div class="mt-2 text-3xl font-bold text-blue-600">
                <?php echo e($pledges->where('status', 'active')->count()); ?>

            </div>
        </div>

        <!-- Completion Rate -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-sm font-medium text-gray-500">Overall Completion Rate</div>
            <div class="mt-2 text-3xl font-bold text-teal">
                <?php
                    $totalAmount = $pledges->sum('amount');
                    $totalFulfilled = $pledges->sum('amount_fulfilled');
                    $completionRate = $totalAmount > 0 ? ($totalFulfilled / $totalAmount) * 100 : 0;
                ?>
                <?php echo e(number_format($completionRate, 1)); ?>%
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\finance\pledges\index.blade.php ENDPATH**/ ?>