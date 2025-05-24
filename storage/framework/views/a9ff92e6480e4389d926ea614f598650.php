

<?php $__env->startSection('content'); ?>
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Service Check-in</h2>
                <p class="mt-1 text-sm text-gray-600"><?php echo e($service->name); ?> - <?php echo e($service->service_date->format('F d, Y h:i A')); ?></p>
            </div>
            <a href="<?php echo e(route('services.show', $service)); ?>" class="text-blue-600 hover:text-blue-900">
                <i class="fas fa-arrow-left mr-1"></i> Back to Service
            </a>
        </div>

        <!-- Check-in Form -->
        <form action="<?php echo e(route('services.process-check-in', $service)); ?>" method="POST" class="max-w-4xl">
            <?php echo csrf_field(); ?>

            <!-- Member Selection -->
            <div class="mb-6">
                <label for="member_id" class="block text-sm font-medium text-gray-700 mb-1">Member <span class="text-red-600">*</span></label>
                <select name="member_id" id="member_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select a Member</option>
                    <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!in_array($member->id, $alreadyCheckedIn)): ?>
                            <option value="<?php echo e($member->id); ?>">
                                <?php echo e($member->last_name); ?>, <?php echo e($member->first_name); ?>

                            </option>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['member_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Check-in Method -->
            <div class="mb-6">
                <label for="check_in_method" class="block text-sm font-medium text-gray-700 mb-1">Check-in Method <span class="text-red-600">*</span></label>
                <select name="check_in_method" id="check_in_method" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="manual">Manual Entry</option>
                    <option value="qr_code">QR Code</option>
                    <option value="card_scan">Card Scan</option>
                    <option value="face_recognition">Face Recognition</option>
                </select>
                <?php $__errorArgs = ['check_in_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Any additional notes..."></textarea>
                <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-check-circle mr-2"></i>
                    Check In Member
                </button>
            </div>
        </form>

        <!-- Already Checked In Members -->
        <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Checked In Members</h3>
            <?php if(count($alreadyCheckedIn) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in Time</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $service->attendees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <?php if($attendee->profile_photo): ?>
                                                <img class="h-8 w-8 rounded-full mr-2" src="<?php echo e(asset('storage/' . $attendee->profile_photo)); ?>" alt="">
                                            <?php endif; ?>
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo e($attendee->full_name); ?>

                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo e(Carbon\Carbon::parse($attendee->pivot->check_in_time)->format('h:i A')); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo e($attendee->pivot->check_in_method); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <?php if(!$attendee->pivot->check_out_time): ?>
                                            <form action="<?php echo e(route('services.process-check-out', ['service' => $service, 'member' => $attendee])); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="text-blue-600 hover:text-blue-900">
                                                    Check Out
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-gray-500">Checked Out</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">No members checked in yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\services\check-in.blade.php ENDPATH**/ ?>