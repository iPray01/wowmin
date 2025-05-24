@extends('layouts.app')

@section('title', 'Edit Campaign - ' . $campaign->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Campaign</h1>
        <div class="space-x-2">
            <a href="{{ route('finance.campaigns.show', $campaign) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                Back to Campaign
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('finance.campaigns.update', $campaign) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Campaign Name *</label>
                    <input type="text" name="name" id="name" required
                        class="form-input w-full rounded-md border-gray-300"
                        value="{{ old('name', $campaign->name) }}"
                        placeholder="Enter campaign name">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <textarea name="description" id="description" rows="3" required
                        class="form-textarea w-full rounded-md border-gray-300"
                        placeholder="Enter campaign description">{{ old('description', $campaign->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Target Amount -->
                <div>
                    <label for="target_amount" class="block text-sm font-medium text-gray-700 mb-1">Target Amount (GH₵) *</label>
                    <input type="number" name="target_amount" id="target_amount" required
                        class="form-input w-full rounded-md border-gray-300"
                        min="0" step="0.01" value="{{ old('target_amount', $campaign->target_amount) }}">
                    @error('target_amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                    <input type="date" name="start_date" id="start_date" required
                        class="form-input w-full rounded-md border-gray-300"
                        value="{{ old('start_date', $campaign->start_date->format('Y-m-d')) }}">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" id="end_date"
                        class="form-input w-full rounded-md border-gray-300"
                        value="{{ old('end_date', $campaign->end_date ? $campaign->end_date->format('Y-m-d') : '') }}">
                    <p class="mt-1 text-xs text-gray-500">Leave blank for ongoing campaigns</p>
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campaign Status -->
                <div class="md:col-span-2">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                class="form-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded"
                                {{ old('is_active', $campaign->is_active) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_active" class="font-medium text-gray-700">Make campaign active</label>
                            <p class="text-gray-500">Inactive campaigns are not visible to members</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('finance.campaigns.show', $campaign) }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                    Cancel
                </a>
                <button type="submit"
                    class="bg-teal hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded">
                    Update Campaign
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 