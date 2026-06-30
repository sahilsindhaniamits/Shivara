"use client";

import React, { useState } from "react";
import Link from "next/link";
import {
  MapPin,
  CreditCard,
  Truck,
  ShieldCheck,
  ChevronRight,
  Check,
} from "lucide-react";
import Button from "@/components/ui/Button";
import Input from "@/components/ui/Input";
import { useCartStore } from "@/store/cart-store";
import { formatPrice } from "@/lib/utils";
import { SHIPPING_CONFIG, INDIAN_STATES } from "@/lib/constants";

type Step = "address" | "shipping" | "payment";

export default function CheckoutPage() {
  const [step, setStep] = useState<Step>("address");
  const [shippingMethod, setShippingMethod] = useState<"standard" | "express">("standard");
  const [paymentMethod, setPaymentMethod] = useState<"razorpay" | "cod">("razorpay");
  const { items, getSubtotal } = useCartStore();
  
  const [address, setAddress] = useState({
    fullName: "",
    phone: "",
    email: "",
    addressLine1: "",
    addressLine2: "",
    city: "",
    state: "",
    pincode: "",
    landmark: "",
  });

  const subtotal = getSubtotal();
  const shippingCharge =
    subtotal >= SHIPPING_CONFIG.freeShippingThreshold
      ? 0
      : shippingMethod === "express"
      ? SHIPPING_CONFIG.expressRate
      : SHIPPING_CONFIG.standardRate;
  const codCharge = paymentMethod === "cod" ? SHIPPING_CONFIG.codCharge : 0;
  const totalAmount = subtotal + shippingCharge + codCharge;

  const steps: { key: Step; label: string; icon: React.ReactNode }[] = [
    { key: "address", label: "Address", icon: <MapPin size={18} /> },
    { key: "shipping", label: "Shipping", icon: <Truck size={18} /> },
    { key: "payment", label: "Payment", icon: <CreditCard size={18} /> },
  ];

  const currentStepIndex = steps.findIndex((s) => s.key === step);

  const handlePlaceOrder = () => {
    // In production, this would:
    // 1. Create order in database
    // 2. If Razorpay: create Razorpay order and open checkout
    // 3. If COD: directly confirm order
    alert("Order placed successfully! (Demo mode)");
  };

  return (
    <div className="max-w-6xl mx-auto px-4 py-8">
      <h1 className="text-2xl font-bold text-gray-900 mb-2">Checkout</h1>

      {/* Steps */}
      <div className="flex items-center gap-2 mb-8 overflow-x-auto pb-2">
        {steps.map((s, i) => (
          <React.Fragment key={s.key}>
            <div
              className={`flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap ${
                i <= currentStepIndex
                  ? "bg-primary text-white"
                  : "bg-gray-100 text-gray-500"
              }`}
            >
              {i < currentStepIndex ? <Check size={16} /> : s.icon}
              <span className="hidden sm:inline">{s.label}</span>
            </div>
            {i < steps.length - 1 && (
              <ChevronRight size={16} className="text-gray-300 shrink-0" />
            )}
          </React.Fragment>
        ))}
      </div>

      <div className="grid lg:grid-cols-3 gap-8">
        {/* Main form */}
        <div className="lg:col-span-2">
          {/* Address Step */}
          {step === "address" && (
            <div className="bg-white p-6 rounded-2xl border border-border">
              <h2 className="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <MapPin size={20} className="text-primary" /> Delivery Address
              </h2>

              <div className="grid md:grid-cols-2 gap-4">
                <Input
                  label="Full Name *"
                  placeholder="Enter full name"
                  value={address.fullName}
                  onChange={(e) => setAddress({ ...address, fullName: e.target.value })}
                />
                <Input
                  label="Phone Number *"
                  placeholder="+91 XXXXX XXXXX"
                  value={address.phone}
                  onChange={(e) => setAddress({ ...address, phone: e.target.value })}
                />
                <div className="md:col-span-2">
                  <Input
                    label="Email *"
                    type="email"
                    placeholder="your@email.com"
                    value={address.email}
                    onChange={(e) => setAddress({ ...address, email: e.target.value })}
                  />
                </div>
                <div className="md:col-span-2">
                  <Input
                    label="Address Line 1 *"
                    placeholder="House no, Building, Street"
                    value={address.addressLine1}
                    onChange={(e) => setAddress({ ...address, addressLine1: e.target.value })}
                  />
                </div>
                <div className="md:col-span-2">
                  <Input
                    label="Address Line 2"
                    placeholder="Area, Colony (optional)"
                    value={address.addressLine2}
                    onChange={(e) => setAddress({ ...address, addressLine2: e.target.value })}
                  />
                </div>
                <Input
                  label="City *"
                  placeholder="City"
                  value={address.city}
                  onChange={(e) => setAddress({ ...address, city: e.target.value })}
                />
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1.5">
                    State *
                  </label>
                  <select
                    value={address.state}
                    onChange={(e) => setAddress({ ...address, state: e.target.value })}
                    className="w-full px-4 py-3 rounded-xl border border-border bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                  >
                    <option value="">Select State</option>
                    {INDIAN_STATES.map((state) => (
                      <option key={state} value={state}>{state}</option>
                    ))}
                  </select>
                </div>
                <Input
                  label="Pincode *"
                  placeholder="6-digit pincode"
                  maxLength={6}
                  value={address.pincode}
                  onChange={(e) => setAddress({ ...address, pincode: e.target.value })}
                />
                <Input
                  label="Landmark"
                  placeholder="Near... (optional)"
                  value={address.landmark}
                  onChange={(e) => setAddress({ ...address, landmark: e.target.value })}
                />
              </div>

              <div className="mt-6 flex justify-end">
                <Button variant="cta" onClick={() => setStep("shipping")}>
                  Continue to Shipping <ChevronRight size={18} className="ml-1" />
                </Button>
              </div>
            </div>
          )}

          {/* Shipping Step */}
          {step === "shipping" && (
            <div className="bg-white p-6 rounded-2xl border border-border">
              <h2 className="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <Truck size={20} className="text-primary" /> Shipping Method
              </h2>

              <div className="space-y-3">
                <label
                  className={`flex items-center justify-between p-4 rounded-xl border-2 cursor-pointer transition ${
                    shippingMethod === "standard"
                      ? "border-primary bg-primary/5"
                      : "border-border hover:border-primary/30"
                  }`}
                >
                  <div className="flex items-center gap-3">
                    <input
                      type="radio"
                      name="shipping"
                      checked={shippingMethod === "standard"}
                      onChange={() => setShippingMethod("standard")}
                      className="text-primary"
                    />
                    <div>
                      <p className="font-medium text-gray-800">Standard Delivery</p>
                      <p className="text-sm text-gray-500">
                        {SHIPPING_CONFIG.estimatedDays.standard}
                      </p>
                    </div>
                  </div>
                  <span className="font-semibold text-gray-700">
                    {subtotal >= SHIPPING_CONFIG.freeShippingThreshold
                      ? "FREE"
                      : formatPrice(SHIPPING_CONFIG.standardRate)}
                  </span>
                </label>

                <label
                  className={`flex items-center justify-between p-4 rounded-xl border-2 cursor-pointer transition ${
                    shippingMethod === "express"
                      ? "border-primary bg-primary/5"
                      : "border-border hover:border-primary/30"
                  }`}
                >
                  <div className="flex items-center gap-3">
                    <input
                      type="radio"
                      name="shipping"
                      checked={shippingMethod === "express"}
                      onChange={() => setShippingMethod("express")}
                      className="text-primary"
                    />
                    <div>
                      <p className="font-medium text-gray-800">Express Delivery</p>
                      <p className="text-sm text-gray-500">
                        {SHIPPING_CONFIG.estimatedDays.express}
                      </p>
                    </div>
                  </div>
                  <span className="font-semibold text-gray-700">
                    {formatPrice(SHIPPING_CONFIG.expressRate)}
                  </span>
                </label>
              </div>

              <div className="mt-6 flex justify-between">
                <Button variant="ghost" onClick={() => setStep("address")}>
                  ← Back
                </Button>
                <Button variant="cta" onClick={() => setStep("payment")}>
                  Continue to Payment <ChevronRight size={18} className="ml-1" />
                </Button>
              </div>
            </div>
          )}

          {/* Payment Step */}
          {step === "payment" && (
            <div className="bg-white p-6 rounded-2xl border border-border">
              <h2 className="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <CreditCard size={20} className="text-primary" /> Payment Method
              </h2>

              <div className="space-y-3">
                <label
                  className={`flex items-center justify-between p-4 rounded-xl border-2 cursor-pointer transition ${
                    paymentMethod === "razorpay"
                      ? "border-primary bg-primary/5"
                      : "border-border hover:border-primary/30"
                  }`}
                >
                  <div className="flex items-center gap-3">
                    <input
                      type="radio"
                      name="payment"
                      checked={paymentMethod === "razorpay"}
                      onChange={() => setPaymentMethod("razorpay")}
                      className="text-primary"
                    />
                    <div>
                      <p className="font-medium text-gray-800">Pay Online (Razorpay)</p>
                      <p className="text-sm text-gray-500">
                        UPI, Credit/Debit Card, Net Banking, Wallets, EMI
                      </p>
                    </div>
                  </div>
                  <span className="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">
                    Recommended
                  </span>
                </label>

                <label
                  className={`flex items-center justify-between p-4 rounded-xl border-2 cursor-pointer transition ${
                    paymentMethod === "cod"
                      ? "border-primary bg-primary/5"
                      : "border-border hover:border-primary/30"
                  }`}
                >
                  <div className="flex items-center gap-3">
                    <input
                      type="radio"
                      name="payment"
                      checked={paymentMethod === "cod"}
                      onChange={() => setPaymentMethod("cod")}
                      className="text-primary"
                    />
                    <div>
                      <p className="font-medium text-gray-800">Cash on Delivery</p>
                      <p className="text-sm text-gray-500">
                        Pay when you receive the product (+₹{SHIPPING_CONFIG.codCharge} COD charge)
                      </p>
                    </div>
                  </div>
                </label>
              </div>

              {/* Security note */}
              <div className="mt-6 p-4 bg-accent/50 rounded-xl flex items-start gap-3">
                <ShieldCheck size={20} className="text-primary shrink-0 mt-0.5" />
                <div>
                  <p className="text-sm font-medium text-gray-700">
                    100% Secure Payment
                  </p>
                  <p className="text-xs text-gray-500">
                    Your payment information is encrypted and secure. We never store your card details.
                  </p>
                </div>
              </div>

              <div className="mt-6 flex justify-between">
                <Button variant="ghost" onClick={() => setStep("shipping")}>
                  ← Back
                </Button>
                <Button variant="cta" size="lg" onClick={handlePlaceOrder}>
                  {paymentMethod === "razorpay" ? "Pay " + formatPrice(totalAmount) : "Place Order (COD)"}
                </Button>
              </div>
            </div>
          )}
        </div>

        {/* Order Summary Sidebar */}
        <div className="lg:col-span-1">
          <div className="bg-white p-6 rounded-2xl border border-border sticky top-40">
            <h3 className="font-bold text-gray-900 mb-4">Order Summary</h3>

            <div className="space-y-3 max-h-60 overflow-y-auto mb-4">
              {items.map((item) => (
                <div key={item.id} className="flex gap-3">
                  <div className="w-12 h-12 bg-accent rounded-lg flex items-center justify-center shrink-0">
                    <span className="text-lg">🌿</span>
                  </div>
                  <div className="flex-1 min-w-0">
                    <p className="text-xs text-gray-700 line-clamp-1">{item.name}</p>
                    <p className="text-xs text-gray-400">Qty: {item.quantity}</p>
                  </div>
                  <p className="text-sm font-semibold text-gray-800">
                    {formatPrice(item.price * item.quantity)}
                  </p>
                </div>
              ))}
            </div>

            <hr className="border-border mb-4" />

            <div className="space-y-2 text-sm">
              <div className="flex justify-between text-gray-600">
                <span>Subtotal</span>
                <span>{formatPrice(subtotal)}</span>
              </div>
              <div className="flex justify-between text-gray-600">
                <span>Shipping</span>
                <span>{shippingCharge === 0 ? "FREE" : formatPrice(shippingCharge)}</span>
              </div>
              {codCharge > 0 && (
                <div className="flex justify-between text-gray-600">
                  <span>COD Charge</span>
                  <span>{formatPrice(codCharge)}</span>
                </div>
              )}
              <hr className="border-border" />
              <div className="flex justify-between text-lg font-bold text-gray-900">
                <span>Total</span>
                <span className="text-primary">{formatPrice(totalAmount)}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
