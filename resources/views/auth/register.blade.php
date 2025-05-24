@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="bg-white py-8 px-6 shadow-xl rounded-lg sm:px-10">
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-extrabold text-metal-gold">Create your account</h2>
        <p class="mt-2 text-sm text-gray-600">Join our church management system</p>
    </div>
    
    @if (session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form class="space-y-6" action="{{ route('register') }}" method="POST">
        @csrf
        
        <!-- Full Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">
                Full Name
            </label>
            <div class="mt-1">
                <input id="name" name="name" type="text" autocomplete="name" required 
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 
                    focus:outline-none focus:ring-teal focus:border-teal sm:text-sm" 
                    value="{{ old('name') }}" placeholder="Enter your full name">
            </div>
            @error('name')
                <p class="mt-1 text-sm text-crimson">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
                Email address
            </label>
            <div class="mt-1">
                <input id="email" name="email" type="email" autocomplete="email" required 
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 
                    focus:outline-none focus:ring-teal focus:border-teal sm:text-sm" 
                    value="{{ old('email') }}" placeholder="Enter your email">
            </div>
            @error('email')
                <p class="mt-1 text-sm text-crimson">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
                Password
            </label>
            <div class="mt-1">
                <input id="password" name="password" type="password" autocomplete="new-password" required 
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 
                    focus:outline-none focus:ring-teal focus:border-teal sm:text-sm" 
                    placeholder="Create a password">
            </div>
            @error('password')
                <p class="mt-1 text-sm text-crimson">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                Confirm Password
            </label>
            <div class="mt-1">
                <input id="password_confirmation" name="password_confirmation" type="password" required 
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 
                    focus:outline-none focus:ring-teal focus:border-teal sm:text-sm" 
                    placeholder="Confirm your password">
            </div>
        </div>

        <div>
            <button type="submit" 
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium 
                text-white bg-teal hover:bg-teal-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal">
                Create Account
            </button>
        </div>
    </form>

    <div class="mt-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">Already have an account?</span>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('login') }}" 
                class="w-full flex justify-center py-2 px-4 border border-metal-gold rounded-md shadow-sm text-sm font-medium 
                text-metal-gold bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-metal-gold">
                Sign in to your account
            </a>
        </div>
    </div>
</div>
@endsection
