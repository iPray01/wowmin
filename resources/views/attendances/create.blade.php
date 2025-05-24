@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Record Attendance</h2>
            <a href="{{ route('attendance.index') }}" class="text-blue-600 hover:text-blue-900">
                <i class="fas fa-arrow-left mr-1"></i> Back to List
            </a>
        </div>

        <form action="{{ route('attendance.store') }}" method="POST" class="max-w-4xl">
            @csrf

            <!-- Service Selection -->
            <div class="mb-6">
                <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">Service <span class="text-red-600">*</span></label>
                <select name="service_id" id="service_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select a Service</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }} ({{ $service->service_date->format('M d, Y') }})
                        </option>
                    @endforeach
                </select>
                @error('service_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Member Selection -->
            <div class="mb-6">
                <label for="member_id" class="block text-sm font-medium text-gray-700 mb-1">Member <span class="text-red-600">*</span></label>
                <select name="member_id" id="member_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select a Member</option>
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

            <!-- Check In Time -->
            <div class="mb-6">
                <label for="check_in_time" class="block text-sm font-medium text-gray-700 mb-1">Check In Time <span class="text-red-600">*</span></label>
                <input type="datetime-local" name="check_in_time" id="check_in_time" required
                       value="{{ old('check_in_time', now()->format('Y-m-d\TH:i')) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('check_in_time')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Check Out Time -->
            <div class="mb-6">
                <label for="check_out_time" class="block text-sm font-medium text-gray-700 mb-1">Check Out Time</label>
                <input type="datetime-local" name="check_out_time" id="check_out_time"
                       value="{{ old('check_out_time') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('check_out_time')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Check In Method -->
            <div class="mb-6">
                <label for="check_in_method" class="block text-sm font-medium text-gray-700 mb-1">Check In Method <span class="text-red-600">*</span></label>
                <select name="check_in_method" id="check_in_method" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @foreach($checkInMethods as $value => $label)
                        <option value="{{ $value }}" {{ old('check_in_method') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('check_in_method')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Record Attendance
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Set default check-in time to now when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        
        const defaultDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
        document.getElementById('check_in_time').value = defaultDateTime;
    });

    // Validate that check-out time is after check-in time
    document.querySelector('form').addEventListener('submit', function(e) {
        const checkInTime = new Date(document.getElementById('check_in_time').value);
        const checkOutTime = document.getElementById('check_out_time').value;
        
        if (checkOutTime) {
            const checkOutDateTime = new Date(checkOutTime);
            if (checkOutDateTime <= checkInTime) {
                e.preventDefault();
                alert('Check-out time must be after check-in time');
            }
        }
    });
</script>
@endpush
@endsection 