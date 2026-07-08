<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('video_url'); // YouTube or Instagram URL
            $table->string('video_type')->default('youtube'); // youtube, instagram, direct
            $table->string('thumbnail')->nullable(); // uploaded thumbnail image
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->tinyInteger('rating')->default(5);
            $table->boolean('is_verified')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_testimonials');
    }
};
