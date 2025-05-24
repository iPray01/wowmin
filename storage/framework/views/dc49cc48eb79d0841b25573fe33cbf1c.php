<?php $__env->startSection('title', 'Harvest Offerings'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold">Harvest Offerings</h2>
        <a href="<?php echo e(route('finance.harvest.create')); ?>" class="btn btn-primary">
            Record New Harvest Offering
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="card bg-white">
            <h3 class="text-sm font-medium text-gray-500">Total Harvest (YTD)</h3>
            <p class="mt-2 text-3xl font-bold" style="color: var(--color-teal)">
                $<?php echo e(number_format($statistics->total_harvest ?? 0, 2)); ?>

            </p>
            <p class="mt-1 text-sm text-gray-500">
                From <?php echo e($statistics->total_events ?? 0); ?> events
            </p>
        </div>

        <div class="card bg-white">
            <h3 class="text-sm font-medium text-gray-500">Average Per Event</h3>
            <p class="mt-2 text-3xl font-bold" style="color: var(--color-burgundy)">
                $<?php echo e(number_format($statistics->average_per_event ?? 0, 2)); ?>

            </p>
            <p class="mt-1 text-sm text-gray-500">
                <?php echo e($statistics->total_contributors ?? 0); ?> contributors
            </p>
        </div>

        <div class="card bg-white">
            <h3 class="text-sm font-medium text-gray-500">Upcoming Events</h3>
            <p class="mt-2 text-3xl font-bold" style="color: var(--color-crimson)">
                <?php echo e($statistics->upcoming_events ?? 0); ?>

            </p>
            <p class="mt-1 text-sm text-gray-500">
                Next: <?php echo e($statistics->next_event_date ?? 'None scheduled'); ?>

            </p>
        </div>

        <div class="card bg-white">
            <h3 class="text-sm font-medium text-gray-500">Target Achievement</h3>
            <p class="mt-2 text-3xl font-bold" style="color: var(--color-teal)">
                <?php echo e(number_format($statistics->target_achievement ?? 0, 1)); ?>%
            </p>
            <p class="mt-1 text-sm text-gray-500">
                Of annual target
            </p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <form action="<?php echo e(route('finance.harvest.index')); ?>" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="harvest_type" class="form-label">Harvest Type</label>
                    <select name="harvest_type" id="harvest_type" class="form-input">
                        <option value="">All Types</option>
                        <option value="annual" <?php echo e(request('harvest_type') == 'annual' ? 'selected' : ''); ?>>Annual Harvest</option>
                        <option value="first_fruit" <?php echo e(request('harvest_type') == 'first_fruit' ? 'selected' : ''); ?>>First Fruit</option>
                        <option value="special" <?php echo e(request('harvest_type') == 'special' ? 'selected' : ''); ?>>Special Harvest</option>
                        <option value="thanksgiving" <?php echo e(request('harvest_type') == 'thanksgiving' ? 'selected' : ''); ?>>Thanksgiving</option>
                    </select>
                </div>

                <div>
                    <label for="year" class="form-label">Year</label>
                    <select name="year" id="year" class="form-input">
                        <?php $__currentLoopData = range(date('Y'), date('Y')-5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year); ?>" <?php echo e(request('year', date('Y')) == $year ? 'selected' : ''); ?>>
                                <?php echo e($year); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-input">
                        <option value="">All Status</option>
                        <option value="upcoming" <?php echo e(request('status') == 'upcoming' ? 'selected' : ''); ?>>Upcoming</option>
                        <option value="ongoing" <?php echo e(request('status') == 'ongoing' ? 'selected' : ''); ?>>Ongoing</option>
                        <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="btn btn-secondary w-full">Apply Filters</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Harvest Events Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Target</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Collected</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $harvests ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $harvest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($harvest->name); ?></div>
                                <div class="text-sm text-gray-500"><?php echo e($harvest->theme); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?php echo e($harvest->date->format('M d, Y')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <?php echo e(ucfirst($harvest->type)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="color: var(--color-burgundy)">
                                $<?php echo e(number_format($harvest->target_amount, 2)); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium" style="color: var(--color-teal)">
                                    $<?php echo e(number_format($harvest->collected_amount, 2)); ?>

                                </div>
                                <div class="text-xs text-gray-500">
                                    <?php echo e(number_format(($harvest->collected_amount / $harvest->target_amount) * 100, 1)); ?>% of target
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php echo e($harvest->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($harvest->status === 'ongoing' ? 'bg-yellow-100 text-yellow-800' : 
                                        'bg-gray-100 text-gray-800')); ?>">
                                    <?php echo e(ucfirst($harvest->status)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('finance.harvest.show', $harvest)); ?>" class="text-indigo-600 hover:text-indigo-900">
                                        View
                                    </a>
                                    <?php if($harvest->status !== 'completed'): ?>
                                        <a href="<?php echo e(route('finance.harvest.edit', $harvest)); ?>" class="text-yellow-600 hover:text-yellow-900">
                                            Edit
                                        </a>
                                        <form action="<?php echo e(route('finance.harvest.delete', $harvest)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-900" 
                                                    onclick="return confirm('Are you sure you want to delete this harvest event?')">
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
                                No harvest events found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if(isset($harvests) && $harvests->hasPages()): ?>
            <div class="mt-4">
                <?php echo e($harvests->links()); ?>

            </div>
        <?php endif; ?>
    </div>

    <!-- Upcoming Events Preview -->
    <?php if(isset($upcoming_harvests) && count($upcoming_harvests) > 0): ?>
        <div class="mt-6">
            <h3 class="text-lg font-medium mb-4">Upcoming Harvest Events</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php $__currentLoopData = $upcoming_harvests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $upcoming): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium"><?php echo e($upcoming->name); ?></h4>
                                <p class="text-sm text-gray-500"><?php echo e($upcoming->date->format('M d, Y')); ?></p>
                            </div>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                <?php echo e(ucfirst($upcoming->type)); ?>

                            </span>
                        </div>
                        <div class="mt-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Target:</span>
                                <span class="font-medium">$<?php echo e(number_format($upcoming->target_amount, 2)); ?></span>
                            </div>
                            <div class="flex justify-between text-sm mt-1">
                                <span class="text-gray-500">Pledges:</span>
                                <span class="font-medium">$<?php echo e(number_format($upcoming->pledged_amount, 2)); ?></span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full" style="width: <?php echo e(($upcoming->pledged_amount / $upcoming->target_amount) * 100); ?>%; background-color: var(--color-teal)"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                <?php echo e(number_format(($upcoming->pledged_amount / $upcoming->target_amount) * 100, 1)); ?>% pledged
                            </p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\finance\harvest\index.blade.php ENDPATH**/ ?>