<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->text('ingredients')->nullable();
            $table->text('how_to_use')->nullable();
            $table->text('benefits')->nullable();
            $table->decimal('mrp', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->string('sku')->nullable()->unique();
            $table->string('hsn_code')->nullable();
            $table->decimal('gst_rate', 5, 2)->default(18.00);
            $table->decimal('weight', 8, 2)->nullable()->comment('in grams');
            $table->integer('stock')->default(0);
            $table->integer('low_stock_alert')->default(5);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('url');
            $table->string('alt')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('mrp', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->integer('stock')->default(0);
            $table->string('sku')->nullable()->unique();
            $table->decimal('weight', 8, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
    }
};
