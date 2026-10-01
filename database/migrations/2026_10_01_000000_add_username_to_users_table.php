<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ადმინ პანელში შესვლა ხდება username-ით, ამიტომ email აღარ არის სავალდებულო.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->unique()->after('id');
            $table->string('email')->nullable()->change();
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn(['username', 'last_login_at']);
            $table->string('email')->nullable(false)->change();
        });
    }
};
