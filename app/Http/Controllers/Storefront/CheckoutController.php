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

        $codCharge = $request->payment_method === 'cod' ? config('shivara.cod_charge') : 0;
        $discount = 0;
        $couponId = null;
        $couponCode = null;

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

            if ($item->variant) {
                $item->variant->decrement('stock', $item->quantity);
            } else {
                if (!is_null($item->product->stock)) {
                    $item->product->decrement('stock', $item->quantity);
                }
            }
        }

        // Free gift
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

        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        }
        session()->forget('cart');
        session()->forget('coupon');

        if ($request->payment_method === 'razorpay') {
            return $this->initiateRazorpay($order);
        }

        $order->update(['status' => 'confirmed']);

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
            'amount' => (int)($order->total_amount * 100),
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
        $razorpayOrderId = $request->input('razorpay_order_id');
        $razorpayPaymentId = $request->input('razorpay_payment_id');
        $razorpaySignature = $request->input('razorpay_signature');

        \Log::info('verifyPayment called', [
            'order_id' => $razorpayOrderId,
            'payment_id' => $razorpayPaymentId,
            'has_signature' => !empty($razorpaySignature),
        ]);

        if (!$razorpayOrderId || !$razorpayPaymentId) {
            \Log::error('verifyPayment: missing order_id or payment_id');
            return redirect()->route('cart.index')
                ->with('error', 'Payment verification failed. Missing payment details.');
        }

        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        try {
            // Fetch payment to determine method (COD vs prepaid)
            $isCod = false;
            try {
                $payment = $api->payment->fetch($razorpayPaymentId);
                $isCod = (($payment->method ?? '') === 'cod');
                \Log::info('Payment fetched', ['method' => $payment->method ?? 'unknown', 'isCod' => $isCod]);
            } catch (\Exception $e) {
                \Log::warning('Could not fetch payment to check method: ' . $e->getMessage());
                // If we can't fetch payment, try signature verification
            }

            // Only verify signature for prepaid (non-COD) payments
            if (!$isCod && !empty($razorpaySignature)) {
                $api->utility->verifyPaymentSignature([
                    'razorpay_order_id' => $razorpayOrderId,
                    'razorpay_payment_id' => $razorpayPaymentId,
                    'razorpay_signature' => $razorpaySignature,
                ]);
                \Log::info('Signature verified for prepaid payment');
            }

            // Find or create order
            $order = Order::where('razorpay_order_id', $razorpayOrderId)->first();
            if (!$order) {
                $order = $this->createOrderFromCart($razorpayOrderId, $razorpayPaymentId);
            }

            if (!$order) {
                \Log::error('Could not find or create order for: ' . $razorpayOrderId);
                return redirect()->route('home')->with('error', 'Order could not be created. Please contact support.');
            }

            // Update order — set to CONFIRMED directly (no pending state)
            $order->update([
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature ?? '',
                'payment_status' => $isCod ? 'cod' : 'paid',
                'payment_method' => $isCod ? 'cod' : 'razorpay',
                'status' => 'confirmed',
                'paid_at' => $isCod ? null : now(),
            ]);

            \Log::info('Order confirmed', ['order' => $order->order_number, 'method' => $isCod ? 'cod' : 'razorpay']);

            // Send confirmation email
            $customerEmail = $order->address?->email ?? ($order->user?->email ?? null);
            if ($customerEmail) {
                try {
                    \Illuminate\Support\Facades\Mail::to($customerEmail)->send(new \App\Mail\OrderStatusMail($order->fresh(['items', 'address']), true));
                } catch (\Exception $e) {
                    \Log::warning('Order confirmation email failed: ' . $e->getMessage());
                }
            }

            return redirect()->route('order.success', $order->order_number)
                ->with('success', $isCod ? 'COD order placed!' : 'Payment successful!');

        } catch (\Exception $e) {
            \Log::error('verifyPayment EXCEPTION: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->route('cart.index')
                ->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    public function success(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('items')->firstOrFail();
        return view('storefront.order-success', compact('order'));
    }

    /**
     * Create Razorpay 1CC order with line_items for full Magic Checkout flow.
     * line_items triggers the sequential flow: Phone → OTP → Address → Delivery → Payment/COD
     * Without line_items, Razorpay shows OPC (one-page-checkout) instead.
     */
    public function createRazorpayOrder(Request $request)
    {
        try {
            $cartItems = $this->getCartItems();
            if ($cartItems->isEmpty()) {
                return response()->json(['success' => false, 'error' => 'Your cart is empty.']);
            }

            $subtotal = $cartItems->sum(function ($item) {
                $price = $item->variant ? $item->variant->selling_price : $item->product->selling_price;
                return $price * $item->quantity;
            });

            // Coupon
            $discount = 0;
            $couponCode = null;
            if (session()->has('coupon')) {
                $sessionCoupon = Coupon::find(session('coupon.id'));
                if ($sessionCoupon && $sessionCoupon->isValid()) {
                    $discount = $sessionCoupon->calculateDiscount($subtotal);
                    $couponCode = $sessionCoupon->code;
                }
            }
            if ($discount == 0) {
                $autoCoupon = Coupon::getBestAutoApply($subtotal);
                if ($autoCoupon) {
                    $discount = $autoCoupon->calculateDiscount($subtotal);
                    $couponCode = $autoCoupon->code;
                }
            }

            $shipping = $subtotal >= config('shivara.free_shipping_threshold', 999) ? 0 : config('shivara.standard_rate', 50);
            $totalAmount = max(1, $subtotal - $discount + $shipping);

            // Build line_items — REQUIRED for full 1CC Magic Checkout flow
            $lineItems = [];
            foreach ($cartItems as $item) {
                $price = $item->variant ? $item->variant->selling_price : $item->product->selling_price;
                $imageUrl = $item->product->primary_image_url ?? '';
                // Razorpay requires a valid URL for image_url — skip if empty/invalid
                if ($imageUrl && !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                    // Might be a relative path like /storage/... — make it absolute
                    $imageUrl = url($imageUrl);
                }
                if (!$imageUrl || !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                    $imageUrl = url('/shivaralogo1.png');
                }

                $lineItem = [
                    'type' => 'e-commerce',
                    'sku' => (string) ($item->product->sku ?? $item->product->id),
                    'variant_id' => $item->variant ? (string) $item->variant->id : '',
                    'price' => (string) round($price * 100),
                    'offer_price' => (string) round($price * 100),
                    'tax_amount' => 0,
                    'quantity' => (int) $item->quantity,
                    'name' => mb_substr($item->product->name . ($item->variant ? ' - ' . $item->variant->name : ''), 0, 200),
                    'description' => mb_substr($item->product->short_description ?? $item->product->name, 0, 200),
                    'weight' => (int) ($item->product->weight ?? 200),
                    'dimensions' => [
                        'length' => (int) ($item->product->length ?? 10),
                        'width' => (int) ($item->product->width ?? 10),
                        'height' => (int) ($item->product->height ?? 10),
                    ],
                    'image_url' => $imageUrl,
                    'product_url' => url('/products/' . ($item->product->slug ?? $item->product->id)),
                ];
                $lineItems[] = $lineItem;
            }

            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            $orderPayload = [
                'amount' => (int) round($totalAmount * 100),
                'currency' => 'INR',
                'receipt' => 'cart_' . time() . '_' . rand(100, 999),
                'line_items' => $lineItems,
                'line_items_total' => (int) round($subtotal * 100),
            ];

            \Log::info('Razorpay 1CC order payload', $orderPayload);

            $razorpayOrder = $api->order->create($orderPayload);

            // Save for verification later
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
                'amount' => (int) round($totalAmount * 100),
                'prefill' => [
                    'name' => auth()->check() ? (auth()->user()->name ?? '') : '',
                    'email' => auth()->check() ? (auth()->user()->email ?? '') : '',
                    'contact' => auth()->check() ? (auth()->user()->phone ?? '') : '',
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error('createRazorpayOrder FAILED: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'error' => 'Payment error: ' . $e->getMessage(),
            ]);
        }
    }

    private function createOrderFromCart(string $razorpayOrderId, string $razorpayPaymentId): ?Order
    {
        $cartItems = $this->getCartItems();
        if ($cartItems->isEmpty()) return null;

        $checkoutData = session('razorpay_checkout', []);
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        $customerName = 'Customer';
        $customerEmail = '';
        $customerPhone = '';
        $addressLine1 = 'Via Razorpay Magic Checkout';
        $addressLine2 = '';
        $city = '';
        $state = '';
        $pincode = '000000';

        try {
            // Fetch payment for contact info
            $payment = $api->payment->fetch($razorpayPaymentId);
            $customerEmail = $payment->email ?? '';
            $customerPhone = $payment->contact ?? '';

            // For 1CC Magic Checkout, shipping address is stored in the ORDER object
            $razorpayOrder = $api->order->fetch($razorpayOrderId);
            $orderArray = json_decode(json_encode($razorpayOrder), true) ?? [];

            \Log::info('Razorpay order data for address extraction', [
                'order_id' => $razorpayOrderId,
                'order_keys' => array_keys($orderArray),
                'has_customer_details' => isset($orderArray['customer_details']),
                'customer_details' => $orderArray['customer_details'] ?? 'not set',
            ]);

            // Try customer_details.shipping_address (1CC format)
            $shippingAddr = null;
            if (isset($orderArray['customer_details']['shipping_address'])) {
                $shippingAddr = $orderArray['customer_details']['shipping_address'];
            }

            // Also try top-level shipping_address
            if (!$shippingAddr && isset($orderArray['shipping_address'])) {
                $shippingAddr = $orderArray['shipping_address'];
            }

            // Also try payment notes
            if (!$shippingAddr && isset($payment)) {
                $paymentArray = json_decode(json_encode($payment), true) ?? [];
                if (isset($paymentArray['notes']['shipping_address'])) {
                    $shippingAddr = is_string($paymentArray['notes']['shipping_address'])
                        ? json_decode($paymentArray['notes']['shipping_address'], true)
                        : $paymentArray['notes']['shipping_address'];
                }
            }

            \Log::info('Extracted shipping address', ['shippingAddr' => $shippingAddr]);

            if ($shippingAddr && is_array($shippingAddr)) {
                $customerName = $shippingAddr['name'] ?? $shippingAddr['contact_name'] ?? $customerName;
                $addressLine1 = $shippingAddr['line1'] ?? ($shippingAddr['address_line1'] ?? '');
                $addressLine2 = $shippingAddr['line2'] ?? ($shippingAddr['address_line2'] ?? '');
                if (!$addressLine1) $addressLine1 = trim(($shippingAddr['street'] ?? '') . ' ' . ($shippingAddr['landmark'] ?? ''));
                $city = $shippingAddr['city'] ?? '';
                $state = $shippingAddr['state'] ?? '';
                $pincode = $shippingAddr['zipcode'] ?? ($shippingAddr['pincode'] ?? ($shippingAddr['zip'] ?? '000000'));
                $customerPhone = $shippingAddr['contact'] ?? ($shippingAddr['phone'] ?? $customerPhone);
            }

            // Fallback name from email
            if ($customerName === 'Customer' && $customerEmail) {
                $customerName = explode('@', $customerEmail)[0];
            }

        } catch (\Exception $e) {
            \Log::warning('Could not fetch Razorpay order/payment details: ' . $e->getMessage());
        }

        $address = Address::create([
            'user_id' => auth()->id(),
            'full_name' => $customerName,
            'phone' => $customerPhone,
            'email' => $customerEmail,
            'address_line1' => $addressLine1 ?: 'Via Razorpay Magic Checkout',
            'address_line2' => $addressLine2,
            'city' => $city ?: 'N/A',
            'state' => $state ?: 'N/A',
            'pincode' => $pincode,
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
            if ($item->variant) {
                $item->variant->decrement('stock', $item->quantity);
            } elseif (!is_null($item->product->stock)) {
                $item->product->decrement('stock', $item->quantity);
            }
        }

        // Free gift
        $fgEnabled = \App\Models\Setting::get('free_gift_enabled', 'false') === 'true';
        $fgThreshold = (float) \App\Models\Setting::get('free_gift_threshold', config('shivara.free_gift_threshold', 1499));
        $fgProductId = \App\Models\Setting::get('free_gift_product_id');
        if ($fgEnabled && $fgProductId && $subtotal >= $fgThreshold) {
            $fgProduct = \App\Models\Product::find($fgProductId);
            if ($fgProduct) {
                OrderItem::create(['order_id' => $order->id, 'product_id' => $fgProduct->id, 'variant_id' => null, 'product_name' => $fgProduct->name . ' (Free Gift)', 'variant_name' => null, 'quantity' => 1, 'price' => 0, 'total_price' => 0, 'gst_rate' => 0, 'gst_amount' => 0]);
            }
        }

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
