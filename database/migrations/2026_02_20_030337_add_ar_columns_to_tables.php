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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('total_scans')->default(0);
        });

        if (!Schema::hasTable('qr_codes')) {
            Schema::create('qr_codes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->uuid('uuid')->unique();
                $table->string('title');
                $table->string('image_path');
                $table->text('narration');
                $table->string('qr_image_path');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('total_scans');
        });
        Schema::dropIfExists('qr_codes');
    }
};
