<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop unique constraint if it exists (try different possible index names)
        $indexes = collect(DB::select("SHOW INDEX FROM reviews WHERE Non_unique = 0"))
            ->pluck('Key_name')
            ->unique()
            ->filter(fn($name) => $name !== 'PRIMARY')
            ->toArray();

        Schema::table('reviews', function (Blueprint $table) use ($indexes) {
            foreach ($indexes as $indexName) {
                if (str_contains($indexName, 'product_id') && str_contains($indexName, 'user_id')) {
                    $table->dropIndex($indexName);
                    break;
                }
            }
        });

        // Make user_id nullable and update foreign key
        Schema::table('reviews', function (Blueprint $table) {
            // Drop existing foreign key (try both naming conventions)
            try {
                $table->dropForeign(['user_id']);
            } catch (\Exception $e) {
                // Foreign key might have different name, try raw SQL
                try {
                    DB::statement('ALTER TABLE reviews DROP FOREIGN KEY reviews_user_id_foreign');
                } catch (\Exception $e2) {
                    // No foreign key to drop
                }
            }
        });

        // Modify column to nullable
        DB::statement('ALTER TABLE reviews MODIFY user_id BIGINT UNSIGNED NULL');

        // Re-add foreign key with nullOnDelete
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE reviews MODIFY user_id BIGINT UNSIGNED NOT NULL');

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique(['product_id', 'user_id']);
        });
    }
};
