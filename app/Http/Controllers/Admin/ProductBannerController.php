<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductBanner;
use Illuminate\Http\Request;

class ProductBannerController extends Controller
{
    public function index()
    {
        $banners = ProductBanner::orderBy('sort_order')->paginate(20);
        return view('admin.product-banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.product-banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|max:5120',
            'link' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $path = $request->file('image')->store('product-banners', 'public');

        ProductBanner::create([
            'title' => $request->title,
            'image' => '/storage/' . $path,
            'link' => $request->link,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.product-banners.index')->with('success', 'Product banner added!');
    }

    public function destroy(ProductBanner $productBanner)
    {
        $productBanner->delete();
        return back()->with('success', 'Banner deleted.');
    }
}
