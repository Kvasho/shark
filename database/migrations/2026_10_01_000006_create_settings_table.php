<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * საიტის ცვლადი პარამეტრები (გასაღები → მნიშვნელობა), მაგ. საკონტაქტო ტელეფონი და ელფოსტა.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // მიმდინარე მნიშვნელობები, რომლებიც აქამდე გვერდზე პირდაპირ ეწერა.
        DB::table('settings')->insert([
            ['key' => 'contact.phone', 'value' => '+995 32 200 00 00', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact.email', 'value' => 'hello@shark.ge', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
