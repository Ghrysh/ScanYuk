<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ar_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('ar_type');
            $table->string('file_path');
            $table->string('bgm_path')->nullable();
            $table->text('narration')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ar_templates');
    }
};