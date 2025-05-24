@extends('layouts.app')

@section('title', 'Record Tithe')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('finance.index') }}" class="btn btn-secondary">
            ← Back to Finance
        </a>
    </div>

    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Record Tithe</h2>

        <form action="{{ route('finance.tithe.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Member Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium">Member Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="member_id" class="form-label">Select Member</label>
                        <select name="member_id" id="member_id" class="form-input" required>
                            <option value="">Select Member</option>
                            @foreach($members ?? [] as $member)
                                <option value="{{ $member->id }}" data-income="{{ $member->monthly_income ?? 0 }}">
                                    {{ $member->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="month_year" class="form-label">Month and Year</label>
                        <input type="month" name="month_year" id="month_year" class="form-input" required 
                               value="{{ date('Y-m') }}">
                    </div>

                    <div>
                        <label for="income_type" class="form-label">Income Type</label>
                        <select name="income_type" id="income_type" class="form-input" required>
                            <option value="salary">Salary</option>
                            <option value="business">Business Income</option>
                            <option value="investment">Investment Returns</option>
                            <option value="rental">Rental Income</option>
                            <option value="other">Other Income</option>
                        </select>
                    </div>

                    <div>
                        <label for="gross_income" class="form-label">Gross Income</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                            <input type="number" name="gross_income" id="gross_income" class="form-input pl-7" 
                                   step="0.01" required>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Total income before deductions</p>
                    </div>
                </div>
            </div>

            <!-- Tithe Calculation -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium">Tithe Calculation</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tithe_percentage" class="form-label">Tithe Percentage</label>
                        <div class="relative">
                            <input type="number" name="tithe_percentage" id="tithe_percentage" 
                                   class="form-input pr-8" value="10" min="0" max="100" required>
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">%</span>
                        </div>
                    </div>

                    <div>
                        <label for="tithe_amount" class="form-label">Tithe Amount</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                            <input type="number" name="tithe_amount" id="tithe_amount" class="form-input pl-7" 
                                   step="0.01" required readonly>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Automatically calculated based on income</p>
                    </div>

                    <div>
                        <label for="additional_offering" class="form-label">Additional Offering (Optional)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                            <input type="number" name="additional_offering" id="additional_offering" 
                                   class="form-input pl-7" step="0.01" value="0">
                        </div>
                    </div>

                    <div>
                        <label for="total_amount" class="form-label">Total Amount</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                            <input type="number" name="total_amount" id="total_amount" class="form-input pl-7" 
                                   step="0.01" required readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium">Payment Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="payment_date" class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" id="payment_date" class="form-input" 
                               required value="{{ date('Y-m-d') }}">
                    </div>

                    <div>
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-input" required>
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
                            <p class="text-gray-500">Send a tithe receipt to the member's email address</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_recurring" id="is_recurring" class="form-checkbox">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_recurring" class="font-medium text-gray-700">Set up Recurring Tithe</label>
                            <p class="text-gray-500">Automatically process tithe payments monthly</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recurring Payment Setup -->
            <div id="recurring_setup" class="space-y-4 hidden">
                <h3 class="text-lg font-medium">Recurring Payment Setup</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_month" class="form-label">Start Month</label>
                        <input type="month" name="start_month" id="start_month" class="form-input" 
                               value="{{ date('Y-m') }}">
                    </div>

                    <div>
                        <label for="end_month" class="form-label">End Month (Optional)</label>
                        <input type="month" name="end_month" id="end_month" class="form-input">
                    </div>

                    <div>
                        <label for="preferred_day" class="form-label">Preferred Payment Day</label>
                        <select name="preferred_day" id="preferred_day" class="form-input">
                            @for($i = 1; $i <= 28; $i++)
                                <option value="{{ $i }}">{{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" class="btn btn-secondary" onclick="clearForm()">Clear Form</button>
                <button type="submit" class="btn btn-primary">Record Tithe</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Calculate tithe amount
    function calculateTithe() {
        const grossIncome = parseFloat(document.getElementById('gross_income').value || 0);
        const percentage = parseFloat(document.getElementById('tithe_percentage').value || 0);
        const additionalOffering = parseFloat(document.getElementById('additional_offering').value || 0);

        const titheAmount = (grossIncome * percentage) / 100;
        const totalAmount = titheAmount + additionalOffering;

        document.getElementById('tithe_amount').value = titheAmount.toFixed(2);
        document.getElementById('total_amount').value = totalAmount.toFixed(2);
    }

    // Add event listeners for calculation
    document.getElementById('gross_income').addEventListener('input', calculateTithe);
    document.getElementById('tithe_percentage').addEventListener('input', calculateTithe);
    document.getElementById('additional_offering').addEventListener('input', calculateTithe);

    // Handle member selection
    document.getElementById('member_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const monthlyIncome = parseFloat(selectedOption.dataset.income || 0);
        if (monthlyIncome > 0) {
            document.getElementById('gross_income').value = monthlyIncome;
            calculateTithe();
        }
    });

    // Handle payment method selection
    document.getElementById('payment_method').addEventListener('change', function() {
        document.getElementById('check_details').classList.toggle('hidden', this.value !== 'check');
        document.getElementById('transaction_details').classList.toggle('hidden', 
            !['credit_card', 'debit_card', 'bank_transfer', 'online'].includes(this.value));
    });

    // Handle recurring tithe toggle
    document.getElementById('is_recurring').addEventListener('change', function() {
        document.getElementById('recurring_setup').classList.toggle('hidden', !this.checked);
    });

    // Clear form
    function clearForm() {
        if (confirm('Are you sure you want to clear the form?')) {
            document.querySelector('form').reset();
            document.getElementById('recurring_setup').classList.add('hidden');
            document.getElementById('check_details').classList.add('hidden');
            document.getElementById('transaction_details').classList.add('hidden');
            calculateTithe();
        }
    }
</script>
@endpush
@endsection
