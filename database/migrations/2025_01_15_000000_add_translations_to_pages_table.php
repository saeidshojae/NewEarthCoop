<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // Add JSON columns for translations
            $table->json('title_translations')->nullable()->after('title');
            $table->json('content_translations')->nullable()->after('content');
            $table->json('meta_title_translations')->nullable()->after('meta_title');
            $table->json('meta_description_translations')->nullable()->after('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'title_translations',
                'content_translations',
                'meta_title_translations',
                'meta_description_translations'
            ]);
        });
    }
};


