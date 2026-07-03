@extends('layouts.app')
@section('title', 'My Addresses - Shivara')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        @include('storefront.account._sidebar')

        <div class="flex-1 min-w-0" x-data="{ showForm: false }">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-display font-bold text-gray-900">My Addresses</h1>
                <button @click="showForm = !showForm" class="px-4 py-2 bg-dark text-white text-sm font-semibold rounded-lg hover:bg-dark-light transition">
                    + Add New
                </button>
            </div>


            <!-- Add Address Form -->
            <div x-show="showForm" x-cloak class="bg-white p-6 rounded-2xl border border-gray-100 mb-6">
                <h2 class="font-bold text-gray-900 mb-4">Add New Address</h2>
                <form method="POST" action="{{ route('account.addresses.store') }}" class="grid sm:grid-cols-2 gap-4">
                    @csrf
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label><input type="text" name="full_name" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label><input type="text" name="phone" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
                    <div class="sm:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1">Address *</label><input type="text" name="address_line1" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">City *</label><input type="text" name="city" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">State *</label><input type="text" name="state" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Pincode *</label><input type="text" name="pincode" maxlength="6" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Landmark</label><input type="text" name="landmark" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
                    <div class="sm:col-span-2 flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="is_default" value="1" class="rounded text-brand-600"><span class="text-sm text-gray-700">Set as default</span></label>
                        <button type="submit" class="px-6 py-3 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition text-sm">Save Address</button>
                    </div>
                </form>
            </div>

            <!-- Address List -->
            <div class="grid sm:grid-cols-2 gap-4">
                @forelse($addresses as $address)
                <div class="bg-white p-5 rounded-2xl border {{ $address->is_default ? 'border-brand-300 bg-brand-50/30' : 'border-gray-100' }} relative">
                    @if($address->is_default)<span class="absolute top-3 right-3 text-[10px] font-bold uppercase bg-brand-100 text-brand-700 px-2 py-0.5 rounded-md">Default</span>@endif
                    <p class="font-semibold text-sm text-gray-900">{{ $address->full_name }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $address->address_line1 }}</p>
                    <p class="text-sm text-gray-600">{{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}</p>
                    <p class="text-sm text-gray-500 mt-1">Phone: {{ $address->phone }}</p>
                    <form method="POST" action="{{ route('account.addresses.destroy', $address) }}" class="mt-3">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 hover:text-red-600 font-medium">Delete</button>
                    </form>
                </div>
                @empty
                <p class="text-gray-400 col-span-2 text-center py-10">No saved addresses. Add one above.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection


            <!-- Add Address Form -->
            <div x-show="showForm" x-cloak class="bg-white p-6 rounded-2xl border border-gray-100 mb-6">
                <h2 class="font-bold text-gray-900 mb-4">Add New Address</h2>
                <form method="POST" action="{{ route('account.addresses.store') }}" class="grid sm:grid-cols-2 gap-4">
                    @csrf
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label><input type="text" name="full_name" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label><input type="text" name="phone" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
                    <div class="sm:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1">Address *</label><input type="text" name="address_line1" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">City *</label><input type="text" name="city" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">State *</label><input type="text" name="state" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Pincode *</label><input type="text" name="pincode" maxlength="6" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Landmark</label><input type="text" name="landmark" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
                    <div class="sm:col-span-2 flex items-center gap-4">
                        <label class="flex items-center gap-2"><input type="checkbox" name="is_default" value="1" class="rounded text-brand-600"><span class="text-sm text-gray-700">Default</span></label>
                        <button type="submit" class="px-6 py-3 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition text-sm">Save</button>
                    </div>
                </form>
            </div>

            <!-- Address List -->
            <div class="grid sm:grid-cols-2 gap-4">
                @forelse($addresses as $address)
                <div class="bg-white p-5 rounded-2xl border {{ $address->is_default ? 'border-brand-300' : 'border-gray-100' }} relative">
                    @if($address->is_default)<span class="absolute top-3 right-3 text-[10px] font-bold bg-brand-100 text-brand-700 px-2 py-0.5 rounded-md">Default</span>@endif
                    <p class="font-semibold text-sm text-gray-900">{{ $address->full_name }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $address->address_line1 }}</p>
                    <p class="text-sm text-gray-600">{{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $address->phone }}</p>
                    <form method="POST" action="{{ route('account.addresses.destroy', $address) }}" class="mt-3">@csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 hover:text-red-600 font-medium">Delete</button>
                    </form>
                </div>
                @empty
                <p class="text-gray-400 col-span-2 text-center py-10">No saved addresses.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
