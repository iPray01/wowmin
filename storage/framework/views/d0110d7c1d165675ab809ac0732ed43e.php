<?php $__env->startSection('header'); ?>
About WoW Ministry International
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white shadow-sm rounded-lg p-6">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="text-center mb-8">
            <i class="fas fa-church text-4xl text-crimson mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-900">Our Mission</h2>
            <p class="mt-4 text-gray-600">
                WoW Ministry International is dedicated to spreading the word of God and making a positive impact in our community through worship, outreach, and discipleship.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="text-center">
                <i class="fas fa-hands-helping text-3xl text-crimson mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900">Our Values</h3>
                <ul class="mt-4 text-gray-600 space-y-2">
                    <li>Faith and Devotion</li>
                    <li>Community Service</li>
                    <li>Spiritual Growth</li>
                    <li>Inclusive Fellowship</li>
                </ul>
            </div>

            <div class="text-center">
                <i class="fas fa-bullseye text-3xl text-crimson mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900">Our Vision</h3>
                <p class="mt-4 text-gray-600">
                    To be a beacon of hope and transformation, nurturing spiritual growth and fostering a community where everyone can experience God's love and purpose.
                </p>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-8 mt-8">
            <h3 class="text-xl font-semibold text-gray-900 text-center mb-6">Leadership Team</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <i class="fas fa-user-circle text-5xl text-gray-400 mb-3"></i>
                    <h4 class="font-medium text-gray-900">Pastor John Doe</h4>
                    <p class="text-sm text-gray-600">Senior Pastor</p>
                </div>
                <div class="text-center">
                    <i class="fas fa-user-circle text-5xl text-gray-400 mb-3"></i>
                    <h4 class="font-medium text-gray-900">Jane Smith</h4>
                    <p class="text-sm text-gray-600">Worship Leader</p>
                </div>
                <div class="text-center">
                    <i class="fas fa-user-circle text-5xl text-gray-400 mb-3"></i>
                    <h4 class="font-medium text-gray-900">Michael Johnson</h4>
                    <p class="text-sm text-gray-600">Youth Pastor</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\pages\about.blade.php ENDPATH**/ ?>