<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'primaryImage']);

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%")
                ->orWhere('sku', 'LIKE', "%{$request->search}%");
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'active' => $query->where('is_active', true)->where('stock', '>', 0),
                'low_stock' => $query->where('stock', '<=', \DB::raw('low_stock_alert'))->where('stock', '>', 0),
                'out_of_stock' => $query->where('stock', '<=', 0),
                default => null,
            };
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'ingredients' => 'nullable|string',
            'how_to_use' => 'nullable|string',
            'benefits' => 'nullable|string',
            'mrp' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products',
            'hsn_code' => 'nullable|string',
            'gst_rate' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        $product = Product::create($validated);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'url' => '/storage/' . $path,
                    'is_primary' => $i === 0,
                    'sort_order' => $i,
                ]);
            }
        }

        // Handle banner uploads
        if ($request->hasFile('banners')) {
            $bannerPaths = [];
            foreach ($request->file('banners') as $banner) {
                $path = $banner->store('product-banners', 'public');
                $bannerPaths[] = '/storage/' . $path;
            }
            $product->update(['banners' => $bannerPaths]);
        }

        // Handle variants from create form
        if ($request->has('variants')) {
            foreach ($request->variants as $v) {
                if (!empty($v['name']) && !empty($v['mrp']) && !empty($v['sp'])) {
                    $weightDisplay = (!empty($v['weight']) && !empty($v['unit'])) ? $v['weight'] . ' ' . $v['unit'] : null;
                    \App\Models\ProductVariant::create([
                        'product_id' => $product->id,
                        'name' => $v['name'],
                        'mrp' => $v['mrp'],
                        'selling_price' => $v['sp'],
                        'stock' => $v['stock'] ?? 0,
                        'weight' => $v['weight'] ?? null,
                        'weight_display' => $weightDisplay,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'variants', 'attributes.values']);
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'ingredients' => 'nullable|string',
            'how_to_use' => 'nullable|string',
            'benefits' => 'nullable|string',
            'mrp' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sku' => "nullable|string|unique:products,sku,{$product->id}",
            'hsn_code' => 'nullable|string',
            'gst_rate' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'return_policy' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        $product->update($validated);

        // Handle editing an existing variant
        if ($request->filled('edit_variant_id')) {
            $variant = \App\Models\ProductVariant::where('id', $request->edit_variant_id)->where('product_id', $product->id)->first();
            if ($variant) {
                $weightDisplay = null;
                if ($request->filled('ev_weight') && $request->filled('ev_unit')) {
                    $weightDisplay = $request->ev_weight . ' ' . $request->ev_unit;
                } elseif ($request->filled('ev_weight_display')) {
                    $weightDisplay = $request->ev_weight_display;
                }

                $variant->update([
                    'name' => $request->ev_name ?? $variant->name,
                    'mrp' => $request->ev_mrp ?? $variant->mrp,
                    'selling_price' => $request->ev_sp ?? $variant->selling_price,
                    'stock' => $request->ev_stock ?? $variant->stock,
                    'weight_display' => $weightDisplay ?: $variant->weight_display,
                ]);
            }
            return redirect()->route('admin.products.edit', $product)->with('success', 'Variant updated!');
        }

        // Handle adding a new variant
        if ($request->filled('add_variant') && $request->filled('variant_name') && $request->filled('variant_mrp') && $request->filled('variant_selling_price')) {
            \App\Models\ProductVariant::create([
                'product_id' => $product->id,
                'name' => $request->variant_name,
                'mrp' => $request->variant_mrp,
                'selling_price' => $request->variant_selling_price,
                'stock' => $request->variant_stock ?? 0,
                'weight' => $request->variant_weight ?: null,
                'weight_display' => $request->variant_weight_display ?: null,
            ]);
            return redirect()->route('admin.products.edit', $product)->with('success', 'Variant added!');
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'url' => '/storage/' . $path,
                    'sort_order' => $product->images()->count(),
                ]);
            }
        }

        // Handle banner uploads (appends to existing)
        if ($request->hasFile('banners')) {
            $existing = $product->banners ?? [];
            foreach ($request->file('banners') as $banner) {
                $path = $banner->store('product-banners', 'public');
                $existing[] = '/storage/' . $path;
            }
            $product->update(['banners' => $existing]);
        }

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted.');
    }

    public function deleteImage(Product $product, \App\Models\ProductImage $image)
    {
        if ($image->product_id === $product->id) {
            $image->delete();
        }
        return back()->with('success', 'Image deleted.');
    }

    public function deleteBanner(Product $product, $index)
    {
        $banners = $product->banners ?? [];
        if (isset($banners[$index])) {
            unset($banners[$index]);
            $product->update(['banners' => array_values($banners)]);
        }
        return back()->with('success', 'Banner deleted.');
    }

    public function deleteVariant(Product $product, \App\Models\ProductVariant $variant)
    {
        if ($variant->product_id === $product->id) {
            $variant->delete();
        }
        return back()->with('success', 'Variant deleted.');
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id',
            'action' => 'required|in:activate,deactivate,delete',
        ]);

        $ids = $request->ids;

        switch ($request->action) {
            case 'activate':
                Product::whereIn('id', $ids)->update(['is_active' => true]);
                break;
            case 'deactivate':
                Product::whereIn('id', $ids)->update(['is_active' => false]);
                break;
            case 'delete':
                Product::whereIn('id', $ids)->delete();
                break;
        }

        return response()->json(['success' => true, 'message' => ucfirst($request->action) . ' completed for ' . count($ids) . ' products.']);
    }

    public function storeAttribute(Request $request, Product $product)
    {
        $request->validate([
            'attribute_name' => 'required|string|max:100',
            'attribute_type' => 'required|in:button,color_swatch,dropdown',
            'attribute_values' => 'required|string',
        ]);

        $attribute = \App\Models\ProductAttribute::create([
            'product_id' => $product->id,
            'name' => $request->attribute_name,
            'type' => $request->attribute_type,
            'sort_order' => $product->attributes()->count(),
        ]);

        $values = array_filter(array_map('trim', explode(',', $request->attribute_values)));
        $colors = array_filter(array_map('trim', explode(',', $request->attribute_colors ?? '')));

        foreach ($values as $i => $value) {
            \App\Models\ProductAttributeValue::create([
                'product_attribute_id' => $attribute->id,
                'value' => $value,
                'color_code' => $colors[$i] ?? null,
                'sort_order' => $i,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function deleteAttribute(Product $product, \App\Models\ProductAttribute $attribute)
    {
        if ($attribute->product_id === $product->id) {
            $attribute->delete();
        }
        return back()->with('success', 'Attribute deleted.');
    }

    public function deleteAttributeValue(Product $product, \App\Models\ProductAttributeValue $attributeValue)
    {
        $attribute = $attributeValue->attribute;
        if ($attribute && $attribute->product_id === $product->id) {
            $attributeValue->delete();
        }
        return back()->with('success', 'Attribute value deleted.');
    }
}
