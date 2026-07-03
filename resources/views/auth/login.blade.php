@extends('layouts.app')
@section('title', 'Login - Shivara')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <h1 class="text-3xl font-serif tracking-wide text-secondary">SHIVARA</h1>
            </a>
            <p class="text-muted text-sm mt-2">Welcome back. Sign in to your account.</p>
        </div>

        <div class="bg-white p-8 rounded-2xl border border-border shadow-sm">
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-4 py-3 rounded-xl border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="you@example.com">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="••••••••">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-primary">
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full px-6 py-3 bg-primary text-white font-medium rounded-xl hover:bg-primary-dark transition">
                    Sign In
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-gray-500 mt-6">
            Don't have an account? <a href="{{ route('register') }}" class="text-primary font-medium hover:underline">Create one</a>
        </p>
    </div>
</div>
@endsection
