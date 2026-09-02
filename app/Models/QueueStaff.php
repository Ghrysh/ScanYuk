<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class QueueStaff extends Model
{
    protected $table = 'queue_staff';

    protected $fillable = [
        'queue_location_id', 'queue_counter_id', 'name', 'pin', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $hidden = ['pin'];

    public function setPinAttribute($value)
    {
        // Only hash if not already hashed
        if ($value && !str_starts_with($value, '$2y$')) {
            $this->attributes['pin'] = Hash::make($value);
        } else {
            $this->attributes['pin'] = $value;
        }
    }

    public function verifyPin(string $pin): bool
    {
        return Hash::check($pin, $this->pin);
    }

    public function location()
    {
        return $this->belongsTo(QueueLocation::class, 'queue_location_id');
    }

    public function counter()
    {
        return $this->belongsTo(QueueCounter::class, 'queue_counter_id');
    }
}
