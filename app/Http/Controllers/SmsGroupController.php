<?php

namespace App\Http\Controllers;

use App\Models\SmsGroup;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsGroupController extends Controller
{
    /**
     * Display a listing of SMS groups.
     */
    public function index()
    {
        $groups = SmsGroup::with(['creator', 'members'])
            ->withCount('members')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('sms.groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new SMS group.
     */
    public function create()
    {
        $members = Member::orderBy('last_name')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'phone']);

        return view('sms.groups.create', compact('members'));
    }

    /**
     * Store a newly created SMS group.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'members' => 'required|array|min:1',
            'members.*' => 'exists:members,id',
        ]);

        $group = new SmsGroup([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);
        $group->created_by = Auth::id();
        $group->save();

        $group->members()->attach($validated['members'], [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('sms.groups.index')
            ->with('success', 'SMS group created successfully.');
    }

    /**
     * Display the specified SMS group.
     */
    public function show(SmsGroup $group)
    {
        $group->load(['creator', 'members']);

        return view('sms.groups.show', compact('group'));
    }

    /**
     * Show the form for editing the specified SMS group.
     */
    public function edit(SmsGroup $group)
    {
        $members = Member::orderBy('last_name')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'phone']);

        $selectedMembers = $group->members->pluck('id')->toArray();

        return view('sms.groups.edit', compact('group', 'members', 'selectedMembers'));
    }

    /**
     * Update the specified SMS group.
     */
    public function update(Request $request, SmsGroup $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'members' => 'required|array|min:1',
            'members.*' => 'exists:members,id',
        ]);

        $group->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        // Sync members
        $group->members()->sync($validated['members']);

        return redirect()->route('sms.groups.index')
            ->with('success', 'SMS group updated successfully.');
    }

    /**
     * Remove the specified SMS group.
     */
    public function destroy(SmsGroup $group)
    {
        $group->delete();

        return redirect()->route('sms.groups.index')
            ->with('success', 'SMS group deleted successfully.');
    }

    /**
     * Add members to the group.
     */
    public function addMembers(Request $request, SmsGroup $group)
    {
        $validated = $request->validate([
            'members' => 'required|array|min:1',
            'members.*' => 'exists:members,id',
        ]);

        $group->members()->attach($validated['members'], [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Members added successfully.',
            'count' => count($validated['members']),
        ]);
    }

    /**
     * Remove members from the group.
     */
    public function removeMembers(Request $request, SmsGroup $group)
    {
        $validated = $request->validate([
            'members' => 'required|array|min:1',
            'members.*' => 'exists:members,id',
        ]);

        $group->members()->detach($validated['members']);

        return response()->json([
            'message' => 'Members removed successfully.',
            'count' => count($validated['members']),
        ]);
    }
}
