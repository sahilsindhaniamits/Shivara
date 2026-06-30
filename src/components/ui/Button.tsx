"use client";

import React from "react";
import { cn } from "@/lib/utils";

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: "primary" | "secondary" | "outline" | "ghost" | "cta" | "danger" | "dark";
  size?: "sm" | "md" | "lg";
  loading?: boolean;
  fullWidth?: boolean;
  children: React.ReactNode;
}

export default function Button({
  variant = "primary",
  size = "md",
  loading = false,
  fullWidth = false,
  className,
  children,
  disabled,
  ...props
}: ButtonProps) {
  const baseStyles =
    "inline-flex items-center justify-center font-medium transition-all duration-300 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed";

  const variants = {
    primary:
      "bg-primary text-white hover:bg-primary-dark rounded-full uppercase tracking-wider text-xs",
    secondary:
      "bg-secondary text-white hover:bg-secondary-light rounded-full uppercase tracking-wider text-xs",
    outline:
      "border border-secondary text-secondary hover:bg-secondary hover:text-white rounded-full uppercase tracking-wider text-xs",
    ghost:
      "text-secondary hover:text-primary uppercase tracking-wider text-xs",
    cta:
      "bg-primary text-white hover:bg-primary-dark rounded-full uppercase tracking-wider text-xs",
    danger:
      "bg-[#8B4513] text-white hover:bg-[#6B3410] rounded-full uppercase tracking-wider text-xs",
    dark:
      "bg-secondary text-white hover:bg-secondary-dark rounded-full uppercase tracking-wider text-xs",
  };

  const sizes = {
    sm: "px-5 py-2.5",
    md: "px-7 py-3",
    lg: "px-9 py-3.5",
  };

  return (
    <button
      className={cn(
        baseStyles,
        variants[variant],
        sizes[size],
        fullWidth && "w-full",
        className
      )}
      disabled={disabled || loading}
      {...props}
    >
      {loading && (
        <svg
          className="animate-spin -ml-1 mr-2 h-4 w-4"
          fill="none"
          viewBox="0 0 24 24"
        >
          <circle
            className="opacity-25"
            cx="12"
            cy="12"
            r="10"
            stroke="currentColor"
            strokeWidth="4"
          />
          <path
            className="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
          />
        </svg>
      )}
      {children}
    </button>
  );
}
