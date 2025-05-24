<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $group->name }} - Members
            </h2>
            <a href="{{ route('sms-groups.edit', $group) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit Group
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Group Details</h3>
                        <p class="text-gray-600">{{ $group->description }}</p>
                        <p class="text-sm text-gray-500 mt-2">Total Members: {{ $group->members->count() }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-semibold mb-2">Members List</h3>
                        @if($group->members->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($group->members as $member)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $member->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $member->phone }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $member->email }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                                    <form action="{{ route('sms-groups.remove-member', ['group' => $group, 'member' => $member]) }}" 
                                                          method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700"
                                                                onclick="return confirm('Remove this member from the group?')">
                                                            Remove
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No members in this group yet.</p>
                        @endif
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('sms-groups.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Back to Groups
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
