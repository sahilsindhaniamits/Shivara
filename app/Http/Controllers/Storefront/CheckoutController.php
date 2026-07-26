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
        } elseif ($subtotal < config('shivara.free_shipping_threshold', 999)) {
            $shippingCharge = config('shivara.standard_rate', 50);
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

        // COD - set as confirmed
        $order->update(['status' => 'confirmed']);

        // Send order confirmation email for COD (+ BCC to shop as new order)
        $customerEmail = $order->address?->email ?? ($order->user?->email ?? null);
        if ($customerEmail) {
            try {
                \Illuminate\Support\Facades\Mail::to($customerEmail)->send(new \App\Mail\OrderStatusMail($order->fresh(['items', 'address']), true));
            } catch (\Exception $e) {
                \Log::warning('COD order email failed: ' . $e->getMessage());
            }
        }

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
        // Magic Checkout redirect flow sends these params
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

            // If no order exists, create one from cart (direct Razorpay/Magic Checkout flow)
            if (!$order) {
                $order = $this->createOrderFromCart($razorpayOrderId, $razorpayPaymentId);
            }

            if (!$order) {
                return redirect()->route('home')->with('error', 'Order could not be created. Please contact support.');
            }

            // Determine payment method (Magic Checkout supports COD)
            $payment = $api->payment->fetch($razorpayPaymentId);
            $paymentMethod = $payment->method ?? 'razorpay';
            $isCod = ($paymentMethod === 'cod');

            $order->update([
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature,
                'payment_status' => $isCod ? 'cod' : 'paid',
                'payment_method' => $isCod ? 'cod' : 'razorpay',
                'status' => 'confirmed',
                'paid_at' => $isCod ? null : now(),
            ]);

            // Send confirmation email
            $customerEmail = $order->address?->email ?? ($order->user?->email ?? null);
            if ($customerEmail) {
                try {
                    \Illuminate\Support\Facades\Mail::to($customerEmail)->send(new \App\Mail\OrderStatusMail($order->fresh(['items', 'address']), true));
                } catch (\Exception $e) {
                    \Log::warning('Order confirmation email failed for ' . $order->order_number . ': ' . $e->getMessage());
                }
            }

            return redirect()->route('order.success', $order->order_number)
                ->with('success', $isCod ? 'COD order placed successfully!' : 'Payment successful! Order confirmed.');

        } catch (\Exception $e) {
            \Log::error('Razorpay verification failed: ' . $e->getMessage());
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
     * Called via AJAX from side cart — Magic Checkout flow
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

        $shipping = $subtotal >= config('shivara.free_shipping_threshold', 999) ? 0 : config('shivara.standard_rate', 50);
        $totalAmount = $subtotal - $discount + $shipping;

        // Build line_items for Magic Checkout product display
        $lineItems = [];
        foreach ($cartItems as $item) {
            $price = $item->variant ? $item->variant->selling_price : $item->product->selling_price;
            $imageUrl = $item->product->primary_image_url ?? '';
            $lineItems[] = [
                'type' => 'e-commerce',
                'sku' => (string) ($item->product->sku ?? $item->product->id),
                'variant_id' => $item->variant ? (string) $item->variant->id : '',
                'price' => (int)($price * 100),
                'offer_price' => (int)($price * 100),
                'tax_amount' => 0,
                'quantity' => (int) $item->quantity,
                'name' => $item->product->name . ($item->variant ? ' - ' . $item->variant->name : ''),
                'description' => mb_substr($item->product->short_description ?? $item->product->name, 0, 250),
                'weight' => (int) ($item->product->weight ?? 200),
                'dimensions' => [
                    'length' => (int) ($item->product->length ?? 10),
                    'width' => (int) ($item->product->width ?? 10),
                    'height' => (int) ($item->product->height ?? 10),
                ],
                'image_url' => $imageUrl,
                'product_url' => url('/products/' . $item->product->slug),
            ];
        }

        // Create Razorpay order with line_items for Magic Checkout
        $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $orderData = [
            'receipt' => 'cart_' . time(),
            'amount' => (int)($totalAmount * 100),
            'currency' => 'INR',
            'line_items' => $lineItems,
            'line_items_total' => (int)($subtotal * 100),
        ];

        // Add shipping charge if applicable
        if ($shipping > 0) {
            $orderData['shipping_fee'] = (int)($shipping * 100);
        }

        $razorpayOrder = $api->order->create($orderData);

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
     * Create order from cart after successful Magic Checkout payment
     * Extracts real shipping address from Razorpay payment details
     */
    private function createOrderFromCart(string $razorpayOrderId, string $razorpayPaymentId): ?Order
    {
        $cartItems = $this->getCartItems();
        if ($cartItems->isEmpty()) return null;

        $checkoutData = session('razorpay_checkout', []);

        // Get payment details from Razorpay to extract address (Magic Checkout)
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $payment = $api->payment->fetch($razorpayPaymentId);

        // Extract customer info
        $customerEmail = $payment->email ?? '';
        $customerPhone = $payment->contact ?? '';

        // Extract shipping address from Magic Checkout
        // Razorpay sends shipping_address in payment.notes or as top-level field
        $shippingAddress = null;
        if (isset($payment->notes) && isset($payment->notes['shipping_address'])) {
            $shippingAddress = json_decode($payment->notes['shipping_address'], true);
        }

        // Also try fetching from the order's shipping_address field
        $razorpayOrderData = $api->order->fetch($razorpayOrderId);
        if (!$shippingAddress && isset($razorpayOrderData->shipping_address)) {
            $shippingAddress = (array) $razorpayOrderData->shipping_address;
        }

        // Build address from extracted data or payment info
        $fullName = 'Customer';
        $addressLine1 = 'Collected via Razorpay Magic Checkout';
        $city = 'N/A';
        $state = 'N/A';
        $pincode = '000000';

        if ($shippingAddress) {
            $fullName = $shippingAddress['name'] ?? $shippingAddress['contact_name'] ?? 'Customer';
            $addressLine1 = trim(($shippingAddress['line1'] ?? '') . ' ' . ($shippingAddress['line2'] ?? '')) ?: 'Via Magic Checkout';
            $city = $shippingAddress['city'] ?? 'N/A';
            $state = $shippingAddress['state'] ?? 'N/A';
            $pincode = $shippingAddress['zipcode'] ?? ($shippingAddress['pincode'] ?? '000000');
        } else {
            // Fallback: try payment-level notes for customer name
            $fullName = $payment->notes['customer_name'] ?? ($customerEmail ? explode('@', $customerEmail)[0] : 'Customer');
        }

        // Create address record
        $address = Address::create([
            'user_id' => auth()->id(),
            'full_name' => $fullName,
            'phone' => $customerPhone,
            'email' => $customerEmail,
            'address_line1' => $addressLine1,
            'city' => $city,
            'state' => $state,
            'pincode' => $pincode,
        ]);

        $subtotal = $checkoutData['subtotal'] ?? $cartItems->sum(fn($i) => ($i->variant ? $i->variant->selling_price : $i->product->selling_price) * $i->quantity);
        $discount = $checkoutData['discount'] ?? 0;
        $shipping = $checkoutData['shipping'] ?? 0;
        $totalAmount = $checkoutData['amount'] ?? ($subtotal - $discount + $shipping);

        // Determine payment method (COD vs prepaid)
        $paymentMethod = ($payment->method === 'cod') ? 'cod' : 'razorpay';

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => auth()->id(),
            'address_id' => $address->id,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $paymentMethod,
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

        // Add free gift if threshold is met
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
