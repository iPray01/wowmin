<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Welcome, <?php echo e(auth()->user()->name); ?></h1>
        <p class="mt-1 text-sm text-gray-600">Here's what's happening in your church</p>
        </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Members -->
        <div class="bg-gradient-to-br from-teal/10 to-teal/20 rounded-xl shadow-lg p-6 transform transition-all duration-200 hover:scale-105 border border-teal/20">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-teal bg-opacity-20">
                    <svg class="w-8 h-8 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                <div class="ml-4">
                    <h2 class="text-sm font-semibold text-teal">Total Members</h2>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($totalMembers)); ?></p>
                    </div>
                </div>
                <div class="mt-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Active Members</span>
                    <span class="font-medium text-teal"><?php echo e(number_format($activeMembers)); ?></span>
                </div>
            </div>
                    </div>

        <!-- Attendance -->
        <div class="bg-gradient-to-br from-metal-gold/10 to-metal-gold/20 rounded-xl shadow-lg p-6 transform transition-all duration-200 hover:scale-105 border border-metal-gold/20">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-metal-gold bg-opacity-20">
                    <svg class="w-8 h-8 text-metal-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                <div class="ml-4">
                    <h2 class="text-sm font-semibold text-metal-gold">Last Service</h2>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($lastServiceAttendance)); ?></p>
                    </div>
                </div>
                <div class="mt-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Average</span>
                    <span class="font-medium text-metal-gold"><?php echo e(number_format($averageAttendance)); ?></span>
                </div>
            </div>
                    </div>

        <!-- Donations -->
        <div class="bg-gradient-to-br from-crimson/10 to-crimson/20 rounded-xl shadow-lg p-6 transform transition-all duration-200 hover:scale-105 border border-crimson/20">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-crimson bg-opacity-20">
                    <svg class="w-8 h-8 text-crimson" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                <div class="ml-4">
                    <h2 class="text-sm font-semibold text-crimson">Monthly Donations</h2>
                    <p class="text-2xl font-bold text-gray-900">GH₵<?php echo e(number_format($totalDonationsThisMonth, 2)); ?></p>
                    </div>
                </div>
                <div class="mt-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">YTD</span>
                    <span class="font-medium text-crimson">GH₵<?php echo e(number_format($totalDonationsThisYear, 2)); ?></span>
                </div>
            </div>
                    </div>

        <!-- Active Campaigns -->
        <div class="bg-gradient-to-br from-teal/10 to-teal/20 rounded-xl shadow-lg p-6 transform transition-all duration-200 hover:scale-105 border border-teal/20">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-teal bg-opacity-20">
                    <svg class="w-8 h-8 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                <div class="ml-4">
                    <h2 class="text-sm font-semibold text-teal">Active Campaigns</h2>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($activeCampaigns->count()); ?></p>
                    </div>
                </div>
                <div class="mt-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Total Progress</span>
                    <span class="font-medium text-teal"><?php echo e(number_format($campaignProgress, 1)); ?>%</span>
                </div>
                </div>
            </div>
        </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Active Campaigns -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-teal-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    <h2 class="text-lg font-semibold text-gray-900">Active Campaigns</h2>
                </div>
                <a href="<?php echo e(route('finance.campaigns.index')); ?>" class="text-sm text-teal-600 hover:text-teal-800 font-medium">View all</a>
            </div>
            <div class="space-y-6">
                <?php $__currentLoopData = $activeCampaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-200">
                        <div class="flex justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-900"><?php echo e($campaign->name); ?></span>
                            <span class="text-sm font-medium text-teal-600"><?php echo e(number_format($campaign->progress_percentage, 1)); ?>%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-gradient-to-r from-teal-500 to-emerald-500 h-2.5 rounded-full transition-all duration-500" style="width: <?php echo e($campaign->progress_percentage); ?>%"></div>
                        </div>
                        <div class="flex justify-between mt-2 text-xs text-gray-600">
                            <span>Raised: GH₵<?php echo e(number_format($campaign->total_fulfilled + ($campaign->donations->sum('amount') ?? 0), 2)); ?></span>
                            <span>Goal: GH₵<?php echo e(number_format($campaign->target_amount, 2)); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Upcoming Birthdays -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-teal-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"/>
                        </svg>
                    <h2 class="text-lg font-semibold text-gray-900">Upcoming Birthdays</h2>
                </div>
                <a href="<?php echo e(route('members.index')); ?>" class="text-sm text-teal-600 hover:text-teal-800 font-medium">View all</a>
            </div>
            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $upcomingBirthdays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center space-x-4 p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-all duration-200">
                        <div class="flex-shrink-0">
                            <?php if($member->profile_photo): ?>
                                <img class="h-12 w-12 rounded-full ring-2 ring-teal-500 ring-offset-2" src="<?php echo e(asset('storage/' . $member->profile_photo)); ?>" alt="<?php echo e($member->full_name); ?>">
                            <?php else: ?>
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center ring-2 ring-teal-500 ring-offset-2">
                                    <span class="text-white text-lg font-medium"><?php echo e(substr($member->first_name, 0, 1)); ?></span>
                                </div>
                            <?php endif; ?>
                    </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate"><?php echo e($member->full_name); ?></p>
                            <p class="text-xs text-gray-600">
                                <?php echo e($member->next_birthday->format('M d')); ?>

                                <span class="text-gray-400 mx-1">&bull;</span>
                                <span class="text-teal-600 font-medium"><?php echo e($member->days_until); ?></span> <?php echo e(Str::plural('day', $member->days_until)); ?> away
                            </p>
                    </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-gray-500 text-center py-4">No upcoming birthdays in the next 30 days</p>
                <?php endif; ?>
            </div>
        </div>

            <!-- Recent Activity -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-teal-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-900">Recent Activity</h2>
                </div>
                <a href="#" class="text-sm text-teal-600 hover:text-teal-800 font-medium">View all</a>
            </div>
                <div class="space-y-4">
                <?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-all duration-200">
                            <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-lg 
                                <?php echo e($activity->type === 'donation' ? 'bg-rose-100 text-rose-600' : 
                                   ($activity->type === 'attendance' ? 'bg-amber-100 text-amber-600' : 
                                    ($activity->type === 'pledge' ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-600'))); ?>">
                                <?php if($activity->type === 'donation'): ?>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                <?php elseif($activity->type === 'attendance'): ?>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                <?php elseif($activity->type === 'pledge'): ?>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                <?php endif; ?>
                                </span>
                            </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900"><?php echo e($activity->title); ?></p>
                            <p class="text-xs text-gray-600 mt-1">
                                <?php echo e($activity->member->full_name); ?> • 
                                <?php echo e($activity->date->diffForHumans()); ?>

                            </p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Prayer Requests -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-teal-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-900">Recent Prayer Requests</h2>
                </div>
                <a href="<?php echo e(route('prayer-requests.index')); ?>" class="text-sm text-teal-600 hover:text-teal-800 font-medium">View all</a>
            </div>
                <div class="space-y-4">
                <?php $__currentLoopData = $recentPrayerRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-all duration-200">
                            <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-lg 
                                <?php echo e($request->is_answered ? 'bg-teal-100 text-teal-600' : 'bg-amber-100 text-amber-600'); ?>">
                                <?php if($request->is_answered): ?>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                <?php endif; ?>
                            </span>
                                </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900"><?php echo e(Str::limit($request->title, 50)); ?></p>
                            <p class="text-xs text-gray-600 mt-1">
                                By <?php echo e($request->member->full_name); ?> • 
                                <?php echo e($request->created_at->diffForHumans()); ?>

                            </p>
                        </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\dashboard.blade.php ENDPATH**/ ?>