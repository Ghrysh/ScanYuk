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
        Schema::table('tamagotchi_sessions', function (Blueprint $table) {
            // Hapus index lama
            $table->dropUnique(['qr_code_id', 'username']);
            // Tambahkan index unique yang baru (global per username)
            $table->unique('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tamagotchi_sessions', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->unique(['qr_code_id', 'username']);
        });
    }
};
