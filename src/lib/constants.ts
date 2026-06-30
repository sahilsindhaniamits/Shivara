export const SITE_CONFIG = {
  name: "Shivara",
  tagline: "Pure Ayurvedic Wellness",
  description:
    "Shivara offers natural herbal products crafted with care to support wellness, purity, and everyday health.",
  url: "https://theshivara.com",
  email: "Info@theshivara.com",
  phone: "+91-9828385808",
  whatsapp: "+919828385808",
  address: {
    line1: "H-1-386-387, Agro Food Park",
    line2: "Udyog Vihar, RIICO Industrial Area",
    city: "Sri Ganganagar",
    state: "Rajasthan",
    pincode: "335001",
    country: "India",
  },
  social: {
    instagram: "https://instagram.com/theshivara",
    facebook: "https://facebook.com/theshivara",
    twitter: "https://twitter.com/theshivara",
    youtube: "https://youtube.com/@theshivara",
  },
};

export const THEME = {
  colors: {
    primary: "#1B5E20", // Deep Forest Green
    primaryLight: "#2E7D32",
    primaryDark: "#0D3B13",
    secondary: "#D4A017", // Gold / Turmeric
    secondaryLight: "#E6B422",
    accent: "#FFF8E7", // Warm Cream
    background: "#FAFAF5", // Off-White
    surface: "#FFFFFF",
    text: "#2D2D2D", // Dark Charcoal
    textLight: "#666666",
    cta: "#E65100", // Saffron Orange
    ctaHover: "#BF360C",
    success: "#2E7D32",
    warning: "#F57C00",
    error: "#C62828",
    border: "#E8E5DE",
  },
};

export const SHIPPING_CONFIG = {
  freeShippingThreshold: 999, // Free shipping above ₹999
  standardRate: 79,
  expressRate: 149,
  codCharge: 49,
  estimatedDays: {
    standard: "5-7 business days",
    express: "2-3 business days",
  },
};

export const ORDER_STATUSES = {
  PENDING: { label: "Order Placed", color: "yellow" },
  CONFIRMED: { label: "Confirmed", color: "blue" },
  PROCESSING: { label: "Processing", color: "indigo" },
  SHIPPED: { label: "Shipped", color: "purple" },
  OUT_FOR_DELIVERY: { label: "Out for Delivery", color: "orange" },
  DELIVERED: { label: "Delivered", color: "green" },
  CANCELLED: { label: "Cancelled", color: "red" },
  RETURNED: { label: "Returned", color: "gray" },
};

export const GST_RATES = [0, 5, 12, 18, 28];

export const INDIAN_STATES = [
  "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh",
  "Goa", "Gujarat", "Haryana", "Himachal Pradesh", "Jharkhand",
  "Karnataka", "Kerala", "Madhya Pradesh", "Maharashtra", "Manipur",
  "Meghalaya", "Mizoram", "Nagaland", "Odisha", "Punjab",
  "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura",
  "Uttar Pradesh", "Uttarakhand", "West Bengal",
  "Andaman and Nicobar Islands", "Chandigarh", "Dadra and Nagar Haveli and Daman and Diu",
  "Delhi", "Jammu and Kashmir", "Ladakh", "Lakshadweep", "Puducherry",
];
