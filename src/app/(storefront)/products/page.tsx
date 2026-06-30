"use client";

import React, { useState } from "react";
import { Filter, Grid3X3, List, SlidersHorizontal } from "lucide-react";
import ProductCard from "@/components/storefront/ProductCard";
import Button from "@/components/ui/Button";

const ALL_PRODUCTS = [
  {
    id: "1",
    name: "Madhu Balance Capsules - For Maintaining Healthy Metabolism",
    slug: "madhu-balance-capsules",
    image: "",
    mrp: 2799,
    sellingPrice: 1249,
    rating: 4.5,
    reviewCount: 128,
    stock: 50,
    isFeatured: true,
  },
  {
    id: "2",
    name: "Ashwagandha Gold Capsules - Stress Relief & Vitality",
    slug: "ashwagandha-gold-capsules",
    image: "",
    mrp: 1499,
    sellingPrice: 899,
    rating: 4.7,
    reviewCount: 245,
    stock: 35,
    isFeatured: true,
  },
  {
    id: "3",
    name: "Triphala Churna - Digestive Health & Detox",
    slug: "triphala-churna",
    image: "",
    mrp: 599,
    sellingPrice: 449,
    rating: 4.3,
    reviewCount: 89,
    stock: 100,
    isFeatured: false,
  },
  {
    id: "4",
    name: "Brahmi Memory Booster - Cognitive Wellness",
    slug: "brahmi-memory-booster",
    image: "",
    mrp: 1999,
    sellingPrice: 1299,
    rating: 4.6,
    reviewCount: 67,
    stock: 25,
    isFeatured: true,
  },
  {
    id: "5",
    name: "Giloy Immunity Booster Tablets",
    slug: "giloy-immunity-booster",
    image: "",
    mrp: 799,
    sellingPrice: 549,
    rating: 4.4,
    reviewCount: 156,
    stock: 80,
    isFeatured: false,
  },
  {
    id: "6",
    name: "Neem & Turmeric Blood Purifier Capsules",
    slug: "neem-turmeric-capsules",
    image: "",
    mrp: 999,
    sellingPrice: 699,
    rating: 4.2,
    reviewCount: 98,
    stock: 60,
    isFeatured: false,
  },
  {
    id: "7",
    name: "Amla Vitamin C Natural Supplement",
    slug: "amla-vitamin-c",
    image: "",
    mrp: 699,
    sellingPrice: 499,
    rating: 4.5,
    reviewCount: 200,
    stock: 45,
    isFeatured: true,
  },
  {
    id: "8",
    name: "Shilajit Gold Resin - Energy & Stamina",
    slug: "shilajit-gold-resin",
    image: "",
    mrp: 2499,
    sellingPrice: 1799,
    rating: 4.8,
    reviewCount: 312,
    stock: 20,
    isFeatured: true,
  },
];

const CATEGORIES = [
  "All",
  "Capsules & Tablets",
  "Herbal Powders",
  "Oils & Syrups",
  "Skin & Hair Care",
  "Immunity",
  "Digestive Health",
];

const SORT_OPTIONS = [
  { value: "featured", label: "Featured" },
  { value: "price-low", label: "Price: Low to High" },
  { value: "price-high", label: "Price: High to Low" },
  { value: "newest", label: "Newest First" },
  { value: "rating", label: "Highest Rated" },
  { value: "discount", label: "Best Discount" },
];

