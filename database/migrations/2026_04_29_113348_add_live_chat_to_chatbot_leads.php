<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('chatbot_leads', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id')->nullable()->after('user_id');
            $table->string('live_chat_status')->default('none')->after('admin_id'); 
        });
    }
    public function down() {
        Schema::table('chatbot_leads', function (Blueprint $table) {
            $table->dropColumn(['admin_id', 'live_chat_status']);
        });
    }
};