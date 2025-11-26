<?php

namespace App\Livewire;

use App\Models\Attendance;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Layout('layouts.app')]
#[Title('Presensi - Sajadadir')]

class Dashboard extends Component
{
    public $todayAttendance;
    public $monthStats;
    public $recentActivities;
    public $userName;
    public $userPosition;

    public function mount()
    {
        // Check if user has karyawan role
        if (!auth()->user()->hasRole('karyawan')) {
            abort(403, 'Unauthorized');
        }

        $this->userName = auth()->user()->name;
        $this->userPosition = auth()->user()->position?->name ?? 'Staff';

        $this->loadTodayAttendance();
        $this->loadMonthStats();
        $this->loadRecentActivities();
    }

    private function loadTodayAttendance()
    {
        $this->todayAttendance = Attendance::where('user_id', auth()->id())
            ->whereDate('date', Carbon::today())
            ->first();
    }

    private function loadMonthStats()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $attendances = Attendance::where('user_id', auth()->id())
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();

        $this->monthStats = [
            'hadir' => $attendances->where('status', 'hadir')->count(),
            'telat' => $attendances->where('status', 'telat')->count(),
            'izin' => $attendances->where('status', 'izin')->count(),
            'sakit' => $attendances->where('status', 'sakit')->count(),
            'alpha' => $attendances->where('status', 'alpha')->count(),
        ];
    }

    private function loadRecentActivities()
    {
        $this->recentActivities = Attendance::where('user_id', auth()->id())
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($attendance) {
                $type = 'Absensi';
                if ($attendance->check_in && !$attendance->check_out) {
                    $type = 'Check In';
                } elseif ($attendance->check_out) {
                    $type = 'Check Out';
                }

                return (object) [
                    'type' => $type,
                    'date' => $attendance->date,
                    'time' => $attendance->check_in ?
                        Carbon::parse($attendance->check_in)->format('H:i') : ($attendance->check_out ? Carbon::parse($attendance->check_out)->format('H:i') : '-'),
                    'status' => $attendance->status_label,
                    'status_class' => $attendance->status_color,
                    'face_matched' => $attendance->face_matched,
                ];
            });
    }

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/login');
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
