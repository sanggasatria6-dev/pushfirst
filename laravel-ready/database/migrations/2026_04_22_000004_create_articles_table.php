<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('article_topics')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('meta_description', 180);
            $table->text('excerpt')->nullable();
            $table->longText('content_html');
            $table->longText('source_prompt')->nullable();
            $table->string('generation_model')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
