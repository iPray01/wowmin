@extends('layouts.app')

@section('title', 'Expense Statistics')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Expense Statistics</h1>
        <div class="space-x-2">
            <a href="{{ route('finance.expenses.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                Back to Expenses
            </a>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('finance.expenses.statistics') }}" method="GET" class="flex items-end space-x-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" id="start_date" 
                       value="{{ $startDate }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="end_date" id="end_date" 
                       value="{{ $endDate }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">
                Apply Filter
            </button>
        </form>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-sm font-medium text-gray-500">Total Expenses</h3>
            <p class="mt-2 text-3xl font-bold text-gray-900">GH₵{{ number_format($totalExpenses, 2) }}</p>
        </div>
    </div>

    <!-- Expenses by Category -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Expenses by Category</h2>
            <div class="space-y-4">
                @foreach($expensesByCategory as $category)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-600">{{ $category->name }}</span>
                            <span class="text-sm font-medium text-gray-900">GH₵{{ number_format($category->total_amount, 2) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ ($category->total_amount / $totalExpenses) * 100 }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $category->count }} transactions</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Expenses by Department -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Expenses by Department</h2>
            <div class="space-y-4">
                @foreach($expensesByDepartment as $department)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-600">{{ $department->name }}</span>
                            <span class="text-sm font-medium text-gray-900">GH₵{{ number_format($department->total_amount, 2) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-teal-600 h-2 rounded-full" style="width: {{ ($department->total_amount / $totalExpenses) * 100 }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $department->count }} transactions</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Expenses by Payment Method and Monthly Trend -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Expenses by Payment Method -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Expenses by Payment Method</h2>
            <div class="space-y-4">
                @foreach($expensesByMethod as $method)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-600">{{ str_replace('_', ' ', ucfirst($method->payment_method)) }}</span>
                            <span class="text-sm font-medium text-gray-900">GH₵{{ number_format($method->total_amount, 2) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ ($method->total_amount / $totalExpenses) * 100 }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $method->count }} transactions</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Monthly Trend -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Monthly Trend</h2>
            <div class="space-y-4">
                @foreach($expensesByMonth as $month)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-600">{{ Carbon\Carbon::createFromDate($month->year, $month->month, 1)->format('F Y') }}</span>
                            <span class="text-sm font-medium text-gray-900">GH₵{{ number_format($month->total_amount, 2) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($month->total_amount / $totalExpenses) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top Vendors -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Top Vendors</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Number of Transactions</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Average per Transaction</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($topVendors as $vendor)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $vendor->vendor }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">GH₵{{ number_format($vendor->total_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $vendor->count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">GH₵{{ number_format($vendor->total_amount / $vendor->count, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 