<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueTicket extends Model
{
    protected $fillable = [
        'queue_location_id', 'queue_service_id', 'queue_counter_id', 'queue_number',
        'customer_name', 'customer_phone', 'status', 'called_at', 'serving_at', 'completed_at', 'date',
    ];

    protected $casts = [
        'called_at' => 'datetime',
        'serving_at' => 'datetime',
        'completed_at' => 'datetime',
        'date' => 'date',
    ];

    public const STATUS_WAITING = 'waiting';
    public const STATUS_CALLED = 'called';
    public const STATUS_SERVING = 'serving';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_SKIPPED = 'skipped';
    public const STATUS_NO_SHOW = 'no_show';

    public function location()
    {
        return $this->belongsTo(QueueLocation::class, 'queue_location_id');
    }

    public function service()
    {
        return $this->belongsTo(QueueService::class, 'queue_service_id');
    }

    public function counter()
    {
        return $this->belongsTo(QueueCounter::class, 'queue_counter_id');
    }

    public function getPositionInQueue(): int
    {
        return self::where('queue_service_id', $this->queue_service_id)
            ->where('date', $this->date)
            ->where('status', self::STATUS_WAITING)
            ->where('id', '<', $this->id)
            ->count() + 1;
    }

    public function getEstimatedWaitMinutes(): int
    {
        $position = $this->getPositionInQueue();
        $duration = $this->service->estimated_duration_minutes ?? 10;
        return max(0, ($position - 1) * $duration);
    }

    public function getWaitDurationMinutes(): ?int
    {
        if (!$this->serving_at) return null;
        return (int) $this->created_at->diffInMinutes($this->serving_at);
    }

    public function getServiceDurationMinutes(): ?int
    {
        if (!$this->serving_at || !$this->completed_at) return null;
        return (int) $this->serving_at->diffInMinutes($this->completed_at);
    }
}
