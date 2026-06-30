"use client";

import React, { useState } from "react";
import { Truck, Package, Globe, Settings, CheckCircle } from "lucide-react";
import Button from "@/components/ui/Button";
import Input from "@/components/ui/Input";
import Badge from "@/components/ui/Badge";

export default function AdminShippingPage() {
  const [shiprocketConfig, setShiprocketConfig] = useState({
    email: "",
    password: "",
    pickupPincode: "335001",
  });

  const [shippingRules, setShippingRules] = useState({
    freeShippingThreshold: "999",
    standardRate: "79",
    expressRate: "149",
    codCharge: "49",
  });

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-bold text-gray-900">Shipping Management</h1>
        <p className="text-sm text-gray-500">
          Configure shipping methods, rates, and delivery integrations
        </p>
      </div>

      <div className="grid lg:grid-cols-2 gap-6">
        {/* Shiprocket Integration */}
        <div className="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
              <Truck size={20} className="text-blue-600" />
            </div>
            <div>
              <h2 className="font-bold text-gray-900">Shiprocket Integration</h2>
              <p className="text-xs text-gray-500">
                Auto-ship orders, generate AWB, track shipments
              </p>
            </div>
            <Badge variant="success" className="ml-auto">Connected</Badge>
          </div>

          <div className="space-y-3">
            <Input
              label="Shiprocket Email"
              type="email"
              placeholder="your@email.com"
              value={shiprocketConfig.email}
              onChange={(e) =>
                setShiprocketConfig({ ...shiprocketConfig, email: e.target.value })
              }
            />
            <Input
              label="Shiprocket Password"
              type="password"
              placeholder="••••••••"
              value={shiprocketConfig.password}
              onChange={(e) =>
                setShiprocketConfig({ ...shiprocketConfig, password: e.target.value })
              }
            />
            <Input
              label="Pickup Pincode"
              placeholder="335001"
              value={shiprocketConfig.pickupPincode}
              onChange={(e) =>
                setShiprocketConfig({ ...shiprocketConfig, pickupPincode: e.target.value })
              }
            />
            <Button variant="primary" size="sm" fullWidth>
              Save Shiprocket Settings
            </Button>
          </div>
        </div>

        {/* Manual Shipping */}
        <div className="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center">
              <Package size={20} className="text-orange-600" />
            </div>
            <div>
              <h2 className="font-bold text-gray-900">Manual Shipping</h2>
              <p className="text-xs text-gray-500">
                IndiaPost, DTDC, BlueDart, or any other courier
              </p>
            </div>
          </div>

          <div className="p-4 bg-gray-50 rounded-xl">
            <h3 className="text-sm font-medium text-gray-700 mb-3">How manual shipping works:</h3>
            <ul className="space-y-2 text-sm text-gray-600">
              <li className="flex items-start gap-2">
                <CheckCircle size={14} className="text-green-500 mt-0.5 shrink-0" />
                Go to Order Detail page
              </li>
              <li className="flex items-start gap-2">
                <CheckCircle size={14} className="text-green-500 mt-0.5 shrink-0" />
                Click &quot;Ship Manually&quot;
              </li>
              <li className="flex items-start gap-2">
                <CheckCircle size={14} className="text-green-500 mt-0.5 shrink-0" />
                Enter courier name & tracking number
              </li>
              <li className="flex items-start gap-2">
                <CheckCircle size={14} className="text-green-500 mt-0.5 shrink-0" />
                Customer gets notified with tracking info
              </li>
            </ul>
          </div>

          <div className="p-4 bg-blue-50 rounded-xl text-sm text-blue-700">
            <p className="font-medium">Supported Couriers:</p>
            <p className="text-blue-600 mt-1">
              IndiaPost, BlueDart, DTDC, Delhivery, Ekart, Professional Courier, etc.
            </p>
          </div>
        </div>
      </div>

      {/* Shipping Rules */}
      <div className="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
        <div className="flex items-center gap-3">
          <div className="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
            <Settings size={20} className="text-green-600" />
          </div>
          <div>
            <h2 className="font-bold text-gray-900">Shipping Rules & Rates</h2>
            <p className="text-xs text-gray-500">Configure shipping charges and free shipping threshold</p>
          </div>
        </div>

        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
          <Input
            label="Free Shipping Above (₹)"
            type="number"
            value={shippingRules.freeShippingThreshold}
            onChange={(e) =>
              setShippingRules({ ...shippingRules, freeShippingThreshold: e.target.value })
            }
          />
          <Input
            label="Standard Delivery (₹)"
            type="number"
            value={shippingRules.standardRate}
            onChange={(e) =>
              setShippingRules({ ...shippingRules, standardRate: e.target.value })
            }
          />
          <Input
            label="Express Delivery (₹)"
            type="number"
            value={shippingRules.expressRate}
            onChange={(e) =>
              setShippingRules({ ...shippingRules, expressRate: e.target.value })
            }
          />
          <Input
            label="COD Charge (₹)"
            type="number"
            value={shippingRules.codCharge}
            onChange={(e) =>
              setShippingRules({ ...shippingRules, codCharge: e.target.value })
            }
          />
        </div>

        <Button variant="primary" size="sm">
          Save Shipping Rules
        </Button>
      </div>
    </div>
  );
}
