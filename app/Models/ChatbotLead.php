<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotLead extends Model
{
    protected $fillable = ['user_id', 'ip_address', 'topic_context', 'contact_info', 'last_message', 'chat_history', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}