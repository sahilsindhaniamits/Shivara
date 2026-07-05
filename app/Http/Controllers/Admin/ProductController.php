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
            'gst_rate' => 'required|numeric',
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

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'variants']);
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
            'gst_rate' => 'required|numeric',
            'weight' => 'nullable|numeric',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        $product->update($validated);

        // Handle adding a new variant
        if ($request->filled('add_variant') && $request->filled('variant_name') && $request->filled('variant_mrp') && $request->filled('variant_selling_price')) {
            \App\Models\ProductVariant::create([
                'product_id' => $product->id,
                'name' => $request->variant_name,
                'mrp' => $request->variant_mrp,
                'selling_price' => $request->variant_selling_price,
                'stock' => $request->variant_stock ?? 0,
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

        return redirect()->route('admin.products.index')
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
}
