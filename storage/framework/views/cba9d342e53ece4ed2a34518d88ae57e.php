<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('View SMS Message')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Message Details</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-800 whitespace-pre-wrap"><?php echo e($message->content); ?></p>
                            <div class="mt-4 text-sm text-gray-500">
                                <p>Status: 
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php echo e($message->status === 'sent' ? 'bg-green-100 text-green-800' : 
                                           ($message->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : 
                                           ($message->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))); ?>">
                                        <?php echo e(ucfirst($message->status)); ?>

                                    </span>
                                </p>
                                <p class="mt-1">Created: <?php echo e($message->created_at->format('M d, Y H:i')); ?></p>
                                <?php if($message->scheduled_at): ?>
                                    <p class="mt-1">Scheduled for: <?php echo e($message->scheduled_at->format('M d, Y H:i')); ?></p>
                                <?php endif; ?>
                                <?php if($message->sent_at): ?>
                                    <p class="mt-1">Sent at: <?php echo e($message->sent_at->format('M d, Y H:i')); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Recipients</h3>
                        <div class="space-y-4">
                            <?php if($message->group): ?>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Group</h4>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-gray-800"><?php echo e($message->group->name); ?> (<?php echo e($message->group->members_count); ?> members)</p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($message->groups && $message->groups->count() > 0): ?>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Groups</h4>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <ul class="list-disc list-inside">
                                            <?php $__currentLoopData = $message->groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="text-gray-800"><?php echo e($group->name); ?> (<?php echo e($group->members_count); ?> members)</li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Delivery Status</h4>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-600">Total</p>
                                            <p class="text-lg font-semibold"><?php echo e($deliveryStats['total']); ?></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Delivered</p>
                                            <p class="text-lg font-semibold text-green-600"><?php echo e($deliveryStats['delivered']); ?></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Failed</p>
                                            <p class="text-lg font-semibold text-red-600"><?php echo e($deliveryStats['failed']); ?></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Pending</p>
                                            <p class="text-lg font-semibold text-yellow-600"><?php echo e($deliveryStats['pending']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if($message->recipients->count() > 0): ?>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Individual Recipients</h4>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead>
                                                    <tr>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sent At</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    <?php $__currentLoopData = $message->recipients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                <?php echo e($recipient->member ? $recipient->member->name : 'Manual Number'); ?>

                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                <?php echo e($recipient->phone_number); ?>

                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                    <?php if($recipient->status === 'delivered'): ?> bg-green-100 text-green-800
                                                                    <?php elseif($recipient->status === 'failed'): ?> bg-red-100 text-red-800
                                                                    <?php else: ?> bg-yellow-100 text-yellow-800
                                                                    <?php endif; ?>">
                                                                    <?php echo e(ucfirst($recipient->status)); ?>

                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                <?php echo e($recipient->sent_at ? $recipient->sent_at->format('M d, Y H:i:s') : '-'); ?>

                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <?php if($message->status === 'scheduled'): ?>
                            <form action="<?php echo e(route('sms.messages.cancel', $message)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                        onclick="return confirm('Are you sure you want to cancel this message?')">
                                    Cancel Message
                                </button>
                            </form>
                        <?php endif; ?>
                        <a href="<?php echo e(route('sms.messages.index')); ?>" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Back to Messages
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\wowmin\resources\views\sms\messages\show.blade.php ENDPATH**/ ?>