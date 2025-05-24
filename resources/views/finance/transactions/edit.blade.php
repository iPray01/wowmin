@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Edit Transaction</h1>
            <p class="text-gray-600">Update transaction details</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('finance.transactions.update', $transaction) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Transaction Type</label>
                        <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="income" {{ $transaction->type === 'income' ? 'selected' : '' }}>Income</option>
                            <option value="expense" {{ $transaction->type === 'expense' ? 'selected' : '' }}>Expense</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₵</span>
                            </div>
                            <input type="number" step="0.01" name="amount" id="amount" value="{{ $transaction->amount }}" class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="date" id="date" value="{{ $transaction->date->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="tithe" {{ $transaction->category === 'tithe' ? 'selected' : '' }}>Tithe</option>
                            <option value="offering" {{ $transaction->category === 'offering' ? 'selected' : '' }}>Offering</option>
                            <option value="harvest" {{ $transaction->category === 'harvest' ? 'selected' : '' }}>Harvest</option>
                            <option value="donation" {{ $transaction->category === 'donation' ? 'selected' : '' }}>Donation</option>
                            <option value="utilities" {{ $transaction->category === 'utilities' ? 'selected' : '' }}>Utilities</option>
                            <option value="maintenance" {{ $transaction->category === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="salary" {{ $transaction->category === 'salary' ? 'selected' : '' }}>Salary</option>
                            <option value="other" {{ $transaction->category === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="cash" {{ $transaction->payment_method === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ $transaction->payment_method === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="mobile_money" {{ $transaction->payment_method === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="check" {{ $transaction->payment_method === 'check' ? 'selected' : '' }}>Check</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $transaction->description }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('finance.transactions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Update Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
