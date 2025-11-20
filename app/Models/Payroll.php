<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'user_id',
        'period_month',
        'period_year',
        'basic_salary',
        'total_bonus',
        'total_deductions',
        'net_salary',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salaryComponents()
    {
        return $this->hasMany(SalaryComponent::class);
    }
}
