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

    /**
     * Hitung EXP saat ini berdasarkan waktu yang berlalu.
     * Bangun (06:00 - 21:00): EXP berkurang agar habis dalam 15 jam (~6.66/jam)
     * Tidur (21:00 - 06:00): EXP bertambah 1 poin per jam
     */
    public function calculateCurrentExp()
    {
        if (!$this->last_active_at) return $this->exp_points;

        $now = now();
        if ($this->last_active_at->greaterThanOrEqualTo($now)) return $this->exp_points;

        $exp = $this->exp_points;
        $time = clone $this->last_active_at;

        while ($time->lessThan($now)) {
            $nextHour = (clone $time)->addHour()->startOfHour();
            if ($nextHour->greaterThan($now)) {
                $nextHour = clone $now;
            }

            $hoursDiff = $time->diffInSeconds($nextHour) / 3600;
            $hourOfDay = $time->hour;

            if ($hourOfDay >= 21 || $hourOfDay < 6) {
                // Tidur: +1 per jam
                $exp += (1.0 * $hoursDiff);
            } else {
                // Bangun: -6.66 per jam
                $exp -= (6.666 * $hoursDiff);
            }

            $time = clone $nextHour;
        }

        return min(100, max(0, $exp));
    }

    /**
     * Update EXP dan waktu aktif terakhir.
     */
    public function syncDecay()
    {
        $this->exp_points = $this->calculateCurrentExp();
        $this->last_active_at = now();
        $this->save();
    }
}
