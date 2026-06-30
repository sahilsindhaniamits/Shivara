"use client";

import React from "react";
import Link from "next/link";
import { Plus, Edit, Trash2, FolderTree } from "lucide-react";
import Button from "@/components/ui/Button";
import Badge from "@/components/ui/Badge";

const CATEGORIES = [
  { id: "1", name: "Capsules & Tablets", slug: "capsules", products: 12, isActive: true, sortOrder: 1 },
  { id: "2", name: "Herbal Powders", slug: "powders", products: 8, isActive: true, sortOrder: 2 },
  { id: "3", name: "Oils & Syrups", slug: "oils", products: 6, isActive: true, sortOrder: 3 },
  { id: "4", name: "Skin & Hair Care", slug: "skincare", products: 10, isActive: true, sortOrder: 4 },
  { id: "5", name: "Immunity Boosters", slug: "immunity", products: 5, isActive: true, sortOrder: 5 },
  { id: "6", name: "Digestive Health", slug: "digestive", products: 7, isActive: true, sortOrder: 6 },
  { id: "7", name: "Men's Health", slug: "mens-health", products: 4, isActive: false, sortOrder: 7 },
];

export default function AdminCategoriesPage() {
  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Categories</h1>
          <p className="text-sm text-gray-500">Organize your products into categories</p>
        </div>
        <Link href="/admin/categories/new">
          <Button variant="cta" size="sm">
            <Plus size={16} className="mr-1" /> Add Category
          </Button>
        </Link>
      </div>

      <div className="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <table className="w-full">
          <thead>
            <tr className="border-b border-gray-100 bg-gray-50">
              <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Category</th>
              <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Slug</th>
              <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Products</th>
              <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Status</th>
              <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            {CATEGORIES.map((cat) => (
              <tr key={cat.id} className="border-b border-gray-50 hover:bg-gray-50">
                <td className="px-6 py-4">
                  <div className="flex items-center gap-3">
                    <div className="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                      <FolderTree size={14} className="text-primary" />
                    </div>
                    <span className="font-medium text-gray-900">{cat.name}</span>
                  </div>
                </td>
                <td className="px-6 py-4 text-sm text-gray-500 font-mono">{cat.slug}</td>
                <td className="px-6 py-4 text-sm text-gray-700">{cat.products}</td>
                <td className="px-6 py-4">
                  <Badge variant={cat.isActive ? "success" : "default"}>
                    {cat.isActive ? "Active" : "Hidden"}
                  </Badge>
                </td>
                <td className="px-6 py-4">
                  <div className="flex gap-2">
                    <button className="text-gray-400 hover:text-primary"><Edit size={16} /></button>
                    <button className="text-gray-400 hover:text-red-500"><Trash2 size={16} /></button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
