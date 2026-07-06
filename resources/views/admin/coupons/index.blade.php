@extends('layouts.admin')
@section('page_title', 'Coupons')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-slate-900">Coupons</h1>
    <a href="{{ route('admin.coupons.create') }}" class="px-4 py-2.5 text-white text-sm font-semibold rounded-xl hover:opacity-90 transition flex items-center gap-1.5" style="background-color:#c06d22">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Add Coupon
    </a>
</div>
<div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full">
        <thead><tr class="border-b border-slate-100 bg-slate-50/50">
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Code</th>
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Type</th>
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Value</th>
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Usage</th>
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Expires</th>
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Status</th>
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Actions</th>
        </tr></thead>
        <tbody>
        @forelse($coupons as $coupon)
        <tr class="border-b border-slate-50 hover:bg-slate-50/50">
            <td class="px-5 py-3.5 text-sm font-bold text-slate-900 font-mono">{{ $coupon->code }}</td>
            <td class="px-5 py-3.5 text-sm text-slate-600 capitalize">{{ $coupon->type }}</td>
            <td class="px-5 py-3.5 text-sm text-slate-700 font-semibold">{{ $coupon->type === 'percentage' ? $coupon->value.'%' : '₹'.$coupon->value }}</td>
            <td class="px-5 py-3.5 text-sm text-slate-500">{{ $coupon->usage_count }}/{{ $coupon->usage_limit ?? '∞' }}</td>
            <td class="px-5 py-3.5 text-sm text-slate-500">{{ $coupon->end_date->format('M d, Y') }}</td>
            <td class="px-5 py-3.5"><span class="text-[10px] font-bold uppercase px-2 py-1 rounded-md {{ $coupon->isValid() ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">{{ $coupon->isValid() ? 'Active' : 'Expired' }}</span></td>
            <td class="px-5 py-3.5 flex items-center gap-2">
                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-slate-400 hover:text-brand-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                    <button class="text-slate-400 hover:text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="px-5 py-12 text-center text-slate-400">No coupons yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
    <div class="p-4 border-t border-slate-100">{{ $coupons->links() }}</div>
</div>
@endsection
