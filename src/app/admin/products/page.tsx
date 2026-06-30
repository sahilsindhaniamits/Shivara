"use client";

import React, { useState } from "react";
import Link from "next/link";
import {
  Plus,
  Search,
  Filter,
  MoreVertical,
  Edit,
  Trash2,
  Eye,
  Download,
  Upload,
} from "lucide-react";
import Button from "@/components/ui/Button";
import Badge from "@/components/ui/Badge";
import { formatPrice } from "@/lib/utils";

const PRODUCTS = [
  {
    id: "1",
    name: "Madhu Balance Capsules",
    sku: "SHV-MBC-001",
    category: "Capsules",
    mrp: 2799,
    sellingPrice: 1249,
    stock: 50,
    status: "active",
    image: "🌿",
  },
  {
    id: "2",
    name: "Ashwagandha Gold Capsules",
    sku: "SHV-AGC-002",
    category: "Capsules",
    mrp: 1499,
    sellingPrice: 899,
    stock: 35,
    status: "active",
    image: "🌿",
  },
  {
    id: "3",
    name: "Triphala Churna",
    sku: "SHV-TC-003",
    category: "Powders",
    mrp: 599,
    sellingPrice: 449,
    stock: 100,
    status: "active",
    image: "🌿",
  },
  {
    id: "4",
    name: "Brahmi Memory Booster",
    sku: "SHV-BMB-004",
    category: "Capsules",
    mrp: 1999,
    sellingPrice: 1299,
    stock: 5,
    status: "low-stock",
    image: "🌿",
  },
  {
    id: "5",
    name: "Shilajit Gold Resin",
    sku: "SHV-SGR-005",
    category: "Supplements",
    mrp: 2499,
    sellingPrice: 1799,
    stock: 0,
    status: "out-of-stock",
    image: "🪨",
  },
];

export default function AdminProductsPage() {
  const [searchQuery, setSearchQuery] = useState("");
  const [selectedProducts, setSelectedProducts] = useState<string[]>([]);

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Products</h1>
          <p className="text-sm text-gray-500">Manage your product catalog</p>
        </div>
        <div className="flex gap-3">
          <Button variant="outline" size="sm">
            <Upload size={16} className="mr-1" /> Import CSV
          </Button>
          <Button variant="outline" size="sm">
            <Download size={16} className="mr-1" /> Export
          </Button>
          <Link href="/admin/products/new">
            <Button variant="cta" size="sm">
              <Plus size={16} className="mr-1" /> Add Product
            </Button>
          </Link>
        </div>
      </div>

      {/* Filters */}
      <div className="bg-white p-4 rounded-2xl border border-gray-200 flex flex-col sm:flex-row items-center gap-4">
        <div className="relative flex-1 w-full">
          <Search size={18} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
          <input
            type="text"
            placeholder="Search products by name, SKU..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"
          />
        </div>
        <div className="flex gap-2 w-full sm:w-auto">
          <select className="px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none w-full sm:w-auto">
            <option>All Categories</option>
            <option>Capsules</option>
            <option>Powders</option>
            <option>Oils</option>
            <option>Supplements</option>
          </select>
          <select className="px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none w-full sm:w-auto">
            <option>All Status</option>
            <option>Active</option>
            <option>Low Stock</option>
            <option>Out of Stock</option>
            <option>Draft</option>
          </select>
        </div>
      </div>

      {/* Products Table */}
      <div className="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead>
              <tr className="border-b border-gray-100 bg-gray-50">
                <th className="text-left px-6 py-3">
                  <input
                    type="checkbox"
                    className="rounded"
                    onChange={(e) => {
                      if (e.target.checked) {
                        setSelectedProducts(PRODUCTS.map((p) => p.id));
                      } else {
                        setSelectedProducts([]);
                      }
                    }}
                  />
                </th>
                <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">
                  Product
                </th>
                <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">
                  SKU
                </th>
                <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">
                  Category
                </th>
                <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">
                  Price
                </th>
                <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">
                  Stock
                </th>
                <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">
                  Status
                </th>
                <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody>
              {PRODUCTS.map((product) => (
                <tr key={product.id} className="border-b border-gray-50 hover:bg-gray-50">
                  <td className="px-6 py-4">
                    <input
                      type="checkbox"
                      className="rounded"
                      checked={selectedProducts.includes(product.id)}
                      onChange={(e) => {
                        if (e.target.checked) {
                          setSelectedProducts([...selectedProducts, product.id]);
                        } else {
                          setSelectedProducts(selectedProducts.filter((id) => id !== product.id));
                        }
                      }}
                    />
                  </td>
                  <td className="px-6 py-4">
                    <div className="flex items-center gap-3">
                      <div className="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                        <span className="text-lg">{product.image}</span>
                      </div>
                      <span className="text-sm font-medium text-gray-900">{product.name}</span>
                    </div>
                  </td>
                  <td className="px-6 py-4 text-sm text-gray-500 font-mono">{product.sku}</td>
                  <td className="px-6 py-4 text-sm text-gray-600">{product.category}</td>
                  <td className="px-6 py-4">
                    <div>
                      <span className="text-sm font-semibold text-gray-900">
                        {formatPrice(product.sellingPrice)}
                      </span>
                      <span className="text-xs text-gray-400 line-through ml-2">
                        {formatPrice(product.mrp)}
                      </span>
                    </div>
                  </td>
                  <td className="px-6 py-4 text-sm font-medium">
                    <span
                      className={
                        product.stock === 0
                          ? "text-red-600"
                          : product.stock <= 10
                          ? "text-orange-600"
                          : "text-green-600"
                      }
                    >
                      {product.stock}
                    </span>
                  </td>
                  <td className="px-6 py-4">
                    <Badge
                      variant={
                        product.status === "active"
                          ? "success"
                          : product.status === "low-stock"
                          ? "warning"
                          : "error"
                      }
                    >
                      {product.status === "active"
                        ? "Active"
                        : product.status === "low-stock"
                        ? "Low Stock"
                        : "Out of Stock"}
                    </Badge>
                  </td>
                  <td className="px-6 py-4">
                    <div className="flex items-center gap-2">
                      <Link
                        href={`/admin/products/${product.id}`}
                        className="text-gray-400 hover:text-primary transition"
                      >
                        <Edit size={16} />
                      </Link>
                      <button className="text-gray-400 hover:text-red-500 transition">
                        <Trash2 size={16} />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        {/* Pagination */}
        <div className="p-4 border-t border-gray-100 flex items-center justify-between">
          <p className="text-sm text-gray-500">
            Showing 1-{PRODUCTS.length} of {PRODUCTS.length} products
          </p>
          <div className="flex gap-1">
            <button className="px-3 py-1.5 border border-gray-200 rounded-lg text-sm bg-primary text-white">
              1
            </button>
            <button className="px-3 py-1.5 border border-gray-200 rounded-lg text-sm hover:bg-gray-50">
              2
            </button>
            <button className="px-3 py-1.5 border border-gray-200 rounded-lg text-sm hover:bg-gray-50">
              3
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
