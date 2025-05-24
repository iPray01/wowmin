

<?php $__env->startSection('title', $campaign->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800"><?php echo e($campaign->name); ?></h1>
        <div class="space-x-2">
            <a href="<?php echo e(route('finance.campaigns.edit', $campaign)); ?>" class="bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-4 rounded">
                Edit Campaign
            </a>
            <a href="<?php echo e(route('finance.campaigns.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Campaign Info -->
        <div class="md:col-span-2 space-y-6">
            <!-- Campaign Details -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Campaign Details</h2>
                
                <?php if($campaign->description): ?>
                    <div class="prose max-w-none mb-4">
                        <?php echo e($campaign->description); ?>

                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Start Date</p>
                        <p class="font-medium"><?php echo e($campaign->start_date->format('M d, Y')); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">End Date</p>
                        <p class="font-medium">
                            <?php echo e($campaign->end_date ? $campaign->end_date->format('M d, Y') : 'Ongoing'); ?>

                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <p class="font-medium">
                            <?php if($campaign->end_date && $campaign->end_date < now()): ?>
                                <span class="text-gray-600">Completed</span>
                            <?php elseif($campaign->is_active): ?>
                                <span class="text-green-600">Active</span>
                            <?php else: ?>
                                <span class="text-red-600">Inactive</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Featured</p>
                        <p class="font-medium">
                            <?php echo e($campaign->is_featured ? 'Yes' : 'No'); ?>

                        </p>
                    </div>
                </div>

                <?php if($campaign->additional_info): ?>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800 mb-2">Additional Information</h3>
                        <div class="prose max-w-none">
                            <?php echo e($campaign->additional_info); ?>

                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Progress -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Campaign Progress</h2>
                
                <div class="mb-4">
                    <div class="flex justify-between mb-1">
                        <span class="text-base font-medium text-gray-700">
                            ₵<?php echo e(number_format($campaign->total_donations, 2)); ?> raised of ₵<?php echo e(number_format($campaign->goal_amount, 2)); ?>

                        </span>
                        <span class="text-sm font-medium text-gray-500"><?php echo e($progressPercentage); ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?php echo e($progressPercentage); ?>%"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-800">Donations</h3>
                        <dl class="mt-2 space-y-1">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Total Amount</dt>
                                <dd class="text-sm font-medium">₵<?php echo e(number_format($donationStats['total_amount'], 2)); ?></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Number of Donations</dt>
                                <dd class="text-sm font-medium"><?php echo e($donationStats['count']); ?></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Average Donation</dt>
                                <dd class="text-sm font-medium">₵<?php echo e(number_format($donationStats['average'], 2)); ?></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Largest Donation</dt>
                                <dd class="text-sm font-medium">₵<?php echo e(number_format($donationStats['largest'], 2)); ?></dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-800">Pledges</h3>
                        <dl class="mt-2 space-y-1">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Total Pledged</dt>
                                <dd class="text-sm font-medium">₵<?php echo e(number_format($pledgeStats['total_amount'], 2)); ?></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Number of Pledges</dt>
                                <dd class="text-sm font-medium"><?php echo e($pledgeStats['count']); ?></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Fulfilled Amount</dt>
                                <dd class="text-sm font-medium">₵<?php echo e(number_format($pledgeStats['fulfilled_amount'], 2)); ?></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Unfulfilled Amount</dt>
                                <dd class="text-sm font-medium">₵<?php echo e(number_format($pledgeStats['unfulfilled_amount'], 2)); ?></dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Recent Activity</h2>
                    <div class="space-x-2">
                        <button type="button" onclick="switchTab('donations')"
                            class="px-3 py-1.5 text-sm font-medium rounded-md"
                            id="donations-tab">
                            Donations
                        </button>
                        <button type="button" onclick="switchTab('pledges')"
                            class="px-3 py-1.5 text-sm font-medium rounded-md"
                            id="pledges-tab">
                            Pledges
                        </button>
                    </div>
                </div>

                <!-- Recent Donations -->
                <div id="donations-content" class="space-y-4">
                    <?php $__empty_1 = true; $__currentLoopData = $recentDonations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-center justify-between py-3 border-b border-gray-200 last:border-0">
                            <div>
                                <p class="font-medium text-gray-800">
                                    <?php echo e($donation->member->full_name); ?>

                                </p>
                                <p class="text-sm text-gray-500">
                                    <?php echo e($donation->donation_date->format('M d, Y')); ?>

                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-800">
                                    ₵<?php echo e(number_format($donation->amount, 2)); ?>

                                </p>
                                <p class="text-sm text-gray-500">
                                    <?php echo e(ucfirst($donation->payment_method)); ?>

                                </p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-gray-500 text-center py-4">No donations yet.</p>
                    <?php endif; ?>
                </div>

                <!-- Recent Pledges -->
                <div id="pledges-content" class="space-y-4 hidden">
                    <?php $__empty_1 = true; $__currentLoopData = $recentPledges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pledge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-center justify-between py-3 border-b border-gray-200 last:border-0">
                            <div>
                                <p class="font-medium text-gray-800">
                                    <?php echo e($pledge->member->full_name); ?>

                                </p>
                                <p class="text-sm text-gray-500">
                                    <?php echo e($pledge->pledge_date->format('M d, Y')); ?>

                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-800">
                                    ₵<?php echo e(number_format($pledge->amount, 2)); ?>

                                </p>
                                <p class="text-sm text-gray-500">
                                    Fulfilled: ₵<?php echo e(number_format($pledge->amount_fulfilled, 2)); ?>

                                </p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-gray-500 text-center py-4">No pledges yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="<?php echo e(route('finance.donations.create', ['campaign_id' => $campaign->id])); ?>"
                        class="block w-full bg-green-500 hover:bg-green-600 text-white text-center font-semibold py-2 px-4 rounded">
                        Record Donation
                    </a>
                    <a href="<?php echo e(route('finance.pledges.create', ['campaign_id' => $campaign->id])); ?>"
                        class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center font-semibold py-2 px-4 rounded">
                        Record Pledge
                    </a>
                    <a href="<?php echo e(route('finance.campaigns.progress', $campaign)); ?>"
                        class="block w-full bg-indigo-500 hover:bg-indigo-600 text-white text-center font-semibold py-2 px-4 rounded">
                        View Detailed Progress
                    </a>
                </div>
            </div>

            <!-- Campaign Image -->
            <?php if($campaign->image_url): ?>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Campaign Image</h2>
                    <img src="<?php echo e($campaign->image_url); ?>" alt="<?php echo e($campaign->name); ?>"
                        class="w-full h-auto rounded-lg">
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function switchTab(tab) {
    // Update tab buttons
    document.getElementById('donations-tab').classList.remove('bg-gray-100', 'text-gray-900');
    document.getElementById('pledges-tab').classList.remove('bg-gray-100', 'text-gray-900');
    document.getElementById(`${tab}-tab`).classList.add('bg-gray-100', 'text-gray-900');

    // Update content visibility
    document.getElementById('donations-content').classList.add('hidden');
    document.getElementById('pledges-content').classList.add('hidden');
    document.getElementById(`${tab}-content`).classList.remove('hidden');
}

// Initialize with donations tab active
document.addEventListener('DOMContentLoaded', function() {
    switchTab('donations');
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\finance\campaigns\show.blade.php ENDPATH**/ ?>