<?php

namespace App\Http\Controllers;

use App\Models\Pledge;
use App\Models\PledgePayment;
use App\Models\Member;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PledgeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Pledge::with(['member', 'campaign']);
        
        // Filter by member if provided
        if ($request->has('member_id') && $request->member_id) {
            $query->where('member_id', $request->member_id);
        }
        
        // Filter by campaign if provided
        if ($request->has('campaign_id') && $request->campaign_id) {
            $query->where('campaign_id', $request->campaign_id);
        }
        
        // Filter by status (completed, partially fulfilled, unfulfilled)
        if ($request->has('status')) {
            switch ($request->status) {
                case 'completed':
                    $query->where(function($q) {
                        $q->where('status', 'completed')
                          ->orWhereRaw('amount_fulfilled >= amount');
                    });
                    break;
                case 'partially':
                    $query->whereRaw('amount_fulfilled > 0 AND amount_fulfilled < amount')
                         ->where('status', 'active');
                    break;
                case 'unfulfilled':
                    $query->where('amount_fulfilled', 0)
                         ->where('status', 'active');
                    break;
            }
        }
        
        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('pledge_date', [$request->start_date, $request->end_date]);
        } elseif ($request->has('start_date')) {
            $query->where('pledge_date', '>=', $request->start_date);
        } elseif ($request->has('end_date')) {
            $query->where('pledge_date', '<=', $request->end_date);
        }
        
        // Filter by amount range if provided
        if ($request->has('min_amount') && is_numeric($request->min_amount)) {
            $query->where('amount', '>=', $request->min_amount);
        }
        if ($request->has('max_amount') && is_numeric($request->max_amount)) {
            $query->where('amount', '<=', $request->max_amount);
        }
        
        $pledges = $query->orderBy('pledge_date', 'desc')
                        ->paginate(20);
        
        // Update status for any pledges that should be marked as completed
        foreach ($pledges as $pledge) {
            if ($pledge->amount_fulfilled >= $pledge->amount && $pledge->status !== 'completed') {
                $pledge->update(['status' => 'completed']);
            }
        }
        
        $members = Member::orderBy('last_name')->get();
        $campaigns = Campaign::orderBy('name')->get();
        $statuses = [
            'all' => 'All Pledges',
            'completed' => 'Completed',
            'partially' => 'Partially Fulfilled',
            'unfulfilled' => 'Unfulfilled'
        ];
        
        return view('finance.pledges.index', compact('pledges', 'members', 'campaigns', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $members = Member::orderBy('last_name')->get();
        $campaigns = Campaign::where('end_date', '>=', now())
                            ->orWhereNull('end_date')
                            ->orderBy('name')
                            ->get();
                            
        $paymentSchedules = [
            'weekly' => 'Weekly',
            'biweekly' => 'Bi-weekly',
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'annually' => 'Annually',
            'custom' => 'Custom'
        ];
        
        return view('finance.pledges.create', compact('members', 'campaigns', 'paymentSchedules'));
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
            'campaign_id' => 'nullable|exists:campaigns,id',
            'amount' => 'required|numeric|min:0.01',
            'pledge_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'payment_schedule' => 'required|string',
            'notes' => 'nullable|string',
            'initial_payment' => 'nullable|numeric|min:0',
        ]);
        
        // Create the pledge
        $pledge = Pledge::create([
            'member_id' => $validated['member_id'],
            'campaign_id' => $validated['campaign_id'] ?? null,
            'amount' => $validated['amount'],
            'amount_fulfilled' => $validated['initial_payment'] ?? 0,
            'pledge_date' => $validated['pledge_date'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'payment_schedule' => $validated['payment_schedule'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'active',
        ]);
        
        // Create initial payment record if provided
        if (isset($validated['initial_payment']) && $validated['initial_payment'] > 0) {
            PledgePayment::create([
                'pledge_id' => $pledge->id,
                'amount' => $validated['initial_payment'],
                'payment_date' => now(),
                'payment_method' => 'cash', // Default method, can be changed later
                'notes' => 'Initial payment',
            ]);
        }
        
        // Update campaign total pledges if applicable
        if ($validated['campaign_id']) {
            $campaign = Campaign::find($validated['campaign_id']);
            $campaign->update([
                'total_pledges' => $campaign->pledges()->sum('amount')
            ]);
        }
        
        return redirect()->route('finance.pledges.show', $pledge)
                         ->with('success', 'Pledge created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pledge  $pledge
     * @return \Illuminate\Http\Response
     */
    public function show(Pledge $pledge)
    {
        $pledge->load(['member', 'campaign', 'payments' => function($query) {
            $query->orderBy('payment_date', 'desc');
        }]);
        
        return view('finance.pledges.show', [
            'pledge' => $pledge,
            'remainingBalance' => $pledge->remaining_balance,
            'completionPercentage' => $pledge->completion_percentage,
            'isOverdue' => $pledge->is_overdue,
            'nextPaymentDate' => $pledge->next_payment_date,
            'paymentMethods' => [
                'cash' => 'Cash',
                'bank_transfer' => 'Bank Transfer',
                'check' => 'Check',
                'mobile_money' => 'Mobile Money',
                'card' => 'Card Payment'
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pledge  $pledge
     * @return \Illuminate\Http\Response
     */
    public function edit(Pledge $pledge)
    {
        $members = Member::orderBy('last_name')->get();
        $campaigns = Campaign::orderBy('name')->get();
        
        $paymentSchedules = [
            'weekly' => 'Weekly',
            'biweekly' => 'Bi-weekly',
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'annually' => 'Annually',
            'custom' => 'Custom'
        ];
        
        $statuses = [
            'active' => 'Active',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];
        
        return view('finance.pledges.edit', compact('pledge', 'members', 'campaigns', 'paymentSchedules', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pledge  $pledge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pledge $pledge)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'amount' => 'required|numeric|min:0.01',
            'pledge_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'payment_schedule' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|string|in:active,completed,cancelled',
        ]);
        
        DB::beginTransaction();
        try {
            $oldAmount = $pledge->amount;
        $oldCampaignId = $pledge->campaign_id;
        
            $pledge->fill([
            'member_id' => $validated['member_id'],
            'campaign_id' => $validated['campaign_id'] ?? null,
            'amount' => $validated['amount'],
            'pledge_date' => $validated['pledge_date'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'payment_schedule' => $validated['payment_schedule'],
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'],
        ]);
        
            // If amount is reduced and it's now less than or equal to amount_fulfilled, mark as completed
            if ($validated['amount'] <= $pledge->amount_fulfilled) {
                $pledge->status = 'completed';
            }
            // If amount is increased and it was previously completed, check if it should be active
            elseif ($validated['amount'] > $oldAmount && $pledge->status === 'completed') {
                if ($pledge->amount_fulfilled < $validated['amount']) {
                    $pledge->status = 'active';
                }
            }

            $pledge->save();

            // Update old campaign totals if campaign changed
            if ($oldCampaignId && $oldCampaignId !== $validated['campaign_id']) {
            $oldCampaign = Campaign::find($oldCampaignId);
            if ($oldCampaign) {
                    $oldCampaign->updateTotals();
            }
        }
        
            // Update new campaign totals
            if ($pledge->campaign_id) {
                $pledge->campaign->updateTotals();
            }

            DB::commit();
        return redirect()->route('finance.pledges.show', $pledge)
                         ->with('success', 'Pledge updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                        ->with('error', 'Failed to update pledge: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pledge  $pledge
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pledge $pledge)
    {
        DB::beginTransaction();
        try {
            $campaignId = $pledge->campaign_id;
            
        // Check if pledge has payments
        if ($pledge->payments()->count() > 0) {
            return redirect()->route('finance.pledges.show', $pledge)
                             ->with('error', 'Cannot delete pledge with associated payments.');
        }
        
        // Soft delete the pledge
        $pledge->delete();
        
        // Update campaign total if applicable
        if ($campaignId) {
            $campaign = Campaign::find($campaignId);
            if ($campaign) {
                    $campaign->updateTotals();
            }
        }
        
            DB::commit();
        return redirect()->route('finance.pledges.index')
                         ->with('success', 'Pledge deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete pledge: ' . $e->getMessage());
        }
    }
    
    /**
     * Record a payment for a pledge.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pledge  $pledge
     * @return \Illuminate\Http\Response
     */
    public function recordPayment(Request $request, Pledge $pledge)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        
        try {
            $payment = $pledge->recordPayment(
                $validated['amount'],
                $validated['payment_date'],
                $validated['payment_method'],
                $validated['notes'] ?? null
            );
        
        return redirect()->route('finance.pledges.show', $pledge)
                         ->with('success', 'Payment recorded successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }
    
    /**
     * Delete a payment for a pledge.
     *
     * @param  \App\Models\Pledge  $pledge
     * @param  \App\Models\PledgePayment  $payment
     * @return \Illuminate\Http\Response
     */
    public function deletePayment(Pledge $pledge, PledgePayment $payment)
    {
        // Verify the payment belongs to this pledge
        if ($payment->pledge_id !== $pledge->id) {
            return redirect()->route('finance.pledges.show', $pledge)
                             ->with('error', 'Payment does not belong to this pledge.');
        }
        
        // Delete the payment
        $payment->delete();
        
        // Update pledge amount fulfilled
        $totalPaid = $pledge->payments()->sum('amount');
        $pledge->update([
            'amount_fulfilled' => $totalPaid
        ]);
        
        // Update pledge status if needed
        if ($totalPaid < $pledge->amount && $pledge->status === 'completed') {
            $pledge->update(['status' => 'active']);
        }
        
        return redirect()->route('finance.pledges.show', $pledge)
                         ->with('success', 'Payment deleted successfully.');
    }
    
    /**
     * Generate payment schedule for a pledge.
     *
     * @param  \App\Models\Pledge  $pledge
     * @return \Illuminate\Http\Response
     */
    public function paymentSchedule(Pledge $pledge)
    {
        $pledge->load(['member', 'campaign', 'payments']);
        
        // Calculate remaining amount
        $remainingAmount = max(0, $pledge->amount - $pledge->amount_fulfilled);
        
        // Generate payment schedule based on pledge settings
        $schedule = [];
        if ($pledge->end_date && $pledge->payment_schedule !== 'custom') {
            $startDate = Carbon::parse($pledge->start_date);
            $endDate = Carbon::parse($pledge->end_date);
            
            // Determine interval based on payment schedule
            $interval = null;
            switch ($pledge->payment_schedule) {
                case 'weekly':
                    $interval = 'week';
                    break;
                case 'biweekly':
                    $interval = 'weeks';
                    $intervalCount = 2;
                    break;
                case 'monthly':
                    $interval = 'month';
                    break;
                case 'quarterly':
                    $interval = 'months';
                    $intervalCount = 3;
                    break;
                case 'annually':
                    $interval = 'year';
                    break;
            }
            
            if ($interval) {
                $currentDate = clone $startDate;
                $paymentNumber = 1;
                
                while ($currentDate <= $endDate) {
                    // Skip first payment if initial payment was made
                    if ($paymentNumber === 1 && $pledge->amount_fulfilled > 0) {
                        // Move to next payment date
                        if ($interval === 'weeks') {
                            $currentDate->addWeeks($intervalCount);
                        } elseif ($interval === 'months') {
                            $currentDate->addMonths($intervalCount);
                        } else {
                            $currentDate->add($interval);
                        }
                        $paymentNumber++;
                        continue;
                    }
                    
                    // Add to schedule
                    $schedule[] = [
                        'payment_number' => $paymentNumber,
                        'due_date' => clone $currentDate,
                        'amount' => $this->calculatePaymentAmount($pledge, count($schedule) + 1),
                        'status' => $this->getPaymentStatus($pledge, $currentDate)
                    ];
                    
                    // Move to next payment date
                    if ($interval === 'weeks') {
                        $currentDate->addWeeks($intervalCount);
                    } elseif ($interval === 'months') {
                        $currentDate->addMonths($intervalCount);
                    } else {
                        $currentDate->add($interval);
                    }
                    
                    $paymentNumber++;
                }
            }
        }
        
        return view('finance.pledges.schedule', compact('pledge', 'remainingAmount', 'schedule'));
    }
    
    /**
     * Calculate payment amount for a specific payment in the schedule.
     *
     * @param  \App\Models\Pledge  $pledge
     * @param  int  $paymentNumber
     * @return float
     */
    private function calculatePaymentAmount(Pledge $pledge, $paymentNumber)
    {
        // Simple calculation - divide remaining amount by remaining payments
        $startDate = Carbon::parse($pledge->start_date);
        $endDate = Carbon::parse($pledge->end_date);
        
        // Determine total number of payments based on schedule
        $totalPayments = 0;
        switch ($pledge->payment_schedule) {
            case 'weekly':
                $totalPayments = $startDate->diffInWeeks($endDate) + 1;
                break;
            case 'biweekly':
                $totalPayments = floor($startDate->diffInWeeks($endDate) / 2) + 1;
                break;
            case 'monthly':
                $totalPayments = $startDate->diffInMonths($endDate) + 1;
                break;
            case 'quarterly':
                $totalPayments = floor($startDate->diffInMonths($endDate) / 3) + 1;
                break;
            case 'annually':
                $totalPayments = $startDate->diffInYears($endDate) + 1;
                break;
            default:
                $totalPayments = 1;
        }
        
        // If this is the last payment, return the remaining amount
        if ($paymentNumber >= $totalPayments) {
            return max(0, $pledge->amount - $pledge->amount_fulfilled);
        }
        
        // Otherwise, calculate the regular payment amount
        $remainingPayments = $totalPayments - $paymentNumber + 1;
        $remainingAmount = max(0, $pledge->amount - $pledge->amount_fulfilled);
        
        return round($remainingAmount / $remainingPayments, 2);
    }
    
    /**
     * Get the status of a payment based on its due date.
     *
     * @param  \App\Models\Pledge  $pledge
     * @param  \Carbon\Carbon  $dueDate
     * @return string
     */
    private function getPaymentStatus(Pledge $pledge, Carbon $dueDate)
    {
        $now = Carbon::now();
        
        // Check if there's a payment matching this date
        $payment = $pledge->payments()
                         ->whereDate('payment_date', $dueDate->format('Y-m-d'))
                         ->first();
        
        if ($payment) {
            return 'paid';
        } elseif ($dueDate->isPast()) {
            return 'overdue';
        } else {
            return 'upcoming';
        }
    }

    /**
     * Update pledge status based on fulfillment
     *
     * @param  \App\Models\Pledge  $pledge
     * @return void
     */
    private function updatePledgeStatus(Pledge $pledge)
    {
        $totalPaid = $pledge->payments()->sum('amount');
        
        if ($totalPaid >= $pledge->amount && $pledge->status !== 'completed') {
            $pledge->update([
                'status' => 'completed',
                'amount_fulfilled' => $totalPaid
            ]);
        } elseif ($totalPaid < $pledge->amount && $pledge->status === 'completed') {
            $pledge->update([
                'status' => 'active',
                'amount_fulfilled' => $totalPaid
            ]);
        } else {
            $pledge->update(['amount_fulfilled' => $totalPaid]);
        }
    }
}

