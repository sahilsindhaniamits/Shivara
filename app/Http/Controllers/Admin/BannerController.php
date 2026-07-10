<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->paginate(20);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'image' => 'required|file|mimes:jpg,jpeg,png,webp,gif|max:10240',
            'mobile_image' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:10240',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);
        $validated['title'] = $request->input('title', '');
        $validated['image'] = '/storage/' . $request->file('image')->store('banners', 'public');

        if ($request->hasFile('mobile_image')) {
            $validated['mobile_image'] = '/storage/' . $request->file('mobile_image')->store('banners', 'public');
        }

        Banner::create($validated);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:10240',
            'mobile_image' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:10240',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = '/storage/' . $request->file('image')->store('banners', 'public');
        } else {
            unset($validated['image']);
        }

        if ($request->hasFile('mobile_image')) {
            $validated['mobile_image'] = '/storage/' . $request->file('mobile_image')->store('banners', 'public');
        } else {
            unset($validated['mobile_image']);
        }

        $banner->update($validated);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner deleted.');
    }
}
