@extends('layouts.admin')
@section('title', 'Add Video Testimonial - Admin')
@section('page_title', 'Add Video Testimonial')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Add Video Testimonial</h1>
        <a href="{{ route('admin.testimonials.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition">Back</a>
    </div>

    @if($errors->any())
    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
        <ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Customer Name *</label>
                <input type="text" name="customer_name" value="{{ old('customer_name') }}" required placeholder="e.g. Priya Sharma" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Video Type *</label>
                    <select name="video_type" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none">
                        <option value="youtube">YouTube</option>
                        <option value="instagram">Instagram Reel</option>
                        <option value="direct">Direct Video URL</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Rating *</label>
                    <select name="rating" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none">
                        <option value="5">★★★★★ (5 stars)</option>
                        <option value="4">★★★★☆ (4 stars)</option>
                        <option value="3">★★★☆☆ (3 stars)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Video URL *</label>
                <input type="text" name="video_url" value="{{ old('video_url') }}" required placeholder="https://www.youtube.com/watch?v=... or https://www.instagram.com/reel/..." class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                <p class="text-[10px] text-gray-400 mt-1">YouTube: Paste full URL or shorts URL. Instagram: Paste reel URL.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Link to Product <span class="text-gray-400">(optional)</span></label>
                <select name="product_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none">
                    <option value="">No product linked</option>
                    @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->name }} — ₹{{ number_format($p->selling_price) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Video File <span class="text-gray-400">(MP4 - for autoplay in cards)</span></label>
                <input type="file" name="video_file" accept="video/mp4,video/webm" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm">
                <p class="text-[10px] text-gray-400 mt-1">Upload a short MP4 clip (5-15 sec, under 10MB). This will autoplay silently in the card. Recommended for best experience.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Thumbnail Image <span class="text-gray-400">(optional - auto-generated for YouTube)</span></label>
                <input type="file" name="thumbnail" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm">
                <p class="text-[10px] text-gray-400 mt-1">Upload a screenshot/photo of the customer. If left blank and video is YouTube, thumbnail will be auto-generated.</p>
            </div>

            <div class="flex flex-wrap gap-5">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 rounded border-gray-300 text-orange-500 focus:ring-orange-200">
                    <span class="text-sm font-medium text-gray-700">Active (visible on website)</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_verified" value="1" checked class="w-5 h-5 rounded border-gray-300 text-orange-500 focus:ring-orange-200">
                    <span class="text-sm font-medium text-gray-700">Verified Review badge</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-8 py-3 text-white font-bold text-sm rounded-xl hover:opacity-90 transition shadow-sm" style="background-color:#B7925C">Add Testimonial</button>
            <a href="{{ route('admin.testimonials.index') }}" class="px-6 py-3 border border-gray-200 text-gray-600 font-medium text-sm rounded-xl hover:bg-gray-50 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
