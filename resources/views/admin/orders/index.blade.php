@extends('layouts.admin')
@section('title', 'Orders - Admin')
@section('page_title', 'Orders')

@section('content')
<div class="space-y-5" x-data="ordersPage()">

    <!-- Header with Stats -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Orders</h1>
            <p class="text-sm text-gray-500">{{ $orders->total() }} total orders</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="px-3 py-1.5 text-xs font-bold rounded-lg {{ request('status') == 'pending' ? 'text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }} transition" {{ request('status') == 'pending' ? 'style=background-color:#B08840' : '' }}>
                Pending
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="px-3 py-1.5 text-xs font-bold rounded-lg {{ request('status') == 'processing' ? 'text-white' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }} transition" {{ request('status') == 'processing' ? 'style=background-color:#3B82F6' : '' }}>
                Processing
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="px-3 py-1.5 text-xs font-bold rounded-lg {{ request('status') == 'shipped' ? 'text-white' : 'bg-purple-50 text-purple-700 hover:bg-purple-100' }} transition" {{ request('status') == 'shipped' ? 'style=background-color:#7C3AED' : '' }}>
                Shipped
            </a>
            <a href="{{ route('admin.orders.index') }}" class="px-3 py-1.5 text-xs font-bold rounded-lg {{ !request('status') ? 'text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition" {{ !request('status') ? 'style=background-color:#2C2418' : '' }}>
                All
            </a>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col md:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order number, customer name, email..." class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
            </div>
            <select name="status" class="px-3 py-2.5 border border-gray-200 rounded-xl text-sm min-w-[140px]">
                <option value="">All Status</option>
                @foreach(['pending','confirmed','processing','shipped','out_for_delivery','delivered','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
            <select name="payment" class="px-3 py-2.5 border border-gray-200 rounded-xl text-sm min-w-[130px]">
                <option value="">All Payments</option>
                <option value="paid" {{ request('payment') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ request('payment') == 'pending' ? 'selected' : '' }}>Unpaid</option>
                <option value="cod" {{ request('payment') == 'cod' ? 'selected' : '' }}>COD</option>
            </select>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold rounded-xl text-white transition" style="background-color:#2C2418">Search</button>
            @if(request()->hasAny(['search','status','payment']))
            <a href="{{ route('admin.orders.index') }}" class="px-3 py-2.5 text-xs text-gray-500 hover:text-red-500 transition">Clear</a>
            @endif
        </form>
    </div>

    <!-- Bulk Actions -->
    <div x-show="selected.length > 0" x-cloak class="bg-slate-800 text-white px-5 py-3 rounded-xl flex items-center justify-between shadow-lg">
        <span class="text-sm font-medium"><span x-text="selected.length"></span> order(s) selected</span>
        <div class="flex items-center gap-2">
            <select x-model="bulkStatus" class="px-3 py-1.5 bg-white/10 text-white text-xs rounded-lg border border-white/20">
                <option value="">Change Status...</option>
                <option value="confirmed">Confirmed</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <button @click="bulkUpdateStatus()" :disabled="!bulkStatus" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 disabled:opacity-40 text-white text-xs font-bold rounded-lg transition">Apply</button>
            <button @click="selected = []; bulkStatus = ''" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white text-xs rounded-lg transition">Cancel</button>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/80">
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox" @change="toggleAll($event)" class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-200">
                        </th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Order</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Customer</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Amount</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Payment</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Status</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Date</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="border-b border-gray-50 hover:bg-orange-50/20 transition">
                        <td class="px-4 py-3">
                            <input type="checkbox" value="{{ $order->id }}" x-model="selected" class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-200">
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-sm font-bold hover:underline" style="color:#c06d22">{{ $order->order_number }}</a>
                            <p class="text-[10px] text-gray-400">{{ $order->items_count ?? $order->items()->count() }} items</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'Guest' }}</p>
                            <p class="text-[11px] text-gray-400">{{ $order->user->email ?? '' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-bold text-gray-900">₹{{ number_format($order->total_amount) }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @if($order->payment_status == 'paid')
                            <span class="inline-flex items-center gap-1 px-2 py-1 text-[10px] font-bold uppercase rounded-md bg-green-50 text-green-700">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Paid
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 text-[10px] font-bold uppercase rounded-md bg-amber-50 text-amber-700">
                                {{ $order->payment_method == 'cod' ? 'COD' : 'Unpaid' }}
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $statusStyles = [
                                    'pending' => 'background-color:#FEF3C7;color:#92400E',
                                    'confirmed' => 'background-color:#DBEAFE;color:#1E40AF',
                                    'processing' => 'background-color:#E0E7FF;color:#3730A3',
                                    'shipped' => 'background-color:#EDE9FE;color:#5B21B6',
                                    'out_for_delivery' => 'background-color:#D1FAE5;color:#065F46',
                                    'delivered' => 'background-color:#D1FAE5;color:#065F46',
                                    'cancelled' => 'background-color:#FEE2E2;color:#991B1B',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold uppercase rounded-md" style="{{ $statusStyles[$order->status] ?? 'background-color:#F3F4F6;color:#374151' }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-xs text-gray-600">{{ $order->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-gray-400">{{ $order->created_at->format('h:i A') }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition inline-flex" title="View Details">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-500">No orders found</p>
                                <p class="text-xs text-gray-400 mt-1">Orders will appear here once customers start purchasing</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-500">Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }}</p>
            <div>{{ $orders->links() }}</div>
        </div>
        @endif
    </div>
</div>

<script>
function ordersPage() {
    return {
        selected: [],
        bulkStatus: '',
        toggleAll(e) {
            if (e.target.checked) {
                this.selected = [@json($orders->pluck('id'))].flat().map(String);
            } else {
                this.selected = [];
            }
        },
        bulkUpdateStatus() {
            if (!this.bulkStatus || this.selected.length === 0) return;
            if (!confirm('Update ' + this.selected.length + ' orders to "' + this.bulkStatus + '"?')) return;

            fetch('{{ route("admin.orders.index") }}/bulk-status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                body: JSON.stringify({ ids: this.selected, status: this.bulkStatus })
            }).then(r => r.json()).then(d => {
                if (d.success) location.reload();
                else alert(d.message || 'Error');
            }).catch(() => alert('Error updating orders'));
        }
    }
}
</script>
@endsection
