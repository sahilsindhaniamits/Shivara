"use client";

import React, { useState } from "react";
import { Store, CreditCard, Mail, Globe, Shield, Save } from "lucide-react";
import Button from "@/components/ui/Button";
import Input from "@/components/ui/Input";

export default function AdminSettingsPage() {
  const [storeSettings, setStoreSettings] = useState({
    name: "Shivara",
    tagline: "Pure Ayurvedic Wellness",
    email: "Info@theshivara.com",
    phone: "+91-9828385808",
    address: "H-1-386-387, Agro Food Park, Udyog Vihar, RIICO Industrial Area",
    city: "Sri Ganganagar",
    state: "Rajasthan",
    pincode: "335001",
    gstNumber: "",
    panNumber: "",
  });

  const [paymentSettings, setPaymentSettings] = useState({
    razorpayKeyId: "",
    razorpayKeySecret: "",
    codEnabled: true,
  });

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-bold text-gray-900">Store Settings</h1>
        <p className="text-sm text-gray-500">Configure your store details and preferences</p>
      </div>

      {/* Store Info */}
      <div className="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
        <div className="flex items-center gap-3">
          <div className="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center">
            <Store size={20} className="text-primary" />
          </div>
          <h2 className="font-bold text-gray-900">Store Information</h2>
        </div>

        <div className="grid md:grid-cols-2 gap-4">
          <Input
            label="Store Name"
            value={storeSettings.name}
            onChange={(e) => setStoreSettings({ ...storeSettings, name: e.target.value })}
          />
          <Input
            label="Tagline"
            value={storeSettings.tagline}
            onChange={(e) => setStoreSettings({ ...storeSettings, tagline: e.target.value })}
          />
          <Input
            label="Email"
            value={storeSettings.email}
            onChange={(e) => setStoreSettings({ ...storeSettings, email: e.target.value })}
          />
          <Input
            label="Phone"
            value={storeSettings.phone}
            onChange={(e) => setStoreSettings({ ...storeSettings, phone: e.target.value })}
          />
          <div className="md:col-span-2">
            <Input
              label="Address"
              value={storeSettings.address}
              onChange={(e) => setStoreSettings({ ...storeSettings, address: e.target.value })}
            />
          </div>
          <Input
            label="City"
            value={storeSettings.city}
            onChange={(e) => setStoreSettings({ ...storeSettings, city: e.target.value })}
          />
          <Input
            label="State"
            value={storeSettings.state}
            onChange={(e) => setStoreSettings({ ...storeSettings, state: e.target.value })}
          />
          <Input
            label="Pincode"
            value={storeSettings.pincode}
            onChange={(e) => setStoreSettings({ ...storeSettings, pincode: e.target.value })}
          />
          <Input
            label="GST Number"
            placeholder="Enter GSTIN"
            value={storeSettings.gstNumber}
            onChange={(e) => setStoreSettings({ ...storeSettings, gstNumber: e.target.value })}
          />
        </div>

        <Button variant="primary" size="sm">
          <Save size={14} className="mr-1" /> Save Store Info
        </Button>
      </div>

      {/* Payment Settings */}
      <div className="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
        <div className="flex items-center gap-3">
          <div className="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
            <CreditCard size={20} className="text-blue-600" />
          </div>
          <div>
            <h2 className="font-bold text-gray-900">Razorpay Payment Gateway</h2>
            <p className="text-xs text-gray-500">Configure Razorpay API keys for online payments</p>
          </div>
        </div>

        <div className="grid md:grid-cols-2 gap-4">
          <Input
            label="Razorpay Key ID"
            placeholder="rzp_live_xxxxxxx"
            value={paymentSettings.razorpayKeyId}
            onChange={(e) => setPaymentSettings({ ...paymentSettings, razorpayKeyId: e.target.value })}
          />
          <Input
            label="Razorpay Key Secret"
            type="password"
            placeholder="••••••••"
            value={paymentSettings.razorpayKeySecret}
            onChange={(e) =>
              setPaymentSettings({ ...paymentSettings, razorpayKeySecret: e.target.value })
            }
          />
        </div>

        <label className="flex items-center gap-2 cursor-pointer">
          <input
            type="checkbox"
            checked={paymentSettings.codEnabled}
            onChange={(e) => setPaymentSettings({ ...paymentSettings, codEnabled: e.target.checked })}
            className="rounded text-primary"
          />
          <span className="text-sm text-gray-700">Enable Cash on Delivery (COD)</span>
        </label>

        <Button variant="primary" size="sm">
          <Save size={14} className="mr-1" /> Save Payment Settings
        </Button>
      </div>

      {/* SEO & Meta */}
      <div className="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
        <div className="flex items-center gap-3">
          <div className="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
            <Globe size={20} className="text-purple-600" />
          </div>
          <h2 className="font-bold text-gray-900">SEO & Meta Tags</h2>
        </div>
        <Input label="Site Title" placeholder="Shivara - Pure Ayurvedic Wellness" />
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1.5">
            Meta Description
          </label>
          <textarea
            rows={3}
            placeholder="Shivara offers natural herbal products..."
            className="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"
          />
        </div>
        <Input label="Google Analytics ID" placeholder="G-XXXXXXXXXX" />
        <Input label="Facebook Pixel ID" placeholder="Enter Pixel ID" />
        <Button variant="primary" size="sm">
          <Save size={14} className="mr-1" /> Save SEO Settings
        </Button>
      </div>
    </div>
  );
}
