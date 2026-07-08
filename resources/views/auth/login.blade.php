@extends('layouts.app')
@section('title', 'Login - Shivara')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <img src="/public/shivaralogo.png" alt="Shivara" class="h-12 mx-auto" onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                <span style="display:none" class="text-3xl font-display font-bold text-espresso-700">SHIVARA</span>
            </a>
            <p class="text-gray-500 text-sm mt-3">Login with your WhatsApp number</p>
        </div>

        <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
            <form method="POST" action="{{ route('login.phone') }}">
                @csrf

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">WhatsApp Number</label>
                    <div class="flex gap-2">
                        <div class="flex items-center px-3.5 py-3.5 border border-gray-200 rounded-xl text-sm text-gray-600 font-medium" style="background-color:#f9fafb;">
                            +91
                        </div>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required maxlength="10" pattern="[0-9]{10}" inputmode="numeric"
                               class="flex-1 px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gold-200 focus:border-gold-300 focus:bg-white transition placeholder:text-gray-400"
                               placeholder="Enter 10-digit number" autofocus>
                    </div>
                    @error('phone')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Your Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gold-200 focus:border-gold-300 focus:bg-white transition placeholder:text-gray-400"
                           placeholder="Enter your name (for new accounts)">
                    <p class="text-[11px] text-gray-400 mt-1">Required only for first time. Leave blank if already registered.</p>
                </div>

                @error('login')<p class="text-red-500 text-xs mb-3">{{ $message }}</p>@enderror

                <button type="submit"
                        class="w-full px-6 py-3.5 text-white font-semibold rounded-xl hover:opacity-90 transition shadow-lg flex items-center justify-center gap-2"
                        style="background-color:#2C2418">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Continue with WhatsApp
                </button>
            </form>
        </div>

        <p class="text-center text-[11px] text-gray-400 mt-6">
            By continuing, you agree to our <a href="{{ route('terms') }}" class="underline">Terms</a> & <a href="{{ route('privacy-policy') }}" class="underline">Privacy Policy</a>
        </p>
    </div>
</div>
@endsection
