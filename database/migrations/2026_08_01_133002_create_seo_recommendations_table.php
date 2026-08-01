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
        Schema::create('seo_recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('page_path');
            $table->string('target_keyword')->nullable();
            $table->integer('overall_score')->default(0);
            $table->json('recommendations'); // JSON holding all the AI recommendations
            $table->string('status')->default('pending'); // pending, applied
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_recommendations');
    }
};
