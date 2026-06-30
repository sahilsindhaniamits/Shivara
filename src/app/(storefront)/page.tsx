import React from "react";
import Link from "next/link";
import { ArrowRight, Leaf, Award, Shield, Sparkles } from "lucide-react";
import Button from "@/components/ui/Button";
import ProductCard from "@/components/storefront/ProductCard";

// Demo products
const FEATURED_PRODUCTS = [
  {
    id: "1",
    name: "Madhu Balance Capsules",
    slug: "madhu-balance-capsules",
    image: "",
    mrp: 2799,
    sellingPrice: 1249,
    rating: 4.5,
    reviewCount: 128,
    stock: 50,
    isFeatured: true,
    category: "Sugar",
  },
  {
    id: "2",
    name: "BPM Capsules",
    slug: "bpm-capsules",
    image: "",
    mrp: 1499,
    sellingPrice: 899,
    rating: 4.7,
    reviewCount: 245,
    stock: 35,
    isFeatured: true,
    category: "BP",
  },
  {
    id: "3",
    name: "G-Liv Care DS Capsule",
    slug: "g-liv-care-ds-capsule",
    image: "",
    mrp: 599,
    sellingPrice: 449,
    rating: 4.3,
    reviewCount: 89,
    stock: 100,
    isFeatured: false,
    category: "Liver Support",
  },
  {
    id: "4",
    name: "Sandhimukta Capsules",
    slug: "sandhimukta-capsules",
    image: "",
    mrp: 1999,
    sellingPrice: 1299,
    rating: 4.6,
    reviewCount: 67,
    stock: 25,
    isFeatured: true,
    category: "Joint Care",
  },
];

const CATEGORIES = [
  { name: "Capsules & Tablets", icon: "💊", slug: "capsules", count: 12 },
  { name: "Herbal Powders", icon: "🌿", slug: "powders", count: 8 },
  { name: "Oils & Syrups", icon: "🍯", slug: "oils", count: 6 },
  { name: "Skin & Hair Care", icon: "✨", slug: "skincare", count: 10 },
  { name: "Immunity Boosters", icon: "🛡️", slug: "immunity", count: 5 },
  { name: "Digestive Health", icon: "🫁", slug: "digestive", count: 7 },
];

const TESTIMONIALS = [
  {
    name: "Priya Sharma",
    location: "Delhi",
    text: "Madhu Balance capsules have significantly improved my metabolism. I feel healthier and more energetic after 2 months of use.",
  },
  {
    name: "Rajesh Kumar",
    location: "Mumbai",
    text: "Quality products with genuine Ayurvedic ingredients. The packaging and delivery were excellent. Highly recommended!",
  },
  {
    name: "Anita Verma",
    location: "Jaipur",
    text: "Great results with G-Liv Care. Natural and effective. Will definitely order again for the whole family.",
  },
];

