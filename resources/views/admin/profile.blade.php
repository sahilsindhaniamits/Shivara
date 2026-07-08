@extends('layouts.admin')
@section('title', 'Profile Settings - Admin')
@section('page_title', 'Profile Settings')

@section('content')
<div class="max-w-2xl space-y-6">

    <!-- Profile Info -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <h2 class="text-base font-bold text-gray-900 mb-5 pb-4 border-b border-gray-100">Profile Information</h2>
        <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Name</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Phone</label>
                    <input type="text" name="phone" value="{{ auth()->user()->phone }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Email</label>
                <input type="email" name="email" value="{{ auth()->user()->email }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
            </div>
            <button type="submit" class="px-6 py-3 text-white font-bold text-sm rounded-xl hover:opacity-90 transition" style="background-color:#c06d22">Save Changes</button>
        </form>
    </div>

    <!-- Change Password -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <h2 class="text-base font-bold text-gray-900 mb-5 pb-4 border-b border-gray-100">Change Password</h2>
        <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Current Password</label>
                <input type="password" name="current_password" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">New Password</label>
                    <input type="password" name="new_password" required minlength="6" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
            </div>
            <button type="submit" class="px-6 py-3 text-white font-bold text-sm rounded-xl hover:opacity-90 transition" style="background-color:#2C2418">Change Password</button>
        </form>
    </div>
</div>
@endsection
