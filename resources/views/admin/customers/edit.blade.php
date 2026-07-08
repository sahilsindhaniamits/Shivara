@extends('layouts.admin')
@section('title', 'Edit Customer - Admin')
@section('page_title', 'Edit Customer')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit: {{ $customer->name }}</h1>
        <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition">Back</a>
    </div>

    <form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="space-y-6">
        @csrf @method('PUT')

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Email *</label>
                <input type="email" name="email" value="{{ old('email', $customer->email) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">New Password <span class="text-gray-400">(leave blank to keep current)</span></label>
                <input type="password" name="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" placeholder="Enter new password">
            </div>
            <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ $customer->is_active ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-orange-500 focus:ring-orange-200">
                <span class="text-sm font-medium text-gray-700">Active (can login & place orders)</span>
            </label>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-8 py-3 text-white font-bold text-sm rounded-xl hover:opacity-90 transition shadow-sm" style="background-color:#B7925C">Update Customer</button>
            <a href="{{ route('admin.customers.index') }}" class="px-6 py-3 border border-gray-200 text-gray-600 font-medium text-sm rounded-xl hover:bg-gray-50 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
