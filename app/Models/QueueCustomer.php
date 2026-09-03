<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueueCustomer extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'points',
        'visits',
        'views',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