export default function HomePage() {
  return (
    <div>
      {/* Hero Section - Minimal luxury */}
      <section className="relative bg-accent">
        <div className="max-w-7xl mx-auto px-4 py-20 md:py-32">
          <div className="max-w-3xl mx-auto text-center animate-fade-in-up">
            <p className="section-label mb-6">Heritage Ayurveda</p>
            <h1 className="heading-editorial text-4xl md:text-6xl lg:text-7xl text-secondary mb-6">
              Ancient wisdom.
              <br />
              Modern purity.
            </h1>
            <p className="text-muted text-base md:text-lg max-w-xl mx-auto mb-10 leading-relaxed">
              Formulations rooted in 5,000 years of Ayurvedic tradition,
              crafted with single-origin herbs from the farms of Rajasthan.
            </p>
            <div className="flex flex-wrap justify-center gap-4">
              <Link href="/products">
                <Button variant="dark" size="lg">
                  Explore Collection
                </Button>
              </Link>
              <Link href="/blog">
                <Button variant="outline" size="lg">
                  Our Philosophy
                </Button>
              </Link>
            </div>

            {/* Scroll indicator */}
            <p className="text-[10px] uppercase tracking-[0.3em] text-muted mt-16">
              Scroll
            </p>
          </div>
        </div>
      </section>

      {/* Trust Badges - matching reference */}
      <section className="bg-accent/60 border-y border-border">
        <div className="max-w-7xl mx-auto px-4 py-12">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div className="flex flex-col items-center text-center gap-3">
              <Leaf className="text-primary" size={24} strokeWidth={1.5} />
              <h3 className="font-serif text-sm text-secondary">Single-Origin Herbs</h3>
              <p className="text-xs text-muted">Sourced from heritage farms in Rajasthan</p>
            </div>
            <div className="flex flex-col items-center text-center gap-3">
              <Shield className="text-primary" size={24} strokeWidth={1.5} />
              <h3 className="font-serif text-sm text-secondary">GMP Certified</h3>
              <p className="text-xs text-muted">Crafted in audited facilities</p>
            </div>
            <div className="flex flex-col items-center text-center gap-3">
              <Sparkles className="text-primary" size={24} strokeWidth={1.5} />
              <h3 className="font-serif text-sm text-secondary">Free Shipping</h3>
              <p className="text-xs text-muted">On all orders within India</p>
            </div>
            <div className="flex flex-col items-center text-center gap-3">
              <Award className="text-primary" size={24} strokeWidth={1.5} />
              <h3 className="font-serif text-sm text-secondary">Lab Tested</h3>
              <p className="text-xs text-muted">Purity verified in certified labs</p>
            </div>
          </div>
        </div>
      </section>

      {/* Featured Products - Editorial style */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4">
          <div className="text-center mb-14">
            <p className="section-label mb-4">Editor&apos;s Selection</p>
            <h2 className="heading-editorial text-3xl md:text-5xl text-secondary">
              Amazing deals.
            </h2>
            <p className="text-muted text-sm mt-4 max-w-md mx-auto">
              Our most-loved formulations, currently offered at heritage prices.
            </p>
          </div>

          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
            {FEATURED_PRODUCTS.map((product) => (
              <ProductCard key={product.id} {...product} />
            ))}
          </div>

          <div className="mt-12 text-center">
            <Link href="/products">
              <Button variant="outline" size="lg">
                View All Products <ArrowRight size={14} className="ml-2" />
              </Button>
            </Link>
          </div>
        </div>
      </section>

      {/* Categories */}
      <section className="py-20 bg-accent/40">
        <div className="max-w-7xl mx-auto px-4">
          <div className="text-center mb-14">
            <p className="section-label mb-4">Shop by Concern</p>
            <h2 className="heading-editorial text-3xl md:text-5xl text-secondary">
              Find your balance.
            </h2>
          </div>

          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            {CATEGORIES.map((cat) => (
              <Link
                key={cat.slug}
                href={`/products?category=${cat.slug}`}
                className="group text-center p-6 bg-white border border-border hover:border-primary/30 transition-all duration-300"
              >
                <div className="text-3xl mb-3 group-hover:scale-110 transition-transform duration-300">
                  {cat.icon}
                </div>
                <h3 className="text-xs font-medium uppercase tracking-wider text-secondary group-hover:text-primary transition">
                  {cat.name}
                </h3>
                <p className="text-[10px] text-muted mt-1">
                  {cat.count} products
                </p>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* Story / About */}
      <section className="py-20 bg-white">
        <div className="max-w-4xl mx-auto px-4 text-center">
          <p className="section-label mb-4">Our Story</p>
          <h2 className="heading-editorial text-3xl md:text-5xl text-secondary mb-8">
            Purity you can trust.
          </h2>
          <p className="text-muted leading-relaxed max-w-2xl mx-auto mb-6">
            At Shivara, we believe that wellness begins with what nature provides.
            Every product is a testament to our commitment — sourcing the finest
            single-origin herbs from Rajasthan, processing them in GMP-certified
            facilities, and delivering them with the integrity your health deserves.
          </p>
          <p className="text-muted leading-relaxed max-w-2xl mx-auto">
            No shortcuts. No compromises. Just Ayurveda in its purest form.
          </p>
          <div className="luxury-divider mt-10 mb-10" />
          <div className="flex justify-center gap-12">
            <div className="text-center">
              <p className="text-3xl font-serif text-secondary">5,000+</p>
              <p className="text-[10px] uppercase tracking-widest text-muted mt-1">Happy Customers</p>
            </div>
            <div className="text-center">
              <p className="text-3xl font-serif text-secondary">50+</p>
              <p className="text-[10px] uppercase tracking-widest text-muted mt-1">Formulations</p>
            </div>
            <div className="text-center">
              <p className="text-3xl font-serif text-secondary">4.8</p>
              <p className="text-[10px] uppercase tracking-widest text-muted mt-1">Average Rating</p>
            </div>
          </div>
        </div>
      </section>

      {/* Testimonials */}
      <section className="py-20 bg-accent">
        <div className="max-w-7xl mx-auto px-4">
          <div className="text-center mb-14">
            <p className="section-label mb-4">Testimonials</p>
            <h2 className="heading-editorial text-3xl md:text-5xl text-secondary">
              What they say.
            </h2>
          </div>

          <div className="grid md:grid-cols-3 gap-6">
            {TESTIMONIALS.map((t, i) => (
              <div
                key={i}
                className="bg-white p-8 border border-border"
              >
                <p className="text-secondary/80 text-sm leading-relaxed italic mb-6">
                  &ldquo;{t.text}&rdquo;
                </p>
                <div className="flex items-center gap-3">
                  <div className="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center">
                    <span className="text-xs font-medium text-primary">
                      {t.name.split(" ").map((n) => n[0]).join("")}
                    </span>
                  </div>
                  <div>
                    <p className="text-xs font-medium text-secondary">{t.name}</p>
                    <p className="text-[10px] text-muted">{t.location}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Newsletter */}
      <section className="py-20 bg-white border-t border-border">
        <div className="max-w-xl mx-auto px-4 text-center">
          <p className="section-label mb-4">Stay Connected</p>
          <h2 className="heading-editorial text-3xl text-secondary mb-4">
            Join the community.
          </h2>
          <p className="text-muted text-sm mb-8">
            Exclusive offers, Ayurvedic wisdom, and new product launches
            delivered to your inbox.
          </p>
          <form className="flex flex-col sm:flex-row gap-3">
            <input
              type="email"
              placeholder="Your email address"
              className="flex-1 px-5 py-3 border border-border bg-white text-sm focus:outline-none focus:border-primary/50 placeholder:text-muted"
            />
            <Button variant="dark">Subscribe</Button>
          </form>
          <p className="text-[10px] text-muted mt-3 uppercase tracking-wider">
            No spam. Unsubscribe anytime.
          </p>
        </div>
      </section>
    </div>
  );
}
