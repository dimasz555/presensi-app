<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperSalaryComponent
 */
class SalaryComponent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'payroll_id',
        'type',
        'name',
        'amount'
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    protected static function booted()
    {
        // Recalculate payroll setelah create/update/delete
        static::created(function ($component) {
            $component->payroll->recalculate();
        });

        static::updated(function ($component) {
            $component->payroll->recalculate();
        });

        static::deleted(function ($component) {
            $component->payroll->recalculate();
        });

        static::restored(function ($component) {
            $component->payroll->recalculate();
        });
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}
