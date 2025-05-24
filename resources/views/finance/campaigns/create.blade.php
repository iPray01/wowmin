@extends('layouts.app')

@section('title', 'Create Campaign')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-metal-gold">Create New Campaign</h1>
            <p class="mt-2 text-sm text-gray-600">Launch a new fundraising campaign for your church</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
            @if ($errors->any())
                <div class="mb-6 bg-crimson-50 border border-crimson-200 text-crimson-600 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Please fix the following errors:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('finance.campaigns.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Campaign Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Campaign Name <span class="text-crimson-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        value="{{ old('name') }}" placeholder="e.g., Building Fund 2024">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        Description <span class="text-crimson-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="4" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        placeholder="Describe the purpose and goals of this campaign">{{ old('description') }}</textarea>
                </div>

                <!-- Target Amount -->
                <div>
                    <label for="target_amount" class="block text-sm font-medium text-gray-700">
                        Target Amount (GH₵) <span class="text-crimson-500">*</span>
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">GH₵</span>
                        </div>
                        <input type="number" name="target_amount" id="target_amount" required step="0.01" min="0"
                            class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                            value="{{ old('target_amount') }}" placeholder="0.00">
                    </div>
                </div>

                <!-- Campaign Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">
                            Start Date <span class="text-crimson-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                            value="{{ old('start_date') }}">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">
                            End Date
                        </label>
                        <input type="date" name="end_date" id="end_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                            value="{{ old('end_date') }}">
                        <p class="mt-1 text-sm text-gray-500">Leave empty for ongoing campaigns</p>
                    </div>
                </div>

                <!-- Campaign Status -->
                <div>
                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded"
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                            Activate campaign immediately
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">If unchecked, the campaign will be saved as a draft</p>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a href="{{ route('finance.campaigns.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        Create Campaign
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum date for start_date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('start_date').min = today;

        // Update end_date minimum when start_date changes
        document.getElementById('start_date').addEventListener('change', function() {
            document.getElementById('end_date').min = this.value;
        });

        // Format target amount with 2 decimal places
        document.getElementById('target_amount').addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    });
</script>
@endpush
@endsection 