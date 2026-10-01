<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * კომპანიის თანამშრომლები.
     * სათარგმნი ველები ინახება JSON-ად: {"ka": "...", "en": "...", "ru": "..."}.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->json('first_name');
            $table->json('last_name');
            $table->json('position')->nullable();
            $table->json('bio')->nullable();
            $table->string('photo');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
