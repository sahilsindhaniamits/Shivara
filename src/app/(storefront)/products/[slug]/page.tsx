"use client";

import React, { useState } from "react";
import Image from "next/image";
import Link from "next/link";
import {
  Star,
  Heart,
  ShoppingCart,
  Minus,
  Plus,
  Truck,
  Shield,
  RefreshCw,
  ChevronRight,
  Check,
} from "lucide-react";
import Button from "@/components/ui/Button";
import Badge from "@/components/ui/Badge";
import { formatPrice, calculateDiscount } from "@/lib/utils";
import { useCartStore } from "@/store/cart-store";
import { useWishlistStore } from "@/store/wishlist-store";

// Demo product detail - will be replaced with API
const PRODUCT = {
  id: "1",
  name: "Madhu Balance Capsules - For Maintaining Healthy Metabolism",
  slug: "madhu-balance-capsules",
  description:
    "Madhu Balance Capsules are an Ayurvedic formulation designed to support healthy blood sugar levels and improve metabolic function. Made from a blend of traditional herbs known for their efficacy in maintaining glucose balance.",
  shortDescription:
    "Supports healthy metabolism and blood sugar management naturally.",
  ingredients:
    "Gudmar (Gymnema Sylvestre), Jamun Beej (Syzygium Cumini), Karela (Momordica Charantia), Methi (Trigonella Foenum-graecum), Vijaysar (Pterocarpus Marsupium), Neem (Azadirachta Indica)",
  howToUse:
    "Take 2 capsules twice daily after meals with lukewarm water. For best results, use consistently for 3-6 months.",
  benefits:
    "• Helps maintain healthy blood sugar levels\n• Supports pancreatic function\n• Boosts natural metabolism\n• Rich in antioxidants\n• Improves energy levels\n• 100% Natural & Safe",
  mrp: 2799,
  sellingPrice: 1249,
  sku: "SHV-MBC-001",
  hsnCode: "3004",
  gstRate: 18,
  weight: 150,
  stock: 50,
  rating: 4.5,
  reviewCount: 128,
  isFeatured: true,
  images: [
    { id: "1", url: "", alt: "Madhu Balance Front", isPrimary: true },
    { id: "2", url: "", alt: "Madhu Balance Back", isPrimary: false },
    { id: "3", url: "", alt: "Madhu Balance Ingredients", isPrimary: false },
  ],
  variants: [
    { id: "v1", name: "60 Capsules (1 Month)", mrp: 2799, sellingPrice: 1249, stock: 50 },
    { id: "v2", name: "120 Capsules (2 Months)", mrp: 4999, sellingPrice: 2199, stock: 30 },
    { id: "v3", name: "180 Capsules (3 Months)", mrp: 7499, sellingPrice: 2999, stock: 20 },
  ],
  category: { name: "Capsules & Tablets", slug: "capsules" },
};

