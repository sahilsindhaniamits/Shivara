<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')
            ->withCount('orders')
            ->withSum('orders', 'total_amount');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                    ->orWhere('email', 'LIKE', "%{$request->search}%")
                    ->orWhere('phone', 'LIKE', "%{$request->search}%");
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        $customer->load(['orders' => fn($q) => $q->orderBy('created_at', 'desc')->take(10), 'addresses']);
        $totalSpent = $customer->orders()->where('payment_status', 'paid')->sum('total_amount');

        return view('admin.customers.show', compact('customer', 'totalSpent'));
    }

    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $customer->update($data);

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated.');
    }

    public function destroy(User $customer)
    {
        if ($customer->orders()->count() > 0) {
            return back()->with('error', 'Cannot delete customer with existing orders.');
        }
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted.');
    }

    public function toggleStatus(User $customer)
    {
        $customer->update(['is_active' => !$customer->is_active]);

        return back()->with('success', $customer->is_active ? 'Customer activated.' : 'Customer deactivated.');
    }
}
