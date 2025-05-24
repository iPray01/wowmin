<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Member::query();
        
        // Filter by active status if provided
        if ($request->has('status')) {
            $status = $request->status === 'active';
            $query->where('is_active', $status);
        }
        
        // Filter by membership status if provided
        if ($request->has('membership_status') && $request->membership_status !== 'all') {
            $query->where('membership_status', $request->membership_status);
        }
        
        // Search by name or email
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }
        
        $members = $query->orderBy('last_name')->paginate(15);
        
        $membershipStatusOptions = [
            'visitor' => 'Visitor',
            'attendee' => 'Regular Attendee',
            'member' => 'Member',
            'inactive' => 'Inactive',
            'transferred' => 'Transferred',
        ];
        
        return view('members.index', compact('members', 'membershipStatusOptions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $families = Family::orderBy('family_name')->get();
        $membershipStatusOptions = [
            'visitor' => 'Visitor',
            'attendee' => 'Regular Attendee',
            'member' => 'Member',
        ];
        
        return view('members.create', compact('families', 'membershipStatusOptions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:members,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'marital_status' => 'nullable|string|in:single,married,divorced,widowed',
            'profile_photo' => 'nullable|image|max:2048',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'membership_status' => 'required|string|in:visitor,attendee,member,inactive,transferred',
            'membership_date' => 'nullable|date',
            'baptism_date' => 'nullable|date',
            'family_id' => 'nullable|exists:families,id',
            'relationship_type' => 'required_with:family_id|string',
        ]);
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo'] = $path;
        }
        
        // Create the member
        $member = Member::create($validated);
        
        // Attach to family if provided
        if ($request->filled('family_id')) {
            $member->families()->attach($request->family_id, [
                'relationship_type' => $request->relationship_type
            ]);
        }
        
        return redirect()->route('members.show', $member)
                         ->with('success', 'Member created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show(Member $member)
    {
        // Load all necessary relationships
        $member->load([
            'families',
            'attendances' => function($query) {
                $query->with('service')
                      ->orderBy('check_in_time', 'desc')
                      ->take(5);
            },
            'donations' => function($query) {
                $query->orderBy('donation_date', 'desc')
                      ->take(5);
            },
            'prayerRequests' => function($query) {
                $query->orderBy('created_at', 'desc')
                      ->take(5);
            },
            'profile'
        ]);
        
        // Get recent attendance records
        $recentAttendances = $member->attendances;
        
        // Get recent donations
        $recentDonations = $member->donations;
        
        return view('members.show', compact('member', 'recentAttendances', 'recentDonations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit(Member $member)
    {
        $families = Family::orderBy('family_name')->get();
        $memberFamilies = $member->families->pluck('id')->toArray();
        $membershipStatusOptions = [
            'visitor' => 'Visitor',
            'attendee' => 'Regular Attendee',
            'member' => 'Member',
            'inactive' => 'Inactive',
            'transferred' => 'Transferred',
        ];
        
        return view('members.edit', compact('member', 'families', 'memberFamilies', 'membershipStatusOptions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('members')->ignore($member->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'marital_status' => 'nullable|string|in:single,married,divorced,widowed',
            'profile_photo' => 'nullable|image|max:2048',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'membership_status' => 'required|string|in:visitor,attendee,member,inactive,transferred',
            'membership_date' => 'nullable|date',
            'baptism_date' => 'nullable|date',
            'is_active' => 'nullable',
            'family_id' => 'nullable|exists:families,id',
            'relationship_type' => 'required_with:family_id|string',
        ]);
        
        // Handle is_active field
        $validated['is_active'] = $request->has('is_active');
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($member->profile_photo) {
                Storage::disk('public')->delete($member->profile_photo);
            }
            
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo'] = $path;
        }
        
        // Update the member
        $member->update($validated);
        
        // Update family relationship if provided
        if ($request->filled('family_id')) {
            // Check if this is a new family for the member
            if (!$member->families()->where('family_id', $request->family_id)->exists()) {
                $member->families()->attach($request->family_id, [
                    'relationship_type' => $request->relationship_type
                ]);
            } else {
                // Update the existing relationship
                $member->families()->updateExistingPivot($request->family_id, [
                    'relationship_type' => $request->relationship_type
                ]);
            }
        }
        
        return redirect()->route('members.show', $member)
                         ->with('success', 'Member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member)
    {
        // Delete profile photo if exists
        if ($member->profile_photo) {
            Storage::disk('public')->delete($member->profile_photo);
        }
        
        // Detach from all families
        $member->families()->detach();
        
        // Soft delete the member
        $member->delete();
        
        return redirect()->route('members.index')
                         ->with('success', 'Member deleted successfully.');
    }

    /**
     * Display the attendance history for a member.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function attendanceHistory(Member $member)
    {
        $attendances = $member->attendances()->with('service')
                              ->orderBy('check_in_time', 'desc')
                              ->paginate(15);
        
        return view('members.attendance', compact('member', 'attendances'));
    }

    /**
     * Display the donation history for a member.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function donationHistory(Member $member)
    {
        $donations = $member->donations()->orderBy('donation_date', 'desc')
                            ->paginate(15);
        
        $totalDonations = $member->donations()->sum('amount');
        
        return view('members.donations', compact('member', 'donations', 'totalDonations'));
    }
}

