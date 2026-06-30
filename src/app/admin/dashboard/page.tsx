"use client";

import React from "react";
import {
  TrendingUp,
  TrendingDown,
  ShoppingCart,
  Package,
  Users,
  IndianRupee,
  AlertTriangle,
  ArrowRight,
  MoreVertical,
} from "lucide-react";
import Link from "next/link";
import Badge from "@/components/ui/Badge";
import { formatPrice } from "@/lib/utils";

// Demo data
const STATS = [
  {
    title: "Total Revenue",
    value: "₹4,52,380",
    change: "+12.5%",
    trend: "up",
    icon: IndianRupee,
    color: "bg-green-50 text-green-600",
  },
  {
    title: "Total Orders",
    value: "384",
    change: "+8.2%",
    trend: "up",
    icon: ShoppingCart,
    color: "bg-blue-50 text-blue-600",
  },
  {
    title: "Products",
    value: "52",
    change: "+3",
    trend: "up",
    icon: Package,
    color: "bg-purple-50 text-purple-600",
  },
  {
    title: "Customers",
    value: "1,247",
    change: "+15.3%",
    trend: "up",
    icon: Users,
    color: "bg-orange-50 text-orange-600",
  },
];

const RECENT_ORDERS = [
  {
    id: "SHV-XK8M-A2",
    customer: "Priya Sharma",
    amount: 2498,
    status: "CONFIRMED",
    date: "2 hours ago",
    items: 2,
  },
  {
    id: "SHV-YL9N-B3",
    customer: "Rajesh Kumar",
    amount: 1249,
    status: "SHIPPED",
    date: "5 hours ago",
    items: 1,
  },
  {
    id: "SHV-ZM0P-C4",
    customer: "Anita Verma",
    amount: 3748,
    status: "PROCESSING",
    date: "8 hours ago",
    items: 3,
  },
  {
    id: "SHV-AN1Q-D5",
    customer: "Vikram Singh",
    amount: 899,
    status: "DELIVERED",
    date: "1 day ago",
    items: 1,
  },
  {
    id: "SHV-BO2R-E6",
    customer: "Meera Patel",
    amount: 4498,
    status: "PENDING",
    date: "1 day ago",
    items: 4,
  },
];

const LOW_STOCK = [
  { name: "Shilajit Gold Resin", stock: 3, image: "🪨" },
  { name: "Brahmi Memory Booster", stock: 5, image: "🧠" },
  { name: "Ashwagandha Gold (120 caps)", stock: 7, image: "🌿" },
];

const TOP_PRODUCTS = [
  { name: "Madhu Balance Capsules", sold: 145, revenue: 181105 },
  { name: "Ashwagandha Gold", sold: 128, revenue: 115072 },
  { name: "Shilajit Gold Resin", sold: 89, revenue: 160111 },
  { name: "Amla Vitamin C", sold: 76, revenue: 37924 },
];

const statusColors: Record<string, "success" | "warning" | "info" | "error" | "default"> = {
  PENDING: "warning",
  CONFIRMED: "info",
  PROCESSING: "info",
  SHIPPED: "default",
  DELIVERED: "success",
  CANCELLED: "error",
};

export default function AdminDashboard() {
  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Dashboard</h1>
          <p className="text-sm text-gray-500">Welcome back! Here&apos;s what&apos;s happening today.</p>
        </div>
        <div className="text-sm text-gray-500">
          {new Date().toLocaleDateString("en-IN", {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
          })}
        </div>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {STATS.map((stat) => (
          <div
            key={stat.title}
            className="bg-white p-6 rounded-2xl border border-gray-200 hover:shadow-md transition"
          >
            <div className="flex items-center justify-between mb-4">
              <div className={`w-10 h-10 rounded-xl flex items-center justify-center ${stat.color}`}>
                <stat.icon size={20} />
              </div>
              <span
                className={`text-xs font-medium flex items-center gap-0.5 ${
                  stat.trend === "up" ? "text-green-600" : "text-red-600"
                }`}
              >
                {stat.trend === "up" ? <TrendingUp size={14} /> : <TrendingDown size={14} />}
                {stat.change}
              </span>
            </div>
            <p className="text-2xl font-bold text-gray-900">{stat.value}</p>
            <p className="text-sm text-gray-500 mt-1">{stat.title}</p>
          </div>
        ))}
      </div>

      <div className="grid lg:grid-cols-3 gap-6">
        {/* Recent Orders */}
        <div className="lg:col-span-2 bg-white rounded-2xl border border-gray-200">
          <div className="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 className="font-bold text-gray-900">Recent Orders</h2>
            <Link
              href="/admin/orders"
              className="text-sm text-primary font-medium hover:underline flex items-center gap-1"
            >
              View All <ArrowRight size={14} />
            </Link>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead>
                <tr className="border-b border-gray-100">
                  <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">
                    Order
                  </th>
                  <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">
                    Customer
                  </th>
                  <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">
                    Amount
                  </th>
                  <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">
                    Status
                  </th>
                  <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">
                    Time
                  </th>
                </tr>
              </thead>
              <tbody>
                {RECENT_ORDERS.map((order) => (
                  <tr key={order.id} className="border-b border-gray-50 hover:bg-gray-50">
                    <td className="px-6 py-4">
                      <span className="text-sm font-medium text-primary">{order.id}</span>
                      <p className="text-xs text-gray-400">{order.items} items</p>
                    </td>
                    <td className="px-6 py-4 text-sm text-gray-700">{order.customer}</td>
                    <td className="px-6 py-4 text-sm font-semibold text-gray-900">
                      {formatPrice(order.amount)}
                    </td>
                    <td className="px-6 py-4">
                      <Badge variant={statusColors[order.status] || "default"}>
                        {order.status}
                      </Badge>
                    </td>
                    <td className="px-6 py-4 text-sm text-gray-500">{order.date}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

        {/* Sidebar */}
        <div className="space-y-6">
          {/* Low Stock Alert */}
          <div className="bg-white rounded-2xl border border-gray-200 p-6">
            <div className="flex items-center gap-2 mb-4">
              <AlertTriangle size={18} className="text-orange-500" />
              <h3 className="font-bold text-gray-900">Low Stock Alert</h3>
            </div>
            <div className="space-y-3">
              {LOW_STOCK.map((item) => (
                <div
                  key={item.name}
                  className="flex items-center justify-between p-3 bg-orange-50 rounded-xl"
                >
                  <div className="flex items-center gap-2">
                    <span className="text-xl">{item.image}</span>
                    <span className="text-sm font-medium text-gray-700 line-clamp-1">
                      {item.name}
                    </span>
                  </div>
                  <Badge variant="error">{item.stock} left</Badge>
                </div>
              ))}
            </div>
          </div>

          {/* Top Products */}
          <div className="bg-white rounded-2xl border border-gray-200 p-6">
            <h3 className="font-bold text-gray-900 mb-4">Top Products</h3>
            <div className="space-y-3">
              {TOP_PRODUCTS.map((product, i) => (
                <div key={product.name} className="flex items-center justify-between">
                  <div className="flex items-center gap-3">
                    <span className="w-6 h-6 bg-primary/10 rounded-full flex items-center justify-center text-xs font-bold text-primary">
                      {i + 1}
                    </span>
                    <div>
                      <p className="text-sm font-medium text-gray-700 line-clamp-1">
                        {product.name}
                      </p>
                      <p className="text-xs text-gray-400">{product.sold} sold</p>
                    </div>
                  </div>
                  <span className="text-sm font-semibold text-gray-900">
                    {formatPrice(product.revenue)}
                  </span>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
