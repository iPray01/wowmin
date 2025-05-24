<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Member;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\DonationReceipt;
use App\Services\PaymentService;
use Exception;
use Illuminate\Validation\ValidationException;

class DonationController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Donation::with(['member', 'campaign']);
        
        // Filter by member if provided
        if ($request->has('member_id') && $request->member_id) {
            $query->where('member_id', $request->member_id);
        }
        
        // Filter by campaign if provided
        if ($request->has('campaign_id') && $request->campaign_id) {
            $query->where('campaign_id', $request->campaign_id);
        }
        
        // Filter by payment method if provided
        if ($request->has('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }
        
        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('donation_date', [$request->start_date, $request->end_date]);
        } elseif ($request->has('start_date')) {
            $query->where('donation_date', '>=', $request->start_date);
        } elseif ($request->has('end_date')) {
            $query->where('donation_date', '<=', $request->end_date);
        }
        
        // Filter by amount range if provided
        if ($request->has('min_amount') && is_numeric($request->min_amount)) {
            $query->where('amount', '>=', $request->min_amount);
        }
        if ($request->has('max_amount') && is_numeric($request->max_amount)) {
            $query->where('amount', '<=', $request->max_amount);
        }
        
        // Search by transaction ID or notes
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', '%' . $search . '%')
                  ->orWhere('notes', 'like', '%' . $search . '%');
            });
        }
        
        $donations = $query->orderBy('donation_date', 'desc')
                          ->paginate(20);
        
        $members = Member::orderBy('last_name')->get();
        $campaigns = Campaign::orderBy('name')->get();
        $paymentMethods = [
            'cash' => 'Cash',
            'check' => 'Check',
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'bank_transfer' => 'Bank Transfer',
            'online_payment' => 'Online Payment',
            'mobile_payment' => 'Mobile Payment',
            'other' => 'Other'
        ];
        
        return view('finance.donations.index', compact('donations', 'members', 'campaigns', 'paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $members = Member::orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $campaigns = Campaign::where(function($query) {
                $query->where('end_date', '>=', now())
                      ->orWhereNull('end_date');
            })
            ->orderBy('name')
            ->get()
            ->map(function($campaign) {
                $campaign->progress = $campaign->total_donations / ($campaign->goal_amount ?: 1) * 100;
                return $campaign;
            });
                            
        $paymentMethods = [
            'cash' => 'Cash',
            'check' => 'Check',
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'bank_transfer' => 'Bank Transfer',
            'online_payment' => 'Online Payment',
            'mobile_payment' => 'Mobile Payment',
            'other' => 'Other'
        ];

        $recurringFrequencies = [
            'weekly' => 'Weekly',
            'biweekly' => 'Bi-weekly',
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'annually' => 'Annually'
        ];
        
        return view('finance.donations.create', compact(
            'members', 
            'campaigns', 
            'paymentMethods',
            'recurringFrequencies'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'amount' => 'required|numeric|min:0.01',
            'donation_date' => 'required|date',
            'payment_method' => 'required|string|in:' . implode(',', array_keys($this->getPaymentMethods())),
                'donation_type' => 'required|string|in:tithe,offering,special_offering,building_fund,missions,other',
            'transaction_id' => 'nullable|string|max:255|required_if:payment_method,credit_card,debit_card,bank_transfer,online_payment',
            'is_recurring' => 'boolean',
            'recurring_frequency' => 'required_if:is_recurring,1|nullable|string|in:weekly,biweekly,monthly,quarterly,annually',
            'recurring_start_date' => 'required_if:is_recurring,1|nullable|date',
            'recurring_end_date' => 'nullable|date|after:recurring_start_date',
            'is_anonymous' => 'boolean',
            'notes' => 'nullable|string|max:1000',
            'send_receipt' => 'boolean',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Create the donation
            $donation = Donation::create([
                'member_id' => $validated['member_id'],
                'campaign_id' => $validated['campaign_id'] ?? null,
                'amount' => $validated['amount'],
                'donation_date' => $validated['donation_date'],
                'payment_method' => $validated['payment_method'],
                    'donation_type' => $validated['donation_type'],
                'transaction_id' => $validated['transaction_id'] ?? null,
                'is_recurring' => $request->boolean('is_recurring'),
                'recurring_frequency' => $request->boolean('is_recurring') ? $validated['recurring_frequency'] : null,
                'recurring_start_date' => $request->boolean('is_recurring') ? $validated['recurring_start_date'] : null,
                'recurring_end_date' => $request->boolean('is_recurring') ? $validated['recurring_end_date'] : null,
                'is_anonymous' => $request->boolean('is_anonymous'),
                'notes' => $validated['notes'] ?? null,
                'recorded_by' => auth()->id(),
                    'payment_status' => 'completed', // Add default payment status
            ]);
            
            // Update campaign total if applicable
            if ($validated['campaign_id']) {
                $campaign = Campaign::findOrFail($validated['campaign_id']);
                $campaign->update([
                    'total_donations' => $campaign->donations()->sum('amount'),
                    'last_donation_date' => now(),
                ]);
            }

            // Send receipt if requested
            if ($request->boolean('send_receipt')) {
                $member = Member::findOrFail($validated['member_id']);
                if ($member->email) {
                        Mail::to($member->email)->queue(new DonationReceipt($donation));
                }
            }

            DB::commit();
            
            return redirect()->route('finance.donations.show', $donation)
                           ->with('success', 'Donation recorded successfully.');
                           
            } catch (Exception $e) {
            DB::rollBack();
                \Log::error('Failed to record donation: ' . $e->getMessage());
                \Log::error($e->getTraceAsString());
                return back()->withInput()
                            ->withErrors(['error' => 'Failed to record donation. Details: ' . $e->getMessage()]);
            }
        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        } catch (Exception $e) {
            \Log::error('Validation failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->withInput()
                        ->withErrors(['error' => 'Failed to validate donation data. Please try again.']);
        }
    }

    private function getPaymentMethods()
    {
        return [
            'cash' => 'Cash',
            'check' => 'Check',
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'bank_transfer' => 'Bank Transfer',
            'online_payment' => 'Online Payment',
            'mobile_payment' => 'Mobile Payment',
            'other' => 'Other'
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function show(Donation $donation)
    {
        $donation->load(['member', 'campaign']);
        
        return view('finance.donations.show', compact('donation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function edit(Donation $donation)
    {
        $members = Member::orderBy('last_name')->get();
        $campaigns = Campaign::orderBy('name')->get();
        
        $paymentMethods = [
            'cash' => 'Cash',
            'check' => 'Check',
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'bank_transfer' => 'Bank Transfer',
            'online_payment' => 'Online Payment',
            'mobile_payment' => 'Mobile Payment',
            'other' => 'Other'
        ];
        
        $recurringFrequencies = [
            'weekly' => 'Weekly',
            'biweekly' => 'Bi-weekly',
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'annually' => 'Annually'
        ];
        
        return view('finance.donations.edit', compact('donation', 'members', 'campaigns', 'paymentMethods', 'recurringFrequencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'amount' => 'required|numeric|min:0.01',
            'donation_date' => 'required|date',
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string|max:255',
            'is_recurring' => 'boolean',
            'recurring_frequency' => 'required_if:is_recurring,1|nullable|string',
            'is_anonymous' => 'boolean',
            'notes' => 'nullable|string',
        ]);
        
        // Store old campaign ID for comparison
        $oldCampaignId = $donation->campaign_id;
        
        // Update the donation
        $donation->update([
            'member_id' => $validated['member_id'],
            'campaign_id' => $validated['campaign_id'] ?? null,
            'amount' => $validated['amount'],
            'donation_date' => $validated['donation_date'],
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'] ?? null,
            'is_recurring' => $request->has('is_recurring'),
            'recurring_frequency' => $request->has('is_recurring') ? $validated['recurring_frequency'] : null,
            'is_anonymous' => $request->has('is_anonymous'),
            'notes' => $validated['notes'] ?? null,
        ]);
        
        // Update old campaign total if applicable
        if ($oldCampaignId) {
            $oldCampaign = Campaign::find($oldCampaignId);
            if ($oldCampaign) {
                $oldCampaign->update([
                    'total_donations' => $oldCampaign->donations()->sum('amount')
                ]);
            }
        }
        
        // Update new campaign total if applicable
        if ($validated['campaign_id'] && $validated['campaign_id'] != $oldCampaignId) {
            $newCampaign = Campaign::find($validated['campaign_id']);
            $newCampaign->update([
                'total_donations' => $newCampaign->donations()->sum('amount')
            ]);
        }
        
        return redirect()->route('finance.donations.show', $donation)
                         ->with('success', 'Donation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Donation $donation)
    {
        // Store campaign ID for updating totals after deletion
        $campaignId = $donation->campaign_id;
        
        // Soft delete the donation
        $donation->delete();
        
        // Update campaign total if applicable
        if ($campaignId) {
            $campaign = Campaign::find($campaignId);
            if ($campaign) {
                $campaign->update([
                    'total_donations' => $campaign->donations()->sum('amount')
                ]);
            }
        }
        
        return redirect()->route('finance.donations.index')
                         ->with('success', 'Donation deleted successfully.');
    }
    
    /**
     * Generate donation receipt.
     *
     * @param  \App\Models\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function generateReceipt(Donation $donation)
    {
        $donation->load(['member', 'campaign']);
        
        // Generate receipt number if not already set
        if (!$donation->receipt_number) {
            $receiptNumber = 'DON-' . date('Y') . '-' . str_pad($donation->id, 6, '0', STR_PAD_LEFT);
            $donation->update(['receipt_number' => $receiptNumber]);
        }
        
        return view('finance.donations.receipt', compact('donation'));
    }
    
    /**
     * Display donation statistics and reports.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function statistics(Request $request)
    {
        // Default to current year if no date range provided
        $startDate = $request->start_date ?? Carbon::now()->startOfYear()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d');
        
        // Total donations in the period
        $totalDonations = Donation::whereBetween('donation_date', [$startDate, $endDate])
                                 ->sum('amount');
        
        // Donations by payment method
        $donationsByMethod = DB::table('donations')
            ->whereBetween('donation_date', [$startDate, $endDate])
            ->select('payment_method', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get();
        
        // Donations by campaign
        $donationsByCampaign = DB::table('donations')
            ->join('campaigns', 'donations.campaign_id', '=', 'campaigns.id')
            ->whereBetween('donations.donation_date', [$startDate, $endDate])
            ->select('campaigns.name', DB::raw('SUM(donations.amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->groupBy('campaigns.name')
            ->get();
        
        // Donations by month (for trend analysis)
        $donationsByMonth = DB::table('donations')
            ->whereBetween('donation_date', [$startDate, $endDate])
            ->select(DB::raw('YEAR(donation_date) as year'), DB::raw('MONTH(donation_date) as month'), DB::raw('SUM(amount) as total_amount'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Top donors
        $topDonors = DB::table('donations')
            ->join('members', 'donations.member_id', '=', 'members.id')
            ->whereBetween('donations.donation_date', [$startDate, $endDate])
            ->select('members.id', 'members.first_name', 'members.last_name', DB::raw('SUM(donations.amount) as total_donated'))
            ->groupBy('members.id', 'members.first_name', 'members.last_name')
            ->orderBy('total_donated', 'desc')
            ->limit(10)
            ->get();
        
        // Recurring donations summary
        $recurringDonationsSummary = DB::table('donations')
            ->where('is_recurring', true)
            ->select('recurring_frequency', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total_amount'))
            ->groupBy('recurring_frequency')
            ->get();
        
        return view('finance.donations.statistics', compact(
            'totalDonations',
            'donationsByMethod',
            'donationsByCampaign',
            'donationsByMonth',
            'topDonors',
            'recurringDonationsSummary',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Process online payment for donation
     */
    public function processPayment(Request $request, Donation $donation)
    {
        try {
            $paymentIntent = $this->paymentService->createPaymentIntent($donation);
            
            return response()->json([
                'clientSecret' => $paymentIntent['clientSecret'],
                'donation' => $donation->load('member', 'campaign')
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle successful payment webhook
     */
    public function handlePaymentWebhook(Request $request)
    {
        try {
            $payload = $request->all();
            $paymentIntentId = $payload['data']['object']['id'];
            
            $this->paymentService->processSuccessfulPayment($paymentIntentId);
            
            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Process gift aid for a donation
     */
    public function processGiftAid(Donation $donation)
    {
        try {
            $giftAidAmount = $this->paymentService->processGiftAid($donation);
            
            return response()->json([
                'status' => 'success',
                'giftAidAmount' => $giftAidAmount
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get donation statistics and analytics
     */
    public function getStatistics(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfYear());
        $endDate = $request->get('end_date', now());

        $statistics = DB::table('donations')
            ->whereBetween('donation_date', [$startDate, $endDate])
            ->select(
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('SUM(gift_aid_amount) as total_gift_aid'),
                DB::raw('COUNT(*) as total_donations'),
                'donation_type',
                DB::raw('DATE_FORMAT(donation_date, "%Y-%m") as month')
            )
            ->groupBy('donation_type', 'month')
            ->orderBy('month')
            ->get();

        $campaignStats = Campaign::with(['donations' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('donation_date', [$startDate, $endDate]);
        }])
        ->get()
        ->map(function ($campaign) {
            return [
                'name' => $campaign->name,
                'target_amount' => $campaign->target_amount,
                'total_donations' => $campaign->donations->sum('amount'),
                'progress_percentage' => $campaign->progress_percentage
            ];
        });

        return response()->json([
            'statistics' => $statistics,
            'campaignStats' => $campaignStats
        ]);
    }
}

