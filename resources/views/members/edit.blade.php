@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold text-metal-gold mb-6">Edit Member</h2>

            <form action="{{ route('members.update', $member) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-teal mb-4">Personal Information</h3>

                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $member->first_name) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring focus:ring-teal focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $member->last_name) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring focus:ring-teal focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $member->email) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring focus:ring-teal focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone', $member->phone) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring focus:ring-teal focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : '') }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring focus:ring-teal focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                            <select name="gender" id="gender" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring focus:ring-teal focus:ring-opacity-50">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $member->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $member->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $member->gender) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="marital_status" class="block text-sm font-medium text-gray-700">Marital Status</label>
                            <select name="marital_status" id="marital_status" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring focus:ring-teal focus:ring-opacity-50">
                                <option value="">Select Marital Status</option>
                                <option value="single" {{ old('marital_status', $member->marital_status) === 'single' ? 'selected' : '' }}>Single</option>
                                <option value="married" {{ old('marital_status', $member->marital_status) === 'married' ? 'selected' : '' }}>Married</option>
                                <option value="divorced" {{ old('marital_status', $member->marital_status) === 'divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="widowed" {{ old('marital_status', $member->marital_status) === 'widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                        </div>
                    </div>

                    <!-- Church Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-teal mb-4">Church Information</h3>

                        <div>
                            <label for="membership_status" class="block text-sm font-medium text-gray-700">Membership Status</label>
                            <select name="membership_status" id="membership_status" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring focus:ring-teal focus:ring-opacity-50">
                                @foreach($membershipStatusOptions as $value => $label)
                                    <option value="{{ $value }}" {{ old('membership_status', $member->membership_status) === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="membership_date" class="block text-sm font-medium text-gray-700">Membership Date</label>
                            <input type="date" name="membership_date" id="membership_date" value="{{ old('membership_date', $member->membership_date ? $member->membership_date->format('Y-m-d') : '') }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring focus:ring-teal focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="baptism_date" class="block text-sm font-medium text-gray-700">Baptism Date</label>
                            <input type="date" name="baptism_date" id="baptism_date" value="{{ old('baptism_date', $member->baptism_date ? $member->baptism_date->format('Y-m-d') : '') }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring focus:ring-teal focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="family_id" class="block text-sm font-medium text-gray-700">Family</label>
                            <select name="family_id" id="family_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring focus:ring-teal focus:ring-opacity-50">
                                <option value="">Select Family</option>
                                @foreach($families as $family)
                                    <option value="{{ $family->id }}" {{ in_array($family->id, $memberFamilies) ? 'selected' : '' }}>
                                        {{ $family->family_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="relationship_type" class="block text-sm font-medium text-gray-700">Family Relationship</label>
                            <select name="relationship_type" id="relationship_type" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring focus:ring-teal focus:ring-opacity-50">
                                <option value="">Select Relationship</option>
                                <option value="head">Head</option>
                                <option value="spouse">Spouse</option>
                                <option value="child">Child</option>
                                <option value="relative">Relative</option>
                            </select>
                        </div>

                        <div class="flex items-center mt-4">
                            <input type="checkbox" name="is_active" id="is_active" {{ old('is_active', $member->is_active) ? 'checked' : '' }} 
                                class="rounded border-gray-300 text-teal focus:ring-teal">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">Active Member</label>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="space-y-4 md:col-span-2">
                        <h3 class="text-lg font-semibold text-teal mb-4">Emergency Contact</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700">Contact Name</label>
                                <input type="text" name="emergency_contact_name" id="emergency_contact_name" value="{{ old('emergency_contact_name', $member->emergency_contact_name) }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring focus:ring-teal focus:ring-opacity-50">
                            </div>

                            <div>
                                <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700">Contact Phone</label>
                                <input type="tel" name="emergency_contact_phone" id="emergency_contact_phone" value="{{ old('emergency_contact_phone', $member->emergency_contact_phone) }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring focus:ring-teal focus:ring-opacity-50">
                            </div>

                            <div>
                                <label for="emergency_contact_relationship" class="block text-sm font-medium text-gray-700">Relationship</label>
                                <input type="text" name="emergency_contact_relationship" id="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $member->emergency_contact_relationship) }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring focus:ring-teal focus:ring-opacity-50">
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal focus:ring focus:ring-teal focus:ring-opacity-50">{{ old('address', $member->address) }}</textarea>
                        </div>
                    </div>

                    <!-- Profile Photo -->
                    <div class="md:col-span-2">
                        <div>
                            <label for="profile_photo" class="block text-sm font-medium text-gray-700">Profile Photo</label>
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*" 
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal hover:file:bg-teal-100">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('members.show', $member) }}" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal hover:bg-teal-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal">
                        Update Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 