export default function ProductsPage() {
  const [selectedCategory, setSelectedCategory] = useState("All");
  const [sortBy, setSortBy] = useState("featured");
  const [showFilters, setShowFilters] = useState(false);
  const [priceRange, setPriceRange] = useState<[number, number]>([0, 5000]);

  return (
    <div className="max-w-7xl mx-auto px-4 py-8">
      {/* Breadcrumb */}
      <nav className="text-sm text-gray-500 mb-6">
        <span>Home</span> / <span className="text-primary font-medium">All Products</span>
      </nav>

      <div className="flex flex-col lg:flex-row gap-8">
        {/* Sidebar Filters - Desktop */}
        <aside className="hidden lg:block w-64 shrink-0">
          <div className="sticky top-40 space-y-6">
            <div>
              <h3 className="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <Filter size={18} /> Categories
              </h3>
              <div className="space-y-2">
                {CATEGORIES.map((cat) => (
                  <button
                    key={cat}
                    onClick={() => setSelectedCategory(cat)}
                    className={`block w-full text-left px-3 py-2 rounded-lg text-sm transition ${
                      selectedCategory === cat
                        ? "bg-primary text-white font-medium"
                        : "text-gray-600 hover:bg-accent"
                    }`}
                  >
                    {cat}
                  </button>
                ))}
              </div>
            </div>

            <div>
              <h3 className="font-semibold text-gray-800 mb-3">Price Range</h3>
              <div className="space-y-3">
                <div className="flex items-center gap-2">
                  <input
                    type="number"
                    placeholder="Min"
                    value={priceRange[0]}
                    onChange={(e) =>
                      setPriceRange([Number(e.target.value), priceRange[1]])
                    }
                    className="w-full px-3 py-2 border border-border rounded-lg text-sm"
                  />
                  <span className="text-gray-400">-</span>
                  <input
                    type="number"
                    placeholder="Max"
                    value={priceRange[1]}
                    onChange={(e) =>
                      setPriceRange([priceRange[0], Number(e.target.value)])
                    }
                    className="w-full px-3 py-2 border border-border rounded-lg text-sm"
                  />
                </div>
                <Button variant="outline" size="sm" fullWidth>
                  Apply
                </Button>
              </div>
            </div>

            <div>
              <h3 className="font-semibold text-gray-800 mb-3">Rating</h3>
              <div className="space-y-2">
                {[4, 3, 2, 1].map((rating) => (
                  <label
                    key={rating}
                    className="flex items-center gap-2 cursor-pointer text-sm text-gray-600"
                  >
                    <input type="checkbox" className="rounded text-primary" />
                    {rating}★ & above
                  </label>
                ))}
              </div>
            </div>
          </div>
        </aside>

        {/* Main Content */}
        <div className="flex-1">
          {/* Toolbar */}
          <div className="flex items-center justify-between mb-6 bg-white p-4 rounded-xl border border-border">
            <div className="flex items-center gap-3">
              <button
                onClick={() => setShowFilters(!showFilters)}
                className="lg:hidden flex items-center gap-1 text-sm font-medium text-gray-700 px-3 py-2 border border-border rounded-lg"
              >
                <SlidersHorizontal size={16} /> Filters
              </button>
              <p className="text-sm text-gray-500">
                Showing <span className="font-semibold text-gray-800">{ALL_PRODUCTS.length}</span>{" "}
                products
              </p>
            </div>

            <div className="flex items-center gap-3">
              <select
                value={sortBy}
                onChange={(e) => setSortBy(e.target.value)}
                className="px-3 py-2 border border-border rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20"
              >
                {SORT_OPTIONS.map((opt) => (
                  <option key={opt.value} value={opt.value}>
                    {opt.label}
                  </option>
                ))}
              </select>
            </div>
          </div>

          {/* Mobile Filters */}
          {showFilters && (
            <div className="lg:hidden mb-6 bg-white p-4 rounded-xl border border-border animate-fade-in">
              <div className="flex flex-wrap gap-2">
                {CATEGORIES.map((cat) => (
                  <button
                    key={cat}
                    onClick={() => setSelectedCategory(cat)}
                    className={`px-3 py-1.5 rounded-full text-sm transition ${
                      selectedCategory === cat
                        ? "bg-primary text-white"
                        : "bg-accent text-gray-600 border border-border"
                    }`}
                  >
                    {cat}
                  </button>
                ))}
              </div>
            </div>
          )}

          {/* Product Grid */}
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4 md:gap-6">
            {ALL_PRODUCTS.map((product) => (
              <ProductCard key={product.id} {...product} />
            ))}
          </div>

          {/* Load More */}
          <div className="mt-12 text-center">
            <Button variant="outline" size="lg">
              Load More Products
            </Button>
          </div>
        </div>
      </div>
    </div>
  );
}
