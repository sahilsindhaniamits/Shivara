"use client";

import React, { useState } from "react";
import Image from "next/image";
import Link from "next/link";
import { Minus, Plus, Trash2, ShoppingBag, Tag, ArrowRight } from "lucide-react";
import Button from "@/components/ui/Button";
import { useCartStore } from "@/store/cart-store";
import { formatPrice } from "@/lib/utils";
import { SHIPPING_CONFIG } from "@/lib/constants";

export default function CartPage() {
  const { items, removeItem, updateQuantity, getSubtotal, getTotalMRP, getTotalDiscount, getItemCount, clearCart } =
    useCartStore();
  const [couponCode, setCouponCode] = useState("");
  const [couponApplied, setCouponApplied] = useState(false);
  const [couponDiscount, setCouponDiscount] = useState(0);

  const subtotal = getSubtotal();
  const totalMRP = getTotalMRP();
  const totalDiscount = getTotalDiscount();
  const shippingCharge = subtotal >= SHIPPING_CONFIG.freeShippingThreshold ? 0 : SHIPPING_CONFIG.standardRate;
  const totalAmount = subtotal - couponDiscount + shippingCharge;

  const handleApplyCoupon = () => {
    if (couponCode.toUpperCase() === "SHIVARA10") {
      const discount = Math.min(subtotal * 0.1, 500); // 10% max 500
      setCouponDiscount(discount);
      setCouponApplied(true);
    }
  };

  if (items.length === 0) {
    return (
      <div className="max-w-4xl mx-auto px-4 py-20 text-center">
        <div className="w-24 h-24 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
          <ShoppingBag size={40} className="text-primary/50" />
        </div>
        <h1 className="text-2xl font-bold text-gray-800 mb-2">
          Your cart is empty
        </h1>
        <p className="text-gray-500 mb-8">
          Looks like you haven&apos;t added any products yet.
        </p>
        <Link href="/products">
          <Button variant="cta" size="lg">
            Continue Shopping
          </Button>
        </Link>
      </div>
    );
  }

  return (
    <div className="max-w-7xl mx-auto px-4 py-8">
      <h1 className="text-2xl font-bold text-gray-900 mb-2">Shopping Cart</h1>
      <p className="text-gray-500 mb-8">
        {getItemCount()} item{getItemCount() > 1 ? "s" : ""} in your cart
      </p>

      <div className="grid lg:grid-cols-3 gap-8">
        {/* Cart Items */}
        <div className="lg:col-span-2 space-y-4">
          {items.map((item) => (
            <div
              key={item.id}
              className="bg-white p-4 md:p-6 rounded-2xl border border-border flex gap-4 group"
            >
              {/* Image */}
              <div className="w-20 h-20 md:w-24 md:h-24 bg-accent/50 rounded-xl overflow-hidden shrink-0">
                {item.image ? (
                  <Image src={item.image} alt={item.name} width={96} height={96} className="object-cover w-full h-full" />
                ) : (
                  <div className="w-full h-full flex items-center justify-center">
                    <span className="text-3xl">🌿</span>
                  </div>
                )}
              </div>

              {/* Details */}
              <div className="flex-1 min-w-0">
                <div className="flex justify-between">
                  <div>
                    <h3 className="text-sm md:text-base font-medium text-gray-800 line-clamp-2">
                      {item.name}
                    </h3>
                    {item.variantName && (
                      <p className="text-xs text-gray-500 mt-0.5">
                        {item.variantName}
                      </p>
                    )}
                  </div>
                  <button
                    onClick={() => removeItem(item.id)}
                    className="text-gray-400 hover:text-red-500 transition p-1"
                  >
                    <Trash2 size={18} />
                  </button>
                </div>

                <div className="flex items-end justify-between mt-3">
                  {/* Quantity */}
                  <div className="flex items-center border border-border rounded-lg">
                    <button
                      onClick={() => updateQuantity(item.id, item.quantity - 1)}
                      className="px-3 py-1.5 text-gray-500 hover:text-primary"
                    >
                      <Minus size={14} />
                    </button>
                    <span className="px-3 py-1.5 text-sm font-semibold min-w-[2rem] text-center">
                      {item.quantity}
                    </span>
                    <button
                      onClick={() => updateQuantity(item.id, item.quantity + 1)}
                      className="px-3 py-1.5 text-gray-500 hover:text-primary"
                    >
                      <Plus size={14} />
                    </button>
                  </div>

                  {/* Price */}
                  <div className="text-right">
                    <p className="text-lg font-bold text-primary">
                      {formatPrice(item.price * item.quantity)}
                    </p>
                    {item.mrp > item.price && (
                      <p className="text-xs text-gray-400 line-through">
                        {formatPrice(item.mrp * item.quantity)}
                      </p>
                    )}
                  </div>
                </div>
              </div>
            </div>
          ))}

          {/* Clear Cart */}
          <div className="flex justify-between items-center pt-4">
            <Link href="/products" className="text-sm text-primary font-medium hover:underline">
              ← Continue Shopping
            </Link>
            <button
              onClick={clearCart}
              className="text-sm text-red-500 font-medium hover:underline"
            >
              Clear Cart
            </button>
          </div>
        </div>

        {/* Order Summary */}
        <div className="lg:col-span-1">
          <div className="bg-white p-6 rounded-2xl border border-border sticky top-40">
            <h2 className="text-lg font-bold text-gray-900 mb-4">
              Order Summary
            </h2>

            {/* Coupon */}
            <div className="mb-6">
              <div className="flex gap-2">
                <div className="relative flex-1">
                  <Tag size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                  <input
                    type="text"
                    placeholder="Coupon code"
                    value={couponCode}
                    onChange={(e) => setCouponCode(e.target.value)}
                    className="w-full pl-9 pr-3 py-2.5 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"
                  />
                </div>
                <Button
                  variant="outline"
                  size="sm"
                  onClick={handleApplyCoupon}
                  disabled={!couponCode || couponApplied}
                >
                  Apply
                </Button>
              </div>
              {couponApplied && (
                <p className="text-xs text-green-600 mt-1 flex items-center gap-1">
                  ✓ Coupon applied! You saved {formatPrice(couponDiscount)}
                </p>
              )}
              <p className="text-xs text-gray-400 mt-1">
                Try: SHIVARA10 for 10% off
              </p>
            </div>

            {/* Price breakdown */}
            <div className="space-y-3 text-sm">
              <div className="flex justify-between text-gray-600">
                <span>Total MRP</span>
                <span>{formatPrice(totalMRP)}</span>
              </div>
              <div className="flex justify-between text-green-600">
                <span>Discount on MRP</span>
                <span>-{formatPrice(totalDiscount)}</span>
              </div>
              {couponDiscount > 0 && (
                <div className="flex justify-between text-green-600">
                  <span>Coupon Discount</span>
                  <span>-{formatPrice(couponDiscount)}</span>
                </div>
              )}
              <div className="flex justify-between text-gray-600">
                <span>Shipping</span>
                <span>
                  {shippingCharge === 0 ? (
                    <span className="text-green-600 font-medium">FREE</span>
                  ) : (
                    formatPrice(shippingCharge)
                  )}
                </span>
              </div>
              {shippingCharge > 0 && (
                <p className="text-xs text-gray-400">
                  Add {formatPrice(SHIPPING_CONFIG.freeShippingThreshold - subtotal)} more for free shipping
                </p>
              )}
              <hr className="border-border" />
              <div className="flex justify-between text-lg font-bold text-gray-900">
                <span>Total</span>
                <span className="text-primary">{formatPrice(totalAmount)}</span>
              </div>
              <p className="text-xs text-gray-400">
                (Inclusive of all taxes)
              </p>
            </div>

            {/* Savings */}
            {totalDiscount + couponDiscount > 0 && (
              <div className="mt-4 p-3 bg-green-50 rounded-xl text-center">
                <p className="text-sm text-green-700 font-medium">
                  🎉 You&apos;re saving {formatPrice(totalDiscount + couponDiscount)} on this order!
                </p>
              </div>
            )}

            {/* Checkout */}
            <Link href="/checkout" className="block mt-6">
              <Button variant="cta" size="lg" fullWidth>
                Proceed to Checkout <ArrowRight size={18} className="ml-2" />
              </Button>
            </Link>

            {/* Payment methods */}
            <div className="mt-4 text-center">
              <p className="text-xs text-gray-400 mb-2">
                Secure payments powered by Razorpay
              </p>
              <div className="flex items-center justify-center gap-2 text-xs text-gray-400">
                <span>UPI</span> | <span>Cards</span> | <span>Net Banking</span> | <span>COD</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
