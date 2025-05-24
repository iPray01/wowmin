<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Member;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Family::query();
        
        // Search by family name
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('family_name', 'like', '%' . $search . '%')
                  ->orWhere('primary_contact_email', 'like', '%' . $search . '%')
                  ->orWhere('primary_contact_phone', 'like', '%' . $search . '%');
            });
        }
        
        $families = $query->withCount('members')
                         ->orderBy('family_name')
                         ->paginate(15);
        
        return view('families.index', compact('families'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $members = Member::orderBy('last_name')->get();
        
        return view('families.create', compact('members'));
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
            'family_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'primary_contact_phone' => 'nullable|string|max:20',
            'primary_contact_email' => 'nullable|email',
            'family_notes' => 'nullable|string',
            'communication_preferences' => 'nullable|array',
            'head_id' => 'nullable|exists:members,id',
            'spouse_id' => 'nullable|exists:members,id',
            'children' => 'nullable|array',
            'children.*' => 'exists:members,id',
        ]);
        
        // Create the family
        $family = Family::create([
            'family_name' => $validated['family_name'],
            'address' => $validated['address'] ?? null,
            'primary_contact_phone' => $validated['primary_contact_phone'] ?? null,
            'primary_contact_email' => $validated['primary_contact_email'] ?? null,
            'family_notes' => $validated['family_notes'] ?? null,
            'communication_preferences' => $validated['communication_preferences'] ?? null,
        ]);
        
        // Attach family head if provided
        if (!empty($validated['head_id'])) {
            $family->members()->attach($validated['head_id'], ['relationship_type' => 'head']);
        }
        
        // Attach spouse if provided
        if (!empty($validated['spouse_id'])) {
            $family->members()->attach($validated['spouse_id'], ['relationship_type' => 'spouse']);
        }
        
        // Attach children if provided
        if (!empty($validated['children'])) {
            foreach ($validated['children'] as $childId) {
                $family->members()->attach($childId, ['relationship_type' => 'child']);
            }
        }
        
        return redirect()->route('families.show', $family)
                         ->with('success', 'Family created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function show(Family $family)
    {
        $family->load('members');
        
        // Group members by relationship type
        $head = $family->head();
        $spouse = $family->spouse();
        $children = $family->children();
        $otherMembers = $family->members()
                             ->wherePivotNotIn('relationship_type', ['head', 'spouse', 'child'])
                             ->get();
        
        return view('families.show', compact('family', 'head', 'spouse', 'children', 'otherMembers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function edit(Family $family)
    {
        $family->load('members');
        
        $members = Member::orderBy('last_name')->get();
        
        // Get current family members by relationship type
        $head = $family->head();
        $spouse = $family->spouse();
        $children = $family->children();
        $otherMembers = $family->members()
                             ->wherePivotNotIn('relationship_type', ['head', 'spouse', 'child'])
                             ->get();
        
        return view('families.edit', compact('family', 'members', 'head', 'spouse', 'children', 'otherMembers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Family $family)
    {
        $validated = $request->validate([
            'family_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'primary_contact_phone' => 'nullable|string|max:20',
            'primary_contact_email' => 'nullable|email',
            'family_notes' => 'nullable|string',
            'communication_preferences' => 'nullable|array',
            'head_id' => 'nullable|exists:members,id',
            'spouse_id' => 'nullable|exists:members,id',
            'children' => 'nullable|array',
            'children.*' => 'exists:members,id',
            'other_members' => 'nullable|array',
            'other_members.*' => 'exists:members,id',
            'other_relationships' => 'nullable|array',
            'other_relationships.*' => 'string',
        ]);
        
        // Update the family
        $family->update([
            'family_name' => $validated['family_name'],
            'address' => $validated['address'] ?? null,
            'primary_contact_phone' => $validated['primary_contact_phone'] ?? null,
            'primary_contact_email' => $validated['primary_contact_email'] ?? null,
            'family_notes' => $validated['family_notes'] ?? null,
            'communication_preferences' => $validated['communication_preferences'] ?? null,
        ]);
        
        // Detach all existing members
        $family->members()->detach();
        
        // Attach family head if provided
        if (!empty($validated['head_id'])) {
            $family->members()->attach($validated['head_id'], ['relationship_type' => 'head']);
        }
        
        // Attach spouse if provided
        if (!empty($validated['spouse_id'])) {
            $family->members()->attach($validated['spouse_id'], ['relationship_type' => 'spouse']);
        }
        
        // Attach children if provided
        if (!empty($validated['children'])) {
            foreach ($validated['children'] as $childId) {
                $family->members()->attach($childId, ['relationship_type' => 'child']);
            }
        }
        
        // Attach other members if provided
        if (!empty($validated['other_members']) && !empty($validated['other_relationships'])) {
            foreach ($validated['other_members'] as $key => $memberId) {
                $relationship = $validated['other_relationships'][$key] ?? 'relative';
                $family->members()->attach($memberId, ['relationship_type' => $relationship]);
            }
        }
        
        return redirect()->route('families.show', $family)
                         ->with('success', 'Family updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function destroy(Family $family)
    {
        // Detach all members from the family
        $family->members()->detach();
        
        // Soft delete the family
        $family->delete();
        
        return redirect()->route('families.index')
                         ->with('success', 'Family deleted successfully.');
    }

    /**
     * Add a member to a family.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function addMember(Request $request, Family $family)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'relationship_type' => 'required|string',
        ]);
        
        // Check if the member is already in the family
        if ($family->members()->where('member_id', $validated['member_id'])->exists()) {
            return redirect()->route('families.show', $family)
                             ->with('error', 'Member is already in this family.');
        }
        
        // Attach the member to the family
        $family->members()->attach($validated['member_id'], [
            'relationship_type' => $validated['relationship_type']
        ]);
        
        return redirect()->route('families.show', $family)
                         ->with('success', 'Member added to family successfully.');
    }

    /**
     * Remove a member from a family.
     *
     * @param  \App\Models\Family  $family
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function removeMember(Family $family, Member $member)
    {
        // Detach the member from the family
        $family->members()->detach($member->id);
        
        return redirect()->route('families.show', $family)
                         ->with('success', 'Member removed from family successfully.');
    }
}

