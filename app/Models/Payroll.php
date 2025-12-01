<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperPayroll
 */
class Payroll extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'period_month',
        'period_year',
        'basic_salary',
        'total_bonus',
        'total_deductions',
        'net_salary',
        'status',
        'file_path',
    ];

    protected static function booted()
    {
        // Hitung net_salary saat create (tanpa bonus/deduction dulu)
        static::creating(function ($payroll) {
            $payroll->net_salary = $payroll->basic_salary;
        });
    }

    // recalculate (dipanggil setelah salary component berubah)
    public function recalculate()
    {
        $this->total_bonus = $this->salaryComponents()
            ->where('type', 'bonus')
            ->sum('amount');

        $this->total_deductions = $this->salaryComponents()
            ->where('type', 'deduction')
            ->sum('amount');

        $this->net_salary = $this->basic_salary + $this->total_bonus - $this->total_deductions;

        $this->saveQuietly(); // Save tanpa trigger event
    }

    public function bonuses()
    {
        return $this->salaryComponents()->where('type', 'bonus');
    }

    // Get deduction components only
    public function deductions()
    {
        return $this->salaryComponents()->where('type', 'deduction');
    }

    // Helper untuk nama bulan
    public function getMonthNameAttribute()
    {
        return match ($this->period_month) {
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
            default => 'Tidak Diketahui',
        };
    }

    // Helper untuk periode
    public function getPeriodLabelAttribute()
    {
        return $this->month_name . ' ' . $this->period_year;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salaryComponents()
    {
        return $this->hasMany(SalaryComponent::class);
    }
}
