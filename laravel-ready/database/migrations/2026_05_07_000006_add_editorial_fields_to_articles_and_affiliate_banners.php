<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->json('source_references')->nullable()->after('content_html');
        });

        Schema::table('affiliate_banners', function (Blueprint $table) {
            $table->string('image_url')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('source_references');
        });

        Schema::table('affiliate_banners', function (Blueprint $table) {
            $table->string('image_url')->nullable(false)->change();
        });
    }
};
