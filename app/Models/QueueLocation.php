<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QueueLocation extends Model
{
    protected $fillable = [
        'user_id', 'uuid', 'name', 'address', 'operational_hours', 'ar_qr_code_id', 'is_active',
    ];

    protected $casts = [
        'operational_hours' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($location) {
            if (empty($location->uuid)) {
                $location->uuid = (string) Str::uuid();
            }
        });
    }

    // Tier limits: [role => max_locations]
    public const LOCATION_LIMITS = [
        'free' => 1,
        'starter' => 3,
        'professional' => 10,
        'business' => null, // unlimited
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->hasMany(QueueService::class)->orderBy('sort_order');
    }

    public function counters()
    {
        return $this->hasMany(QueueCounter::class);
    }

    public function staff()
    {
        return $this->hasMany(QueueStaff::class);
    }

    public function tickets()
    {
        return $this->hasMany(QueueTicket::class);
    }

    public function arQrCode()
    {
        return $this->belongsTo(QrCode::class, 'ar_qr_code_id');
    }

    public function todayTickets()
    {
        return $this->tickets()->where('date', now()->toDateString());
    }

    public function isOpen(): bool
    {
        if (!$this->operational_hours) return true;

        $dayKey = strtolower(now()->format('D')); // mon, tue, wed...
        $hours = $this->operational_hours[$dayKey] ?? null;

        if (!$hours || empty($hours['open']) || empty($hours['close'])) return false;

        $now = now()->format('H:i');
        return $now >= $hours['open'] && $now <= $hours['close'];
    }
}
