<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueCounter extends Model
{
    protected $fillable = [
        'queue_location_id', 'name', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function location()
    {
        return $this->belongsTo(QueueLocation::class, 'queue_location_id');
    }

    public function staff()
    {
        return $this->hasMany(QueueStaff::class);
    }

    public function tickets()
    {
        return $this->hasMany(QueueTicket::class);
    }

    public function currentServingTicket()
    {
        return $this->tickets()
            ->where('date', now()->toDateString())
            ->whereIn('status', ['called', 'serving'])
            ->latest('called_at')
            ->first();
    }
}
