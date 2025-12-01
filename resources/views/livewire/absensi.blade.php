<div class="min-h-screen bg-custom-gray-20 pb-24" x-data="attendanceApp()">
    <!-- Header -->
    <div class="bg-gradient-to-br from-primary to-secondary text-white px-6 pt-6 pb-20">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <a href="/presensi" wire:navigate
                    class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center hover:bg-white/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold">Presensi</h1>
                    <p class="text-white/70 text-sm">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="px-6 -mt-12">
        <!-- Message Alert -->
        @if ($message)
            <div
                class="mb-4 p-4 rounded-xl flex items-start gap-3 {{ $messageType === 'success' ? 'bg-success-secondary border border-success-main' : ($messageType === 'error' ? 'bg-danger-secondary border border-danger-main' : ($messageType === 'warning' ? 'bg-warning-secondary border border-warning-main' : 'bg-info-focus border border-secondary')) }}">
                <svg class="w-6 h-6 flex-shrink-0 {{ $messageType === 'success' ? 'text-success-main' : ($messageType === 'error' ? 'text-danger-main' : ($messageType === 'warning' ? 'text-warning-pressed' : 'text-secondary')) }}"
                    fill="currentColor" viewBox="0 0 20 20">
                    @if ($messageType === 'success')
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    @else
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    @endif
                </svg>
                <p
                    class="text-sm font-medium {{ $messageType === 'success' ? 'text-success-pressed' : ($messageType === 'error' ? 'text-danger-pressed' : ($messageType === 'warning' ? 'text-warning-pressed' : 'text-secondary')) }}">
                    {{ $message }}
                </p>
            </div>
        @endif

        <!-- Attendance Status Card -->
        @if ($todayAttendance)
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-custom-gray-100">Status Hari Ini</h2>
                    <span
                        class="text-success-main text-xs font-semibold px-3 py-1.5 rounded-full flex items-center gap-1">
                        @if ($todayAttendance->face_matched)
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                        {{ $todayAttendance->status === 'hadir' ? 'Tepat Waktu' : ($todayAttendance->status === 'telat' ? 'Terlambat' : ucfirst($todayAttendance->status)) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-success-secondary rounded-xl p-4">
                        <p class="text-sm text-success-pressed mb-1">Check In</p>
                        <p class="text-2xl font-bold text-custom-gray-100">
                            {{ $todayAttendance->check_in ? \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') : '--:--' }}
                        </p>
                    </div>
                    <div class="bg-danger-secondary rounded-xl p-4">
                        <p class="text-sm text-danger-pressed mb-1">Check Out</p>
                        <p class="text-2xl font-bold text-custom-gray-100">
                            {{ $todayAttendance->check_out ? \Carbon\Carbon::parse($todayAttendance->check_out)->format('H:i') : '--:--' }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Location Info Card -->
        @if ($currentLocation)
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <h2 class="text-lg font-bold text-custom-gray-100 mb-4">Lokasi Presensi</h2>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-primary-secondary rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-custom-gray-100">{{ $currentLocation->name }}</p>
                            <p class="text-xs text-custom-gray-60 mt-1">{{ $currentLocation->address }}</p>
                            <p class="text-xs text-custom-gray-60 mt-1">Radius: {{ $currentLocation->radius }} meter
                            </p>
                        </div>
                    </div>

                    @if ($distance !== null)
                        <div
                            class="p-3 rounded-lg {{ $locationValid ? 'bg-success-secondary' : 'bg-danger-secondary' }}">
                            <p
                                class="text-sm font-medium {{ $locationValid ? 'text-success-pressed' : 'text-danger-pressed' }}">
                                Jarak Anda: {{ round($distance) }} meter dari lokasi
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="space-y-4">
            @if (!$locationValid && ($canCheckIn || $canCheckOut))
                <button @click="getLocation()" :disabled="isGettingLocation"
                    class="w-full bg-gradient-to-r from-primary to-secondary text-white font-semibold py-4 rounded-xl shadow-lg hover:shadow-xl active:scale-[0.98] transition-all flex items-center justify-center gap-2 disabled:opacity-50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span x-show="!isGettingLocation">Validasi Lokasi</span>
                    <span x-show="isGettingLocation">Mendapatkan Lokasi...</span>
                </button>
            @endif

            @if ($locationValid && $canCheckIn && !$showCamera)
                <button wire:click="openCamera"
                    class="w-full bg-gradient-to-r from-success-main to-green-secondary text-white font-semibold py-4 rounded-xl shadow-lg hover:shadow-xl active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Check In
                </button>
            @endif

            @if ($locationValid && $canCheckOut && !$showCamera)
                <button wire:click="openCamera"
                    class="w-full bg-gradient-to-r from-danger-main to-red-600 text-white font-semibold py-4 rounded-xl shadow-lg hover:shadow-xl active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Check Out
                </button>
            @endif
        </div>

        <!-- Camera Modal -->
        @if ($showCamera)
            <div class="fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4" x-data="cameraApp()">
                <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-custom-gray-100">Verifikasi Wajah</h3>
                            <button wire:click="closeCamera"
                                class="w-8 h-8 bg-custom-gray-20 rounded-lg flex items-center justify-center hover:bg-custom-gray-30">
                                <svg class="w-5 h-5 text-custom-gray-90" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="relative aspect-[3/4] bg-custom-gray-20 rounded-2xl overflow-hidden mb-4">
                            <video x-ref="video" autoplay playsinline class="w-full h-full object-cover"></video>
                            <canvas x-ref="canvas" class="hidden"></canvas>

                            <!-- Face Detection Status -->
                            <div class="absolute top-4 left-1/2 -translate-x-1/2 px-4 py-2 rounded-full text-sm font-medium"
                                :class="faceDetected ? 'bg-success-main text-white' : 'bg-warning-main text-white'">
                                <span x-text="faceDetected ? '✓ Wajah Terdeteksi' : '⚠ Posisikan Wajah'"></span>
                            </div>

                            <!-- Loading Models -->
                            <div x-show="!modelsLoaded"
                                class="absolute inset-0 bg-black/70 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <div
                                        class="animate-spin rounded-full h-12 w-12 border-b-2 border-white mx-auto mb-3">
                                    </div>
                                    <p class="text-sm">Memuat AI...</p>
                                </div>
                            </div>
                        </div>

                        <p class="text-sm text-center text-custom-gray-60 mb-4">
                            Posisikan wajah Anda di depan kamera dan pastikan pencahayaan cukup
                        </p>

                        <div class="space-y-3">
                            <button @click="captureAndProcess()"
                                :disabled="isProcessing || !cameraReady || !faceDetected || !modelsLoaded"
                                class="w-full bg-gradient-to-r from-primary to-secondary text-white font-semibold py-3 rounded-xl hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!isProcessing">
                                    {{ $canCheckIn ? 'Capture & Check In' : 'Capture & Check Out' }}
                                </span>
                                <span x-show="isProcessing">Memproses...</span>
                            </button>
                            <button wire:click="closeCamera" :disabled="isProcessing"
                                class="w-full bg-custom-gray-30 text-custom-gray-90 font-semibold py-3 rounded-xl hover:bg-custom-gray-40 disabled:opacity-50">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @include('components.bottom-nav')

    <script>
        function attendanceApp() {
            return {
                isGettingLocation: false,

                getLocation() {
                    if (!navigator.geolocation) {
                        alert('Geolocation tidak didukung oleh browser Anda');
                        return;
                    }

                    this.isGettingLocation = true;

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.isGettingLocation = false;
                            @this.call('validateLocation', position.coords.latitude, position.coords.longitude);
                        },
                        (error) => {
                            this.isGettingLocation = false;
                            let errorMsg = 'Gagal mendapatkan lokasi. ';
                            switch (error.code) {
                                case error.PERMISSION_DENIED:
                                    errorMsg +=
                                    'Izin lokasi ditolak. Mohon aktifkan izin lokasi di pengaturan browser.';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    errorMsg += 'Informasi lokasi tidak tersedia.';
                                    break;
                                case error.TIMEOUT:
                                    errorMsg += 'Permintaan lokasi timeout.';
                                    break;
                                default:
                                    errorMsg += 'Terjadi kesalahan yang tidak diketahui.';
                            }
                            alert(errorMsg);
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000,
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

                async init() {
                    await this.loadModels();
                    await this.startCamera();
                    this.startFaceDetection();
                },

                async loadModels() {
                    try {
                        await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
                        await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
                        await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
                        this.modelsLoaded = true;
                        console.log('Face detection models loaded');
                    } catch (error) {
                        console.error('Error loading models:', error);
                        alert('Gagal memuat model AI untuk deteksi wajah.');
                    }
                },

                async startCamera() {
                    try {
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
                        this.cameraReady = true;
                    } catch (error) {
                        console.error('Error accessing camera:', error);
                        alert('Gagal mengakses kamera. Pastikan Anda telah memberikan izin akses kamera.');
                        @this.call('closeCamera');
                    }
                },

                startFaceDetection() {
                    setInterval(async () => {
                        if (!this.$refs.video || !this.modelsLoaded || !this.cameraReady) return;

                        const video = this.$refs.video;
                        if (video.videoWidth === 0) return;

                        try {
                            const detections = await faceapi
                                .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                                .withFaceLandmarks()
                                .withFaceDescriptors();

                            this.faceDetected = detections.length > 0;
                        } catch (error) {
                            console.error('Face detection error:', error);
                        }
                    }, 100);
                },

                async captureAndProcess() {
                    if (this.isProcessing || !this.faceDetected) return;

                    this.isProcessing = true;

                    try {
                        const video = this.$refs.video;
                        const canvas = this.$refs.canvas;
                        const context = canvas.getContext('2d');

                        // Capture image
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);
                        const imageData = canvas.toDataURL('image/png');

                        // Get face descriptor
                        const detection = await faceapi
                            .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                            .withFaceLandmarks()
                            .withFaceDescriptor();

                        if (!detection) {
                            throw new Error('Wajah tidak terdeteksi. Silakan coba lagi.');
                        }

                        const descriptor = Array.from(detection.descriptor);

                        // Stop camera
                        this.stopCamera();

                        // Process based on check-in or check-out
                        @if ($canCheckIn)
                            await @this.call('processCheckIn', imageData, descriptor);
                        @elseif ($canCheckOut)
                            await @this.call('processCheckOut', imageData, descriptor);
                        @endif
                    } catch (error) {
                        console.error('Error capturing image:', error);
                        alert(error.message || 'Gagal mengambil gambar. Silakan coba lagi.');
                        this.isProcessing = false;
                    }
                },

                stopCamera() {
                    if (this.stream) {
                        this.stream.getTracks().forEach(track => track.stop());
                        this.stream = null;
                        this.cameraReady = false;
                    }
                },

                destroy() {
                    this.stopCamera();
                }
            }
        }
    </script>
</div>
