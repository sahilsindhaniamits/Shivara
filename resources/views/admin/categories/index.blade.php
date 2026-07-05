@extends('layouts.admin')
@section('page_title', 'Categories')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-slate-900">Categories</h1>
    <a href="{{ route('admin.categories.create') }}" class="px-4 py-2.5 text-white" style="background-color:#c06d22 text-sm font-semibold rounded-xl hover:opacity-90 transition flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Add Category
    </a>
</div>
<div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
    <table class="w-full">
        <thead><tr class="border-b border-slate-100 bg-slate-50/50">
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Name</th>
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Slug</th>
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Products</th>
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Status</th>
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Actions</th>
        </tr></thead>
        <tbody>
        @forelse($categories as $cat)
        <tr class="border-b border-slate-50 hover:bg-slate-50/50">
            <td class="px-5 py-3.5 text-sm font-semibold text-slate-900">{{ $cat->name }}</td>
            <td class="px-5 py-3.5 text-sm text-slate-500 font-mono">{{ $cat->slug }}</td>
            <td class="px-5 py-3.5 text-sm text-slate-600">{{ $cat->products_count }}</td>
            <td class="px-5 py-3.5"><span class="text-[10px] font-bold uppercase px-2 py-1 rounded-md {{ $cat->is_active ? 'bg-green-50 text-green-700' : 'bg-slate-100 text-slate-500' }}">{{ $cat->is_active ? 'Active' : 'Inactive' }}</span></td>
            <td class="px-5 py-3.5 flex items-center gap-2">
                <a href="{{ route('admin.categories.edit', $cat) }}" class="text-slate-400 hover:text-brand-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                    <button class="text-slate-400 hover:text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="5" class="px-5 py-12 text-center text-slate-400">No categories yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-slate-100">{{ $categories->links() }}</div>
</div>
@endsection
