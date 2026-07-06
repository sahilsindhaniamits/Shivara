@extends('layouts.admin')
@section('title', 'Edit Product - Admin')
@section('page_title', 'Edit Product')

@section('content')
<div class="max-w-5xl" x-data="productEditor()">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Product</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $product->name }} &bull; SKU: {{ $product->sku ?? 'N/A' }}</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6" id="productForm">
        @csrf @method('PUT')


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
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Short Description</label>
                    <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" placeholder="Brief one-liner about this product">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Full Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" placeholder="Detailed product description...">{{ old('description', $product->description) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Category</label>
                    <select name="category_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" placeholder="e.g. SHV-001">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Return/Exchange Policy</label>
                    <select name="return_policy" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                        <option value="">Select Policy</option>
                        @foreach(['Easy 7 days return','Easy 5 days return','Exchange Only within 5 days','Easy 3 days return','Exchange Only within 3 days','No Return/Exchange'] as $policy)
                        <option value="{{ $policy }}" {{ ($product->return_policy ?? '') == $policy ? 'selected' : '' }}>{{ $policy }}</option>
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
                    <input type="number" name="mrp" value="{{ old('mrp', $product->mrp) }}" step="0.01" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Selling Price (₹) *</label>
                    <input type="number" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" step="0.01" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Stock *</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
            </div>
            @if($product->discount_percent > 0)
            <div class="px-4 py-2.5 bg-green-50 rounded-xl border border-green-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="text-xs font-semibold text-green-700">Discount: {{ $product->discount_percent }}% off (Save ₹{{ number_format($product->mrp - $product->selling_price) }})</span>
            </div>
            @endif
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
                <textarea name="ingredients" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" placeholder="Comma-separated: Turmeric, Neem, Aloe Vera...">{{ old('ingredients', $product->ingredients) }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">How to Use</label>
                <textarea name="how_to_use" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" placeholder="Step-by-step usage instructions...">{{ old('how_to_use', $product->how_to_use) }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Key Benefits</label>
                <textarea name="benefits" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" placeholder="One benefit per line or comma-separated...">{{ old('benefits', $product->benefits) }}</textarea>
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
            @if($product->images->count())
            <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-3 mb-4">
                @foreach($product->images as $img)
                <div class="relative group aspect-square">
                    <div class="w-full h-full rounded-xl overflow-hidden border-2 border-gray-100 group-hover:border-red-200 transition">
                        <img src="{{ str_starts_with($img->url, '/storage/') ? '/public' . $img->url : $img->url }}" class="w-full h-full object-cover">
                    </div>
                    <button type="button" onclick="if(confirm('Delete this image?')){fetch('{{ route('admin.products.deleteImage', [$product, $img]) }}',{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Content-Type':'application/json'},body:JSON.stringify({_method:'DELETE'})}).then(()=>this.closest('.relative').remove())}" class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] shadow-md opacity-0 group-hover:opacity-100 transition cursor-pointer">&times;</button>
                    @if($img->is_primary)<span class="absolute bottom-0 inset-x-0 bg-green-500/90 text-white text-[8px] text-center font-bold py-0.5 rounded-b-xl">Primary</span>@endif
                </div>
                @endforeach
            </div>
            @endif
            <label class="block border-2 border-dashed border-gray-200 rounded-xl p-6 text-center cursor-pointer hover:border-orange-300 hover:bg-orange-50/30 transition">
                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                <p class="text-sm font-medium text-gray-500">Click to upload images</p>
                <p class="text-xs text-gray-400 mt-1">PNG, JPG, WebP up to 5MB each</p>
                <input type="file" name="images[]" multiple accept="image/*" class="hidden">
            </label>
        </div>

        <!-- Product Page Banners -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
            <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-indigo-50">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">Product Page Banners</h2>
                    <p class="text-xs text-gray-400">Carousel banners on this product's page (1200×400px)</p>
                </div>
            </div>
            @if($product->banners && count($product->banners))
            <div class="flex gap-3 flex-wrap">
                @foreach($product->banners as $idx => $b)
                <div class="relative group">
                    <div class="w-40 h-16 rounded-xl overflow-hidden border border-gray-200">
                        <img src="{{ str_starts_with($b, '/storage/') ? '/public' . $b : $b }}" class="w-full h-full object-cover">
                    </div>
                    <button type="button" onclick="if(confirm('Delete?')){fetch('{{ route('admin.products.deleteBanner', [$product, $idx]) }}',{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Content-Type':'application/json'},body:JSON.stringify({_method:'DELETE'})}).then(()=>this.closest('.relative').remove())}" class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] shadow-md opacity-0 group-hover:opacity-100 transition cursor-pointer">&times;</button>
                </div>
                @endforeach
            </div>
            @endif
            <input type="file" name="banners[]" multiple accept="image/*" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm">
        </div>


        <!-- Status -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex items-center gap-3 pb-4 border-b border-gray-100 mb-4">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-blue-50">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h2 class="text-base font-bold text-gray-900">Status & Visibility</h2>
            </div>
            <div class="flex flex-wrap gap-6">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-orange-500 focus:ring-orange-200">
                    <div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Active</span>
                        <p class="text-xs text-gray-400">Visible on storefront</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-orange-500 focus:ring-orange-200">
                    <div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Featured</span>
                        <p class="text-xs text-gray-400">Show on homepage</p>
                    </div>
                </label>
            </div>
        </div>


        <!-- Variants / Packs -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-amber-50">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">Packs / Variants</h2>
                    <p class="text-xs text-gray-400">Different pack sizes shown as selectable options</p>
                </div>
            </div>

            @if($product->variants->count())
            <div class="space-y-2">
                @foreach($product->variants as $v)
                <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100 hover:border-gray-200 transition">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-xs font-bold text-white" style="background-color:#2C2418">{{ $loop->iteration }}</div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-gray-900">{{ $v->name }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">₹{{ number_format($v->selling_price) }} <span class="line-through text-gray-400">₹{{ number_format($v->mrp) }}</span> &bull; Stock: {{ $v->stock }}</p>
                    </div>
                    @if($v->mrp > $v->selling_price)
                    <span class="text-[10px] font-bold text-green-700 bg-green-50 border border-green-200 px-2 py-1 rounded-lg">-{{ round((($v->mrp - $v->selling_price) / $v->mrp) * 100) }}%</span>
                    @endif
                    <button type="button" onclick="if(confirm('Delete this variant?')){fetch('{{ route('admin.products.deleteVariant', [$product, $v]) }}',{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Content-Type':'application/json'},body:JSON.stringify({_method:'DELETE'})}).then(()=>location.reload())}" class="text-xs font-bold text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">Delete</button>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-6 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <p class="text-sm text-gray-400">No variants yet. Add pack sizes below.</p>
            </div>
            @endif

            <!-- Add Variant Form (Alpine.js driven) -->
            <div class="p-5 bg-slate-50 rounded-xl border border-gray-200 space-y-4">
                <p class="text-xs font-bold text-gray-600 uppercase tracking-wide flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add New Pack
                </p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Pack Name *</label>
                        <input type="text" x-model="variantName" placeholder="Pack of 2" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">MRP (₹) *</label>
                        <input type="number" x-model="variantMrp" placeholder="599" step="0.01" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Selling Price (₹) *</label>
                        <input type="number" x-model="variantSp" placeholder="449" step="0.01" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Stock</label>
                        <input type="number" x-model="variantStock" placeholder="50" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300">
                    </div>
                </div>
                <button type="button" @click="addVariant()" class="px-5 py-2.5 text-white text-xs font-bold uppercase tracking-wide rounded-lg hover:opacity-90 transition shadow-sm" style="background-color:#16a34a">
                    + Add Pack
                </button>
            </div>
        </div>


        <!-- Product Attributes -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-pink-50">
                    <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">Product Attributes</h2>
                    <p class="text-xs text-gray-400">Color swatches, size buttons, material options</p>
                </div>
            </div>

            @if($product->attributes && $product->attributes->count())
            <div class="space-y-3">
                @foreach($product->attributes as $attr)
                <div class="p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-gray-800">{{ $attr->name }}</span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold {{ $attr->type === 'color_swatch' ? 'bg-pink-50 text-pink-600' : ($attr->type === 'dropdown' ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-600') }}">
                                {{ $attr->type === 'color_swatch' ? 'Color Swatch' : ($attr->type === 'dropdown' ? 'Dropdown' : 'Buttons') }}
                            </span>
                        </div>
                        <button type="button" onclick="if(confirm('Delete attribute?')){fetch('{{ route('admin.products.deleteAttribute', [$product, $attr]) }}',{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Content-Type':'application/json'},body:JSON.stringify({_method:'DELETE'})}).then(()=>location.reload())}" class="text-xs text-red-500 font-bold hover:text-red-700 hover:bg-red-50 px-2 py-1 rounded-lg transition">Delete</button>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($attr->values as $val)
                        <div class="flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-gray-200 text-xs shadow-sm">
                            @if($attr->type === 'color_swatch' && $val->color_code)
                            <span class="w-4 h-4 rounded-full border border-gray-300 shrink-0" style="background-color: {{ $val->color_code }}"></span>
                            @endif
                            <span class="font-medium text-gray-700">{{ $val->value }}</span>
                            <button type="button" onclick="if(confirm('Delete?')){fetch('{{ route('admin.products.deleteAttributeValue', [$product, $val]) }}',{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Content-Type':'application/json'},body:JSON.stringify({_method:'DELETE'})}).then(()=>location.reload())}" class="text-red-300 hover:text-red-500 ml-1 transition">&times;</button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Add Attribute Form -->
            <div class="p-5 bg-slate-50 rounded-xl border border-gray-200 space-y-4">
                <p class="text-xs font-bold text-gray-600 uppercase tracking-wide flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add New Attribute
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Attribute Name *</label>
                        <select x-model="attrName" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-100">
                            <option value="">Select type...</option>
                            <option value="Color">Color</option>
                            <option value="Size">Size</option>
                            <option value="Weight">Weight</option>
                            <option value="Material">Material</option>
                            <option value="Fragrance">Fragrance</option>
                            <option value="custom">Custom...</option>
                        </select>
                        <template x-if="attrName === 'custom'">
                            <input x-model="customAttrName" type="text" placeholder="Custom name" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm mt-2 focus:outline-none focus:ring-2 focus:ring-orange-100">
                        </template>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Display Type</label>
                        <select x-model="attrType" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-100">
                            <option value="button">Buttons</option>
                            <option value="color_swatch">Color Swatches</option>
                            <option value="dropdown">Dropdown</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Values (comma separated) *</label>
                        <input x-model="attrValues" type="text" placeholder="Red, Blue, Green" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-100">
                    </div>
                </div>
                <template x-if="attrType === 'color_swatch'">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Color Codes (hex, same order)</label>
                        <input x-model="attrColors" type="text" placeholder="#FF0000, #0000FF, #00FF00" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-100">
                    </div>
                </template>
                <button type="button" @click="addAttribute()" class="px-5 py-2.5 text-white text-xs font-bold uppercase tracking-wide rounded-lg hover:opacity-90 transition shadow-sm" style="background-color:#16a34a">
                    + Add Attribute
                </button>
            </div>
        </div>


        <!-- Action Buttons -->
        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="px-8 py-3.5 text-white font-bold text-sm rounded-xl hover:opacity-90 transition shadow-lg" style="background-color:#c06d22">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Update Product
                </span>
            </button>
            <a href="{{ route('admin.products.index') }}" class="px-6 py-3.5 border border-gray-200 text-gray-600 font-medium text-sm rounded-xl hover:bg-gray-50 transition">Cancel</a>
        </div>
    </form>
</div>


<script>
function productEditor() {
    return {
        // Variant fields
        variantName: '',
        variantMrp: '',
        variantSp: '',
        variantStock: '',

        // Attribute fields
        attrName: '',
        customAttrName: '',
        attrType: 'button',
        attrValues: '',
        attrColors: '',

        addVariant() {
            if (!this.variantName || !this.variantMrp || !this.variantSp) {
                alert('Please fill Pack Name, MRP, and Selling Price');
                return;
            }
            // Submit via hidden form to maintain existing controller logic
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.products.update", $product) }}';
            form.style.display = 'none';

            let fields = {
                '_token': '{{ csrf_token() }}',
                '_method': 'PUT',
                'add_variant': '1',
                'variant_name': this.variantName,
                'variant_mrp': this.variantMrp,
                'variant_selling_price': this.variantSp,
                'variant_stock': this.variantStock || '0',
                'name': '{{ addslashes($product->name) }}',
                'mrp': '{{ $product->mrp }}',
                'selling_price': '{{ $product->selling_price }}',
                'stock': '{{ $product->stock }}'
            };

            for (let key in fields) {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = fields[key];
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();
        },

        addAttribute() {
            let name = this.attrName === 'custom' ? this.customAttrName : this.attrName;
            if (!name || !this.attrValues.trim()) {
                alert('Please fill attribute name and values');
                return;
            }
            let formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('attribute_name', name);
            formData.append('attribute_type', this.attrType);
            formData.append('attribute_values', this.attrValues);
            formData.append('attribute_colors', this.attrColors);

            fetch('{{ route("admin.products.storeAttribute", $product) }}', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    location.reload();
                } else {
                    alert(d.message || 'Error adding attribute');
                }
            })
            .catch(() => alert('Error adding attribute'));
        }
    }
}
</script>
@endsection
