@extends('layouts.app')
@section('title', ($blog->meta_title ?? $blog->title) . ' - Shivara Blog')
@section('meta_description', $blog->meta_description ?? $blog->excerpt ?? Str::limit(strip_tags($blog->content), 155))

@section('content')
<article class="max-w-4xl mx-auto px-4 sm:px-6 py-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-espresso-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-gold-600 transition">Home</a>
        <span class="text-espresso-300">›</span>
        <a href="{{ route('blog.index') }}" class="hover:text-gold-600 transition">Blog</a>
        <span class="text-espresso-300">›</span>
        <span class="text-espresso-600 font-medium">{{ Str::limit($blog->title, 40) }}</span>
    </nav>

    <!-- Header -->
    <header class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <span class="text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full border border-gold-200 text-gold-600">{{ $blog->category ?? 'Ayurveda' }}</span>
            <span class="text-xs text-espresso-300">{{ $blog->published_at->format('M d, Y') }}</span>
            <span class="text-xs text-espresso-300">&bull; {{ $blog->read_time }} min read</span>
            <span class="text-xs text-espresso-300">&bull; {{ number_format($blog->views) }} views</span>
        </div>
        <h1 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-espresso-700 leading-tight">{{ $blog->title }}</h1>
        @if($blog->excerpt)
        <p class="text-lg text-espresso-400 mt-4 leading-relaxed">{{ $blog->excerpt }}</p>
        @endif
    </header>

    <!-- Featured Image -->
    @if($blog->featured_image)
    <div class="rounded-2xl overflow-hidden mb-10 border border-gold-100">
        <img src="{{ str_starts_with($blog->featured_image, '/storage/') ? '/public' . $blog->featured_image : $blog->featured_image }}" alt="{{ $blog->title }}" class="w-full h-auto max-h-[500px] object-cover">
    </div>
    @endif

    <!-- Content -->
    <div class="prose prose-lg max-w-none text-espresso-600 leading-relaxed">
        {!! $blog->content !!}
    </div>

    <!-- Tags -->
    @if($blog->tags)
    <div class="mt-10 pt-6 border-t border-gold-100">
        <div class="flex flex-wrap gap-2">
            @foreach(explode(',', $blog->tags) as $tag)
            <span class="text-xs font-medium px-3 py-1.5 bg-cream-200 text-espresso-500 rounded-full">{{ trim($tag) }}</span>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Share -->
    <div class="mt-8 p-6 bg-cream-100 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-sm font-semibold text-espresso-700">Found this helpful? Share it!</p>
        <div class="flex gap-3">
            <a href="https://wa.me/?text={{ urlencode($blog->title . ' - ' . url()->current()) }}" target="_blank" class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center hover:opacity-80 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
            </a>
            <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="w-10 h-10 bg-black text-white rounded-full flex items-center justify-center hover:opacity-80 transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
        </div>
    </div>

    <!-- Related Posts -->
    @if($related->count())
    <div class="mt-12 pt-10 border-t border-gold-100">
        <h3 class="font-display text-2xl font-bold text-espresso-700 mb-6">Related Articles</h3>
        <div class="grid md:grid-cols-3 gap-5">
            @foreach($related as $r)
            <a href="{{ route('blog.show', $r->slug) }}" class="group">
                <div class="aspect-[16/10] rounded-xl overflow-hidden bg-cream-100 mb-3">
                    @if($r->featured_image)
                    <img src="{{ str_starts_with($r->featured_image, '/storage/') ? '/public' . $r->featured_image : $r->featured_image }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @endif
                </div>
                <h4 class="text-sm font-bold text-espresso-700 group-hover:text-gold-600 transition line-clamp-2">{{ $r->title }}</h4>
                <p class="text-[11px] text-espresso-300 mt-1">{{ $r->published_at->format('M d, Y') }}</p>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</article>
@endsection
