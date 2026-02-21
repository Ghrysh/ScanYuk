<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $fillable = [
        'user_id',
        'uuid',
        'title',
        'image_path',
        'narration',
        'qr_image_path',
        'status',
        'scan_count',
        'is_active',
    ];

    public function arAsset()
    {
        return $this->belongsTo(ArAsset::class, 'ar_asset_id');
    }
}
