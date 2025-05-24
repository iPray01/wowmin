@extends('layouts.app')

@section('title', $record->event_name ?? 'Attendance Details')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
            ← Back to Attendance Records
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="md:col-span-2 space-y-6">
            <!-- Event Details -->
            <div class="card">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold">{{ $record->event_name ?? ucfirst(str_replace('_', ' ', $record->event_type)) }}</h2>
                        <p class="text-sm" style="color: var(--color-burgundy)">{{ $record->event_date->format('F d, Y') }} at {{ $record->event_time->format('h:i A') }}</p>
                    </div>
                    <a href="{{ route('attendance.edit', $record) }}" class="btn btn-secondary">Edit Record</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium" style="color: var(--color-teal)">Event Information</h3>
                        <dl class="mt-2 space-y-2">
                            <div>
                                <dt class="text-sm text-gray-500">Event Type</dt>
                                <dd class="text-sm">{{ ucfirst(str_replace('_', ' ', $record->event_type)) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Ministry</dt>
                                <dd class="text-sm">{{ ucfirst($record->ministry ?? 'General') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Location</dt>
                                <dd class="text-sm">{{ $record->location }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium" style="color: var(--color-teal)">Attendance Summary</h3>
                        <dl class="mt-2 space-y-2">
                            <div>
                                <dt class="text-sm text-gray-500">Total Present</dt>
                                <dd class="text-sm font-medium" style="color: var(--color-teal)">{{ $record->total_attendance }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">New Visitors</dt>
                                <dd class="text-sm font-medium" style="color: var(--color-burgundy)">{{ $record->new_visitors }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Families Present</dt>
                                <dd class="text-sm font-medium" style="color: var(--color-crimson)">{{ $record->families_count }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Attendance List -->
            <div class="card">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium">Attendance List</h3>
                    <div class="flex space-x-2">
                        <button onclick="window.print()" class="btn btn-secondary">Print List</button>
                        <a href="{{ route('attendance.export', $record) }}" class="btn btn-secondary">Export</a>
                    </div>
                </div>

                <!-- Filters -->
                <div class="mb-4">
                    <div class="flex space-x-4">
                        <button class="text-sm" style="color: var(--color-teal)" onclick="filterAttendance('all')">All</button>
                        <button class="text-sm" style="color: var(--color-burgundy)" onclick="filterAttendance('visitors')">Visitors</button>
                        <button class="text-sm" style="color: var(--color-crimson)" onclick="filterAttendance('members')">Members</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Family</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-burgundy)">Check-in Time</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($record->attendees as $attendee)
                                <tr class="attendance-row" data-type="{{ $attendee->is_visitor ? 'visitor' : 'member' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($attendee->photo)
                                                <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/' . $attendee->photo) }}" alt="{{ $attendee->name }}">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <span class="text-gray-500 text-xs">{{ substr($attendee->name, 0, 2) }}</span>
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $attendee->name }}</div>
                                                @if($attendee->phone)
                                                    <div class="text-sm text-gray-500">{{ $attendee->phone }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $attendee->is_visitor ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $attendee->is_visitor ? 'Visitor' : 'Member' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($attendee->family)
                                            <a href="{{ route('families.show', $attendee->family) }}" class="text-sm hover:underline" style="color: var(--color-teal)">
                                                {{ $attendee->family->name }}
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $attendee->check_in_time->format('h:i A') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                        No attendance records found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="card">
                <h3 class="text-lg font-medium mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div>
                        <span class="text-sm text-gray-500">Attendance Rate</span>
                        <div class="flex items-end space-x-2">
                            <span class="text-2xl font-bold" style="color: var(--color-teal)">{{ $record->attendance_rate }}%</span>
                            <span class="text-sm text-gray-500">vs average</span>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">First-Time Visitors</span>
                        <div class="flex items-end space-x-2">
                            <span class="text-2xl font-bold" style="color: var(--color-burgundy)">{{ $record->first_time_visitors }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Return Visitors</span>
                        <div class="flex items-end space-x-2">
                            <span class="text-2xl font-bold" style="color: var(--color-crimson)">{{ $record->return_visitors }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weather & Conditions -->
            <div class="card">
                <h3 class="text-lg font-medium mb-4">Conditions</h3>
                <div class="space-y-2">
                    <div>
                        <span class="text-sm text-gray-500">Weather</span>
                        <p class="text-sm">{{ ucfirst($record->weather) }}</p>
                    </div>
                    @if($record->special_occasion)
                        <div>
                            <span class="text-sm text-gray-500">Special Occasion</span>
                            <p class="text-sm">{{ $record->special_occasion }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Notes -->
            <div class="card">
                <h3 class="text-lg font-medium mb-4">Notes</h3>
                @if($record->notes)
                    <p class="text-sm">{{ $record->notes }}</p>
                @else
                    <p class="text-sm text-gray-500">No notes available</p>
                @endif
            </div>

            <!-- Record Info -->
            <div class="card">
                <h3 class="text-lg font-medium mb-4">Record Information</h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm text-gray-500">Created By</dt>
                        <dd class="text-sm">{{ $record->created_by->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Created At</dt>
                        <dd class="text-sm">{{ $record->created_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    @if($record->updated_by)
                        <div>
                            <dt class="text-sm text-gray-500">Last Updated By</dt>
                            <dd class="text-sm">{{ $record->updated_by->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Last Updated At</dt>
                            <dd class="text-sm">{{ $record->updated_at->format('M d, Y h:i A') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function filterAttendance(type) {
        const rows = document.querySelectorAll('.attendance-row');
        rows.forEach(row => {
            if (type === 'all') {
                row.style.display = '';
            } else if (type === 'visitors') {
                row.style.display = row.dataset.type === 'visitor' ? '' : 'none';
            } else if (type === 'members') {
                row.style.display = row.dataset.type === 'member' ? '' : 'none';
            }
        });
    }
</script>
@endpush
@endsection
