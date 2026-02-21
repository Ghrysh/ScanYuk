<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArTemplate extends Model
{
    protected $fillable = [
        'title',
        'ar_type',
        'file_path',
        'bgm_path',
        'narration',
    ];
}