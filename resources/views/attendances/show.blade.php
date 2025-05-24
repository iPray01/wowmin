@extends('layouts.app')

@section('title', 'View Attendance')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">View Attendance</h1>
        <div class="space-x-2">
            <a href="{{ route('attendance.edit', $attendance) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-4 rounded">
                Edit Attendance
            </a>
            <a href="{{ route('attendance.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                Back to List
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Service Information -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Service Information</h2>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Service Name</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $attendance->service->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Service Date</dt>
                        <dd class="mt-1 text-gray-900">{{ $attendance->service->service_date->format('F d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Service Type</dt>
                        <dd class="mt-1 text-gray-900">{{ $attendance->service->service_type }}</dd>
                    </div>
                    @if($attendance->service->location)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Location</dt>
                        <dd class="mt-1 text-gray-900">{{ $attendance->service->location }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Member Information -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Member Information</h2>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Member Name</dt>
                        <dd class="mt-1">
                            <a href="{{ route('members.show', $attendance->member) }}" 
                               class="text-lg font-semibold text-indigo-600 hover:text-indigo-800">
                                {{ $attendance->member->full_name }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Membership Status</dt>
                        <dd class="mt-1 text-gray-900">{{ ucfirst($attendance->member->membership_status) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Member ID</dt>
                        <dd class="mt-1 text-gray-900">{{ $attendance->member->member_id }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Check-in Details -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Check-in Details</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Check-in Time</dt>
                    <dd class="mt-1 text-gray-900">{{ $attendance->check_in_time->format('F d, Y g:i A') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Check-in Method</dt>
                    <dd class="mt-1 text-gray-900">{{ ucfirst(str_replace('_', ' ', $attendance->check_in_method)) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Checked in by</dt>
                    <dd class="mt-1 text-gray-900">{{ $attendance->check_in_by }}</dd>
                </div>
                @if($attendance->check_out_time)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Check-out Time</dt>
                    <dd class="mt-1 text-gray-900">{{ $attendance->check_out_time->format('F d, Y g:i A') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Duration</dt>
                    <dd class="mt-1 text-gray-900">{{ $attendance->duration_in_minutes }} minutes</dd>
                </div>
                @endif
            </dl>
        </div>

        <!-- Notes -->
        @if($attendance->notes)
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Notes</h2>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-700 whitespace-pre-wrap">{{ $attendance->notes }}</p>
            </div>
        </div>
        @endif

        <!-- Delete Button -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <form action="{{ route('attendance.destroy', $attendance) }}" method="POST" class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this attendance record? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Delete Record
                </button>
            </form>
        </div>
    </div>
</div>
@endsection 