"use client";

import React from "react";
import { Plus, Edit, Trash2, Image, GripVertical } from "lucide-react";
import Button from "@/components/ui/Button";
import Badge from "@/components/ui/Badge";

const BANNERS = [
  { id: "1", title: "New Year Sale", subtitle: "Up to 50% off", isActive: true, link: "/offers" },
  { id: "2", title: "Madhu Balance", subtitle: "55% off limited time", isActive: true, link: "/products/madhu-balance-capsules" },
  { id: "3", title: "Free Shipping", subtitle: "On orders above ₹999", isActive: true, link: "/products" },
  { id: "4", title: "Summer Collection", subtitle: "Coming Soon", isActive: false, link: "#" },
];

export default function AdminBannersPage() {
  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Banners & CMS</h1>
          <p className="text-sm text-gray-500">Manage homepage banners and promotional content</p>
        </div>
        <Button variant="cta" size="sm">
          <Plus size={16} className="mr-1" /> Add Banner
        </Button>
      </div>

      {/* Banners List */}
      <div className="space-y-3">
        {BANNERS.map((banner) => (
          <div key={banner.id} className="bg-white p-4 rounded-2xl border border-gray-200 flex items-center gap-4">
            <GripVertical size={18} className="text-gray-300 cursor-grab" />
            
            <div className="w-32 h-20 bg-accent rounded-xl flex items-center justify-center shrink-0">
              <Image size={24} className="text-gray-300" />
            </div>

            <div className="flex-1">
              <div className="flex items-center gap-2">
                <h3 className="font-medium text-gray-900">{banner.title}</h3>
                <Badge variant={banner.isActive ? "success" : "default"}>
                  {banner.isActive ? "Active" : "Draft"}
                </Badge>
              </div>
              <p className="text-sm text-gray-500">{banner.subtitle}</p>
              <p className="text-xs text-gray-400 mt-0.5">Link: {banner.link}</p>
            </div>

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

      {/* Announcement Bar */}
      <div className="bg-white p-6 rounded-2xl border border-gray-200">
        <h2 className="font-bold text-gray-900 mb-4">Announcement Bar</h2>
        <textarea
          defaultValue="🎉 FLAT 10% OFF on first order | Use Code: SHIVARA10 | View All Offers"
          rows={2}
          className="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"
        />
        <div className="mt-3 flex gap-2">
          <Button variant="primary" size="sm">Save</Button>
          <label className="flex items-center gap-2">
            <input type="checkbox" defaultChecked className="rounded text-primary" />
            <span className="text-sm text-gray-600">Show on site</span>
          </label>
        </div>
      </div>
    </div>
  );
}
