@extends('layouts.app')

@section('title', 'Create Family Unit')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('families.index') }}" class="btn btn-secondary">
            ← Back to Families
        </a>
    </div>

    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Create New Family Unit</h2>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('families.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="family_name" class="form-label">
                        Family Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="family_name" 
                           id="family_name" 
                           class="form-input @error('family_name') border-red-500 @enderror" 
                           required 
                           value="{{ old('family_name') }}">
                    @error('family_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="primary_contact_phone" class="form-label">
                        Primary Contact Phone
                    </label>
                    <input type="tel" 
                           name="primary_contact_phone" 
                           id="primary_contact_phone" 
                           class="form-input @error('primary_contact_phone') border-red-500 @enderror"
                           value="{{ old('primary_contact_phone') }}">
                    @error('primary_contact_phone')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="primary_contact_email" class="form-label">
                    Primary Contact Email
                </label>
                <input type="email" 
                       name="primary_contact_email" 
                       id="primary_contact_email" 
                       class="form-input @error('primary_contact_email') border-red-500 @enderror"
                       value="{{ old('primary_contact_email') }}">
                @error('primary_contact_email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="address" class="form-label">
                    Address
                </label>
                <textarea name="address" 
                          id="address" 
                          class="form-input @error('address') border-red-500 @enderror"
                          rows="3">{{ old('address') }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Family Members -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium">Family Members</h3>
                
                <!-- Head of Family -->
                <div>
                    <label for="head_id" class="form-label">Head of Family</label>
                    <select name="head_id" id="head_id" class="form-input @error('head_id') border-red-500 @enderror">
                        <option value="">Select Head of Family</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('head_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->full_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('head_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Spouse -->
                <div>
                    <label for="spouse_id" class="form-label">Spouse</label>
                    <select name="spouse_id" id="spouse_id" class="form-input @error('spouse_id') border-red-500 @enderror">
                        <option value="">Select Spouse</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('spouse_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->full_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('spouse_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Children -->
                <div>
                    <label class="form-label">Children</label>
                    <div class="space-y-2">
                        @foreach($members as $member)
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" 
                                       name="children[]" 
                                       value="{{ $member->id }}"
                                       id="child_{{ $member->id }}"
                                       class="form-checkbox"
                                       {{ (is_array(old('children')) && in_array($member->id, old('children'))) ? 'checked' : '' }}>
                                <label for="child_{{ $member->id }}">{{ $member->full_name }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('children')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="family_notes" class="form-label">Family Notes</label>
                <textarea name="family_notes" 
                          id="family_notes" 
                          class="form-input @error('family_notes') border-red-500 @enderror"
                          rows="3">{{ old('family_notes') }}</textarea>
                @error('family_notes')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary">
                    Create Family Unit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
