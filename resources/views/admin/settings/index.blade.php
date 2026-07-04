@extends('layouts.admin')
@section('page_title', 'Settings')

@section('content')
<div class="max-w-3xl space-y-6">
    <h1 class="text-xl font-bold text-slate-900">Store Settings</h1>

    <!-- Free Gift Configuration -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100">
        <h2 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
            Free Gift Settings
        </h2>
        <p class="text-sm text-slate-500 mb-4">Configure a free product that's automatically added to cart when customer reaches a minimum order amount.</p>
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
            <p class="text-sm text-amber-800"><strong>How to configure:</strong> Edit the file <code class="bg-amber-100 px-1.5 py-0.5 rounded text-xs">config/shivara.php</code> on your server:</p>
            <pre class="mt-2 text-xs text-amber-700 bg-amber-100 p-3 rounded-lg overflow-x-auto">
'free_gift_enabled' => true,        // true or false
'free_gift_threshold' => 1499,      // Minimum cart amount
'free_gift_name' => 'Your Product', // Name shown in cart
'free_gift_image' => '/url.jpg',    // Image URL</pre>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div class="p-4 bg-slate-50 rounded-xl">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status</p>
                <p class="text-sm font-semibold mt-1 {{ config('shivara.free_gift_enabled') ? 'text-green-600' : 'text-red-600' }}">{{ config('shivara.free_gift_enabled') ? '✓ Enabled' : '✗ Disabled' }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Threshold</p>
                <p class="text-sm font-semibold mt-1 text-slate-700">₹{{ number_format(config('shivara.free_gift_threshold')) }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Gift Product</p>
                <p class="text-sm font-semibold mt-1 text-slate-700">{{ config('shivara.free_gift_name') }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Free Shipping At</p>
                <p class="text-sm font-semibold mt-1 text-slate-700">₹{{ number_format(config('shivara.free_shipping_threshold')) }}</p>
            </div>
        </div>
    </div>

    <!-- Product Page Banners -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100">
        <h2 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
            Product Page Offers
        </h2>
        <p class="text-sm text-slate-500 mb-4">Offer banners on product pages are automatically pulled from your <strong>active coupons</strong>. To change them:</p>
        <div class="space-y-3">
            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center text-green-600 font-bold text-xs">1</span>
                <p class="text-sm text-slate-700">Go to <a href="{{ route('admin.coupons.index') }}" class="text-brand-600 font-semibold hover:underline">Coupons</a> → Create/Edit a coupon</p>
            </div>
            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center text-green-600 font-bold text-xs">2</span>
                <p class="text-sm text-slate-700">Set <strong>is_active = ON</strong> and valid dates</p>
            </div>
            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center text-green-600 font-bold text-xs">3</span>
                <p class="text-sm text-slate-700">The coupon will automatically appear on product pages & side cart</p>
            </div>
            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                <span class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center text-red-600 font-bold text-xs">!</span>
                <p class="text-sm text-slate-700">To <strong>hide</strong> offers → Deactivate all coupons or set expired dates</p>
            </div>
        </div>
    </div>

    <!-- Homepage Banner Slider -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100">
        <h2 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Homepage Banners
        </h2>
        <p class="text-sm text-slate-500 mb-4">The homepage slider banners are currently hardcoded. To change banner images/text, edit the file:</p>
        <code class="text-xs bg-slate-100 px-3 py-2 rounded-lg block text-slate-600">resources/views/storefront/home.blade.php</code>
        <p class="text-sm text-slate-500 mt-3">Or manage dynamic banners via <a href="{{ route('admin.banners.index') }}" class="text-brand-600 font-semibold hover:underline">Banners →</a></p>
    </div>
</div>
@endsection
