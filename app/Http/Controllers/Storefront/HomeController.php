<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;

class HomeController extends Controller
{
    public function index()
    {
        // Load up to 2 products per category + fill remaining spots for 'All' tab
        $categories = Category::active()
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        $featuredProducts = Product::active()
            ->with(['primaryImage', 'images', 'category', 'variants'])
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->take(32); // Load enough to cover all categories

        $banners = Banner::active()->orderBy('sort_order')->get();

        return view('storefront.home', compact('featuredProducts', 'categories', 'banners'));
    }

    public function contact()
    {
        return view('storefront.contact');
    }

    public function about()
    {
        return view('storefront.pages.about');
    }
}
