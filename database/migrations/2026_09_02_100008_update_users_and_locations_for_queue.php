<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('queue_status')->default('none')->after('role');
        });

        Schema::table('queue_locations', function (Blueprint $table) {
            $table->boolean('has_booths')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('queue_status');
        });

        Schema::table('queue_locations', function (Blueprint $table) {
            $table->dropColumn('has_booths');
        });
    }
};
