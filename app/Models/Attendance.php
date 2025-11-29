<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @mixin IdeHelperAttendance
 */
class Attendance extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'check_in_lat',
        'check_in_long',
        'check_out_lat',
        'check_out_long',
        'face_matched',
        'face_confidence',
        'auto_checkout'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'face_matched' => 'boolean',
        'face_confidence' => 'float',
        'auto_checkout' => 'boolean',
    ];

    public function getCheckoutTypeAttribute()
    {
        if (!$this->check_out) {
            return 'Belum Checkout';
        }

        return $this->auto_checkout ? 'Auto Checkout' : 'Manual Checkout';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('face_verification')->singleFile();
    }
}
