"use client";

import React from "react";
import Link from "next/link";
import { Plus, Tag, Edit, Trash2, Copy } from "lucide-react";
import Button from "@/components/ui/Button";
import Badge from "@/components/ui/Badge";
import { formatPrice } from "@/lib/utils";

const COUPONS = [
  {
    id: "1",
    code: "SHIVARA10",
    description: "10% off on first order",
    type: "PERCENTAGE",
    value: 10,
    minOrderAmount: 500,
    maxDiscount: 500,
    usageLimit: 1000,
    usageCount: 245,
    isActive: true,
    startDate: "2024-12-01",
    endDate: "2025-03-31",
  },
  {
    id: "2",
    code: "WELLNESS200",
    description: "Flat ₹200 off on orders above ₹1500",
    type: "FLAT",
    value: 200,
    minOrderAmount: 1500,
    maxDiscount: null,
    usageLimit: 500,
    usageCount: 89,
    isActive: true,
    startDate: "2024-12-15",
    endDate: "2025-01-31",
  },
  {
    id: "3",
    code: "FREESHIP",
    description: "Free shipping on all orders",
    type: "FREE_SHIPPING",
    value: 0,
    minOrderAmount: 0,
    maxDiscount: null,
    usageLimit: null,
    usageCount: 456,
    isActive: true,
    startDate: "2024-12-01",
    endDate: "2025-12-31",
  },
  {
    id: "4",
    code: "NEWYEAR25",
    description: "25% off New Year Sale",
    type: "PERCENTAGE",
    value: 25,
    minOrderAmount: 1000,
    maxDiscount: 750,
    usageLimit: 200,
    usageCount: 200,
    isActive: false,
    startDate: "2024-12-28",
    endDate: "2025-01-05",
  },
];

export default function AdminCouponsPage() {
  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Coupons & Offers</h1>
          <p className="text-sm text-gray-500">Manage discount codes and promotional offers</p>
        </div>
        <Link href="/admin/coupons/new">
          <Button variant="cta" size="sm">
            <Plus size={16} className="mr-1" /> Create Coupon
          </Button>
        </Link>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div className="bg-white p-4 rounded-xl border border-gray-200 text-center">
          <p className="text-2xl font-bold text-primary">4</p>
          <p className="text-xs text-gray-500">Total Coupons</p>
        </div>
        <div className="bg-white p-4 rounded-xl border border-gray-200 text-center">
          <p className="text-2xl font-bold text-green-600">3</p>
          <p className="text-xs text-gray-500">Active</p>
        </div>
        <div className="bg-white p-4 rounded-xl border border-gray-200 text-center">
          <p className="text-2xl font-bold text-secondary">990</p>
          <p className="text-xs text-gray-500">Total Uses</p>
        </div>
        <div className="bg-white p-4 rounded-xl border border-gray-200 text-center">
          <p className="text-2xl font-bold text-orange-600">₹1.2L</p>
          <p className="text-xs text-gray-500">Total Discount Given</p>
        </div>
      </div>

      {/* Coupons List */}
      <div className="space-y-4">
        {COUPONS.map((coupon) => (
          <div
            key={coupon.id}
            className="bg-white p-6 rounded-2xl border border-gray-200 flex flex-col md:flex-row md:items-center gap-4"
          >
            {/* Coupon Code */}
            <div className="flex items-center gap-4 flex-1">
              <div className="w-12 h-12 bg-secondary/10 rounded-xl flex items-center justify-center">
                <Tag size={20} className="text-secondary" />
              </div>
              <div>
                <div className="flex items-center gap-2">
                  <span className="font-bold text-gray-900 font-mono text-lg">
                    {coupon.code}
                  </span>
                  <button className="text-gray-400 hover:text-primary">
                    <Copy size={14} />
                  </button>
                  {coupon.isActive ? (
                    <Badge variant="success">Active</Badge>
                  ) : (
                    <Badge variant="error">Expired</Badge>
                  )}
                </div>
                <p className="text-sm text-gray-500">{coupon.description}</p>
              </div>
            </div>

            {/* Details */}
            <div className="flex flex-wrap items-center gap-6 text-sm">
              <div className="text-center">
                <p className="font-semibold text-gray-900">
                  {coupon.type === "PERCENTAGE"
                    ? `${coupon.value}%`
                    : coupon.type === "FLAT"
                    ? formatPrice(coupon.value)
                    : "Free Ship"}
                </p>
                <p className="text-xs text-gray-400">Discount</p>
              </div>
              <div className="text-center">
                <p className="font-semibold text-gray-900">
                  {coupon.usageCount}/{coupon.usageLimit || "∞"}
                </p>
                <p className="text-xs text-gray-400">Used</p>
              </div>
              <div className="text-center">
                <p className="font-semibold text-gray-900">
                  {coupon.minOrderAmount ? formatPrice(coupon.minOrderAmount) : "No min"}
                </p>
                <p className="text-xs text-gray-400">Min Order</p>
              </div>
              <div className="text-center">
                <p className="text-xs text-gray-500">{coupon.startDate}</p>
                <p className="text-xs text-gray-500">to {coupon.endDate}</p>
              </div>
            </div>

            {/* Actions */}
            <div className="flex gap-2">
              <button className="p-2 text-gray-400 hover:text-primary rounded-lg hover:bg-gray-50">
                <Edit size={16} />
              </button>
              <button className="p-2 text-gray-400 hover:text-red-500 rounded-lg hover:bg-gray-50">
                <Trash2 size={16} />
              </button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
