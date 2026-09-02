<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('queue_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_location_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('prefix', 5);
            $table->integer('estimated_duration_minutes')->default(10);
            $table->integer('daily_quota')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queue_services');
    }
};
