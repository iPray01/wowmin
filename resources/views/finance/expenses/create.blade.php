@extends('layouts.app')

@section('title', 'Record Expense')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Create New Expense</h1>
        <a href="{{ route('finance.expenses.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('finance.expenses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <select name="category_id" id="category_id" required class="form-select w-full rounded-md border-gray-300">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department -->
                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Department *</label>
                    <select name="department_id" id="department_id" required class="form-select w-full rounded-md border-gray-300">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (₵) *</label>
                    <input type="number" name="amount" id="amount" required
                        class="form-input w-full rounded-md border-gray-300"
                        min="0.01" step="0.01" value="{{ old('amount') }}">
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Expense Date -->
                <div>
                    <label for="expense_date" class="block text-sm font-medium text-gray-700 mb-1">Expense Date *</label>
                    <input type="date" name="expense_date" id="expense_date" required
                        class="form-input w-full rounded-md border-gray-300"
                        value="{{ old('expense_date', date('Y-m-d')) }}">
                    @error('expense_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <input type="text" name="description" id="description" required
                        class="form-input w-full rounded-md border-gray-300"
                        value="{{ old('description') }}"
                        placeholder="Brief description of the expense">
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Vendor -->
                <div>
                    <label for="vendor" class="block text-sm font-medium text-gray-700 mb-1">Vendor</label>
                    <input type="text" name="vendor" id="vendor"
                        class="form-input w-full rounded-md border-gray-300"
                        value="{{ old('vendor') }}"
                        placeholder="Vendor or supplier name">
                    @error('vendor')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method *</label>
                    <select name="payment_method" id="payment_method" required class="form-select w-full rounded-md border-gray-300">
                        <option value="">Select Payment Method</option>
                        @foreach($paymentMethods as $key => $value)
                            <option value="{{ $key }}" {{ old('payment_method') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reference Number -->
                <div>
                    <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-1">Reference Number</label>
                    <input type="text" name="reference_number" id="reference_number"
                        class="form-input w-full rounded-md border-gray-300"
                        value="{{ old('reference_number') }}"
                        placeholder="Check number, transaction ID, etc.">
                    @error('reference_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Receipt Upload -->
                <div>
                    <label for="receipt" class="block text-sm font-medium text-gray-700 mb-1">Receipt</label>
                    <input type="file" name="receipt" id="receipt"
                        class="form-input w-full rounded-md border-gray-300"
                        accept=".jpg,.jpeg,.png,.pdf">
                    <p class="mt-1 text-sm text-gray-500">Upload receipt (JPEG, PNG, or PDF, max 2MB)</p>
                    @error('receipt')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="form-textarea w-full rounded-md border-gray-300"
                        placeholder="Additional notes or details">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Recurring Expense -->
                <div class="md:col-span-2">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_recurring" id="is_recurring"
                                class="form-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded"
                                {{ old('is_recurring') ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_recurring" class="font-medium text-gray-700">This is a recurring expense</label>
                        </div>
                    </div>
                </div>

                <!-- Recurring Options (hidden by default) -->
                <div id="recurring_options" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 {{ old('is_recurring') ? '' : 'hidden' }}">
                    <div>
                        <label for="recurring_frequency" class="block text-sm font-medium text-gray-700 mb-1">Frequency</label>
                        <select name="recurring_frequency" id="recurring_frequency" class="form-select w-full rounded-md border-gray-300">
                            <option value="weekly" {{ old('recurring_frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="biweekly" {{ old('recurring_frequency') == 'biweekly' ? 'selected' : '' }}>Bi-weekly</option>
                            <option value="monthly" {{ old('recurring_frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="quarterly" {{ old('recurring_frequency') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                            <option value="annually" {{ old('recurring_frequency') == 'annually' ? 'selected' : '' }}>Annually</option>
                        </select>
                    </div>

                    <div>
                        <label for="recurring_end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="recurring_end_date" id="recurring_end_date"
                            class="form-input w-full rounded-md border-gray-300"
                            value="{{ old('recurring_end_date') }}">
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" onclick="window.history.back()"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                    Create Expense
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('is_recurring').addEventListener('change', function() {
        const recurringOptions = document.getElementById('recurring_options');
        recurringOptions.classList.toggle('hidden', !this.checked);
        
        const frequencyInput = document.getElementById('recurring_frequency');
        const endDateInput = document.getElementById('recurring_end_date');
        
        if (this.checked) {
            frequencyInput.setAttribute('required', 'required');
            endDateInput.setAttribute('required', 'required');
        } else {
            frequencyInput.removeAttribute('required');
            endDateInput.removeAttribute('required');
        }
    });
</script>
@endpush
@endsection
