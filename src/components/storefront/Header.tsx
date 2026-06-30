"use client";

import React, { useState } from "react";
import Link from "next/link";
import {
  Search,
  ShoppingCart,
  Heart,
  User,
  Menu,
  X,
  Phone,
  ChevronDown,
} from "lucide-react";
import { useCartStore } from "@/store/cart-store";
import { useWishlistStore } from "@/store/wishlist-store";
import { SITE_CONFIG } from "@/lib/constants";

export default function Header() {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [searchOpen, setSearchOpen] = useState(false);
  const cartItemCount = useCartStore((state) => state.getItemCount());
  const wishlistCount = useWishlistStore((state) => state.items.length);

  return (
    <header className="sticky top-0 z-50">
      {/* Top bar */}
      <div className="bg-primary text-white text-sm">
        <div className="max-w-7xl mx-auto px-4 py-2 flex items-center justify-between">
          <div className="flex items-center gap-4">
            <span className="flex items-center gap-1">
              <Phone size={14} />
              {SITE_CONFIG.phone}
            </span>
          </div>
          <div className="hidden md:block">
            <span className="text-secondary font-medium">
              Free Shipping on orders above ₹999 | 100% Natural & Ayurvedic
            </span>
          </div>
          <div className="flex items-center gap-4">
            <Link href="/account/orders" className="hover:text-secondary transition">
              Track Order
            </Link>
          </div>
        </div>
      </div>

      {/* Main header */}
      <div className="bg-white shadow-soft border-b border-border">
        <div className="max-w-7xl mx-auto px-4 py-4">
          <div className="flex items-center justify-between gap-4">
            {/* Mobile menu button */}
            <button
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
              className="lg:hidden text-primary"
            >
              {mobileMenuOpen ? <X size={24} /> : <Menu size={24} />}
            </button>

            {/* Logo */}
            <Link href="/" className="flex items-center gap-2">
              <div className="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                <span className="text-white font-bold text-lg">S</span>
              </div>
              <div>
                <h1 className="text-2xl font-bold text-primary tracking-tight">
                  SHIVARA
                </h1>
                <p className="text-[10px] text-secondary uppercase tracking-widest -mt-1">
                  Pure Ayurvedic Wellness
                </p>
              </div>
            </Link>

            {/* Search bar - desktop */}
            <div className="hidden lg:flex flex-1 max-w-xl mx-8">
              <div className="relative w-full">
                <input
                  type="text"
                  placeholder="Search for Ayurvedic products..."
                  className="w-full px-5 py-3 rounded-full border border-border bg-accent/50 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                />
                <button className="absolute right-2 top-1/2 -translate-y-1/2 bg-primary text-white p-2 rounded-full hover:bg-primary-light transition">
                  <Search size={18} />
                </button>
              </div>
            </div>

            {/* Actions */}
            <div className="flex items-center gap-3">
              {/* Mobile search */}
              <button
                onClick={() => setSearchOpen(!searchOpen)}
                className="lg:hidden text-primary p-2 hover:bg-primary-50 rounded-full"
              >
                <Search size={22} />
              </button>

              {/* Wishlist */}
              <Link
                href="/wishlist"
                className="relative text-primary p-2 hover:bg-primary-50 rounded-full transition"
              >
                <Heart size={22} />
                {wishlistCount > 0 && (
                  <span className="absolute -top-0.5 -right-0.5 bg-cta text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">
                    {wishlistCount}
                  </span>
                )}
              </Link>

              {/* Account */}
              <Link
                href="/auth/login"
                className="hidden sm:flex text-primary p-2 hover:bg-primary-50 rounded-full transition"
              >
                <User size={22} />
              </Link>

              {/* Cart */}
              <Link
                href="/cart"
                className="relative bg-primary text-white p-2.5 rounded-full hover:bg-primary-light transition shadow-md"
              >
                <ShoppingCart size={22} />
                {cartItemCount > 0 && (
                  <span className="absolute -top-1 -right-1 bg-cta text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center animate-pulse">
                    {cartItemCount}
                  </span>
                )}
              </Link>
            </div>
          </div>
        </div>

        {/* Navigation */}
        <nav className="hidden lg:block border-t border-border/50">
          <div className="max-w-7xl mx-auto px-4">
            <ul className="flex items-center gap-8 py-3">
              <li>
                <Link
                  href="/"
                  className="text-sm font-medium text-gray-700 hover:text-primary transition"
                >
                  Home
                </Link>
              </li>
              <li className="relative group">
                <button className="flex items-center gap-1 text-sm font-medium text-gray-700 hover:text-primary transition">
                  Shop <ChevronDown size={14} />
                </button>
                <div className="absolute top-full left-0 mt-2 w-56 bg-white rounded-xl shadow-hard border border-border opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                  <div className="p-4 space-y-2">
                    <Link
                      href="/products"
                      className="block px-3 py-2 rounded-lg hover:bg-accent text-sm text-gray-700 hover:text-primary transition"
                    >
                      All Products
                    </Link>
                    <Link
                      href="/products?category=capsules"
                      className="block px-3 py-2 rounded-lg hover:bg-accent text-sm text-gray-700 hover:text-primary transition"
                    >
                      Capsules & Tablets
                    </Link>
                    <Link
                      href="/products?category=oils"
                      className="block px-3 py-2 rounded-lg hover:bg-accent text-sm text-gray-700 hover:text-primary transition"
                    >
                      Oils & Syrups
                    </Link>
                    <Link
                      href="/products?category=powders"
                      className="block px-3 py-2 rounded-lg hover:bg-accent text-sm text-gray-700 hover:text-primary transition"
                    >
                      Herbal Powders
                    </Link>
                    <Link
                      href="/products?category=skincare"
                      className="block px-3 py-2 rounded-lg hover:bg-accent text-sm text-gray-700 hover:text-primary transition"
                    >
                      Skin & Hair Care
                    </Link>
                  </div>
                </div>
              </li>
              <li>
                <Link
                  href="/offers"
                  className="text-sm font-medium text-cta hover:text-cta-hover transition flex items-center gap-1"
                >
                  🔥 Offers
                </Link>
              </li>
              <li>
                <Link
                  href="/blog"
                  className="text-sm font-medium text-gray-700 hover:text-primary transition"
                >
                  Wellness Blog
                </Link>
              </li>
              <li>
                <Link
                  href="/contact"
                  className="text-sm font-medium text-gray-700 hover:text-primary transition"
                >
                  Contact
                </Link>
              </li>
            </ul>
          </div>
        </nav>
      </div>

      {/* Mobile search */}
      {searchOpen && (
        <div className="lg:hidden bg-white border-b border-border p-4 animate-fade-in">
          <div className="relative">
            <input
              type="text"
              placeholder="Search products..."
              className="w-full px-5 py-3 rounded-full border border-border bg-accent/50 focus:outline-none focus:ring-2 focus:ring-primary/20"
              autoFocus
            />
            <button className="absolute right-2 top-1/2 -translate-y-1/2 bg-primary text-white p-2 rounded-full">
              <Search size={18} />
            </button>
          </div>
        </div>
      )}

      {/* Mobile menu */}
      {mobileMenuOpen && (
        <div className="lg:hidden fixed inset-0 top-[120px] bg-white z-50 overflow-y-auto animate-fade-in">
          <nav className="p-6 space-y-4">
            <Link
              href="/"
              className="block text-lg font-medium text-gray-800 py-3 border-b border-border"
              onClick={() => setMobileMenuOpen(false)}
            >
              Home
            </Link>
            <Link
              href="/products"
              className="block text-lg font-medium text-gray-800 py-3 border-b border-border"
              onClick={() => setMobileMenuOpen(false)}
            >
              All Products
            </Link>
            <Link
              href="/offers"
              className="block text-lg font-medium text-cta py-3 border-b border-border"
              onClick={() => setMobileMenuOpen(false)}
            >
              🔥 Offers
            </Link>
            <Link
              href="/blog"
              className="block text-lg font-medium text-gray-800 py-3 border-b border-border"
              onClick={() => setMobileMenuOpen(false)}
            >
              Wellness Blog
            </Link>
            <Link
              href="/contact"
              className="block text-lg font-medium text-gray-800 py-3 border-b border-border"
              onClick={() => setMobileMenuOpen(false)}
            >
              Contact
            </Link>
            <Link
              href="/account"
              className="block text-lg font-medium text-gray-800 py-3 border-b border-border"
              onClick={() => setMobileMenuOpen(false)}
            >
              My Account
            </Link>
            <Link
              href="/account/orders"
              className="block text-lg font-medium text-gray-800 py-3 border-b border-border"
              onClick={() => setMobileMenuOpen(false)}
            >
              Track Order
            </Link>
          </nav>
        </div>
      )}
    </header>
  );
}
