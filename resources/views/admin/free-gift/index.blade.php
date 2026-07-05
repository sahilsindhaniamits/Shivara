@extends('layouts.admin')
@section('page_title', 'Free Gift')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-xl font-bold text-slate-900 mb-2">Free Gift Settings</h1>
    <p class="text-sm text-slate-500 mb-6">Select a product that will be automatically added as a free gift when the customer's cart reaches the minimum amount.</p>

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
                <label class="flex items-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition {{ $settings['enabled'] != 'true' ? 'border-red-500 bg-red-50' : 'border-slate-200' }}">
                    <input type="radio" name="enabled" value="false" {{ $settings['enabled'] != 'true' ? 'checked' : '' }} class="text-red-600">
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

        <!-- Select Gift Product -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Select Gift Product *</label>
            <p class="text-xs text-slate-400 mb-2">This product will show as a free gift in the cart. Its name and image will be used automatically.</p>
            <select name="product_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white">
                <option value="">-- Select a product --</option>
                @foreach($products as $p)
                <option value="{{ $p->id }}" {{ $settings['product_id'] == $p->id ? 'selected' : '' }}>{{ $p->name }} (₹{{ number_format($p->selling_price) }})</option>
                @endforeach
            </select>
        </div>

        <!-- Preview of selected product -->
        @if($settings['product_id'])
        @php $giftProduct = \App\Models\Product::with('primaryImage')->find($settings['product_id']); @endphp
        @if($giftProduct)
        <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 flex items-center gap-4">
            <div class="w-16 h-16 bg-white rounded-lg overflow-hidden border border-purple-100 shrink-0">
                <img src="{{ $giftProduct->primaryImage?->url ?? 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=100&h=100&fit=crop' }}" class="w-full h-full object-cover">
            </div>
            <div>
                <p class="text-xs font-bold text-purple-500 uppercase tracking-wider">Current Gift Product</p>
                <p class="text-sm font-semibold text-purple-700 mt-0.5">{{ $giftProduct->name }}</p>
                <p class="text-xs text-purple-500">Will show as FREE in cart when subtotal ≥ ₹{{ number_format($settings['threshold']) }}</p>
            </div>
        </div>
        @endif
        @endif

        <button type="submit" class="px-6 py-3 text-white" style="background-color:#c06d22 font-bold rounded-xl hover:opacity-90 transition text-sm">Save Settings</button>
    </form>
</div>
@endsection
