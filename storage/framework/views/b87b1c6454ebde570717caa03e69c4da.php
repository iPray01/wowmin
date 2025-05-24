<?php $__env->startSection('title', $family->name ?? 'Family Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="<?php echo e(route('families.index')); ?>" class="btn btn-secondary">
            ← Back to Families
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="md:col-span-2 space-y-6">
            <!-- Family Overview -->
            <div class="card">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold"><?php echo e($family->name); ?></h2>
                        <p class="text-sm" style="color: var(--color-burgundy)">Created <?php echo e($family->created_at->format('F d, Y')); ?></p>
                    </div>
                    <a href="<?php echo e(route('families.edit', $family)); ?>" class="btn btn-secondary">Edit Family</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium" style="color: var(--color-teal)">Primary Contact</h3>
                        <div class="mt-2">
                            <p class="text-sm">
                                <?php if($family->primary_contact_phone): ?>
                                    <span class="font-medium">Phone:</span> <?php echo e($family->primary_contact_phone); ?><br>
                                <?php endif; ?>
                                <?php if($family->primary_contact_email): ?>
                                    <span class="font-medium">Email:</span> <?php echo e($family->primary_contact_email); ?>

                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium" style="color: var(--color-teal)">Family Address</h3>
                        <p class="mt-2 text-sm">
                            <?php echo e($family->address); ?><br>
                            <?php echo e($family->city); ?>, <?php echo e($family->state); ?> <?php echo e($family->zip); ?>

                        </p>
                    </div>
                </div>
            </div>

            <!-- Family Tree -->
            <div class="card">
                <h3 class="text-lg font-medium mb-6">Family Tree</h3>
                <div class="space-y-6">
                    <!-- Head of Family -->
                    <div>
                        <h4 class="text-sm font-medium mb-3" style="color: var(--color-teal)">Head of Family</h4>
                        <?php if($headOfFamily = $family->members->firstWhere('relationship', 'head')): ?>
                            <div class="flex items-center space-x-3">
                                <?php if($headOfFamily->photo): ?>
                                    <img class="h-12 w-12 rounded-full object-cover" src="<?php echo e(asset('storage/' . $headOfFamily->photo)); ?>" alt="<?php echo e($headOfFamily->name); ?>">
                                <?php else: ?>
                                    <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500 text-sm"><?php echo e(substr($headOfFamily->name, 0, 2)); ?></span>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <a href="<?php echo e(route('members.show', $headOfFamily)); ?>" class="text-sm font-medium hover:underline"><?php echo e($headOfFamily->name); ?></a>
                                    <p class="text-xs text-gray-500">Head of Family</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Spouse -->
                    <div>
                        <h4 class="text-sm font-medium mb-3" style="color: var(--color-teal)">Spouse</h4>
                        <?php if($spouse = $family->members->firstWhere('relationship', 'spouse')): ?>
                            <div class="flex items-center space-x-3">
                                <?php if($spouse->photo): ?>
                                    <img class="h-12 w-12 rounded-full object-cover" src="<?php echo e(asset('storage/' . $spouse->photo)); ?>" alt="<?php echo e($spouse->name); ?>">
                                <?php else: ?>
                                    <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500 text-sm"><?php echo e(substr($spouse->name, 0, 2)); ?></span>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <a href="<?php echo e(route('members.show', $spouse)); ?>" class="text-sm font-medium hover:underline"><?php echo e($spouse->name); ?></a>
                                    <p class="text-xs text-gray-500">Spouse</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Children -->
                    <div>
                        <h4 class="text-sm font-medium mb-3" style="color: var(--color-teal)">Children</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <?php $__empty_1 = true; $__currentLoopData = $family->members->where('relationship', 'child'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex items-center space-x-3">
                                    <?php if($child->photo): ?>
                                        <img class="h-10 w-10 rounded-full object-cover" src="<?php echo e(asset('storage/' . $child->photo)); ?>" alt="<?php echo e($child->name); ?>">
                                    <?php else: ?>
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-500 text-xs"><?php echo e(substr($child->name, 0, 2)); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <a href="<?php echo e(route('members.show', $child)); ?>" class="text-sm font-medium hover:underline"><?php echo e($child->name); ?></a>
                                        <p class="text-xs text-gray-500">Child</p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-sm text-gray-500">No children registered</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Other Members -->
                    <?php if($otherMembers = $family->members->whereNotIn('relationship', ['head', 'spouse', 'child'])): ?>
                        <div>
                            <h4 class="text-sm font-medium mb-3" style="color: var(--color-teal)">Other Family Members</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <?php $__currentLoopData = $otherMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center space-x-3">
                                        <?php if($member->photo): ?>
                                            <img class="h-10 w-10 rounded-full object-cover" src="<?php echo e(asset('storage/' . $member->photo)); ?>" alt="<?php echo e($member->name); ?>">
                                        <?php else: ?>
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-500 text-xs"><?php echo e(substr($member->name, 0, 2)); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <a href="<?php echo e(route('members.show', $member)); ?>" class="text-sm font-medium hover:underline"><?php echo e($member->name); ?></a>
                                            <p class="text-xs text-gray-500"><?php echo e(ucfirst($member->relationship)); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="card">
                <h3 class="text-lg font-medium mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="<?php echo e(route('members.create', ['family_id' => $family->id])); ?>" class="btn btn-secondary block text-center">Add Family Member</a>
                    <a href="<?php echo e(route('messages.create', ['family_id' => $family->id])); ?>" class="btn btn-secondary block text-center">Send Family Message</a>
                    <a href="<?php echo e(route('attendance.create', ['family_id' => $family->id])); ?>" class="btn btn-secondary block text-center">Record Family Attendance</a>
                </div>
            </div>

            <!-- Communication Preferences -->
            <div class="card">
                <h3 class="text-lg font-medium mb-4">Communication Preferences</h3>
                <div class="space-y-2">
                    <?php $__currentLoopData = $family->preferences ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $preference): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full" style="background-color: var(--color-teal)"></span>
                            <span class="ml-2 text-sm"><?php echo e(ucfirst($preference)); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <h3 class="text-lg font-medium mb-4">Recent Activity</h3>
                <div class="space-y-4">
                    <?php $__empty_1 = true; $__currentLoopData = $family->activities ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full" style="background-color: var(--color-burgundy)"></div>
                            <div>
                                <p class="text-sm"><?php echo e($activity->description); ?></p>
                                <p class="text-xs" style="color: var(--color-teal)"><?php echo e($activity->created_at->diffForHumans()); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-sm text-gray-500">No recent activity</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Notes -->
            <div class="card">
                <h3 class="text-lg font-medium mb-4">Family Notes</h3>
                <?php if($family->notes): ?>
                    <p class="text-sm"><?php echo e($family->notes); ?></p>
                <?php else: ?>
                    <p class="text-sm text-gray-500">No notes available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\families\show.blade.php ENDPATH**/ ?>