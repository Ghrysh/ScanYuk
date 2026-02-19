<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingPackage extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'features' => 'array',
        'is_popular' => 'boolean',
    ];
}