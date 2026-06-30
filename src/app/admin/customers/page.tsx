"use client";

import React from "react";
import { Search, Download, Mail, Phone, ShoppingBag } from "lucide-react";
import Button from "@/components/ui/Button";
import Badge from "@/components/ui/Badge";
import { formatPrice } from "@/lib/utils";

const CUSTOMERS = [
  { id: "1", name: "Priya Sharma", email: "priya@email.com", phone: "+91-9876543210", orders: 5, totalSpent: 8745, lastOrder: "2 days ago", status: "active" },
  { id: "2", name: "Rajesh Kumar", email: "rajesh@email.com", phone: "+91-9876543211", orders: 3, totalSpent: 4497, lastOrder: "5 days ago", status: "active" },
  { id: "3", name: "Anita Verma", email: "anita@email.com", phone: "+91-9876543212", orders: 8, totalSpent: 15680, lastOrder: "1 day ago", status: "active" },
  { id: "4", name: "Vikram Singh", email: "vikram@email.com", phone: "+91-9876543213", orders: 1, totalSpent: 899, lastOrder: "2 weeks ago", status: "active" },
  { id: "5", name: "Meera Patel", email: "meera@email.com", phone: "+91-9876543214", orders: 12, totalSpent: 24560, lastOrder: "Today", status: "active" },
];

export default function AdminCustomersPage() {
  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Customers</h1>
          <p className="text-sm text-gray-500">{CUSTOMERS.length} total customers</p>
        </div>
        <Button variant="outline" size="sm">
          <Download size={16} className="mr-1" /> Export
        </Button>
      </div>

      {/* Search */}
      <div className="bg-white p-4 rounded-2xl border border-gray-200">
        <div className="relative">
          <Search size={18} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
          <input
            type="text"
            placeholder="Search customers by name, email, phone..."
            className="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"
          />
        </div>
      </div>

      {/* Table */}
      <div className="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead>
              <tr className="border-b border-gray-100 bg-gray-50">
                <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Customer</th>
                <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Contact</th>
                <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Orders</th>
                <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Total Spent</th>
                <th className="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Last Order</th>
              </tr>
            </thead>
            <tbody>
              {CUSTOMERS.map((customer) => (
                <tr key={customer.id} className="border-b border-gray-50 hover:bg-gray-50">
                  <td className="px-6 py-4">
                    <div className="flex items-center gap-3">
                      <div className="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                        <span className="text-xs font-bold text-primary">
                          {customer.name.split(" ").map((n) => n[0]).join("")}
                        </span>
                      </div>
                      <span className="text-sm font-medium text-gray-900">{customer.name}</span>
                    </div>
                  </td>
                  <td className="px-6 py-4">
                    <div className="flex items-center gap-1 text-sm text-gray-500">
                      <Mail size={12} /> {customer.email}
                    </div>
                    <div className="flex items-center gap-1 text-xs text-gray-400 mt-0.5">
                      <Phone size={10} /> {customer.phone}
                    </div>
                  </td>
                  <td className="px-6 py-4">
                    <div className="flex items-center gap-1 text-sm text-gray-700">
                      <ShoppingBag size={14} /> {customer.orders}
                    </div>
                  </td>
                  <td className="px-6 py-4 text-sm font-semibold text-gray-900">
                    {formatPrice(customer.totalSpent)}
                  </td>
                  <td className="px-6 py-4 text-sm text-gray-500">{customer.lastOrder}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
