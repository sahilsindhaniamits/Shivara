import React from "react";
import { cn } from "@/lib/utils";

interface BadgeProps {
  children: React.ReactNode;
  variant?: "default" | "success" | "warning" | "error" | "info" | "secondary" | "gold";
  size?: "sm" | "md";
  className?: string;
}

export default function Badge({
  children,
  variant = "default",
  size = "sm",
  className,
}: BadgeProps) {
  const variants = {
    default: "bg-gray-100 text-gray-700",
    success: "bg-green-50 text-green-800",
    warning: "bg-amber-50 text-amber-800",
    error: "bg-[#8B4513]/10 text-[#8B4513]",
    info: "bg-blue-50 text-blue-800",
    secondary: "bg-secondary/10 text-secondary",
    gold: "bg-primary/10 text-primary-dark",
  };

  const sizes = {
    sm: "text-[10px] px-2.5 py-0.5",
    md: "text-xs px-3 py-1",
  };

  return (
    <span
      className={cn(
        "inline-flex items-center font-medium rounded-full uppercase tracking-wider",
        variants[variant],
        sizes[size],
        className
      )}
    >
      {children}
    </span>
  );
}
