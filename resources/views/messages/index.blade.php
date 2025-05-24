<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Messages') }}
            </h2>
            <a href="{{ route('messages.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('New Message') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Status Filter -->
                    <div class="mb-6">
                        <div class="flex space-x-4">
                            @foreach($statuses as $key => $label)
                                <a href="{{ request()->fullUrlWithQuery(['status' => $key]) }}" 
                                   class="px-4 py-2 rounded-md text-sm font-medium {{ request('status', 'all') === $key ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700' }}">
                                    {{ $label }}
                                    @if($key === 'unread' && $unreadCount > 0)
                                        <span class="ml-2 bg-blue-600 text-white px-2 py-0.5 rounded-full text-xs">
                                            {{ $unreadCount }}
                                        </span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Messages List -->
                    <div class="space-y-4">
                        @forelse($threads as $thread)
                            <div class="flex items-center justify-between p-4 {{ $thread->isUnreadByMember(auth()->id()) ? 'bg-blue-50' : 'bg-white' }} border rounded-lg hover:shadow-md transition-shadow duration-200">
                                <div class="flex-1">
                                    <a href="{{ route('messages.show', $thread->id) }}" class="block">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ $thread->subject }}
                                        </h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $thread->latestMessage?->content ?? 'No messages' }}
                                        </p>
                                        <div class="flex items-center mt-2 space-x-4 text-xs text-gray-500">
                                            <span>
                                                {{ $thread->participants->count() }} participants
                                            </span>
                                            <span>
                                                Last updated: {{ $thread->updated_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </a>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($thread->isUnreadByMember(auth()->id()))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Unread
                                        </span>
                                    @endif
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                                            <div class="py-1">
                                                @if($thread->isUnreadByMember(auth()->id()))
                                                    <a href="{{ route('messages.show', $thread->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Mark as Read
                                                    </a>
                                                @endif
                                                @if(request('status') !== 'archived')
                                                    <form action="{{ route('messages.archive', $thread->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            Archive
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
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No messages</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Get started by creating a new message.
                                </p>
                                <div class="mt-6">
                                    <a href="{{ route('messages.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        New Message
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $threads->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 