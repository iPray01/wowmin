<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Pledge;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Campaign::query()
            ->withSum('donations', 'amount')
            ->withCount('pledges');
        
        // Apply status filter
        if ($request->filled('status')) {
            $now = now();
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true)
                          ->where('start_date', '<=', $now)
                          ->where(function ($q) use ($now) {
                              $q->whereNull('end_date')
                                ->orWhere('end_date', '>=', $now);
                });
                    break;
                case 'completed':
                    $query->where('end_date', '<', $now);
                    break;
                case 'upcoming':
                    $query->where('start_date', '>', $now);
                    break;
            }
        }
        
        // Apply date range filter
        if ($request->filled('date_range')) {
            $now = now();
            switch ($request->date_range) {
                case 'current':
                    $query->where('start_date', '<=', $now)
                          ->where(function ($q) use ($now) {
                              $q->whereNull('end_date')
                                ->orWhere('end_date', '>=', $now);
            });
                    break;
                case 'past':
                    $query->where('end_date', '<', $now);
                    break;
                case 'future':
                    $query->where('start_date', '>', $now);
                    break;
            }
        }
        
        $campaigns = $query->latest('start_date')->paginate(10);
        
        return view('finance.campaigns.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('finance.campaigns.create');
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
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'sometimes|nullable|boolean',
        ]);
        
        // Create the campaign
        $campaign = Campaign::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'target_amount' => $validated['target_amount'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => $request->boolean('is_active'),
        ]);
        
        return redirect()
            ->route('finance.campaigns.show', $campaign)
            ->with('success', 'Campaign created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function show(Campaign $campaign)
    {
        $campaign->load(['donations.member', 'pledges.member']);
        
        // Calculate progress percentage
        $progressPercentage = 0;
        if ($campaign->goal_amount > 0) {
            $progressPercentage = min(100, round(($campaign->total_donations / $campaign->goal_amount) * 100));
        }
        
        // Get recent donations
        $recentDonations = $campaign->donations()
                                  ->with('member')
                                  ->orderBy('donation_date', 'desc')
                                  ->limit(10)
                                  ->get();
        
        // Get recent pledges
        $recentPledges = $campaign->pledges()
                               ->with('member')
                               ->orderBy('pledge_date', 'desc')
                               ->limit(10)
                               ->get();
        
        // Get donation statistics
        $donationStats = [
            'total_amount' => $campaign->total_donations,
            'count' => $campaign->donations()->count(),
            'average' => $campaign->donations()->count() > 0 ? $campaign->total_donations / $campaign->donations()->count() : 0,
            'largest' => $campaign->donations()->max('amount') ?? 0,
        ];
        
        // Get pledge statistics
        $pledgeStats = [
            'total_amount' => $campaign->total_pledges,
            'count' => $campaign->pledges()->count(),
            'average' => $campaign->pledges()->count() > 0 ? $campaign->total_pledges / $campaign->pledges()->count() : 0,
            'fulfilled_amount' => $campaign->pledges()->sum('amount_fulfilled'),
            'unfulfilled_amount' => $campaign->total_pledges - $campaign->pledges()->sum('amount_fulfilled'),
        ];
        
        return view('finance.campaigns.show', compact(
            'campaign',
            'progressPercentage',
            'recentDonations',
            'recentPledges',
            'donationStats',
            'pledgeStats'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function edit(Campaign $campaign)
    {
        return view('finance.campaigns.edit', compact('campaign'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'sometimes|nullable|boolean',
        ]);
        
        // Update the campaign
        $campaign->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'target_amount' => $validated['target_amount'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => $request->boolean('is_active'),
        ]);
        
        return redirect()->route('finance.campaigns.show', $campaign)
                        ->with('success', 'Campaign updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy(Campaign $campaign)
    {
        // Check if campaign has donations or pledges
        if ($campaign->donations()->count() > 0 || $campaign->pledges()->count() > 0) {
            return redirect()->route('finance.campaigns.show', $campaign)
                             ->with('error', 'Cannot delete campaign with associated donations or pledges.');
        }
        
        // Soft delete the campaign
        $campaign->delete();
        
        return redirect()->route('finance.campaigns.index')
                         ->with('success', 'Campaign deleted successfully.');
    }
    
    /**
     * Display campaign progress and statistics.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function progress(Campaign $campaign)
    {
        // Get monthly data for the chart
        $monthlyData = DB::table('donations')
            ->select(
                DB::raw('DATE_FORMAT(donation_date, "%Y-%m") as month'),
                DB::raw('SUM(amount) as amount')
            )
            ->where('campaign_id', $campaign->id)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->month => $item->amount];
            })
            ->toArray();

        // Add pledge payments to monthly data
        DB::table('pledge_payments')
            ->join('pledges', 'pledges.id', '=', 'pledge_payments.pledge_id')
            ->where('pledges.campaign_id', $campaign->id)
            ->select(
                DB::raw('DATE_FORMAT(payment_date, "%Y-%m") as month'),
                DB::raw('SUM(pledge_payments.amount) as amount')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->each(function ($item) use (&$monthlyData) {
                if (!isset($monthlyData[$item->month])) {
                    $monthlyData[$item->month] = $item->amount;
                } else {
                    $monthlyData[$item->month] += $item->amount;
                }
            });

        // Sort by month
        ksort($monthlyData);

        // Find best month
        $bestMonth = [
            'month' => null,
            'amount' => 0
        ];

        foreach ($monthlyData as $month => $amount) {
            if ($amount > $bestMonth['amount']) {
                $bestMonth = [
                    'month' => Carbon::createFromFormat('Y-m', $month)->format('F Y'),
                    'amount' => $amount
                ];
            }
        }
        
        // Format data for Chart.js
        $chartData = [
            'labels' => array_map(function($month) {
                return Carbon::createFromFormat('Y-m', $month)->format('M Y');
            }, array_keys($monthlyData)),
            'values' => array_values($monthlyData)
        ];

        // Get recent activity
        $recentActivity = collect();

        // Add donations to activity
        $donations = $campaign->donations()
            ->with('member')
            ->select('*', DB::raw("'donation' as type"), DB::raw("'completed' as status"))
            ->latest('donation_date')
            ->limit(10)
            ->get();

        // Add pledge payments to activity
        $pledgePayments = DB::table('pledge_payments')
            ->join('pledges', 'pledges.id', '=', 'pledge_payments.pledge_id')
            ->join('members', 'pledges.member_id', '=', 'members.id')
            ->where('pledges.campaign_id', $campaign->id)
            ->select(
                'pledge_payments.created_at',
                'pledge_payments.amount',
                'members.id as member_id',
                'members.first_name',
                'members.last_name',
                DB::raw("'pledge_payment' as type"),
                'pledge_payments.status'
            )
            ->latest('pledge_payments.created_at')
            ->limit(10)
            ->get()
            ->map(function ($payment) {
                return (object) [
                    'created_at' => Carbon::parse($payment->created_at),
                    'amount' => $payment->amount,
                    'type' => $payment->type,
                    'status' => $payment->status,
                    'member' => (object) [
                        'id' => $payment->member_id,
                        'full_name' => $payment->first_name . ' ' . $payment->last_name
                    ]
                ];
            });

        // Merge and sort activity
        $recentActivity = $donations->concat($pledgePayments)
            ->sortByDesc('created_at')
            ->take(10);
        
        return view('finance.campaigns.progress', compact(
            'campaign',
            'monthlyData',
            'chartData',
            'bestMonth',
            'recentActivity'
        ));
    }
    
    /**
     * Display all campaigns with summary statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        // Active campaigns
        $activeCampaigns = Campaign::where(function($query) {
                                $query->where('end_date', '>=', now())
                                      ->orWhereNull('end_date');
                            })
                            ->where('is_active', true)
                            ->withCount(['donations', 'pledges'])
                            ->get();
        
        // Featured campaigns
        $featuredCampaigns = Campaign::where('is_featured', true)
                                  ->where('is_active', true)
                                  ->withCount(['donations', 'pledges'])
                                  ->get();
        
        // Recently completed campaigns
        $completedCampaigns = Campaign::where('end_date', '<', now())
                                   ->where('is_active', true)
                                   ->orderBy('end_date', 'desc')
                                   ->limit(5)
                                   ->withCount(['donations', 'pledges'])
                                   ->get();
        
        // Upcoming campaigns
        $upcomingCampaigns = Campaign::where('start_date', '>', now())
                                  ->where('is_active', true)
                                  ->orderBy('start_date', 'asc')
                                  ->limit(5)
                                  ->withCount(['donations', 'pledges'])
                                  ->get();
        
        // Calculate overall statistics
        $totalRaised = Campaign::sum('total_donations');
        $totalPledged = Campaign::sum('total_pledges');
        $campaignCount = Campaign::count();
        $activeCount = Campaign::where(function($query) {
                            $query->where('end_date', '>=', now())
                                  ->orWhereNull('end_date');
                        })
                        ->where('is_active', true)
                        ->count();
        
        return view('finance.campaigns.dashboard', compact(
            'activeCampaigns',
            'featuredCampaigns',
            'completedCampaigns',
            'upcomingCampaigns',
            'totalRaised',
            'totalPledged',
            'campaignCount',
            'activeCount'
        ));
    }
}

