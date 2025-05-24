@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
<div class="bg-white py-8 px-6 shadow-xl rounded-lg sm:px-10">
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-extrabold text-metal-gold">Verify Your Email</h2>
        <p class="mt-2 text-sm text-gray-600">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">A new verification link has been sent to your email address.</span>
        </div>
    @endif

    <form class="space-y-6" method="POST" action="{{ route('verification.send') }}">
        @csrf
        <div>
            <button type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium 
                text-white bg-teal hover:bg-teal-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal">
                Resend Verification Email
            </button>
        </div>
    </form>

    <div class="mt-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">or</span>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button type="submit"
                class="w-full flex justify-center py-2 px-4 border border-metal-gold rounded-md shadow-sm text-sm font-medium 
                text-metal-gold bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-metal-gold">
                Log Out
            </button>
        </form>
    </div>
</div>
@endsection 