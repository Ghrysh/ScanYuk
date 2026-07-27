<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TamagotchiJourney extends Model
{
    protected $fillable = [
        'session_id',
        'status_text',
        'mood',
        'exp_points',
        'lat',
        'lon',
        'location_name',
    ];

    protected $casts = [
        'exp_points' => 'float',
    ];

    public function session()
    {
        return $this->belongsTo(TamagotchiSession::class, 'session_id');
    }
}
