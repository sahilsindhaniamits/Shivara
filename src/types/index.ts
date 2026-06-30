// @ts-nocheck
import { 
  Product, 
  Category, 
  ProductImage, 
  ProductVariant, 
  Order, 
  OrderItem, 
  User, 
  Review, 
  Coupon, 
  Banner, 
  Address,
  BlogPost,
} from "@prisma/client";

export type ProductWithRelations = Product & {
  images: ProductImage[];
  variants: ProductVariant[];
  category: Category | null;
  reviews: Review[];
  _count?: {
    reviews: number;
    orderItems: number;
  };
};

export type OrderWithRelations = Order & {
  items: (OrderItem & {
    product: Product & { images: ProductImage[] };
    variant: ProductVariant | null;
  })[];
  user: Pick<User, "id" | "name" | "email" | "phone">;
  address: Address;
};

export type ReviewWithUser = Review & {
  user: Pick<User, "id" | "name" | "image">;
  product: Pick<Product, "id" | "name" | "slug"> & { images: ProductImage[] };
};

export type CategoryWithChildren = Category & {
  children: Category[];
  _count?: {
    products: number;
  };
};

export interface DashboardStats {
  totalRevenue: number;
  totalOrders: number;
  totalCustomers: number;
  totalProducts: number;
  revenueGrowth: number;
  ordersGrowth: number;
  customersGrowth: number;
  recentOrders: OrderWithRelations[];
  topProducts: (Product & { _count: { orderItems: number } })[];
  lowStockProducts: Product[];
  revenueByMonth: { month: string; revenue: number }[];
}

export interface ShippingRate {
  method: string;
  rate: number;
  estimatedDays: string;
}

export interface RazorpayOrder {
  id: string;
  amount: number;
  currency: string;
  receipt: string;
  status: string;
}

export interface ShiprocketOrder {
  order_id: string;
  shipment_id: string;
  awb_code: string;
  courier_name: string;
  tracking_url: string;
}
