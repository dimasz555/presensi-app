<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperLeaveRequest
 */
class LeaveRequest extends Model
{
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'reason',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Unknown',
        };
    }

    // Helper untuk status color
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'bg-warning-secondary text-warning-pressed',
            'approved' => 'bg-success-secondary text-success-main',
            'rejected' => 'bg-danger-secondary text-danger-main',
            default => 'bg-custom-gray-30 text-custom-gray-60',
        };
    }

    // Helper untuk durasi
    public function getDurationAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}
