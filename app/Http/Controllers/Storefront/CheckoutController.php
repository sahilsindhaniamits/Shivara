<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $addresses = auth()->check() ? auth()->user()->addresses : collect();
        $indianStates = config('shivara.indian_states');

        return view('storefront.checkout', compact('cartItems', 'addresses', 'indianStates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email',
            'address_line1' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'required|string|size:6',
            'payment_method' => 'required|in:razorpay,cod',
            'shipping_method' => 'required|in:standard,express',
        ]);

        $cartItems = $this->getCartItems();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Create or reuse address
        $address = Address::create([
            'user_id' => auth()->id(),
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'landmark' => $request->landmark,
        ]);

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            $price = $item->variant ? $item->variant->selling_price : $item->product->selling_price;
            return $price * $item->quantity;
        });

        $shippingCharge = 0;
        if ($request->shipping_method === 'express') {
            $shippingCharge = config('shivara.express_rate', 149);
        } elseif ($subtotal < config('shivara.free_shipping_threshold', 299)) {
            $shippingCharge = config('shivara.standard_rate', 79);
        }
        // Free standard shipping if above threshold, Express always charged

        $codCharge = $request->payment_method === 'cod' ? config('shivara.cod_charge') : 0;
        $discount = 0;
        $couponId = null;
        $couponCode = null;

        // Apply coupon if exists in session (manually applied)
        if (session()->has('coupon')) {
            $couponData = session('coupon');
            $coupon = Coupon::find($couponData['id']);
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotal);
                $couponId = $coupon->id;
                $couponCode = $coupon->code;
                $coupon->increment('usage_count');
            }
        }

        // If no manual coupon applied, try auto-apply best coupon
        if ($discount == 0 && !$couponId) {
            $autoCoupon = Coupon::getBestAutoApply($subtotal);
            if ($autoCoupon) {
                $discount = $autoCoupon->calculateDiscount($subtotal);
                $couponId = $autoCoupon->id;
                $couponCode = $autoCoupon->code;
                $autoCoupon->increment('usage_count');
            }
        }

        $totalAmount = $subtotal - $discount + $shippingCharge + $codCharge;

        // Create order
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => auth()->id(),
            'address_id' => $address->id,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $request->payment_method,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping_charge' => $shippingCharge,
            'total_amount' => $totalAmount,
            'coupon_id' => $couponId,
            'coupon_code' => $couponCode,
            'shipping_method' => $request->shipping_method,
        ]);

        // Create order items
        foreach ($cartItems as $item) {
            $price = $item->variant ? $item->variant->selling_price : $item->product->selling_price;
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product->id,
                'variant_id' => $item->variant?->id,
                'product_name' => $item->product->name,
                'variant_name' => $item->variant?->name,
                'quantity' => $item->quantity,
                'price' => $price,
                'total_price' => $price * $item->quantity,
                'gst_rate' => $item->product->gst_rate,
                'gst_amount' => ($price * $item->quantity) * ($item->product->gst_rate / (100 + $item->product->gst_rate)),
            ]);

            // Decrease stock
            if ($item->variant) {
                $item->variant->decrement('stock', $item->quantity);
            } else {
                if (!is_null($item->product->stock)) {
                    $item->product->decrement('stock', $item->quantity);
                }
            }
        }

        // Add free gift as order item if threshold is met
        $fgEnabled = \App\Models\Setting::get('free_gift_enabled', 'false') === 'true';
        $fgThreshold = (float) \App\Models\Setting::get('free_gift_threshold', config('shivara.free_gift_threshold', 1499));
        $fgProductId = \App\Models\Setting::get('free_gift_product_id');
        if ($fgEnabled && $fgProductId && $subtotal >= $fgThreshold) {
            $fgProduct = \App\Models\Product::find($fgProductId);
            if ($fgProduct) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $fgProduct->id,
                    'variant_id' => null,
                    'product_name' => $fgProduct->name . ' (Free Gift)',
                    'variant_name' => null,
                    'quantity' => 1,
                    'price' => 0,
                    'total_price' => 0,
                    'gst_rate' => 0,
                    'gst_amount' => 0,
                ]);
            }
        }

        // Clear cart
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        }
        session()->forget('cart');
        session()->forget('coupon');

        // Handle payment
        if ($request->payment_method === 'razorpay') {
            return $this->initiateRazorpay($order);
        }

        // COD - keep as pending (admin will confirm manually)
        $order->update(['status' => 'pending']);

        return redirect()->route('order.success', $order->order_number)
            ->with('success', 'Order placed successfully!');
    }

    private function initiateRazorpay(Order $order)
    {
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        $razorpayOrder = $api->order->create([
            'receipt' => $order->order_number,
            'amount' => (int)($order->total_amount * 100), // Amount in paise
            'currency' => 'INR',
        ]);

        $order->update(['razorpay_order_id' => $razorpayOrder['id']]);

        return view('storefront.payment', [
            'order' => $order,
            'razorpayOrderId' => $razorpayOrder['id'],
            'razorpayKey' => config('services.razorpay.key'),
            'amount' => (int)($order->total_amount * 100),
        ]);
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature' => 'required',
        ]);

        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        $attributes = [
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature,
        ];

        try {
            $api->utility->verifyPaymentSignature($attributes);

            // Check if order already exists (standard checkout flow)
            $order = Order::where('razorpay_order_id', $request->razorpay_order_id)->first();

            // If no order exists, create one from cart (direct Razorpay flow)
            if (!$order) {
                $order = $this->createOrderFromCart($request->razorpay_order_id, $request->razorpay_payment_id);
            }

            if (!$order) {
                return redirect()->route('home')->with('error', 'Order could not be created. Please contact support.');
            }

            $order->update([
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'paid_at' => now(),
            ]);

            // Send confirmation email
            $customerEmail = $order->address?->email ?? ($order->user?->email ?? null);
            if ($customerEmail) {
                try { \Illuminate\Support\Facades\Mail::to($customerEmail)->send(new \App\Mail\OrderStatusMail($order->fresh(['items', 'address']))); } catch (\Exception $e) {}
            }

            return redirect()->route('order.success', $order->order_number)
                ->with('success', 'Payment successful! Order confirmed.');

        } catch (\Exception $e) {
            return redirect()->route('cart.index')
                ->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    public function success(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('items')
            ->firstOrFail();

        return view('storefront.order-success', compact('order'));
    }

    /**
     * Create Razorpay order directly from cart (skips checkout page)
     * Called via AJAX from side cart
     */
    public function createRazorpayOrder(Request $request)
    {
        $cartItems = $this->getCartItems();
        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        $subtotal = $cartItems->sum(function ($item) {
            $price = $item->variant ? $item->variant->selling_price : $item->product->selling_price;
            return $price * $item->quantity;
        });

        // Apply auto-coupon if available
        $discount = 0;
        $couponCode = null;
        $autoCoupon = \App\Models\Coupon::getBestAutoApply($subtotal);
        if ($autoCoupon) {
            $discount = $autoCoupon->calculateDiscount($subtotal);
            $couponCode = $autoCoupon->code;
        }

        // Session coupon overrides auto
        if (session()->has('coupon')) {
            $sessionCoupon = \App\Models\Coupon::find(session('coupon.id'));
            if ($sessionCoupon && $sessionCoupon->isValid()) {
                $discount = $sessionCoupon->calculateDiscount($subtotal);
                $couponCode = $sessionCoupon->code;
            }
        }

        $shipping = $subtotal >= config('shivara.free_shipping_threshold', 299) ? 0 : config('shivara.standard_rate', 79);
        $totalAmount = $subtotal - $discount + $shipping;

        // Create Razorpay order
        $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $razorpayOrder = $api->order->create([
            'receipt' => 'cart_' . time(),
            'amount' => (int)($totalAmount * 100),
            'currency' => 'INR',
        ]);

        // Store in session for later verification
        session()->put('razorpay_checkout', [
            'order_id' => $razorpayOrder['id'],
            'amount' => $totalAmount,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'coupon_code' => $couponCode,
        ]);

        return response()->json([
            'success' => true,
            'razorpay_order_id' => $razorpayOrder['id'],
            'razorpay_key' => config('services.razorpay.key'),
            'amount' => (int)($totalAmount * 100),
            'currency' => 'INR',
            'name' => 'Shivara',
            'description' => 'Order from Shivara',
            'prefill' => [
                'name' => auth()->user()->name ?? '',
                'email' => auth()->user()->email ?? '',
                'contact' => auth()->user()->phone ?? '',
            ],
        ]);
    }

    /**
     * Create order from cart after successful direct Razorpay payment
     */
    private function createOrderFromCart(string $razorpayOrderId, string $razorpayPaymentId): ?Order
    {
        $cartItems = $this->getCartItems();
        if ($cartItems->isEmpty()) return null;

        $checkoutData = session('razorpay_checkout', []);

        // Get payment details from Razorpay to extract address (Magic Checkout)
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $payment = $api->payment->fetch($razorpayPaymentId);

        $customerName = $payment->notes['customer_name'] ?? ($payment->email ? explode('@', $payment->email)[0] : 'Customer');
        $customerEmail = $payment->email ?? '';
        $customerPhone = $payment->contact ?? '';

        // Create address from Razorpay payment info
        $address = Address::create([
            'user_id' => auth()->id(),
            'full_name' => $customerName,
            'phone' => $customerPhone,
            'email' => $customerEmail,
            'address_line1' => 'Collected via Razorpay',
            'city' => 'N/A',
            'state' => 'N/A',
            'pincode' => '000000',
        ]);

        $subtotal = $checkoutData['subtotal'] ?? $cartItems->sum(fn($i) => ($i->variant ? $i->variant->selling_price : $i->product->selling_price) * $i->quantity);
        $discount = $checkoutData['discount'] ?? 0;
        $shipping = $checkoutData['shipping'] ?? 0;
        $totalAmount = $checkoutData['amount'] ?? ($subtotal - $discount + $shipping);

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => auth()->id(),
            'address_id' => $address->id,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => 'razorpay',
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping_charge' => $shipping,
            'total_amount' => $totalAmount,
            'coupon_code' => $checkoutData['coupon_code'] ?? null,
            'razorpay_order_id' => $razorpayOrderId,
            'shipping_method' => 'standard',
        ]);

        foreach ($cartItems as $item) {
            $price = $item->variant ? $item->variant->selling_price : $item->product->selling_price;
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product->id,
                'variant_id' => $item->variant?->id,
                'product_name' => $item->product->name,
                'variant_name' => $item->variant?->name,
                'quantity' => $item->quantity,
                'price' => $price,
                'total_price' => $price * $item->quantity,
                'gst_rate' => $item->product->gst_rate ?? 0,
                'gst_amount' => 0,
            ]);
            if ($item->variant) { $item->variant->decrement('stock', $item->quantity); }
            elseif (!is_null($item->product->stock)) { $item->product->decrement('stock', $item->quantity); }
        }

        // Clear cart
        if (auth()->check()) { CartItem::where('user_id', auth()->id())->delete(); }
        session()->forget('cart');
        session()->forget('coupon');
        session()->forget('razorpay_checkout');

        return $order;
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
            $product = \App\Models\Product::with('primaryImage')->find($item['product_id']);
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
