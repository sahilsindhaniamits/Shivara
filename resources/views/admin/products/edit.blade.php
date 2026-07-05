@extends('layouts.admin')
@section('title', 'Edit Product - Admin')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Product</h1>
        <p class="text-sm text-gray-500">{{ $product->name }}</p>
    </div>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        <div class="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
            <h2 class="font-bold text-gray-900">Basic Information</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                    <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('description', $product->description) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
            <h2 class="font-bold text-gray-900">Pricing & Inventory</h2>
            <div class="grid md:grid-cols-3 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">MRP (₹) *</label><input type="number" name="mrp" value="{{ old('mrp', $product->mrp) }}" step="0.01" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Selling Price (₹) *</label><input type="number" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" step="0.01" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Cost Price (₹)</label><input type="number" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" step="0.01" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Stock *</label><input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">GST Rate (%)</label><select name="gst_rate" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm">@foreach(config('shivara.gst_rates') as $rate)<option value="{{ $rate }}" {{ $product->gst_rate == $rate ? 'selected' : '' }}>{{ $rate }}%</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Weight (g)</label><input type="number" name="weight" value="{{ old('weight', $product->weight) }}" step="0.01" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
            <h2 class="font-bold text-gray-900">Details</h2>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Ingredients</label><textarea name="ingredients" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none">{{ old('ingredients', $product->ingredients) }}</textarea></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">How to Use</label><textarea name="how_to_use" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none">{{ old('how_to_use', $product->how_to_use) }}</textarea></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Benefits</label><textarea name="benefits" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none">{{ old('benefits', $product->benefits) }}</textarea></div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200">
            <h2 class="font-bold text-gray-900 mb-3">Product Images</h2>
            <input type="file" name="images[]" multiple accept="image/*" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm">
            <p class="text-xs text-gray-400 mt-2">Upload additional product images.</p>
        </div>

        <!-- Product Page Banners -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200">
            <h2 class="font-bold text-gray-900 mb-2">Product Page Banners</h2>
            <p class="text-xs text-gray-500 mb-3">Banners shown as a slider on this product's page (1200×400px recommended).</p>
            @if($product->banners && count($product->banners))
            <div class="flex gap-2 flex-wrap mb-3">
                @foreach($product->banners as $b)
                <div class="w-32 h-12 rounded-lg overflow-hidden border border-gray-200"><img src="{{ str_starts_with($b, '/storage/') ? '/public' . $b : $b }}" class="w-full h-full object-cover"></div>
                @endforeach
            </div>
            @endif
            <input type="file" name="banners[]" multiple accept="image/*" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm">
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200">
            <div class="flex flex-wrap gap-6">
                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="rounded text-primary"><span class="text-sm text-gray-700">Active</span></label>
                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="rounded text-primary"><span class="text-sm text-gray-700">Featured</span></label>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-8 py-3 text-white font-medium rounded-xl hover:opacity-90 transition" style="background-color:#c06d22">Update Product</button>
            <a href="{{ route('admin.products.index') }}" class="px-8 py-3 border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
