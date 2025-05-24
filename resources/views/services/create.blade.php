@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Create New Service</h2>
            <a href="{{ route('services.index') }}" class="text-blue-600 hover:text-blue-900">
                <i class="fas fa-arrow-left mr-1"></i> Back to List
            </a>
        </div>

        <form action="{{ route('services.store') }}" method="POST" class="max-w-4xl">
            @csrf

            <!-- Service Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Service Name <span class="text-red-600">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Enter service name">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Service Type -->
            <div class="mb-6">
                <label for="service_type" class="block text-sm font-medium text-gray-700 mb-1">Service Type <span class="text-red-600">*</span></label>
                <select name="service_type" id="service_type" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select a type</option>
                    @foreach($serviceTypes as $type => $label)
                        <option value="{{ $type }}" {{ old('service_type') == $type ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('service_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Service Date and Time -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="service_date" class="block text-sm font-medium text-gray-700 mb-1">Service Date <span class="text-red-600">*</span></label>
                    <input type="date" name="service_date" id="service_date" value="{{ old('service_date') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('service_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="service_time" class="block text-sm font-medium text-gray-700 mb-1">Service Time <span class="text-red-600">*</span></label>
                    <input type="time" name="service_time" id="service_time" value="{{ old('service_time') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('service_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Location -->
            <div class="mb-6">
                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                <input type="text" name="location" id="location" value="{{ old('location') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Enter service location">
                @error('location')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Enter service description">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Expected Attendance -->
            <div class="mb-6">
                <label for="expected_attendance" class="block text-sm font-medium text-gray-700 mb-1">Expected Attendance</label>
                <input type="number" name="expected_attendance" id="expected_attendance" value="{{ old('expected_attendance') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       min="0" placeholder="Enter expected attendance">
                @error('expected_attendance')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Recurring Service -->
            <div class="mb-6">
                <div class="flex items-center">
                    <input type="checkbox" name="is_recurring" id="is_recurring" value="1" {{ old('is_recurring') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           onchange="toggleRecurringFields()">
                    <label for="is_recurring" class="ml-2 block text-sm font-medium text-gray-700">This is a recurring service</label>
                </div>
            </div>

            <!-- Recurring Service Fields (initially hidden) -->
            <div id="recurring-fields" class="mb-6 {{ old('is_recurring') ? '' : 'hidden' }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="recurrence_pattern" class="block text-sm font-medium text-gray-700 mb-1">Recurrence Pattern</label>
                        <select name="recurrence_pattern" id="recurrence_pattern"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select a pattern</option>
                            @foreach($recurrencePatterns as $pattern => $label)
                                <option value="{{ $pattern }}" {{ old('recurrence_pattern') == $pattern ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('recurrence_pattern')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="recurrence_count" class="block text-sm font-medium text-gray-700 mb-1">Number of Occurrences</label>
                        <input type="number" name="recurrence_count" id="recurrence_count" value="{{ old('recurrence_count') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               min="1" max="52" placeholder="Enter number of occurrences">
                        @error('recurrence_count')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create Service
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function toggleRecurringFields() {
        const isRecurring = document.getElementById('is_recurring').checked;
        const recurringFields = document.getElementById('recurring-fields');
        const recurrencePattern = document.getElementById('recurrence_pattern');
        const recurrenceCount = document.getElementById('recurrence_count');

        if (isRecurring) {
            recurringFields.classList.remove('hidden');
            recurrencePattern.setAttribute('required', 'required');
            recurrenceCount.setAttribute('required', 'required');
        } else {
            recurringFields.classList.add('hidden');
            recurrencePattern.removeAttribute('required');
            recurrenceCount.removeAttribute('required');
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleRecurringFields();
    });
</script>
@endpush

@endsection 