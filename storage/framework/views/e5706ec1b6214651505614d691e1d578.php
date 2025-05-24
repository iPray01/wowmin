<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['donation']));

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

foreach (array_filter((['donation']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-2xl font-semibold text-metal-gold mb-4">Complete Your Donation</h3>
    
    <div class="mb-6">
        <div class="flex justify-between mb-2">
            <span class="text-gray-600">Amount:</span>
            <span class="font-semibold">GH₵ <?php echo e(number_format($donation->amount, 2)); ?></span>
        </div>
        <?php if($donation->campaign): ?>
            <div class="flex justify-between mb-2">
                <span class="text-gray-600">Campaign:</span>
                <span><?php echo e($donation->campaign->name); ?></span>
            </div>
        <?php endif; ?>
        <div class="flex justify-between">
            <span class="text-gray-600">Type:</span>
            <span><?php echo e(ucfirst($donation->donation_type)); ?></span>
        </div>
    </div>

    <form id="payment-form" class="space-y-4">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="donation_id" value="<?php echo e($donation->id); ?>">
        
        <!-- Gift Aid Section -->
        <?php if($donation->is_gift_aid_eligible): ?>
        <div class="bg-teal-50 p-4 rounded-md mb-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-teal" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-teal">Gift Aid Eligible</h3>
                    <p class="mt-1 text-sm text-teal-700">
                        Your donation qualifies for Gift Aid. We can claim an additional 25% from the government.
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Stripe Elements Placeholder -->
        <div class="bg-gray-50 p-4 rounded-md">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Card Information
            </label>
            <div id="card-element" class="p-3 border rounded-md bg-white"></div>
            <div id="card-errors" class="mt-2 text-crimson text-sm" role="alert"></div>
        </div>

        <!-- Recurring Donation Option -->
        <?php if($donation->is_recurring): ?>
        <div class="bg-gray-50 p-4 rounded-md">
            <h4 class="font-medium text-gray-700 mb-2">Recurring Payment</h4>
            <p class="text-sm text-gray-600">
                This donation will automatically repeat <?php echo e($donation->recurrence_frequency); ?>.
                You can cancel at any time from your donation history.
            </p>
        </div>
        <?php endif; ?>

        <button type="submit" 
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal hover:bg-teal-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal"
            id="submit-button">
            <span id="button-text">Pay GH₵ <?php echo e(number_format($donation->amount, 2)); ?></span>
            <div id="spinner" class="hidden">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </button>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('<?php echo e(config('services.stripe.key')); ?>');
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const spinner = document.getElementById('spinner');
    const buttonText = document.getElementById('button-text');

    card.addEventListener('change', ({error}) => {
        const displayError = document.getElementById('card-errors');
        if (error) {
            displayError.textContent = error.message;
        } else {
            displayError.textContent = '';
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        setLoading(true);

        try {
            const response = await fetch(`/donations/${form.donation_id.value}/process-payment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const { clientSecret } = await response.json();

            const result = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: card,
                    billing_details: {
                        name: '<?php echo e($donation->member->full_name); ?>'
                    }
                }
            });

            if (result.error) {
                document.getElementById('card-errors').textContent = result.error.message;
                setLoading(false);
            } else {
                window.location.href = `/donations/${form.donation_id.value}/success`;
            }
        } catch (error) {
            document.getElementById('card-errors').textContent = 'An error occurred while processing your payment.';
            setLoading(false);
        }
    });

    function setLoading(isLoading) {
        if (isLoading) {
            submitButton.disabled = true;
            spinner.classList.remove('hidden');
            buttonText.classList.add('hidden');
        } else {
            submitButton.disabled = false;
            spinner.classList.add('hidden');
            buttonText.classList.remove('hidden');
        }
    }
</script>
<?php $__env->stopPush(); ?> <?php /**PATH C:\xampp\htdocs\wowmin\resources\views\components\donation-payment-form.blade.php ENDPATH**/ ?>