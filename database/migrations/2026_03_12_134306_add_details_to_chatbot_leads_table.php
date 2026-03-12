<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('chatbot_leads', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->string('ip_address')->nullable()->after('user_id');
            $table->json('chat_history')->nullable()->after('last_message');
        });
    }

    public function down()
    {
        Schema::table('chatbot_leads', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'ip_address', 'chat_history']);
        });
    }
};