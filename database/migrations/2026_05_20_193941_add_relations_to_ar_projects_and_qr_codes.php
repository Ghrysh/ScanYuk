<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan user_id ke ar_projects agar kita tahu siapa pemilik project marker tersebut
        Schema::table('ar_projects', function (Blueprint $table) {
            if (!Schema::hasColumn('ar_projects', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            }
        });

        // 2. Tambahkan ar_project_id ke qr_codes agar terhubung ke project marker Anda
        Schema::table('qr_codes', function (Blueprint $table) {
            if (!Schema::hasColumn('qr_codes', 'ar_project_id')) {
                $table->foreignId('ar_project_id')->nullable()->after('ar_asset_id')->constrained('ar_projects')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->dropForeign(['ar_project_id']);
            $table->dropColumn('ar_project_id');
        });

        Schema::table('ar_projects', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};