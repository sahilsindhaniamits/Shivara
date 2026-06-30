import React from "react";
import Link from "next/link";
import {
  ArrowRight,
  Star,
  Leaf,
  Award,
  Shield,
  Sparkles,
  TrendingUp,
  Clock,
} from "lucide-react";
import Button from "@/components/ui/Button";
import ProductCard from "@/components/storefront/ProductCard";

// Demo products - will be replaced with database
const FEATURED_PRODUCTS = [
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
    rating: 5,
    text: "Madhu Balance capsules have significantly improved my metabolism. I feel healthier and more energetic after 2 months of use.",
    product: "Madhu Balance Capsules",
  },
  {
    name: "Rajesh Kumar",
    location: "Mumbai",
    rating: 5,
    text: "Quality products with genuine Ayurvedic ingredients. The packaging and delivery were excellent. Highly recommended!",
    product: "Ashwagandha Gold",
  },
  {
    name: "Anita Verma",
    location: "Jaipur",
    rating: 4,
    text: "Great results with Triphala Churna. Natural and effective. Will definitely order again.",
    product: "Triphala Churna",
  },
];

export default function HomePage() {
  return (
    <div>
      {/* Announcement bar */}
      <div className="bg-secondary/10 border-b border-secondary/20 py-2 text-center">
        <p className="text-sm text-primary font-medium">
          🎉 FLAT 10% OFF on first order | Use Code:{" "}
          <span className="font-bold text-cta">SHIVARA10</span> |{" "}
          <Link href="/offers" className="underline hover:text-cta transition">
            View All Offers
          </Link>
        </p>
      </div>

      {/* Hero Section */}
      <section className="relative overflow-hidden bg-gradient-to-br from-accent via-white to-primary-50">
        <div className="ayurveda-pattern absolute inset-0 opacity-50" />
        <div className="max-w-7xl mx-auto px-4 py-16 md:py-24 relative">
          <div className="grid md:grid-cols-2 gap-12 items-center">
            <div className="space-y-6 animate-fade-in">
              <div className="inline-flex items-center gap-2 bg-white/80 backdrop-blur px-4 py-2 rounded-full border border-primary/10">
                <Leaf size={16} className="text-primary" />
                <span className="text-sm font-medium text-primary">
                  AYUSH Certified | 100% Natural
                </span>
              </div>

              <h1 className="text-4xl md:text-6xl font-bold text-primary-dark leading-tight">
                Pure Ayurvedic
                <br />
                <span className="text-gradient">Wellness</span>
                <br />
                For Your Life
              </h1>

              <p className="text-lg text-gray-600 max-w-lg">
                Discover the ancient wisdom of Ayurveda with Shivara&apos;s
                premium herbal products. Crafted with care to support your
                health naturally.
              </p>

              <div className="flex flex-wrap gap-4">
                <Link href="/products">
                  <Button variant="cta" size="lg">
                    Shop Now <ArrowRight size={20} className="ml-2" />
                  </Button>
                </Link>
                <Link href="/blog">
                  <Button variant="outline" size="lg">
                    Learn Ayurveda
                  </Button>
                </Link>
              </div>

              {/* Stats */}
              <div className="flex gap-8 pt-4">
                <div>
                  <p className="text-2xl font-bold text-primary">10K+</p>
                  <p className="text-xs text-gray-500">Happy Customers</p>
                </div>
                <div>
                  <p className="text-2xl font-bold text-primary">50+</p>
                  <p className="text-xs text-gray-500">Products</p>
                </div>
                <div>
                  <p className="text-2xl font-bold text-primary">4.8⭐</p>
                  <p className="text-xs text-gray-500">Average Rating</p>
                </div>
              </div>
            </div>

            {/* Hero Image placeholder */}
            <div className="relative">
              <div className="relative aspect-square max-w-md mx-auto">
                <div className="absolute inset-0 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full animate-pulse-glow" />
                <div className="absolute inset-8 bg-gradient-to-br from-accent to-white rounded-full flex items-center justify-center shadow-xl">
                  <div className="text-center">
                    <span className="text-8xl">🌿</span>
                    <p className="text-primary font-semibold mt-4">
                      Ancient Wisdom
                    </p>
                    <p className="text-sm text-gray-500">Modern Wellness</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Categories */}
      <section className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-primary-dark">
              Shop by Category
            </h2>
            <p className="text-gray-500 mt-2">
              Explore our range of pure Ayurvedic products
            </p>
          </div>

          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            {CATEGORIES.map((cat) => (
              <Link
                key={cat.slug}
                href={`/products?category=${cat.slug}`}
                className="group text-center p-6 rounded-2xl border border-border hover:border-primary/30 hover:shadow-medium transition-all duration-300 bg-white"
              >
                <div className="text-4xl mb-3 group-hover:scale-110 transition-transform">
                  {cat.icon}
                </div>
                <h3 className="text-sm font-semibold text-gray-800 group-hover:text-primary transition">
                  {cat.name}
                </h3>
                <p className="text-xs text-gray-400 mt-1">
                  {cat.count} products
                </p>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* Featured Products */}
      <section className="py-16 bg-accent/30 ayurveda-pattern">
        <div className="max-w-7xl mx-auto px-4">
          <div className="flex items-center justify-between mb-10">
            <div>
              <h2 className="text-3xl font-bold text-primary-dark">
                Bestselling Products
              </h2>
              <p className="text-gray-500 mt-1">
                Most loved by our customers
              </p>
            </div>
            <Link
              href="/products"
              className="hidden md:flex items-center gap-1 text-primary font-medium hover:text-primary-light transition"
            >
              View All <ArrowRight size={18} />
            </Link>
          </div>

          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            {FEATURED_PRODUCTS.map((product) => (
              <ProductCard key={product.id} {...product} />
            ))}
          </div>

          <div className="mt-8 text-center md:hidden">
            <Link href="/products">
              <Button variant="outline">
                View All Products <ArrowRight size={16} className="ml-1" />
              </Button>
            </Link>
          </div>
        </div>
      </section>

      {/* Why Shivara */}
      <section className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-primary-dark">
              Why Choose Shivara?
            </h2>
            <p className="text-gray-500 mt-2">
              We are committed to purity, quality, and your wellness
            </p>
          </div>

          <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div className="p-6 rounded-2xl bg-accent/50 border border-primary/10 text-center group hover:shadow-medium transition">
              <div className="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary/20 transition">
                <Leaf className="text-primary" size={28} />
              </div>
              <h3 className="font-semibold text-gray-800 mb-2">
                100% Natural
              </h3>
              <p className="text-sm text-gray-500">
                Pure Ayurvedic herbs sourced directly from organic farms across
                India
              </p>
            </div>
            <div className="p-6 rounded-2xl bg-accent/50 border border-primary/10 text-center group hover:shadow-medium transition">
              <div className="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary/20 transition">
                <Award className="text-primary" size={28} />
              </div>
              <h3 className="font-semibold text-gray-800 mb-2">
                GMP Certified
              </h3>
              <p className="text-sm text-gray-500">
                Manufactured in AYUSH-approved, WHO-GMP certified facility
              </p>
            </div>
            <div className="p-6 rounded-2xl bg-accent/50 border border-primary/10 text-center group hover:shadow-medium transition">
              <div className="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary/20 transition">
                <Shield className="text-primary" size={28} />
              </div>
              <h3 className="font-semibold text-gray-800 mb-2">
                Quality Tested
              </h3>
              <p className="text-sm text-gray-500">
                Every batch tested for purity, potency, and safety in certified
                labs
              </p>
            </div>
            <div className="p-6 rounded-2xl bg-accent/50 border border-primary/10 text-center group hover:shadow-medium transition">
              <div className="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary/20 transition">
                <Sparkles className="text-primary" size={28} />
              </div>
              <h3 className="font-semibold text-gray-800 mb-2">
                Proven Results
              </h3>
              <p className="text-sm text-gray-500">
                Thousands of satisfied customers with real health improvements
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Testimonials */}
      <section className="py-16 bg-primary-dark text-white">
        <div className="max-w-7xl mx-auto px-4">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold">What Our Customers Say</h2>
            <p className="text-gray-300 mt-2">
              Real reviews from real people
            </p>
          </div>

          <div className="grid md:grid-cols-3 gap-6">
            {TESTIMONIALS.map((t, i) => (
              <div
                key={i}
                className="bg-white/10 backdrop-blur p-6 rounded-2xl border border-white/10"
              >
                <div className="flex gap-1 mb-3">
                  {Array.from({ length: t.rating }).map((_, j) => (
                    <Star
                      key={j}
                      size={16}
                      className="fill-secondary text-secondary"
                    />
                  ))}
                </div>
                <p className="text-gray-200 text-sm mb-4 italic">
                  &ldquo;{t.text}&rdquo;
                </p>
                <div className="flex items-center justify-between">
                  <div>
                    <p className="font-semibold text-white">{t.name}</p>
                    <p className="text-xs text-gray-400">{t.location}</p>
                  </div>
                  <span className="text-xs bg-secondary/20 text-secondary px-2 py-1 rounded-full">
                    {t.product}
                  </span>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Newsletter */}
      <section className="py-16 bg-accent">
        <div className="max-w-3xl mx-auto px-4 text-center">
          <h2 className="text-3xl font-bold text-primary-dark mb-4">
            Join the Wellness Community
          </h2>
          <p className="text-gray-600 mb-8">
            Get exclusive offers, Ayurvedic tips, and new product updates
            delivered to your inbox.
          </p>
          <form className="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
            <input
              type="email"
              placeholder="Enter your email"
              className="flex-1 px-5 py-3 rounded-full border border-border bg-white focus:outline-none focus:ring-2 focus:ring-primary/20"
            />
            <Button variant="cta">Subscribe</Button>
          </form>
          <p className="text-xs text-gray-400 mt-3">
            No spam. Unsubscribe anytime.
          </p>
        </div>
      </section>
    </div>
  );
}
