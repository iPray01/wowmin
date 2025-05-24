@extends('layouts.app')

@section('title', $campaign->name . ' - Progress')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $campaign->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">
                {{ $campaign->start_date->format('M d, Y') }} - 
                {{ $campaign->end_date ? $campaign->end_date->format('M d, Y') : 'Ongoing' }}
            </p>
        </div>
        <a href="{{ route('finance.campaigns.index') }}"
            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
            </svg>
            Back to Campaigns
        </a>
    </div>

    <!-- Progress Overview -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Progress Bar and Stats -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Overall Progress</h2>
                <div class="w-full bg-gray-200 rounded-full h-4 mb-4">
                    <div class="bg-teal h-4 rounded-full" style="width: {{ $campaign->progress_percentage }}%"></div>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Target Amount:</span>
                        <span class="font-semibold">{{ number_format($campaign->target_amount, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Total Raised:</span>
                        <span class="font-semibold text-green-600">
                            {{ number_format($campaign->total_fulfilled + $campaign->total_donations, 2) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600">Progress:</span>
                        <span class="font-semibold">{{ number_format($campaign->progress_percentage, 1) }}%</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Remaining:</span>
                        <span class="font-semibold text-red-600">
                            {{ number_format($campaign->remaining_amount, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Contribution Breakdown -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Contribution Breakdown</h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Direct Donations:</span>
                        <span class="text-sm font-semibold">{{ number_format($campaign->total_donations, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Pledges Fulfilled:</span>
                        <span class="text-sm font-semibold">{{ number_format($campaign->total_fulfilled, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Pending Pledges:</span>
                        <span class="text-sm font-semibold text-yellow-600">
                            {{ number_format($campaign->total_pledges - $campaign->total_fulfilled, 2) }}
                        </span>
                    </div>
                    <div class="pt-2 border-t">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Total Pledged:</span>
                            <span class="text-sm font-semibold">{{ number_format($campaign->total_pledges, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Collection Timeline</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Monthly Progress -->
            <div class="col-span-2">
                <canvas id="monthlyProgress" height="200"></canvas>
            </div>
            <!-- Key Statistics -->
            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-600">Average Monthly Collection</h3>
                    <p class="text-2xl font-bold text-teal mt-1">
                        {{ number_format(($campaign->total_fulfilled + $campaign->total_donations) / max(1, $campaign->start_date->diffInMonths($campaign->end_date ?? now()) + 1), 2) }}
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-600">Best Month</h3>
                    <p class="text-2xl font-bold text-teal mt-1">
                        {{ number_format($bestMonth['amount'] ?? 0, 2) }}
                        @if(isset($bestMonth['month']))
                            <span class="text-sm text-gray-600 block">{{ $bestMonth['month'] }}</span>
                        @endif
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-600">Time Remaining</h3>
                    <p class="text-2xl font-bold text-teal mt-1">
                        @if($campaign->end_date)
                            {{ $campaign->end_date->diffForHumans(['parts' => 2]) }}
                        @else
                            Ongoing
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentActivity as $activity)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $activity->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $activity->type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $activity->member->full_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($activity->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $activity->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($activity->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                        'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($activity->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('monthlyProgress').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($monthlyData['labels']),
            datasets: [{
                label: 'Monthly Collections',
                data: @json($monthlyData['values']),
                borderColor: '#0694a2',
                backgroundColor: 'rgba(6, 148, 162, 0.1)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Amount: ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection 