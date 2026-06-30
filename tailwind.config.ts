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
          DEFAULT: "#B7925C",
          light: "#C9A46C",
          dark: "#96743A",
          50: "#FBF7F1",
          100: "#F5EDE0",
          200: "#EBDBC1",
          300: "#DFC69E",
          400: "#CEAB78",
          500: "#B7925C",
          600: "#96743A",
          700: "#7A5E2F",
          800: "#5E4824",
          900: "#42321A",
        },
        secondary: {
          DEFAULT: "#2C2C2C",
          light: "#4A4A4A",
          dark: "#1A1A1A",
          50: "#F5F5F5",
          100: "#E8E8E8",
          200: "#D1D1D1",
          300: "#B0B0B0",
          400: "#888888",
          500: "#2C2C2C",
          600: "#242424",
          700: "#1A1A1A",
          800: "#111111",
          900: "#0A0A0A",
        },
        accent: "#F5F0E8",
        surface: "#FFFFFF",
        background: "#F7F4EF",
        cta: {
          DEFAULT: "#B7925C",
          hover: "#96743A",
        },
        border: "#E8E3DB",
        muted: "#7A7A7A",
      },
      fontFamily: {
        sans: ["Inter", "system-ui", "-apple-system", "sans-serif"],
        serif: ["Cormorant Garamond", "Georgia", "Times New Roman", "serif"],
      },
      borderRadius: {
        xl: "1rem",
        "2xl": "1.5rem",
      },
      boxShadow: {
        soft: "0 2px 15px rgba(0, 0, 0, 0.04)",
        medium: "0 4px 25px rgba(0, 0, 0, 0.06)",
        hard: "0 10px 40px rgba(0, 0, 0, 0.10)",
      },
      letterSpacing: {
        widest: "0.2em",
      },
    },
  },
  plugins: [],
};

export default config;
