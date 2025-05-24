

<?php $__env->startSection('title', 'Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Settings</h2>

                <?php if(session('status')): ?>
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('settings.update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <!-- Notification Settings -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Notification Preferences</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="notifications[email]" id="notifications_email"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           <?php echo e($settings['notifications']['email'] ? 'checked' : ''); ?>>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="notifications_email" class="font-medium text-gray-700">Email Notifications</label>
                                    <p class="text-gray-500">Receive notifications via email</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="notifications[sms]" id="notifications_sms"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           <?php echo e($settings['notifications']['sms'] ? 'checked' : ''); ?>>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="notifications_sms" class="font-medium text-gray-700">SMS Notifications</label>
                                    <p class="text-gray-500">Receive notifications via SMS</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="notifications[browser]" id="notifications_browser"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           <?php echo e($settings['notifications']['browser'] ? 'checked' : ''); ?>>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="notifications_browser" class="font-medium text-gray-700">Browser Notifications</label>
                                    <p class="text-gray-500">Receive notifications in your browser</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Display Settings -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Display Preferences</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="display_theme" class="block text-sm font-medium text-gray-700">Theme</label>
                                <select name="display[theme]" id="display_theme"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="light" <?php echo e($settings['display']['theme'] === 'light' ? 'selected' : ''); ?>>Light</option>
                                    <option value="dark" <?php echo e($settings['display']['theme'] === 'dark' ? 'selected' : ''); ?>>Dark</option>
                                </select>
                            </div>

                            <div>
                                <label for="display_language" class="block text-sm font-medium text-gray-700">Language</label>
                                <select name="display[language]" id="display_language"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="en" <?php echo e($settings['display']['language'] === 'en' ? 'selected' : ''); ?>>English</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Privacy Settings -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Privacy Settings</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="privacy_profile_visibility" class="block text-sm font-medium text-gray-700">Profile Visibility</label>
                                <select name="privacy[profile_visibility]" id="privacy_profile_visibility"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="public" <?php echo e($settings['privacy']['profile_visibility'] === 'public' ? 'selected' : ''); ?>>Public</option>
                                    <option value="members" <?php echo e($settings['privacy']['profile_visibility'] === 'members' ? 'selected' : ''); ?>>Members Only</option>
                                    <option value="private" <?php echo e($settings['privacy']['profile_visibility'] === 'private' ? 'selected' : ''); ?>>Private</option>
                                </select>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="privacy[show_online_status]" id="privacy_show_online_status"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           <?php echo e($settings['privacy']['show_online_status'] ? 'checked' : ''); ?>>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="privacy_show_online_status" class="font-medium text-gray-700">Show Online Status</label>
                                    <p class="text-gray-500">Allow others to see when you're online</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\settings\index.blade.php ENDPATH**/ ?>