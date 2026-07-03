<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with(['primaryImage', 'category']);

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                    ->orWhere('description', 'LIKE', "%{$request->search}%");
            });
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('selling_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('selling_price', '<=', $request->max_price);
        }

        // Sorting
        $sort = $request->get('sort', 'featured');
        $query = match ($sort) {
            'price_low' => $query->orderBy('selling_price', 'asc'),
            'price_high' => $query->orderBy('selling_price', 'desc'),
            'newest' => $query->orderBy('created_at', 'desc'),
            'name' => $query->orderBy('name', 'asc'),
            default => $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc'),
        };

        $products = $query->paginate(12)->appends($request->query());
        $categories = Category::active()->orderBy('sort_order')->get();

        return view('storefront.products.index', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->active()
            ->with(['images', 'variants', 'category', 'reviews.user'])
            ->firstOrFail();

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('primaryImage')
            ->take(4)
            ->get();

        return view('storefront.products.show', compact('product', 'relatedProducts'));
    }
}
