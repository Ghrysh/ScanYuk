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
        Schema::create('ar_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marker_id')->nullable()->constrained('markers')->nullOnDelete();
            $table->enum('type', ['template', 'gltf', 'blend']);
            $table->foreignId('template_id')->nullable()->constrained('templates')->nullOnDelete();
            $table->string('model_path')->nullable();
            $table->json('config')->nullable();
            $table->float('scale')->default(1.0);
            $table->json('position')->nullable();
            $table->json('rotation')->nullable();
            $table->string('status')->default('ready');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ar_projects');
    }
};
