"use client";

import React, { useState } from "react";
import Link from "next/link";
import {
  ArrowLeft,
  MapPin,
  Package,
  Truck,
  CreditCard,
  User,
  Phone,
  Mail,
  Clock,
  Download,
  Printer,
  CheckCircle,
} from "lucide-react";
import Button from "@/components/ui/Button";
import Badge from "@/components/ui/Badge";
import Input from "@/components/ui/Input";
import { formatPrice } from "@/lib/utils";

// Demo order detail
const ORDER = {
  id: "1",
  orderNumber: "SHV-XK8M-A2",
  status: "CONFIRMED",
  paymentStatus: "PAID",
  paymentMethod: "RAZORPAY",
  razorpayPaymentId: "pay_Nh1234567890",
  createdAt: "2024-12-28T10:30:00",
  customer: {
    name: "Priya Sharma",
    email: "priya@email.com",
    phone: "+91-9876543210",
  },
  address: {
    fullName: "Priya Sharma",
    addressLine1: "42, Green Park Colony",
    addressLine2: "Near City Mall",
    city: "Jaipur",
    state: "Rajasthan",
    pincode: "302001",
    phone: "+91-9876543210",
  },
  items: [
    {
      id: "1",
      name: "Madhu Balance Capsules",
      variantName: "60 Capsules (1 Month)",
      quantity: 1,
      price: 1249,
      totalPrice: 1249,
      image: "🌿",
    },
    {
      id: "2",
      name: "Ashwagandha Gold Capsules",
      variantName: "60 Capsules",
      quantity: 1,
      price: 899,
      totalPrice: 899,
      image: "🌿",
    },
  ],
  subtotal: 2148,
  discount: 0,
  shippingCharge: 0,
  totalAmount: 2148,
  timeline: [
    { status: "Order Placed", message: "Order confirmed via Razorpay", date: "28 Dec, 10:30 AM" },
    { status: "Confirmed", message: "Payment verified successfully", date: "28 Dec, 10:31 AM" },
  ],
};

