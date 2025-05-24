@extends('layouts.app')

@section('title', 'View Harvest Record')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">View Harvest Record</h1>
        <div class="space-x-2">
            <a href="{{ route('finance.harvest.edit', $harvest) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-4 rounded">
                Edit Record
            </a>
            <a href="{{ route('finance.harvest.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                Back to List
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Basic Information</h2>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Harvest Name</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $harvest->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date</dt>
                        <dd class="mt-1 text-gray-900">{{ $harvest->harvest_date->format('F d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1 text-gray-900">{{ ucfirst($harvest->type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $harvest->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($harvest->status === 'ongoing' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($harvest->status) }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Financial Information -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Financial Information</h2>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Target Amount</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">GH₵{{ number_format($harvest->target_amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Amount Raised</dt>
                        <dd class="mt-1 text-lg font-semibold" style="color: var(--color-teal)">GH₵{{ number_format($harvest->amount_raised, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Progress</dt>
                        <dd class="mt-1">
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-teal-600 h-2.5 rounded-full" style="width: {{ min(100, ($harvest->amount_raised / $harvest->target_amount) * 100) }}%"></div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">{{ number_format(($harvest->amount_raised / $harvest->target_amount) * 100, 1) }}% of target</p>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Number of Contributors</dt>
                        <dd class="mt-1 text-gray-900">{{ $harvest->contributors_count }} contributors</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Description -->
        @if($harvest->description)
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Description</h2>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-700 whitespace-pre-wrap">{{ $harvest->description }}</p>
            </div>
        </div>
        @endif

        <!-- Recent Contributions -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Contributions</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($harvest->contributions as $contribution)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $contribution->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($contribution->is_anonymous)
                                        <span class="text-gray-500 italic">Anonymous</span>
                                    @else
                                        <div class="text-sm font-medium text-gray-900">{{ $contribution->member->name }}</div>
                                        <div class="text-sm text-gray-500">ID: {{ $contribution->member->member_id }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="color: var(--color-teal)">
                                    GH₵{{ number_format($contribution->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ ucfirst(str_replace('_', ' ', $contribution->payment_method)) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    No contributions recorded yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex justify-between">
                <div class="flex space-x-3">
                    @if($harvest->status !== 'completed')
                    <form action="{{ route('finance.harvest.update', $harvest) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-check mr-2"></i>
                            Mark as Completed
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('finance.harvest.download-report', $harvest) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-download mr-2"></i>
                        Download Report
                    </a>
                </div>
                @if($harvest->status !== 'completed')
                <form action="{{ route('finance.harvest.destroy', $harvest) }}" method="POST" class="inline"
                      onsubmit="return confirm('Are you sure you want to delete this harvest record? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Delete Record
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 