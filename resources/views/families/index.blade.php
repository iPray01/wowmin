@extends('layouts.app')

@section('title', 'Family Units')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="card">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Family Units</h2>
            <a href="{{ route('families.create') }}" class="btn btn-primary">Create New Family</a>
        </div>

        <!-- Search -->
        <div class="mb-6">
            <form action="{{ route('families.index') }}" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <label for="search" class="form-label">Search Families</label>
                    <input type="text" name="search" id="search" class="form-input" placeholder="Search by family name" value="{{ request('search') }}">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn btn-secondary">Search</button>
                </div>
            </form>
        </div>

        <!-- Families Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($families ?? [] as $family)
                <div class="card hover:shadow-lg transition-shadow duration-200">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-medium">{{ $family->family_name }}</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('families.edit', $family) }}" class="text-sm" style="color: var(--color-burgundy)">Edit</a>
                            <form action="{{ route('families.destroy', $family) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm" style="color: var(--color-crimson)" onclick="return confirm('Are you sure you want to delete this family unit?')">Delete</button>
                            </form>
                        </div>
                    </div>

                    <!-- Family Members -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium" style="color: var(--color-teal)">Family Members ({{ $family->members_count }})</span>
                            <a href="{{ route('families.show', $family) }}" class="text-sm hover:underline" style="color: var(--color-burgundy)">View Details →</a>
                        </div>
                        <div class="flex -space-x-2 overflow-hidden">
                            @foreach($family->members->take(5) as $member)
                                @if($member->profile_photo)
                                    <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white" src="{{ asset('storage/' . $member->profile_photo) }}" alt="{{ $member->full_name }}">
                                @else
                                    <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500 text-xs">{{ substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1) }}</span>
                                    </div>
                                @endif
                            @endforeach
                            @if($family->members_count > 5)
                                <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white" style="background-color: var(--color-teal); color: white">
                                    <span class="text-xs flex items-center justify-center h-full">+{{ $family->members_count - 5 }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="block text-gray-500">Address</span>
                            <span class="font-medium">{{ $family->address ?? 'Not set' }}</span>
                        </div>
                        <div>
                            <span class="block text-gray-500">Contact</span>
                            <span class="font-medium">{{ $family->primary_contact_phone ?? 'Not set' }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <p class="text-center text-gray-500">No family units found</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(isset($families) && $families->hasPages())
            <div class="mt-6">
                {{ $families->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
