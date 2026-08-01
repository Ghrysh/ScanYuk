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
        Schema::table('seo_recommendations', function (Blueprint $table) {
            $table->string('manual_status')->default('pending')->after('status');
            $table->string('ai_type')->default('manual')->after('manual_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo_recommendations', function (Blueprint $table) {
            $table->dropColumn(['manual_status', 'ai_type']);
        });
    }
};
