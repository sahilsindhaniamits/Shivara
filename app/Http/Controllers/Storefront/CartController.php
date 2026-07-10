<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();
        return view('storefront.cart', compact('cartItems'));
    }

    public function data()
    {
        $cartItems = $this->getCartItems();
        $items = $cartItems->map(function ($item) {
            $price = $item->variant ? $item->variant->selling_price : $item->product->selling_price;
            $imageUrl = $item->product->primaryImage?->url ?? 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=100&h=100&fit=crop';
            // Fix image path for Hostinger (storage images need /public prefix)
            if (str_starts_with($imageUrl, '/storage/')) {
                $imageUrl = '/public' . $imageUrl;
            }
            return [
                'id' => $item->id,
                'name' => $item->product->name,
                'variant' => $item->variant?->name,
                'price' => (float) $price,
                'quantity' => (int) $item->quantity,
                'image' => $imageUrl,
                'slug' => $item->product->slug,
            ];
        });

        // Calculate subtotal for auto-apply coupon check
        $subtotal = $items->sum(fn($i) => $i['price'] * $i['quantity']);
        $response = ['items' => $items];

        // Check for best auto-apply coupon
        if ($subtotal > 0) {
            $autoCoupon = Coupon::getBestAutoApply($subtotal);
            if ($autoCoupon) {
                $response['auto_coupon'] = [
                    'code' => $autoCoupon->code,
                    'type' => $autoCoupon->type,
                    'value' => (float) $autoCoupon->value,
                    'max_discount' => $autoCoupon->max_discount ? (float) $autoCoupon->max_discount : null,
                    'description' => $autoCoupon->description,
                ];
            }
        }

        return response()->json($response);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'integer|min:1|max:10',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (auth()->check()) {
            $existing = CartItem::where('user_id', auth()->id())
                ->where('product_id', $request->product_id)
                ->where('variant_id', $request->variant_id)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $request->quantity ?? 1);
            } else {
                CartItem::create([
                    'user_id' => auth()->id(),
                    'product_id' => $request->product_id,
                    'variant_id' => $request->variant_id,
                    'quantity' => $request->quantity ?? 1,
                ]);
            }
        } else {
            // Session-based cart for guests
            $cart = session()->get('cart', []);
            $key = $request->product_id . '-' . ($request->variant_id ?? '0');

            if (isset($cart[$key])) {
                $cart[$key]['quantity'] += $request->quantity ?? 1;
            } else {
                $cart[$key] = [
                    'product_id' => $request->product_id,
                    'variant_id' => $request->variant_id,
                    'quantity' => $request->quantity ?? 1,
                ];
            }
            session()->put('cart', $cart);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => "{$product->name} added to cart!"]);
        }

        return back()->with('success', "{$product->name} added to cart!")->with('open_cart', true);
    }

    public function update(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'quantity' => 'required|integer|min:0|max:10',
        ]);

        if (auth()->check()) {
            if ($request->quantity === 0 || $request->quantity == 0) {
                CartItem::where('id', $request->item_id)->where('user_id', auth()->id())->delete();
            } else {
                CartItem::where('id', $request->item_id)
                    ->where('user_id', auth()->id())
                    ->update(['quantity' => $request->quantity]);
            }
        } else {
            $cart = session()->get('cart', []);
            if ($request->quantity == 0) {
                unset($cart[$request->item_id]);
            } else {
                if (isset($cart[$request->item_id])) {
                    $cart[$request->item_id]['quantity'] = $request->quantity;
                }
            }
            session()->put('cart', $cart);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request)
    {
        if (auth()->check()) {
            CartItem::where('id', $request->item_id)->where('user_id', auth()->id())->delete();
        } else {
            $cart = session()->get('cart', []);
            unset($cart[$request->item_id]);
            session()->put('cart', $cart);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        } else {
            session()->forget('cart');
        }

        return back()->with('success', 'Cart cleared.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon || !$coupon->isValid()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Invalid or expired coupon code.'], 422);
            }
            return back()->with('error', 'Invalid or expired coupon code.');
        }

        session()->put('coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'max_discount' => $coupon->max_discount,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Coupon '{$coupon->code}' applied!",
                'coupon' => [
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => (float) $coupon->value,
                    'max_discount' => $coupon->max_discount ? (float) $coupon->max_discount : null,
                ],
            ]);
        }

        return back()->with('success', "Coupon '{$coupon->code}' applied!");
    }

    private function getCartItems()
    {
        if (auth()->check()) {
            return CartItem::where('user_id', auth()->id())
                ->with(['product.primaryImage', 'variant'])
                ->get();
        }

        $cart = session()->get('cart', []);
        $items = collect();

        foreach ($cart as $key => $item) {
            $product = Product::with('primaryImage')->find($item['product_id']);
            if ($product) {
                $items->push((object) [
                    'id' => $key,
                    'product' => $product,
                    'variant' => $item['variant_id'] ? \App\Models\ProductVariant::find($item['variant_id']) : null,
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        return $items;
    }
}
