"use client";

import React, { useState } from "react";
import Link from "next/link";
import { ArrowLeft, Plus, Trash2, ImageIcon, Save } from "lucide-react";
import Button from "@/components/ui/Button";
import Input from "@/components/ui/Input";
import { GST_RATES } from "@/lib/constants";

interface Variant {
  id: string;
  name: string;
  mrp: string;
  sellingPrice: string;
  stock: string;
  sku: string;
  weight: string;
}

export default function NewProductPage() {
  const [formData, setFormData] = useState({
    name: "",
    description: "",
    shortDescription: "",
    ingredients: "",
    howToUse: "",
    benefits: "",
    mrp: "",
    sellingPrice: "",
    costPrice: "",
    sku: "",
    hsnCode: "",
    gstRate: "18",
    weight: "",
    stock: "",
    lowStockAlert: "5",
    categoryId: "",
    isFeatured: false,
    isActive: true,
    metaTitle: "",
    metaDescription: "",
  });

  const [variants, setVariants] = useState<Variant[]>([]);
  const [images, setImages] = useState<string[]>([]);

  const addVariant = () => {
    setVariants([
      ...variants,
      {
        id: Date.now().toString(),
        name: "",
        mrp: "",
        sellingPrice: "",
        stock: "",
        sku: "",
        weight: "",
      },
    ]);
  };

  const removeVariant = (id: string) => {
    setVariants(variants.filter((v) => v.id !== id));
  };

  const updateVariant = (id: string, field: keyof Variant, value: string) => {
    setVariants(
      variants.map((v) => (v.id === id ? { ...v, [field]: value } : v))
    );
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // API call to create product
    alert("Product created successfully! (Demo mode)");
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex items-center gap-4">
        <Link href="/admin/products" className="text-gray-500 hover:text-gray-700">
          <ArrowLeft size={20} />
        </Link>
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Add New Product</h1>
          <p className="text-sm text-gray-500">Create a new product for your store</p>
        </div>
      </div>

      <form onSubmit={handleSubmit}>
        <div className="grid lg:grid-cols-3 gap-6">
          {/* Main content */}
          <div className="lg:col-span-2 space-y-6">
            {/* Basic Info */}
            <div className="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
              <h2 className="font-bold text-gray-900">Basic Information</h2>
              <Input
                label="Product Name *"
                placeholder="e.g., Ashwagandha Gold Capsules"
                value={formData.name}
                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
              />
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                  Short Description
                </label>
                <textarea
                  placeholder="Brief product description (shown in listings)"
                  rows={2}
                  value={formData.shortDescription}
                  onChange={(e) => setFormData({ ...formData, shortDescription: e.target.value })}
                  className="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/20 text-sm"
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                  Full Description
                </label>
                <textarea
                  placeholder="Detailed product description"
                  rows={5}
                  value={formData.description}
                  onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                  className="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/20 text-sm"
                />
              </div>
            </div>

            {/* Ayurvedic Details */}
            <div className="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
              <h2 className="font-bold text-gray-900">Ayurvedic Details</h2>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                  Key Ingredients
                </label>
                <textarea
                  placeholder="List of ingredients (comma separated)"
                  rows={3}
                  value={formData.ingredients}
                  onChange={(e) => setFormData({ ...formData, ingredients: e.target.value })}
                  className="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/20 text-sm"
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                  How to Use
                </label>
                <textarea
                  placeholder="Dosage and usage instructions"
                  rows={3}
                  value={formData.howToUse}
                  onChange={(e) => setFormData({ ...formData, howToUse: e.target.value })}
                  className="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/20 text-sm"
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                  Benefits
                </label>
                <textarea
                  placeholder="Key benefits (one per line)"
                  rows={4}
                  value={formData.benefits}
                  onChange={(e) => setFormData({ ...formData, benefits: e.target.value })}
                  className="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/20 text-sm"
                />
              </div>
            </div>

            {/* Pricing */}
            <div className="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
              <h2 className="font-bold text-gray-900">Pricing & Inventory</h2>
              <div className="grid md:grid-cols-3 gap-4">
                <Input
                  label="MRP (₹) *"
                  type="number"
                  placeholder="0"
                  value={formData.mrp}
                  onChange={(e) => setFormData({ ...formData, mrp: e.target.value })}
                />
                <Input
                  label="Selling Price (₹) *"
                  type="number"
                  placeholder="0"
                  value={formData.sellingPrice}
                  onChange={(e) => setFormData({ ...formData, sellingPrice: e.target.value })}
                />
                <Input
                  label="Cost Price (₹)"
                  type="number"
                  placeholder="0"
                  value={formData.costPrice}
                  onChange={(e) => setFormData({ ...formData, costPrice: e.target.value })}
                />
              </div>
              <div className="grid md:grid-cols-3 gap-4">
                <Input
                  label="SKU"
                  placeholder="SHV-XXX-001"
                  value={formData.sku}
                  onChange={(e) => setFormData({ ...formData, sku: e.target.value })}
                />
                <Input
                  label="HSN Code"
                  placeholder="3004"
                  value={formData.hsnCode}
                  onChange={(e) => setFormData({ ...formData, hsnCode: e.target.value })}
                />
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1.5">
                    GST Rate
                  </label>
                  <select
                    value={formData.gstRate}
                    onChange={(e) => setFormData({ ...formData, gstRate: e.target.value })}
                    className="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/20 text-sm"
                  >
                    {GST_RATES.map((rate) => (
                      <option key={rate} value={rate}>{rate}%</option>
                    ))}
                  </select>
                </div>
              </div>
              <div className="grid md:grid-cols-3 gap-4">
                <Input
                  label="Stock Quantity *"
                  type="number"
                  placeholder="0"
                  value={formData.stock}
                  onChange={(e) => setFormData({ ...formData, stock: e.target.value })}
                />
                <Input
                  label="Low Stock Alert"
                  type="number"
                  placeholder="5"
                  value={formData.lowStockAlert}
                  onChange={(e) => setFormData({ ...formData, lowStockAlert: e.target.value })}
                />
                <Input
                  label="Weight (grams)"
                  type="number"
                  placeholder="150"
                  value={formData.weight}
                  onChange={(e) => setFormData({ ...formData, weight: e.target.value })}
                />
              </div>
            </div>

            {/* Variants */}
            <div className="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
              <div className="flex items-center justify-between">
                <h2 className="font-bold text-gray-900">Variants (Optional)</h2>
                <Button variant="outline" size="sm" type="button" onClick={addVariant}>
                  <Plus size={14} className="mr-1" /> Add Variant
                </Button>
              </div>
              <p className="text-sm text-gray-500">
                Add different pack sizes (e.g., 60 capsules, 120 capsules)
              </p>

              {variants.map((variant) => (
                <div
                  key={variant.id}
                  className="p-4 bg-gray-50 rounded-xl border border-gray-200 space-y-3"
                >
                  <div className="flex items-center justify-between">
                    <span className="text-sm font-medium text-gray-700">Variant</span>
                    <button
                      type="button"
                      onClick={() => removeVariant(variant.id)}
                      className="text-red-500 hover:text-red-700"
                    >
                      <Trash2 size={16} />
                    </button>
                  </div>
                  <div className="grid md:grid-cols-3 gap-3">
                    <Input
                      placeholder="Name (e.g., 120 Capsules)"
                      value={variant.name}
                      onChange={(e) => updateVariant(variant.id, "name", e.target.value)}
                    />
                    <Input
                      placeholder="MRP"
                      type="number"
                      value={variant.mrp}
                      onChange={(e) => updateVariant(variant.id, "mrp", e.target.value)}
                    />
                    <Input
                      placeholder="Selling Price"
                      type="number"
                      value={variant.sellingPrice}
                      onChange={(e) => updateVariant(variant.id, "sellingPrice", e.target.value)}
                    />
                    <Input
                      placeholder="Stock"
                      type="number"
                      value={variant.stock}
                      onChange={(e) => updateVariant(variant.id, "stock", e.target.value)}
                    />
                    <Input
                      placeholder="SKU"
                      value={variant.sku}
                      onChange={(e) => updateVariant(variant.id, "sku", e.target.value)}
                    />
                    <Input
                      placeholder="Weight (g)"
                      type="number"
                      value={variant.weight}
                      onChange={(e) => updateVariant(variant.id, "weight", e.target.value)}
                    />
                  </div>
                </div>
              ))}
            </div>

            {/* SEO */}
            <div className="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
              <h2 className="font-bold text-gray-900">SEO Settings</h2>
              <Input
                label="Meta Title"
                placeholder="SEO title (60 characters max)"
                value={formData.metaTitle}
                onChange={(e) => setFormData({ ...formData, metaTitle: e.target.value })}
              />
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                  Meta Description
                </label>
                <textarea
                  placeholder="SEO description (160 characters max)"
                  rows={3}
                  value={formData.metaDescription}
                  onChange={(e) => setFormData({ ...formData, metaDescription: e.target.value })}
                  className="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/20 text-sm"
                />
              </div>
            </div>
          </div>

          {/* Sidebar */}
          <div className="lg:col-span-1 space-y-6">
            {/* Publish */}
            <div className="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
              <h2 className="font-bold text-gray-900">Publish</h2>
              <div className="space-y-3">
                <label className="flex items-center gap-2 cursor-pointer">
                  <input
                    type="checkbox"
                    checked={formData.isActive}
                    onChange={(e) => setFormData({ ...formData, isActive: e.target.checked })}
                    className="rounded text-primary"
                  />
                  <span className="text-sm text-gray-700">Active (visible on store)</span>
                </label>
                <label className="flex items-center gap-2 cursor-pointer">
                  <input
                    type="checkbox"
                    checked={formData.isFeatured}
                    onChange={(e) => setFormData({ ...formData, isFeatured: e.target.checked })}
                    className="rounded text-primary"
                  />
                  <span className="text-sm text-gray-700">Featured Product</span>
                </label>
              </div>
              <Button variant="cta" fullWidth type="submit">
                <Save size={16} className="mr-2" /> Save Product
              </Button>
            </div>

            {/* Category */}
            <div className="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
              <h2 className="font-bold text-gray-900">Category</h2>
              <select
                value={formData.categoryId}
                onChange={(e) => setFormData({ ...formData, categoryId: e.target.value })}
                className="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/20 text-sm"
              >
                <option value="">Select Category</option>
                <option value="capsules">Capsules & Tablets</option>
                <option value="powders">Herbal Powders</option>
                <option value="oils">Oils & Syrups</option>
                <option value="skincare">Skin & Hair Care</option>
                <option value="immunity">Immunity Boosters</option>
                <option value="digestive">Digestive Health</option>
              </select>
            </div>

            {/* Images */}
            <div className="bg-white p-6 rounded-2xl border border-gray-200 space-y-4">
              <h2 className="font-bold text-gray-900">Product Images</h2>
              <div className="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center">
                <ImageIcon size={40} className="text-gray-300 mx-auto mb-2" />
                <p className="text-sm text-gray-500 mb-2">
                  Drag & drop images or click to upload
                </p>
                <p className="text-xs text-gray-400">
                  PNG, JPG up to 5MB. First image is primary.
                </p>
                <Button variant="outline" size="sm" className="mt-3" type="button">
                  Upload Images
                </Button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  );
}
