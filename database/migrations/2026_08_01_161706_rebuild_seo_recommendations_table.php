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
        Schema::dropIfExists('seo_recommendations');
        
        Schema::create('seo_recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('page_path');
            $table->string('category'); 
            $table->text('research_finding');
            $table->text('current_condition');
            $table->text('impact');
            $table->text('recommendation_text');
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
