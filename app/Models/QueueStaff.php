<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class QueueStaff extends Model
{
    protected $table = 'queue_staff';

    protected $fillable = [
        'queue_location_id', 'queue_counter_id', 'name', 'username', 'password', 'is_active',

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $hidden = ['password'];

    {
        // Only hash if not already hashed
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }

    public function verifyPassword(string $password): bool
        return Hash::check($password, $this->password);
    }

    public function location()
        return $this->belongsTo(QueueLocation::class, 'queue_location_id');
    }
    public function counter()
    {
        return $this->belongsTo(QueueCounter::class, 'queue_counter_id');
    }
}
