<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * სერვისების გვერდის "როგორ ვმუშაობთ" ნაბიჯები და მათი სურათები.
     * სათარგმნი ველები JSON-ად: {"ka": "...", "en": "...", "ru": "..."}.
     */
    public function up(): void
    {
        Schema::create('service_steps', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('service_step_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_step_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_step_images');
        Schema::dropIfExists('service_steps');
    }
};
