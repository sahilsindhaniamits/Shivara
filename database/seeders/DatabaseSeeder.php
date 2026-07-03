<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@theshivara.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Capsules & Tablets', 'slug' => 'capsules', 'sort_order' => 1],
            ['name' => 'Herbal Powders', 'slug' => 'powders', 'sort_order' => 2],
            ['name' => 'Oils & Syrups', 'slug' => 'oils', 'sort_order' => 3],
            ['name' => 'Skin & Hair Care', 'slug' => 'skincare', 'sort_order' => 4],
            ['name' => 'Immunity Boosters', 'slug' => 'immunity', 'sort_order' => 5],
            ['name' => 'Digestive Health', 'slug' => 'digestive', 'sort_order' => 6],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Create Products
        $products = [
            [
                'name' => 'Madhu Balance Capsules',
                'slug' => 'madhu-balance-capsules',
                'short_description' => 'Supports healthy metabolism and blood sugar management naturally.',
                'description' => 'Madhu Balance Capsules are an Ayurvedic formulation designed to support healthy blood sugar levels and improve metabolic function.',
                'ingredients' => 'Gudmar, Jamun Beej, Karela, Methi, Vijaysar, Neem',
                'how_to_use' => 'Take 2 capsules twice daily after meals with lukewarm water. For best results, use consistently for 3-6 months.',
                'benefits' => "Helps maintain healthy blood sugar levels\nSupports pancreatic function\nBoosts natural metabolism\nRich in antioxidants\n100% Natural & Safe",
                'mrp' => 2799, 'selling_price' => 1249, 'stock' => 50,
                'sku' => 'SHV-MBC-001', 'category_id' => 1, 'is_featured' => true,
            ],
            [
                'name' => 'BPM Capsules',
                'slug' => 'bpm-capsules',
                'short_description' => 'Natural blood pressure management with Ayurvedic herbs.',
                'description' => 'BPM Capsules help maintain healthy blood pressure levels using a blend of time-tested Ayurvedic herbs.',
                'ingredients' => 'Arjuna, Ashwagandha, Sarpagandha, Brahmi, Shankhpushpi',
                'how_to_use' => 'Take 1-2 capsules twice daily with warm water.',
                'benefits' => "Supports healthy blood pressure\nCalms nervous system\nNatural stress relief\nImproves heart health",
                'mrp' => 1499, 'selling_price' => 899, 'stock' => 35,
                'sku' => 'SHV-BPM-002', 'category_id' => 1, 'is_featured' => true,
            ],
            [
                'name' => 'G-Liv Care DS Capsule',
                'slug' => 'g-liv-care-ds-capsule',
                'short_description' => 'Advanced liver support and detoxification.',
                'description' => 'G-Liv Care DS supports liver function and promotes natural detoxification.',
                'ingredients' => 'Kutki, Bhumi Amla, Kasni, Punarnava, Giloy',
                'how_to_use' => 'Take 1 capsule twice daily before meals.',
                'benefits' => "Supports liver health\nAids detoxification\nBoosts digestion\nProtects liver cells",
                'mrp' => 599, 'selling_price' => 449, 'stock' => 100,
                'sku' => 'SHV-GLC-003', 'category_id' => 1, 'is_featured' => false,
            ],
            [
                'name' => 'Sandhimukta Capsules',
                'slug' => 'sandhimukta-capsules',
                'short_description' => 'Natural joint care and flexibility support.',
                'description' => 'Sandhimukta provides comprehensive joint support using traditional Ayurvedic ingredients.',
                'ingredients' => 'Shallaki, Guggulu, Ashwagandha, Nirgundi, Rasna',
                'how_to_use' => 'Take 2 capsules twice daily with warm milk or water.',
                'benefits' => "Supports joint flexibility\nReduces discomfort\nStrengthens bones\nAnti-inflammatory",
                'mrp' => 1999, 'selling_price' => 1299, 'stock' => 25,
                'sku' => 'SHV-SM-004', 'category_id' => 1, 'is_featured' => true,
            ],
            [
                'name' => 'Ashwagandha Gold Capsules',
                'slug' => 'ashwagandha-gold-capsules',
                'short_description' => 'Premium stress relief and vitality booster.',
                'description' => 'Pure Ashwagandha root extract for natural stress management and enhanced energy.',
                'ingredients' => 'Ashwagandha Root Extract, Shilajit, Safed Musli',
                'how_to_use' => 'Take 1-2 capsules daily with milk before bedtime.',
                'benefits' => "Reduces stress & anxiety\nBoosts stamina & energy\nImproves sleep quality\nEnhances immunity",
                'mrp' => 1499, 'selling_price' => 899, 'stock' => 35,
                'sku' => 'SHV-AGC-005', 'category_id' => 1, 'is_featured' => true,
            ],
            [
                'name' => 'Triphala Churna',
                'slug' => 'triphala-churna',
                'short_description' => 'Classic digestive health and detox powder.',
                'description' => 'Traditional Triphala formulation for optimal digestive health and gentle detoxification.',
                'ingredients' => 'Haritaki, Bibhitaki, Amalaki',
                'how_to_use' => 'Mix 1 teaspoon with warm water before bedtime.',
                'benefits' => "Improves digestion\nGentle detox\nRich in Vitamin C\nSupports regularity",
                'mrp' => 599, 'selling_price' => 449, 'stock' => 100,
                'sku' => 'SHV-TC-006', 'category_id' => 2, 'is_featured' => false,
            ],
            [
                'name' => 'Amla Vitamin C Natural Supplement',
                'slug' => 'amla-vitamin-c',
                'short_description' => 'Natural Vitamin C from premium Indian Gooseberry.',
                'description' => 'Pure Amla extract providing natural Vitamin C for immunity and skin health.',
                'ingredients' => 'Amla Extract, Amla Powder',
                'how_to_use' => 'Take 2 tablets daily with water after meals.',
                'benefits' => "Natural Vitamin C source\nBoosts immunity\nImproves skin health\nRich in antioxidants",
                'mrp' => 699, 'selling_price' => 499, 'stock' => 45,
                'sku' => 'SHV-AVC-007', 'category_id' => 5, 'is_featured' => true,
            ],
            [
                'name' => 'Shilajit Gold Resin',
                'slug' => 'shilajit-gold-resin',
                'short_description' => 'Pure Himalayan Shilajit for energy and stamina.',
                'description' => 'Premium quality Shilajit resin sourced from high-altitude Himalayas for maximum potency.',
                'ingredients' => 'Pure Shilajit Resin, Gold Bhasma',
                'how_to_use' => 'Dissolve a pea-sized amount in warm milk or water. Take once daily.',
                'benefits' => "Boosts energy & stamina\nEnhances vitality\nRich in fulvic acid\nSupports muscle recovery",
                'mrp' => 2499, 'selling_price' => 1799, 'stock' => 20,
                'sku' => 'SHV-SGR-008', 'category_id' => 1, 'is_featured' => true,
            ],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }

        // Create Coupons
        Coupon::create([
            'code' => 'WOW10',
            'description' => '10% off up to ₹200',
            'type' => 'percentage',
            'value' => 10,
            'max_discount' => 200,
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addYear(),
        ]);

        Coupon::create([
            'code' => 'EXTRA15',
            'description' => '15% off up to ₹500',
            'type' => 'percentage',
            'value' => 15,
            'max_discount' => 500,
            'min_order_amount' => 999,
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addYear(),
        ]);
    }
}
