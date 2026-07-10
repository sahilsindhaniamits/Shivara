@extends('layouts.admin')
@section('page_title', 'Edit Coupon')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-xl font-bold text-slate-900 mb-6">Edit: {{ $coupon->code }}</h1>
    <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}" class="bg-white p-6 rounded-2xl border border-slate-100 space-y-4">
        @csrf @method('PUT')
        <div class="grid sm:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Code *</label>
            <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm uppercase focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
            <input type="text" name="description" value="{{ old('description', $coupon->description) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
        </div>
        <div class="grid sm:grid-cols-3 gap-4">
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Type</label>
            <select name="type" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm"><option value="percentage" {{ $coupon->type=='percentage'?'selected':'' }}>Percentage</option><option value="flat" {{ $coupon->type=='flat'?'selected':'' }}>Flat</option><option value="free_shipping" {{ $coupon->type=='free_shipping'?'selected':'' }}>Free Shipping</option></select></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Value</label>
            <input type="number" name="value" value="{{ old('value', $coupon->value) }}" step="0.01" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Max Discount</label>
            <input type="number" name="max_discount" value="{{ old('max_discount', $coupon->max_discount) }}" step="0.01" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
        </div>
        <div class="grid sm:grid-cols-3 gap-4">
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Min Order</label>
            <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" step="0.01" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Usage Limit</label>
            <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Per User</label>
            <input type="number" name="per_user_limit" value="{{ old('per_user_limit', $coupon->per_user_limit) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Start</label>
            <input type="date" name="start_date" value="{{ old('start_date', $coupon->start_date->format('Y-m-d')) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1">End <span class="text-slate-400 font-normal">(optional)</span></label>
            <input type="date" name="end_date" value="{{ old('end_date', $coupon->end_date ? $coupon->end_date->format('Y-m-d') : '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"><p class="text-[10px] text-slate-400 mt-1">Leave blank for no expiry</p></div>
        </div>
        <div class="flex flex-wrap gap-6">
            <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ $coupon->is_active ? 'checked' : '' }} class="rounded text-brand-600"><span class="text-sm text-slate-700">Active</span></label>
            <label class="flex items-center gap-2"><input type="checkbox" name="show_as_popup" value="1" {{ $coupon->show_as_popup ? 'checked' : '' }} class="rounded text-brand-600"><span class="text-sm text-slate-700">Show as Popup on Website</span></label>
            <label class="flex items-center gap-2"><input type="checkbox" name="auto_apply" value="1" {{ $coupon->auto_apply ? 'checked' : '' }} class="rounded text-green-600"><span class="text-sm text-slate-700">Auto Apply to Cart</span></label>
        </div>
        <p class="text-[10px] text-slate-400 -mt-2">Auto Apply: Coupon will be automatically applied to customer's cart if their order meets the minimum amount.</p>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-6 py-3 text-white font-semibold rounded-xl hover:opacity-90 transition text-sm" style="background-color:#c06d22">Update</button>
            <a href="{{ route('admin.coupons.index') }}" class="px-6 py-3 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
