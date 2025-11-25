<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia, FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'position_id',
        'gender',
        'basic_salary',
        'late_penalty_per_minute',
        'phone',
        'face_embedding',
        'face_registered_at',
        'status',
    ];

    protected $casts = [
        'face_embedding' => 'array',
        'face_registered_at' => 'datetime',
        'late_penalty_per_minute' => 'integer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Method untuk hitung total menit terlambat dalam bulan tertentu
    public function getTotalLateMinutesInMonth(int $month, int $year): int
    {
        $attendances = $this->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', 'telat')
            ->whereNotNull('check_in')
            ->get();

        $totalLateMinutes = 0;

        foreach ($attendances as $attendance) {
            $dayOfWeek = strtolower(\Carbon\Carbon::parse($attendance->date)->format('l'));

            $schedule = \App\Models\WorkSchedule::where('work_day', $dayOfWeek)->first();

            if ($schedule) {
                $dateString = \Carbon\Carbon::parse($attendance->date)->format('Y-m-d');
                $timeString = \Carbon\Carbon::parse($schedule->work_start_time)->format('H:i:s');

                $scheduledStart = \Carbon\Carbon::parse($dateString . ' ' . $timeString);
                $actualCheckIn = \Carbon\Carbon::parse($attendance->check_in);

                // Hanya hitung jika terlambat
                if ($actualCheckIn->gt($scheduledStart)) {
                    $lateMinutes = abs($scheduledStart->diffInMinutes($actualCheckIn));
                    $totalLateMinutes += $lateMinutes;
                }
            }
        }

        return $totalLateMinutes;
    }

    // Method untuk hitung total denda keterlambatan
    public function getTotalLatePenaltyInMonth(int $month, int $year): int
    {
        $totalLateMinutes = $this->getTotalLateMinutesInMonth($month, $year);
        return abs($totalLateMinutes * ($this->late_penalty_per_minute ?? 0));
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Hanya super_admin dan admin yang bisa akses
        return $this->hasAnyRole(['super_admin', 'admin']);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }
}
