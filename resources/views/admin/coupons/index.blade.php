@extends('layouts.admin')
@section('page_title', 'Coupons')
@section('title', 'Coupons - Admin')

@section('content')
<div class="space-y-5">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Coupons</h1>
            <p class="text-sm text-gray-500">{{ $coupons->total() }} discount codes &bull; Manage promotions</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="px-5 py-2.5 text-white text-sm font-bold rounded-xl hover:opacity-90 transition flex items-center gap-2 shadow-sm" style="background-color:#c06d22">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create Coupon
        </a>
    </div>

    <!-- Coupons Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/80">
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Code</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Discount</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Usage</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Min Order</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Valid Until</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Status</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                    <tr class="border-b border-gray-50 hover:bg-orange-50/20 transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1.5 bg-gray-900 text-white text-xs font-bold rounded-lg font-mono tracking-wider">{{ $coupon->code }}</span>
                            </div>
                            @if($coupon->description)
                            <p class="text-[10px] text-gray-400 mt-1 max-w-[160px] truncate">{{ $coupon->description }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-sm font-bold text-gray-900">
                                @if($coupon->type === 'percentage')
                                    {{ $coupon->value }}% OFF
                                @elseif($coupon->type === 'free_shipping')
                                    Free Shipping
                                @else
                                    ₹{{ number_format($coupon->value) }} OFF
                                @endif
                            </p>
                            @if($coupon->max_discount)
                            <p class="text-[10px] text-gray-400">Max: ₹{{ number_format($coupon->max_discount) }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 max-w-[80px]">
                                    @php $usagePercent = $coupon->usage_limit ? min(100, ($coupon->usage_count / $coupon->usage_limit) * 100) : 0; @endphp
                                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full" style="width: {{ $usagePercent }}%; background-color: {{ $usagePercent > 80 ? '#EF4444' : '#10B981' }}"></div>
                                    </div>
                                </div>
                                <span class="text-xs font-medium text-gray-600">{{ $coupon->usage_count }}/{{ $coupon->usage_limit ?? '∞' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-sm text-gray-600">{{ $coupon->min_order_amount ? '₹'.number_format($coupon->min_order_amount) : 'None' }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @if($coupon->end_date)
                            <p class="text-xs font-medium text-gray-700">{{ $coupon->end_date->format('d M Y') }}</p>
                            @if($coupon->end_date->isPast())
                            <p class="text-[10px] text-red-500 font-semibold">Expired</p>
                            @elseif($coupon->end_date->diffInDays(now()) <= 7)
                            <p class="text-[10px] text-amber-600 font-semibold">{{ $coupon->end_date->diffForHumans() }}</p>
                            @endif
                            @else
                            <p class="text-xs text-gray-400">No expiry</p>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex flex-wrap gap-1">
                            @if($coupon->isValid())
                            <span class="inline-flex items-center px-2 py-1 text-[10px] font-bold uppercase rounded-md bg-green-50 text-green-700">Active</span>
                            @elseif($coupon->end_date && $coupon->end_date->isPast())
                            <span class="inline-flex items-center px-2 py-1 text-[10px] font-bold uppercase rounded-md bg-red-50 text-red-700">Expired</span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 text-[10px] font-bold uppercase rounded-md bg-gray-100 text-gray-500">Inactive</span>
                            @endif
                            @if($coupon->auto_apply)
                            <span class="inline-flex items-center px-2 py-1 text-[10px] font-bold uppercase rounded-md bg-blue-50 text-blue-700">Auto</span>
                            @endif
                            @if($coupon->show_as_popup)
                            <span class="inline-flex items-center px-2 py-1 text-[10px] font-bold uppercase rounded-md bg-purple-50 text-purple-700">Popup</span>
                            @endif
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm('Delete coupon {{ $coupon->code }}?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-500">No coupons yet</p>
                                <p class="text-xs text-gray-400 mt-1">Create discount codes to boost sales</p>
                                <a href="{{ route('admin.coupons.create') }}" class="mt-4 px-4 py-2 text-white text-xs font-bold rounded-lg" style="background-color:#c06d22">+ Create Coupon</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($coupons->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-500">Showing {{ $coupons->firstItem() }}–{{ $coupons->lastItem() }} of {{ $coupons->total() }}</p>
            <div>{{ $coupons->links() }}</div>
        </div>
        @endif
    </div>
</div>
@endsection
