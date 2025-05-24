@extends('layouts.app')

@section('title', 'Financial Management')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="card bg-white">
            <h3 class="text-sm font-medium text-gray-500">Total Income (YTD)</h3>
            <p class="mt-2 text-3xl font-bold" style="color: var(--color-teal)">${{ number_format($statistics->total_income ?? 0, 2) }}</p>
            <p class="mt-1 text-sm text-gray-500">
                @if(($statistics->income_trend ?? 0) > 0)
                    <span class="text-green-600">↑ {{ $statistics->income_trend }}%</span>
                @else
                    <span class="text-red-600">↓ {{ abs($statistics->income_trend) }}%</span>
                @endif
                vs last year
            </p>
        </div>

        <div class="card bg-white">
            <h3 class="text-sm font-medium text-gray-500">Total Expenses (YTD)</h3>
            <p class="mt-2 text-3xl font-bold" style="color: var(--color-burgundy)">${{ number_format($statistics->total_expenses ?? 0, 2) }}</p>
            <p class="mt-1 text-sm text-gray-500">
                @if(($statistics->expense_trend ?? 0) > 0)
                    <span class="text-red-600">↑ {{ $statistics->expense_trend }}%</span>
                @else
                    <span class="text-green-600">↓ {{ abs($statistics->expense_trend) }}%</span>
                @endif
                vs last year
            </p>
        </div>

        <div class="card bg-white">
            <h3 class="text-sm font-medium text-gray-500">Net Balance</h3>
            <p class="mt-2 text-3xl font-bold" style="color: var(--color-crimson)">${{ number_format(($statistics->total_income ?? 0) - ($statistics->total_expenses ?? 0), 2) }}</p>
            <p class="mt-1 text-sm text-gray-500">Current fiscal year</p>
        </div>

        <div class="card bg-white">
            <h3 class="text-sm font-medium text-gray-500">Pending Pledges</h3>
            <p class="mt-2 text-3xl font-bold" style="color: var(--color-teal)">${{ number_format($statistics->pending_pledges ?? 0, 2) }}</p>
            <p class="mt-1 text-sm text-gray-500">{{ $statistics->active_pledges ?? 0 }} active pledges</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Recent Transactions -->
        <div class="md:col-span-2 space-y-6">
            <div class="card">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">Recent Transactions</h2>
                    <div class="flex space-x-2">
                        <a href="{{ route('finance.donations.create') }}" class="btn btn-primary">Record Donation</a>
                        <a href="{{ route('finance.expenses.create') }}" class="btn btn-secondary">Record Expense</a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transactions ?? [] as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $transaction->date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $transaction->description }}
                                        </div>
                                        @if($transaction->member)
                                            <div class="text-sm text-gray-500">
                                                {{ $transaction->member->name }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                            style="{{ $transaction->type === 'income' ? 'background-color: var(--color-teal)' : 'background-color: var(--color-burgundy)' }}; color: white">
                                            {{ ucfirst($transaction->category) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" 
                                        style="color: {{ $transaction->type === 'income' ? 'var(--color-teal)' : 'var(--color-crimson)' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        No recent transactions
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(isset($transactions) && $transactions->hasPages())
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>

            <!-- Monthly Overview -->
            <div class="card">
                <h2 class="text-xl font-bold mb-6">Monthly Overview</h2>
                <div class="h-64">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="card">
                <h3 class="text-lg font-medium mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('finance.pledges.create') }}" class="btn btn-secondary block text-center">Create Pledge</a>
                    <a href="{{ route('finance.reports.generate') }}" class="btn btn-secondary block text-center">Generate Report</a>
                    <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary block text-center">Manage Budget</a>
                </div>
            </div>

            <!-- Upcoming Pledges -->
            <div class="card">
                <h3 class="text-lg font-medium mb-4">Upcoming Pledges</h3>
                <div class="space-y-4">
                    @forelse($upcoming_pledges ?? [] as $pledge)
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium">{{ $pledge->member->name }}</p>
                                <p class="text-xs text-gray-500">Due {{ $pledge->due_date->format('M d, Y') }}</p>
                            </div>
                            <span class="text-sm font-medium" style="color: var(--color-teal)">${{ number_format($pledge->amount, 2) }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No upcoming pledges</p>
                    @endforelse
                </div>
            </div>

            <!-- Budget Overview -->
            <div class="card">
                <h3 class="text-lg font-medium mb-4">Budget Overview</h3>
                <div class="space-y-4">
                    @foreach($budget_categories ?? [] as $category)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium">{{ $category->name }}</span>
                                <span class="text-sm text-gray-500">
                                    {{ number_format($category->spent / $category->allocated * 100, 0) }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full" style="width: {{ ($category->spent / $category->allocated * 100) }}%; background-color: 
                                    {{ ($category->spent / $category->allocated) > 0.9 ? 'var(--color-crimson)' : 
                                       ($category->spent / $category->allocated) > 0.7 ? 'var(--color-burgundy)' : 'var(--color-teal)' }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chart_data->labels ?? []),
            datasets: [
                {
                    label: 'Income',
                    data: @json($chart_data->income ?? []),
                    borderColor: getComputedStyle(document.documentElement).getPropertyValue('--color-teal'),
                    tension: 0.4
                },
                {
                    label: 'Expenses',
                    data: @json($chart_data->expenses ?? []),
                    borderColor: getComputedStyle(document.documentElement).getPropertyValue('--color-burgundy'),
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
