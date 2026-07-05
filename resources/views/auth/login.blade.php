@extends('layouts.app')
@section('title', 'Login - Shivara')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <span class="text-3xl font-display font-bold text-espresso-700">SHIVARA</span>
            </a>
            <p class="text-gray-500 text-sm mt-2">Welcome back. Sign in to continue.</p>
        </div>

        <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300 focus:bg-white transition placeholder:text-gray-400"
                           placeholder="you@example.com">
                    @error('email')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300 focus:bg-white transition placeholder:text-gray-400"
                           placeholder="••••••••">
                    @error('password')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full px-6 py-3.5 text-white font-semibold rounded-xl hover:opacity-90" style="background-color:#2C2418 transition shadow-lg">
                    Sign In
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-gray-500 mt-6">
            Don't have an account? <a href="{{ route('register') }}" class="text-brand-600 font-semibold hover:text-brand-700">Create one</a>
        </p>
    </div>
</div>
@endsection
