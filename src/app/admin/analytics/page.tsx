"use client";

import React from "react";
import {
  TrendingUp,
  DollarSign,
  ShoppingCart,
  Users,
  Target,
  ArrowUpRight,
  ArrowDownRight,
} from "lucide-react";

const MONTHLY_DATA = [
  { month: "Jul", revenue: 125000 },
  { month: "Aug", revenue: 148000 },
  { month: "Sep", revenue: 172000 },
  { month: "Oct", revenue: 195000 },
  { month: "Nov", revenue: 234000 },
  { month: "Dec", revenue: 452380 },
];

const TOP_CHANNELS = [
  { name: "Direct", visits: 4520, orders: 145, rate: "3.2%" },
  { name: "Google Organic", visits: 3200, orders: 98, rate: "3.1%" },
  { name: "Instagram", visits: 2800, orders: 67, rate: "2.4%" },
  { name: "Facebook Ads", visits: 1500, orders: 52, rate: "3.5%" },
  { name: "WhatsApp", visits: 980, orders: 38, rate: "3.9%" },
];

export default function AdminAnalyticsPage() {
  const maxRevenue = Math.max(...MONTHLY_DATA.map((d) => d.revenue));

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-bold text-gray-900">Analytics</h1>
        <p className="text-sm text-gray-500">Business performance overview</p>
      </div>

      {/* KPI Cards */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div className="bg-white p-5 rounded-2xl border border-gray-200">
          <div className="flex items-center justify-between mb-3">
            <DollarSign size={20} className="text-green-600" />
            <span className="text-xs text-green-600 flex items-center">
              <ArrowUpRight size={12} /> 12.5%
            </span>
          </div>
          <p className="text-2xl font-bold text-gray-900">₹4.52L</p>
          <p className="text-xs text-gray-500">Monthly Revenue</p>
        </div>
        <div className="bg-white p-5 rounded-2xl border border-gray-200">
          <div className="flex items-center justify-between mb-3">
            <ShoppingCart size={20} className="text-blue-600" />
            <span className="text-xs text-green-600 flex items-center">
              <ArrowUpRight size={12} /> 8.2%
            </span>
          </div>
          <p className="text-2xl font-bold text-gray-900">384</p>
          <p className="text-xs text-gray-500">Monthly Orders</p>
        </div>
        <div className="bg-white p-5 rounded-2xl border border-gray-200">
          <div className="flex items-center justify-between mb-3">
            <Target size={20} className="text-purple-600" />
            <span className="text-xs text-green-600 flex items-center">
              <ArrowUpRight size={12} /> 0.3%
            </span>
          </div>
          <p className="text-2xl font-bold text-gray-900">3.2%</p>
          <p className="text-xs text-gray-500">Conversion Rate</p>
        </div>
        <div className="bg-white p-5 rounded-2xl border border-gray-200">
          <div className="flex items-center justify-between mb-3">
            <Users size={20} className="text-orange-600" />
            <span className="text-xs text-red-600 flex items-center">
              <ArrowDownRight size={12} /> 2.1%
            </span>
          </div>
          <p className="text-2xl font-bold text-gray-900">₹1,178</p>
          <p className="text-xs text-gray-500">Avg Order Value</p>
        </div>
      </div>

      <div className="grid lg:grid-cols-2 gap-6">
        {/* Revenue Chart (Simple bar) */}
        <div className="bg-white p-6 rounded-2xl border border-gray-200">
          <h3 className="font-bold text-gray-900 mb-6">Revenue Trend</h3>
          <div className="flex items-end justify-between gap-3 h-48">
            {MONTHLY_DATA.map((d) => (
              <div key={d.month} className="flex flex-col items-center flex-1">
                <div
                  className="w-full bg-primary/80 rounded-t-lg hover:bg-primary transition min-h-[4px]"
                  style={{ height: `${(d.revenue / maxRevenue) * 100}%` }}
                  title={`₹${(d.revenue / 1000).toFixed(0)}K`}
                />
                <span className="text-xs text-gray-500 mt-2">{d.month}</span>
              </div>
            ))}
          </div>
        </div>

        {/* Traffic Sources */}
        <div className="bg-white p-6 rounded-2xl border border-gray-200">
          <h3 className="font-bold text-gray-900 mb-4">Traffic Sources</h3>
          <div className="space-y-4">
            {TOP_CHANNELS.map((channel) => (
              <div key={channel.name} className="flex items-center justify-between">
                <div className="flex-1">
                  <div className="flex items-center justify-between mb-1">
                    <span className="text-sm font-medium text-gray-700">{channel.name}</span>
                    <span className="text-xs text-gray-500">{channel.visits} visits</span>
                  </div>
                  <div className="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div
                      className="h-full bg-primary rounded-full"
                      style={{ width: `${(channel.visits / 4520) * 100}%` }}
                    />
                  </div>
                </div>
                <div className="ml-4 text-right">
                  <p className="text-sm font-semibold text-gray-900">{channel.orders}</p>
                  <p className="text-xs text-gray-400">orders ({channel.rate})</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
