"use client";

import React from "react";
import Link from "next/link";
import Image from "next/image";
import { Heart, ShoppingCart, Star } from "lucide-react";
import { formatPrice, calculateDiscount } from "@/lib/utils";
import { useCartStore } from "@/store/cart-store";
import { useWishlistStore } from "@/store/wishlist-store";
import Badge from "@/components/ui/Badge";

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
      <div className="product-card bg-white rounded-2xl border border-border overflow-hidden transition-all duration-300 group relative">
        {/* Image */}
        <div className="relative aspect-square overflow-hidden bg-accent/30">
          <div className="product-image transition-transform duration-500 w-full h-full relative">
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
                <span className="text-6xl">🌿</span>
              </div>
            )}
          </div>

          {/* Badges */}
          <div className="absolute top-3 left-3 flex flex-col gap-1">
            {discount > 0 && (
              <Badge variant="error" size="sm">
                {discount}% OFF
              </Badge>
            )}
            {isFeatured && (
              <Badge variant="secondary" size="sm">
                Bestseller
              </Badge>
            )}
            {stock === 0 && (
              <Badge variant="default" size="sm">
                Out of Stock
              </Badge>
            )}
          </div>

          {/* Wishlist button */}
          <button
            onClick={handleWishlist}
            className="absolute top-3 right-3 w-9 h-9 bg-white rounded-full flex items-center justify-center shadow-md hover:scale-110 transition"
          >
            <Heart
              size={18}
              className={inWishlist ? "fill-red-500 text-red-500" : "text-gray-400"}
            />
          </button>

          {/* Quick add to cart */}
          {stock > 0 && (
            <button
              onClick={handleAddToCart}
              className="absolute bottom-3 right-3 bg-primary text-white p-2.5 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-all transform translate-y-2 group-hover:translate-y-0 hover:bg-primary-light"
            >
              <ShoppingCart size={18} />
            </button>
          )}
        </div>

        {/* Content */}
        <div className="p-4">
          <h3 className="text-sm font-medium text-gray-800 line-clamp-2 mb-2 group-hover:text-primary transition min-h-[2.5rem]">
            {name}
          </h3>

          {/* Rating */}
          {rating > 0 && (
            <div className="flex items-center gap-1 mb-2">
              <div className="flex items-center gap-0.5 bg-green-50 px-2 py-0.5 rounded-full">
                <Star size={12} className="fill-green-600 text-green-600" />
                <span className="text-xs font-semibold text-green-700">
                  {rating.toFixed(1)}
                </span>
              </div>
              <span className="text-xs text-gray-400">({reviewCount})</span>
            </div>
          )}

          {/* Price */}
          <div className="flex items-center gap-2">
            <span className="text-lg font-bold text-primary">
              {formatPrice(sellingPrice)}
            </span>
            {discount > 0 && (
              <span className="text-sm text-gray-400 line-through">
                {formatPrice(mrp)}
              </span>
            )}
          </div>
        </div>
      </div>
    </Link>
  );
}
