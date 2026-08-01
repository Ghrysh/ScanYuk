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
        Schema::create('page_seo_contents', function (Blueprint $table) {
            $table->id();
            $table->string('page_path')->unique(); // e.g., '/', '/pricing', '/faq'
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('h1_heading')->nullable();
            $table->json('faq_schema')->nullable(); // Store JSON-LD for FAQ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_seo_contents');
    }
};
