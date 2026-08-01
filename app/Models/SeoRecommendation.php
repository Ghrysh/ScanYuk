<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_path',
        'category',
        'research_finding',
        'current_condition',
        'impact',
        'recommendation_text',
        'status',
    ];
}
