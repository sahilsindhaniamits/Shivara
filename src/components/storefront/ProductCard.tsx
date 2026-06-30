"use client";

import React from "react";
import Link from "next/link";
import Image from "next/image";
import { Heart, ShoppingCart } from "lucide-react";
import { formatPrice, calculateDiscount } from "@/lib/utils";
import { useCartStore } from "@/store/cart-store";
import { useWishlistStore } from "@/store/wishlist-store";

interface ProductCardProps {
  id: string;
  name: string;
  slug: string;
  image: string;
  mrp: number;
  sellingPrice: number;
  rating?: number;
  reviewCount?: number;
  stock: number;
  isFeatured?: boolean;
  category?: string;
}

export default function ProductCard({
  id,
  name,
  slug,
  image,
  mrp,
  sellingPrice,
  rating = 0,
  reviewCount = 0,
  stock,
  isFeatured = false,
  category,
}: ProductCardProps) {
  const addToCart = useCartStore((s) => s.addItem);
  const { addItem: addToWishlist, removeItem: removeFromWishlist, isInWishlist } =
    useWishlistStore();

  const discount = calculateDiscount(mrp, sellingPrice);
  const inWishlist = isInWishlist(id);

  const handleAddToCart = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    addToCart({
      id: `${id}-default`,
      productId: id,
      name,
      image,
      price: sellingPrice,
      mrp,
      quantity: 1,
      stock,
    });
  };

  const handleWishlist = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    if (inWishlist) {
      removeFromWishlist(id);
    } else {
      addToWishlist({ id: `wl-${id}`, productId: id, name, image, price: sellingPrice, mrp, slug });
    }
  };

  return (
    <Link href={`/products/${slug}`}>
      <div className="product-card bg-white border border-border overflow-hidden group relative">
        {/* Image */}
        <div className="relative aspect-[4/5] overflow-hidden bg-accent">
          <div className="product-image transition-transform duration-700 w-full h-full relative">
            {image ? (
              <Image
                src={image}
                alt={name}
                fill
                className="object-cover"
                sizes="(max-width: 768px) 50vw, 25vw"
              />
            ) : (
              <div className="w-full h-full flex items-center justify-center bg-accent">
                <span className="text-6xl opacity-30">🌿</span>
              </div>
            )}
          </div>

          {/* Discount badge - rust/terracotta pill */}
          {discount > 0 && (
            <div className="absolute top-3 left-3">
              <span className="bg-[#8B4513] text-white text-[10px] font-medium px-2.5 py-1 rounded-full">
                -{discount}%
              </span>
            </div>
          )}

          {/* Wishlist button */}
          <button
            onClick={handleWishlist}
            className="absolute top-3 right-3 w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300"
          >
            <Heart
              size={15}
              strokeWidth={1.5}
              className={inWishlist ? "fill-primary text-primary" : "text-secondary/60"}
            />
          </button>

          {/* Quick add to cart */}
          {stock > 0 && (
            <button
              onClick={handleAddToCart}
              className="absolute bottom-0 left-0 right-0 bg-secondary/90 backdrop-blur-sm text-white py-3 text-xs uppercase tracking-widest font-medium translate-y-full group-hover:translate-y-0 transition-transform duration-300 text-center"
            >
              Add to Cart
            </button>
          )}

          {stock === 0 && (
            <div className="absolute inset-0 bg-white/60 flex items-center justify-center">
              <span className="text-xs uppercase tracking-widest text-secondary/60 font-medium">
                Sold Out
              </span>
            </div>
          )}
        </div>

        {/* Content */}
        <div className="p-4 pt-5">
          {/* Category label */}
          {category && (
            <p className="text-[10px] font-medium uppercase tracking-[0.2em] text-primary mb-2">
              {category}
            </p>
          )}

          <h3 className="font-serif text-base text-secondary line-clamp-1 group-hover:text-primary transition-colors duration-300">
            {name}
          </h3>

          {/* Short description placeholder */}
          <p className="text-xs text-muted mt-1 line-clamp-1">
            Natural Ayurvedic formulation
          </p>

          {/* Price */}
          <div className="flex items-center gap-2 mt-3">
            <span className="text-base font-medium text-secondary">
              {formatPrice(sellingPrice)}
            </span>
            {discount > 0 && (
              <span className="text-sm text-muted line-through">
                {formatPrice(mrp)}
              </span>
            )}
          </div>
        </div>
      </div>
    </Link>
  );
}
