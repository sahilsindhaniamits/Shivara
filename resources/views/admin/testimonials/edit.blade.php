@extends('layouts.admin')
@section('title', 'Edit Testimonial - Admin')
@section('page_title', 'Edit Testimonial')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit: {{ $testimonial->customer_name }}</h1>
        <a href="{{ route('admin.testimonials.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition">Back</a>
    </div>

    <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Customer Name *</label>
                <input type="text" name="customer_name" value="{{ $testimonial->customer_name }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Video Type *</label>
                    <select name="video_type" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none">
                        <option value="youtube" {{ $testimonial->video_type === 'youtube' ? 'selected' : '' }}>YouTube</option>
                        <option value="instagram" {{ $testimonial->video_type === 'instagram' ? 'selected' : '' }}>Instagram Reel</option>
                        <option value="direct" {{ $testimonial->video_type === 'direct' ? 'selected' : '' }}>Direct Video URL</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Rating *</label>
                    <select name="rating" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none">
                        @for($r = 5; $r >= 1; $r--)
                        <option value="{{ $r }}" {{ $testimonial->rating == $r ? 'selected' : '' }}>{{ str_repeat('★', $r) }}{{ str_repeat('☆', 5-$r) }} ({{ $r }})</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Video URL *</label>
                <input type="text" name="video_url" value="{{ $testimonial->video_url }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Link to Product</label>
                <select name="product_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none">
                    <option value="">No product linked</option>
                    @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ $testimonial->product_id == $p->id ? 'selected' : '' }}>{{ $p->name }} — ₹{{ number_format($p->selling_price) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Thumbnail</label>
                @if($testimonial->thumbnail)
                <img src="{{ $testimonial->thumbnail_url }}" class="w-20 h-28 object-cover rounded-lg mb-2 border">
                @endif
                <input type="file" name="thumbnail" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm">
            </div>
            <div class="flex flex-wrap gap-5">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $testimonial->is_active ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-orange-500 focus:ring-orange-200">
                    <span class="text-sm font-medium text-gray-700">Active</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_verified" value="1" {{ $testimonial->is_verified ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-orange-500 focus:ring-orange-200">
                    <span class="text-sm font-medium text-gray-700">Verified Review</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-8 py-3 text-white font-bold text-sm rounded-xl hover:opacity-90 transition shadow-sm" style="background-color:#B7925C">Update</button>
            <a href="{{ route('admin.testimonials.index') }}" class="px-6 py-3 border border-gray-200 text-gray-600 font-medium text-sm rounded-xl hover:bg-gray-50 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
