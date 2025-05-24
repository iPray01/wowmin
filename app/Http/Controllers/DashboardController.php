<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Family;
use App\Models\Attendance;
use App\Models\Donation;
use App\Models\Campaign;
use App\Models\Service;
use App\Models\PrayerRequest;
use App\Models\Pledge;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get key metrics
        $totalMembers = Member::count();
        $activeMembers = Member::where('is_active', true)->count();
        $totalFamilies = Family::count();
        $newMembersThisMonth = Member::where('created_at', '>=', now()->startOfMonth())->count();
        
        // Get financial metrics
        $totalDonationsThisYear = Donation::whereYear('donation_date', now()->year)->sum('amount');
        $totalDonationsThisMonth = Donation::whereYear('donation_date', now()->year)
            ->whereMonth('donation_date', now()->month)
            ->sum('amount');
        
        // Get active campaigns
        $activeCampaigns = Campaign::with(['donations'])
            ->where(function($query) {
            $query->where('end_date', '>=', now())
                  ->orWhereNull('end_date');
        })->where('is_active', true)
          ->limit(5)
          ->get();
        
        // Calculate overall campaign progress
        $totalTargetAmount = $activeCampaigns->sum('target_amount');
        $totalReceived = $activeCampaigns->sum(function($campaign) {
            return $campaign->total_fulfilled + $campaign->donations->sum('amount');
        });
        $campaignProgress = $totalTargetAmount > 0 
            ? round(($totalReceived / $totalTargetAmount) * 100, 1)
            : 0;
        
        // Get recent attendance
        $recentServices = Service::with(['attendances'])
            ->orderBy('service_date', 'desc')
            ->limit(5)
            ->get();
        
        // Calculate last service attendance
        $lastServiceAttendance = $recentServices->first() 
            ? $recentServices->first()->attendances->count() 
            : 0;
        
        // Calculate average attendance
        $servicesCount = Service::where('service_date', '>=', now()->subMonths(3))->count();
        $averageAttendance = $servicesCount > 0 
            ? Attendance::whereHas('service', function($query) {
                $query->where('service_date', '>=', now()->subMonths(3));
              })->count() / $servicesCount 
            : 0;
        
        // Get recent prayer requests
        $recentPrayerRequests = PrayerRequest::with('member')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get upcoming birthdays
        $upcomingBirthdays = Member::whereRaw('DAYOFYEAR(date_of_birth) >= DAYOFYEAR(CURDATE())')
            ->whereRaw('DAYOFYEAR(date_of_birth) <= DAYOFYEAR(CURDATE() + INTERVAL 30 DAY)')
            ->orderByRaw('DAYOFYEAR(date_of_birth)')
            ->limit(5)
            ->get()
            ->map(function($member) {
                $birthday = $member->date_of_birth->setYear(now()->year);
                if ($birthday->isPast()) {
                    $birthday->addYear();
                }
                $member->next_birthday = $birthday;
                $member->days_until = (int)$birthday->diffInDays(now());
                return $member;
            });
        
        // Get attendance trend
        $attendanceTrend = Service::select(
                DB::raw('DATE(service_date) as date'),
                DB::raw('COUNT(DISTINCT attendances.id) as count')
            )
            ->leftJoin('attendances', 'services.id', '=', 'attendances.service_id')
            ->where('service_date', '>=', now()->subMonths(3))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Get donation trend
        $donationTrend = Donation::select(
                DB::raw('DATE(donation_date) as date'),
                DB::raw('SUM(amount) as amount')
            )
            ->where('donation_date', '>=', now()->subMonths(3))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get recent activities
        $recentDonations = Donation::with('member')
            ->latest('donation_date')
            ->limit(5)
            ->get()
            ->map(function($donation) {
                return (object)[
                    'id' => $donation->id,
                    'type' => 'donation',
                    'title' => 'Donation of GH₵' . number_format($donation->amount, 2),
                    'member' => $donation->member,
                    'date' => $donation->donation_date,
                    'created_at' => $donation->created_at
                ];
            });

        $recentAttendances = Attendance::with(['member', 'service'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($attendance) {
                return (object)[
                    'id' => $attendance->id,
                    'type' => 'attendance',
                    'title' => 'Attended ' . $attendance->service->name,
                    'member' => $attendance->member,
                    'date' => $attendance->service->service_date,
                    'created_at' => $attendance->created_at
                ];
            });

        $recentPledges = Pledge::with('member')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($pledge) {
                return (object)[
                    'id' => $pledge->id,
                    'type' => 'pledge',
                    'title' => 'Pledged GH₵' . number_format($pledge->amount, 2),
                    'member' => $pledge->member,
                    'date' => $pledge->pledge_date,
                    'created_at' => $pledge->created_at
                ];
            });

        // Combine and sort activities
        $recentActivities = $recentDonations->concat($recentAttendances)
            ->concat($recentPledges)
            ->sortByDesc('created_at')
            ->take(5);
        
        return view('dashboard', compact(
            'totalMembers',
            'activeMembers',
            'totalFamilies',
            'newMembersThisMonth',
            'totalDonationsThisYear',
            'totalDonationsThisMonth',
            'activeCampaigns',
            'campaignProgress',
            'recentServices',
            'lastServiceAttendance',
            'averageAttendance',
            'recentPrayerRequests',
            'upcomingBirthdays',
            'attendanceTrend',
            'donationTrend',
            'recentActivities'
        ));
    }
} 