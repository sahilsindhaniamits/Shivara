@extends('layouts.admin')
@section('page_title', 'Customers')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-slate-900">Customers</h1>
</div>

<!-- Search -->
<form method="GET" class="mb-6">
    <div class="relative max-w-md">
        <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, phone..." class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200">
    </div>
</form>

<div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full">
        <thead><tr class="border-b border-slate-100 bg-slate-50/50">
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Customer</th>
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Email</th>
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Phone</th>
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Orders</th>
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Spent</th>
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Joined</th>
            <th class="text-left text-[11px] font-semibold text-slate-500 uppercase px-5 py-3">Status</th>
        </tr></thead>
        <tbody>
        @forelse($customers as $customer)
        <tr class="border-b border-slate-50 hover:bg-slate-50/50">
            <td class="px-5 py-3.5">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-brand-50 rounded-full flex items-center justify-center text-brand-700 text-xs font-bold">{{ substr($customer->name, 0, 1) }}</div>
                    <a href="{{ route('admin.customers.show', $customer) }}" class="text-sm font-semibold text-slate-900 hover:text-brand-600">{{ $customer->name }}</a>
                </div>
            </td>
            <td class="px-5 py-3.5 text-sm text-slate-500">{{ $customer->email }}</td>
            <td class="px-5 py-3.5 text-sm text-slate-500">{{ $customer->phone ?? '-' }}</td>
            <td class="px-5 py-3.5 text-sm font-semibold text-slate-700">{{ $customer->orders_count }}</td>
            <td class="px-5 py-3.5 text-sm font-semibold text-slate-700">₹{{ number_format($customer->orders_sum_total_amount ?? 0) }}</td>
            <td class="px-5 py-3.5 text-sm text-slate-500">{{ $customer->created_at->format('M d, Y') }}</td>
            <td class="px-5 py-3.5">
                <form method="POST" action="{{ route('admin.customers.toggleStatus', $customer) }}">@csrf @method('PATCH')
                    <button class="text-[10px] font-bold uppercase px-2 py-1 rounded-md {{ $customer->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">{{ $customer->is_active ? 'Active' : 'Blocked' }}</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="px-5 py-12 text-center text-slate-400">No customers yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
    <div class="p-4 border-t border-slate-100">{{ $customers->links() }}</div>
</div>
@endsection
