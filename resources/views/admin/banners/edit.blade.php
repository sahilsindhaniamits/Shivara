@extends('layouts.admin')
@section('page_title', 'Edit Banner')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-xl font-bold text-slate-900 mb-6">Edit: {{ $banner->title }}</h1>
    <form method="POST" action="{{ route('admin.banners.update', $banner) }}" enctype="multipart/form-data" class="bg-white p-6 rounded-2xl border border-slate-100 space-y-4">
        @csrf @method('PUT')
        <div><label class="block text-sm font-medium text-slate-700 mb-1">Title *</label><input type="text" name="title" value="{{ old('title', $banner->title) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
        <div><label class="block text-sm font-medium text-slate-700 mb-1">Subtitle</label><input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
        <div><label class="block text-sm font-medium text-slate-700 mb-1">Link URL</label><input type="url" name="link" value="{{ old('link', $banner->link) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Replace Image</label><input type="file" name="image" accept="image/*" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm"></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label><input type="number" name="sort_order" value="{{ $banner->sort_order }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none"></div>
        </div>
        <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ $banner->is_active ? 'checked' : '' }} class="rounded text-brand-600"><span class="text-sm text-slate-700">Active</span></label>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-6 py-3 text-white" style="background-color:#c06d22 font-semibold rounded-xl hover:opacity-90 transition text-sm">Update</button>
            <a href="{{ route('admin.banners.index') }}" class="px-6 py-3 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
