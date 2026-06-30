import type { Metadata } from "next";
import { Inter, Cormorant_Garamond } from "next/font/google";
import "./globals.css";
import { Toaster } from "react-hot-toast";

const inter = Inter({
  subsets: ["latin"],
  variable: "--font-inter",
});

const cormorant = Cormorant_Garamond({
  subsets: ["latin"],
  variable: "--font-serif",
  weight: ["300", "400", "500", "600"],
});

export const metadata: Metadata = {
  title: {
    default: "Shivara — Ayurvedic Purity, Elevated",
    template: "%s | Shivara",
  },
  description:
    "Shivara offers natural herbal products crafted with care to support wellness, purity, and everyday health. Shop authentic Ayurvedic capsules, powders, and supplements online.",
  keywords: [
    "ayurvedic products",
    "herbal supplements",
    "natural wellness",
    "ayurveda",
    "shivara",
    "organic health",
    "herbal capsules",
    "ayurvedic medicine online",
  ],
  authors: [{ name: "Shivara" }],
  creator: "Shivara",
  openGraph: {
    type: "website",
    locale: "en_IN",
    url: "https://theshivara.com",
    siteName: "Shivara",
    title: "Shivara - Pure Ayurvedic Wellness",
    description:
      "Shop authentic Ayurvedic products online. 100% Natural, GMP Certified, Free Delivery above ₹999.",
    images: [
      {
        url: "/images/og-image.jpg",
        width: 1200,
        height: 630,
        alt: "Shivara - Pure Ayurvedic Wellness",
      },
    ],
  },
  twitter: {
    card: "summary_large_image",
    title: "Shivara - Pure Ayurvedic Wellness",
    description:
      "Shop authentic Ayurvedic products online. 100% Natural, GMP Certified.",
  },
  robots: {
    index: true,
    follow: true,
    googleBot: {
      index: true,
      follow: true,
      "max-video-preview": -1,
      "max-image-preview": "large",
      "max-snippet": -1,
    },
  },
  verification: {
    google: "your-google-verification-code",
  },
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en" className={`${inter.variable} ${cormorant.variable}`}>
      <head>
        <link rel="icon" href="/favicon.ico" />
        <meta name="theme-color" content="#1B5E20" />
      </head>
      <body className="min-h-screen bg-background text-secondary antialiased">
        {children}
        <Toaster
          position="top-center"
          toastOptions={{
            duration: 3000,
            style: {
              background: "#2C2C2C",
              color: "#fff",
              borderRadius: "100px",
              fontSize: "13px",
              padding: "12px 20px",
            },
          }}
        />
      </body>
    </html>
  );
}
