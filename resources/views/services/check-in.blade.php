@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Service Check-in</h2>
                <p class="mt-1 text-sm text-gray-600">{{ $service->name }} - {{ $service->service_date->format('F d, Y h:i A') }}</p>
            </div>
            <a href="{{ route('services.show', $service) }}" class="text-blue-600 hover:text-blue-900">
                <i class="fas fa-arrow-left mr-1"></i> Back to Service
            </a>
        </div>

        <!-- Check-in Form -->
        <form action="{{ route('services.process-check-in', $service) }}" method="POST" class="max-w-4xl">
            @csrf

            <!-- Member Selection -->
            <div class="mb-6">
                <label for="member_id" class="block text-sm font-medium text-gray-700 mb-1">Member <span class="text-red-600">*</span></label>
                <select name="member_id" id="member_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select a Member</option>
                    @foreach($members as $member)
                        @if(!in_array($member->id, $alreadyCheckedIn))
                            <option value="{{ $member->id }}">
                                {{ $member->last_name }}, {{ $member->first_name }}
                            </option>
                        @endif
                    @endforeach
                </select>
                @error('member_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Check-in Method -->
            <div class="mb-6">
                <label for="check_in_method" class="block text-sm font-medium text-gray-700 mb-1">Check-in Method <span class="text-red-600">*</span></label>
                <select name="check_in_method" id="check_in_method" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="manual">Manual Entry</option>
                    <option value="qr_code">QR Code</option>
                    <option value="card_scan">Card Scan</option>
                    <option value="face_recognition">Face Recognition</option>
                </select>
                @error('check_in_method')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Any additional notes..."></textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-check-circle mr-2"></i>
                    Check In Member
                </button>
            </div>
        </form>

        <!-- Already Checked In Members -->
        <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Checked In Members</h3>
            @if(count($alreadyCheckedIn) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in Time</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($service->attendees as $attendee)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($attendee->profile_photo)
                                                <img class="h-8 w-8 rounded-full mr-2" src="{{ asset('storage/' . $attendee->profile_photo) }}" alt="">
                                            @endif
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $attendee->full_name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ Carbon\Carbon::parse($attendee->pivot->check_in_time)->format('h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $attendee->pivot->check_in_method }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if(!$attendee->pivot->check_out_time)
                                            <form action="{{ route('services.process-check-out', ['service' => $service, 'member' => $attendee]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-blue-600 hover:text-blue-900">
                                                    Check Out
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-500">Checked Out</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No members checked in yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection 