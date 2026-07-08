<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoTestimonial;
use App\Models\Product;
use Illuminate\Http\Request;

class VideoTestimonialController extends Controller
{
    public function index()
    {
        $testimonials = VideoTestimonial::with('product')->orderBy('sort_order')->paginate(20);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        $products = Product::active()->orderBy('name')->get();
        return view('admin.testimonials.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'video_url' => 'required|string|max:500',
            'video_type' => 'required|in:youtube,instagram,direct',
            'product_id' => 'nullable|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'thumbnail' => 'nullable|image|max:5120',
        ]);

        $data = $request->only(['customer_name', 'video_url', 'video_type', 'product_id', 'rating']);
        $data['is_active'] = $request->has('is_active');
        $data['is_verified'] = $request->has('is_verified');
        $data['sort_order'] = VideoTestimonial::max('sort_order') + 1;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = '/storage/' . $request->file('thumbnail')->store('testimonials', 'public');
        }

        if ($request->hasFile('video_file')) {
            $data['video_file'] = '/storage/' . $request->file('video_file')->store('testimonial-videos', 'public');
        }

        VideoTestimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Video testimonial added!');
    }

    public function edit(VideoTestimonial $testimonial)
    {
        $products = Product::active()->orderBy('name')->get();
        return view('admin.testimonials.edit', compact('testimonial', 'products'));
    }

    public function update(Request $request, VideoTestimonial $testimonial)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'video_url' => 'required|string|max:500',
            'video_type' => 'required|in:youtube,instagram,direct',
            'product_id' => 'nullable|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'thumbnail' => 'nullable|image|max:5120',
        ]);

        $data = $request->only(['customer_name', 'video_url', 'video_type', 'product_id', 'rating']);
        $data['is_active'] = $request->has('is_active');
        $data['is_verified'] = $request->has('is_verified');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = '/storage/' . $request->file('thumbnail')->store('testimonials', 'public');
        }

        if ($request->hasFile('video_file')) {
            $data['video_file'] = '/storage/' . $request->file('video_file')->store('testimonial-videos', 'public');
        }

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated!');
    }

    public function destroy(VideoTestimonial $testimonial)
    {
        $testimonial->delete();
        return back()->with('success', 'Testimonial deleted.');
    }
}
