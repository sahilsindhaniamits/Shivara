<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Address;
use App\Models\WishlistItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // Auto-link any guest orders matching this user's phone/email
        $this->linkGuestOrders($user);

        $recentOrders = $user->orders()->with('items')->orderBy('created_at', 'desc')->take(5)->get();
        $addressCount = $user->addresses()->count();
        $wishlistCount = $user->wishlist()->count();

        return view('storefront.account.dashboard', compact('user', 'recentOrders', 'addressCount', 'wishlistCount'));
    }

    // Orders
    public function orders()
    {
        $orders = auth()->user()->orders()
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('storefront.account.orders', compact('orders'));
    }

    public function orderDetail(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with(['items.product', 'address', 'timeline'])
            ->firstOrFail();

        return view('storefront.account.order-detail', compact('order'));
    }

    // Profile
    public function profile()
    {
        $user = auth()->user();
        return view('storefront.account.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15|unique:users,phone,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully.');
    }

    // Addresses
    public function addresses()
    {
        $addresses = auth()->user()->addresses()->orderBy('is_default', 'desc')->get();
        return view('storefront.account.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address_line1' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'required|string|size:6',
        ]);

        $data = $request->only(['full_name', 'phone', 'address_line1', 'address_line2', 'city', 'state', 'pincode', 'landmark']);
        $data['user_id'] = auth()->id();

        if ($request->boolean('is_default')) {
            auth()->user()->addresses()->update(['is_default' => false]);
            $data['is_default'] = true;
        }

        Address::create($data);

        return back()->with('success', 'Address added successfully.');
    }

    public function deleteAddress(Address $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $address->delete();
        return back()->with('success', 'Address deleted.');
    }

    // Wishlist
    public function wishlist()
    {
        $wishlistItems = auth()->user()->wishlist()
            ->with('product.primaryImage')
            ->paginate(12);

        return view('storefront.account.wishlist', compact('wishlistItems'));
    }

    public function addToWishlist(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        WishlistItem::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
        ]);

        return back()->with('success', 'Added to wishlist!');
    }

    public function removeFromWishlist(WishlistItem $wishlistItem)
    {
        if ($wishlistItem->user_id !== auth()->id()) {
            abort(403);
        }

        $wishlistItem->delete();
        return back()->with('success', 'Removed from wishlist.');
    }

    /**
     * Link guest orders to this user based on phone/email match
     */
    private function linkGuestOrders($user): void
    {
        $phone = $user->phone;
        $email = $user->email;

        if (!$phone && !$email) return;

        $phonePatterns = [];
        if ($phone && strlen($phone) >= 10) {
            $last10 = substr($phone, -10);
            $phonePatterns = [
                $last10,
                '+91' . $last10,
                '+91 ' . $last10,
                '91' . $last10,
                '0' . $last10,
            ];
        }

        $guestOrders = Order::whereNull('user_id')
            ->whereHas('address', function ($query) use ($phonePatterns, $email) {
                $query->where(function ($q) use ($phonePatterns, $email) {
                    foreach ($phonePatterns as $pattern) {
                        $q->orWhere('phone', $pattern);
                    }
                    if (count($phonePatterns) > 0) {
                        $q->orWhere('phone', 'LIKE', '%' . $phonePatterns[0]);
                    }
                    if ($email) {
                        $q->orWhere('email', $email);
                    }
                });
            })
            ->get();

        foreach ($guestOrders as $order) {
            $order->update(['user_id' => $user->id]);
            if ($order->address && !$order->address->user_id) {
                $order->address->update(['user_id' => $user->id]);
            }
        }

        if ($guestOrders->count() > 0) {
            \Log::info("Linked {$guestOrders->count()} guest orders to user {$user->id} from dashboard");
        }
    }
}
