@extends('layouts.app')

@section('title', 'Add New Member')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('members.index') }}" class="btn btn-secondary">
            ← Back to Members
        </a>
    </div>

    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Add New Member</h2>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium" style="color: var(--color-teal)">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="form-label">First Name <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="first_name" 
                               id="first_name" 
                               class="form-input @error('first_name') border-red-500 @enderror" 
                               required 
                               value="{{ old('first_name') }}">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="form-label">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="last_name" 
                               id="last_name" 
                               class="form-input @error('last_name') border-red-500 @enderror" 
                               required 
                               value="{{ old('last_name') }}">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="form-label">Email</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-input @error('email') border-red-500 @enderror" 
                               value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" 
                               name="phone" 
                               id="phone" 
                               class="form-input @error('phone') border-red-500 @enderror" 
                               value="{{ old('phone') }}">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium" style="color: var(--color-teal)">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" 
                               name="date_of_birth" 
                               id="date_of_birth" 
                               class="form-input @error('date_of_birth') border-red-500 @enderror" 
                               value="{{ old('date_of_birth') }}">
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" 
                                id="gender" 
                                class="form-input @error('gender') border-red-500 @enderror">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="marital_status" class="form-label">Marital Status</label>
                        <select name="marital_status" 
                                id="marital_status" 
                                class="form-input @error('marital_status') border-red-500 @enderror">
                            <option value="">Select Marital Status</option>
                            <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                            <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                            <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                        @error('marital_status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="profile_photo" class="form-label">Profile Photo</label>
                        <input type="file" 
                               name="profile_photo" 
                               id="profile_photo" 
                               class="form-input @error('profile_photo') border-red-500 @enderror" 
                               accept="image/*">
                        @error('profile_photo')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Family Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium" style="color: var(--color-teal)">Family Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="family_id" class="form-label">Family Unit</label>
                        <select name="family_id" 
                                id="family_id" 
                                class="form-input @error('family_id') border-red-500 @enderror">
                            <option value="">Select Family</option>
                            @foreach($families as $family)
                                <option value="{{ $family->id }}" {{ old('family_id') == $family->id ? 'selected' : '' }}>
                                    {{ $family->family_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('family_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="relationship_type" class="form-label">Relationship in Family</label>
                        <select name="relationship_type" 
                                id="relationship_type" 
                                class="form-input @error('relationship_type') border-red-500 @enderror">
                            <option value="">Select Relationship</option>
                            <option value="head" {{ old('relationship_type') == 'head' ? 'selected' : '' }}>Head of Family</option>
                            <option value="spouse" {{ old('relationship_type') == 'spouse' ? 'selected' : '' }}>Spouse</option>
                            <option value="child" {{ old('relationship_type') == 'child' ? 'selected' : '' }}>Child</option>
                            <option value="parent" {{ old('relationship_type') == 'parent' ? 'selected' : '' }}>Parent</option>
                            <option value="sibling" {{ old('relationship_type') == 'sibling' ? 'selected' : '' }}>Sibling</option>
                            <option value="other" {{ old('relationship_type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('relationship_type')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Church Membership -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium" style="color: var(--color-teal)">Church Membership</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="membership_status" class="form-label">Membership Status <span class="text-red-500">*</span></label>
                        <select name="membership_status" 
                                id="membership_status" 
                                class="form-input @error('membership_status') border-red-500 @enderror" 
                                required>
                            <option value="">Select Status</option>
                            @foreach($membershipStatusOptions as $value => $label)
                                <option value="{{ $value }}" {{ old('membership_status') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('membership_status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="membership_date" class="form-label">Membership Date</label>
                        <input type="date" 
                               name="membership_date" 
                               id="membership_date" 
                               class="form-input @error('membership_date') border-red-500 @enderror" 
                               value="{{ old('membership_date') }}">
                        @error('membership_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="baptism_date" class="form-label">Baptism Date</label>
                        <input type="date" 
                               name="baptism_date" 
                               id="baptism_date" 
                               class="form-input @error('baptism_date') border-red-500 @enderror" 
                               value="{{ old('baptism_date') }}">
                        @error('baptism_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium" style="color: var(--color-teal)">Emergency Contact</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="emergency_contact_name" class="form-label">Contact Name</label>
                        <input type="text" 
                               name="emergency_contact_name" 
                               id="emergency_contact_name" 
                               class="form-input @error('emergency_contact_name') border-red-500 @enderror" 
                               value="{{ old('emergency_contact_name') }}">
                        @error('emergency_contact_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="emergency_contact_phone" class="form-label">Contact Phone</label>
                        <input type="tel" 
                               name="emergency_contact_phone" 
                               id="emergency_contact_phone" 
                               class="form-input @error('emergency_contact_phone') border-red-500 @enderror" 
                               value="{{ old('emergency_contact_phone') }}">
                        @error('emergency_contact_phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="emergency_contact_relationship" class="form-label">Relationship to Member</label>
                        <input type="text" 
                               name="emergency_contact_relationship" 
                               id="emergency_contact_relationship" 
                               class="form-input @error('emergency_contact_relationship') border-red-500 @enderror" 
                               value="{{ old('emergency_contact_relationship') }}">
                        @error('emergency_contact_relationship')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('members.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Member</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Show/hide relationship field based on family selection
    document.getElementById('family_id').addEventListener('change', function() {
        const relationshipField = document.getElementById('relationship_type');
        const relationshipContainer = relationshipField.closest('div');
        
        if (this.value) {
            relationshipContainer.style.display = 'block';
            relationshipField.required = true;
        } else {
            relationshipContainer.style.display = 'none';
            relationshipField.required = false;
            relationshipField.value = '';
        }
    });

    // Show/hide membership date based on status
    document.getElementById('membership_status').addEventListener('change', function() {
        const membershipDateField = document.getElementById('membership_date');
        const membershipDateContainer = membershipDateField.closest('div');
        
        if (this.value === 'member') {
            membershipDateContainer.style.display = 'block';
            membershipDateField.required = true;
        } else {
            membershipDateContainer.style.display = 'none';
            membershipDateField.required = false;
            membershipDateField.value = '';
        }
    });
</script>
@endpush
@endsection
