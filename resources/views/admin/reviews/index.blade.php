@extends('layouts.admin')
@section('page_title', 'Reviews')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Reviews</h1>
            <p class="text-sm text-slate-500 mt-1">Manage customer product reviews</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reviews.index') }}" class="px-3 py-2 text-xs font-medium rounded-lg transition {{ !request('status') ? 'text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}" @if(!request('status')) style="background-color:#c06d22" @endif>All ({{ $totalCount }})</a>
            <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="px-3 py-2 text-xs font-medium rounded-lg transition {{ request('status') === 'pending' ? 'text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}" @if(request('status') === 'pending') style="background-color:#c06d22" @endif>Pending ({{ $pendingCount }})</a>
            <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" class="px-3 py-2 text-xs font-medium rounded-lg transition {{ request('status') === 'approved' ? 'text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}" @if(request('status') === 'approved') style="background-color:#c06d22" @endif>Approved ({{ $approvedCount }})</a>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-slate-600">Product</th>
                        <th class="text-left px-4 py-3 font-semibold text-slate-600">Customer</th>
                        <th class="text-left px-4 py-3 font-semibold text-slate-600">Rating</th>
                        <th class="text-left px-4 py-3 font-semibold text-slate-600">Comment</th>
                        <th class="text-left px-4 py-3 font-semibold text-slate-600">Status</th>
                        <th class="text-right px-4 py-3 font-semibold text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reviews as $review)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3">
                            <span class="font-medium text-slate-700">{{ $review->product->name ?? 'Deleted Product' }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $review->user->name ?? 'Unknown' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-0.5">
                                @for($s = 1; $s <= 5; $s++)
                                <svg class="w-3.5 h-3.5 {{ $s <= $review->rating ? 'text-amber-400 fill-amber-400' : 'text-gray-200 fill-gray-200' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                @endfor
                            </div>
                        </td>
                        <td class="px-4 py-3 text-slate-600 max-w-xs">
                            <span class="line-clamp-2">{{ $review->comment ?? '—' }}</span>
                            @if($review->images && count($review->images))
                            <div class="flex gap-1.5 mt-2">
                                @foreach($review->images as $rImg)
                                <div class="w-10 h-10 rounded-lg overflow-hidden border border-gray-200">
                                    <img src="{{ str_starts_with($rImg, '/storage/') ? '/public' . $rImg : $rImg }}" class="w-full h-full object-cover">
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($review->is_approved)
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full border border-green-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Approved
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-amber-50 text-amber-700 text-xs font-medium rounded-full border border-amber-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Pending
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                @if(!$review->is_approved)
                                <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white rounded-lg hover:opacity-90 transition" style="background-color:#c06d22">Approve</button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('admin.reviews.decline', $review) }}" onsubmit="return confirm('Are you sure you want to delete this review?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-slate-400">No reviews found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($reviews->hasPages())
    <div class="mt-4">
        {{ $reviews->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
