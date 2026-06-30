import { create } from "zustand";
import { persist } from "zustand/middleware";

export interface CartItemType {
  id: string;
  productId: string;
  variantId?: string;
  name: string;
  variantName?: string;
  image: string;
  price: number;
  mrp: number;
  quantity: number;
  stock: number;
  weight?: number;
}

interface CartStore {
  items: CartItemType[];
  isOpen: boolean;
  
  addItem: (item: CartItemType) => void;
  removeItem: (id: string) => void;
  updateQuantity: (id: string, quantity: number) => void;
  clearCart: () => void;
  toggleCart: () => void;
  openCart: () => void;
  closeCart: () => void;
  
  getSubtotal: () => number;
  getTotalMRP: () => number;
  getTotalDiscount: () => number;
  getItemCount: () => number;
}

export const useCartStore = create<CartStore>()(
  persist(
    (set, get) => ({
      items: [],
      isOpen: false,

      addItem: (item) => {
        const items = get().items;
        const existingIndex = items.findIndex(
          (i) => i.productId === item.productId && i.variantId === item.variantId
        );

        if (existingIndex > -1) {
          const updatedItems = [...items];
          const newQty = updatedItems[existingIndex].quantity + item.quantity;
          updatedItems[existingIndex].quantity = Math.min(newQty, item.stock);
          set({ items: updatedItems });
        } else {
          set({ items: [...items, item] });
        }
      },

      removeItem: (id) => {
        set({ items: get().items.filter((item) => item.id !== id) });
      },

      updateQuantity: (id, quantity) => {
        if (quantity <= 0) {
          get().removeItem(id);
          return;
        }
        const items = get().items.map((item) =>
          item.id === id ? { ...item, quantity: Math.min(quantity, item.stock) } : item
        );
        set({ items });
      },

      clearCart: () => set({ items: [] }),
      toggleCart: () => set({ isOpen: !get().isOpen }),
      openCart: () => set({ isOpen: true }),
      closeCart: () => set({ isOpen: false }),

      getSubtotal: () => {
        return get().items.reduce((sum, item) => sum + item.price * item.quantity, 0);
      },

      getTotalMRP: () => {
        return get().items.reduce((sum, item) => sum + item.mrp * item.quantity, 0);
      },

      getTotalDiscount: () => {
        return get().getTotalMRP() - get().getSubtotal();
      },

      getItemCount: () => {
        return get().items.reduce((sum, item) => sum + item.quantity, 0);
      },
    }),
    {
      name: "shivara-cart",
    }
  )
);
