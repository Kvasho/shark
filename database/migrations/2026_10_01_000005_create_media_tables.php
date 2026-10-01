<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * მედიის გვერდი: ფოტო გალერეა და ვიდეოები (სათაური სამ ენაზე).
     */
    public function up(): void
    {
        Schema::create('media_photos', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->timestamps();
        });

        Schema::create('media_videos', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_videos');
        Schema::dropIfExists('media_photos');
    }
};
