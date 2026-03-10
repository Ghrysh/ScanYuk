<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiConversion extends Model
{
    protected $fillable = ['user_id', 'status', 'progress', 'result_url'];
}
