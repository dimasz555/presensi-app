<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Location;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Absensi - Sajadadir')]

class Absensi extends Component
{
    use WithFileUploads;

    public $todayAttendance;
    public $canCheckIn = false;
    public $canCheckOut = false;
    public $currentLocation;
    public $userLatitude;
    public $userLongitude;
    public $distance;
    public $locationValid = false;
    public $faceImage;
    public $message;
    public $messageType = 'info';
    public $showCamera = false;
    public $isProcessing = false;

    public function mount()
    {
        if (!auth()->user()->hasRole('karyawan')) {
            abort(403, 'Unauthorized');
        }

        $this->loadTodayAttendance();
        $this->checkAbilityToAttend();
    }

    private function loadTodayAttendance()
    {
        $this->todayAttendance = Attendance::where('user_id', auth()->id())
            ->whereDate('date', Carbon::today())
            ->first();
    }

    private function checkAbilityToAttend()
    {
        $today = Carbon::today();
        $dayOfWeek = strtolower($today->format('l'));

        // Check if today is weekend (Saturday/Sunday)
        if (in_array($dayOfWeek, ['saturday', 'sunday'])) {
            $this->message = 'Hari ini adalah hari libur (Sabtu/Minggu). Presensi tidak tersedia.';
            $this->messageType = 'warning';
            $this->canCheckIn = false;
            $this->canCheckOut = false;
            return;
        }

        // Check if user has approved leave today
        $hasLeave = LeaveRequest::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->exists();

        if ($hasLeave) {
            $this->message = 'Anda sedang dalam masa izin yang telah disetujui. Presensi tidak diperlukan.';
            $this->messageType = 'info';
            $this->canCheckIn = false;
            $this->canCheckOut = false;
            return;
        }

        // Check if already checked in today
        if ($this->todayAttendance && $this->todayAttendance->check_in) {
            if ($this->todayAttendance->check_out) {
                $this->message = 'Anda sudah melakukan check-in dan check-out hari ini.';
                $this->messageType = 'success';
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

        if (!$this->currentLocation) {
            $this->message = 'Lokasi presensi belum dikonfigurasi. Hubungi administrator.';
            $this->messageType = 'error';
            $this->canCheckIn = false;
            $this->canCheckOut = false;
        }
    }

    public function validateLocation($latitude, $longitude)
    {
        $this->userLatitude = $latitude;
        $this->userLongitude = $longitude;

        if (!$this->currentLocation) {
            $this->message = 'Lokasi presensi belum dikonfigurasi.';
            $this->messageType = 'error';
            $this->locationValid = false;
            return;
        }

        $this->distance = $this->currentLocation->calculateDistance($latitude, $longitude);
        $this->locationValid = $this->currentLocation->isWithinRadius($latitude, $longitude);

        if ($this->locationValid) {
            $this->message = 'Lokasi Anda valid. Silakan lanjutkan verifikasi wajah.';
            $this->messageType = 'success';
        } else {
            $this->message = "Anda berada di luar jangkauan lokasi presensi. Jarak Anda: " . round($this->distance) . " meter dari lokasi.";
            $this->messageType = 'error';
        }

        $this->dispatch('locationValidated', $this->locationValid);
    }

    public function openCamera()
    {
        if (!$this->locationValid) {
            $this->message = 'Validasi lokasi terlebih dahulu sebelum membuka kamera.';
            $this->messageType = 'error';
            return;
        }

        $this->showCamera = true;
    }

    public function closeCamera()
    {
        $this->showCamera = false;
    }

    public function processCheckIn($faceImageData, $faceDescriptor)
    {
        if ($this->isProcessing) {
            return;
        }

        $this->isProcessing = true;

        try {
            // Validate prerequisites
            if (!$this->canCheckIn) {
                throw new \Exception('Anda tidak dapat melakukan check-in saat ini.');
            }

            if (!$this->locationValid) {
                throw new \Exception('Lokasi Anda tidak valid untuk presensi.');
            }

            // Verify face matching
            $confidence = $this->verifyFaceMatch($faceDescriptor);

            // Decode base64 image
            $imageData = str_replace('data:image/png;base64,', '', $faceImageData);
            $imageData = str_replace(' ', '+', $imageData);
            $image = base64_decode($imageData);

            // Determine status based on check-in time
            $now = Carbon::now();
            $dayOfWeek = strtolower($now->format('l'));
            $schedule = WorkSchedule::where('work_day', $dayOfWeek)->first();

            $status = 'hadir';
            if ($schedule) {
                $workStartTime = Carbon::parse($schedule->work_start_time);
                if ($now->gt($workStartTime)) {
                    $status = 'telat';
                }
            }

            // Create attendance record
            $attendance = Attendance::create([
                'user_id' => auth()->id(),
                'date' => Carbon::today(),
                'check_in' => $now,
                'status' => $status,
                'check_in_lat' => $this->userLatitude,
                'check_in_long' => $this->userLongitude,
                'face_matched' => true,
                'face_confidence' => $confidence,
            ]);

            // Save face image
            $tempPath = storage_path('app/temp/' . uniqid() . '.png');
            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }
            file_put_contents($tempPath, $image);
            $attendance->addMedia($tempPath)->toMediaCollection('face_verification');

            $this->loadTodayAttendance();
            $this->checkAbilityToAttend();
            $this->showCamera = false;
            $this->message = 'Check-in berhasil! Status: ' . ($status === 'hadir' ? 'Tepat Waktu' : 'Terlambat');
            $this->messageType = 'success';

            session()->flash('success', $this->message);
            $this->redirect('/presensi', navigate: true);
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->messageType = 'error';
        } finally {
            $this->isProcessing = false;
        }
    }

    public function processCheckOut($faceImageData, $faceDescriptor)
    {
        if ($this->isProcessing) {
            return;
        }

        $this->isProcessing = true;

        try {
            if (!$this->canCheckOut) {
                throw new \Exception('Anda tidak dapat melakukan check-out saat ini.');
            }

            if (!$this->locationValid) {
                throw new \Exception('Lokasi Anda tidak valid untuk presensi.');
            }

            // Verify face matching
            $confidence = $this->verifyFaceMatch($faceDescriptor);

            // Decode base64 image for logging
            $imageData = str_replace('data:image/png;base64,', '', $faceImageData);
            $imageData = str_replace(' ', '+', $imageData);
            $image = base64_decode($imageData);

            // Update attendance record
            $this->todayAttendance->update([
                'check_out' => Carbon::now(),
                'check_out_lat' => $this->userLatitude,
                'check_out_long' => $this->userLongitude,
            ]);

            $this->loadTodayAttendance();
            $this->checkAbilityToAttend();
            $this->showCamera = false;
            $this->message = 'Check-out berhasil!';
            $this->messageType = 'success';

            session()->flash('success', $this->message);
            $this->redirect('/presensi', navigate: true);
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->messageType = 'error';
        } finally {
            $this->isProcessing = false;
        }
    }

    public function verifyFaceMatch($capturedDescriptor)
    {
        $user = auth()->user();

        // Check if user has registered face
        if (!$user->face_embedding) {
            throw new \Exception('Anda belum mendaftarkan wajah. Silakan daftar wajah terlebih dahulu di menu Profil.');
        }

        // Get stored face descriptor
        $storedDescriptor = $user->face_embedding;

        if (!is_array($storedDescriptor) || count($storedDescriptor) !== 128) {
            throw new \Exception('Data wajah tidak valid. Silakan daftar ulang wajah Anda.');
        }

        // Calculate Euclidean distance between descriptors
        $distance = $this->calculateEuclideanDistance($storedDescriptor, $capturedDescriptor);

        // Threshold for face matching (lower is better)
        // Typically 0.6 is a good threshold
        $threshold = 0.6;

        if ($distance > $threshold) {
            throw new \Exception('Verifikasi wajah gagal. Wajah tidak cocok dengan data yang terdaftar.');
        }

        // Calculate confidence (inverse of distance, normalized)
        $confidence = max(0, min(1, 1 - ($distance / $threshold)));

        return $confidence;
    }

    private function calculateEuclideanDistance(array $descriptor1, array $descriptor2): float
    {
        $sum = 0;
        for ($i = 0; $i < count($descriptor1); $i++) {
            $diff = $descriptor1[$i] - $descriptor2[$i];
            $sum += $diff * $diff;
        }
        return sqrt($sum);
    }

    public function render()
    {
        return view('livewire.absensi');
    }
}
