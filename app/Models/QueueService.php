<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueService extends Model
{
    protected $fillable = [
        'queue_location_id', 'name', 'prefix', 'estimated_duration_minutes', 'daily_quota', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function location()
    {
        return $this->belongsTo(QueueLocation::class, 'queue_location_id');
    }

    public function tickets()
    {
        return $this->hasMany(QueueTicket::class);
    }

    public function todayTickets()
    {
        return $this->tickets()->where('date', now()->toDateString());
    }

    public function getTodayTicketCount(): int
    {
        return $this->todayTickets()->count();
    }

    public function isQuotaFull(): bool
    {
        if (empty($this->daily_quota) || $this->daily_quota <= 0) return false;
        return $this->getTodayTicketCount() >= $this->daily_quota;
    }

    public function generateNextQueueNumber(): string
    {
        $lastTicket = $this->todayTickets()
            ->orderByRaw("CAST(SUBSTRING(queue_number FROM '[0-9]+') AS INTEGER) DESC")
            ->first();

        if ($lastTicket) {
            $lastNum = (int) preg_replace('/[^0-9]/', '', $lastTicket->queue_number);
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }

        return $this->prefix . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }
}
