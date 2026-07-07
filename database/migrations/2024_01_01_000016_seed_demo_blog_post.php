<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Only seed if no blogs exist
        if (DB::table('blogs')->count() === 0) {
            DB::table('blogs')->insert([
                [
                    'title' => '10 Ayurvedic Herbs That Boost Immunity Naturally',
                    'slug' => '10-ayurvedic-herbs-boost-immunity',
                    'excerpt' => 'Discover the ancient Ayurvedic herbs that can strengthen your immune system naturally without any side effects.',
                    'content' => '<p>In the ancient science of Ayurveda, immunity is known as <strong>Vyadhikshamatva</strong> — the body\'s natural ability to resist disease. Unlike modern medicine that often targets symptoms, Ayurveda strengthens the body from within.</p><h2>1. Ashwagandha (Withania Somnifera)</h2><p>Known as the "Indian Ginseng," Ashwagandha is an adaptogenic herb that helps the body manage stress and strengthens the immune system. It increases white blood cell production and helps the body fight infections.</p><h2>2. Tulsi (Holy Basil)</h2><p>Tulsi is revered as the "Queen of Herbs" in Ayurveda. It has powerful antimicrobial, anti-inflammatory, and adaptogenic properties. Regular consumption of Tulsi tea can significantly boost respiratory immunity.</p><h2>3. Turmeric (Curcuma Longa)</h2><p>The golden spice of India, turmeric contains curcumin — a powerful anti-inflammatory and antioxidant compound. It modulates the immune system and protects against various infections.</p><h2>4. Amla (Indian Gooseberry)</h2><p>One of the richest natural sources of Vitamin C, Amla strengthens immunity, improves digestion, and promotes healthy skin and hair. A single Amla fruit contains as much Vitamin C as 20 oranges.</p><h2>5. Giloy (Tinospora Cordifolia)</h2><p>Called "Amrita" or the root of immortality, Giloy is an excellent immunomodulator. It purifies the blood, fights bacteria, and is particularly effective against recurrent fevers.</p><p><em>Start your immunity journey today with Shivara\'s range of pure Ayurvedic supplements.</em></p>',
                    'featured_image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=800&h=500&fit=crop',
                    'category' => 'Health Tips',
                    'tags' => 'immunity, ayurveda, herbs, health',
                    'meta_title' => '10 Ayurvedic Herbs for Immunity | Shivara Blog',
                    'meta_description' => 'Learn about 10 powerful Ayurvedic herbs that naturally boost your immune system. Expert tips from Shivara Ayurveda.',
                    'author_id' => 1,
                    'is_published' => true,
                    'published_at' => now()->subDays(3),
                    'views' => 156,
                    'created_at' => now()->subDays(3),
                    'updated_at' => now()->subDays(3),
                ],
                [
                    'title' => 'Ayurvedic Morning Routine: Start Your Day Right',
                    'slug' => 'ayurvedic-morning-routine',
                    'excerpt' => 'Learn the perfect Dinacharya (daily routine) recommended by Ayurveda for optimal health and energy throughout the day.',
                    'content' => '<p>Ayurveda emphasizes the importance of <strong>Dinacharya</strong> — a daily routine aligned with nature\'s rhythms. Following these simple morning practices can transform your health.</p><h2>Wake Up Before Sunrise</h2><p>Ayurveda recommends waking during Brahma Muhurta (approximately 4:30-5:30 AM). This time is sattvic in nature and ideal for meditation and self-care.</p><h2>Oil Pulling (Gandusha)</h2><p>Swish 1 tablespoon of sesame or coconut oil in your mouth for 15-20 minutes. This ancient practice removes toxins, strengthens teeth and gums, and improves oral health.</p><h2>Tongue Scraping</h2><p>Use a copper tongue scraper to remove the white coating (ama/toxins) from your tongue. This improves taste perception and stimulates digestive enzymes.</p><h2>Drink Warm Water</h2><p>Start your day with a glass of warm water, optionally with lemon and honey. This kindles the digestive fire (Agni) and helps flush toxins from the system.</p><p><em>Explore Shivara\'s morning wellness range for the perfect start to your day.</em></p>',
                    'featured_image' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=800&h=500&fit=crop',
                    'category' => 'Lifestyle',
                    'tags' => 'morning routine, dinacharya, wellness, lifestyle',
                    'meta_title' => 'Ayurvedic Morning Routine Guide | Shivara',
                    'meta_description' => 'Discover the perfect Ayurvedic morning routine (Dinacharya) for better health, energy, and mental clarity.',
                    'author_id' => 1,
                    'is_published' => true,
                    'published_at' => now()->subDays(7),
                    'views' => 89,
                    'created_at' => now()->subDays(7),
                    'updated_at' => now()->subDays(7),
                ],
                [
                    'title' => 'Understanding Your Dosha: Vata, Pitta, Kapha Guide',
                    'slug' => 'understanding-your-dosha-guide',
                    'excerpt' => 'A comprehensive guide to understanding the three Doshas in Ayurveda and how to find balance for your unique constitution.',
                    'content' => '<p>In Ayurveda, every person has a unique constitution called <strong>Prakriti</strong>, determined by the balance of three fundamental energies or Doshas: Vata, Pitta, and Kapha.</p><h2>Vata Dosha (Air + Space)</h2><p>Vata types are creative, energetic, and quick-thinking. When balanced, they are lively and enthusiastic. When imbalanced, they experience anxiety, dry skin, and irregular digestion. Best foods: warm, moist, grounding foods like soups, ghee, and cooked grains.</p><h2>Pitta Dosha (Fire + Water)</h2><p>Pitta types are intelligent, focused, and ambitious. When balanced, they are natural leaders. When imbalanced, they experience inflammation, acidity, and irritability. Best foods: cooling foods like cucumber, coconut, and sweet fruits.</p><h2>Kapha Dosha (Earth + Water)</h2><p>Kapha types are calm, steady, and nurturing. When balanced, they are strong and loyal. When imbalanced, they experience weight gain, lethargy, and congestion. Best foods: light, warm, spicy foods like ginger tea and leafy greens.</p><p><em>Shop Shivara\'s dosha-specific product collections for personalized wellness.</em></p>',
                    'featured_image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800&h=500&fit=crop',
                    'category' => 'Ayurveda',
                    'tags' => 'dosha, vata, pitta, kapha, constitution',
                    'meta_title' => 'Vata Pitta Kapha Dosha Guide | Shivara Ayurveda',
                    'meta_description' => 'Learn about Vata, Pitta, and Kapha doshas. Find your Ayurvedic body type and achieve natural balance.',
                    'author_id' => 1,
                    'is_published' => true,
                    'published_at' => now()->subDays(14),
                    'views' => 234,
                    'created_at' => now()->subDays(14),
                    'updated_at' => now()->subDays(14),
                ],
            ]);
        }
    }

    public function down(): void
    {
        DB::table('blogs')->whereIn('slug', [
            '10-ayurvedic-herbs-boost-immunity',
            'ayurvedic-morning-routine',
            'understanding-your-dosha-guide',
        ])->delete();
    }
};
