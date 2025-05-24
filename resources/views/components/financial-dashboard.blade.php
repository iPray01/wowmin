@props(['statistics', 'campaignStats'])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Total Donations Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-metal-gold mb-4">Total Donations</h3>
        <div class="flex items-baseline">
            <span class="text-3xl font-bold">GH₵ {{ number_format($statistics->sum('total_amount'), 2) }}</span>
            <span class="ml-2 text-sm text-gray-500">This year</span>
        </div>
        <div class="mt-2 text-sm text-teal">
            +GH₵ {{ number_format($statistics->sum('total_gift_aid'), 2) }} Gift Aid
        </div>
    </div>

    <!-- Donation Trends Chart -->
    <div class="bg-white rounded-lg shadow-md p-6 md:col-span-2">
        <h3 class="text-lg font-semibold text-metal-gold mb-4">Donation Trends</h3>
        <div class="h-64">
            <canvas id="donationTrendsChart"></canvas>
        </div>
    </div>

    <!-- Campaign Progress -->
    <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
        <h3 class="text-lg font-semibold text-metal-gold mb-4">Campaign Progress</h3>
        <div class="space-y-4">
            @foreach($campaignStats as $campaign)
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span>{{ $campaign['name'] }}</span>
                    <span>{{ number_format($campaign['progress_percentage'], 1) }}%</span>
                </div>
                <div class="relative pt-1">
                    <div class="overflow-hidden h-2 text-xs flex rounded bg-teal-200">
                        <div style="width: {{ $campaign['progress_percentage'] }}%"
                            class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-teal">
                        </div>
                    </div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span>GH₵ {{ number_format($campaign['total_donations'], 2) }}</span>
                    <span>GH₵ {{ number_format($campaign['target_amount'], 2) }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Donation Types Distribution -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-metal-gold mb-4">Donation Types</h3>
        <div class="h-64">
            <canvas id="donationTypesChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Prepare data for charts
    const monthlyData = @json($statistics->groupBy('month'));
    const donationTypes = @json($statistics->groupBy('donation_type'));

    // Donation Trends Chart
    new Chart(document.getElementById('donationTrendsChart'), {
        type: 'line',
        data: {
            labels: Object.keys(monthlyData),
            datasets: [{
                label: 'Donations',
                data: Object.values(monthlyData).map(month => 
                    month.reduce((sum, record) => sum + parseFloat(record.total_amount), 0)
                ),
                borderColor: '#0D9488',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'GH₵ ' + value.toLocaleString()
                    }
                }
            }
        }
    });

    // Donation Types Chart
    new Chart(document.getElementById('donationTypesChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(donationTypes),
            datasets: [{
                data: Object.values(donationTypes).map(type =>
                    type.reduce((sum, record) => sum + parseFloat(record.total_amount), 0)
                ),
                backgroundColor: [
                    '#0D9488',
                    '#B45309',
                    '#9F1239',
                    '#1E40AF',
                    '#047857'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush 