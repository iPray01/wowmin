<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit SMS Message') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('sms.messages.update', $message) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="template_id" class="block text-sm font-medium text-gray-700">Use Template (Optional)</label>
                            <div class="mt-1 flex space-x-2">
                                <select name="template_id" id="template_id" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        onchange="loadTemplate(this.value)">
                                    <option value="">Select a template...</option>
                                    @foreach($templates as $template)
                                        <option value="{{ $template->id }}" 
                                                data-content="{{ $template->content }}"
                                                data-name="{{ $template->name }}"
                                                {{ old('template_id', $message->template_id) == $template->id ? 'selected' : '' }}>
                                            {{ $template->name }}
                                        </option>
                                    @endforeach
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
                                      onkeyup="updateMessageStats(this.value)">{{ old('content', $message->content) }}</textarea>
                            <div class="mt-2 flex items-center justify-between text-sm text-gray-500">
                                <div>
                                    Available variables: {name}, {phone}, {email}, etc.
                                </div>
                                <div class="text-right">
                                    <span id="charCount">0</span> characters |
                                    <span id="segmentCount">1</span> segment(s)
                                </div>
                            </div>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                                                placeholder="Enter phone numbers, one per line. Format: +1234567890">{{ old('manual_numbers', $message->recipients->whereNull('member_id')->pluck('phone_number')->implode("\n")) }}</textarea>
                                            <p class="mt-1 text-xs text-gray-500">Enter each phone number on a new line. Include country code (e.g., +1 for US).</p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Select Groups</h4>
                                    <div class="border rounded-md p-4 max-h-40 overflow-y-auto">
                                        @foreach($groups as $group)
                                            <div class="flex items-center mb-2">
                                                <input type="checkbox" name="groups[]" value="{{ $group->id }}"
                                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                       {{ in_array($group->id, old('groups', [$message->group_id])) ? 'checked' : '' }}>
                                                <label class="ml-2 text-sm text-gray-700">
                                                    {{ $group->name }} ({{ $group->members_count }} members)
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Individual Recipients</h4>
                                    <div class="border rounded-md p-4 max-h-40 overflow-y-auto">
                                        @foreach($members as $member)
                                            <div class="flex items-center mb-2">
                                                <input type="checkbox" name="members[]" value="{{ $member->id }}"
                                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                       {{ in_array($member->id, old('members', $message->recipients->whereNotNull('member_id')->pluck('member_id')->toArray())) ? 'checked' : '' }}>
                                                <label class="ml-2 text-sm text-gray-700">
                                                    {{ $member->name }} ({{ $member->phone }})
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @error('groups')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('members')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sending Options</label>
                            <div class="space-y-4">
                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="send_time" value="now" class="form-radio"
                                               {{ !$message->scheduled_at ? 'checked' : '' }}
                                               onclick="document.getElementById('scheduled_at').disabled = true">
                                        <span class="ml-2">Send Immediately</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="send_time" value="scheduled" class="form-radio"
                                               {{ $message->scheduled_at ? 'checked' : '' }}
                                               onclick="document.getElementById('scheduled_at').disabled = false">
                                        <span class="ml-2">Schedule for Later</span>
                                    </label>
                                    <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                                           class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           value="{{ old('scheduled_at', $message->scheduled_at ? $message->scheduled_at->format('Y-m-d\TH:i') : '') }}"
                                           {{ !$message->scheduled_at ? 'disabled' : '' }}>
                                </div>
                            </div>
                            @error('scheduled_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('sms.messages.show', $message) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Message
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
                <div class="px-6 py-4">
                    <h3 class="text-lg font-semibold mb-2" id="previewTitle"></h3>
                    <div class="bg-gray-100 p-4 rounded-lg mb-4">
                        <p class="whitespace-pre-wrap" id="previewContent"></p>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <h4 class="text-sm font-semibold mb-2">Raw Template</h4>
                        <p class="whitespace-pre-wrap text-sm text-gray-600" id="previewRawContent"></p>
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

    @push('scripts')
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
    @endpush
</x-app-layout> 