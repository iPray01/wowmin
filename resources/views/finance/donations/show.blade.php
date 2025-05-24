@extends('layouts.app')

@section('title', 'Donation Details')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-5" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('finance.donations.index') }}" class="text-gray-700 hover:text-blue-600">
                        Donations
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-500">Donation Details</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-start mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Donation Details</h2>
                <div class="flex space-x-3">
                    <a href="{{ route('finance.donations.edit', $donation) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    @if($donation->payment_status !== 'completed')
                    <form action="{{ route('finance.donations.process-payment', $donation) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-check mr-2"></i>
                            Process Payment
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('finance.donations.destroy', $donation) }}" method="POST" class="inline" 
                          onsubmit="return confirm('Are you sure you want to delete this donation?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-trash mr-2"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Donation Information -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Donation Information</h3>
                    <dl class="grid grid-cols-1 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Amount</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($donation->amount, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Donation Type</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $donation->donation_type)) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $donation->payment_method)) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $donation->payment_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($donation->payment_status) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Donation Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $donation->donation_date->format('F j, Y') }}</dd>
                        </div>
                        @if($donation->transaction_id)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Transaction ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $donation->transaction_id }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Donor Information -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Donor Information</h3>
                    <dl class="grid grid-cols-1 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Donor Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($donation->is_anonymous)
                                    <em>Anonymous Donation</em>
                                @else
                                    <a href="{{ route('members.show', $donation->member) }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $donation->member->full_name }}
                                    </a>
                                @endif
                            </dd>
                        </div>
                        @if($donation->campaign)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Campaign</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('finance.campaigns.show', $donation->campaign) }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $donation->campaign->name }}
                                </a>
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Recurring Information -->
                @if($donation->is_recurring)
                <div class="bg-gray-50 p-6 rounded-lg md:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recurring Donation Details</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Frequency</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($donation->recurring_frequency ?? 'Not set') }}</dd>
                        </div>
                        @if($donation->recurring_start_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $donation->recurring_start_date->format('F j, Y') }}</dd>
                        </div>
                        @endif
                        @if($donation->recurring_end_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">End Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $donation->recurring_end_date->format('F j, Y') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
                @endif

                <!-- Additional Information -->
                @if($donation->notes)
                <div class="bg-gray-50 p-6 rounded-lg md:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                    <div class="prose max-w-none">
                        <p class="text-sm text-gray-700">{{ $donation->notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 