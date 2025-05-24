

<?php $__env->startSection('title', 'Budget Report'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Budget Report</h1>
        <div class="space-x-2">
            <a href="<?php echo e(route('finance.expenses.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                Back to Expenses
            </a>
        </div>
    </div>

    <!-- Report Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="<?php echo e(route('finance.expenses.budget-report')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Report Type -->
            <div>
                <label for="report_type" class="block text-sm font-medium text-gray-700">Report Type</label>
                <select name="report_type" id="report_type" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="monthly" <?php echo e($reportType == 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                    <option value="quarterly" <?php echo e($reportType == 'quarterly' ? 'selected' : ''); ?>>Quarterly</option>
                    <option value="yearly" <?php echo e($reportType == 'yearly' ? 'selected' : ''); ?>>Yearly</option>
                </select>
            </div>

            <!-- Start Date -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" id="start_date" 
                       value="<?php echo e($startDate); ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- End Date -->
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="end_date" id="end_date" 
                       value="<?php echo e($endDate); ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Submit Button -->
            <div class="flex items-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">
                    Generate Report
                </button>
            </div>
        </form>
    </div>

    <!-- Report Data -->
    <div class="space-y-6">
        <?php $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4"><?php echo e($period['month']); ?></h2>

                <!-- Total Spending Overview -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-600">Total Spending</span>
                        <span class="text-lg font-semibold text-gray-900">GH₵<?php echo e(number_format($period['total_spent'], 2)); ?></span>
                    </div>
                </div>

                <!-- Department Expenses -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Department Expenses</h3>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-600"><?php echo e($department->name); ?></span>
                                    <span class="text-sm font-medium text-gray-900">
                                        GH₵<?php echo e(number_format($period['department_expenses'][$department->name] ?? 0, 2)); ?>

                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-teal-600 h-2 rounded-full" 
                                         style="width: <?php echo e($period['total_spent'] > 0 ? (($period['department_expenses'][$department->name] ?? 0) / $period['total_spent']) * 100 : 0); ?>%">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Category Expenses -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Category Expenses</h3>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-600"><?php echo e($category->name); ?></span>
                                    <span class="text-sm font-medium text-gray-900">
                                        GH₵<?php echo e(number_format($period['category_expenses'][$category->name] ?? 0, 2)); ?>

                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-indigo-600 h-2 rounded-full" 
                                         style="width: <?php echo e($period['total_spent'] > 0 ? (($period['category_expenses'][$category->name] ?? 0) / $period['total_spent']) * 100 : 0); ?>%">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Export Options -->
    <div class="mt-6 flex justify-end space-x-4">
        <a href="<?php echo e(route('finance.expenses.budget-report', array_merge(request()->query(), ['export' => 'pdf']))); ?>" 
           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
            <i class="fas fa-file-pdf mr-2"></i>
            Export as PDF
        </a>
        <a href="<?php echo e(route('finance.expenses.budget-report', array_merge(request()->query(), ['export' => 'excel']))); ?>" 
           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
            <i class="fas fa-file-excel mr-2"></i>
            Export as Excel
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\finance\expenses\budget-report.blade.php ENDPATH**/ ?>