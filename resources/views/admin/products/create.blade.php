@extends('layouts.admin')
@section('title', 'Add Product - Admin')
@section('page_title', 'Add New Product')

@section('content')
<div class="max-w-5xl" x-data="{ mrp: '', sp: '' }">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add New Product</h1>
            <p class="text-sm text-gray-500 mt-0.5">Fill in the product details. After saving, you can add variants.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    @if($errors->any())
    <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
        <p class="font-semibold mb-1">Please fix these errors:</p>
        <ul class="list-disc pl-5 space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: rgba(192,109,34,0.1)">
                    <svg class="w-4 h-4" style="color:#c06d22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h2 class="text-base font-bold text-gray-900">Basic Information</h2>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Kumkumadi Tailam Face Oil" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Short Description</label>
                    <input type="text" name="short_description" value="{{ old('short_description') }}" placeholder="Brief one-liner shown on product cards" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Full Description</label>
                    <textarea name="description" rows="4" placeholder="Detailed product description..." class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Category</label>
                    <select name="category_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" placeholder="e.g. SHV-001" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Return/Exchange Policy</label>
                    <select name="return_policy" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                        <option value="">Select Policy</option>
                        @foreach(['Easy 7 days return','Easy 5 days return','Exchange Only within 5 days','Easy 3 days return','Exchange Only within 3 days','No Return/Exchange'] as $policy)
                        <option value="{{ $policy }}" {{ old('return_policy') == $policy ? 'selected' : '' }}>{{ $policy }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Pricing & Inventory -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: rgba(16,163,127,0.1)">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h2 class="text-base font-bold text-gray-900">Pricing & Inventory</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">MRP (₹) *</label>
                    <input type="number" name="mrp" value="{{ old('mrp') }}" step="0.01" required placeholder="999" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" x-model="mrp">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Selling Price (₹) *</label>
                    <input type="number" name="selling_price" value="{{ old('selling_price') }}" step="0.01" required placeholder="699" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" x-model="sp">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Stock *</label>
                    <input type="number" name="stock" value="{{ old('stock', 50) }}" required placeholder="50" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
            </div>
            <div x-show="mrp > 0 && sp > 0 && mrp > sp" class="px-4 py-2.5 bg-green-50 rounded-xl border border-green-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="text-xs font-semibold text-green-700">Discount: <span x-text="Math.round(((mrp - sp) / mrp) * 100)"></span>% off</span>
            </div>
        </div>

        <!-- Variants Note -->
        <div class="bg-amber-50 p-4 rounded-2xl border border-amber-200 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-amber-100 shrink-0">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div>
                <p class="text-sm font-bold text-amber-800">Packs / Variants</p>
                <p class="text-xs text-amber-600">You can add pack variants (Pack of 1, Pack of 2, etc.) after creating the product. You'll be redirected to the edit page.</p>
            </div>
        </div>

        <!-- Ayurvedic Details -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: rgba(176,136,64,0.1)">
                    <svg class="w-4 h-4" style="color:#B08840" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                <h2 class="text-base font-bold text-gray-900">Ayurvedic Details</h2>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Ingredients</label>
                <textarea name="ingredients" rows="3" placeholder="Comma-separated: Turmeric, Neem, Aloe Vera..." class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">{{ old('ingredients') }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">How to Use</label>
                <textarea name="how_to_use" rows="3" placeholder="Step-by-step usage instructions..." class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">{{ old('how_to_use') }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Key Benefits</label>
                <textarea name="benefits" rows="3" placeholder="One benefit per line or comma-separated..." class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">{{ old('benefits') }}</textarea>
            </div>
        </div>

        <!-- Product Images -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
            <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-purple-50">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h2 class="text-base font-bold text-gray-900">Product Images</h2>
            </div>
            <div x-data="{ previews: [] }">
                <label class="block border-2 border-dashed border-gray-200 rounded-xl p-6 text-center cursor-pointer hover:border-orange-300 hover:bg-orange-50/30 transition group">
                    <svg class="w-8 h-8 text-gray-300 mx-auto mb-2 group-hover:text-orange-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-sm font-medium text-gray-500">Click to upload images</p>
                    <p class="text-xs text-gray-400 mt-1">PNG, JPG, WebP up to 5MB &bull; First image = primary thumbnail</p>
                    <input type="file" name="images[]" multiple accept="image/*" class="hidden" @change="previews=[]; for(let f of $event.target.files){let r=new FileReader(); r.onload=e=>{previews=[...previews,e.target.result]}; r.readAsDataURL(f)}">
                </label>
                <div x-show="previews.length > 0" class="grid grid-cols-4 sm:grid-cols-6 gap-3 mt-4">
                    <template x-for="(src, i) in previews" :key="i">
                        <div class="aspect-square rounded-xl overflow-hidden border-2 border-gray-100 relative">
                            <img :src="src" class="w-full h-full object-cover">
                            <span x-show="i === 0" class="absolute bottom-0 inset-x-0 bg-green-500/90 text-white text-[8px] text-center font-bold py-0.5">Primary</span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Product Page Banners -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
            <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-indigo-50">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">Product Page Banners <span class="text-gray-400 font-normal text-xs">(optional)</span></h2>
                    <p class="text-xs text-gray-400">Carousel banners on this product's page (1200x400px)</p>
                </div>
            </div>
            <input type="file" name="banners[]" multiple accept="image/*" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm">
        </div>

        <!-- Status & Submit -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex items-center gap-3 pb-4 border-b border-gray-100 mb-4">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-blue-50">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h2 class="text-base font-bold text-gray-900">Status & Visibility</h2>
            </div>
            <div class="flex flex-wrap gap-6 mb-6">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 rounded border-gray-300 text-orange-500 focus:ring-orange-200">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Active</span>
                        <p class="text-xs text-gray-400">Visible on storefront</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="is_featured" value="1" class="w-5 h-5 rounded border-gray-300 text-orange-500 focus:ring-orange-200">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Featured</span>
                        <p class="text-xs text-gray-400">Show on homepage</p>
                    </div>
                </label>
            </div>
            <div class="flex items-center gap-4">
                <button type="submit" class="px-8 py-3.5 text-white font-bold text-sm rounded-xl hover:opacity-90 transition shadow-lg flex items-center gap-2" style="background-color:#c06d22">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Create Product
                </button>
                <a href="{{ route('admin.products.index') }}" class="px-6 py-3.5 border border-gray-200 text-gray-600 font-medium text-sm rounded-xl hover:bg-gray-50 transition">Cancel</a>
            </div>
            <p class="text-xs text-gray-400 mt-3">After creating, you'll be taken to the edit page to add pack variants.</p>
        </div>
    </form>
</div>
@endsection
