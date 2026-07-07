<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::published()->latest('published_at')->paginate(12);
        $categories = Blog::published()->whereNotNull('category')->distinct()->pluck('category');
        return view('storefront.blog.index', compact('blogs', 'categories'));
    }

    public function show(string $slug)
    {
        $blog = Blog::where('slug', $slug)->published()->firstOrFail();
        $blog->increment('views');

        $related = Blog::published()
            ->where('id', '!=', $blog->id)
            ->where('category', $blog->category)
            ->take(3)
            ->get();

        return view('storefront.blog.show', compact('blog', 'related'));
    }
}
