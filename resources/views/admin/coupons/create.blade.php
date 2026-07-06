@extends('layouts.admin')
@section('page_title', 'Add Coupon')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-xl font-bold text-slate-900 mb-6">Create Coupon</h1>
    <form method="POST" action="{{ route('admin.coupons.store') }}" class="bg-white p-6 rounded-2xl border border-slate-100 space-y-4">
        @csrf
        <div class="grid sm:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Code *</label><input type="text" name="code" value="{{ old('code') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm uppercase focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white" placeholder="e.g. SAVE20">@error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Description</label><input type="text" name="description" value="{{ old('description') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
        </div>
        <div class="grid sm:grid-cols-3 gap-4">
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Type *</label><select name="type" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none"><option value="percentage">Percentage</option><option value="flat">Flat Amount</option><option value="free_shipping">Free Shipping</option></select></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Value *</label><input type="number" name="value" value="{{ old('value') }}" step="0.01" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Max Discount (₹)</label><input type="number" name="max_discount" value="{{ old('max_discount') }}" step="0.01" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
        </div>
        <div class="grid sm:grid-cols-3 gap-4">
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Min Order (₹)</label><input type="number" name="min_order_amount" value="{{ old('min_order_amount') }}" step="0.01" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Usage Limit</label><input type="number" name="usage_limit" value="{{ old('usage_limit') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white" placeholder="Unlimited"></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Per User Limit</label><input type="number" name="per_user_limit" value="{{ old('per_user_limit', 1) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Start Date *</label><input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1">End Date *</label><input type="date" name="end_date" value="{{ old('end_date') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
        </div>
        <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" checked class="rounded text-brand-600"><span class="text-sm text-slate-700">Active</span></label>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-6 py-3 text-white font-semibold rounded-xl hover:opacity-90 transition text-sm" style="background-color:#c06d22">Create Coupon</button>
            <a href="{{ route('admin.coupons.index') }}" class="px-6 py-3 border border-slate-200 text-slate-600 font-medium rounded-xl hover:bg-slate-50 transition text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
