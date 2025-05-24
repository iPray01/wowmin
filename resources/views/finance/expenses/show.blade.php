@extends('layouts.app')

@section('title', 'View Expense')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">View Expense</h1>
        <div class="space-x-2">
            <a href="{{ route('finance.expenses.edit', $expense) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-4 rounded">
                Edit Expense
            </a>
            <a href="{{ route('finance.expenses.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                Back to List
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Basic Information</h2>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Amount</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">GH₵{{ number_format($expense->amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date</dt>
                        <dd class="mt-1 text-gray-900">{{ $expense->expense_date->format('F d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                        <dd class="mt-1 text-gray-900">{{ $expense->category->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Department</dt>
                        <dd class="mt-1 text-gray-900">{{ $expense->department->name }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Additional Details -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Additional Details</h2>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                        <dd class="mt-1 text-gray-900">{{ str_replace('_', ' ', ucfirst($expense->payment_method)) }}</dd>
                    </div>
                    @if($expense->vendor)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Vendor</dt>
                        <dd class="mt-1 text-gray-900">{{ $expense->vendor }}</dd>
                    </div>
                    @endif
                    @if($expense->reference_number)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Reference Number</dt>
                        <dd class="mt-1 text-gray-900">{{ $expense->reference_number }}</dd>
                    </div>
                    @endif
                    @if($expense->is_recurring)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Recurring Details</dt>
                        <dd class="mt-1 text-gray-900">
                            {{ ucfirst($expense->recurring_frequency) }} until {{ $expense->recurring_end_date->format('F d, Y') }}
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Description and Notes -->
        <div class="mt-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Description & Notes</h2>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-700 whitespace-pre-wrap">{{ $expense->description }}</p>
                @if($expense->notes)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Additional Notes</h3>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $expense->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Receipt -->
        @if($expense->receipt_path)
        <div class="mt-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Receipt</h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('finance.expenses.download-receipt', $expense) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-download mr-2"></i>
                    Download Receipt
                </a>
            </div>
        </div>
        @endif

        <!-- Delete Button -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <form action="{{ route('finance.expenses.destroy', $expense) }}" method="POST" class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this expense? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Delete Expense
                </button>
            </form>
        </div>
    </div>
</div>
@endsection 