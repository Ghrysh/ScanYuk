<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('queue_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('queue_service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('queue_counter_id')->nullable()->constrained()->nullOnDelete();
            $table->string('queue_number');
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('status')->default('waiting');
            $table->timestamp('called_at')->nullable();
            $table->timestamp('serving_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->date('date')->index();
            $table->timestamps();

            $table->index(['queue_service_id', 'date']);
            $table->index(['queue_location_id', 'date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queue_tickets');
    }
};
