@extends('layouts.app')
@section('title', 'Verify Email - Shivara')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <span class="text-3xl font-display font-bold text-dark">SHIVARA</span>
            </a>
        </div>

        <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Check your email</h2>
                <p class="text-gray-500 text-sm">We sent a 6-digit code to <span class="font-medium text-gray-700">{{ auth()->user()->email }}</span></p>
            </div>

            <form method="POST" action="{{ route('verify.email') }}" class="space-y-5">
                @csrf
                <div>
                    <input type="text" name="otp" required maxlength="6" autofocus
                           class="w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-xl text-center text-2xl font-bold tracking-[0.5em] focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300 focus:bg-white transition"
                           placeholder="------" inputmode="numeric" pattern="[0-9]{6}">
                    @error('otp')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="w-full px-6 py-3.5 text-white font-semibold rounded-xl hover:opacity-90 transition shadow-lg" style="background-color:#2C2418">Verify Email</button>
            </form>

            <div class="text-center mt-5 pt-5 border-t border-gray-100">
                <form method="POST" action="{{ route('verify.email.resend') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-espresso-700 hover:text-espresso-500 transition">Resend OTP</button>
                </form>
                <div class="mt-3"><a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-gray-600">Skip for now →</a></div>
            </div>
        </div>
    </div>
</div>
@endsection
