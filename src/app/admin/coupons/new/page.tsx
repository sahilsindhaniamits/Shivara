"use client";

import React, { useState } from "react";
import Link from "next/link";
import { ArrowLeft, Save } from "lucide-react";
import Button from "@/components/ui/Button";
import Input from "@/components/ui/Input";

export default function NewCouponPage() {
  const [formData, setFormData] = useState({
    code: "",
    description: "",
    type: "PERCENTAGE",
    value: "",
    minOrderAmount: "",
    maxDiscount: "",
    usageLimit: "",
    perUserLimit: "1",
    startDate: "",
    endDate: "",
    isActive: true,
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    alert("Coupon created! (Demo mode)");
  };

  return (
    <div className="space-y-6">
      <div className="flex items-center gap-4">
        <Link href="/admin/coupons" className="text-gray-500 hover:text-gray-700">
          <ArrowLeft size={20} />
        </Link>
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Create Coupon</h1>
          <p className="text-sm text-gray-500">Create a new discount code</p>
        </div>
      </div>

      <form onSubmit={handleSubmit} className="max-w-2xl space-y-6">
        <div className="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
          <div className="grid md:grid-cols-2 gap-4">
            <Input
              label="Coupon Code *"
              placeholder="e.g., SUMMER25"
              value={formData.code}
              onChange={(e) => setFormData({ ...formData, code: e.target.value.toUpperCase() })}
            />
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1.5">
                Discount Type *
              </label>
              <select
                value={formData.type}
                onChange={(e) => setFormData({ ...formData, type: e.target.value })}
                className="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"
              >
                <option value="PERCENTAGE">Percentage (%)</option>
                <option value="FLAT">Flat Amount (₹)</option>
                <option value="FREE_SHIPPING">Free Shipping</option>
                <option value="BUY_X_GET_Y">Buy X Get Y</option>
              </select>
            </div>
          </div>

          <Input
            label="Description"
            placeholder="Internal description for reference"
            value={formData.description}
            onChange={(e) => setFormData({ ...formData, description: e.target.value })}
          />

          <div className="grid md:grid-cols-2 gap-4">
            <Input
              label={formData.type === "PERCENTAGE" ? "Discount (%) *" : "Discount Amount (₹) *"}
              type="number"
              placeholder="0"
              value={formData.value}
              onChange={(e) => setFormData({ ...formData, value: e.target.value })}
            />
            <Input
              label="Min Order Amount (₹)"
              type="number"
              placeholder="No minimum"
              value={formData.minOrderAmount}
              onChange={(e) => setFormData({ ...formData, minOrderAmount: e.target.value })}
            />
          </div>

          {formData.type === "PERCENTAGE" && (
            <Input
              label="Max Discount Cap (₹)"
              type="number"
              placeholder="No cap"
              value={formData.maxDiscount}
              onChange={(e) => setFormData({ ...formData, maxDiscount: e.target.value })}
            />
          )}

          <div className="grid md:grid-cols-2 gap-4">
            <Input
              label="Total Usage Limit"
              type="number"
              placeholder="Unlimited"
              value={formData.usageLimit}
              onChange={(e) => setFormData({ ...formData, usageLimit: e.target.value })}
            />
            <Input
              label="Per User Limit"
              type="number"
              placeholder="1"
              value={formData.perUserLimit}
              onChange={(e) => setFormData({ ...formData, perUserLimit: e.target.value })}
            />
          </div>

          <div className="grid md:grid-cols-2 gap-4">
            <Input
              label="Start Date *"
              type="date"
              value={formData.startDate}
              onChange={(e) => setFormData({ ...formData, startDate: e.target.value })}
            />
            <Input
              label="End Date *"
              type="date"
              value={formData.endDate}
              onChange={(e) => setFormData({ ...formData, endDate: e.target.value })}
            />
          </div>

          <label className="flex items-center gap-2 cursor-pointer">
            <input
              type="checkbox"
              checked={formData.isActive}
              onChange={(e) => setFormData({ ...formData, isActive: e.target.checked })}
              className="rounded text-primary"
            />
            <span className="text-sm text-gray-700">Active immediately</span>
          </label>
        </div>

        <div className="flex gap-3">
          <Button variant="cta" type="submit">
            <Save size={16} className="mr-2" /> Create Coupon
          </Button>
          <Link href="/admin/coupons">
            <Button variant="ghost" type="button">Cancel</Button>
          </Link>
        </div>
      </form>
    </div>
  );
}
