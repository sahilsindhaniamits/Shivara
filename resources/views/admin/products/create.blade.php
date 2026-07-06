@extends('layouts.admin')
@section('title', 'Add Product - Admin')
@section('page_title', 'Add New Product')

@section('content')
<div class="max-w-5xl" x-data="newProduct()">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add New Product</h1>
            <p class="text-sm text-gray-500 mt-0.5">Fill in the basics — you can add variants & attributes after saving.</p>
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

        <!-- Step 1: Product Name & Category (Most Important) -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex items-center gap-3 mb-5">
                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background-color:#c06d22">1</span>
                <h2 class="text-base font-bold text-gray-900">What are you selling?</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Kumkumadi Tailam Face Oil" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" x-model="name">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Category</label>
                    <select name="category_id" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Short Description <span class="text-gray-400 normal-case">(shown on product cards)</span></label>
                <input type="text" name="short_description" value="{{ old('short_description') }}" placeholder="Brief one-liner, e.g. 'Premium Ayurvedic face oil for glowing skin'" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" maxlength="500">
            </div>
        </div>

        <!-- Step 2: Images (Visual First!) -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex items-center gap-3 mb-5">
                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background-color:#c06d22">2</span>
                <h2 class="text-base font-bold text-gray-900">Upload Product Images</h2>
                <span class="text-xs text-gray-400 ml-auto">First image = thumbnail</span>
            </div>
            <div x-data="{ previews: [] }">
                <label class="block border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center cursor-pointer hover:border-orange-300 hover:bg-orange-50/20 transition group">
                    <div class="flex flex-col items-center">
                        <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-orange-100 transition">
                            <svg class="w-7 h-7 text-gray-400 group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-600">Drop images here or click to browse</p>
                        <p class="text-xs text-gray-400 mt-1">PNG, JPG, WebP &bull; Up to 5MB each &bull; Multiple allowed</p>
                    </div>
                    <input type="file" name="images[]" multiple accept="image/*" class="hidden" @change="
                        previews = [];
                        for (let f of $event.target.files) {
                            let r = new FileReader();
                            r.onload = e => { previews = [...previews, e.target.result]; };
                            r.readAsDataURL(f);
                        }
                    ">
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

        <!-- Step 3: Pricing -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex items-center gap-3 mb-5">
                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background-color:#c06d22">3</span>
                <h2 class="text-base font-bold text-gray-900">Set Your Price</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">MRP (₹) *</label>
                    <input type="number" name="mrp" value="{{ old('mrp') }}" step="0.01" required placeholder="999" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" x-model="mrp">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Selling Price (₹) *</label>
                    <input type="number" name="selling_price" value="{{ old('selling_price') }}" step="0.01" required placeholder="699" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" x-model="sp">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Stock Quantity *</label>
                    <input type="number" name="stock" value="{{ old('stock', 50) }}" required placeholder="50" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">SKU <span class="text-gray-400">(optional)</span></label>
                    <input type="text" name="sku" value="{{ old('sku') }}" placeholder="SHV-001" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
            </div>
            <!-- Live discount preview -->
            <div x-show="mrp > 0 && sp > 0 && mrp > sp" class="mt-4 px-4 py-2.5 bg-green-50 rounded-xl border border-green-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="text-xs font-semibold text-green-700">Discount: <span x-text="Math.round(((mrp - sp) / mrp) * 100)"></span>% off (Save ₹<span x-text="(mrp - sp).toLocaleString()"></span>)</span>
            </div>
        </div>

        <!-- Step 4: Product Details (Collapsible - Optional) -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden" x-data="{ open: false }">
            <button type="button" @click="open = !open" class="w-full p-6 flex items-center justify-between text-left">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white bg-gray-400">4</span>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Product Details</h2>
                        <p class="text-xs text-gray-400">Description, Ingredients, How to Use, Benefits</p>
                    </div>
                </div>
                <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" x-collapse class="px-6 pb-6 space-y-4 border-t border-gray-100 pt-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Full Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" placeholder="Detailed product description for the product page...">{{ old('description') }}</textarea>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Ingredients</label>
                        <textarea name="ingredients" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" placeholder="Turmeric, Neem, Aloe Vera...">{{ old('ingredients') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">How to Use</label>
                        <textarea name="how_to_use" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" placeholder="Apply on face twice daily...">{{ old('how_to_use') }}</textarea>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Key Benefits</label>
                    <textarea name="benefits" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" placeholder="One per line: Reduces dark spots, Brightens skin...">{{ old('benefits') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Status & Submit -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex flex-wrap gap-5">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 rounded border-gray-300 text-orange-500 focus:ring-orange-200">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Active</span>
                            <p class="text-[11px] text-gray-400">Visible on store</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" class="w-5 h-5 rounded border-gray-300 text-orange-500 focus:ring-orange-200">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Featured</span>
                            <p class="text-[11px] text-gray-400">Show on homepage</p>
                        </div>
                    </label>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.products.index') }}" class="px-5 py-3 border border-gray-200 text-gray-600 font-medium text-sm rounded-xl hover:bg-gray-50 transition">Cancel</a>
                    <button type="submit" class="px-8 py-3 text-white font-bold text-sm rounded-xl hover:opacity-90 transition shadow-lg flex items-center gap-2" style="background-color:#c06d22">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Create Product
                    </button>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3">After clicking "Create Product", you'll be taken to the edit page where you can immediately add pack variants, product banners, and more details.</p>
        </div>
    </form>
</div>

<script>
function newProduct() {
    return { name: '', mrp: '', sp: '' }
}
</script>
@endsection
