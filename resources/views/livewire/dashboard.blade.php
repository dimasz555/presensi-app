<div class="min-h-screen bg-custom-gray-20 pb-24" x-data="attendanceApp()">
    <!-- Header with Gradient -->
    <div class="bg-gradient-to-br from-primary to-secondary text-white px-6 pt-6 pb-24 rounded-b-[2rem]">
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-white/80 text-sm">Selamat Datang,</p>
                <h1 class="text-2xl font-bold">{{ $userName }}</h1>
                <p class="text-white/70 text-xs mt-1">{{ $userPosition }}</p>
            </div>
        </div>

        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm">{{ now()->locale('id')->isoFormat('dddd') }}</p>
                    <p class="text-xl font-bold">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-white/80 text-sm">Waktu</p>
                    <p class="text-xl font-bold" x-data="{ time: '{{ now()->format('H:i') }}' }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) }, 1000)" x-text="time"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="px-6 -mt-16">
        @if ($message)
            <div
                class="p-4 rounded-xl mb-4 flex items-start gap-3 {{ $messageType === 'error' ? 'bg-danger-secondary border border-danger-main' : ($messageType === 'warning' ? 'bg-warning-secondary border border-warning-main' : 'bg-info-focus border border-secondary') }}">
                <p
                    class="text-sm font-medium {{ $messageType === 'error' ? 'text-danger-pressed' : ($messageType === 'warning' ? 'text-warning-pressed' : 'text-secondary') }}">
                    {!! $message !!}
                </p>
            </div>
        @endif
        <!-- Status card -->
        <div class="bg-white rounded-2xl shadow-lg p-5 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-custom-gray-100">Status Hari Ini</h2>
                @if ($todayAttendance)
                    <span
                        class="text-success-main text-xs font-semibold px-3 py-1.5 rounded-full flex items-center gap-1">
                        @if ($todayAttendance->face_matched)
                            ✓
                        @endif
                        {{ $todayAttendance->status === 'hadir' ? 'Tepat Waktu' : ($todayAttendance->status === 'telat' ? 'Terlambat' : ucfirst($todayAttendance->status)) }}
                    </span>
                @else
                    <span
                        class="bg-danger-secondary text-danger-main text-xs font-semibold px-3 py-1.5 rounded-full">Belum
                        Absen</span>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="bg-success-secondary rounded-xl p-4">
                    <p class="text-2xl font-bold text-custom-gray-100">
                        {{ $todayAttendance?->check_in ? \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') : '--:--' }}
                    </p>
                    <p class="text-xs text-custom-gray-90 mt-1">
                        {{ $todayAttendance?->check_in ? \Carbon\Carbon::parse($todayAttendance->check_in)->diffForHumans() : 'Belum Absen Masuk' }}
                    </p>
                </div>
                <div class="bg-danger-secondary rounded-xl p-4">
                    <p class="text-2xl font-bold text-custom-gray-100">
                        {{ $todayAttendance?->check_out ? \Carbon\Carbon::parse($todayAttendance->check_out)->format('H:i') : '--:--' }}
                    </p>
                    <p class="text-xs text-custom-gray-90 mt-1">
                        {{ $todayAttendance?->check_out ? \Carbon\Carbon::parse($todayAttendance->check_out)->diffForHumans() : 'Belum Absen Keluar' }}
                    </p>
                </div>
            </div>

            @if ($canCheckIn)
                <div class="space-y-3">
                    @if ($hasFaceRegistered)
                        <button @click="autoValidateAndOpen('checkin')" :disabled="isGettingLocation"
                            class="w-full bg-gradient-to-r from-success-main to-green-600 text-white font-semibold py-4 rounded-xl shadow-lg disabled:opacity-50">
                            <span x-show="!isGettingLocation" class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                                </svg>
                                Absen Masuk (Face ID)
                            </span>
                            <span x-show="isGettingLocation" class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Mendapatkan Lokasi...
                            </span>
                        </button>
                    @endif

                    <button @click="manualCheckIn()" :disabled="isGettingLocation"
                        class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold py-{{ $hasFaceRegistered ? '3' : '4' }} rounded-xl shadow disabled:opacity-50">
                        <span x-show="!isGettingLocation" class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Absen Masuk (Lokasi)
                        </span>
                        <span x-show="isGettingLocation">Memproses...</span>
                    </button>
                </div>
            @elseif ($canCheckOut)
                <button @click="quickCheckOut()" :disabled="isGettingLocation"
                    class="w-full bg-gradient-to-r from-danger-main to-red-600 text-white font-semibold py-4 rounded-xl shadow-lg disabled:opacity-50">
                    <span x-show="!isGettingLocation" class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Absen Keluar
                    </span>
                    <span x-show="isGettingLocation">Memproses...</span>
                </button>
            @endif
        </div>

        @if ($showCamera)
            <div class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4" x-data="cameraApp()"
                x-init="init()">
                <div class="bg-white rounded-2xl w-full max-w-md shadow-xl overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold">Verifikasi Wajah</h2>
                            <button @click="forceClose()"
                                class="w-8 h-8 bg-custom-gray-20 rounded-lg flex items-center justify-center hover:bg-custom-gray-30">
                                ✕
                            </button>
                        </div>

                        <div wire:ignore class="relative rounded-xl overflow-hidden bg-gray-200 mb-4"
                            style="aspect-ratio: 4/3;">
                            <video x-ref="video" autoplay playsinline class="w-full h-full object-cover"></video>
                            <canvas x-ref="canvas" class="hidden"></canvas>

                            <div class="absolute top-3 left-1/2 -translate-x-1/2 z-10">
                                <span class="px-3 py-1.5 rounded-full text-xs font-medium text-white shadow-lg"
                                    :class="faceDetected ? 'bg-green-600' : 'bg-yellow-600'">
                                    <span x-text="faceDetected ? '✓ Wajah Terdeteksi' : '⚠ Posisikan Wajah'"></span>
                                </span>
                            </div>

                            <div x-show="!modelsLoaded || !cameraReady"
                                class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center gap-3">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"></div>
                                <p class="text-white text-sm" x-text="loadingMessage"></p>
                            </div>

                            <div x-show="isProcessing"
                                class="absolute inset-0 bg-black/80 flex flex-col items-center justify-center gap-3">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"></div>
                                <p class="text-white text-sm font-medium">Memproses...</p>
                            </div>
                        </div>

                        <p class="text-sm text-center text-custom-gray-60 mb-4">
                            Pastikan wajah terlihat jelas dan pencahayaan cukup
                        </p>

                        <div class="space-y-3">
                            <button @click="captureAndProcess()"
                                :disabled="isProcessing || !cameraReady || !faceDetected || !modelsLoaded"
                                class="w-full bg-primary text-white py-3.5 rounded-xl font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!isProcessing">Verifikasi</span>
                                <span x-show="isProcessing">Memproses...</span>
                            </button>
                            <button @click="forceClose()"
                                class="w-full bg-gray-200 text-gray-700 py-3.5 rounded-xl font-semibold">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats -->
        <div class="bg-white rounded-2xl shadow-lg p-5 mb-6">
            <h2 class="text-lg font-bold text-custom-gray-100 mb-4">Statistik Bulan Ini</h2>
            <div class="grid grid-cols-4 gap-3">
                <div class="text-center">
                    <p class="text-xl font-bold text-custom-gray-100">{{ $monthStats['hadir'] }}</p>
                    <p class="text-xs text-custom-gray-60">Hadir</p>
                </div>
                <div class="text-center">
                    <p class="text-xl font-bold text-custom-gray-100">{{ $monthStats['telat'] }}</p>
                    <p class="text-xs text-custom-gray-60">Telat</p>
                </div>
                <div class="text-center">
                    <p class="text-xl font-bold text-custom-gray-100">{{ $monthStats['izin'] }}</p>
                    <p class="text-xs text-custom-gray-60">Izin</p>
                </div>
                <div class="text-center">
                    <p class="text-xl font-bold text-custom-gray-100">{{ $monthStats['alpha'] }}</p>
                    <p class="text-xs text-custom-gray-60">Alpha</p>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-2xl shadow-lg p-5">
            <h2 class="text-lg font-bold text-custom-gray-100 mb-4">Aktivitas Terbaru</h2>
            <div class="space-y-3">
                @forelse($recentActivities as $activity)
                    <div class="flex items-start space-x-3 pb-3 border-b border-custom-gray-30 last:border-0">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-custom-gray-100">
                                {{ $activity->type }}
                                @if ($activity->has_check_in_out)
                                    @if ($activity->face_matched)
                                        <span class="text-success-main text-xs">(Face ID)</span>
                                    @else
                                        <span class="text-blue-500 text-xs">(Lokasi)</span>
                                    @endif
                                @endif
                            </p>
                            <p class="text-xs text-custom-gray-60">
                                {{ $activity->date->locale('id')->isoFormat('D MMM YYYY') }} • {{ $activity->time }}
                            </p>
                        </div>
                        <span class="text-xs font-medium px-2 py-1 rounded-full {{ $activity->status_class }}">
                            {{ $activity->status }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-custom-gray-60 text-sm">Belum ada aktivitas</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @include('components.bottom-nav')
</div>

<script>
    function attendanceApp() {
        return {
            isGettingLocation: false,

            async autoValidateAndOpen(type) {
                if (!navigator.geolocation) {
                    showToast('Geolocation tidak didukung oleh browser Anda', 'error');
                    return;
                }

                this.isGettingLocation = true;

                navigator.geolocation.getCurrentPosition(
                    async (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            const accuracy = position.coords.accuracy;

                            const valid = await @this.validateAndOpenCamera(lat, lng, accuracy);
                            this.isGettingLocation = false;

                            if (valid) {
                                window.__attendance_action = type;
                            }
                        },
                        (err) => {
                            this.isGettingLocation = false;
                            let msg = 'Gagal mendapatkan lokasi.';
                            if (err.code === err.PERMISSION_DENIED) {
                                msg = 'Izin lokasi ditolak. Mohon izinkan akses lokasi.';
                            }
                            showToast(msg, 'error');
                        }, {
                            enableHighAccuracy: true,
                            timeout: 20000,
                            maximumAge: 0
                        }
                );
            },

            async manualCheckIn() {
                if (!navigator.geolocation) {
                    showToast('Geolocation tidak didukung oleh browser Anda', 'error');
                    return;
                }

                this.isGettingLocation = true;

                navigator.geolocation.getCurrentPosition(
                    async (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            const accuracy = position.coords.accuracy;

                            const result = await @this.call('manualCheckIn', lat, lng, accuracy);
                            this.isGettingLocation = false;

                            if (result.success) {
                                showToast(result.message, 'success');
                                setTimeout(() => window.location.reload(), 2500);
                            } else {
                                showToast(result.message, 'error');
                            }
                        },
                        (err) => {
                            this.isGettingLocation = false;
                            let msg = 'Gagal mendapatkan lokasi.';
                            if (err.code === err.PERMISSION_DENIED) {
                                msg = 'Izin lokasi ditolak. Mohon izinkan akses lokasi.';
                            }
                            showToast(msg, 'error');
                        }, {
                            enableHighAccuracy: true,
                            timeout: 20000,
                            maximumAge: 0
                        }
                );
            },

            async quickCheckOut() {
                if (!navigator.geolocation) {
                    showToast('Geolocation tidak didukung oleh browser Anda', 'error');
                    return;
                }

                this.isGettingLocation = true;

                navigator.geolocation.getCurrentPosition(
                    async (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            const accuracy = position.coords.accuracy;

                            const result = await @this.call('quickCheckOut', lat, lng, accuracy);
                            this.isGettingLocation = false;

                            if (result.success) {
                                showToast(result.message, 'success');
                                setTimeout(() => window.location.reload(), 2500);
                            } else {
                                showToast(result.message, 'error');
                            }
                        },
                        (err) => {
                            this.isGettingLocation = false;
                            let msg = 'Gagal mendapatkan lokasi.';
                            if (err.code === err.PERMISSION_DENIED) {
                                msg = 'Izin lokasi ditolak. Mohon izinkan akses lokasi.';
                            }
                            showToast(msg, 'error');
                        }, {
                            enableHighAccuracy: true,
                            timeout: 20000,
                            maximumAge: 0
                        }
                );
            }
        }
    }

    function cameraApp() {
        return {
            stream: null,
            cameraReady: false,
            isProcessing: false,
            modelsLoaded: false,
            faceDetected: false,
            detectionLoop: null,
            loadingMessage: 'Memuat model AI...',

            async init() {
                await this.loadModels();
                await this.startCamera();
                this.startFaceDetection();
            },

            async loadModels() {
                try {
                    const modelPath = '/models';
                    await Promise.all([
                        faceapi.nets.tinyFaceDetector.loadFromUri(modelPath),
                        faceapi.nets.faceLandmark68Net.loadFromUri(modelPath),
                        faceapi.nets.faceRecognitionNet.loadFromUri(modelPath)
                    ]);
                    this.modelsLoaded = true;
                } catch (e) {
                    showToast('Gagal memuat model AI.', 'error');
                    setTimeout(() => this.forceClose(), 2000);
                }
            },

            async startCamera() {
                try {
                    this.loadingMessage = 'Mengakses kamera...';
                    this.stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: 'user',
                            width: {
                                ideal: 640
                            },
                            height: {
                                ideal: 480
                            }
                        }
                    });

                    this.$refs.video.srcObject = this.stream;

                    await new Promise((resolve) => {
                        this.$refs.video.onloadedmetadata = () => {
                            this.$refs.video.play();
                            resolve();
                        };
                    });

                    await new Promise(r => setTimeout(r, 300));
                    this.cameraReady = true;
                } catch (e) {
                    showToast('Gagal mengakses kamera.', 'error');
                    setTimeout(() => this.forceClose(), 2000);
                }
            },

            startFaceDetection() {
                const detect = async () => {
                    if (!this.cameraReady || !this.modelsLoaded || this.isProcessing) {
                        this.detectionLoop = requestAnimationFrame(detect);
                        return;
                    }

                    try {
                        const video = this.$refs.video;
                        if (video && video.readyState >= 2) {
                            const detection = await faceapi
                                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({
                                    inputSize: 224,
                                    scoreThreshold: 0.5
                                }));
                            this.faceDetected = !!detection;
                        }
                    } catch (err) {
                        // Silent error
                    }

                    this.detectionLoop = requestAnimationFrame(detect);
                };

                this.detectionLoop = requestAnimationFrame(detect);
            },

            async captureAndProcess() {
                if (this.isProcessing) return;

                if (!this.faceDetected) {
                    showToast('Wajah belum terdeteksi. Pastikan wajah terlihat jelas.', 'error');
                    return;
                }

                this.isProcessing = true;

                try {
                    const video = this.$refs.video;
                    const detection = await faceapi
                        .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (!detection) {
                        throw new Error('Gagal mendeteksi wajah. Silakan coba lagi.');
                    }

                    const descriptor = Array.from(detection.descriptor);
                    const result = await @this.call('processCheckIn', descriptor);

                    if (result.success) {
                        this.stopCamera();
                        showToast(result.message, 'success');
                        setTimeout(() => window.location.reload(), 2500);
                    } else {
                        this.isProcessing = false;
                        showToast(result.message, 'error');
                    }

                } catch (err) {
                    showToast(err.message || 'Gagal memproses wajah.', 'error');
                    this.isProcessing = false;
                }
            },

            forceClose() {
                this.stopCamera();
                @this.call('closeCamera');
            },

            stopCamera() {
                if (this.detectionLoop) {
                    cancelAnimationFrame(this.detectionLoop);
                    this.detectionLoop = null;
                }
                if (this.stream) {
                    this.stream.getTracks().forEach(t => t.stop());
                    this.stream = null;
                }
                this.cameraReady = false;
                this.faceDetected = false;
                this.isProcessing = false;
            }
        };
    }
</script>
