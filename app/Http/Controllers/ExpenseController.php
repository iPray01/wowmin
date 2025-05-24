<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Expense::with(['category', 'department']);
        
        // Filter by category if provided
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filter by department if provided
        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }
        
        // Filter by payment method if provided
        if ($request->has('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }
        
        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('expense_date', [$request->start_date, $request->end_date]);
        } elseif ($request->has('start_date')) {
            $query->where('expense_date', '>=', $request->start_date);
        } elseif ($request->has('end_date')) {
            $query->where('expense_date', '<=', $request->end_date);
        }
        
        // Filter by amount range if provided
        if ($request->has('min_amount') && is_numeric($request->min_amount)) {
            $query->where('amount', '>=', $request->min_amount);
        }
        if ($request->has('max_amount') && is_numeric($request->max_amount)) {
            $query->where('amount', '<=', $request->max_amount);
        }
        
        // Search by description or vendor
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', '%' . $search . '%')
                  ->orWhere('vendor', 'like', '%' . $search . '%')
                  ->orWhere('reference_number', 'like', '%' . $search . '%');
            });
        }
        
        $expenses = $query->orderBy('expense_date', 'desc')
                         ->paginate(20);
        
        $categories = ExpenseCategory::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
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
        
        return view('finance.expenses.index', compact('expenses', 'categories', 'departments', 'paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ExpenseCategory::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        
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
        
        return view('finance.expenses.create', compact('categories', 'departments', 'paymentMethods'));
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
            'category_id' => 'required|exists:expense_categories,id',
            'department_id' => 'required|exists:departments,id',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'description' => 'required|string|max:255',
            'vendor' => 'nullable|string|max:255',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string|max:255',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'notes' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurring_frequency' => 'required_if:is_recurring,1|nullable|string',
            'recurring_end_date' => 'required_if:is_recurring,1|nullable|date|after:expense_date',
        ]);
        
        // Handle receipt upload
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }
        
        // Create the expense
        $expense = Expense::create([
            'category_id' => $validated['category_id'],
            'department_id' => $validated['department_id'],
            'amount' => $validated['amount'],
            'expense_date' => $validated['expense_date'],
            'description' => $validated['description'],
            'vendor' => $validated['vendor'] ?? null,
            'payment_method' => $validated['payment_method'],
            'reference_number' => $validated['reference_number'] ?? null,
            'receipt_path' => $receiptPath,
            'notes' => $validated['notes'] ?? null,
            'is_recurring' => $request->has('is_recurring'),
            'recurring_frequency' => $request->has('is_recurring') ? $validated['recurring_frequency'] : null,
            'recurring_end_date' => $request->has('is_recurring') ? $validated['recurring_end_date'] : null,
        ]);
        
        // Create recurring expenses if needed
        if ($request->has('is_recurring') && isset($validated['recurring_end_date'])) {
            $this->createRecurringExpenses($expense, $validated['recurring_frequency'], $validated['recurring_end_date']);
        }
        
        return redirect()->route('finance.expenses.show', $expense)
                         ->with('success', 'Expense recorded successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        $expense->load(['category', 'department']);
        
        return view('finance.expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        
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
        
        return view('finance.expenses.edit', compact('expense', 'categories', 'departments', 'paymentMethods', 'recurringFrequencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'department_id' => 'required|exists:departments,id',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'description' => 'required|string|max:255',
            'vendor' => 'nullable|string|max:255',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string|max:255',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'notes' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurring_frequency' => 'required_if:is_recurring,1|nullable|string',
            'recurring_end_date' => 'required_if:is_recurring,1|nullable|date|after:expense_date',
        ]);
        
        // Handle receipt upload
        $receiptPath = $expense->receipt_path;
        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($expense->receipt_path) {
                Storage::disk('public')->delete($expense->receipt_path);
            }
            
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }
        
        // Update the expense
        $expense->update([
            'category_id' => $validated['category_id'],
            'department_id' => $validated['department_id'],
            'amount' => $validated['amount'],
            'expense_date' => $validated['expense_date'],
            'description' => $validated['description'],
            'vendor' => $validated['vendor'] ?? null,
            'payment_method' => $validated['payment_method'],
            'reference_number' => $validated['reference_number'] ?? null,
            'receipt_path' => $receiptPath,
            'notes' => $validated['notes'] ?? null,
            'is_recurring' => $request->has('is_recurring'),
            'recurring_frequency' => $request->has('is_recurring') ? $validated['recurring_frequency'] : null,
            'recurring_end_date' => $request->has('is_recurring') ? $validated['recurring_end_date'] : null,
        ]);
        
        return redirect()->route('finance.expenses.show', $expense)
                         ->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        // Delete receipt if exists
        if ($expense->receipt_path) {
            Storage::disk('public')->delete($expense->receipt_path);
        }
        
        // Soft delete the expense
        $expense->delete();
        
        return redirect()->route('finance.expenses.index')
                         ->with('success', 'Expense deleted successfully.');
    }
    
    /**
     * Download receipt for an expense.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function downloadReceipt(Expense $expense)
    {
        if (!$expense->receipt_path) {
            return redirect()->route('finance.expenses.show', $expense)
                             ->with('error', 'No receipt available for this expense.');
        }
        
        return Storage::disk('public')->download($expense->receipt_path);
    }
    
    /**
     * Display expense statistics and reports.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function statistics(Request $request)
    {
        // Default to current year if no date range provided
        $startDate = $request->start_date ?? Carbon::now()->startOfYear()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d');
        
        // Total expenses in the period
        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
                               ->sum('amount');
        
        // Expenses by category
        $expensesByCategory = DB::table('expenses')
            ->join('expense_categories', 'expenses.category_id', '=', 'expense_categories.id')
            ->whereBetween('expenses.expense_date', [$startDate, $endDate])
            ->select('expense_categories.name', DB::raw('SUM(expenses.amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->groupBy('expense_categories.name')
            ->get();
        
        // Expenses by department
        $expensesByDepartment = DB::table('expenses')
            ->join('departments', 'expenses.department_id', '=', 'departments.id')
            ->whereBetween('expenses.expense_date', [$startDate, $endDate])
            ->select('departments.name', DB::raw('SUM(expenses.amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->groupBy('departments.name')
            ->get();
        
        // Expenses by payment method
        $expensesByMethod = DB::table('expenses')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->select('payment_method', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get();
        
        // Expenses by month (for trend analysis)
        $expensesByMonth = DB::table('expenses')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->select(DB::raw('YEAR(expense_date) as year'), DB::raw('MONTH(expense_date) as month'), DB::raw('SUM(amount) as total_amount'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Top expense vendors
        $topVendors = DB::table('expenses')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->whereNotNull('vendor')
            ->select('vendor', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->groupBy('vendor')
            ->orderBy('total_amount', 'desc')
            ->limit(10)
            ->get();
        
        return view('finance.expenses.statistics', compact(
            'totalExpenses',
            'expensesByCategory',
            'expensesByDepartment',
            'expensesByMethod',
            'expensesByMonth',
            'topVendors',
            'startDate',
            'endDate'
        ));
    }
    
    /**
     * Generate budget report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function budgetReport(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'report_type' => 'required|in:department,category,monthly',
        ]);
        
        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];
        $reportType = $validated['report_type'];
        
        // Generate different report types
        switch ($reportType) {
            case 'department':
                // Department budget report
                $reportData = DB::table('departments')
                    ->leftJoin(DB::raw("(SELECT department_id, SUM(amount) as total_spent FROM expenses WHERE expense_date BETWEEN '$startDate' AND '$endDate' GROUP BY department_id) as e"), 'departments.id', '=', 'e.department_id')
                    ->select(
                        'departments.id',
                        'departments.name',
                        'departments.budget',
                        DB::raw('COALESCE(e.total_spent, 0) as total_spent'),
                        DB::raw('departments.budget - COALESCE(e.total_spent, 0) as remaining_budget'),
                        DB::raw('CASE WHEN departments.budget > 0 THEN (COALESCE(e.total_spent, 0) / departments.budget) * 100 ELSE 0 END as budget_utilization_percentage')
                    )
                    ->orderBy('departments.name')
                    ->get();
                break;
                
            case 'category':
                // Category budget report
                $reportData = DB::table('expense_categories')
                    ->leftJoin(DB::raw("(SELECT category_id, SUM(amount) as total_spent FROM expenses WHERE expense_date BETWEEN '$startDate' AND '$endDate' GROUP BY category_id) as e"), 'expense_categories.id', '=', 'e.category_id')
                    ->select(
                        'expense_categories.id',
                        'expense_categories.name',
                        'expense_categories.budget',
                        DB::raw('COALESCE(e.total_spent, 0) as total_spent'),
                        DB::raw('expense_categories.budget - COALESCE(e.total_spent, 0) as remaining_budget'),
                        DB::raw('CASE WHEN expense_categories.budget > 0 THEN (COALESCE(e.total_spent, 0) / expense_categories.budget) * 100 ELSE 0 END as budget_utilization_percentage')
                    )
                    ->orderBy('expense_categories.name')
                    ->get();
                break;
                
            case 'monthly':
                // Monthly budget report
                $reportData = [];
                $startMonth = Carbon::parse($startDate)->startOfMonth();
                $endMonth = Carbon::parse($endDate)->endOfMonth();
                $currentMonth = clone $startMonth;
                
                while ($currentMonth <= $endMonth) {
                    $monthStart = $currentMonth->format('Y-m-d');
                    $monthEnd = $currentMonth->copy()->endOfMonth()->format('Y-m-d');
                    
                    // Get expenses for this month by department
                    $departmentExpenses = DB::table('expenses')
                        ->join('departments', 'expenses.department_id', '=', 'departments.id')
                        ->whereBetween('expenses.expense_date', [$monthStart, $monthEnd])
                        ->select('departments.name', DB::raw('SUM(expenses.amount) as total_spent'))
                        ->groupBy('departments.name')
                        ->get()
                        ->keyBy('name')
                        ->map(function ($item) {
                            return $item->total_spent;
                        })
                        ->toArray();
                    
                    // Get expenses for this month by category
                    $categoryExpenses = DB::table('expenses')
                        ->join('expense_categories', 'expenses.category_id', '=', 'expense_categories.id')
                        ->whereBetween('expenses.expense_date', [$monthStart, $monthEnd])
                        ->select('expense_categories.name', DB::raw('SUM(expenses.amount) as total_spent'))
                        ->groupBy('expense_categories.name')
                        ->get()
                        ->keyBy('name')
                        ->map(function ($item) {
                            return $item->total_spent;
                        })
                        ->toArray();
                    
                    $reportData[] = [
                        'month' => $currentMonth->format('F Y'),
                        'total_spent' => array_sum($departmentExpenses),
                        'department_expenses' => $departmentExpenses,
                        'category_expenses' => $categoryExpenses,
                    ];
                    
                    $currentMonth->addMonth();
                }
                break;
        }
        
        $departments = Department::orderBy('name')->get();
        $categories = ExpenseCategory::orderBy('name')->get();
        
        return view('finance.expenses.budget-report', compact(
            'reportData',
            'reportType',
            'startDate',
            'endDate',
            'departments',
            'categories'
        ));
    }
    
    /**
     * Create recurring expenses based on a pattern.
     *
     * @param  \App\Models\Expense  $expense
     * @param  string  $frequency
     * @param  string  $endDate
     * @return void
     */
    private function createRecurringExpenses(Expense $expense, $frequency, $endDate)
    {
        $startDate = Carbon::parse($expense->expense_date);
        $endDate = Carbon::parse($endDate);
        
        $currentDate = clone $startDate;
        
        // Determine interval based on frequency
        switch ($frequency) {
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
            default:
                return; // Invalid frequency
        }
        
        // Move to the next date (skip the first one since it's already created)
        if ($interval === 'weeks') {
            $currentDate->addWeeks($intervalCount);
        } elseif ($interval === 'months') {
            $currentDate->addMonths($intervalCount);
        } else {
            $currentDate->add($interval);
        }
        
        // Create recurring expenses until end date
        while ($currentDate <= $endDate) {
            Expense::create([
                'category_id' => $expense->category_id,
                'department_id' => $expense->department_id,
                'amount' => $expense->amount,
                'expense_date' => $currentDate,
                'description' => $expense->description,
                'vendor' => $expense->vendor,
                'payment_method' => $expense->payment_method,
                'reference_number' => $expense->reference_number,
                'receipt_path' => null, // Don't copy receipt for recurring expenses
                'notes' => $expense->notes,
                'is_recurring' => false, // Only the original expense is marked as recurring
                'recurring_frequency' => null,
                'recurring_end_date' => null,
            ]);
            
            // Move to the next date
            if ($interval === 'weeks') {
                $currentDate->addWeeks($intervalCount);
            } elseif ($interval === 'months') {
                $currentDate->addMonths($intervalCount);
            } else {
                $currentDate->add($interval);
            }
        }
    }
}

