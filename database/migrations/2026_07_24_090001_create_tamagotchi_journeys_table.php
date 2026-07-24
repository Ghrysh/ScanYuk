<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tamagotchi_journeys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->string('status_text', 255);
            $table->string('mood', 20)->default('senang');
            $table->float('exp_points')->default(100);
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lon', 10, 7)->nullable();
            $table->timestamps();

            $table->foreign('session_id')->references('id')->on('tamagotchi_sessions')->onDelete('cascade');
            $table->index('session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tamagotchi_journeys');
    }
};
