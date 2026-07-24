<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TamagotchiSession extends Model
{
    protected $fillable = [
        'qr_code_id',
        'username',
        'password',
        'exp_points',
        'last_lat',
        'last_lon',
        'total_scans',
        'last_active_at',
    ];

    protected $casts = [
        'exp_points' => 'float',
        'last_active_at' => 'datetime',
    ];

    public function qrCode()
    {
        return $this->belongsTo(QrCode::class, 'qr_code_id');
    }

    public function journeys()
    {
        return $this->hasMany(TamagotchiJourney::class, 'session_id');
    }
}
