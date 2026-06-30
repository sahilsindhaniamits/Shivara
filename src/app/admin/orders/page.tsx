"use client";

import React, { useState } from "react";
import Link from "next/link";
import {
  Search,
  Filter,
  Download,
  Eye,
  Truck,
  Package,
  MoreVertical,
  Calendar,
} from "lucide-react";
import Button from "@/components/ui/Button";
import Badge from "@/components/ui/Badge";
import { formatPrice, formatDate } from "@/lib/utils";

const ORDERS = [
  {
    id: "1",
    orderNumber: "SHV-XK8M-A2",
    customer: "Priya Sharma",
    email: "priya@email.com",
    phone: "+91-9876543210",
    items: 2,
    totalAmount: 2498,
    paymentMethod: "RAZORPAY",
    paymentStatus: "PAID",
    status: "CONFIRMED",
    shippingMethod: "SHIPROCKET",
    date: "2024-12-28",
  },
  {
    id: "2",
    orderNumber: "SHV-YL9N-B3",
    customer: "Rajesh Kumar",
    email: "rajesh@email.com",
    phone: "+91-9876543211",
    items: 1,
    totalAmount: 1249,
    paymentMethod: "COD",
    paymentStatus: "PENDING",
    status: "SHIPPED",
    shippingMethod: "MANUAL",
    date: "2024-12-27",
  },
  {
    id: "3",
    orderNumber: "SHV-ZM0P-C4",
    customer: "Anita Verma",
    email: "anita@email.com",
    phone: "+91-9876543212",
    items: 3,
    totalAmount: 3748,
    paymentMethod: "RAZORPAY",
    paymentStatus: "PAID",
    status: "PROCESSING",
    shippingMethod: "SHIPROCKET",
    date: "2024-12-26",
  },
  {
    id: "4",
    orderNumber: "SHV-AN1Q-D5",
    customer: "Vikram Singh",
    email: "vikram@email.com",
    phone: "+91-9876543213",
    items: 1,
    totalAmount: 899,
    paymentMethod: "RAZORPAY",
    paymentStatus: "PAID",
    status: "DELIVERED",
    shippingMethod: "MANUAL",
    date: "2024-12-25",
  },
  {
    id: "5",
    orderNumber: "SHV-BO2R-E6",
    customer: "Meera Patel",
    email: "meera@email.com",
    phone: "+91-9876543214",
    items: 4,
    totalAmount: 4498,
    paymentMethod: "COD",
    paymentStatus: "PENDING",
    status: "PENDING",
    shippingMethod: "STANDARD",
    date: "2024-12-28",
  },
];

const statusColors: Record<string, "success" | "warning" | "info" | "error" | "default"> = {
  PENDING: "warning",
  CONFIRMED: "info",
  PROCESSING: "info",
  SHIPPED: "default",
  OUT_FOR_DELIVERY: "info",
  DELIVERED: "success",
  CANCELLED: "error",
  RETURNED: "error",
};

const paymentStatusColors: Record<string, "success" | "warning" | "error"> = {
  PAID: "success",
  PENDING: "warning",
  FAILED: "error",
  REFUNDED: "info" as "success",
};

