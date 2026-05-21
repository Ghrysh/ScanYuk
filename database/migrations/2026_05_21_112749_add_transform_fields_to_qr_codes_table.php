<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->string('scale')->default('1');
            $table->string('position')->default('[0,0,0]');
            $table->string('rotation')->default('[0,0,0]');
            $table->boolean('orbit_active')->default(0);
            $table->string('orbit_speed')->default('0.5');
            $table->string('orbit_radius')->default('1.5');
            $table->string('orbit_dir')->default('1');
            $table->string('anim_clip')->default('*');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            //
        });
    }
};
