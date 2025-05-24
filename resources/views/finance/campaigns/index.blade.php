@extends('layouts.app')

@section('title', 'Campaigns')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Fundraising Campaigns</h1>
        <a href="{{ route('finance.campaigns.create') }}"
            class="bg-teal hover:bg-teal-dark text-white font-bold py-2 px-4 rounded inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            New Campaign
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form action="{{ route('finance.campaigns.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-teal focus:border-teal sm:text-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                </select>
            </div>

            <!-- Date Range -->
            <div>
                <label for="date_range" class="block text-sm font-medium text-gray-700">Date Range</label>
                <select name="date_range" id="date_range" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-teal focus:border-teal sm:text-sm">
                    <option value="">All Time</option>
                    <option value="current" {{ request('date_range') == 'current' ? 'selected' : '' }}>Current</option>
                    <option value="past" {{ request('date_range') == 'past' ? 'selected' : '' }}>Past</option>
                    <option value="future" {{ request('date_range') == 'future' ? 'selected' : '' }}>Future</option>
                </select>
            </div>

            <!-- Filter Button -->
            <div class="flex items-end">
                <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Total Campaigns -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-sm font-medium text-gray-500">Total Campaigns</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">
                {{ $campaigns->count() }}
            </div>
        </div>

        <!-- Total Target Amount -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-sm font-medium text-gray-500">Total Target Amount</div>
            <div class="mt-2 text-3xl font-bold text-teal">
                {{ number_format($campaigns->sum('target_amount'), 2) }}
            </div>
        </div>

        <!-- Total Amount Raised -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-sm font-medium text-gray-500">Total Amount Raised</div>
            <div class="mt-2 text-3xl font-bold text-green-600">
                {{ number_format($campaigns->sum('total_fulfilled') + $campaigns->sum('total_donations'), 2) }}
            </div>
        </div>

        <!-- Overall Progress -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-sm font-medium text-gray-500">Overall Progress</div>
            <div class="mt-2 text-3xl font-bold text-blue-600">
                @php
                    $totalTarget = $campaigns->sum('target_amount');
                    $totalRaised = $campaigns->sum('total_fulfilled') + $campaigns->sum('total_donations');
                    $overallProgress = $totalTarget > 0 ? min(100, ($totalRaised / $totalTarget) * 100) : 0;
                @endphp
                {{ number_format($overallProgress, 1) }}%
            </div>
        </div>
    </div>

    <!-- Campaigns Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Campaign
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Target
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Raised
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Progress
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($campaigns as $campaign)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $campaign->name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $campaign->start_date->format('M d, Y') }} - 
                                {{ $campaign->end_date ? $campaign->end_date->format('M d, Y') : 'Ongoing' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ number_format($campaign->target_amount, 2) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ number_format($campaign->total_fulfilled + $campaign->total_donations, 2) }}
                                <div class="text-xs text-gray-500">
                                    Pledged: {{ number_format($campaign->total_pledges, 2) }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-teal h-2.5 rounded-full" style="width: {{ $campaign->progress_percentage }}%"></div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ number_format($campaign->progress_percentage, 1) }}%
                                @if($campaign->remaining_amount > 0)
                                    <span class="ml-2">
                                        ({{ number_format($campaign->remaining_amount, 2) }} remaining)
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $campaign->is_active_now ? 'bg-green-100 text-green-800' : 
                                   ($campaign->end_date && $campaign->end_date->isPast() ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $campaign->is_active_now ? 'Active' : 
                                   ($campaign->end_date && $campaign->end_date->isPast() ? 'Completed' : 'Upcoming') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('finance.campaigns.show', $campaign) }}" 
                               class="text-teal hover:text-teal-dark mr-3">View</a>
                            <a href="{{ route('finance.campaigns.edit', $campaign) }}" 
                               class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            No campaigns found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $campaigns->links() }}
    </div>
</div>
@endsection
