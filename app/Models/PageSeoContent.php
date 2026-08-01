<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSeoContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_path',
        'meta_title',
        'meta_description',
        'h1_heading',
        'faq_schema',
    ];

    protected $casts = [
        'faq_schema' => 'array',
    ];
}
