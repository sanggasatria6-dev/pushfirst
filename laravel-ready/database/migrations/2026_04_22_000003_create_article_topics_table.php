<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_topics', function (Blueprint $table) {
            $table->id();
            $table->string('keyword')->unique();
            $table->string('category', 40);
            $table->string('search_intent', 30);
            $table->string('language', 12)->default('id');
            $table->string('country_code', 2)->default('ID');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_generated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_topics');
    }
};
