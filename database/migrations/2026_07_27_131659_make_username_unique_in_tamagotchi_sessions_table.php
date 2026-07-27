<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Bersihkan duplikat terlebih dahulu sebelum membuat unique constraint
        $duplicates = DB::select("
            SELECT username
            FROM tamagotchi_sessions
            GROUP BY username
            HAVING COUNT(*) > 1
        ");

        foreach ($duplicates as $duplicate) {
            $username = $duplicate->username;
            
            // Ambil ID session pertama (yang akan dipertahankan)
            $firstSessionId = DB::table('tamagotchi_sessions')
                ->where('username', $username)
                ->orderBy('id', 'asc')
                ->value('id');

            if ($firstSessionId) {
                // Hapus sisa duplikatnya
                DB::table('tamagotchi_sessions')
                    ->where('username', $username)
                    ->where('id', '!=', $firstSessionId)
                    ->delete();
            }
        }

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
