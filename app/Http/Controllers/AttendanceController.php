<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Service;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Attendance::with(['member', 'service']);
        
        // Filter by member if provided
        if ($request->has('member_id') && $request->member_id) {
            $query->where('member_id', $request->member_id);
        }
        
        // Filter by service if provided
        if ($request->has('service_id') && $request->service_id) {
            $query->where('service_id', $request->service_id);
        }
        
        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereHas('service', function($q) use ($request) {
                $q->whereBetween('service_date', [$request->start_date, $request->end_date]);
            });
        } elseif ($request->has('start_date')) {
            $query->whereHas('service', function($q) use ($request) {
                $q->where('service_date', '>=', $request->start_date);
            });
        } elseif ($request->has('end_date')) {
            $query->whereHas('service', function($q) use ($request) {
                $q->where('service_date', '<=', $request->end_date);
            });
        }
        
        $attendances = $query->orderBy('created_at', 'desc')
                            ->paginate(20);
        
        $members = Member::orderBy('last_name')->get();
        $services = Service::orderBy('service_date', 'desc')->get();
        
        return view('attendances.index', compact('attendances', 'members', 'services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $members = Member::where('is_active', true)
                        ->orderBy('last_name')
                        ->get();
                        
        $services = Service::where('service_date', '>=', now()->subDays(30))
                          ->orderBy('service_date', 'desc')
                          ->get();
                          
        $checkInMethods = [
            'manual' => 'Manual Entry',
            'qr_code' => 'QR Code',
            'card_scan' => 'Card Scan',
            'face_recognition' => 'Face Recognition',
        ];
        
        return view('attendances.create', compact('members', 'services', 'checkInMethods'));
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
            'service_id' => 'required|exists:services,id',
            'member_id' => 'required|exists:members,id',
            'check_in_time' => 'required|date',
            'check_out_time' => 'nullable|date|after:check_in_time',
            'check_in_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        
        // Check if attendance record already exists
        $existingAttendance = Attendance::where('service_id', $validated['service_id'])
                                      ->where('member_id', $validated['member_id'])
                                      ->first();
        
        if ($existingAttendance) {
            return redirect()->route('attendance.create')
                             ->with('error', 'Attendance record already exists for this member and service.');
        }
        
        // Create attendance record
        $attendance = Attendance::create([
            'service_id' => $validated['service_id'],
            'member_id' => $validated['member_id'],
            'check_in_time' => $validated['check_in_time'],
            'check_out_time' => $validated['check_out_time'] ?? null,
            'check_in_method' => $validated['check_in_method'],
            'check_in_by' => auth()->user()->name ?? 'System',
            'notes' => $validated['notes'] ?? null,
        ]);
        
        return redirect()->route('attendance.show', $attendance)
                         ->with('success', 'Attendance record created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        $attendance->load(['member', 'service']);
        
        return view('attendances.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        $attendance->load(['member', 'service']);
        
        $members = Member::orderBy('last_name')->get();
        $services = Service::orderBy('service_date', 'desc')->get();
        
        $checkInMethods = [
            'manual' => 'Manual Entry',
            'qr_code' => 'QR Code',
            'card_scan' => 'Card Scan',
            'face_recognition' => 'Face Recognition',
        ];
        
        return view('attendances.edit', compact('attendance', 'members', 'services', 'checkInMethods'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'member_id' => 'required|exists:members,id',
            'check_in_time' => 'required|date',
            'check_out_time' => 'nullable|date|after:check_in_time',
            'check_in_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        
        // Check if changing to a member/service combination that already exists
        if ($attendance->service_id != $validated['service_id'] || $attendance->member_id != $validated['member_id']) {
            $existingAttendance = Attendance::where('service_id', $validated['service_id'])
                                          ->where('member_id', $validated['member_id'])
                                          ->where('id', '!=', $attendance->id)
                                          ->first();
            
            if ($existingAttendance) {
                return redirect()->route('attendance.edit', $attendance)
                                 ->with('error', 'Attendance record already exists for this member and service.');
            }
        }
        
        // Update attendance record
        $attendance->update([
            'service_id' => $validated['service_id'],
            'member_id' => $validated['member_id'],
            'check_in_time' => $validated['check_in_time'],
            'check_out_time' => $validated['check_out_time'] ?? null,
            'check_in_method' => $validated['check_in_method'],
            'notes' => $validated['notes'] ?? null,
        ]);
        
        return redirect()->route('attendance.show', $attendance)
                         ->with('success', 'Attendance record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        
        return redirect()->route('attendance.index')
                         ->with('success', 'Attendance record deleted successfully.');
    }
    
    /**
     * Display attendance statistics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function statistics(Request $request)
    {
        // Default to last 3 months if no date range provided
        $startDate = $request->start_date ?? now()->subMonths(3)->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');
        
        // Get attendance counts by service type
        $attendanceByServiceType = DB::table('attendances')
            ->join('services', 'attendances.service_id', '=', 'services.id')
            ->whereBetween('services.service_date', [$startDate, $endDate])
            ->select('services.service_type', DB::raw('count(*) as count'))
            ->groupBy('services.service_type')
            ->get();
        
        // Get attendance counts by date (for trend analysis)
        $attendanceByDate = DB::table('attendances')
            ->join('services', 'attendances.service_id', '=', 'services.id')
            ->whereBetween('services.service_date', [$startDate, $endDate])
            ->select(DB::raw('DATE(services.service_date) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Get top attending members
        $topAttendingMembers = DB::table('attendances')
            ->join('services', 'attendances.service_id', '=', 'services.id')
            ->join('members', 'attendances.member_id', '=', 'members.id')
            ->whereBetween('services.service_date', [$startDate, $endDate])
            ->select('members.id', 'members.first_name', 'members.last_name', DB::raw('count(*) as attendance_count'))
            ->groupBy('members.id', 'members.first_name', 'members.last_name')
            ->orderBy('attendance_count', 'desc')
            ->limit(10)
            ->get();
        
        // Get first-time visitors
        $firstTimeVisitors = Member::whereHas('attendances', function($query) use ($startDate, $endDate) {
                $query->whereHas('service', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('service_date', [$startDate, $endDate]);
                });
            })
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->count();
        
        // Calculate average attendance by service type
        $avgAttendanceByServiceType = DB::table('services')
            ->leftJoin(DB::raw('(SELECT service_id, COUNT(*) as attendee_count FROM attendances GROUP BY service_id) as a'), 'services.id', '=', 'a.service_id')
            ->whereBetween('services.service_date', [$startDate, $endDate])
            ->select('services.service_type', DB::raw('AVG(COALESCE(a.attendee_count, 0)) as avg_attendance'))
            ->groupBy('services.service_type')
            ->get();
        
        return view('attendances.statistics', compact(
            'attendanceByServiceType',
            'attendanceByDate',
            'topAttendingMembers',
            'firstTimeVisitors',
            'avgAttendanceByServiceType',
            'startDate',
            'endDate'
        ));
    }
    
    /**
     * Generate attendance report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'report_type' => 'required|in:summary,detailed,member,service',
            'service_type' => 'nullable|string',
            'member_id' => 'nullable|exists:members,id',
        ]);
        
        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];
        $reportType = $validated['report_type'];
        
        // Base query
        $query = Attendance::with(['member', 'service'])
            ->whereHas('service', function($q) use ($startDate, $endDate) {
                $q->whereBetween('service_date', [$startDate, $endDate]);
            });
        
        // Apply additional filters
        if (isset($validated['service_type'])) {
            $query->whereHas('service', function($q) use ($validated) {
                $q->where('service_type', $validated['service_type']);
            });
        }
        
        if (isset($validated['member_id'])) {
            $query->where('member_id', $validated['member_id']);
        }
        
        // Generate different report types
        switch ($reportType) {
            case 'summary':
                // Summary report - attendance counts by date and service type
                $reportData = DB::table('attendances')
                    ->join('services', 'attendances.service_id', '=', 'services.id')
                    ->whereBetween('services.service_date', [$startDate, $endDate])
                    ->select(
                        DB::raw('DATE(services.service_date) as date'),
                        'services.service_type',
                        DB::raw('count(*) as attendance_count')
                    )
                    ->groupBy('date', 'services.service_type')
                    ->orderBy('date')
                    ->get();
                break;
                
            case 'detailed':
                // Detailed report - all attendance records
                $reportData = $query->orderBy('created_at', 'desc')->get();
                break;
                
            case 'member':
                // Member-focused report - attendance by member
                $reportData = DB::table('attendances')
                    ->join('services', 'attendances.service_id', '=', 'services.id')
                    ->join('members', 'attendances.member_id', '=', 'members.id')
                    ->whereBetween('services.service_date', [$startDate, $endDate])
                    ->select(
                        'members.id',
                        'members.first_name',
                        'members.last_name',
                        DB::raw('count(*) as attendance_count'),
                        DB::raw('MAX(services.service_date) as last_attendance')
                    )
                    ->groupBy('members.id', 'members.first_name', 'members.last_name')
                    ->orderBy('attendance_count', 'desc')
                    ->get();
                break;
                
            case 'service':
                // Service-focused report - attendance by service
                $reportData = DB::table('services')
                    ->leftJoin(DB::raw('(SELECT service_id, COUNT(*) as attendee_count FROM attendances GROUP BY service_id) as a'), 'services.id', '=', 'a.service_id')
                    ->whereBetween('services.service_date', [$startDate, $endDate])
                    ->select(
                        'services.id',
                        'services.name',
                        'services.service_date',
                        'services.service_type',
                        DB::raw('COALESCE(a.attendee_count, 0) as attendance_count')
                    )
                    ->orderBy('services.service_date', 'desc')
                    ->get();
                break;
        }
        
        $members = Member::orderBy('last_name')->get();
        $serviceTypes = Service::select('service_type')->distinct()->pluck('service_type');
        
        return view('attendances.report', compact(
            'reportData',
            'reportType',
            'startDate',
            'endDate',
            'members',
            'serviceTypes'
        ));
    }
}

