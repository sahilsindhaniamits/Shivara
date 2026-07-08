<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the unique index directly using raw SQL (works even with foreign keys)
        try {
            DB::statement('ALTER TABLE reviews DROP INDEX reviews_product_id_user_id_unique');
        } catch (\Exception $e) {
            // Index might not exist or have different name, try alternative
            try {
                Schema::table('reviews', function (Blueprint $table) {
                    $table->dropUnique('reviews_product_id_user_id_unique');
                });
            } catch (\Exception $e2) {
                // Already removed or doesn't exist - skip
            }
        }
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->unique(['product_id', 'user_id']);
        });
    }
};