export default function OrderDetailPage() {
  const [trackingNumber, setTrackingNumber] = useState("");
  const [courierName, setCourierName] = useState("");
  const [statusUpdate, setStatusUpdate] = useState(ORDER.status);
  const [showShipModal, setShowShipModal] = useState(false);

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div className="flex items-center gap-4">
          <Link href="/admin/orders" className="text-gray-500 hover:text-gray-700">
            <ArrowLeft size={20} />
          </Link>
          <div>
            <div className="flex items-center gap-3">
              <h1 className="text-2xl font-bold text-gray-900">
                #{ORDER.orderNumber}
              </h1>
              <Badge variant="info">{ORDER.status}</Badge>
              <Badge variant="success">{ORDER.paymentStatus}</Badge>
            </div>
            <p className="text-sm text-gray-500">
              Placed on {new Date(ORDER.createdAt).toLocaleString("en-IN")}
            </p>
          </div>
        </div>
        <div className="flex gap-2">
          <Button variant="outline" size="sm">
            <Printer size={14} className="mr-1" /> Invoice
          </Button>
          <Button variant="outline" size="sm">
            <Download size={14} className="mr-1" /> Download
          </Button>
        </div>
      </div>

      <div className="grid lg:grid-cols-3 gap-6">
        {/* Main Content */}
        <div className="lg:col-span-2 space-y-6">
          {/* Order Items */}
          <div className="bg-white p-6 rounded-2xl border border-gray-200">
            <h2 className="font-bold text-gray-900 mb-4 flex items-center gap-2">
              <Package size={18} className="text-primary" /> Order Items
            </h2>
            <div className="space-y-4">
              {ORDER.items.map((item) => (
                <div key={item.id} className="flex gap-4 p-3 bg-gray-50 rounded-xl">
                  <div className="w-14 h-14 bg-accent rounded-lg flex items-center justify-center shrink-0">
                    <span className="text-2xl">{item.image}</span>
                  </div>
                  <div className="flex-1">
                    <p className="font-medium text-gray-800">{item.name}</p>
                    <p className="text-sm text-gray-500">{item.variantName}</p>
                    <p className="text-sm text-gray-400">Qty: {item.quantity}</p>
                  </div>
                  <div className="text-right">
                    <p className="font-semibold text-gray-900">
                      {formatPrice(item.totalPrice)}
                    </p>
                    <p className="text-xs text-gray-400">
                      {formatPrice(item.price)} each
                    </p>
                  </div>
                </div>
              ))}
            </div>

            {/* Total */}
            <div className="mt-4 pt-4 border-t border-gray-200 space-y-2">
              <div className="flex justify-between text-sm text-gray-600">
                <span>Subtotal</span>
                <span>{formatPrice(ORDER.subtotal)}</span>
              </div>
              <div className="flex justify-between text-sm text-gray-600">
                <span>Shipping</span>
                <span>{ORDER.shippingCharge === 0 ? "FREE" : formatPrice(ORDER.shippingCharge)}</span>
              </div>
              {ORDER.discount > 0 && (
                <div className="flex justify-between text-sm text-green-600">
                  <span>Discount</span>
                  <span>-{formatPrice(ORDER.discount)}</span>
                </div>
              )}
              <div className="flex justify-between text-lg font-bold text-gray-900 pt-2 border-t border-gray-200">
                <span>Total</span>
                <span className="text-primary">{formatPrice(ORDER.totalAmount)}</span>
              </div>
            </div>
          </div>

          {/* Shipping / Fulfillment */}
          <div className="bg-white p-6 rounded-2xl border border-gray-200">
            <h2 className="font-bold text-gray-900 mb-4 flex items-center gap-2">
              <Truck size={18} className="text-primary" /> Shipping & Fulfillment
            </h2>

            {ORDER.status === "CONFIRMED" || ORDER.status === "PROCESSING" ? (
              <div className="space-y-4">
                <p className="text-sm text-gray-600">
                  Choose how to ship this order:
                </p>
                <div className="grid md:grid-cols-2 gap-4">
                  <button
                    onClick={() => setShowShipModal(true)}
                    className="p-4 border-2 border-primary/20 rounded-xl hover:border-primary hover:bg-primary/5 transition text-left"
                  >
                    <Truck size={24} className="text-primary mb-2" />
                    <p className="font-semibold text-gray-800">Ship via Shiprocket</p>
                    <p className="text-xs text-gray-500 mt-1">
                      Auto-generate AWB, get courier assigned
                    </p>
                  </button>
                  <button
                    onClick={() => setShowShipModal(true)}
                    className="p-4 border-2 border-gray-200 rounded-xl hover:border-primary hover:bg-primary/5 transition text-left"
                  >
                    <Package size={24} className="text-secondary mb-2" />
                    <p className="font-semibold text-gray-800">Ship Manually</p>
                    <p className="text-xs text-gray-500 mt-1">
                      IndiaPost, DTDC, or other courier
                    </p>
                  </button>
                </div>

                {showShipModal && (
                  <div className="p-4 bg-gray-50 rounded-xl border border-gray-200 space-y-3 mt-4">
                    <h3 className="font-medium text-gray-800">Enter Shipping Details</h3>
                    <div className="grid md:grid-cols-2 gap-3">
                      <Input
                        label="Courier Name"
                        placeholder="e.g., IndiaPost, Shiprocket, DTDC"
                        value={courierName}
                        onChange={(e) => setCourierName(e.target.value)}
                      />
                      <Input
                        label="Tracking Number / AWB"
                        placeholder="Enter tracking number"
                        value={trackingNumber}
                        onChange={(e) => setTrackingNumber(e.target.value)}
                      />
                    </div>
                    <div className="flex gap-2">
                      <Button variant="cta" size="sm">
                        Mark as Shipped
                      </Button>
                      <Button variant="ghost" size="sm" onClick={() => setShowShipModal(false)}>
                        Cancel
                      </Button>
                    </div>
                  </div>
                )}
              </div>
            ) : (
              <div className="text-sm text-gray-500">
                Order status: {ORDER.status}
              </div>
            )}
          </div>

          {/* Update Status */}
          <div className="bg-white p-6 rounded-2xl border border-gray-200">
            <h2 className="font-bold text-gray-900 mb-4">Update Order Status</h2>
            <div className="flex gap-3">
              <select
                value={statusUpdate}
                onChange={(e) => setStatusUpdate(e.target.value)}
                className="flex-1 px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"
              >
                <option value="PENDING">Pending</option>
                <option value="CONFIRMED">Confirmed</option>
                <option value="PROCESSING">Processing</option>
                <option value="SHIPPED">Shipped</option>
                <option value="OUT_FOR_DELIVERY">Out for Delivery</option>
                <option value="DELIVERED">Delivered</option>
                <option value="CANCELLED">Cancelled</option>
              </select>
              <Button variant="primary" size="sm">
                Update Status
              </Button>
            </div>
          </div>
        </div>

        {/* Sidebar */}
        <div className="lg:col-span-1 space-y-6">
          {/* Customer */}
          <div className="bg-white p-6 rounded-2xl border border-gray-200">
            <h3 className="font-bold text-gray-900 mb-4 flex items-center gap-2">
              <User size={18} className="text-primary" /> Customer
            </h3>
            <div className="space-y-3">
              <p className="font-medium text-gray-800">{ORDER.customer.name}</p>
              <div className="flex items-center gap-2 text-sm text-gray-500">
                <Mail size={14} /> {ORDER.customer.email}
              </div>
              <div className="flex items-center gap-2 text-sm text-gray-500">
                <Phone size={14} /> {ORDER.customer.phone}
              </div>
            </div>
          </div>

          {/* Address */}
          <div className="bg-white p-6 rounded-2xl border border-gray-200">
            <h3 className="font-bold text-gray-900 mb-4 flex items-center gap-2">
              <MapPin size={18} className="text-primary" /> Delivery Address
            </h3>
            <div className="text-sm text-gray-600 space-y-1">
              <p className="font-medium">{ORDER.address.fullName}</p>
              <p>{ORDER.address.addressLine1}</p>
              {ORDER.address.addressLine2 && <p>{ORDER.address.addressLine2}</p>}
              <p>
                {ORDER.address.city}, {ORDER.address.state} - {ORDER.address.pincode}
              </p>
              <p className="text-gray-500">{ORDER.address.phone}</p>
            </div>
          </div>

          {/* Payment */}
          <div className="bg-white p-6 rounded-2xl border border-gray-200">
            <h3 className="font-bold text-gray-900 mb-4 flex items-center gap-2">
              <CreditCard size={18} className="text-primary" /> Payment
            </h3>
            <div className="space-y-2 text-sm">
              <div className="flex justify-between">
                <span className="text-gray-500">Method</span>
                <span className="font-medium">{ORDER.paymentMethod}</span>
              </div>
              <div className="flex justify-between">
                <span className="text-gray-500">Status</span>
                <Badge variant="success">{ORDER.paymentStatus}</Badge>
              </div>
              {ORDER.razorpayPaymentId && (
                <div className="flex justify-between">
                  <span className="text-gray-500">Transaction ID</span>
                  <span className="text-xs font-mono">{ORDER.razorpayPaymentId}</span>
                </div>
              )}
            </div>
          </div>

          {/* Timeline */}
          <div className="bg-white p-6 rounded-2xl border border-gray-200">
            <h3 className="font-bold text-gray-900 mb-4 flex items-center gap-2">
              <Clock size={18} className="text-primary" /> Timeline
            </h3>
            <div className="space-y-4">
              {ORDER.timeline.map((event, i) => (
                <div key={i} className="flex gap-3">
                  <div className="flex flex-col items-center">
                    <div className="w-3 h-3 bg-primary rounded-full" />
                    {i < ORDER.timeline.length - 1 && (
                      <div className="w-0.5 h-full bg-primary/20 mt-1" />
                    )}
                  </div>
                  <div className="pb-4">
                    <p className="text-sm font-medium text-gray-800">{event.status}</p>
                    <p className="text-xs text-gray-500">{event.message}</p>
                    <p className="text-xs text-gray-400 mt-0.5">{event.date}</p>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
