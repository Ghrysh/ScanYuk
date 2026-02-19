<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $fillable = ['user_id', 'title', 'image_path', 'narration', 'scan_count', 'status'];
}
