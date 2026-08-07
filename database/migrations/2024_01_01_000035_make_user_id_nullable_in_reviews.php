<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Find and drop any unique index involving user_id
        $uniqueIndexes = DB::select("
            SELECT DISTINCT INDEX_NAME 
            FROM INFORMATION_SCHEMA.STATISTICS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'reviews' 
            AND NON_UNIQUE = 0 
            AND INDEX_NAME != 'PRIMARY'
            AND COLUMN_NAME IN ('product_id', 'user_id')
        ");

        $droppedIndexes = [];
        foreach ($uniqueIndexes as $idx) {
            if (!in_array($idx->INDEX_NAME, $droppedIndexes)) {
                DB::statement("ALTER TABLE reviews DROP INDEX `{$idx->INDEX_NAME}`");
                $droppedIndexes[] = $idx->INDEX_NAME;
            }
        }

        // 2. Find and drop any foreign key on user_id
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'reviews' 
            AND COLUMN_NAME = 'user_id' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE reviews DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        // 3. Make user_id nullable
        DB::statement('ALTER TABLE reviews MODIFY user_id BIGINT UNSIGNED NULL');

        // 4. Set orphaned user_id values to NULL (users that don't exist anymore)
        DB::statement('UPDATE reviews SET user_id = NULL WHERE user_id IS NOT NULL AND user_id NOT IN (SELECT id FROM users)');

        // 5. Re-add foreign key with nullOnDelete
        DB::statement('ALTER TABLE reviews ADD CONSTRAINT reviews_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the FK we added
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'reviews' 
            AND COLUMN_NAME = 'user_id' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE reviews DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        DB::statement('ALTER TABLE reviews MODIFY user_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE reviews ADD CONSTRAINT reviews_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE reviews ADD UNIQUE INDEX reviews_product_id_user_id_unique (product_id, user_id)');
    }
};
