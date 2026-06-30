export const SITE_CONFIG = {
  name: "Shivara",
  tagline: "Ayurvedic Purity, Elevated",
  description:
    "Shivara offers natural herbal products crafted with care to support wellness, purity, and everyday health, bringing the goodness of traditional ingredients to your lifestyle.",
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
    primary: "#B7925C", // Antique Gold
    primaryLight: "#C9A46C",
    primaryDark: "#96743A",
    secondary: "#2C2C2C", // Dark Charcoal
    secondaryLight: "#4A4A4A",
    accent: "#F5F0E8", // Light Beige
    background: "#F7F4EF", // Warm Cream
    surface: "#FFFFFF",
    text: "#2A2A2A", // Near Black
    textLight: "#7A7A7A", // Muted Gray
    textMuted: "#9B9B9B",
    cta: "#B7925C", // Gold as CTA
    ctaHover: "#96743A",
    success: "#4A7C59",
    warning: "#B7925C",
    error: "#8B4513", // Rust/Terracotta
    border: "#E8E3DB",
    dark: "#1A1A1A",
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
