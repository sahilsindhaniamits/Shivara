@extends('layouts.admin')
@section('page_title', 'Free Gift')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-xl font-bold text-slate-900 mb-2">Free Gift Settings</h1>
    <p class="text-sm text-slate-500 mb-6">Configure a free product that's automatically added to the cart when a customer reaches the minimum order amount.</p>

    <form method="POST" action="{{ route('admin.free-gift.update') }}" class="bg-white p-6 rounded-2xl border border-slate-100 space-y-5">
        @csrf

        <!-- Enable/Disable -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition {{ $settings['enabled'] == 'true' ? 'border-green-500 bg-green-50' : 'border-slate-200' }}">
                    <input type="radio" name="enabled" value="true" {{ $settings['enabled'] == 'true' ? 'checked' : '' }} class="text-green-600">
                    <span class="text-sm font-medium text-slate-700">Enabled</span>
                </label>
                <label class="flex items-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition {{ $settings['enabled'] == 'false' ? 'border-red-500 bg-red-50' : 'border-slate-200' }}">
                    <input type="radio" name="enabled" value="false" {{ $settings['enabled'] == 'false' ? 'checked' : '' }} class="text-red-600">
                    <span class="text-sm font-medium text-slate-700">Disabled</span>
                </label>
            </div>
        </div>

        <!-- Threshold -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Minimum Cart Amount (₹)</label>
            <p class="text-xs text-slate-400 mb-2">Free gift will be added when cart reaches this amount</p>
            <input type="number" name="threshold" value="{{ $settings['threshold'] }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white">
        </div>

        <!-- Gift Product Name -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Gift Product Name</label>
            <p class="text-xs text-slate-400 mb-2">This name will show in the cart</p>
            <input type="text" name="name" value="{{ $settings['name'] }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white">
        </div>

        <!-- Gift Image URL -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Gift Product Image URL</label>
            <p class="text-xs text-slate-400 mb-2">Paste an image URL (or leave blank for default)</p>
            <input type="text" name="image" value="{{ $settings['image'] }}" placeholder="https://..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white">
        </div>

        <!-- Link to product (optional) -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Link to Product (optional)</label>
            <select name="product_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none">
                <option value="">-- No linked product --</option>
                @foreach($products as $p)
                <option value="{{ $p->id }}" {{ $settings['product_id'] == $p->id ? 'selected' : '' }}>{{ $p->name }} (₹{{ number_format($p->selling_price) }})</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="px-6 py-3 bg-brand-600 text-white font-bold rounded-xl hover:bg-brand-700 transition text-sm">Save Settings</button>
    </form>
</div>
@endsection
