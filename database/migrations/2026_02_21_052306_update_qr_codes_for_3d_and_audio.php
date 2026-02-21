<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->string('ar_type')->default('2d')->after('title');

            $table->foreignId('ar_asset_id')->nullable()->after('ar_type')->constrained('ar_assets')->onDelete('set null');
            
            $table->string('bgm_path')->nullable()->after('ar_asset_id'); 
        });
    }

    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->dropForeign(['ar_asset_id']);
            $table->dropColumn(['ar_type', 'ar_asset_id', 'bgm_path']);
        });
    }
};