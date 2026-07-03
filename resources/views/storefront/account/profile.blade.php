@extends('layouts.app')
@section('title', 'Profile - Shivara')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        @include('storefront.account._sidebar')

        <div class="flex-1 min-w-0 space-y-6">
            <h1 class="text-2xl font-display font-bold text-gray-900">My Profile</h1>

            <!-- Profile Info -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100">
                <h2 class="font-bold text-gray-900 mb-5">Personal Information</h2>
                <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-4">
                    @csrf @method('PUT')
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300 focus:bg-white">
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300 focus:bg-white">
                            @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                            <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm text-gray-500 cursor-not-allowed">
                            <p class="text-xs text-gray-400 mt-1">Email cannot be changed</p>
                        </div>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-dark text-white font-semibold rounded-xl hover:bg-dark-light transition text-sm">Save Changes</button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100">
                <h2 class="font-bold text-gray-900 mb-5">Change Password</h2>
                <form method="POST" action="{{ route('account.password.update') }}" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Current Password</label>
                        <input type="password" name="current_password" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300 focus:bg-white">
                        @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
                            <input type="password" name="password" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300 focus:bg-white">
                            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password</label>
                            <input type="password" name="password_confirmation" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300 focus:bg-white">
                        </div>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-dark text-white font-semibold rounded-xl hover:bg-dark-light transition text-sm">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
