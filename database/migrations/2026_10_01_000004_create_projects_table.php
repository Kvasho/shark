<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * შესრულებული პროექტები და მათი გალერეა (სურათები და ვიდეოები).
     * სათარგმნი ველები JSON-ად: {"ka": "...", "en": "...", "ru": "..."}.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('title');
            $table->json('category');
            $table->json('location')->nullable();
            $table->json('excerpt')->nullable();
            $table->json('description')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->unsignedInteger('area')->nullable(); // მ²
            $table->string('cover');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('project_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('type', 10); // image | video
            $table->string('path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_media');
        Schema::dropIfExists('projects');
    }
};
