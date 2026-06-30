import type { Config } from "tailwindcss";

const config: Config = {
  content: [
    "./src/pages/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/components/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/app/**/*.{js,ts,jsx,tsx,mdx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: "#1B5E20",
          light: "#2E7D32",
          dark: "#0D3B13",
          50: "#E8F5E9",
          100: "#C8E6C9",
          200: "#A5D6A7",
          300: "#81C784",
          400: "#66BB6A",
          500: "#1B5E20",
          600: "#175218",
          700: "#0D3B13",
          800: "#0A2E0D",
          900: "#061F08",
        },
        secondary: {
          DEFAULT: "#D4A017",
          light: "#E6B422",
          dark: "#B8860B",
          50: "#FFF8E1",
          100: "#FFECB3",
          200: "#FFE082",
          300: "#FFD54F",
          400: "#FFCA28",
          500: "#D4A017",
          600: "#C49000",
          700: "#B8860B",
          800: "#9A7209",
          900: "#7C5A07",
        },
        accent: "#FFF8E7",
        surface: "#FFFFFF",
        background: "#FAFAF5",
        cta: {
          DEFAULT: "#E65100",
          hover: "#BF360C",
        },
        border: "#E8E5DE",
      },
      fontFamily: {
        sans: ["Inter", "system-ui", "-apple-system", "sans-serif"],
        serif: ["Playfair Display", "Georgia", "serif"],
      },
      borderRadius: {
        xl: "1rem",
        "2xl": "1.5rem",
      },
      boxShadow: {
        soft: "0 2px 15px rgba(0, 0, 0, 0.05)",
        medium: "0 4px 25px rgba(0, 0, 0, 0.08)",
        hard: "0 10px 40px rgba(0, 0, 0, 0.12)",
      },
    },
  },
  plugins: [],
};

export default config;
