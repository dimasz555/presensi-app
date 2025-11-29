<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Location;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
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

    // Attendance properties
    public $canCheckIn = false;
    public $canCheckOut = false;
    public $useManualCheckIn = false;
    public $currentLocation;
    public $userLatitude;
    public $userLongitude;
    public $distance;
    public $locationValid = false;
    public $showCamera = false;
    public $isProcessing = false;
    public $message;
    public $messageType = 'info';

    public function mount()
    {
        if (!auth()->user()->hasRole('karyawan')) {
            abort(403, 'Unauthorized');
        }

        $this->userName = auth()->user()->name;
        $this->userPosition = auth()->user()->position?->name ?? 'Staff';

        $this->loadTodayAttendance();
        $this->loadMonthStats();
        $this->loadRecentActivities();
        $this->checkAbilityToAttend();
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
            ->select('status')
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

    private function checkAbilityToAttend()
    {
        $today = Carbon::today();

        $dayOfWeek = strtolower($today->format('l'));
        $schedule = WorkSchedule::where('work_day', $dayOfWeek)->first();
        if (!$schedule) {
            $this->message = 'Hari ini adalah hari libur.';
            $this->messageType = 'warning';
            $this->canCheckIn = false;
            $this->canCheckOut = false;
            return;
        }



        // Check if user has registered face
        $user = auth()->user();
        if (!$user->face_embedding) {
            $this->message = 'Anda belum mendaftarkan wajah. Silakan daftarkan wajah di menu Profil terlebih dahulu.';
            $this->messageType = 'warning';
            $this->canCheckIn = false;
            $this->canCheckOut = false;
            return;
        }

        // Check if user has approved leave
        $hasLeave = LeaveRequest::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->exists();

        if ($hasLeave) {
            $this->message = 'Anda sedang dalam masa izin yang disetujui.';
            $this->messageType = 'info';
            $this->canCheckIn = false;
            $this->canCheckOut = false;
            return;
        }

        // Check attendance status
        if ($this->todayAttendance && $this->todayAttendance->check_in) {
            if ($this->todayAttendance->check_out) {
                $this->canCheckIn = false;
                $this->canCheckOut = false;
            } else {
                $this->canCheckIn = false;
                $this->canCheckOut = true;
            }
        } else {
            $this->canCheckIn = true;
            $this->canCheckOut = false;
        }

        // Get active location
        $this->currentLocation = Location::where('is_active', true)->first();

        if (!$this->currentLocation && ($this->canCheckIn || $this->canCheckOut)) {
            $this->message = 'Lokasi presensi belum dikonfigurasi. Hubungi administrator.';
            $this->messageType = 'error';
            $this->canCheckIn = false;
            $this->canCheckOut = false;
        }
    }

    public function validateAndOpenCamera($latitude, $longitude, $accuracy = null)
    {
        $this->userLatitude = $latitude;
        $this->userLongitude = $longitude;

        if (!$this->currentLocation) {
            $this->message = 'Lokasi presensi belum dikonfigurasi.';
            $this->messageType = 'error';
            return false;
        }

        // ANTI FAKE GPS: Validasi accuracy
        if ($accuracy !== null && $accuracy > 50) {
            $this->message = 'Akurasi lokasi tidak memadai (' . round($accuracy) . ' meter). Pastikan GPS aktif dan tidak menggunakan fake GPS.';
            $this->messageType = 'error';
            return false;
        }

        $this->distance = $this->currentLocation->calculateDistance($latitude, $longitude);
        $this->locationValid = $this->currentLocation->isWithinRadius($latitude, $longitude);

        if ($this->locationValid) {
            $this->showCamera = true;
            $this->message = '';
            return true;
        } else {
            $this->message = "Anda berada di luar jangkauan lokasi presensi. Jarak: " . round($this->distance) . " meter.";
            $this->messageType = 'error';
            return false;
        }
    }

    public function closeCamera()
    {
        $this->showCamera = false;
        $this->locationValid = false;
        $this->isProcessing = false;
        $this->dispatch('camera-closed');
    }

    public function processCheckIn($faceDescriptor)
    {
        try {
            if (!$this->canCheckIn) {
                throw new \Exception('Anda tidak dapat melakukan check-in saat ini.');
            }

            if (!$this->locationValid) {
                throw new \Exception('Lokasi tidak valid.');
            }

            // Verify face
            $confidence = $this->verifyFaceMatch($faceDescriptor);

            // Determine status
            $now = Carbon::now();
            $status = $this->determineAttendanceStatus($now);

            // Create attendance
            Attendance::create([
                'user_id' => auth()->id(),
                'date' => Carbon::today(),
                'check_in' => $now,
                'status' => $status,
                'check_in_lat' => $this->userLatitude,
                'check_in_long' => $this->userLongitude,
                'face_matched' => true,
                'face_confidence' => $confidence,
            ]);

            // Reset state
            $this->showCamera = false;
            $this->locationValid = false;
            $this->isProcessing = false;

            // Reload data
            $this->loadTodayAttendance();
            $this->loadRecentActivities();
            $this->checkAbilityToAttend();

            return [
                'success' => true,
                'message' => 'Check-in berhasil! Status: ' . ($status === 'hadir' ? 'Tepat Waktu' : 'Terlambat'),
            ];
        } catch (\Exception $e) {
            $this->isProcessing = false;

            Log::error('Check-in error', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function manualCheckIn($latitude, $longitude, $accuracy = null)
    {
        try {
            if (!$this->canCheckIn) {
                throw new \Exception('Anda tidak dapat melakukan check-in saat ini.');
            }

            if (!$this->currentLocation) {
                throw new \Exception('Lokasi presensi belum dikonfigurasi.');
            }

            // ANTI FAKE GPS
            if ($accuracy !== null && $accuracy > 50) {
                throw new \Exception('Akurasi lokasi tidak memadai (' . round($accuracy) . ' meter). Pastikan GPS aktif dan tidak menggunakan fake GPS.');
            }

            $distance = $this->currentLocation->calculateDistance($latitude, $longitude);
            $locationValid = $this->currentLocation->isWithinRadius($latitude, $longitude);

            if (!$locationValid) {
                throw new \Exception("Anda berada di luar jangkauan lokasi presensi. Jarak: " . round($distance) . " meter.");
            }

            // Determine status
            $now = Carbon::now();
            $status = $this->determineAttendanceStatus($now);

            // Create attendance WITHOUT face verification
            Attendance::create([
                'user_id' => auth()->id(),
                'date' => Carbon::today(),
                'check_in' => $now,
                'status' => $status,
                'check_in_lat' => $latitude,
                'check_in_long' => $longitude,
                'face_matched' => false, // Manual check-in
                'face_confidence' => null,
            ]);

            // Reload data
            $this->loadTodayAttendance();
            $this->loadRecentActivities();
            $this->checkAbilityToAttend();

            // Set success dengan delay untuk tampilkan notifikasi
            return [
                'success' => true,
                'message' => 'Check-in manual berhasil! Status: ' . ($status === 'hadir' ? 'Tepat Waktu' : 'Terlambat'),
            ];
        } catch (\Exception $e) {
            Log::error('Manual check-in error', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    // GANTI METHOD processCheckOut (TANPA FACE VERIFICATION):
    public function processCheckOut()
    {
        try {
            if (!$this->canCheckOut) {
                throw new \Exception('Anda tidak dapat melakukan absen keluar saat ini.');
            }

            if (!$this->locationValid) {
                throw new \Exception('Lokasi tidak valid.');
            }

            if (!$this->todayAttendance) {
                throw new \Exception('Data absen masuk tidak ditemukan.');
            }

            $this->todayAttendance->update([
                'check_out' => Carbon::now(),
                'check_out_lat' => $this->userLatitude,
                'check_out_long' => $this->userLongitude,
            ]);

            $successMessage = 'Absen Keluar berhasil!';

            // Reset state
            $this->showCamera = false;
            $this->locationValid = false;
            $this->isProcessing = false;

            session()->flash('success', $successMessage);

            // Dispatch success
            $this->dispatch('close-modal-success');
        } catch (\Exception $e) {
            $this->isProcessing = false;

            Log::error('Check-out error', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            $this->dispatch('show-error', message: $e->getMessage());
        }
    }

    public function quickCheckOut($latitude, $longitude, $accuracy = null)
    {
        try {
            if (!$this->canCheckOut) {
                throw new \Exception('Anda tidak dapat melakukan check-out saat ini.');
            }

            if (!$this->currentLocation) {
                throw new \Exception('Lokasi presensi belum dikonfigurasi.');
            }

            // ANTI FAKE GPS
            if ($accuracy !== null && $accuracy > 50) {
                throw new \Exception('Akurasi lokasi tidak memadai (' . round($accuracy) . ' meter). Pastikan GPS aktif dan tidak menggunakan fake GPS.');
            }

            $distance = $this->currentLocation->calculateDistance($latitude, $longitude);
            $locationValid = $this->currentLocation->isWithinRadius($latitude, $longitude);

            if (!$locationValid) {
                throw new \Exception("Anda berada di luar jangkauan lokasi presensi. Jarak: " . round($distance) . " meter.");
            }

            if (!$this->todayAttendance) {
                throw new \Exception('Data check-in tidak ditemukan.');
            }

            $this->todayAttendance->update([
                'check_out' => Carbon::now(),
                'check_out_lat' => $latitude,
                'check_out_long' => $longitude,
            ]);

            // Reload data
            $this->loadTodayAttendance();
            $this->loadRecentActivities();
            $this->checkAbilityToAttend();

            return [
                'success' => true,
                'message' => 'Check-out berhasil!',
            ];
        } catch (\Exception $e) {
            Log::error('Quick check-out error', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    private function determineAttendanceStatus($now)
    {
        $dayOfWeek = strtolower($now->format('l'));
        $schedule = WorkSchedule::where('work_day', $dayOfWeek)->first();

        $status = 'hadir';

        if ($schedule) {
            $workStartTime = Carbon::parse($schedule->work_start_time);
            if ($now->gt($workStartTime)) {
                $status = 'telat';
            }
        }

        return $status;
    }

    private function verifyFaceMatch($capturedDescriptor)
    {
        $user = auth()->user();

        if (!$user->face_embedding) {
            throw new \Exception('Anda belum mendaftarkan wajah.');
        }

        $storedDescriptor = $user->face_embedding;

        // Validasi format
        if (!is_array($storedDescriptor) || count($storedDescriptor) !== 128) {
            throw new \Exception('Data wajah tidak valid. Silakan daftar ulang di menu Profil.');
        }

        if (!is_array($capturedDescriptor) || count($capturedDescriptor) !== 128) {
            throw new \Exception('Data wajah yang di-capture tidak valid.');
        }

        // Calculate distance
        $distance = $this->calculateEuclideanDistance($storedDescriptor, $capturedDescriptor);

        // Threshold
        $threshold = config('attendance.face_match_threshold', 0.6);

        if ($distance > $threshold) {
            Log::warning('Face verification failed', [
                'user_id' => $user->id,
                'distance' => $distance,
                'threshold' => $threshold
            ]);
            throw new \Exception('Verifikasi wajah gagal. Wajah tidak cocok dengan data yang terdaftar.');
        }

        // Return confidence score
        return max(0, min(1, 1 - ($distance / $threshold)));
    }

    private function calculateEuclideanDistance(array $d1, array $d2): float
    {
        $sum = 0.0;
        $count = count($d1);

        for ($i = 0; $i < $count; $i++) {
            $diff = $d1[$i] - $d2[$i];
            $sum += $diff * $diff;
        }

        return sqrt($sum);
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
