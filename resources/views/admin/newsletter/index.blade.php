@extends('layouts.admin')
@section('page_title', 'Newsletter Subscribers')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Newsletter</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $totalCount }} total subscribers</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-slate-600">#</th>
                        <th class="text-left px-4 py-3 font-semibold text-slate-600">Email</th>
                        <th class="text-left px-4 py-3 font-semibold text-slate-600">Subscribed At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($subscribers as $i => $sub)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 text-slate-500">{{ $subscribers->firstItem() + $i }}</td>
                        <td class="px-4 py-3 font-medium text-slate-700">{{ $sub->email }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $sub->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-12 text-center text-slate-400">No subscribers yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($subscribers->hasPages())
    <div class="mt-4">{{ $subscribers->links() }}</div>
    @endif
</div>
@endsection
