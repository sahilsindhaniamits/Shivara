"use client";

import React from "react";
import { Star, CheckCircle, XCircle, MessageSquare } from "lucide-react";
import Button from "@/components/ui/Button";
import Badge from "@/components/ui/Badge";

const REVIEWS = [
  {
    id: "1",
    customer: "Priya Sharma",
    product: "Madhu Balance Capsules",
    rating: 5,
    title: "Excellent product!",
    comment: "Really helped with my metabolism. I can see visible changes in just 2 months.",
    date: "2 days ago",
    isApproved: true,
    isVerified: true,
  },
  {
    id: "2",
    customer: "Rajesh Kumar",
    product: "Ashwagandha Gold",
    rating: 4,
    title: "Good quality",
    comment: "Good product, but takes time to show results. Packaging was excellent.",
    date: "5 days ago",
    isApproved: true,
    isVerified: true,
  },
  {
    id: "3",
    customer: "Anita Verma",
    product: "Triphala Churna",
    rating: 5,
    title: "Must buy",
    comment: "Natural and effective. Best Triphala I have used so far. Will reorder.",
    date: "1 week ago",
    isApproved: false,
    isVerified: true,
  },
  {
    id: "4",
    customer: "Vikram Singh",
    product: "Brahmi Capsules",
    rating: 3,
    title: "Average",
    comment: "Decent product but expected better results. Maybe need to use longer.",
    date: "1 week ago",
    isApproved: false,
    isVerified: false,
  },
];

export default function AdminReviewsPage() {
  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Reviews</h1>
          <p className="text-sm text-gray-500">Moderate and manage customer reviews</p>
        </div>
        <div className="flex gap-3">
          <Badge variant="warning">2 Pending</Badge>
        </div>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div className="bg-white p-4 rounded-xl border border-gray-200 text-center">
          <p className="text-2xl font-bold text-primary">4.5</p>
          <p className="text-xs text-gray-500">Avg Rating</p>
        </div>
        <div className="bg-white p-4 rounded-xl border border-gray-200 text-center">
          <p className="text-2xl font-bold text-gray-900">128</p>
          <p className="text-xs text-gray-500">Total Reviews</p>
        </div>
        <div className="bg-white p-4 rounded-xl border border-gray-200 text-center">
          <p className="text-2xl font-bold text-green-600">126</p>
          <p className="text-xs text-gray-500">Approved</p>
        </div>
        <div className="bg-white p-4 rounded-xl border border-gray-200 text-center">
          <p className="text-2xl font-bold text-orange-600">2</p>
          <p className="text-xs text-gray-500">Pending</p>
        </div>
      </div>

      {/* Reviews List */}
      <div className="space-y-4">
        {REVIEWS.map((review) => (
          <div key={review.id} className="bg-white p-6 rounded-2xl border border-gray-200">
            <div className="flex flex-col md:flex-row md:items-start justify-between gap-4">
              <div className="flex-1">
                <div className="flex items-center gap-3 mb-2">
                  <div className="flex gap-0.5">
                    {Array.from({ length: 5 }).map((_, i) => (
                      <Star
                        key={i}
                        size={14}
                        className={
                          i < review.rating
                            ? "fill-yellow-400 text-yellow-400"
                            : "text-gray-200"
                        }
                      />
                    ))}
                  </div>
                  <span className="text-sm font-medium text-gray-900">{review.title}</span>
                  {review.isVerified && (
                    <Badge variant="success" size="sm">Verified</Badge>
                  )}
                  {!review.isApproved && (
                    <Badge variant="warning" size="sm">Pending</Badge>
                  )}
                </div>
                <p className="text-sm text-gray-600 mb-3">{review.comment}</p>
                <div className="flex items-center gap-4 text-xs text-gray-400">
                  <span>By {review.customer}</span>
                  <span>|</span>
                  <span>Product: {review.product}</span>
                  <span>|</span>
                  <span>{review.date}</span>
                </div>
              </div>

              <div className="flex gap-2">
                {!review.isApproved && (
                  <>
                    <Button variant="primary" size="sm">
                      <CheckCircle size={14} className="mr-1" /> Approve
                    </Button>
                    <Button variant="danger" size="sm">
                      <XCircle size={14} className="mr-1" /> Reject
                    </Button>
                  </>
                )}
                <Button variant="outline" size="sm">
                  <MessageSquare size={14} className="mr-1" /> Reply
                </Button>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
