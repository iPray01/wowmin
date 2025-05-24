<?php $__env->startSection('title', 'Record Harvest Offering'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="<?php echo e(route('finance.index')); ?>" class="btn btn-secondary">
            ← Back to Finance
        </a>
    </div>

    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Record Harvest Offering</h2>

        <form action="<?php echo e(route('finance.harvest.store')); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>

            <!-- Harvest Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium">Harvest Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="harvest_type" class="form-label">Harvest Type</label>
                        <select name="harvest_type" id="harvest_type" class="form-input" required>
                            <option value="annual">Annual Harvest</option>
                            <option value="first_fruit">First Fruit</option>
                            <option value="special">Special Harvest</option>
                            <option value="thanksgiving">Thanksgiving</option>
                        </select>
                    </div>

                    <div>
                        <label for="harvest_name" class="form-label">Harvest Name/Theme</label>
                        <input type="text" name="harvest_name" id="harvest_name" class="form-input" required>
                    </div>

                    <div>
                        <label for="harvest_date" class="form-label">Harvest Date</label>
                        <input type="date" name="harvest_date" id="harvest_date" class="form-input" 
                               required value="<?php echo e(date('Y-m-d')); ?>">
                    </div>

                    <div>
                        <label for="target_amount" class="form-label">Target Amount</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                            <input type="number" name="target_amount" id="target_amount" class="form-input pl-7" 
                                   step="0.01" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Member Contribution -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium">Member Contribution</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="member_id" class="form-label">Select Member</label>
                        <select name="member_id" id="member_id" class="form-input" required>
                            <option value="">Select Member</option>
                            <?php $__currentLoopData = $members ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($member->id); ?>"><?php echo e($member->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label for="pledge_amount" class="form-label">Pledge Amount</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                            <input type="number" name="pledge_amount" id="pledge_amount" class="form-input pl-7" 
                                   step="0.01">
                        </div>
                    </div>

                    <div>
                        <label for="payment_amount" class="form-label">Payment Amount</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                            <input type="number" name="payment_amount" id="payment_amount" class="form-input pl-7" 
                                   step="0.01" required>
                        </div>
                    </div>

                    <div>
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-input" required>
                            <option value="full">Full Payment</option>
                            <option value="partial">Partial Payment</option>
                            <option value="pledge">Pledge Only</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div id="payment_details" class="space-y-4">
                <h3 class="text-lg font-medium">Payment Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="payment_date" class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" id="payment_date" class="form-input" 
                               value="<?php echo e(date('Y-m-d')); ?>">
                    </div>

                    <div>
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-input">
                            <option value="cash">Cash</option>
                            <option value="check">Check</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="online">Online Payment</option>
                        </select>
                    </div>

                    <div id="check_details" class="hidden">
                        <label for="check_number" class="form-label">Check Number</label>
                        <input type="text" name="check_number" id="check_number" class="form-input">
                    </div>

                    <div id="transaction_details" class="hidden">
                        <label for="transaction_id" class="form-label">Transaction ID</label>
                        <input type="text" name="transaction_id" id="transaction_id" class="form-input">
                    </div>
                </div>
            </div>

            <!-- Installment Plan -->
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="setup_installment" id="setup_installment" class="form-checkbox">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="setup_installment" class="font-medium text-gray-700">Setup Installment Plan</label>
                        <p class="text-gray-500">Break down the pledge into multiple payments</p>
                    </div>
                </div>

                <div id="installment_details" class="hidden space-y-4">
                    <h3 class="text-lg font-medium">Installment Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="number_of_installments" class="form-label">Number of Installments</label>
                            <input type="number" name="number_of_installments" id="number_of_installments" 
                                   class="form-input" min="2" max="12">
                        </div>

                        <div>
                            <label for="installment_frequency" class="form-label">Payment Frequency</label>
                            <select name="installment_frequency" id="installment_frequency" class="form-input">
                                <option value="weekly">Weekly</option>
                                <option value="biweekly">Bi-weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>

                        <div>
                            <label for="first_payment_date" class="form-label">First Payment Date</label>
                            <input type="date" name="first_payment_date" id="first_payment_date" class="form-input">
                        </div>

                        <div>
                            <label for="installment_amount" class="form-label">Amount per Installment</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                                <input type="number" name="installment_amount" id="installment_amount" 
                                       class="form-input pl-7" step="0.01" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium">Additional Information</h3>
                <div class="space-y-4">
                    <div>
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" rows="3" class="form-input"></textarea>
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="send_receipt" id="send_receipt" class="form-checkbox" checked>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="send_receipt" class="font-medium text-gray-700">Send Receipt</label>
                            <p class="text-gray-500">Send a receipt to the member's email address</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="send_reminders" id="send_reminders" class="form-checkbox">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="send_reminders" class="font-medium text-gray-700">Send Payment Reminders</label>
                            <p class="text-gray-500">Send reminders for upcoming installment payments</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" class="btn btn-secondary" onclick="clearForm()">Clear Form</button>
                <button type="submit" class="btn btn-primary">Record Harvest Offering</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Handle payment status changes
    document.getElementById('payment_status').addEventListener('change', function() {
        const paymentDetails = document.getElementById('payment_details');
        paymentDetails.classList.toggle('hidden', this.value === 'pledge');
    });

    // Handle payment method selection
    document.getElementById('payment_method').addEventListener('change', function() {
        document.getElementById('check_details').classList.toggle('hidden', this.value !== 'check');
        document.getElementById('transaction_details').classList.toggle('hidden', 
            !['credit_card', 'debit_card', 'bank_transfer', 'online'].includes(this.value));
    });

    // Handle installment setup
    document.getElementById('setup_installment').addEventListener('change', function() {
        document.getElementById('installment_details').classList.toggle('hidden', !this.checked);
    });

    // Calculate installment amount
    function calculateInstallment() {
        const pledgeAmount = parseFloat(document.getElementById('pledge_amount').value || 0);
        const numberOfInstallments = parseInt(document.getElementById('number_of_installments').value || 0);

        if (pledgeAmount && numberOfInstallments) {
            const installmentAmount = pledgeAmount / numberOfInstallments;
            document.getElementById('installment_amount').value = installmentAmount.toFixed(2);
        }
    }

    document.getElementById('pledge_amount').addEventListener('input', calculateInstallment);
    document.getElementById('number_of_installments').addEventListener('input', calculateInstallment);

    // Update payment amount when pledge amount changes
    document.getElementById('pledge_amount').addEventListener('input', function() {
        const paymentStatus = document.getElementById('payment_status').value;
        if (paymentStatus === 'full') {
            document.getElementById('payment_amount').value = this.value;
        }
    });

    // Handle payment status change
    document.getElementById('payment_status').addEventListener('change', function() {
        const pledgeAmount = document.getElementById('pledge_amount').value;
        if (this.value === 'full' && pledgeAmount) {
            document.getElementById('payment_amount').value = pledgeAmount;
        } else if (this.value === 'pledge') {
            document.getElementById('payment_amount').value = '0';
        }
    });

    // Clear form
    function clearForm() {
        if (confirm('Are you sure you want to clear the form?')) {
            document.querySelector('form').reset();
            document.getElementById('installment_details').classList.add('hidden');
            document.getElementById('check_details').classList.add('hidden');
            document.getElementById('transaction_details').classList.add('hidden');
            document.getElementById('payment_details').classList.remove('hidden');
            document.getElementById('installment_amount').value = '';
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowmin\resources\views\finance\harvest\create.blade.php ENDPATH**/ ?>