export default function ProductDetailPage() {
  const [selectedVariant, setSelectedVariant] = useState(PRODUCT.variants[0]);
  const [quantity, setQuantity] = useState(1);
  const [selectedImage, setSelectedImage] = useState(0);
  const [activeTab, setActiveTab] = useState<"description" | "ingredients" | "howToUse" | "reviews">("description");
  const [pincode, setPincode] = useState("");

  const addToCart = useCartStore((s) => s.addItem);
  const { addItem: addToWishlist, removeItem: removeFromWishlist, isInWishlist } =
    useWishlistStore();

  const discount = calculateDiscount(selectedVariant.mrp, selectedVariant.sellingPrice);
  const inWishlist = isInWishlist(PRODUCT.id);

  const handleAddToCart = () => {
    addToCart({
      id: `${PRODUCT.id}-${selectedVariant.id}`,
      productId: PRODUCT.id,
      variantId: selectedVariant.id,
      name: PRODUCT.name,
      variantName: selectedVariant.name,
      image: PRODUCT.images[0]?.url || "",
      price: selectedVariant.sellingPrice,
      mrp: selectedVariant.mrp,
      quantity,
      stock: selectedVariant.stock,
    });
  };

  return (
    <div className="max-w-7xl mx-auto px-4 py-8">
      {/* Breadcrumb */}
      <nav className="text-sm text-gray-500 mb-6 flex items-center gap-1">
        <Link href="/" className="hover:text-primary">Home</Link>
        <ChevronRight size={14} />
        <Link href="/products" className="hover:text-primary">Products</Link>
        <ChevronRight size={14} />
        <span className="text-primary font-medium">{PRODUCT.name}</span>
      </nav>

      <div className="grid lg:grid-cols-2 gap-8 lg:gap-12">
        {/* Images */}
        <div className="space-y-4">
          <div className="aspect-square bg-accent/50 rounded-2xl overflow-hidden relative border border-border">
            {PRODUCT.images[selectedImage]?.url ? (
              <Image
                src={PRODUCT.images[selectedImage].url}
                alt={PRODUCT.images[selectedImage].alt || PRODUCT.name}
                fill
                className="object-cover"
              />
            ) : (
              <div className="w-full h-full flex items-center justify-center">
                <span className="text-[120px]">🌿</span>
              </div>
            )}
            {discount > 0 && (
              <div className="absolute top-4 left-4">
                <Badge variant="error" size="md">
                  {discount}% OFF
                </Badge>
              </div>
            )}
          </div>

          {/* Thumbnail gallery */}
          <div className="flex gap-3">
            {PRODUCT.images.map((img, i) => (
              <button
                key={img.id}
                onClick={() => setSelectedImage(i)}
                className={`w-20 h-20 rounded-xl overflow-hidden border-2 transition ${
                  selectedImage === i
                    ? "border-primary shadow-md"
                    : "border-border hover:border-primary/50"
                }`}
              >
                {img.url ? (
                  <Image src={img.url} alt={img.alt || ""} width={80} height={80} className="object-cover" />
                ) : (
                  <div className="w-full h-full bg-accent/50 flex items-center justify-center">
                    <span className="text-2xl">🌿</span>
                  </div>
                )}
              </button>
            ))}
          </div>
        </div>

        {/* Product Info */}
        <div className="space-y-6">
          <div>
            <p className="text-sm text-secondary font-medium mb-1">
              {PRODUCT.category.name}
            </p>
            <h1 className="text-2xl md:text-3xl font-bold text-gray-900">
              {PRODUCT.name}
            </h1>
            <p className="text-gray-500 mt-2">{PRODUCT.shortDescription}</p>
          </div>

          {/* Rating */}
          <div className="flex items-center gap-3">
            <div className="flex items-center gap-1 bg-green-50 px-3 py-1 rounded-full">
              <Star size={16} className="fill-green-600 text-green-600" />
              <span className="font-semibold text-green-700">{PRODUCT.rating}</span>
            </div>
            <span className="text-sm text-gray-500">
              {PRODUCT.reviewCount} Reviews
            </span>
            <span className="text-sm text-gray-400">|</span>
            <span className="text-sm text-green-600 font-medium">In Stock</span>
          </div>

          {/* Price */}
          <div className="bg-accent/50 p-4 rounded-xl border border-primary/10">
            <div className="flex items-baseline gap-3">
              <span className="text-3xl font-bold text-primary">
                {formatPrice(selectedVariant.sellingPrice)}
              </span>
              <span className="text-lg text-gray-400 line-through">
                {formatPrice(selectedVariant.mrp)}
              </span>
              <Badge variant="success" size="md">
                Save {formatPrice(selectedVariant.mrp - selectedVariant.sellingPrice)}
              </Badge>
            </div>
            <p className="text-xs text-gray-500 mt-1">
              Inclusive of all taxes | Free shipping on orders above ₹999
            </p>
          </div>

          {/* Variants */}
          <div>
            <h3 className="text-sm font-semibold text-gray-700 mb-3">
              Select Pack Size:
            </h3>
            <div className="flex flex-wrap gap-3">
              {PRODUCT.variants.map((v) => (
                <button
                  key={v.id}
                  onClick={() => setSelectedVariant(v)}
                  className={`px-4 py-3 rounded-xl border-2 text-sm font-medium transition ${
                    selectedVariant.id === v.id
                      ? "border-primary bg-primary/5 text-primary"
                      : "border-border text-gray-600 hover:border-primary/50"
                  }`}
                >
                  <span className="block">{v.name}</span>
                  <span className="text-xs text-gray-400 mt-0.5">
                    {formatPrice(v.sellingPrice)}
                  </span>
                </button>
              ))}
            </div>
          </div>

          {/* Quantity + Add to Cart */}
          <div className="flex flex-col sm:flex-row gap-4">
            <div className="flex items-center border border-border rounded-xl">
              <button
                onClick={() => setQuantity(Math.max(1, quantity - 1))}
                className="px-4 py-3 text-gray-500 hover:text-primary transition"
              >
                <Minus size={18} />
              </button>
              <span className="px-4 py-3 font-semibold text-gray-800 min-w-[3rem] text-center">
                {quantity}
              </span>
              <button
                onClick={() => setQuantity(Math.min(selectedVariant.stock, quantity + 1))}
                className="px-4 py-3 text-gray-500 hover:text-primary transition"
              >
                <Plus size={18} />
              </button>
            </div>

            <Button variant="cta" size="lg" className="flex-1" onClick={handleAddToCart}>
              <ShoppingCart size={20} className="mr-2" />
              Add to Cart
            </Button>

            <button
              onClick={() =>
                inWishlist
                  ? removeFromWishlist(PRODUCT.id)
                  : addToWishlist({
                      id: `wl-${PRODUCT.id}`,
                      productId: PRODUCT.id,
                      name: PRODUCT.name,
                      image: PRODUCT.images[0]?.url || "",
                      price: selectedVariant.sellingPrice,
                      mrp: selectedVariant.mrp,
                      slug: PRODUCT.slug,
                    })
              }
              className={`p-3 border-2 rounded-xl transition ${
                inWishlist
                  ? "border-red-300 bg-red-50 text-red-500"
                  : "border-border text-gray-400 hover:border-red-300 hover:text-red-500"
              }`}
            >
              <Heart size={22} className={inWishlist ? "fill-red-500" : ""} />
            </button>
          </div>

          {/* Buy Now */}
          <Link href="/checkout">
            <Button variant="primary" size="lg" fullWidth>
              Buy Now
            </Button>
          </Link>

          {/* Delivery check */}
          <div className="bg-white p-4 rounded-xl border border-border">
            <h4 className="text-sm font-semibold text-gray-700 mb-2">
              Check Delivery
            </h4>
            <div className="flex gap-2">
              <input
                type="text"
                placeholder="Enter pincode"
                value={pincode}
                onChange={(e) => setPincode(e.target.value)}
                maxLength={6}
                className="flex-1 px-4 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"
              />
              <Button variant="outline" size="sm">
                Check
              </Button>
            </div>
          </div>

          {/* Trust Badges */}
          <div className="grid grid-cols-3 gap-4">
            <div className="text-center p-3 bg-accent/50 rounded-xl">
              <Truck size={20} className="text-primary mx-auto mb-1" />
              <p className="text-xs font-medium text-gray-700">Free Delivery</p>
            </div>
            <div className="text-center p-3 bg-accent/50 rounded-xl">
              <Shield size={20} className="text-primary mx-auto mb-1" />
              <p className="text-xs font-medium text-gray-700">Genuine Product</p>
            </div>
            <div className="text-center p-3 bg-accent/50 rounded-xl">
              <RefreshCw size={20} className="text-primary mx-auto mb-1" />
              <p className="text-xs font-medium text-gray-700">7-Day Returns</p>
            </div>
          </div>
        </div>
      </div>

      {/* Tabs */}
      <div className="mt-16">
        <div className="flex border-b border-border overflow-x-auto">
          {[
            { key: "description", label: "Description" },
            { key: "ingredients", label: "Ingredients" },
            { key: "howToUse", label: "How to Use" },
            { key: "reviews", label: `Reviews (${PRODUCT.reviewCount})` },
          ].map((tab) => (
            <button
              key={tab.key}
              onClick={() => setActiveTab(tab.key as typeof activeTab)}
              className={`px-6 py-4 text-sm font-medium whitespace-nowrap transition border-b-2 -mb-px ${
                activeTab === tab.key
                  ? "border-primary text-primary"
                  : "border-transparent text-gray-500 hover:text-gray-700"
              }`}
            >
              {tab.label}
            </button>
          ))}
        </div>

        <div className="py-8">
          {activeTab === "description" && (
            <div className="prose max-w-none">
              <p className="text-gray-600 leading-relaxed">{PRODUCT.description}</p>
              <div className="mt-6">
                <h3 className="text-lg font-semibold text-gray-800 mb-3">Key Benefits:</h3>
                <div className="space-y-2">
                  {PRODUCT.benefits.split("\n").map((benefit, i) => (
                    <div key={i} className="flex items-start gap-2">
                      <Check size={16} className="text-primary shrink-0 mt-0.5" />
                      <span className="text-gray-600">{benefit.replace("• ", "")}</span>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          )}
          {activeTab === "ingredients" && (
            <div>
              <h3 className="text-lg font-semibold text-gray-800 mb-3">
                Key Ingredients:
              </h3>
              <div className="grid md:grid-cols-2 gap-4">
                {PRODUCT.ingredients.split(", ").map((ing, i) => (
                  <div
                    key={i}
                    className="flex items-center gap-3 p-3 bg-accent/50 rounded-xl"
                  >
                    <span className="text-2xl">🌿</span>
                    <span className="text-sm font-medium text-gray-700">{ing}</span>
                  </div>
                ))}
              </div>
            </div>
          )}
          {activeTab === "howToUse" && (
            <div className="bg-accent/30 p-6 rounded-2xl">
              <h3 className="text-lg font-semibold text-gray-800 mb-3">
                Recommended Usage:
              </h3>
              <p className="text-gray-600">{PRODUCT.howToUse}</p>
            </div>
          )}
          {activeTab === "reviews" && (
            <div className="text-center py-12">
              <p className="text-gray-500">Reviews will appear here once customers start rating.</p>
              <Button variant="outline" className="mt-4">
                Write a Review
              </Button>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
