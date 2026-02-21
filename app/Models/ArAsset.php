<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArAsset extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'file_path',
        'is_public',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function qrCodes()
    {
        return $this->hasMany(QrCode::class, 'ar_asset_id');
    }
}