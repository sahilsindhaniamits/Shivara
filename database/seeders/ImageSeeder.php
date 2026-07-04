<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Banner;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    public function run(): void
    {
        // Product Images (Unsplash - Ayurvedic/herbal themed)
        $productImages = [
            'madhu-balance-capsules' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=600&h=600&fit=crop',
            'bpm-capsules' => 'https://images.unsplash.com/photo-1471864190281-a93a3070b6de?w=600&h=600&fit=crop',
            'g-liv-care-ds-capsule' => 'https://images.unsplash.com/photo-1512069772995-ec65ed45afd6?w=600&h=600&fit=crop',
            'sandhimukta-capsules' => 'https://images.unsplash.com/photo-1607619056574-7b8d3ee536b2?w=600&h=600&fit=crop',
            'ashwagandha-gold-capsules' => 'https://images.unsplash.com/photo-1611241893603-3c359704e0ee?w=600&h=600&fit=crop',
            'triphala-churna' => 'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?w=600&h=600&fit=crop',
            'amla-vitamin-c' => 'https://images.unsplash.com/photo-1577003833619-76bbd7f82948?w=600&h=600&fit=crop',
            'shilajit-gold-resin' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=600&h=600&fit=crop',
        ];

        foreach ($productImages as $slug => $imageUrl) {
            $product = Product::where('slug', $slug)->first();
            if ($product) {
                // Delete old images
                $product->images()->delete();

                // Add new image
                ProductImage::create([
                    'product_id' => $product->id,
                    'url' => $imageUrl,
                    'alt' => $product->name,
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
            }
        }

        // Category Images
        $categoryImages = [
            'capsules' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=300&h=300&fit=crop',
            'powders' => 'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?w=300&h=300&fit=crop',
            'oils' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=300&h=300&fit=crop',
            'skincare' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=300&h=300&fit=crop',
            'immunity' => 'https://images.unsplash.com/photo-1577003833619-76bbd7f82948?w=300&h=300&fit=crop',
            'digestive' => 'https://images.unsplash.com/photo-1512069772995-ec65ed45afd6?w=300&h=300&fit=crop',
        ];

        foreach ($categoryImages as $slug => $imageUrl) {
            Category::where('slug', $slug)->update(['image' => $imageUrl]);
        }

        // Banners
        Banner::truncate();

        Banner::create([
            'title' => 'Ancient Wisdom, Modern Purity',
            'subtitle' => 'Discover our heritage Ayurvedic formulations',
            'image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=1920&h=800&fit=crop',
            'mobile_image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=800&h=800&fit=crop',
            'link' => '/products',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Banner::create([
            'title' => 'Flat 15% Off on Orders Above ₹999',
            'subtitle' => 'Use code EXTRA15 at checkout',
            'image' => 'https://images.unsplash.com/photo-1611241893603-3c359704e0ee?w=1920&h=800&fit=crop',
            'mobile_image' => 'https://images.unsplash.com/photo-1611241893603-3c359704e0ee?w=800&h=800&fit=crop',
            'link' => '/products',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Banner::create([
            'title' => 'New Launch: Shilajit Gold Resin',
            'subtitle' => 'Pure Himalayan Shilajit for energy & vitality',
            'image' => 'https://images.unsplash.com/photo-1471864190281-a93a3070b6de?w=1920&h=800&fit=crop',
            'mobile_image' => 'https://images.unsplash.com/photo-1471864190281-a93a3070b6de?w=800&h=800&fit=crop',
            'link' => '/products/shilajit-gold-resin',
            'is_active' => true,
            'sort_order' => 3,
        ]);
    }
}
