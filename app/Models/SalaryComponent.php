<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryComponent extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'payroll_id',
        'type',
        'name',
        'amount'
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}
