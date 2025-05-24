<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View SMS Message') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Message Details</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-800 whitespace-pre-wrap">{{ $message->content }}</p>
                            <div class="mt-4 text-sm text-gray-500">
                                <p>Status: 
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $message->status === 'sent' ? 'bg-green-100 text-green-800' : 
                                           ($message->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : 
                                           ($message->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ ucfirst($message->status) }}
                                    </span>
                                </p>
                                <p class="mt-1">Created: {{ $message->created_at->format('M d, Y H:i') }}</p>
                                @if($message->scheduled_at)
                                    <p class="mt-1">Scheduled for: {{ $message->scheduled_at->format('M d, Y H:i') }}</p>
                                @endif
                                @if($message->sent_at)
                                    <p class="mt-1">Sent at: {{ $message->sent_at->format('M d, Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Recipients</h3>
                        <div class="space-y-4">
                            @if($message->group)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Group</h4>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-gray-800">{{ $message->group->name }} ({{ $message->group->members_count }} members)</p>
                                    </div>
                                </div>
                            @endif

                            @if($message->groups && $message->groups->count() > 0)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Groups</h4>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <ul class="list-disc list-inside">
                                            @foreach($message->groups as $group)
                                                <li class="text-gray-800">{{ $group->name }} ({{ $group->members_count }} members)</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Delivery Status</h4>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-600">Total</p>
                                            <p class="text-lg font-semibold">{{ $deliveryStats['total'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Delivered</p>
                                            <p class="text-lg font-semibold text-green-600">{{ $deliveryStats['delivered'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Failed</p>
                                            <p class="text-lg font-semibold text-red-600">{{ $deliveryStats['failed'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Pending</p>
                                            <p class="text-lg font-semibold text-yellow-600">{{ $deliveryStats['pending'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($message->recipients->count() > 0)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Individual Recipients</h4>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead>
                                                    <tr>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sent At</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($message->recipients as $recipient)
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                {{ $recipient->member ? $recipient->member->name : 'Manual Number' }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                {{ $recipient->phone_number }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                    @if($recipient->status === 'delivered') bg-green-100 text-green-800
                                                                    @elseif($recipient->status === 'failed') bg-red-100 text-red-800
                                                                    @else bg-yellow-100 text-yellow-800
                                                                    @endif">
                                                                    {{ ucfirst($recipient->status) }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                {{ $recipient->sent_at ? $recipient->sent_at->format('M d, Y H:i:s') : '-' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        @if($message->status === 'scheduled')
                            <form action="{{ route('sms.messages.cancel', $message) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                        onclick="return confirm('Are you sure you want to cancel this message?')">
                                    Cancel Message
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('sms.messages.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Back to Messages
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
