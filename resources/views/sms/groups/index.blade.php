<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('SMS Groups') }}
            </h2>
            <a href="{{ route('sms-groups.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Group
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($groups->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($groups as $group)
                                <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                                    <h3 class="text-lg font-semibold mb-2">{{ $group->name }}</h3>
                                    <p class="text-gray-600 mb-2">{{ $group->description }}</p>
                                    <p class="text-sm text-gray-500 mb-4">{{ $group->members_count }} members</p>
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('sms-groups.show', $group) }}" 
                                           class="text-blue-500 hover:text-blue-700">
                                            View Members
                                        </a>
                                        <a href="{{ route('sms-groups.edit', $group) }}" 
                                           class="text-green-500 hover:text-green-700">
                                            Edit
                                        </a>
                                        <form action="{{ route('sms-groups.destroy', $group) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" 
                                                    onclick="return confirm('Are you sure you want to delete this group?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $groups->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No SMS groups found. Create your first group!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
