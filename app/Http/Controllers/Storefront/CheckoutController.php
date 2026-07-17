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
            return $this->initiateRazorpay($order->load('items.product'));
        }

        // COD - keep as pending (admin will confirm manually)
        $order->update(['status' => 'pending']);

        return redirect()->route('order.success', $order->order_number)
            ->with('success', 'Order placed successfully!');
    }

    private function initiateRazorpay(Order $order)
    {
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        // Build line_items from order items for Magic Checkout
        $lineItems = [];
        $lineItemsTotal = 0;
        foreach ($order->items as $item) {
            $product = $item->product;
            $offerPrice = (int)($item->price * 100); // paise
            $lineItemsTotal += $offerPrice * $item->quantity;

            $imageUrl = $product ? $product->primary_image_url : null;
            if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
                $imageUrl = config('app.url') . $imageUrl;
            }

            $lineItems[] = [
                'sku' => $product ? ($product->sku ?: ('SKU-' . $product->id)) : ('SKU-' . $item->product_id),
                'variant_id' => $item->variant_id ? (string)$item->variant_id : (string)$item->product_id,
                'price' => $offerPrice,
                'offer_price' => $offerPrice,
                'quantity' => (int)$item->quantity,
                'name' => $item->product_name . ($item->variant_name ? ' - ' . $item->variant_name : ''),
                'description' => $product ? substr(strip_tags($product->short_description ?: $product->description ?: $product->name), 0, 200) : $item->product_name,
                'image_url' => $imageUrl ?: (config('app.url') . '/public/shivaralogo1.png'),
            ];

            if ($product && $product->weight) {
                $lineItems[count($lineItems) - 1]['weight'] = (int)$product->weight;
            }
        }

        $orderData = [
            'receipt' => $order->order_number,
            'amount' => (int)($order->total_amount * 100),
            'currency' => 'INR',
            'line_items_total' => $lineItemsTotal,
            'line_items' => $lineItems,
        ];

        $razorpayOrder = $api->order->create($orderData);

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
        // Magic Checkout redirects with these parameters
        $razorpayOrderId = $request->input('razorpay_order_id');
        $razorpayPaymentId = $request->input('razorpay_payment_id');
        $razorpaySignature = $request->input('razorpay_signature');

        if (!$razorpayOrderId || !$razorpayPaymentId || !$razorpaySignature) {
            return redirect()->route('cart.index')
                ->with('error', 'Payment verification failed. Missing payment details.');
        }

        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        $attributes = [
            'razorpay_order_id' => $razorpayOrderId,
            'razorpay_payment_id' => $razorpayPaymentId,
            'razorpay_signature' => $razorpaySignature,
        ];

        try {
            $api->utility->verifyPaymentSignature($attributes);

            // Check if order already exists (standard checkout flow)
            $order = Order::where('razorpay_order_id', $razorpayOrderId)->first();

            // If no order exists, create one from cart (Magic Checkout / direct Razorpay flow)
            if (!$order) {
                $order = $this->createOrderFromCart($razorpayOrderId, $razorpayPaymentId);
            }

            if (!$order) {
                return redirect()->route('home')->with('error', 'Order could not be created. Please contact support.');
            }

            $order->update([
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature,
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
            \Illuminate\Support\Facades\Log::error('Razorpay verification failed', ['error' => $e->getMessage(), 'order_id' => $razorpayOrderId]);
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
     * Called via AJAX from side cart - creates Magic Checkout order with line_items
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

        // Build line_items for Magic Checkout (mandatory)
        $lineItems = [];
        $lineItemsTotal = 0;
        foreach ($cartItems as $item) {
            $product = $item->product;
            $price = $item->variant ? $item->variant->selling_price : $product->selling_price;
            $mrp = $item->variant ? ($item->variant->mrp ?? $price) : ($product->mrp ?? $price);
            $offerPrice = (int)($price * 100); // in paise
            $itemTotal = $offerPrice * $item->quantity;
            $lineItemsTotal += $itemTotal;

            $imageUrl = $product->primary_image_url;
            if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
                $imageUrl = config('app.url') . $imageUrl;
            }

            $lineItems[] = [
                'sku' => $product->sku ?: ('SKU-' . $product->id),
                'variant_id' => $item->variant ? (string)$item->variant->id : (string)$product->id,
                'price' => (int)($mrp * 100),
                'offer_price' => $offerPrice,
                'quantity' => (int)$item->quantity,
                'name' => $product->name . ($item->variant ? ' - ' . $item->variant->name : ''),
                'description' => substr(strip_tags($product->short_description ?: $product->description ?: $product->name), 0, 200),
                'image_url' => $imageUrl ?: (config('app.url') . '/public/shivaralogo1.png'),
                'product_url' => route('products.show', $product->slug),
            ];

            if ($product->weight) {
                $lineItems[count($lineItems) - 1]['weight'] = (int)$product->weight;
            }
        }

        // Create Razorpay order with line_items for Magic Checkout
        $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        $receipt = 'cart_' . time();
        $orderData = [
            'receipt' => $receipt,
            'amount' => (int)($totalAmount * 100),
            'currency' => 'INR',
            'line_items_total' => $lineItemsTotal,
            'line_items' => $lineItems,
        ];

        // Add notes for pre-discount if coupon applied
        if ($discount > 0 && $couponCode) {
            $orderData['notes'] = [
                'coupon_code' => $couponCode,
                'prediscount_applied' => (string)(int)($discount * 100),
            ];
        }

        $razorpayOrder = $api->order->create($orderData);

        // Store in session for later verification
        session()->put('razorpay_checkout', [
            'order_id' => $razorpayOrder['id'],
            'receipt' => $receipt,
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
            'callback_url' => route('payment.verify'),
            'prefill' => [
                'name' => auth()->user()->name ?? '',
                'email' => auth()->user()->email ?? '',
                'contact' => auth()->user()->phone ?? '',
            ],
            'coupon_code' => $couponCode,
        ]);
    }

    /**
     * Create order from cart after successful Magic Checkout payment.
     * Fetches the shipping address from Razorpay order API (populated by Magic Checkout).
     */
    private function createOrderFromCart(string $razorpayOrderId, string $razorpayPaymentId): ?Order
    {
        $cartItems = $this->getCartItems();
        if ($cartItems->isEmpty()) return null;

        $checkoutData = session('razorpay_checkout', []);

        // Fetch order from Razorpay to get customer_details.shipping_address (Magic Checkout populates this)
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        $customerName = 'Customer';
        $customerEmail = '';
        $customerPhone = '';
        $addressLine1 = 'Address collected via Razorpay';
        $addressLine2 = '';
        $city = '';
        $state = '';
        $pincode = '';
        $rzpOrderArr = [];

        try {
            // Fetch the Razorpay order to get shipping address from Magic Checkout
            $rzpOrder = $api->order->fetch($razorpayOrderId);
            $rzpOrderArr = $rzpOrder->toArray();

            if (!empty($rzpOrderArr['customer_details'])) {
                $custDetails = $rzpOrderArr['customer_details'];
                $customerEmail = $custDetails['email'] ?? '';
                $customerPhone = $custDetails['contact'] ?? '';

                if (!empty($custDetails['shipping_address'])) {
                    $shippingAddr = $custDetails['shipping_address'];
                    $customerName = $shippingAddr['name'] ?? $customerName;
                    $addressLine1 = $shippingAddr['line1'] ?? $addressLine1;
                    $addressLine2 = $shippingAddr['line2'] ?? '';
                    $city = $shippingAddr['city'] ?? '';
                    $state = $shippingAddr['state'] ?? '';
                    $pincode = $shippingAddr['zipcode'] ?? '';
                    $customerPhone = $shippingAddr['contact'] ?? $customerPhone;
                }
            }

            // Fallback: if no address from order, try fetching from payment
            if ($addressLine1 === 'Address collected via Razorpay') {
                $payment = $api->payment->fetch($razorpayPaymentId);
                $customerEmail = $customerEmail ?: ($payment->email ?? '');
                $customerPhone = $customerPhone ?: ($payment->contact ?? '');
                $customerName = $customerName !== 'Customer' ? $customerName : ($payment->notes['customer_name'] ?? (explode('@', $customerEmail)[0] ?? 'Customer'));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to fetch Razorpay order/payment details', ['error' => $e->getMessage()]);
            // Try payment fetch as fallback
            try {
                $payment = $api->payment->fetch($razorpayPaymentId);
                $customerEmail = $payment->email ?? '';
                $customerPhone = $payment->contact ?? '';
                $customerName = $payment->notes['customer_name'] ?? (explode('@', $customerEmail)[0] ?? 'Customer');
            } catch (\Exception $e2) {
                // Continue with defaults
            }
        }

        // Clean phone number (remove +91 prefix if present for storage)
        $customerPhone = preg_replace('/^\+91/', '', $customerPhone);
        $customerPhone = preg_replace('/^\+/', '', $customerPhone);

        // Create address from Razorpay Magic Checkout shipping info
        $address = Address::create([
            'user_id' => auth()->id(),
            'full_name' => $customerName,
            'phone' => $customerPhone,
            'email' => $customerEmail,
            'address_line1' => $addressLine1,
            'address_line2' => $addressLine2,
            'city' => $city ?: 'N/A',
            'state' => $state ?: 'N/A',
            'pincode' => $pincode ?: '000000',
        ]);

        $subtotal = $checkoutData['subtotal'] ?? $cartItems->sum(fn($i) => ($i->variant ? $i->variant->selling_price : $i->product->selling_price) * $i->quantity);
        $discount = $checkoutData['discount'] ?? 0;
        $shipping = $checkoutData['shipping'] ?? 0;
        $totalAmount = $checkoutData['amount'] ?? ($subtotal - $discount + $shipping);

        // Check if Razorpay order has promotions applied (coupon applied in Magic Checkout)
        $couponCode = $checkoutData['coupon_code'] ?? null;
        $couponId = null;
        if (!$couponCode && !empty($rzpOrderArr['promotions'])) {
            // Customer applied a coupon inside Magic Checkout
            $firstPromo = $rzpOrderArr['promotions'][0] ?? null;
            if ($firstPromo) {
                $couponCode = $firstPromo['code'] ?? null;
                $promoDiscount = ($firstPromo['value'] ?? 0) / 100; // paise to rupees
                if ($promoDiscount > 0) {
                    $discount = $promoDiscount;
                    $totalAmount = $subtotal - $discount + $shipping;
                }
            }
        }

        // Increment coupon usage if applicable
        if ($couponCode) {
            $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
            if ($coupon) {
                $couponId = $coupon->id;
                $coupon->increment('usage_count');
            }
        }

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
            'coupon_id' => $couponId,
            'coupon_code' => $couponCode,
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

        // Add free gift if threshold met
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
