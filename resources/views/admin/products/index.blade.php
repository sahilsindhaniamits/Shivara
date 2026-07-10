@extends('layouts.admin')
@section('title', 'Products - Admin')
@section('page_title', 'Products')

@section('content')
<div class="space-y-5" x-data="productsPage()">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Products</h1>
            <p class="text-sm text-gray-500">{{ $products->total() }} products in your catalog</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="px-5 py-2.5 text-white text-sm font-bold rounded-xl hover:opacity-90 transition flex items-center gap-2 shadow-sm" style="background-color:#c06d22">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Product
        </a>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col md:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, SKU, description..." class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
            </div>
            <select name="category" class="px-3 py-2.5 border border-gray-200 rounded-xl text-sm min-w-[140px]">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="status" class="px-3 py-2.5 border border-gray-200 rounded-xl text-sm min-w-[130px]">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
            </select>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold rounded-xl transition flex items-center gap-1.5 text-white" style="background-color:#2C2418">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter
            </button>
            @if(request()->hasAny(['search','category','status']))
            <a href="{{ route('admin.products.index') }}" class="px-3 py-2.5 text-xs text-gray-500 hover:text-red-500 transition">Clear</a>
            @endif
        </form>
    </div>

    <!-- Bulk Actions Bar -->
    <div x-show="selected.length > 0" x-cloak class="bg-slate-800 text-white px-5 py-3 rounded-xl flex items-center justify-between shadow-lg">
        <span class="text-sm font-medium"><span x-text="selected.length"></span> product(s) selected</span>
        <div class="flex items-center gap-2">
            <button @click="bulkAction('activate')" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg transition">Activate</button>
            <button @click="bulkAction('deactivate')" class="px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-bold rounded-lg transition">Deactivate</button>
            <button @click="bulkAction('delete')" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition">Delete</button>
            <button @click="selected = []" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white text-xs rounded-lg transition">Cancel</button>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/80">
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox" @change="toggleAll($event)" class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-200">
                        </th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Product</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Category</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Price</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Stock</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Status</th>
                        <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="border-b border-gray-50 hover:bg-orange-50/30 transition group">
                        <td class="px-4 py-3">
                            <input type="checkbox" value="{{ $product->id }}" x-model="selected" class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-200">
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.products.edit', $product) }}" class="flex items-center gap-3 group/link">
                                <div class="w-11 h-11 bg-gray-100 rounded-xl overflow-hidden shrink-0 border border-gray-200">
                                    @if($product->primaryImage)
                                    <img src="{{ str_starts_with($product->primaryImage->url, '/storage/') ? '/public' . $product->primaryImage->url : $product->primaryImage->url }}" alt="" class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 group-hover/link:text-orange-700 transition truncate max-w-[200px]">{{ $product->name }}</p>
                                    <p class="text-[11px] text-gray-400 font-mono">{{ $product->sku ?? 'No SKU' }}</p>
                                </div>
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-medium text-gray-600 bg-gray-100 px-2 py-1 rounded-md">{{ $product->category->name ?? 'Uncategorized' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-bold text-gray-900">₹{{ number_format($product->selling_price) }}</p>
                            @if($product->discount_percent > 0)
                            <p class="text-[10px] text-gray-400 line-through">₹{{ number_format($product->mrp) }} <span class="text-green-600 no-underline font-semibold">-{{ $product->discount_percent }}%</span></p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if(is_null($product->stock))
                            <span class="text-sm font-bold text-blue-600">∞</span>
                            @else
                            <span class="text-sm font-bold {{ $product->stock <= 0 ? 'text-red-600' : ($product->stock <= ($product->low_stock_alert ?? 5) ? 'text-amber-600' : 'text-green-600') }}">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if(!$product->is_active)
                            <span class="inline-flex items-center px-2 py-1 text-[10px] font-bold uppercase rounded-md bg-gray-100 text-gray-500">Inactive</span>
                            @elseif(!$product->in_stock)
                            <span class="inline-flex items-center px-2 py-1 text-[10px] font-bold uppercase rounded-md bg-red-50 text-red-700">Out of Stock</span>
                            @elseif(!is_null($product->stock) && $product->stock <= ($product->low_stock_alert ?? 5))
                            <span class="inline-flex items-center px-2 py-1 text-[10px] font-bold uppercase rounded-md bg-amber-50 text-amber-700">Low Stock</span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 text-[10px] font-bold uppercase rounded-md bg-green-50 text-green-700">Active</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.products.edit', $product) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition" title="View on Store">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete {{ addslashes($product->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
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
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-500">No products found</p>
                                <p class="text-xs text-gray-400 mt-1">Try changing filters or add your first product</p>
                                <a href="{{ route('admin.products.create') }}" class="mt-4 px-4 py-2 text-white text-xs font-bold rounded-lg" style="background-color:#c06d22">+ Add Product</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-500">Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}</p>
            <div>{{ $products->links() }}</div>
        </div>
        @endif
    </div>
</div>

<script>
function productsPage() {
    return {
        selected: [],
        toggleAll(e) {
            if (e.target.checked) {
                this.selected = [@json($products->pluck('id'))].flat().map(String);
            } else {
                this.selected = [];
            }
        },
        bulkAction(action) {
            if (this.selected.length === 0) return;
            let msg = action === 'delete' ? 'Delete ' + this.selected.length + ' products? This cannot be undone.' : (action === 'activate' ? 'Activate' : 'Deactivate') + ' ' + this.selected.length + ' products?';
            if (!confirm(msg)) return;

            fetch('/admin/products/bulk-action', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                body: JSON.stringify({ ids: this.selected, action: action })
            }).then(r => {
                if (r.ok) return r.json();
                if (r.status === 419) { location.reload(); return; }
                return r.json().catch(() => r.text()).then(t => { throw new Error(typeof t === 'object' ? t.message : t); });
            }).then(d => {
                if (d && d.success) location.reload();
                else if (d) alert(d.message || 'Error');
            }).catch(e => { console.error(e); alert(e.message || 'Error. Please refresh and try again.'); });
        }
    }
}
</script>
@endsection
