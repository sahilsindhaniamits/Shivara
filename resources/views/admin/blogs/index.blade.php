@extends('layouts.admin')
@section('title', 'Blog Posts - Admin')
@section('page_title', 'Blog Posts')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Blog Posts</h1>
            <p class="text-sm text-gray-500">{{ $blogs->total() }} articles</p>
        </div>
        <a href="{{ route('admin.blogs.create') }}" class="px-5 py-2.5 text-white text-sm font-bold rounded-xl hover:opacity-90 transition flex items-center gap-2 shadow-sm" style="background-color:#c06d22">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Post
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/80">
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Post</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Category</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Status</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Views</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Date</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $blog)
                    <tr class="border-b border-gray-50 hover:bg-orange-50/20 transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-9 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                                    @if($blog->featured_image)
                                    <img src="{{ str_starts_with($blog->featured_image, '/storage/') ? '/public' . $blog->featured_image : $blog->featured_image }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <p class="text-sm font-semibold text-gray-900 line-clamp-1 max-w-[250px]">{{ $blog->title }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-4"><span class="text-xs font-medium text-gray-600 bg-gray-100 px-2 py-1 rounded-md">{{ $blog->category ?? 'General' }}</span></td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2 py-1 text-[10px] font-bold uppercase rounded-md {{ $blog->is_published ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $blog->is_published ? 'Published' : 'Draft' }}</span>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-600">{{ number_format($blog->views) }}</td>
                        <td class="px-5 py-4 text-xs text-gray-500">{{ $blog->published_at ? $blog->published_at->format('d M Y') : '-' }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.blogs.edit', $blog) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" onsubmit="return confirm('Delete this post?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-16 text-center text-gray-400">No blog posts yet. <a href="{{ route('admin.blogs.create') }}" class="font-semibold" style="color:#c06d22">Create your first post</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($blogs->hasPages())<div class="px-5 py-4 border-t border-gray-100">{{ $blogs->links() }}</div>@endif
    </div>
</div>
@endsection
