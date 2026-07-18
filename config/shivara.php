<?php

return [
    'name' => 'Shivara',
    'tagline' => 'Ayurvedic Purity, Elevated',
    'description' => 'Shivara offers natural herbal products crafted with care to support wellness, purity, and everyday health.',
    'url' => env('APP_URL', 'https://theshivara.com'),
    'email' => 'Info@theshivara.com',
    'shop_email' => 'shop@theshivara.com',
    'phone' => '+91-9828385808',
    'whatsapp' => '919828385808',

    'address' => [
        'line1' => 'H-1-386-387, Agro Food Park',
        'line2' => 'Udyog Vihar, RIICO Industrial Area',
        'city' => 'Sri Ganganagar',
        'state' => 'Rajasthan',
        'pincode' => '335001',
        'country' => 'India',
    ],

    'social' => [
        'instagram' => 'https://www.instagram.com/shivarawellness',
        'facebook' => 'https://www.facebook.com/profile.php?id=61588139999665',
        'twitter' => 'https://twitter.com/theshivara',
        'youtube' => 'https://youtube.com/@theshivara',
    ],

    // Contact form email
    'contact_email' => 'Contact@theshivara.com',

    // Shipping
    'free_shipping_threshold' => 999,
    'standard_rate' => 50,
    'express_rate' => 149,
    'cod_charge' => 49,
    'standard_days' => '5-7 business days',
    'express_days' => '2-3 business days',

    // Free Gift Product (above specific amount)
    'free_gift_enabled' => true,
    'free_gift_threshold' => 1499, // Cart amount above which free product is added
    'free_gift_product_id' => null, // Set to a product ID, or null for default
    'free_gift_name' => 'Herbal Immunity Booster (Sample)',
    'free_gift_image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=100&h=100&fit=crop',

    // GST
    'gst_rates' => [0, 5, 12, 18, 28],

    'indian_states' => [
        'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
        'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand',
        'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur',
        'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab',
        'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura',
        'Uttar Pradesh', 'Uttarakhand', 'West Bengal',
        'Andaman and Nicobar Islands', 'Chandigarh',
        'Dadra and Nagar Haveli and Daman and Diu',
        'Delhi', 'Jammu and Kashmir', 'Ladakh', 'Lakshadweep', 'Puducherry',
    ],
];
