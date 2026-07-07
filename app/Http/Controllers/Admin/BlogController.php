<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(20);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:3072',
            'is_published' => 'boolean',
        ]);

        if (empty($validated['content']) || $validated['content'] === '<p><br></p>') {
            return back()->withErrors(['content' => 'Blog content is required.'])->withInput();
        }

        $validated['slug'] = Str::slug($validated['title']);
        // Ensure unique slug
        $baseSlug = $validated['slug'];
        $counter = 1;
        while (Blog::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $baseSlug . '-' . $counter++;
        }
        $validated['author_id'] = auth()->id();
        $validated['is_published'] = $request->has('is_published');
        $validated['published_at'] = $request->has('is_published') ? now() : null;

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = '/storage/' . $request->file('featured_image')->store('blogs', 'public');
        }

        Blog::create($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post created!');
    }

    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:3072',
            'is_published' => 'boolean',
        ]);

        if (empty($validated['content']) || $validated['content'] === '<p><br></p>') {
            return back()->withErrors(['content' => 'Blog content is required.'])->withInput();
        }

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->has('is_published');

        if ($request->has('is_published') && !$blog->published_at) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = '/storage/' . $request->file('featured_image')->store('blogs', 'public');
        } else {
            unset($validated['featured_image']);
        }

        $blog->update($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated!');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('admin.blogs.index')->with('success', 'Blog post deleted.');
    }
}
