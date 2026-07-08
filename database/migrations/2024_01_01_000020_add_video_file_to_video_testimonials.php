<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('video_testimonials', function (Blueprint $table) {
            $table->string('video_file')->nullable()->after('thumbnail');
        });
    }

    public function down(): void
    {
        Schema::table('video_testimonials', function (Blueprint $table) {
            $table->dropColumn('video_file');
        });
    }
};
