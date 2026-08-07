<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')->where('key', 'marquee_text')->update([
            'value' => 'Pure Herbs — No Chemicals,Free Delivery — On orders above ₹999,Lab Tested — GMP Certified,5000+ Happy Customers,Secure Payments',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->where('key', 'marquee_text')->update([
            'value' => 'Free Shipping Pan-India,10% Off — Code: WOW10,100% Natural Ayurvedic,GMP Certified Lab Tested',
        ]);
    }
};
