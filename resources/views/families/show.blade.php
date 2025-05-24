@extends('layouts.app')

@section('title', $family->name ?? 'Family Details')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('families.index') }}" class="btn btn-secondary">
            ← Back to Families
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="md:col-span-2 space-y-6">
            <!-- Family Overview -->
            <div class="card">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold">{{ $family->name }}</h2>
                        <p class="text-sm" style="color: var(--color-burgundy)">Created {{ $family->created_at->format('F d, Y') }}</p>
                    </div>
                    <a href="{{ route('families.edit', $family) }}" class="btn btn-secondary">Edit Family</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium" style="color: var(--color-teal)">Primary Contact</h3>
                        <div class="mt-2">
                            <p class="text-sm">
                                @if($family->primary_contact_phone)
                                    <span class="font-medium">Phone:</span> {{ $family->primary_contact_phone }}<br>
                                @endif
                                @if($family->primary_contact_email)
                                    <span class="font-medium">Email:</span> {{ $family->primary_contact_email }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium" style="color: var(--color-teal)">Family Address</h3>
                        <p class="mt-2 text-sm">
                            {{ $family->address }}<br>
                            {{ $family->city }}, {{ $family->state }} {{ $family->zip }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Family Tree -->
            <div class="card">
                <h3 class="text-lg font-medium mb-6">Family Tree</h3>
                <div class="space-y-6">
                    <!-- Head of Family -->
                    <div>
                        <h4 class="text-sm font-medium mb-3" style="color: var(--color-teal)">Head of Family</h4>
                        @if($headOfFamily = $family->members->firstWhere('relationship', 'head'))
                            <div class="flex items-center space-x-3">
                                @if($headOfFamily->photo)
                                    <img class="h-12 w-12 rounded-full object-cover" src="{{ asset('storage/' . $headOfFamily->photo) }}" alt="{{ $headOfFamily->name }}">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500 text-sm">{{ substr($headOfFamily->name, 0, 2) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('members.show', $headOfFamily) }}" class="text-sm font-medium hover:underline">{{ $headOfFamily->name }}</a>
                                    <p class="text-xs text-gray-500">Head of Family</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Spouse -->
                    <div>
                        <h4 class="text-sm font-medium mb-3" style="color: var(--color-teal)">Spouse</h4>
                        @if($spouse = $family->members->firstWhere('relationship', 'spouse'))
                            <div class="flex items-center space-x-3">
                                @if($spouse->photo)
                                    <img class="h-12 w-12 rounded-full object-cover" src="{{ asset('storage/' . $spouse->photo) }}" alt="{{ $spouse->name }}">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500 text-sm">{{ substr($spouse->name, 0, 2) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('members.show', $spouse) }}" class="text-sm font-medium hover:underline">{{ $spouse->name }}</a>
                                    <p class="text-xs text-gray-500">Spouse</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Children -->
                    <div>
                        <h4 class="text-sm font-medium mb-3" style="color: var(--color-teal)">Children</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse($family->members->where('relationship', 'child') as $child)
                                <div class="flex items-center space-x-3">
                                    @if($child->photo)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $child->photo) }}" alt="{{ $child->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-500 text-xs">{{ substr($child->name, 0, 2) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('members.show', $child) }}" class="text-sm font-medium hover:underline">{{ $child->name }}</a>
                                        <p class="text-xs text-gray-500">Child</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No children registered</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Other Members -->
                    @if($otherMembers = $family->members->whereNotIn('relationship', ['head', 'spouse', 'child']))
                        <div>
                            <h4 class="text-sm font-medium mb-3" style="color: var(--color-teal)">Other Family Members</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($otherMembers as $member)
                                    <div class="flex items-center space-x-3">
                                        @if($member->photo)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-500 text-xs">{{ substr($member->name, 0, 2) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('members.show', $member) }}" class="text-sm font-medium hover:underline">{{ $member->name }}</a>
                                            <p class="text-xs text-gray-500">{{ ucfirst($member->relationship) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="card">
                <h3 class="text-lg font-medium mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('members.create', ['family_id' => $family->id]) }}" class="btn btn-secondary block text-center">Add Family Member</a>
                    <a href="{{ route('messages.create', ['family_id' => $family->id]) }}" class="btn btn-secondary block text-center">Send Family Message</a>
                    <a href="{{ route('attendance.create', ['family_id' => $family->id]) }}" class="btn btn-secondary block text-center">Record Family Attendance</a>
                </div>
            </div>

            <!-- Communication Preferences -->
            <div class="card">
                <h3 class="text-lg font-medium mb-4">Communication Preferences</h3>
                <div class="space-y-2">
                    @foreach($family->preferences ?? [] as $preference)
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full" style="background-color: var(--color-teal)"></span>
                            <span class="ml-2 text-sm">{{ ucfirst($preference) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <h3 class="text-lg font-medium mb-4">Recent Activity</h3>
                <div class="space-y-4">
                    @forelse($family->activities ?? [] as $activity)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full" style="background-color: var(--color-burgundy)"></div>
                            <div>
                                <p class="text-sm">{{ $activity->description }}</p>
                                <p class="text-xs" style="color: var(--color-teal)">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No recent activity</p>
                    @endforelse
                </div>
            </div>

            <!-- Notes -->
            <div class="card">
                <h3 class="text-lg font-medium mb-4">Family Notes</h3>
                @if($family->notes)
                    <p class="text-sm">{{ $family->notes }}</p>
                @else
                    <p class="text-sm text-gray-500">No notes available</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
