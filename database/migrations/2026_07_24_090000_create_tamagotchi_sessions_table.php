<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tamagotchi_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('qr_code_id');
            $table->string('phone', 20);
            $table->string('display_name', 100)->nullable();
            $table->float('exp_points')->default(100);
            $table->decimal('last_lat', 10, 7)->nullable();
            $table->decimal('last_lon', 10, 7)->nullable();
            $table->integer('total_scans')->default(1);
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();

            $table->unique(['qr_code_id', 'phone']);
            $table->foreign('qr_code_id')->references('id')->on('qr_codes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tamagotchi_sessions');
    }
};
