<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Preview SMS Template') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">{{ $template->name }}</h3>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <p class="whitespace-pre-wrap">{{ $template->content }}</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-md font-semibold mb-2">Preview with Sample Data</h4>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="whitespace-pre-wrap">{{ $preview }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('sms-templates.edit', $template) }}" 
                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Edit Template
                        </a>
                        <a href="{{ route('sms-templates.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Back to Templates
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
