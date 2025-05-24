@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="bg-white py-8 px-6 shadow-xl rounded-lg sm:px-10">
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-extrabold text-metal-gold">Welcome back</h2>
        <p class="mt-2 text-sm text-gray-600">Sign in to your account to continue</p>
    </div>
    
    @if (session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form class="space-y-6" action="{{ route('login') }}" method="POST">
        @csrf
        
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
                <input id="password" name="password" type="password" autocomplete="current-password" required 
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 
                    focus:outline-none focus:ring-teal focus:border-teal sm:text-sm" 
                    placeholder="Enter your password">
            </div>
            @error('password')
                <p class="mt-1 text-sm text-crimson">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox" 
                    class="h-4 w-4 text-teal focus:ring-teal border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-gray-900">
                    Remember me
                </label>
            </div>

            <div class="text-sm">
                <a href="{{ route('password.request') }}" class="font-medium text-teal hover:text-teal-dark">
                    Forgot password?
                </a>
            </div>
        </div>

        <div>
            <button type="submit" 
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium 
                text-white bg-teal hover:bg-teal-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal">
                Sign in
            </button>
        </div>
    </form>

    <div class="mt-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">Don't have an account?</span>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('register') }}" 
                class="w-full flex justify-center py-2 px-4 border border-metal-gold rounded-md shadow-sm text-sm font-medium 
                text-metal-gold bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-metal-gold">
                Create new account
            </a>
        </div>
    </div>
</div>
@endsection