export default function AdminOrdersPage() {
  const [searchQuery, setSearchQuery] = useState("");
  const [statusFilter, setStatusFilter] = useState("all");
  const [selectedOrders, setSelectedOrders] = useState<string[]>([]);

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Orders</h1>
          <p className="text-sm text-gray-500">
            Manage and track all customer orders
          </p>
        </div>
        <div className="flex gap-3">
          <Button variant="outline" size="sm">
            <Download size={16} className="mr-1" /> Export
          </Button>
        </div>
      </div>

      {/* Stats Row */}
      <div className="grid grid-cols-2 md:grid-cols-5 gap-3">
        {[
          { label: "All", count: 384, active: statusFilter === "all" },
          { label: "Pending", count: 12, active: statusFilter === "PENDING" },
          { label: "Processing", count: 8, active: statusFilter === "PROCESSING" },
          { label: "Shipped", count: 15, active: statusFilter === "SHIPPED" },
          { label: "Delivered", count: 340, active: statusFilter === "DELIVERED" },
        ].map((stat) => (
          <button
            key={stat.label}
            onClick={() => setStatusFilter(stat.label === "All" ? "all" : stat.label.toUpperCase())}
            className={`p-3 rounded-xl text-center border transition ${
              stat.active
                ? "bg-primary text-white border-primary"
                : "bg-white border-gray-200 hover:border-primary/30"
            }`}
          >
            <p className={`text-xl font-bold ${stat.active ? "text-white" : "text-gray-900"}`}>
              {stat.count}
            </p>
            <p className={`text-xs ${stat.active ? "text-white/80" : "text-gray-500"}`}>
              {stat.label}
            </p>
          </button>
        ))}
      </div>

      {/* Filters */}
      <div className="bg-white p-4 rounded-2xl border border-gray-200 flex flex-col sm:flex-row items-center gap-4">
        <div className="relative flex-1 w-full">
          <Search size={18} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
          <input
            type="text"
            placeholder="Search by order number, customer name..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"
          />
        </div>
        <div className="flex gap-2 w-full sm:w-auto">
          <select className="px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none w-full sm:w-auto">
            <option>All Payment</option>
            <option>Razorpay</option>
            <option>COD</option>
          </select>
          <select className="px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none w-full sm:w-auto">
            <option>All Shipping</option>
            <option>Shiprocket</option>
            <option>Manual</option>
          </select>
        </div>
      </div>

      {/* Bulk Actions */}
      {selectedOrders.length > 0 && (
        <div className="bg-primary/5 p-4 rounded-xl border border-primary/20 flex items-center justify-between">
          <span className="text-sm font-medium text-primary">
            {selectedOrders.length} order(s) selected
          </span>
          <div className="flex gap-2">
            <Button variant="outline" size="sm">
              <Truck size={14} className="mr-1" /> Ship via Shiprocket
            </Button>
            <Button variant="outline" size="sm">
              <Package size={14} className="mr-1" /> Mark as Shipped (Manual)
            </Button>
            <Button variant="outline" size="sm">
              <Download size={14} className="mr-1" /> Download Invoices
            </Button>
          </div>
        </div>
      )}

      {/* Orders Table */}
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
                        setSelectedOrders(ORDERS.map((o) => o.id));
                      } else {
                        setSelectedOrders([]);
                      }
                    }}
                  />
                </th>
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
                  Payment
                </th>
                <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">
                  Status
                </th>
                <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">
                  Shipping
                </th>
                <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">
                  Date
                </th>
                <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody>
              {ORDERS.map((order) => (
                <tr key={order.id} className="border-b border-gray-50 hover:bg-gray-50">
                  <td className="px-6 py-4">
                    <input
                      type="checkbox"
                      className="rounded"
                      checked={selectedOrders.includes(order.id)}
                      onChange={(e) => {
                        if (e.target.checked) {
                          setSelectedOrders([...selectedOrders, order.id]);
                        } else {
                          setSelectedOrders(selectedOrders.filter((id) => id !== order.id));
                        }
                      }}
                    />
                  </td>
                  <td className="px-6 py-4">
                    <Link
                      href={`/admin/orders/${order.id}`}
                      className="text-sm font-medium text-primary hover:underline"
                    >
                      {order.orderNumber}
                    </Link>
                    <p className="text-xs text-gray-400">{order.items} items</p>
                  </td>
                  <td className="px-6 py-4">
                    <p className="text-sm text-gray-700">{order.customer}</p>
                    <p className="text-xs text-gray-400">{order.phone}</p>
                  </td>
                  <td className="px-6 py-4 text-sm font-semibold text-gray-900">
                    {formatPrice(order.totalAmount)}
                  </td>
                  <td className="px-6 py-4">
                    <Badge variant={paymentStatusColors[order.paymentStatus] || "warning"} size="sm">
                      {order.paymentStatus}
                    </Badge>
                    <p className="text-xs text-gray-400 mt-0.5">{order.paymentMethod}</p>
                  </td>
                  <td className="px-6 py-4">
                    <Badge variant={statusColors[order.status] || "default"}>
                      {order.status}
                    </Badge>
                  </td>
                  <td className="px-6 py-4">
                    <span className="text-xs text-gray-500">{order.shippingMethod}</span>
                  </td>
                  <td className="px-6 py-4 text-sm text-gray-500">{order.date}</td>
                  <td className="px-6 py-4">
                    <Link
                      href={`/admin/orders/${order.id}`}
                      className="text-gray-400 hover:text-primary transition"
                    >
                      <Eye size={16} />
                    </Link>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
