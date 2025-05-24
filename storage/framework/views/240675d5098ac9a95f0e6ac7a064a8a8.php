<?php $__env->startSection('title', 'Member Directory'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Member Directory</h1>
        <a href="<?php echo e(route('members.create')); ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">
            Add New Member
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="<?php echo e(route('members.index')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" 
                       name="search" 
                       id="search" 
                       placeholder="Search by name, email, or phone"
                       value="<?php echo e(request('search')); ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Membership Status -->
            <div>
                <label for="membership_status" class="block text-sm font-medium text-gray-700">Membership Status</label>
                <select name="membership_status" 
                        id="membership_status" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="active" <?php echo e(request('membership_status') == 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="inactive" <?php echo e(request('membership_status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                    <option value="pending" <?php echo e(request('membership_status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                </select>
            </div>

            <!-- Marital Status -->
            <div>
                <label for="marital_status" class="block text-sm font-medium text-gray-700">Marital Status</label>
                <select name="marital_status" 
                        id="marital_status" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All</option>
                    <option value="single" <?php echo e(request('marital_status') == 'single' ? 'selected' : ''); ?>>Single</option>
                    <option value="married" <?php echo e(request('marital_status') == 'married' ? 'selected' : ''); ?>>Married</option>
                    <option value="widowed" <?php echo e(request('marital_status') == 'widowed' ? 'selected' : ''); ?>>Widowed</option>
                    <option value="divorced" <?php echo e(request('marital_status') == 'divorced' ? 'selected' : ''); ?>>Divorced</option>
                </select>
            </div>

            <div class="md:col-span-3 flex justify-end space-x-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">
                    Apply Filters
                </button>
                <a href="<?php echo e(route('members.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Members Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Photo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact Information
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Membership Details
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($member->profile_photo): ?>
                                    <img src="<?php echo e(Storage::url($member->profile_photo)); ?>" 
                                         alt="<?php echo e($member->full_name); ?>"
                                         class="h-10 w-10 rounded-full object-cover">
                                <?php else: ?>
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500 text-sm">
                                            <?php echo e(strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1))); ?>

                                        </span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($member->full_name); ?></div>
                                <div class="text-sm text-gray-500"><?php echo e($member->gender); ?></div>
                                <?php if($member->date_of_birth): ?>
                                    <div class="text-sm text-gray-500"><?php echo e($member->date_of_birth->format('M d, Y')); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900"><?php echo e($member->email); ?></div>
                                <div class="text-sm text-gray-500"><?php echo e($member->phone); ?></div>
                                <div class="text-sm text-gray-500"><?php echo e($member->address); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    Joined: <?php echo e($member->membership_date ? $member->membership_date->format('M d, Y') : 'N/A'); ?>

                                </div>
                                <?php if($member->baptism_date): ?>
                                    <div class="text-sm text-gray-500">
                                        Baptized: <?php echo e($member->baptism_date->format('M d, Y')); ?>

                                    </div>
                                <?php endif; ?>
                                <div class="text-sm text-gray-500"><?php echo e(ucfirst($member->marital_status)); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php echo e($member->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                    <?php echo e($member->is_active ? 'Active' : 'Inactive'); ?>

                                </span>
                                <div class="text-xs text-gray-500 mt-1">
                                    <?php echo e(ucfirst($member->membership_status)); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-3">
                                    <a href="<?php echo e(route('members.show', $member)); ?>" 
                                       class="text-indigo-600 hover:text-indigo-900">View</a>
                                    <a href="<?php echo e(route('members.edit', $member)); ?>" 
                                       class="text-blue-600 hover:text-blue-900">Edit</a>
                                    <form action="<?php echo e(route('members.destroy', $member)); ?>" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this member?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No members found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        <?php echo e($members->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\members\index.blade.php ENDPATH**/ ?>