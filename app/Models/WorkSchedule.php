<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperWorkSchedule
 */
class WorkSchedule extends Model
{
    protected $fillable = [
        'work_day',
        'work_start_time',
        'work_end_time',
    ];

    protected $casts = [
        'work_start_time' => 'datetime:H:i',
        'work_end_time' => 'datetime:H:i',
    ];
}
