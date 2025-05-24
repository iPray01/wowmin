@extends('layouts.app')

@section('title', 'Edit Pledge')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Edit Pledge</h1>
        <a href="{{ route('finance.pledges.show', $pledge) }}" 
           class="inline-flex items-center px-4 py-2 border border-metal-gold rounded-md shadow-sm text-sm font-medium text-metal-gold bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-metal-gold">
            Back to Details
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Validation Error!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <form action="{{ route('finance.pledges.update', $pledge) }}" method="POST" class="p-6" id="pledgeEditForm">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Member Selection -->
                <div>
                    <label for="member_id" class="block text-sm font-medium text-gray-700">Member</label>
                    <select id="member_id" name="member_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-teal focus:border-teal sm:text-sm">
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ $pledge->member_id == $member->id ? 'selected' : '' }}>
                                {{ $member->full_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('member_id')
                        <p class="mt-1 text-sm text-crimson">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campaign Selection -->
                <div>
                    <label for="campaign_id" class="block text-sm font-medium text-gray-700">Campaign</label>
                    <select id="campaign_id" name="campaign_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-teal focus:border-teal sm:text-sm">
                        @foreach($campaigns as $campaign)
                            <option value="{{ $campaign->id }}" {{ $pledge->campaign_id == $campaign->id ? 'selected' : '' }}>
                                {{ $campaign->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('campaign_id')
                        <p class="mt-1 text-sm text-crimson">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pledge Date -->
                <div>
                    <label for="pledge_date" class="block text-sm font-medium text-gray-700">Pledge Date</label>
                    <input type="date" name="pledge_date" id="pledge_date" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-teal focus:border-teal sm:text-sm"
                        value="{{ old('pledge_date', $pledge->pledge_date->format('Y-m-d')) }}">
                    @error('pledge_date')
                        <p class="mt-1 text-sm text-crimson">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700">Total Amount</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">GH₵</span>
                        </div>
                        <input type="number" name="amount" id="amount" step="0.01" required
                            class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-teal focus:border-teal sm:text-sm"
                            value="{{ old('amount', $pledge->amount) }}">
                    </div>
                    @error('amount')
                        <p class="mt-1 text-sm text-crimson">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Schedule -->
                <div>
                    <label for="payment_schedule" class="block text-sm font-medium text-gray-700">Payment Schedule</label>
                    <select id="payment_schedule" name="payment_schedule" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-teal focus:border-teal sm:text-sm">
                        <option value="weekly" {{ $pledge->payment_schedule === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ $pledge->payment_schedule === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="quarterly" {{ $pledge->payment_schedule === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                        <option value="annually" {{ $pledge->payment_schedule === 'annually' ? 'selected' : '' }}>Annually</option>
                    </select>
                    @error('payment_schedule')
                        <p class="mt-1 text-sm text-crimson">{{ $message }}</p>
                    @enderror
                </div>

                Start Date
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" id="start_date" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-teal focus:border-teal sm:text-sm"
                        value="{{ old('start_date', $pledge->start_date->format('Y-m-d')) }}">
                    @error('start_date')
                        <p class="mt-1 text-sm text-crimson">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" id="end_date" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-teal focus:border-teal sm:text-sm"
                        value="{{ old('end_date', $pledge->end_date ? $pledge->end_date->format('Y-m-d') : '') }}">
                    @error('end_date')
                        <p class="mt-1 text-sm text-crimson">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-teal focus:border-teal sm:text-sm">
                        <option value="active" {{ $pledge->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ $pledge->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $pledge->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-crimson">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-teal focus:border-teal sm:text-sm">{{ old('notes', $pledge->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-crimson">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal hover:bg-teal-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal">
                        Update Pledge
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('pledgeEditForm');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    // Add form submission handler for debugging
    form.addEventListener('submit', function(e) {
        console.log('Form submission attempted');
        // Don't prevent default - let the form submit normally
    });

    // Ensure end date is after start date
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
        if (endDateInput.value && endDateInput.value < this.value) {
            endDateInput.value = this.value;
        }
    });

    // Set initial min value for end date
    endDateInput.min = startDateInput.value;
});
</script>
@endpush

@endsection 