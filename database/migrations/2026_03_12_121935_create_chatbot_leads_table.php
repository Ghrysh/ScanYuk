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
        Schema::create('chatbot_leads', function (Blueprint $table) {
            $table->id();
            $table->string('topic_context')->nullable();
            $table->string('contact_info');
            $table->text('last_message')->nullable();
            $table->enum('status', ['pending', 'contacted'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_leads');
    }
};
