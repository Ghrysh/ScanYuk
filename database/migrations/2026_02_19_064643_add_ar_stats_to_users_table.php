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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('image')->default(0)->after('role');
            $table->integer('voice')->default(0)->after('image');
            $table->integer('scan')->default(0)->after('voice');
            $table->string('status')->default('active')->after('scan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['image', 'voice', 'scan', 'status']);
        });
    }
};
