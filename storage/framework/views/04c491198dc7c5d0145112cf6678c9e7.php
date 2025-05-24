

<?php $__env->startSection('title', 'Expense Statistics'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Expense Statistics</h1>
        <div class="space-x-2">
            <a href="<?php echo e(route('finance.expenses.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                Back to Expenses
            </a>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="<?php echo e(route('finance.expenses.statistics')); ?>" method="GET" class="flex items-end space-x-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" id="start_date" 
                       value="<?php echo e($startDate); ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="end_date" id="end_date" 
                       value="<?php echo e($endDate); ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">
                Apply Filter
            </button>
        </form>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-sm font-medium text-gray-500">Total Expenses</h3>
            <p class="mt-2 text-3xl font-bold text-gray-900">GH₵<?php echo e(number_format($totalExpenses, 2)); ?></p>
        </div>
    </div>

    <!-- Expenses by Category -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Expenses by Category</h2>
            <div class="space-y-4">
                <?php $__currentLoopData = $expensesByCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-600"><?php echo e($category->name); ?></span>
                            <span class="text-sm font-medium text-gray-900">GH₵<?php echo e(number_format($category->total_amount, 2)); ?></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: <?php echo e(($category->total_amount / $totalExpenses) * 100); ?>%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1"><?php echo e($category->count); ?> transactions</p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Expenses by Department -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Expenses by Department</h2>
            <div class="space-y-4">
                <?php $__currentLoopData = $expensesByDepartment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-600"><?php echo e($department->name); ?></span>
                            <span class="text-sm font-medium text-gray-900">GH₵<?php echo e(number_format($department->total_amount, 2)); ?></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-teal-600 h-2 rounded-full" style="width: <?php echo e(($department->total_amount / $totalExpenses) * 100); ?>%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1"><?php echo e($department->count); ?> transactions</p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- Expenses by Payment Method and Monthly Trend -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Expenses by Payment Method -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Expenses by Payment Method</h2>
            <div class="space-y-4">
                <?php $__currentLoopData = $expensesByMethod; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-600"><?php echo e(str_replace('_', ' ', ucfirst($method->payment_method))); ?></span>
                            <span class="text-sm font-medium text-gray-900">GH₵<?php echo e(number_format($method->total_amount, 2)); ?></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: <?php echo e(($method->total_amount / $totalExpenses) * 100); ?>%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1"><?php echo e($method->count); ?> transactions</p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Monthly Trend -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Monthly Trend</h2>
            <div class="space-y-4">
                <?php $__currentLoopData = $expensesByMonth; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-600"><?php echo e(Carbon\Carbon::createFromDate($month->year, $month->month, 1)->format('F Y')); ?></span>
                            <span class="text-sm font-medium text-gray-900">GH₵<?php echo e(number_format($month->total_amount, 2)); ?></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: <?php echo e(($month->total_amount / $totalExpenses) * 100); ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- Top Vendors -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Top Vendors</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Number of Transactions</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Average per Transaction</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $topVendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($vendor->vendor); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">GH₵<?php echo e(number_format($vendor->total_amount, 2)); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($vendor->count); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">GH₵<?php echo e(number_format($vendor->total_amount / $vendor->count, 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\finance\expenses\statistics.blade.php ENDPATH**/ ?>