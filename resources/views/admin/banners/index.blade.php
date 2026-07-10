@extends('layouts.admin')
@section('page_title', 'Banners')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-slate-900">Banners</h1>
    <a href="{{ route('admin.banners.create') }}" class="px-4 py-2.5 text-white text-sm font-semibold rounded-xl hover:opacity-90 transition flex items-center gap-1.5" style="background-color:#c06d22">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Add Banner
    </a>
</div>
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($banners as $banner)
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden group">
        <div class="aspect-[16/7] bg-slate-100 relative">
            @if($banner->image)<img src="{{ $banner->image_url }}" class="w-full h-full object-cover">@endif
            <span class="absolute top-2 right-2 text-[10px] font-bold px-2 py-0.5 rounded-md {{ $banner->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-600' }}">{{ $banner->is_active ? 'Active' : 'Inactive' }}</span>
        </div>
        <div class="p-4">
            <h3 class="font-semibold text-sm text-slate-900">{{ $banner->title ?: 'Image Banner' }}</h3>
            @if($banner->subtitle)<p class="text-xs text-slate-500 mt-0.5">{{ $banner->subtitle }}</p>@endif
            <div class="flex items-center gap-2 mt-3">
                <a href="{{ route('admin.banners.edit', $banner) }}" class="text-xs text-brand-600 font-medium hover:underline">Edit</a>
                <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                    <button class="text-xs text-red-500 font-medium hover:underline">Delete</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-16 text-slate-400">No banners yet.</div>
    @endforelse
</div>
<div class="mt-6">{{ $banners->links() }}</div>
@endsection
