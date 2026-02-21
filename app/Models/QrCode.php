<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $fillable = [
        'user_id',
        'uuid',
        'title',
        'ar_type',
        'image_path',
        'ar_asset_id',
        'bgm_path',
        'narration',
        'qr_image_path',
        'status',
        'scan_count',
    ];

    public function arAsset()
    {
        return $this->belongsTo(ArAsset::class, 'ar_asset_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}