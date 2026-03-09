<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    use HasFactory;

    protected $fillable = ['session_id', 'ip_address', 'date', 'page_journey'];

    protected $casts = [
        'page_journey' => 'array',
    ];
}