<?php $__env->startSection('title', 'Family Units'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="card">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Family Units</h2>
            <a href="<?php echo e(route('families.create')); ?>" class="btn btn-primary">Create New Family</a>
        </div>

        <!-- Search -->
        <div class="mb-6">
            <form action="<?php echo e(route('families.index')); ?>" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <label for="search" class="form-label">Search Families</label>
                    <input type="text" name="search" id="search" class="form-input" placeholder="Search by family name" value="<?php echo e(request('search')); ?>">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn btn-secondary">Search</button>
                </div>
            </form>
        </div>

        <!-- Families Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $families ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $family): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="card hover:shadow-lg transition-shadow duration-200">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-medium"><?php echo e($family->family_name); ?></h3>
                        <div class="flex space-x-2">
                            <a href="<?php echo e(route('families.edit', $family)); ?>" class="text-sm" style="color: var(--color-burgundy)">Edit</a>
                            <form action="<?php echo e(route('families.destroy', $family)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-sm" style="color: var(--color-crimson)" onclick="return confirm('Are you sure you want to delete this family unit?')">Delete</button>
                            </form>
                        </div>
                    </div>

                    <!-- Family Members -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium" style="color: var(--color-teal)">Family Members (<?php echo e($family->members_count); ?>)</span>
                            <a href="<?php echo e(route('families.show', $family)); ?>" class="text-sm hover:underline" style="color: var(--color-burgundy)">View Details →</a>
                        </div>
                        <div class="flex -space-x-2 overflow-hidden">
                            <?php $__currentLoopData = $family->members->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($member->profile_photo): ?>
                                    <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white" src="<?php echo e(asset('storage/' . $member->profile_photo)); ?>" alt="<?php echo e($member->full_name); ?>">
                                <?php else: ?>
                                    <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500 text-xs"><?php echo e(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)); ?></span>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($family->members_count > 5): ?>
                                <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white" style="background-color: var(--color-teal); color: white">
                                    <span class="text-xs flex items-center justify-center h-full">+<?php echo e($family->members_count - 5); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="block text-gray-500">Address</span>
                            <span class="font-medium"><?php echo e($family->address ?? 'Not set'); ?></span>
                        </div>
                        <div>
                            <span class="block text-gray-500">Contact</span>
                            <span class="font-medium"><?php echo e($family->primary_contact_phone ?? 'Not set'); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full">
                    <p class="text-center text-gray-500">No family units found</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if(isset($families) && $families->hasPages()): ?>
            <div class="mt-6">
                <?php echo e($families->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\families\index.blade.php ENDPATH**/ ?>