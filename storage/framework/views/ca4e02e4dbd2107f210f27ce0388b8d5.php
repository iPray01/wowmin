<?php $__env->startSection('title', 'Financial Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="card bg-white">
            <h3 class="text-sm font-medium text-gray-500">Total Income (YTD)</h3>
            <p class="mt-2 text-3xl font-bold" style="color: var(--color-teal)">$<?php echo e(number_format($statistics->total_income ?? 0, 2)); ?></p>
            <p class="mt-1 text-sm text-gray-500">
                <?php if(($statistics->income_trend ?? 0) > 0): ?>
                    <span class="text-green-600">↑ <?php echo e($statistics->income_trend); ?>%</span>
                <?php else: ?>
                    <span class="text-red-600">↓ <?php echo e(abs($statistics->income_trend)); ?>%</span>
                <?php endif; ?>
                vs last year
            </p>
        </div>

        <div class="card bg-white">
            <h3 class="text-sm font-medium text-gray-500">Total Expenses (YTD)</h3>
            <p class="mt-2 text-3xl font-bold" style="color: var(--color-burgundy)">$<?php echo e(number_format($statistics->total_expenses ?? 0, 2)); ?></p>
            <p class="mt-1 text-sm text-gray-500">
                <?php if(($statistics->expense_trend ?? 0) > 0): ?>
                    <span class="text-red-600">↑ <?php echo e($statistics->expense_trend); ?>%</span>
                <?php else: ?>
                    <span class="text-green-600">↓ <?php echo e(abs($statistics->expense_trend)); ?>%</span>
                <?php endif; ?>
                vs last year
            </p>
        </div>

        <div class="card bg-white">
            <h3 class="text-sm font-medium text-gray-500">Net Balance</h3>
            <p class="mt-2 text-3xl font-bold" style="color: var(--color-crimson)">$<?php echo e(number_format(($statistics->total_income ?? 0) - ($statistics->total_expenses ?? 0), 2)); ?></p>
            <p class="mt-1 text-sm text-gray-500">Current fiscal year</p>
        </div>

        <div class="card bg-white">
            <h3 class="text-sm font-medium text-gray-500">Pending Pledges</h3>
            <p class="mt-2 text-3xl font-bold" style="color: var(--color-teal)">$<?php echo e(number_format($statistics->pending_pledges ?? 0, 2)); ?></p>
            <p class="mt-1 text-sm text-gray-500"><?php echo e($statistics->active_pledges ?? 0); ?> active pledges</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Recent Transactions -->
        <div class="md:col-span-2 space-y-6">
            <div class="card">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">Recent Transactions</h2>
                    <div class="flex space-x-2">
                        <a href="<?php echo e(route('finance.donations.create')); ?>" class="btn btn-primary">Record Donation</a>
                        <a href="<?php echo e(route('finance.expenses.create')); ?>" class="btn btn-secondary">Record Expense</a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__empty_1 = true; $__currentLoopData = $transactions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <?php echo e($transaction->date->format('M d, Y')); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo e($transaction->description); ?>

                                        </div>
                                        <?php if($transaction->member): ?>
                                            <div class="text-sm text-gray-500">
                                                <?php echo e($transaction->member->name); ?>

                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                            style="<?php echo e($transaction->type === 'income' ? 'background-color: var(--color-teal)' : 'background-color: var(--color-burgundy)'); ?>; color: white">
                                            <?php echo e(ucfirst($transaction->category)); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" 
                                        style="color: <?php echo e($transaction->type === 'income' ? 'var(--color-teal)' : 'var(--color-crimson)'); ?>">
                                        <?php echo e($transaction->type === 'income' ? '+' : '-'); ?>$<?php echo e(number_format($transaction->amount, 2)); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php echo e($transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                            <?php echo e(ucfirst($transaction->status)); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        No recent transactions
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if(isset($transactions) && $transactions->hasPages()): ?>
                    <div class="mt-4">
                        <?php echo e($transactions->links()); ?>

                    </div>
                <?php endif; ?>
            </div>

            <!-- Monthly Overview -->
            <div class="card">
                <h2 class="text-xl font-bold mb-6">Monthly Overview</h2>
                <div class="h-64">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="card">
                <h3 class="text-lg font-medium mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="<?php echo e(route('finance.pledges.create')); ?>" class="btn btn-secondary block text-center">Create Pledge</a>
                    <a href="<?php echo e(route('finance.reports.generate')); ?>" class="btn btn-secondary block text-center">Generate Report</a>
                    <a href="<?php echo e(route('finance.budgets.index')); ?>" class="btn btn-secondary block text-center">Manage Budget</a>
                </div>
            </div>

            <!-- Upcoming Pledges -->
            <div class="card">
                <h3 class="text-lg font-medium mb-4">Upcoming Pledges</h3>
                <div class="space-y-4">
                    <?php $__empty_1 = true; $__currentLoopData = $upcoming_pledges ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pledge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium"><?php echo e($pledge->member->name); ?></p>
                                <p class="text-xs text-gray-500">Due <?php echo e($pledge->due_date->format('M d, Y')); ?></p>
                            </div>
                            <span class="text-sm font-medium" style="color: var(--color-teal)">$<?php echo e(number_format($pledge->amount, 2)); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-sm text-gray-500">No upcoming pledges</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Budget Overview -->
            <div class="card">
                <h3 class="text-lg font-medium mb-4">Budget Overview</h3>
                <div class="space-y-4">
                    <?php $__currentLoopData = $budget_categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium"><?php echo e($category->name); ?></span>
                                <span class="text-sm text-gray-500">
                                    <?php echo e(number_format($category->spent / $category->allocated * 100, 0)); ?>%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full" style="width: <?php echo e(($category->spent / $category->allocated * 100)); ?>%; background-color: 
                                    <?php echo e(($category->spent / $category->allocated) > 0.9 ? 'var(--color-crimson)' : 
                                       ($category->spent / $category->allocated) > 0.7 ? 'var(--color-burgundy)' : 'var(--color-teal)'); ?>">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chart_data->labels ?? [], 15, 512) ?>,
            datasets: [
                {
                    label: 'Income',
                    data: <?php echo json_encode($chart_data->income ?? [], 15, 512) ?>,
                    borderColor: getComputedStyle(document.documentElement).getPropertyValue('--color-teal'),
                    tension: 0.4
                },
                {
                    label: 'Expenses',
                    data: <?php echo json_encode($chart_data->expenses ?? [], 15, 512) ?>,
                    borderColor: getComputedStyle(document.documentElement).getPropertyValue('--color-burgundy'),
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\finance\index.blade.php ENDPATH**/ ?>