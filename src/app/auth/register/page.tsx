"use client";

import React, { useState } from "react";
import Link from "next/link";
import { Mail, Lock, User, Phone } from "lucide-react";
import Button from "@/components/ui/Button";
import Input from "@/components/ui/Input";

export default function RegisterPage() {
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    phone: "",
    password: "",
    confirmPassword: "",
  });
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setTimeout(() => setLoading(false), 1500);
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-accent via-white to-primary-50 flex items-center justify-center px-4 py-12">
      <div className="w-full max-w-md">
        <div className="text-center mb-8">
          <Link href="/" className="inline-flex items-center gap-2">
            <div className="w-12 h-12 bg-primary rounded-full flex items-center justify-center">
              <span className="text-white font-bold text-xl">S</span>
            </div>
          </Link>
          <h1 className="text-2xl font-bold text-gray-900 mt-4">Create Account</h1>
          <p className="text-gray-500 mt-1">Join Shivara for wellness & exclusive offers</p>
        </div>

        <div className="bg-white p-8 rounded-2xl shadow-medium border border-border">
          <form onSubmit={handleSubmit} className="space-y-4">
            <Input
              label="Full Name"
              placeholder="Your full name"
              value={formData.name}
              onChange={(e) => setFormData({ ...formData, name: e.target.value })}
              icon={<User size={18} />}
            />
            <Input
              label="Email Address"
              type="email"
              placeholder="your@email.com"
              value={formData.email}
              onChange={(e) => setFormData({ ...formData, email: e.target.value })}
              icon={<Mail size={18} />}
            />
            <Input
              label="Phone Number"
              type="tel"
              placeholder="+91 XXXXX XXXXX"
              value={formData.phone}
              onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
              icon={<Phone size={18} />}
            />
            <Input
              label="Password"
              type="password"
              placeholder="Create a password"
              value={formData.password}
              onChange={(e) => setFormData({ ...formData, password: e.target.value })}
              icon={<Lock size={18} />}
            />
            <Input
              label="Confirm Password"
              type="password"
              placeholder="Confirm your password"
              value={formData.confirmPassword}
              onChange={(e) => setFormData({ ...formData, confirmPassword: e.target.value })}
              icon={<Lock size={18} />}
            />

            <label className="flex items-start gap-2 cursor-pointer">
              <input type="checkbox" className="rounded text-primary mt-0.5" />
              <span className="text-xs text-gray-500">
                I agree to the{" "}
                <Link href="/policies/terms" className="text-primary hover:underline">
                  Terms & Conditions
                </Link>{" "}
                and{" "}
                <Link href="/policies/privacy" className="text-primary hover:underline">
                  Privacy Policy
                </Link>
              </span>
            </label>

            <Button variant="cta" fullWidth loading={loading} type="submit">
              Create Account
            </Button>
          </form>
        </div>

        <p className="text-center text-sm text-gray-500 mt-6">
          Already have an account?{" "}
          <Link href="/auth/login" className="text-primary font-medium hover:underline">
            Sign In
          </Link>
        </p>
      </div>
    </div>
  );
}
