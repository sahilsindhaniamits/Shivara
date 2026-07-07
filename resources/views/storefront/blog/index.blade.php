@extends('layouts.app')
@section('title', 'Blog - Ayurvedic Health Tips & Wellness')
@section('meta_description', 'Discover Ayurvedic health tips, wellness guides, and natural remedies from Shivara experts.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
    <div class="text-center mb-12">
        <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500">Ayurvedic Wisdom</span>
        <h1 class="font-display text-4xl md:text-5xl font-bold text-espresso-700 mt-3">Our Blog</h1>
        <p class="text-espresso-400 mt-3 max-w-lg mx-auto">Expert insights on Ayurveda, natural health tips, and holistic wellness for everyday life.</p>
    </div>

    @if($blogs->count())
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($blogs as $blog)
        <a href="{{ route('blog.show', $blog->slug) }}" class="group bg-white rounded-2xl border border-gold-100/50 overflow-hidden hover:shadow-xl transition-all duration-400 hover:-translate-y-1">
            <div class="aspect-[16/10] overflow-hidden bg-cream-100">
                @if($blog->featured_image)
                <img src="{{ str_starts_with($blog->featured_image, '/storage/') ? '/public' . $blog->featured_image : $blog->featured_image }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gold-50 to-gold-100">
                    <svg class="w-16 h-16 text-gold-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                @endif
            </div>
            <div class="p-5">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-gold-500">{{ $blog->category ?? 'Ayurveda' }}</span>
                    <span class="text-[10px] text-espresso-300">&bull; {{ $blog->read_time }} min read</span>
                </div>
                <h2 class="text-base font-bold text-espresso-700 group-hover:text-gold-600 transition line-clamp-2 leading-snug">{{ $blog->title }}</h2>
                <p class="text-xs text-espresso-400 mt-2 line-clamp-2">{{ $blog->excerpt ?? Str::limit(strip_tags($blog->content), 120) }}</p>
                <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
                    <span class="text-[10px] text-espresso-300">{{ $blog->published_at->format('M d, Y') }}</span>
                    <span class="text-[11px] font-bold text-gold-600 group-hover:translate-x-1 transition-transform">Read →</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    <div class="mt-10">{{ $blogs->links() }}</div>
    @else
    <div class="text-center py-20">
        <p class="text-espresso-400">Blog posts coming soon! Stay tuned for Ayurvedic wellness tips.</p>
    </div>
    @endif
</div>
@endsection
