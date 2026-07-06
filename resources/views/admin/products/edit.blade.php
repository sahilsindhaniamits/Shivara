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
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Return/Exchange Policy</label>
                    <select name="return_policy" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none">
                        <option value="">Select Policy</option>
                        <option value="Easy 7 days return" {{ ($product->return_policy ?? '') == 'Easy 7 days return' ? 'selected' : '' }}>Easy 7 days return</option>
                        <option value="Easy 5 days return" {{ ($product->return_policy ?? '') == 'Easy 5 days return' ? 'selected' : '' }}>Easy 5 days return</option>
                        <option value="Exchange Only within 5 days" {{ ($product->return_policy ?? '') == 'Exchange Only within 5 days' ? 'selected' : '' }}>Exchange Only within 5 days</option>
                        <option value="Easy 3 days return" {{ ($product->return_policy ?? '') == 'Easy 3 days return' ? 'selected' : '' }}>Easy 3 days return</option>
                        <option value="Exchange Only within 3 days" {{ ($product->return_policy ?? '') == 'Exchange Only within 3 days' ? 'selected' : '' }}>Exchange Only within 3 days</option>
                        <option value="No Return/Exchange" {{ ($product->return_policy ?? '') == 'No Return/Exchange' ? 'selected' : '' }}>No Return/Exchange</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
            <h2 class="font-bold text-gray-900">Pricing & Inventory</h2>
            <div class="grid md:grid-cols-3 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">MRP (₹) *</label><input type="number" name="mrp" value="{{ old('mrp', $product->mrp) }}" step="0.01" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Selling Price (₹) *</label><input type="number" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" step="0.01" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Stock *</label><input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"></div>
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
            @if($product->images->count())
            <div class="flex gap-3 flex-wrap mb-4">
                @foreach($product->images as $img)
                <div class="relative group">
                    <div class="w-20 h-20 rounded-lg overflow-hidden border border-gray-200">
                        <img src="{{ str_starts_with($img->url, '/storage/') ? '/public' . $img->url : $img->url }}" class="w-full h-full object-cover">
                    </div>
                    <button type="button" onclick="if(confirm('Delete this image?')){fetch('{{ route('admin.products.deleteImage', [$product, $img]) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify({_method:'DELETE'})}).then(()=>this.closest('.relative').remove())}" class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs shadow-md hover:bg-red-600 cursor-pointer">&times;</button>
                    @if($img->is_primary)<span class="absolute bottom-0 left-0 right-0 bg-green-500 text-white text-[8px] text-center font-bold py-0.5">Primary</span>@endif
                </div>
                @endforeach
            </div>
            @endif
            <input type="file" name="images[]" multiple accept="image/*" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm">
            <p class="text-xs text-gray-400 mt-2">Upload additional product images.</p>
        </div>

        <!-- Product Page Banners -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200">
            <h2 class="font-bold text-gray-900 mb-2">Product Page Banners</h2>
            <p class="text-xs text-gray-500 mb-3">Banners shown as a slider on this product's page (1200×400px recommended).</p>
            @if($product->banners && count($product->banners))
            <div class="flex gap-3 flex-wrap mb-4">
                @foreach($product->banners as $idx => $b)
                <div class="relative group">
                    <div class="w-36 h-14 rounded-lg overflow-hidden border border-gray-200">
                        <img src="{{ str_starts_with($b, '/storage/') ? '/public' . $b : $b }}" class="w-full h-full object-cover">
                    </div>
                    <button type="button" onclick="if(confirm('Delete this banner?')){fetch('{{ route('admin.products.deleteBanner', [$product, $idx]) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify({_method:'DELETE'})}).then(()=>this.closest('.relative').remove())}" class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs shadow-md hover:bg-red-600 cursor-pointer">&times;</button>
                </div>
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

        <!-- Variants / Packs -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200">
            <h2 class="font-bold text-gray-900 mb-2">Packs / Variants</h2>
            <p class="text-xs text-gray-500 mb-4">Add different pack sizes (e.g., Pack of 1, Pack of 2). These show as selectable options on the product page.</p>

            @if($product->variants->count())
            <div class="space-y-2 mb-4">
                @foreach($product->variants as $v)
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">{{ $v->name }} — ₹{{ number_format($v->selling_price) }} <span class="text-xs text-gray-400 line-through">₹{{ number_format($v->mrp) }}</span></p>
                        <p class="text-xs text-gray-500">Stock: {{ $v->stock }} | SKU: {{ $v->sku ?? '-' }}</p>
                    </div>
                    <button type="button" onclick="if(confirm('Delete this variant?')){fetch('{{ route('admin.products.show', $product) }}/variant/{{ $v->id }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify({_method:'DELETE'})}).then(()=>location.reload())}" class="text-xs text-red-500 font-bold hover:underline">Delete</button>
                </div>
                @endforeach
            </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 p-4 bg-cream-50 rounded-xl border border-dashed border-gray-300">
                <div><label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Name *</label><input type="text" name="variant_name" placeholder="Pack of 2" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm"></div>
                <div><label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">MRP *</label><input type="number" name="variant_mrp" placeholder="599" step="0.01" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm"></div>
                <div><label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Selling Price *</label><input type="number" name="variant_selling_price" placeholder="449" step="0.01" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm"></div>
                <div><label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Stock</label><input type="number" name="variant_stock" placeholder="50" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm"></div>
                <div class="flex items-end"><button type="button" onclick="
                    let name = this.closest('.grid').querySelector('[name=variant_name]').value;
                    let mrp = this.closest('.grid').querySelector('[name=variant_mrp]').value;
                    let sp = this.closest('.grid').querySelector('[name=variant_selling_price]').value;
                    let stock = this.closest('.grid').querySelector('[name=variant_stock]').value;
                    if(!name||!mrp||!sp){alert('Fill Name, MRP, Selling Price');return;}
                    let form = document.createElement('form');form.method='POST';form.action=window.location.href;
                    form.innerHTML='@csrf<input name=_method value=PUT><input name=add_variant value=1><input name=variant_name value="'+name+'"><input name=variant_mrp value="'+mrp+'"><input name=variant_selling_price value="'+sp+'"><input name=variant_stock value="'+stock+'"><input name=name value={{ $product->name }}><input name=mrp value={{ $product->mrp }}><input name=selling_price value={{ $product->selling_price }}><input name=stock value={{ $product->stock }}><input name=gst_rate value={{ $product->gst_rate }}>';
                    document.body.appendChild(form);form.submit();
                " class="w-full px-3 py-2 text-white text-xs font-bold rounded-lg hover:opacity-90 transition" style="background-color:#16a34a">+ Add Pack</button></div>
            </div>
        </div>

        <!-- Product Attributes (Color, Size, Material) -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200" x-data="productAttributes()">
            <h2 class="font-bold text-gray-900 mb-2">Product Attributes</h2>
            <p class="text-xs text-gray-500 mb-4">Add selectable options like Color, Size/Weight, Material. These show as swatches/buttons on the product page.</p>

            @if($product->attributes && $product->attributes->count())
            <div class="space-y-4 mb-4">
                @foreach($product->attributes as $attr)
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-gray-800">{{ $attr->name }}</span>
                            <span class="text-[10px] px-2 py-0.5 bg-blue-50 text-blue-600 font-semibold rounded-full">{{ $attr->type === 'color_swatch' ? 'Color Swatch' : ($attr->type === 'dropdown' ? 'Dropdown' : 'Buttons') }}</span>
                        </div>
                        <button type="button" onclick="if(confirm('Delete this attribute and all its values?')){fetch('{{ route('admin.products.show', $product) }}/attribute/{{ $attr->id }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify({_method:'DELETE'})}).then(()=>location.reload())}" class="text-xs text-red-500 font-bold hover:underline">Delete</button>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($attr->values as $val)
                        <div class="flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-gray-200 text-xs">
                            @if($attr->type === 'color_swatch' && $val->color_code)
                            <span class="w-4 h-4 rounded-full border border-gray-300 shrink-0" style="background-color: {{ $val->color_code }}"></span>
                            @endif
                            <span class="font-medium text-gray-700">{{ $val->value }}</span>
                            @if($val->price_adjustment != 0)
                            <span class="text-gray-400">({{ $val->price_adjustment > 0 ? '+' : '' }}₹{{ number_format($val->price_adjustment) }})</span>
                            @endif
                            <button type="button" onclick="if(confirm('Delete this value?')){fetch('{{ route('admin.products.show', $product) }}/attribute-value/{{ $val->id }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify({_method:'DELETE'})}).then(()=>location.reload())}" class="text-red-400 hover:text-red-600 ml-1">&times;</button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Add New Attribute -->
            <div class="p-4 bg-cream-50 rounded-xl border border-dashed border-gray-300 space-y-3">
                <p class="text-xs font-bold text-gray-600 uppercase">Add New Attribute</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Attribute Name *</label>
                        <select x-model="attrName" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                            <option value="">Select...</option>
                            <option value="Color">Color</option>
                            <option value="Size">Size</option>
                            <option value="Weight">Weight</option>
                            <option value="Material">Material</option>
                            <option value="Fragrance">Fragrance</option>
                            <option value="custom">Custom...</option>
                        </select>
                        <input x-show="attrName === 'custom'" x-model="customAttrName" type="text" placeholder="Custom name" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm mt-2">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Display Type</label>
                        <select x-model="attrType" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                            <option value="button">Buttons</option>
                            <option value="color_swatch">Color Swatches</option>
                            <option value="dropdown">Dropdown</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Values (comma separated) *</label>
                        <input x-model="attrValues" type="text" placeholder="Red, Blue, Green" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    </div>
                </div>
                <div x-show="attrType === 'color_swatch'" class="mt-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Color Codes (comma separated, same order as values)</label>
                    <input x-model="attrColors" type="text" placeholder="#FF0000, #0000FF, #00FF00" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                </div>
                <button type="button" @click="addAttribute()" class="px-4 py-2 text-white text-xs font-bold rounded-lg hover:opacity-90 transition" style="background-color:#16a34a">+ Add Attribute</button>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-8 py-3 text-white font-medium rounded-xl hover:opacity-90 transition" style="background-color:#c06d22">Update Product</button>
            <a href="{{ route('admin.products.index') }}" class="px-8 py-3 border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition">Cancel</a>
        </div>
    </form>
</div>

<script>
function productAttributes() {
    return {
        attrName: '', customAttrName: '', attrType: 'button', attrValues: '', attrColors: '',
        addAttribute() {
            let name = this.attrName === 'custom' ? this.customAttrName : this.attrName;
            if (!name || !this.attrValues.trim()) { alert('Fill attribute name and values'); return; }
            let form = new FormData();
            form.append('_token', '{{ csrf_token() }}');
            form.append('attribute_name', name);
            form.append('attribute_type', this.attrType);
            form.append('attribute_values', this.attrValues);
            form.append('attribute_colors', this.attrColors);
            fetch('{{ route("admin.products.show", $product) }}/attributes', { method: 'POST', body: form })
            .then(r => r.json())
            .then(d => { if(d.success) location.reload(); else alert(d.message || 'Error'); })
            .catch(() => alert('Error adding attribute'));
        }
    }
}
</script>
@endsection
