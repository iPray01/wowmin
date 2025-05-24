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
            <?php echo e(__('Create New SMS Message')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="<?php echo e(route('sms.messages.store')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="mb-4">
                            <label for="template_id" class="block text-sm font-medium text-gray-700">Use Template (Optional)</label>
                            <div class="mt-1 flex space-x-2">
                                <select name="template_id" id="template_id" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        onchange="loadTemplate(this.value)">
                                    <option value="">Select a template...</option>
                                    <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($template->id); ?>" 
                                                data-content="<?php echo e($template->content); ?>"
                                                data-name="<?php echo e($template->name); ?>">
                                            <?php echo e($template->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <button type="button" 
                                        onclick="previewTemplate()"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Preview
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700">Message Content</label>
                            <textarea name="content" id="content" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      required
                                      onkeyup="updateMessageStats(this.value)"><?php echo e(old('content')); ?></textarea>
                            <div class="mt-2 flex items-center justify-between text-sm text-gray-500">
                                <div>
                                    Available variables: {name}, {phone}, {email}, etc.
                                </div>
                                <div class="text-right">
                                    <span id="charCount">0</span> characters |
                                    <span id="segmentCount">1</span> segment(s)
                                </div>
                            </div>
                            <?php $__errorArgs = ['content'];
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

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Recipients</label>
                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Manual Phone Numbers</h4>
                                    <div class="border rounded-md p-4">
                                        <div class="mb-2">
                                            <textarea name="manual_numbers" id="manual_numbers" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                placeholder="Enter phone numbers, one per line. Format: +1234567890"><?php echo e(old('manual_numbers')); ?></textarea>
                                            <p class="mt-1 text-xs text-gray-500">Enter each phone number on a new line. Include country code (e.g., +1 for US).</p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Select Groups</h4>
                                    <div class="border rounded-md p-4 max-h-40 overflow-y-auto">
                                        <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex items-center mb-2">
                                                <input type="checkbox" name="groups[]" value="<?php echo e($group->id); ?>"
                                                       class="h-4 w-4 rounded border-gray-400 text-blue-600 focus:ring-blue-500"
                                                       <?php echo e(in_array($group->id, old('groups', [])) ? 'checked' : ''); ?>>
                                                <label class="ml-2 text-sm text-gray-700">
                                                    <?php echo e($group->name); ?> (<?php echo e($group->members_count); ?> members)
                                                </label>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Individual Recipients</h4>
                                    <div class="border rounded-md p-4 max-h-40 overflow-y-auto">
                                        <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex items-center mb-2">
                                                <input type="checkbox" name="members[]" value="<?php echo e($member->id); ?>"
                                                       class="h-4 w-4 rounded border-gray-400 text-blue-600 focus:ring-blue-500"
                                                       <?php echo e(in_array($member->id, old('members', [])) ? 'checked' : ''); ?>>
                                                <label class="ml-2 text-sm text-gray-700">
                                                    <?php echo e($member->name); ?> (<?php echo e($member->phone); ?>)
                                                </label>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                            <?php $__errorArgs = ['groups'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <?php $__errorArgs = ['members'];
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

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sending Options</label>
                            <div class="space-y-4">
                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="send_time" value="now" class="form-radio" checked
                                               onclick="document.getElementById('scheduled_at').disabled = true">
                                        <span class="ml-2">Send Immediately</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="send_time" value="scheduled" class="form-radio"
                                               onclick="document.getElementById('scheduled_at').disabled = false">
                                        <span class="ml-2">Schedule for Later</span>
                                    </label>
                                    <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                                           class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           disabled>
                                </div>
                            </div>
                            <?php $__errorArgs = ['scheduled_at'];
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

                        <div class="flex justify-end space-x-3">
                            <a href="<?php echo e(route('sms.messages.index')); ?>" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden" style="z-index: 50;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-medium text-gray-900" id="previewTitle"></h3>
                        <button type="button" onclick="closePreview()" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div class="border rounded-lg p-4 bg-gray-50">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Sample Preview</h4>
                            <p class="text-gray-900 whitespace-pre-wrap" id="previewContent"></p>
                        </div>
                        <div class="border rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">With Variables</h4>
                            <p class="text-gray-900 whitespace-pre-wrap" id="previewRawContent"></p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 rounded-b-lg flex justify-end">
                    <button type="button" 
                            onclick="closePreview()"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        function loadTemplate(templateId) {
            if (!templateId) {
                document.getElementById('content').value = '';
                updateMessageStats('');
                return;
            }
            
            const option = document.querySelector(`option[value="${templateId}"]`);
            if (option) {
                const content = option.dataset.content;
                document.getElementById('content').value = content;
                updateMessageStats(content);
            }
        }

        function updateMessageStats(text) {
            const charCount = text.length;
            let segments = 1;
            
            // GSM-7 encoding limits
            const GSM_SINGLE_LIMIT = 160;
            const GSM_MULTI_LIMIT = 153;
            
            // Check if message contains any non-GSM characters
            const nonGsmRegex = /[^\x20-\x7E¡£¤¥§¿ÄÅÆÇÉÑÖØÜßàäåæèéìñòöøùü\n\r]/;
            const isUnicode = nonGsmRegex.test(text);
            
            if (isUnicode) {
                // Unicode (UCS-2) limits
                if (charCount > 70) {
                    segments = Math.ceil(charCount / 67);
                }
            } else {
                // GSM-7 limits
                if (charCount > GSM_SINGLE_LIMIT) {
                    segments = Math.ceil(charCount / GSM_MULTI_LIMIT);
                }
            }
            
            document.getElementById('charCount').textContent = charCount;
            document.getElementById('segmentCount').textContent = segments;
            
            // Visual feedback on segment count
            const segmentElement = document.getElementById('segmentCount');
            if (segments > 1) {
                segmentElement.classList.add('text-yellow-600');
            } else {
                segmentElement.classList.remove('text-yellow-600');
            }
        }

        // Initialize stats on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateMessageStats(document.getElementById('content').value);
        });

        function previewTemplate() {
            const templateSelect = document.getElementById('template_id');
            const selectedOption = templateSelect.options[templateSelect.selectedIndex];
            
            if (!selectedOption.value) {
                alert('Please select a template first');
                return;
            }
            
            const content = selectedOption.dataset.content;
            const name = selectedOption.dataset.name;
            
            // Sample data for preview
            const sampleData = {
                name: 'John Doe',
                phone: '+1234567890',
                email: 'john@example.com'
            };
            
            // Replace variables in content
            const previewContent = content.replace(/{(\w+)}/g, (match, key) => {
                return sampleData[key] || match;
            });
            
            document.getElementById('previewTitle').textContent = name;
            document.getElementById('previewContent').textContent = previewContent;
            document.getElementById('previewRawContent').textContent = content;
            document.getElementById('previewModal').classList.remove('hidden');
        }
        
        function closePreview() {
            document.getElementById('previewModal').classList.add('hidden');
        }
        
        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closePreview();
            }
        });
        
        // Close modal on outside click
        document.getElementById('previewModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closePreview();
            }
        });
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
<?php /**PATH C:\xampp\htdocs\wowmin\resources\views\sms\messages\create.blade.php ENDPATH**/ ?>