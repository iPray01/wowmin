@extends('layouts.app')

@section('title', 'Record Attendance')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
            ← Back to Attendance Records
        </a>
    </div>

    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Record Attendance</h2>

        <form action="{{ route('attendance.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Event Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="event_type" class="form-label">Event Type</label>
                    <select name="event_type" id="event_type" class="form-input" required>
                        <option value="">Select Event Type</option>
                        <option value="sunday_service">Sunday Service</option>
                        <option value="bible_study">Bible Study</option>
                        <option value="prayer_meeting">Prayer Meeting</option>
                        <option value="youth_service">Youth Service</option>
                        <option value="special_event">Special Event</option>
                    </select>
                </div>

                <div id="event_name_container" style="display: none;">
                    <label for="event_name" class="form-label">Event Name</label>
                    <input type="text" name="event_name" id="event_name" class="form-input" placeholder="Enter event name">
                </div>

                <div>
                    <label for="event_date" class="form-label">Event Date</label>
                    <input type="date" name="event_date" id="event_date" class="form-input" required value="{{ date('Y-m-d') }}">
                </div>

                <div>
                    <label for="event_time" class="form-label">Event Time</label>
                    <input type="time" name="event_time" id="event_time" class="form-input" required>
                </div>

                <div>
                    <label for="ministry" class="form-label">Ministry</label>
                    <select name="ministry" id="ministry" class="form-input">
                        <option value="">General Service</option>
                        <option value="choir">Choir</option>
                        <option value="youth">Youth Ministry</option>
                        <option value="children">Children's Ministry</option>
                        <option value="outreach">Outreach</option>
                    </select>
                </div>

                <div>
                    <label for="location" class="form-label">Location</label>
                    <input type="text" name="location" id="location" class="form-input" value="Main Sanctuary">
                </div>
            </div>

            <!-- Quick Check-in -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium">Quick Check-in</h3>
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <label for="member_search" class="form-label">Search Member</label>
                        <input type="text" id="member_search" class="form-input" placeholder="Type name or phone number">
                    </div>
                    <div class="flex-1">
                        <label for="family_search" class="form-label">Search Family</label>
                        <input type="text" id="family_search" class="form-input" placeholder="Type family name">
                    </div>
                </div>

                <!-- Search Results -->
                <div id="search_results" class="hidden space-y-2 p-4 bg-gray-50 rounded-md">
                    <!-- Results will be populated by JavaScript -->
                </div>
            </div>

            <!-- Attendance List -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium">Attendance List</h3>
                <div id="attendance_list" class="space-y-2">
                    <!-- List will be populated by JavaScript -->
                </div>

                <!-- Summary -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-md">
                    <div>
                        <span class="text-sm text-gray-500">Total Present</span>
                        <p class="text-2xl font-bold" style="color: var(--color-teal)">
                            <span id="total_present">0</span>
                        </p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">New Visitors</span>
                        <p class="text-2xl font-bold" style="color: var(--color-burgundy)">
                            <span id="total_visitors">0</span>
                        </p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Families Present</span>
                        <p class="text-2xl font-bold" style="color: var(--color-crimson)">
                            <span id="total_families">0</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium">Additional Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="weather" class="form-label">Weather Conditions</label>
                        <select name="weather" id="weather" class="form-input">
                            <option value="sunny">Sunny</option>
                            <option value="cloudy">Cloudy</option>
                            <option value="rainy">Rainy</option>
                            <option value="stormy">Stormy</option>
                            <option value="snowy">Snowy</option>
                        </select>
                    </div>

                    <div>
                        <label for="special_occasion" class="form-label">Special Occasion</label>
                        <input type="text" name="special_occasion" id="special_occasion" class="form-input" placeholder="e.g., Easter Sunday">
                    </div>
                </div>

                <div>
                    <label for="notes" class="form-label">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="form-input" placeholder="Any additional notes about the service or attendance"></textarea>
                </div>
            </div>

            <!-- Hidden Fields for Attendance Data -->
            <input type="hidden" name="attendance_data" id="attendance_data" value="">
            <input type="hidden" name="total_count" id="total_count" value="0">
            <input type="hidden" name="visitor_count" id="visitor_count" value="0">
            <input type="hidden" name="family_count" id="family_count" value="0">

            <div class="flex justify-end space-x-4">
                <button type="button" class="btn btn-secondary" onclick="clearAttendance()">Clear All</button>
                <button type="submit" class="btn btn-primary">Save Attendance</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Show/hide event name field based on event type
    document.getElementById('event_type').addEventListener('change', function() {
        const eventNameContainer = document.getElementById('event_name_container');
        eventNameContainer.style.display = this.value === 'special_event' ? 'block' : 'none';
    });

    // Initialize attendance tracking
    let attendanceList = [];
    let familiesPresent = new Set();

    // Member search functionality
    document.getElementById('member_search').addEventListener('input', debounce(function(e) {
        if (e.target.value.length < 2) return;
        
        fetch(`/api/members/search?q=${e.target.value}`)
            .then(response => response.json())
            .then(data => {
                const results = document.getElementById('search_results');
                results.innerHTML = data.map(member => `
                    <div class="flex items-center justify-between p-2 hover:bg-gray-100 rounded">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                <span class="text-xs">${member.name.substring(0, 2)}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium">${member.name}</p>
                                <p class="text-xs text-gray-500">${member.phone || 'No phone'}</p>
                            </div>
                        </div>
                        <button onclick="addAttendance('member', ${JSON.stringify(member)})" class="btn btn-secondary btn-sm">
                            Add
                        </button>
                    </div>
                `).join('');
                results.classList.remove('hidden');
            });
    }, 300));

    // Family search functionality
    document.getElementById('family_search').addEventListener('input', debounce(function(e) {
        if (e.target.value.length < 2) return;
        
        fetch(`/api/families/search?q=${e.target.value}`)
            .then(response => response.json())
            .then(data => {
                const results = document.getElementById('search_results');
                results.innerHTML = data.map(family => `
                    <div class="flex items-center justify-between p-2 hover:bg-gray-100 rounded">
                        <div>
                            <p class="text-sm font-medium">${family.name}</p>
                            <p class="text-xs text-gray-500">${family.members_count} members</p>
                        </div>
                        <button onclick="addAttendance('family', ${JSON.stringify(family)})" class="btn btn-secondary btn-sm">
                            Add All
                        </button>
                    </div>
                `).join('');
                results.classList.remove('hidden');
            });
    }, 300));

    function addAttendance(type, data) {
        if (type === 'member') {
            if (attendanceList.some(item => item.id === data.id)) return;
            attendanceList.push({
                type: 'member',
                id: data.id,
                name: data.name,
                family_id: data.family_id
            });
            if (data.family_id) familiesPresent.add(data.family_id);
        } else if (type === 'family') {
            data.members.forEach(member => {
                if (attendanceList.some(item => item.id === member.id)) return;
                attendanceList.push({
                    type: 'member',
                    id: member.id,
                    name: member.name,
                    family_id: data.id
                });
            });
            familiesPresent.add(data.id);
        }
        updateAttendanceDisplay();
        document.getElementById('search_results').classList.add('hidden');
    }

    function removeAttendance(id) {
        const index = attendanceList.findIndex(item => item.id === id);
        if (index > -1) {
            const removed = attendanceList.splice(index, 1)[0];
            if (removed.family_id) {
                // Check if there are any other members from this family
                if (!attendanceList.some(item => item.family_id === removed.family_id)) {
                    familiesPresent.delete(removed.family_id);
                }
            }
        }
        updateAttendanceDisplay();
    }

    function updateAttendanceDisplay() {
        const list = document.getElementById('attendance_list');
        list.innerHTML = attendanceList.map(item => `
            <div class="flex items-center justify-between p-2 bg-white rounded shadow-sm">
                <span class="text-sm">${item.name}</span>
                <button onclick="removeAttendance(${item.id})" class="text-sm text-red-600 hover:text-red-800">Remove</button>
            </div>
        `).join('');

        // Update counters
        document.getElementById('total_present').textContent = attendanceList.length;
        document.getElementById('total_families').textContent = familiesPresent.size;
        
        // Update hidden fields
        document.getElementById('attendance_data').value = JSON.stringify(attendanceList);
        document.getElementById('total_count').value = attendanceList.length;
        document.getElementById('family_count').value = familiesPresent.size;
    }

    function clearAttendance() {
        attendanceList = [];
        familiesPresent.clear();
        updateAttendanceDisplay();
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
</script>
@endpush
@endsection
