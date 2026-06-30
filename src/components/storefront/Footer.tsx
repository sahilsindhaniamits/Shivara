import React from "react";
import Link from "next/link";
import {
  Mail,
  Phone,
  MapPin,
  Leaf,
  ShieldCheck,
  Truck,
  RefreshCw,
} from "lucide-react";
import { SITE_CONFIG } from "@/lib/constants";

export default function Footer() {
  return (
    <footer className="bg-secondary text-white">
      {/* Trust badges */}
      <div className="border-b border-white/10">
        <div className="max-w-7xl mx-auto px-4 py-10">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div className="flex flex-col items-center text-center gap-2">
              <Leaf className="text-primary" size={22} strokeWidth={1.5} />
              <h4 className="text-xs font-medium uppercase tracking-widest">Single-Origin Herbs</h4>
              <p className="text-[11px] text-white/50">Sourced from heritage farms in Rajasthan</p>
            </div>
            <div className="flex flex-col items-center text-center gap-2">
              <ShieldCheck className="text-primary" size={22} strokeWidth={1.5} />
              <h4 className="text-xs font-medium uppercase tracking-widest">GMP Certified</h4>
              <p className="text-[11px] text-white/50">Crafted in audited facilities</p>
            </div>
            <div className="flex flex-col items-center text-center gap-2">
              <Truck className="text-primary" size={22} strokeWidth={1.5} />
              <h4 className="text-xs font-medium uppercase tracking-widest">Free Shipping</h4>
              <p className="text-[11px] text-white/50">On all orders within India</p>
            </div>
            <div className="flex flex-col items-center text-center gap-2">
              <RefreshCw className="text-primary" size={22} strokeWidth={1.5} />
              <h4 className="text-xs font-medium uppercase tracking-widest">Easy Returns</h4>
              <p className="text-[11px] text-white/50">7-day hassle-free returns</p>
            </div>
          </div>
        </div>
      </div>

      {/* Main footer */}
      <div className="max-w-7xl mx-auto px-4 py-14">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
          {/* Brand */}
          <div className="space-y-5">
            <div>
              <h3 className="text-xl font-serif tracking-wide">SHIVARA</h3>
              <p className="text-[10px] uppercase tracking-[0.25em] text-primary mt-1">
                Ayurvedic Purity, Elevated
              </p>
            </div>
            <p className="text-white/60 text-sm leading-relaxed">
              {SITE_CONFIG.description}
            </p>
            <div className="flex gap-4">
              <a
                href={SITE_CONFIG.social.instagram}
                target="_blank"
                rel="noopener noreferrer"
                className="w-9 h-9 border border-white/20 rounded-full flex items-center justify-center hover:border-primary hover:text-primary transition"
              >
                <svg viewBox="0 0 24 24" fill="currentColor" className="w-4 h-4"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
              </a>
              <a
                href={SITE_CONFIG.social.facebook}
                target="_blank"
                rel="noopener noreferrer"
                className="w-9 h-9 border border-white/20 rounded-full flex items-center justify-center hover:border-primary hover:text-primary transition"
              >
                <svg viewBox="0 0 24 24" fill="currentColor" className="w-4 h-4"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
              </a>
              <a
                href={SITE_CONFIG.social.youtube}
                target="_blank"
                rel="noopener noreferrer"
                className="w-9 h-9 border border-white/20 rounded-full flex items-center justify-center hover:border-primary hover:text-primary transition"
              >
                <svg viewBox="0 0 24 24" fill="currentColor" className="w-4 h-4"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
              </a>
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="text-xs font-medium uppercase tracking-widest text-primary mb-5">Quick Links</h4>
            <ul className="space-y-3">
              <li>
                <Link href="/products" className="text-sm text-white/60 hover:text-white transition">
                  All Products
                </Link>
              </li>
              <li>
                <Link href="/offers" className="text-sm text-white/60 hover:text-white transition">
                  Offers & Deals
                </Link>
              </li>
              <li>
                <Link href="/blog" className="text-sm text-white/60 hover:text-white transition">
                  Wellness Journal
                </Link>
              </li>
              <li>
                <Link href="/account/orders" className="text-sm text-white/60 hover:text-white transition">
                  Track Your Order
                </Link>
              </li>
              <li>
                <Link href="/account" className="text-sm text-white/60 hover:text-white transition">
                  My Account
                </Link>
              </li>
            </ul>
          </div>

          {/* Policies */}
          <div>
            <h4 className="text-xs font-medium uppercase tracking-widest text-primary mb-5">Policies</h4>
            <ul className="space-y-3">
              <li>
                <Link href="/policies/shipping" className="text-sm text-white/60 hover:text-white transition">
                  Shipping Policy
                </Link>
              </li>
              <li>
                <Link href="/policies/returns" className="text-sm text-white/60 hover:text-white transition">
                  Return & Refund
                </Link>
              </li>
              <li>
                <Link href="/policies/privacy" className="text-sm text-white/60 hover:text-white transition">
                  Privacy Policy
                </Link>
              </li>
              <li>
                <Link href="/policies/terms" className="text-sm text-white/60 hover:text-white transition">
                  Terms & Conditions
                </Link>
              </li>
              <li>
                <Link href="/policies/cancellation" className="text-sm text-white/60 hover:text-white transition">
                  Cancellation Policy
                </Link>
              </li>
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h4 className="text-xs font-medium uppercase tracking-widest text-primary mb-5">Contact Us</h4>
            <ul className="space-y-4">
              <li className="flex items-start gap-3">
                <MapPin size={16} className="text-primary shrink-0 mt-0.5" strokeWidth={1.5} />
                <span className="text-sm text-white/60">
                  {SITE_CONFIG.address.line1}, {SITE_CONFIG.address.line2},{" "}
                  {SITE_CONFIG.address.city}, {SITE_CONFIG.address.state} -{" "}
                  {SITE_CONFIG.address.pincode}
                </span>
              </li>
              <li className="flex items-center gap-3">
                <Phone size={16} className="text-primary shrink-0" strokeWidth={1.5} />
                <a
                  href={`tel:${SITE_CONFIG.phone}`}
                  className="text-sm text-white/60 hover:text-white transition"
                >
                  {SITE_CONFIG.phone}
                </a>
              </li>
              <li className="flex items-center gap-3">
                <Mail size={16} className="text-primary shrink-0" strokeWidth={1.5} />
                <a
                  href={`mailto:${SITE_CONFIG.email}`}
                  className="text-sm text-white/60 hover:text-white transition"
                >
                  {SITE_CONFIG.email}
                </a>
              </li>
            </ul>
            {/* WhatsApp */}
            <a
              href={`https://wa.me/${SITE_CONFIG.whatsapp}?text=Hi, I have a question about Shivara products`}
              target="_blank"
              rel="noopener noreferrer"
              className="mt-5 inline-flex items-center gap-2 border border-white/20 text-white px-4 py-2.5 rounded-full text-xs font-medium uppercase tracking-wider hover:border-primary hover:text-primary transition"
            >
              <svg viewBox="0 0 24 24" fill="currentColor" className="w-4 h-4">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
              </svg>
              Chat on WhatsApp
            </a>
          </div>
        </div>
      </div>

      {/* Bottom bar */}
      <div className="border-t border-white/10">
        <div className="max-w-7xl mx-auto px-4 py-5 flex flex-col md:flex-row items-center justify-between gap-4">
          <p className="text-xs text-white/40">
            © {new Date().getFullYear()} Shivara. All rights reserved.
          </p>
          <div className="flex items-center gap-2 text-xs text-white/40">
            <span>UPI</span>
            <span className="text-white/20">|</span>
            <span>Cards</span>
            <span className="text-white/20">|</span>
            <span>Net Banking</span>
            <span className="text-white/20">|</span>
            <span>COD</span>
          </div>
        </div>
      </div>
    </footer>
  );
}
