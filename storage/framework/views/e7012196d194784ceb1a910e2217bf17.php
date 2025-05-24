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
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('SMS Messages')); ?>

            </h2>
            <a href="<?php echo e(route('sms.messages.create')); ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                New Message
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Search and Filters Section -->
                    <div class="mb-6 space-y-4">
                        <!-- Status Filters -->
                        <div class="flex flex-wrap gap-2">
                            <a href="<?php echo e(route('sms.messages.index', ['status' => 'all'] + request()->except('status', 'page'))); ?>" 
                               class="px-4 py-2 rounded <?php echo e(request('status', 'all') == 'all' ? 'bg-blue-500 text-white' : 'bg-gray-100'); ?>">
                                All
                            </a>
                            <a href="<?php echo e(route('sms.messages.index', ['status' => 'scheduled'] + request()->except('status', 'page'))); ?>" 
                               class="px-4 py-2 rounded <?php echo e(request('status') == 'scheduled' ? 'bg-blue-500 text-white' : 'bg-gray-100'); ?>">
                                Scheduled
                            </a>
                            <a href="<?php echo e(route('sms.messages.index', ['status' => 'sent'] + request()->except('status', 'page'))); ?>" 
                               class="px-4 py-2 rounded <?php echo e(request('status') == 'sent' ? 'bg-blue-500 text-white' : 'bg-gray-100'); ?>">
                                Sent
                            </a>
                            <a href="<?php echo e(route('sms.messages.index', ['status' => 'failed'] + request()->except('status', 'page'))); ?>" 
                               class="px-4 py-2 rounded <?php echo e(request('status') == 'failed' ? 'bg-blue-500 text-white' : 'bg-gray-100'); ?>">
                                Failed
                            </a>
                        </div>

                        <!-- Search and Advanced Filters -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <form action="<?php echo e(route('sms.messages.index')); ?>" method="GET" class="space-y-4">
                                <input type="hidden" name="status" value="<?php echo e(request('status', 'all')); ?>">
                                
                                <!-- Search Bar -->
                                <div class="flex gap-4">
                                    <div class="flex-1">
                                        <label for="search" class="sr-only">Search messages</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </div>
                                            <input type="text" 
                                                   name="search" 
                                                   id="search" 
                                                   value="<?php echo e(request('search')); ?>"
                                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                   placeholder="Search message content or recipients...">
                                        </div>
                                    </div>
                                    
                                    <button type="button" 
                                            onclick="toggleAdvancedFilters()"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                        </svg>
                                        Filters
                                    </button>
                                </div>

                                <!-- Advanced Filters (Hidden by default) -->
                                <div id="advancedFilters" class="hidden space-y-4">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <!-- Date Range -->
                                        <div>
                                            <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                                            <input type="date" 
                                                   name="date_from" 
                                                   id="date_from"
                                                   value="<?php echo e(request('date_from')); ?>"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                                            <input type="date" 
                                                   name="date_to" 
                                                   id="date_to"
                                                   value="<?php echo e(request('date_to')); ?>"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        </div>
                                        
                                        <!-- Group Filter -->
                                        <div>
                                            <label for="group" class="block text-sm font-medium text-gray-700">Group</label>
                                            <select name="group" 
                                                    id="group"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                                <option value="">All Groups</option>
                                                <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($group->id); ?>" <?php echo e(request('group') == $group->id ? 'selected' : ''); ?>>
                                                        <?php echo e($group->name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="flex justify-end space-x-3">
                                        <button type="reset"
                                                onclick="resetFilters()"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Reset Filters
                                        </button>
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Apply Filters
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php if($messages->count() > 0): ?>
                        <form action="<?php echo e(route('sms.messages.bulk-action')); ?>" method="POST" id="bulkActionForm">
                            <?php echo csrf_field(); ?>
                            <div class="overflow-x-auto">
                                <div class="mb-4 flex items-center space-x-4">
                                    <select name="bulk_action" 
                                            id="bulk_action"
                                            class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="">Select Action</option>
                                        <option value="cancel">Cancel Selected</option>
                                        <option value="delete">Delete Selected</option>
                                    </select>
                                    <button type="submit"
                                            onclick="return handleBulkAction()"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                                            disabled
                                            id="bulkActionButton">
                                        Apply to Selected
                                    </button>
                                </div>

                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="w-4 px-6 py-3">
                                                <input type="checkbox" 
                                                       id="selectAll"
                                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                       onclick="toggleAllCheckboxes()">
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Content</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipients</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheduled For</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="px-6 py-4">
                                                    <input type="checkbox" 
                                                           name="selected_messages[]" 
                                                           value="<?php echo e($message->id); ?>"
                                                           class="message-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                           onclick="updateBulkActionButton()">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900"><?php echo e(Str::limit($message->content, 50)); ?></div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900">
                                                        <?php echo e($message->recipients_count); ?> recipient(s)
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        <?php echo e($message->status === 'sent' ? 'bg-green-100 text-green-800' : 
                                                           ($message->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : 
                                                           ($message->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))); ?>">
                                                        <?php echo e(ucfirst($message->status)); ?>

                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900">
                                                        <?php echo e($message->scheduled_at ? $message->scheduled_at->format('M d, Y H:i') : 'Immediate'); ?>

                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                                    <a href="<?php echo e(route('sms.messages.show', $message)); ?>" class="text-blue-600 hover:text-blue-900">View</a>
                                                    <?php if($message->status === 'scheduled'): ?>
                                                        <form action="<?php echo e(route('sms.messages.cancel', $message)); ?>" method="POST" class="inline">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('PUT'); ?>
                                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                                    onclick="return confirm('Are you sure you want to cancel this message?')">
                                                                Cancel
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                        <div class="mt-4">
                            <?php echo e($messages->links()); ?>

                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-4">No messages found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        function toggleAdvancedFilters() {
            const filters = document.getElementById('advancedFilters');
            filters.classList.toggle('hidden');
        }

        function resetFilters() {
            const form = event.target.closest('form');
            const searchInput = form.querySelector('input[name="search"]');
            const statusInput = form.querySelector('input[name="status"]');
            const currentStatus = statusInput.value;

            // Reset all form fields
            form.reset();

            // Restore the current status filter
            statusInput.value = currentStatus;

            // Submit the form
            form.submit();
        }

        function toggleAllCheckboxes() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const messageCheckboxes = document.querySelectorAll('.message-checkbox');
            
            messageCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            
            updateBulkActionButton();
        }
        
        function updateBulkActionButton() {
            const selectedCheckboxes = document.querySelectorAll('.message-checkbox:checked');
            const bulkActionButton = document.getElementById('bulkActionButton');
            const selectAllCheckbox = document.getElementById('selectAll');
            const messageCheckboxes = document.querySelectorAll('.message-checkbox');
            
            // Update bulk action button state
            bulkActionButton.disabled = selectedCheckboxes.length === 0;
            
            // Update select all checkbox state
            selectAllCheckbox.checked = messageCheckboxes.length > 0 && 
                                      selectedCheckboxes.length === messageCheckboxes.length;
            selectAllCheckbox.indeterminate = selectedCheckboxes.length > 0 && 
                                            selectedCheckboxes.length < messageCheckboxes.length;
        }
        
        function handleBulkAction() {
            const selectedAction = document.getElementById('bulk_action').value;
            const selectedCount = document.querySelectorAll('.message-checkbox:checked').length;
            
            if (!selectedAction) {
                alert('Please select an action to perform');
                return false;
            }
            
            if (selectedCount === 0) {
                alert('Please select at least one message');
                return false;
            }
            
            const actionText = selectedAction === 'cancel' ? 'cancel' : 'delete';
            return confirm(`Are you sure you want to ${actionText} ${selectedCount} selected message(s)?`);
        }
    </script>
    <?php $__env->stopPush(); ?>
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
<?php /**PATH C:\xampp\htdocs\wowmin\resources\views\sms\messages\index.blade.php ENDPATH**/ ?>