import { create } from "zustand";
import { persist } from "zustand/middleware";

export interface WishlistItemType {
  id: string;
  productId: string;
  name: string;
  image: string;
  price: number;
  mrp: number;
  slug: string;
}

interface WishlistStore {
  items: WishlistItemType[];
  addItem: (item: WishlistItemType) => void;
  removeItem: (productId: string) => void;
  isInWishlist: (productId: string) => boolean;
  clearWishlist: () => void;
}

export const useWishlistStore = create<WishlistStore>()(
  persist(
    (set, get) => ({
      items: [],

      addItem: (item) => {
        const exists = get().items.find((i) => i.productId === item.productId);
        if (!exists) {
          set({ items: [...get().items, item] });
        }
      },

      removeItem: (productId) => {
        set({ items: get().items.filter((i) => i.productId !== productId) });
      },

      isInWishlist: (productId) => {
        return get().items.some((i) => i.productId === productId);
      },

      clearWishlist: () => set({ items: [] }),
    }),
    {
      name: "shivara-wishlist",
    }
  )
);
