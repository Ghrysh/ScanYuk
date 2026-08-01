<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_path',
        'target_keyword',
        'overall_score',
        'recommendations',
        'status',
    ];

    protected $casts = [
        'recommendations' => 'array',
    ];
}
