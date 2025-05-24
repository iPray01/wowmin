@extends('layouts.app')

@section('title', 'Edit Harvest Record')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Harvest Record</h1>
        <a href="{{ route('finance.harvest.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('finance.harvest.update', $harvest) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Harvest Name</label>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name', $harvest->name) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="harvest_date" class="block text-sm font-medium text-gray-700">Harvest Date</label>
                        <input type="date" name="harvest_date" id="harvest_date" 
                               value="{{ old('harvest_date', $harvest->harvest_date->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                        @error('harvest_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                        <select name="type" id="type" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                            <option value="general" {{ old('type', $harvest->type) == 'general' ? 'selected' : '' }}>General Harvest</option>
                            <option value="thanksgiving" {{ old('type', $harvest->type) == 'thanksgiving' ? 'selected' : '' }}>Thanksgiving</option>
                            <option value="special" {{ old('type', $harvest->type) == 'special' ? 'selected' : '' }}>Special Harvest</option>
                            <option value="project" {{ old('type', $harvest->type) == 'project' ? 'selected' : '' }}>Project Harvest</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                            <option value="upcoming" {{ old('status', $harvest->status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="ongoing" {{ old('status', $harvest->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ old('status', $harvest->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Financial Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="target_amount" class="block text-sm font-medium text-gray-700">Target Amount</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">GH₵</span>
                            </div>
                            <input type="number" name="target_amount" id="target_amount" 
                                   value="{{ old('target_amount', $harvest->target_amount) }}"
                                   class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   step="0.01" required>
                        </div>
                        @error('target_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="amount_raised" class="block text-sm font-medium text-gray-700">Amount Raised</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">GH₵</span>
                            </div>
                            <input type="number" name="amount_raised" id="amount_raised" 
                                   value="{{ old('amount_raised', $harvest->amount_raised) }}"
                                   class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   step="0.01" required>
                        </div>
                        @error('amount_raised')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Description</h2>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $harvest->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Additional Settings -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Additional Settings</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="allow_anonymous" id="allow_anonymous" 
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                   {{ old('allow_anonymous', $harvest->allow_anonymous) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="allow_anonymous" class="font-medium text-gray-700">Allow Anonymous Contributions</label>
                            <p class="text-gray-500">Allow members to make anonymous contributions to this harvest</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="send_reminders" id="send_reminders" 
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                   {{ old('send_reminders', $harvest->send_reminders) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="send_reminders" class="font-medium text-gray-700">Send Reminders</label>
                            <p class="text-gray-500">Send reminder notifications to members about this harvest</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="show_progress" id="show_progress" 
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                   {{ old('show_progress', $harvest->show_progress) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="show_progress" class="font-medium text-gray-700">Show Progress</label>
                            <p class="text-gray-500">Display progress towards the target amount publicly</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="window.history.back()" class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </button>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Harvest Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 