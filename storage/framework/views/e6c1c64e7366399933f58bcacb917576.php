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
            <?php echo e(__('Profile')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                <?php echo e(__('Profile Information')); ?>

                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                <?php echo e(__("Update your account's profile information.")); ?>

                            </p>
                        </header>

                        <dl class="mt-6 space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500"><?php echo e(__('Name')); ?></dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo e($user->name); ?></dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500"><?php echo e(__('Email')); ?></dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo e($user->email); ?></dd>
                            </div>
                        </dl>

                        <div class="mt-6">
                            <a href="<?php echo e(route('profile.edit')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                <?php echo e(__('Edit Profile')); ?>

                            </a>
                        </div>
                    </section>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                <?php echo e(__('Delete Account')); ?>

                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                <?php echo e(__('Once your account is deleted, all of its resources and data will be permanently deleted.')); ?>

                            </p>
                        </header>

                        <div class="mt-6">
                            <form method="post" action="<?php echo e(route('profile.destroy')); ?>" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('delete'); ?>

                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                                    <?php echo e(__('Delete Account')); ?>

                                </button>
                            </form>
                        </div>
                    </section>
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
<?php endif; ?> <?php /**PATH C:\xampp\htdocs\wowmin\resources\views\profile\show.blade.php ENDPATH**/ ?>