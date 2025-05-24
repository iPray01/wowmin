<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['statistics', 'campaignStats']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['statistics', 'campaignStats']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Total Donations Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-metal-gold mb-4">Total Donations</h3>
        <div class="flex items-baseline">
            <span class="text-3xl font-bold">GH₵ <?php echo e(number_format($statistics->sum('total_amount'), 2)); ?></span>
            <span class="ml-2 text-sm text-gray-500">This year</span>
        </div>
        <div class="mt-2 text-sm text-teal">
            +GH₵ <?php echo e(number_format($statistics->sum('total_gift_aid'), 2)); ?> Gift Aid
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
            <?php $__currentLoopData = $campaignStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span><?php echo e($campaign['name']); ?></span>
                    <span><?php echo e(number_format($campaign['progress_percentage'], 1)); ?>%</span>
                </div>
                <div class="relative pt-1">
                    <div class="overflow-hidden h-2 text-xs flex rounded bg-teal-200">
                        <div style="width: <?php echo e($campaign['progress_percentage']); ?>%"
                            class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-teal">
                        </div>
                    </div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span>GH₵ <?php echo e(number_format($campaign['total_donations'], 2)); ?></span>
                    <span>GH₵ <?php echo e(number_format($campaign['target_amount'], 2)); ?></span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Prepare data for charts
    const monthlyData = <?php echo json_encode($statistics->groupBy('month'), 15, 512) ?>;
    const donationTypes = <?php echo json_encode($statistics->groupBy('donation_type'), 15, 512) ?>;

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
<?php $__env->stopPush(); ?> <?php /**PATH C:\xampp\htdocs\wowmin\resources\views\components\financial-dashboard.blade.php ENDPATH**/ ?>