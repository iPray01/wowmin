<?php

namespace App\Http\Controllers;

use App\Models\PrayerRequest;
use App\Models\PrayerResponse;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrayerRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = PrayerRequest::with(['member', 'responses']);
        
        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by member if provided
        if ($request->has('member_id') && $request->member_id) {
            $query->where('member_id', $request->member_id);
        }
        
        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } elseif ($request->has('start_date')) {
            $query->where('created_at', '>=', $request->start_date);
        } elseif ($request->has('end_date')) {
            $query->where('created_at', '<=', $request->end_date);
        }
        
        // Filter by category if provided
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        
        // Search by title or content
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }
        
        // Determine if we're showing only public requests or all requests
        if (!$request->has('show_private') || !$request->show_private) {
            $query->where('is_public', true);
        }
        
        $prayerRequests = $query->orderBy('created_at', 'desc')
                               ->paginate(20);
        
        $members = Member::orderBy('last_name')->get();
        $statuses = [
            'all' => 'All Requests',
            'active' => 'Active',
            'answered' => 'Answered',
            'closed' => 'Closed'
        ];
        $categories = [
            'all' => 'All Categories',
            'health' => 'Health',
            'family' => 'Family',
            'financial' => 'Financial',
            'spiritual' => 'Spiritual',
            'other' => 'Other'
        ];
        
        return view('prayer-requests.index', compact('prayerRequests', 'members', 'statuses', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $members = Member::orderBy('last_name')->get();
        $categories = [
            'health' => 'Health',
            'family' => 'Family',
            'financial' => 'Financial',
            'spiritual' => 'Spiritual',
            'other' => 'Other'
        ];
        
        return view('prayer-requests.create', compact('members', 'categories'));
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
            'member_id' => 'required|exists:members,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'is_public' => 'boolean',
            'is_anonymous' => 'boolean',
            'status' => 'required|string|in:active,answered,closed',
        ]);
        
        // Create the prayer request
        $prayerRequest = PrayerRequest::create([
            'member_id' => $validated['member_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'is_public' => $request->has('is_public'),
            'is_anonymous' => $request->has('is_anonymous'),
            'status' => $validated['status'],
            'submitted_by' => Auth::id(),
        ]);
        
        return redirect()->route('prayer-requests.show', $prayerRequest)
                         ->with('success', 'Prayer request created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PrayerRequest  $prayerRequest
     * @return \Illuminate\Http\Response
     */
    public function show(PrayerRequest $prayerRequest)
    {
        $prayerRequest->load(['member', 'responses.member']);
        
        // Check if the request is private and the current user is not the owner or an admin
        if (!$prayerRequest->is_public && Auth::id() !== $prayerRequest->member_id && !Auth::user()->hasRole('admin')) {
            return redirect()->route('prayer-requests.index')
                             ->with('error', 'You do not have permission to view this prayer request.');
        }
        
        return view('prayer-requests.show', compact('prayerRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PrayerRequest  $prayerRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(PrayerRequest $prayerRequest)
    {
        // Check if the current user is the owner or an admin
        if (Auth::id() !== $prayerRequest->member_id && !Auth::user()->hasRole('admin')) {
            return redirect()->route('prayer-requests.index')
                             ->with('error', 'You do not have permission to edit this prayer request.');
        }
        
        $members = Member::orderBy('last_name')->get();
        $categories = [
            'health' => 'Health',
            'family' => 'Family',
            'financial' => 'Financial',
            'spiritual' => 'Spiritual',
            'other' => 'Other'
        ];
        $statuses = [
            'active' => 'Active',
            'answered' => 'Answered',
            'closed' => 'Closed'
        ];
        
        return view('prayer-requests.edit', compact('prayerRequest', 'members', 'categories', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PrayerRequest  $prayerRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PrayerRequest $prayerRequest)
    {
        // Check if the current user is the owner or an admin
        if (Auth::id() !== $prayerRequest->member_id && !Auth::user()->hasRole('admin')) {
            return redirect()->route('prayer-requests.index')
                             ->with('error', 'You do not have permission to update this prayer request.');
        }
        
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'is_private' => 'boolean',
            'is_anonymous' => 'boolean',
            'status' => 'required|string|in:active,answered,closed',
        ]);
        
        // Update the prayer request
        $prayerRequest->update([
            'member_id' => $validated['member_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'is_private' => $request->has('is_private'),
            'is_anonymous' => $request->has('is_anonymous'),
            'status' => $validated['status'],
        ]);
        
        return redirect()->route('prayer-requests.show', $prayerRequest)
                         ->with('success', 'Prayer request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PrayerRequest  $prayerRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(PrayerRequest $prayerRequest)
    {
        // Check if the current user is the owner or an admin
        if (Auth::id() !== $prayerRequest->member_id && !Auth::user()->hasRole('admin')) {
            return redirect()->route('prayer-requests.index')
                             ->with('error', 'You do not have permission to delete this prayer request.');
        }
        
        // Delete all responses
        $prayerRequest->responses()->delete();
        
        // Delete the prayer request
        $prayerRequest->delete();
        
        return redirect()->route('prayer-requests.index')
                         ->with('success', 'Prayer request deleted successfully.');
    }
    
    /**
     * Add a response to a prayer request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PrayerRequest  $prayerRequest
     * @return \Illuminate\Http\Response
     */
    public function addResponse(Request $request, PrayerRequest $prayerRequest)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'is_anonymous' => 'boolean',
        ]);
        
        // Create the prayer response
        PrayerResponse::create([
            'prayer_request_id' => $prayerRequest->id,
            'member_id' => Auth::id(),
            'content' => $validated['content'],
            'is_anonymous' => $request->has('is_anonymous'),
        ]);
        
        return redirect()->route('prayer-requests.show', $prayerRequest)
                         ->with('success', 'Response added successfully.');
    }
    
    /**
     * Remove a response from a prayer request.
     *
     * @param  \App\Models\PrayerRequest  $prayerRequest
     * @param  \App\Models\PrayerResponse  $response
     * @return \Illuminate\Http\Response
     */
    public function removeResponse(PrayerRequest $prayerRequest, PrayerResponse $response)
    {
        // Check if the current user is the owner of the response or an admin
        if (Auth::id() !== $response->member_id && !Auth::user()->hasRole('admin')) {
            return redirect()->route('prayer-requests.show', $prayerRequest)
                             ->with('error', 'You do not have permission to delete this response.');
        }
        
        // Delete the response
        $response->delete();
        
        return redirect()->route('prayer-requests.show', $prayerRequest)
                         ->with('success', 'Response removed successfully.');
    }
    
    /**
     * Mark a prayer request as answered.
     *
     * @param  \App\Models\PrayerRequest  $prayerRequest
     * @return \Illuminate\Http\Response
     */
    public function markAsAnswered(PrayerRequest $prayerRequest)
    {
        // Check if the current user is the owner or an admin
        if (Auth::id() !== $prayerRequest->member_id && !Auth::user()->hasRole('admin')) {
            return redirect()->route('prayer-requests.index')
                             ->with('error', 'You do not have permission to update this prayer request.');
        }
        
        // Update the prayer request status
        $prayerRequest->update([
            'status' => 'answered',
            'answered_at' => now(),
        ]);
        
        return redirect()->route('prayer-requests.show', $prayerRequest)
                         ->with('success', 'Prayer request marked as answered.');
    }
    
    /**
     * Display a dashboard of prayer request statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        // Get counts by status
        $statusCounts = [
            'active' => PrayerRequest::where('status', 'active')->count(),
            'answered' => PrayerRequest::where('status', 'answered')->count(),
            'closed' => PrayerRequest::where('status', 'closed')->count(),
        ];
        
        // Get counts by category
        $categoryCounts = PrayerRequest::select('category')
                                    ->selectRaw('COUNT(*) as count')
                                    ->groupBy('category')
                                    ->pluck('count', 'category')
                                    ->toArray();
        
        // Get recent prayer requests
        $recentRequests = PrayerRequest::with(['member', 'responses'])
                                    ->where('is_private', false)
                                    ->orderBy('created_at', 'desc')
                                    ->limit(10)
                                    ->get();
        
        // Get recently answered prayer requests
        $recentlyAnswered = PrayerRequest::with(['member', 'responses'])
                                      ->where('status', 'answered')
                                      ->where('is_private', false)
                                      ->orderBy('answered_at', 'desc')
                                      ->limit(10)
                                      ->get();
        
        // Get most active members (those with the most requests)
        $activeMemberIds = PrayerRequest::select('member_id')
                                      ->selectRaw('COUNT(*) as count')
                                      ->groupBy('member_id')
                                      ->orderBy('count', 'desc')
                                      ->limit(10)
                                      ->pluck('member_id')
                                      ->toArray();
        
        $activeMembers = Member::whereIn('id', $activeMemberIds)
                             ->get()
                             ->sortBy(function($member) use ($activeMemberIds) {
                                 return array_search($member->id, $activeMemberIds);
                             });
        
        return view('prayer-requests.dashboard', compact(
            'statusCounts',
            'categoryCounts',
            'recentRequests',
            'recentlyAnswered',
            'activeMembers'
        ));
    }
}

