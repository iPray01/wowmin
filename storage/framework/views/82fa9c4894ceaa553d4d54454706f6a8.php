<?php $__env->startSection('title', $member->full_name); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="<?php echo e(route('members.index')); ?>" class="text-indigo-600 hover:text-indigo-900">
            ← Back to Members
        </a>
    </div>

    <!-- Member Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex items-center space-x-4">
                <?php if($member->profile_photo): ?>
                    <img src="<?php echo e(Storage::url($member->profile_photo)); ?>" 
                         alt="<?php echo e($member->full_name); ?>"
                         class="h-16 w-16 rounded-full object-cover">
                <?php else: ?>
                    <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500 text-xl">
                            <?php echo e(strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1))); ?>

                        </span>
                    </div>
                <?php endif; ?>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900"><?php echo e($member->full_name); ?></h1>
                    <p class="text-gray-500">Member since <?php echo e($member->membership_date ? $member->membership_date->format('F Y') : 'N/A'); ?></p>
                    <div class="mt-2">
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full <?php echo e($member->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                            <?php echo e($member->is_active ? 'Active' : 'Inactive'); ?>

                        </span>
                        <span class="ml-2 px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            <?php echo e(ucfirst($member->membership_status)); ?>

                        </span>
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="<?php echo e(route('members.edit', $member)); ?>" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">
                    Edit Member
                </a>
                <form action="<?php echo e(route('members.destroy', $member)); ?>" 
                      method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this member?');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded">
                        Delete Member
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Personal Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Personal Information</h2>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="text-sm text-gray-900"><?php echo e($member->email ?? 'Not provided'); ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                    <dd class="text-sm text-gray-900"><?php echo e($member->phone ?? 'Not provided'); ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                    <dd class="text-sm text-gray-900"><?php echo e($member->address ?? 'Not provided'); ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                    <dd class="text-sm text-gray-900"><?php echo e($member->date_of_birth ? $member->date_of_birth->format('F j, Y') : 'Not provided'); ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Gender</dt>
                    <dd class="text-sm text-gray-900"><?php echo e(ucfirst($member->gender ?? 'Not specified')); ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Marital Status</dt>
                    <dd class="text-sm text-gray-900"><?php echo e(ucfirst($member->marital_status ?? 'Not specified')); ?></dd>
                </div>
            </dl>
        </div>

        <!-- Church Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Church Information</h2>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Membership Status</dt>
                    <dd class="text-sm text-gray-900"><?php echo e(ucfirst($member->membership_status)); ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Membership Date</dt>
                    <dd class="text-sm text-gray-900"><?php echo e($member->membership_date ? $member->membership_date->format('F j, Y') : 'Not recorded'); ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Baptism Date</dt>
                    <dd class="text-sm text-gray-900"><?php echo e($member->baptism_date ? $member->baptism_date->format('F j, Y') : 'Not recorded'); ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="text-sm text-gray-900"><?php echo e($member->is_active ? 'Active' : 'Inactive'); ?></dd>
                </div>
            </dl>
        </div>

        <!-- Emergency Contact -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Emergency Contact</h2>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                    <dd class="text-sm text-gray-900"><?php echo e($member->emergency_contact_name ?? 'Not provided'); ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                    <dd class="text-sm text-gray-900"><?php echo e($member->emergency_contact_phone ?? 'Not provided'); ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Relationship</dt>
                    <dd class="text-sm text-gray-900"><?php echo e($member->emergency_contact_relationship ?? 'Not specified'); ?></dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Family Relationships -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Family Relationships</h2>
            <a href="<?php echo e(route('families.create')); ?>" class="text-indigo-600 hover:text-indigo-900">
                + Add to Family
            </a>
        </div>
        <?php if($member->families->isNotEmpty()): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Family Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Relationship</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $member->families; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $family): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($family->family_name); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e(ucfirst($family->pivot->relationship_type)); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="<?php echo e(route('families.show', $family)); ?>" class="text-indigo-600 hover:text-indigo-900">View</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-gray-500">No family relationships recorded.</p>
        <?php endif; ?>
    </div>

    <!-- Recent Attendance -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Recent Attendance</h2>
            <a href="<?php echo e(route('attendance.index', ['member_id' => $member->id])); ?>" class="text-indigo-600 hover:text-indigo-900">
                View All
            </a>
        </div>
        <?php if($recentAttendances->isNotEmpty()): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $recentAttendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($attendance->service->service_date->format('M j, Y')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($attendance->service->service_type); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($attendance->check_in_time->format('g:i A')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($attendance->check_out_time ? $attendance->check_out_time->format('g:i A') : 'Not checked out'); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-gray-500">No attendance records found.</p>
        <?php endif; ?>
    </div>

    <!-- Recent Donations -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Recent Donations</h2>
            <a href="<?php echo e(route('finance.donations.index', ['member_id' => $member->id])); ?>" class="text-indigo-600 hover:text-indigo-900">
                View All
            </a>
        </div>
        <?php if($recentDonations->isNotEmpty()): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $recentDonations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($donation->donation_date->format('M j, Y')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e(ucfirst($donation->donation_type)); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e(number_format($donation->amount, 2)); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php echo e($donation->payment_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                        <?php echo e(ucfirst($donation->payment_status)); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-gray-500">No donation records found.</p>
        <?php endif; ?>
    </div>

    <!-- Prayer Requests -->
    <?php if($member->prayerRequests->isNotEmpty()): ?>
        <div class="mt-6 bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Prayer Requests</h2>
                <a href="<?php echo e(route('prayer-requests.index', ['member_id' => $member->id])); ?>" class="text-indigo-600 hover:text-indigo-900">
                    View All
                </a>
            </div>
            <div class="space-y-4">
                <?php $__currentLoopData = $member->prayerRequests->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-lg font-medium text-gray-900"><?php echo e($request->title); ?></h3>
                        <p class="mt-1 text-sm text-gray-600"><?php echo e(Str::limit($request->description, 150)); ?></p>
                        <div class="mt-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php echo e($request->is_answered ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'); ?>">
                                <?php echo e($request->is_answered ? 'Answered' : 'Active'); ?>

                            </span>
                            <span class="ml-2 text-sm text-gray-500">
                                Submitted <?php echo e($request->created_at->diffForHumans()); ?>

                            </span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\members\show.blade.php ENDPATH**/ ?>