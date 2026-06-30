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
      {/* Announcement bar - dark marquee */}
      <div className="bg-secondary text-white overflow-hidden">
        <div className="py-2.5 flex">
          <div className="animate-marquee flex items-center gap-8 whitespace-nowrap text-xs tracking-wide">
            <span>10% OFF UPTO ₹200 — CODE: WOW10</span>
            <span className="text-primary">·</span>
            <span>15% OFF UPTO ₹500 — CODE: EXTRA15</span>
            <span className="text-primary">·</span>
            <span>FREE SHIPPING ON ALL ORDERS</span>
            <span className="text-primary">·</span>
            <span>AYURVEDA, CRAFTED WITH CARE</span>
            <span className="text-primary mx-8">10% OFF UPTO ₹200 — CODE: WOW10</span>
            <span className="text-primary">·</span>
            <span>15% OFF UPTO ₹500 — CODE: EXTRA15</span>
            <span className="text-primary">·</span>
            <span>FREE SHIPPING ON ALL ORDERS</span>
            <span className="text-primary">·</span>
            <span>AYURVEDA, CRAFTED WITH CARE</span>
          </div>
        </div>
      </div>

      {/* Main header */}
      <div className="bg-white/95 backdrop-blur-md border-b border-border">
        <div className="max-w-7xl mx-auto px-4 py-4">
          <div className="flex items-center justify-between gap-4">
            {/* Mobile menu button */}
            <button
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
              className="lg:hidden text-secondary"
            >
              {mobileMenuOpen ? <X size={22} /> : <Menu size={22} />}
            </button>

            {/* Navigation - left (desktop) */}
            <nav className="hidden lg:flex items-center gap-8">
              <Link
                href="/"
                className="text-xs font-medium uppercase tracking-widest text-secondary hover:text-primary transition"
              >
                Home
              </Link>
              <div className="relative group">
                <button className="flex items-center gap-1 text-xs font-medium uppercase tracking-widest text-secondary hover:text-primary transition">
                  Shop <ChevronDown size={12} />
                </button>
                <div className="absolute top-full left-0 mt-3 w-52 bg-white rounded-lg shadow-hard border border-border opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                  <div className="p-4 space-y-1">
                    <Link
                      href="/products"
                      className="block px-3 py-2.5 rounded-lg hover:bg-accent text-sm text-secondary hover:text-primary transition"
                    >
                      All Products
                    </Link>
                    <Link
                      href="/products?category=capsules"
                      className="block px-3 py-2.5 rounded-lg hover:bg-accent text-sm text-secondary hover:text-primary transition"
                    >
                      Capsules & Tablets
                    </Link>
                    <Link
                      href="/products?category=oils"
                      className="block px-3 py-2.5 rounded-lg hover:bg-accent text-sm text-secondary hover:text-primary transition"
                    >
                      Oils & Syrups
                    </Link>
                    <Link
                      href="/products?category=powders"
                      className="block px-3 py-2.5 rounded-lg hover:bg-accent text-sm text-secondary hover:text-primary transition"
                    >
                      Herbal Powders
                    </Link>
                    <Link
                      href="/products?category=skincare"
                      className="block px-3 py-2.5 rounded-lg hover:bg-accent text-sm text-secondary hover:text-primary transition"
                    >
                      Skin & Hair Care
                    </Link>
                  </div>
                </div>
              </div>
              <Link
                href="/offers"
                className="text-xs font-medium uppercase tracking-widest text-primary hover:text-primary-dark transition"
              >
                Offers
              </Link>
            </nav>

            {/* Logo - center */}
            <Link href="/" className="flex flex-col items-center">
              <h1 className="text-2xl md:text-3xl font-serif tracking-wide text-secondary">
                SHIVARA
              </h1>
              <p className="text-[9px] uppercase tracking-[0.3em] text-muted mt-0.5">
                {SITE_CONFIG.tagline}
              </p>
            </Link>

            {/* Actions - right */}
            <div className="flex items-center gap-4">
              {/* Search */}
              <button
                onClick={() => setSearchOpen(!searchOpen)}
                className="text-secondary hover:text-primary transition"
              >
                <Search size={20} strokeWidth={1.5} />
              </button>

              {/* Account */}
              <Link
                href="/auth/login"
                className="hidden sm:block text-secondary hover:text-primary transition"
              >
                <User size={20} strokeWidth={1.5} />
              </Link>

              {/* Wishlist */}
              <Link
                href="/wishlist"
                className="relative text-secondary hover:text-primary transition"
              >
                <Heart size={20} strokeWidth={1.5} />
                {wishlistCount > 0 && (
                  <span className="absolute -top-1.5 -right-1.5 bg-primary text-white text-[9px] font-medium w-4 h-4 rounded-full flex items-center justify-center">
                    {wishlistCount}
                  </span>
                )}
              </Link>

              {/* Cart */}
              <Link
                href="/cart"
                className="relative text-secondary hover:text-primary transition"
              >
                <ShoppingCart size={20} strokeWidth={1.5} />
                {cartItemCount > 0 && (
                  <span className="absolute -top-1.5 -right-1.5 bg-primary text-white text-[9px] font-medium w-4 h-4 rounded-full flex items-center justify-center">
                    {cartItemCount}
                  </span>
                )}
              </Link>
            </div>
          </div>
        </div>

        {/* Bottom nav links (desktop) */}
        <div className="hidden lg:block border-t border-border/50">
          <div className="max-w-7xl mx-auto px-4">
            <ul className="flex items-center justify-center gap-10 py-2.5">
              <li>
                <Link
                  href="/blog"
                  className="text-xs font-medium uppercase tracking-widest text-muted hover:text-secondary transition"
                >
                  Wellness Journal
                </Link>
              </li>
              <li>
                <Link
                  href="/contact"
                  className="text-xs font-medium uppercase tracking-widest text-muted hover:text-secondary transition"
                >
                  Contact
                </Link>
              </li>
              <li>
                <Link
                  href="/account/orders"
                  className="text-xs font-medium uppercase tracking-widest text-muted hover:text-secondary transition"
                >
                  Track Order
                </Link>
              </li>
            </ul>
          </div>
        </div>
      </div>

      {/* Mobile search */}
      {searchOpen && (
        <div className="bg-white border-b border-border p-4 animate-fade-in">
          <div className="relative max-w-xl mx-auto">
            <input
              type="text"
              placeholder="Search for products..."
              className="w-full px-5 py-3 rounded-full border border-border bg-accent/50 text-sm focus:outline-none focus:ring-1 focus:ring-primary/30 focus:border-primary/50 placeholder:text-muted"
              autoFocus
            />
            <button className="absolute right-3 top-1/2 -translate-y-1/2 text-primary">
              <Search size={18} />
            </button>
          </div>
        </div>
      )}

      {/* Mobile menu */}
      {mobileMenuOpen && (
        <div className="lg:hidden fixed inset-0 top-[105px] bg-white z-50 overflow-y-auto animate-fade-in">
          <nav className="p-8 space-y-1">
            <Link
              href="/"
              className="block text-sm uppercase tracking-widest text-secondary py-4 border-b border-border"
              onClick={() => setMobileMenuOpen(false)}
            >
              Home
            </Link>
            <Link
              href="/products"
              className="block text-sm uppercase tracking-widest text-secondary py-4 border-b border-border"
              onClick={() => setMobileMenuOpen(false)}
            >
              All Products
            </Link>
            <Link
              href="/offers"
              className="block text-sm uppercase tracking-widest text-primary py-4 border-b border-border"
              onClick={() => setMobileMenuOpen(false)}
            >
              Offers
            </Link>
            <Link
              href="/blog"
              className="block text-sm uppercase tracking-widest text-secondary py-4 border-b border-border"
              onClick={() => setMobileMenuOpen(false)}
            >
              Wellness Journal
            </Link>
            <Link
              href="/contact"
              className="block text-sm uppercase tracking-widest text-secondary py-4 border-b border-border"
              onClick={() => setMobileMenuOpen(false)}
            >
              Contact
            </Link>
            <Link
              href="/account"
              className="block text-sm uppercase tracking-widest text-secondary py-4 border-b border-border"
              onClick={() => setMobileMenuOpen(false)}
            >
              My Account
            </Link>
            <Link
              href="/account/orders"
              className="block text-sm uppercase tracking-widest text-secondary py-4 border-b border-border"
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
