

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Expenses</h1>
        <a href="<?php echo e(route('finance.expenses.create')); ?>" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
            Add New Expense
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="<?php echo e(route('finance.expenses.index')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Category Filter -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category_id" id="category_id" class="form-select w-full rounded-md border-gray-300">
                    <option value="">All Categories</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>" <?php echo e(request('category_id') == $category->id ? 'selected' : ''); ?>>
                            <?php echo e($category->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Department Filter -->
            <div>
                <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                <select name="department_id" id="department_id" class="form-select w-full rounded-md border-gray-300">
                    <option value="">All Departments</option>
                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($department->id); ?>" <?php echo e(request('department_id') == $department->id ? 'selected' : ''); ?>>
                            <?php echo e($department->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Payment Method Filter -->
            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                <select name="payment_method" id="payment_method" class="form-select w-full rounded-md border-gray-300">
                    <option value="">All Methods</option>
                    <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(request('payment_method') == $key ? 'selected' : ''); ?>>
                            <?php echo e($value); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Date Range Filter -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="<?php echo e(request('start_date')); ?>"
                    class="form-input w-full rounded-md border-gray-300">
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date" id="end_date" value="<?php echo e(request('end_date')); ?>"
                    class="form-input w-full rounded-md border-gray-300">
            </div>

            <!-- Amount Range Filter -->
            <div>
                <label for="min_amount" class="block text-sm font-medium text-gray-700 mb-1">Min Amount</label>
                <input type="number" name="min_amount" id="min_amount" value="<?php echo e(request('min_amount')); ?>"
                    class="form-input w-full rounded-md border-gray-300" step="0.01">
            </div>

            <div>
                <label for="max_amount" class="block text-sm font-medium text-gray-700 mb-1">Max Amount</label>
                <input type="number" name="max_amount" id="max_amount" value="<?php echo e(request('max_amount')); ?>"
                    class="form-input w-full rounded-md border-gray-300" step="0.01">
            </div>

            <!-- Search -->
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>"
                    placeholder="Search by description, vendor, or reference number"
                    class="form-input w-full rounded-md border-gray-300">
            </div>

            <div class="md:col-span-2 flex items-end justify-end gap-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                    Apply Filters
                </button>
                <a href="<?php echo e(route('finance.expenses.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Expenses Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo e($expense->expense_date->format('M d, Y')); ?>

                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="font-medium"><?php echo e($expense->description); ?></div>
                            <?php if($expense->vendor): ?>
                                <div class="text-gray-500 text-xs"><?php echo e($expense->vendor); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo e($expense->category->name); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo e($expense->department->name); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            $<?php echo e(number_format($expense->amount, 2)); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                <?php echo e($expense->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                <?php echo e($expense->status === 'approved' ? 'bg-green-100 text-green-800' : ''); ?>

                                <?php echo e($expense->status === 'rejected' ? 'bg-red-100 text-red-800' : ''); ?>">
                                <?php echo e(ucfirst($expense->status)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex space-x-2">
                                <a href="<?php echo e(route('finance.expenses.show', $expense)); ?>"
                                    class="text-blue-600 hover:text-blue-900">View</a>
                                <a href="<?php echo e(route('finance.expenses.edit', $expense)); ?>"
                                    class="text-green-600 hover:text-green-900">Edit</a>
                                <?php if($expense->receipt_path): ?>
                                    <a href="<?php echo e(route('finance.expenses.download-receipt', $expense)); ?>"
                                        class="text-purple-600 hover:text-purple-900">Receipt</a>
                                <?php endif; ?>
                                <form action="<?php echo e(route('finance.expenses.destroy', $expense)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Are you sure you want to delete this expense?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            No expenses found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        <?php echo e($expenses->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\finance\expenses\index.blade.php ENDPATH**/ ?>