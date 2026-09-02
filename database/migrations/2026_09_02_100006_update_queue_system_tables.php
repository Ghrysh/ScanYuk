<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('queue_locations', function (Blueprint $table) {
            $table->integer('daily_quota')->nullable()->after('operational_hours');
        });

        Schema::table('queue_staff', function (Blueprint $table) {
            $table->dropColumn('pin');
            $table->string('username')->unique()->after('name');
            $table->string('password')->after('username');
        });
    }

    public function down(): void
    {
        Schema::table('queue_locations', function (Blueprint $table) {
            $table->dropColumn('daily_quota');
        });

        Schema::table('queue_staff', function (Blueprint $table) {
            $table->string('pin')->after('name');
            $table->dropColumn(['username', 'password']);
        });
    }
};
