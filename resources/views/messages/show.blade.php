<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $thread->subject }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('messages.create', ['reply_to' => $thread->id]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Reply') }}
                </a>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                        {{ __('Actions') }}
                        <svg class="ml-2 -mr-0.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                        <div class="py-1">
                            @if(request('status') !== 'archived')
                                <form action="{{ route('messages.archive', $thread->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Archive Thread
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('messages.unarchive', $thread->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Move to Inbox
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('messages.destroy', $thread->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100" onclick="return confirm('Are you sure you want to delete this thread?')">
                                    Delete Thread
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Participants -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Participants</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($thread->participants as $participant)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $participant->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="space-y-6">
                        @foreach($thread->messages as $message)
                            <div class="flex space-x-4 {{ $message->sender_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-600 font-medium">
                                            {{ strtoupper(substr($message->sender->name, 0, 2)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow {{ $message->sender_id === auth()->id() ? 'items-end' : 'items-start' }}">
                                    <div class="bg-gray-100 rounded-lg p-4 {{ $message->sender_id === auth()->id() ? 'bg-blue-100' : '' }}">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="font-medium text-gray-900">
                                                {{ $message->sender->name }}
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                {{ $message->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="text-gray-700 whitespace-pre-wrap">{{ $message->content }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Reply Form -->
                    <form action="{{ route('messages.store') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="thread_id" value="{{ $thread->id }}">
                        
                        <div class="mb-4">
                            <label for="content" class="sr-only">Message</label>
                            <textarea name="content" id="content" rows="4" 
                                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                      placeholder="Type your message..."
                                      required>{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Send Reply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 