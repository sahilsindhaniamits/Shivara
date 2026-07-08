@extends('layouts.admin')
@section('title', 'Video Testimonials - Admin')
@section('page_title', 'Video Testimonials')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Video Testimonials</h1>
            <p class="text-sm text-gray-500">{{ $testimonials->total() }} customer videos</p>
        </div>
        <a href="{{ route('admin.testimonials.create') }}" class="px-5 py-2.5 text-white text-sm font-bold rounded-xl hover:opacity-90 transition flex items-center gap-2 shadow-sm" style="background-color:#B7925C">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Video
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($testimonials as $t)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden group">
            <div class="relative aspect-[3/4] bg-gray-100 overflow-hidden">
                <img src="{{ $t->thumbnail_url }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                    <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </div>
                @if(!$t->is_active)<span class="absolute top-2 left-2 text-[9px] font-bold uppercase px-2 py-0.5 rounded bg-red-500 text-white">Hidden</span>@endif
                @if($t->is_verified)<span class="absolute top-2 right-2 text-[9px] font-bold uppercase px-2 py-0.5 rounded bg-purple-500 text-white">Verified</span>@endif
            </div>
            <div class="p-3">
                <p class="text-sm font-bold text-gray-900 truncate">{{ $t->customer_name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ $t->product->name ?? 'No product linked' }}</p>
                <div class="flex items-center justify-between mt-2">
                    <div class="flex gap-0.5">
                        @for($s = 1; $s <= 5; $s++)
                        <svg class="w-3 h-3 {{ $s <= $t->rating ? 'text-amber-400 fill-amber-400' : 'text-gray-200' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        @endfor
                    </div>
                    <div class="flex gap-1">
                        <a href="{{ route('admin.testimonials.edit', $t) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                            <button class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16">
            <p class="text-gray-400">No video testimonials yet.</p>
            <a href="{{ route('admin.testimonials.create') }}" class="mt-3 inline-block px-4 py-2 text-white text-xs font-bold rounded-lg" style="background-color:#B7925C">+ Add First Video</a>
        </div>
        @endforelse
    </div>
    @if($testimonials->hasPages())<div class="mt-4">{{ $testimonials->links() }}</div>@endif
</div>
@endsection
