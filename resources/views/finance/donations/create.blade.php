@extends('layouts.app')

@section('title', 'Record New Donation')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-5" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('finance.donations.index') }}" class="text-gray-700 hover:text-blue-600">
                        Donations
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-500">Record New Donation</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Main Content -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Record New Donation</h2>

            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('finance.donations.store') }}" method="POST" id="donationForm" class="space-y-8">
                @csrf

                <!-- Donor Selection -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Donor Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="member_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Select Member
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="member_id" id="member_id" class="select2 form-select block w-full" required>
                                <option value="">Select a member</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->last_name }}, {{ $member->first_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('member_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="is_anonymous" class="flex items-center">
                                <input type="checkbox" name="is_anonymous" id="is_anonymous" class="form-checkbox rounded text-blue-600" value="1" {{ old('is_anonymous') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Make this donation anonymous</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Donation Details -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Donation Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Amount
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="amount" id="amount" step="0.01" min="0.01" 
                                    class="form-input block w-full pl-7" required
                                    value="{{ old('amount') }}"
                                    placeholder="0.00">
                            </div>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="donation_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Donation Type
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="donation_type" id="donation_type" class="form-select block w-full" required>
                                <option value="">Select donation type</option>
                                <option value="tithe" {{ old('donation_type') == 'tithe' ? 'selected' : '' }}>Tithe</option>
                                <option value="offering" {{ old('donation_type') == 'offering' ? 'selected' : '' }}>Offering</option>
                                <option value="special_offering" {{ old('donation_type') == 'special_offering' ? 'selected' : '' }}>Special Offering</option>
                                <option value="building_fund" {{ old('donation_type') == 'building_fund' ? 'selected' : '' }}>Building Fund</option>
                                <option value="missions" {{ old('donation_type') == 'missions' ? 'selected' : '' }}>Missions</option>
                                <option value="other" {{ old('donation_type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('donation_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="donation_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Donation Date
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="donation_date" id="donation_date" 
                                class="form-input block w-full" required
                                value="{{ old('donation_date', date('Y-m-d')) }}">
                            @error('donation_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="campaign_id" class="block text-sm font-medium text-gray-700 mb-2">Campaign</label>
                            <select name="campaign_id" id="campaign_id" class="select2 form-select block w-full">
                                <option value="">Select a campaign (optional)</option>
                                @foreach($campaigns as $campaign)
                                    <option value="{{ $campaign->id }}" {{ old('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                        {{ $campaign->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('campaign_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Method
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="payment_method" id="payment_method" class="form-select block w-full" required>
                                @foreach($paymentMethods as $value => $label)
                                    <option value="{{ $value }}" {{ old('payment_method') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="transaction_details" class="hidden">
                            <label for="transaction_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Transaction ID / Reference Number
                            </label>
                            <input type="text" name="transaction_id" id="transaction_id" 
                                class="form-input block w-full"
                                value="{{ old('transaction_id') }}">
                            @error('transaction_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Recurring Options -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Recurring Donation</h3>
                        <label for="is_recurring" class="flex items-center">
                            <input type="checkbox" name="is_recurring" id="is_recurring" 
                                class="form-checkbox rounded text-blue-600" value="1" 
                                {{ old('is_recurring') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">This is a recurring donation</span>
                        </label>
                    </div>

                    <div id="recurring_details" class="grid grid-cols-1 md:grid-cols-3 gap-6 {{ old('is_recurring') ? '' : 'hidden' }}">
                        <div>
                            <label for="recurring_frequency" class="block text-sm font-medium text-gray-700 mb-2">
                                Frequency
                            </label>
                            <select name="recurring_frequency" id="recurring_frequency" class="form-select block w-full">
                                <option value="weekly" {{ old('recurring_frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="biweekly" {{ old('recurring_frequency') == 'biweekly' ? 'selected' : '' }}>Bi-weekly</option>
                                <option value="monthly" {{ old('recurring_frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="quarterly" {{ old('recurring_frequency') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                <option value="annually" {{ old('recurring_frequency') == 'annually' ? 'selected' : '' }}>Annually</option>
                            </select>
                        </div>

                        <div>
                            <label for="recurring_start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Start Date
                            </label>
                            <input type="date" name="recurring_start_date" id="recurring_start_date" 
                                class="form-input block w-full"
                                value="{{ old('recurring_start_date', date('Y-m-d')) }}">
                        </div>

                        <div>
                            <label for="recurring_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                End Date (Optional)
                            </label>
                            <input type="date" name="recurring_end_date" id="recurring_end_date" 
                                class="form-input block w-full"
                                value="{{ old('recurring_end_date') }}">
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" id="notes" rows="3" 
                                class="form-textarea block w-full"
                                placeholder="Add any additional notes about this donation">{{ old('notes') }}</textarea>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="send_receipt" id="send_receipt" 
                                class="form-checkbox rounded text-blue-600" value="1"
                                {{ old('send_receipt') ? 'checked' : '' }}>
                            <label for="send_receipt" class="ml-2 block text-sm text-gray-700">
                                Send receipt to donor's email
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="resetForm()" class="btn btn-secondary">
                        Reset Form
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Record Donation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Initialize Select2
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'classic',
            width: '100%'
        });

        // Format amount input
        $('#amount').on('input', function() {
            let value = this.value;
            if(value !== '') {
                value = parseFloat(value).toFixed(2);
                if(!isNaN(value)) {
                    this.value = value;
                }
            }
        });

        // Handle payment method change
        $('#payment_method').on('change', function() {
            const needsTransaction = ['credit_card', 'debit_card', 'bank_transfer', 'online_payment'].includes(this.value);
            $('#transaction_details').toggleClass('hidden', !needsTransaction);
            if(needsTransaction) {
                $('#transaction_id').attr('required', true);
            } else {
                $('#transaction_id').removeAttr('required');
            }
        });

        // Handle recurring donation toggle
        $('#is_recurring').on('change', function() {
            $('#recurring_details').toggleClass('hidden', !this.checked);
            if(this.checked) {
                $('#recurring_frequency, #recurring_start_date').attr('required', true);
            } else {
                $('#recurring_frequency, #recurring_start_date').removeAttr('required');
            }
        });

        // Campaign details tooltip
        $('#campaign_id').on('change', function() {
            const campaignId = $(this).val();
            if(campaignId) {
                // You can add AJAX call here to fetch campaign details
                // and show them in a tooltip or info box
            }
        });
    });

    // Reset form with confirmation
    function resetForm() {
        if(confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
            $('#donationForm')[0].reset();
            $('.select2').val('').trigger('change');
            $('#recurring_details').addClass('hidden');
            $('#transaction_details').addClass('hidden');
        }
    }

    // Form validation before submit
    document.getElementById('donationForm').addEventListener('submit', function(e) {
        const amount = parseFloat($('#amount').val());
        if(isNaN(amount) || amount <= 0) {
            e.preventDefault();
            alert('Please enter a valid donation amount');
            return false;
        }

        if($('#is_recurring').is(':checked')) {
            const startDate = new Date($('#recurring_start_date').val());
            const endDate = new Date($('#recurring_end_date').val());
            if(endDate && startDate > endDate) {
                e.preventDefault();
                alert('Recurring end date must be after start date');
                return false;
            }
        }
    });
</script>
@endsection
