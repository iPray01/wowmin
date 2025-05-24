<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Attendance;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Service::query();
        
        // Filter by service type if provided
        if ($request->has('service_type') && $request->service_type !== 'all') {
            $query->where('service_type', $request->service_type);
        }
        
        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('service_date', [$request->start_date, $request->end_date]);
        } elseif ($request->has('start_date')) {
            $query->where('service_date', '>=', $request->start_date);
        } elseif ($request->has('end_date')) {
            $query->where('service_date', '<=', $request->end_date);
        }
        
        // Search by name
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%');
            });
        }
        
        $services = $query->withCount('attendances')
                          ->orderBy('service_date', 'desc')
                          ->paginate(15);
        
        $serviceTypes = [
            'Sunday Service' => 'Sunday Service',
            'Bible Study' => 'Bible Study',
            'Prayer Meeting' => 'Prayer Meeting',
            'Youth Service' => 'Youth Service',
            'Special Event' => 'Special Event',
        ];
        
        return view('services.index', compact('services', 'serviceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $serviceTypes = [
            'Sunday Service' => 'Sunday Service',
            'Bible Study' => 'Bible Study',
            'Prayer Meeting' => 'Prayer Meeting',
            'Youth Service' => 'Youth Service',
            'Special Event' => 'Special Event',
        ];
        
        $recurrencePatterns = [
            'weekly' => 'Weekly',
            'biweekly' => 'Bi-weekly',
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
        ];
        
        return view('services.create', compact('serviceTypes', 'recurrencePatterns'));
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'service_date' => 'required|date',
            'service_time' => 'required|string',
            'service_type' => 'required|string',
            'location' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurrence_pattern' => 'required_if:is_recurring,1|nullable|string',
            'recurrence_count' => 'required_if:is_recurring,1|nullable|integer|min:1|max:52',
        ]);
        
        // Combine date and time
        $serviceDateTime = Carbon::parse($validated['service_date'] . ' ' . $validated['service_time']);
        
        // Create the service
        $service = Service::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'service_date' => $serviceDateTime,
            'service_type' => $validated['service_type'],
            'location' => $validated['location'] ?? null,
            'is_recurring' => $request->has('is_recurring'),
            'recurrence_pattern' => $request->has('is_recurring') ? $validated['recurrence_pattern'] : null,
        ]);
        
        // Create recurring services if needed
        if ($request->has('is_recurring') && isset($validated['recurrence_count']) && $validated['recurrence_count'] > 1) {
            $this->createRecurringServices($service, $validated['recurrence_pattern'], $validated['recurrence_count'], $serviceDateTime);
        }
        
        return redirect()->route('services.show', $service)
                         ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        $service->load('attendances.member');
        
        $attendanceCount = $service->attendances()->count();
        $attendees = $service->attendees()->orderBy('last_name')->get();
        
        return view('services.show', compact('service', 'attendanceCount', 'attendees'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        $serviceTypes = [
            'Sunday Service' => 'Sunday Service',
            'Bible Study' => 'Bible Study',
            'Prayer Meeting' => 'Prayer Meeting',
            'Youth Service' => 'Youth Service',
            'Special Event' => 'Special Event',
        ];
        
        $recurrencePatterns = [
            'weekly' => 'Weekly',
            'biweekly' => 'Bi-weekly',
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
        ];
        
        return view('services.edit', compact('service', 'serviceTypes', 'recurrencePatterns'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'service_date' => 'required|date',
            'service_time' => 'required|string',
            'service_type' => 'required|string',
            'location' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurrence_pattern' => 'required_if:is_recurring,1|nullable|string',
        ]);
        
        // Combine date and time
        $serviceDateTime = Carbon::parse($validated['service_date'] . ' ' . $validated['service_time']);
        
        // Update the service
        $service->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'service_date' => $serviceDateTime,
            'service_type' => $validated['service_type'],
            'location' => $validated['location'] ?? null,
            'is_recurring' => $request->has('is_recurring'),
            'recurrence_pattern' => $request->has('is_recurring') ? $validated['recurrence_pattern'] : null,
        ]);
        
        return redirect()->route('services.show', $service)
                         ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        // Delete all attendances for this service
        $service->attendances()->delete();
        
        // Soft delete the service
        $service->delete();
        
        return redirect()->route('services.index')
                         ->with('success', 'Service deleted successfully.');
    }

    /**
     * Display the check-in page for a service.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function checkIn(Service $service)
    {
        $members = Member::where('is_active', true)
                        ->orderBy('last_name')
                        ->get();
        
        $alreadyCheckedIn = $service->attendees()->pluck('member_id')->toArray();
        
        return view('services.check-in', compact('service', 'members', 'alreadyCheckedIn'));
    }

    /**
     * Process check-in for a member.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function processCheckIn(Request $request, Service $service)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'check_in_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        
        // Check if member is already checked in
        $existingAttendance = Attendance::where('service_id', $service->id)
                                      ->where('member_id', $validated['member_id'])
                                      ->first();
        
        if ($existingAttendance) {
            return redirect()->route('services.check-in', $service)
                             ->with('error', 'Member is already checked in for this service.');
        }
        
        // Create attendance record
        Attendance::create([
            'service_id' => $service->id,
            'member_id' => $validated['member_id'],
            'check_in_time' => now(),
            'check_in_method' => $validated['check_in_method'],
            'check_in_by' => auth()->user()->name ?? 'System',
            'notes' => $validated['notes'] ?? null,
        ]);
        
        return redirect()->route('services.check-in', $service)
                         ->with('success', 'Member checked in successfully.');
    }

    /**
     * Process check-out for a member.
     *
     * @param  \App\Models\Service  $service
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function processCheckOut(Service $service, Member $member)
    {
        $attendance = Attendance::where('service_id', $service->id)
                              ->where('member_id', $member->id)
                              ->first();
        
        if (!$attendance) {
            return redirect()->route('services.show', $service)
                             ->with('error', 'Member is not checked in for this service.');
        }
        
        // Update check-out time
        $attendance->update([
            'check_out_time' => now(),
        ]);
        
        return redirect()->route('services.show', $service)
                         ->with('success', 'Member checked out successfully.');
    }

    /**
     * Create recurring services based on a pattern.
     *
     * @param  \App\Models\Service  $service
     * @param  string  $pattern
     * @param  int  $count
     * @param  \Carbon\Carbon  $startDate
     * @return void
     */
    private function createRecurringServices(Service $service, $pattern, $count, $startDate)
    {
        for ($i = 1; $i < $count; $i++) {
            $nextDate = clone $startDate;
            
            switch ($pattern) {
                case 'weekly':
                    $nextDate->addWeeks($i);
                    break;
                case 'biweekly':
                    $nextDate->addWeeks($i * 2);
                    break;
                case 'monthly':
                    $nextDate->addMonths($i);
                    break;
                case 'quarterly':
                    $nextDate->addMonths($i * 3);
                    break;
                default:
                    continue 2; // Skip this iteration if pattern is invalid
            }
            
            Service::create([
                'name' => $service->name,
                'description' => $service->description,
                'service_date' => $nextDate,
                'service_type' => $service->service_type,
                'location' => $service->location,
                'is_recurring' => false, // Only the original service is marked as recurring
                'recurrence_pattern' => null,
            ]);
        }
    }
}

