<div class="min-h-screen bg-custom-gray-20 pb-24" x-data="faceRegistrationApp()">
    <!-- Header -->
    <div class="bg-gradient-to-br from-primary to-secondary text-white px-6 pt-6 pb-20">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <a href="/profil" wire:navigate
                    class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center hover:bg-white/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold">Daftar Wajah</h1>
                    <p class="text-white/70 text-sm">Untuk Verifikasi Presensi</p>
                </div>
            </div>
        </div>
    </div>

    <div class="px-6 -mt-12">
        @if (session()->has('success'))
            <div class="bg-success-secondary border border-success-main rounded-xl p-4 mb-4 flex items-center gap-3">
                <svg class="w-6 h-6 text-success-main flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <p class="text-success-pressed font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-danger-secondary border border-danger-main rounded-xl p-4 mb-4 flex items-center gap-3">
                <svg class="w-6 h-6 text-danger-main flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <p class="text-danger-pressed font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Status Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-16 h-16 rounded-full flex items-center justify-center {{ $isRegistered ? 'bg-success-secondary' : 'bg-warning-secondary' }}">
                    <svg class="w-8 h-8 {{ $isRegistered ? 'text-success-main' : 'text-warning-pressed' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if ($isRegistered)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        @endif
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-custom-gray-100">
                        {{ $isRegistered ? 'Wajah Sudah Terdaftar' : 'Wajah Belum Terdaftar' }}
                    </h2>
                    <p class="text-sm text-custom-gray-60 mt-1">
                        {{ $isRegistered ? 'Data wajah Anda sudah tersimpan di sistem' : 'Daftarkan wajah Anda untuk dapat melakukan presensi' }}
                    </p>
                    @if ($isRegistered && auth()->user()->face_registered_at)
                        <p class="text-xs text-custom-gray-50 mt-1">
                            Didaftarkan: {{ auth()->user()->face_registered_at->format('d M Y H:i') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-info-focus rounded-2xl p-6 mb-6">
            <h3 class="font-bold text-secondary mb-3">Panduan Pendaftaran Wajah:</h3>
            <ul class="space-y-2 text-sm text-custom-gray-90">
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-secondary flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Pastikan wajah Anda terlihat jelas dan menghadap kamera</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-secondary flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Gunakan pencahayaan yang cukup (tidak terlalu gelap/terang)</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-secondary flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Lepas kacamata, masker, atau topi yang menutupi wajah</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-secondary flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Tetap diam sebentar saat pengambilan data wajah</span>
                </li>
            </ul>
        </div>

        <!-- Camera Section -->
        @if (!$isRegistered)
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <div x-show="!modelsLoaded" class="text-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4"></div>
                    <p class="text-sm text-custom-gray-60">Memuat model AI...</p>
                    <p class="text-xs text-custom-gray-50 mt-1">Mohon tunggu sebentar</p>
                </div>

                <div x-show="modelsLoaded && !capturing" class="space-y-4">
                    <div class="relative aspect-[3/4] bg-custom-gray-20 rounded-2xl overflow-hidden">
                        <video x-ref="video" autoplay playsinline class="w-full h-full object-cover"></video>
                        <canvas x-ref="overlay" class="absolute inset-0 w-full h-full"></canvas>

                        <!-- Face Detection Indicator -->
                        <div class="absolute top-4 left-1/2 -translate-x-1/2 px-4 py-2 rounded-full text-sm font-medium"
                            :class="faceDetected ? 'bg-success-main text-white' : 'bg-warning-main text-white'">
                            <span x-text="faceDetected ? '✓ Wajah Terdeteksi' : '⚠ Posisikan Wajah'"></span>
                        </div>
                    </div>

                    <button @click="captureFace()" :disabled="!faceDetected || capturing"
                        class="w-full bg-gradient-to-r from-primary to-secondary text-white font-semibold py-4 rounded-xl shadow-lg hover:shadow-xl active:scale-[0.98] transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!capturing">Daftarkan Wajah</span>
                        <span x-show="capturing">Memproses...</span>
                    </button>
                </div>
            </div>
        @else
            <div class="space-y-4">
                <button wire:click="deleteFaceData"
                    wire:confirm="Apakah Anda yakin ingin menghapus data wajah? Anda perlu mendaftar ulang untuk presensi."
                    class="w-full bg-danger-main text-white font-semibold py-4 rounded-xl shadow-lg hover:shadow-xl active:scale-[0.98] transition-all">
                    Hapus Data Wajah
                </button>
            </div>
        @endif
    </div>

    @include('components.bottom-nav')

    <script>
        function faceRegistrationApp() {
            return {
                modelsLoaded: false,
                stream: null,
                faceDetected: false,
                capturing: false,
                detectionInterval: null,

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
                        console.log('Models loaded successfully');
                    } catch (error) {
                        console.error('Error loading models:', error);
                        alert('Gagal memuat model AI. Silakan refresh halaman.');
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
                    } catch (error) {
                        console.error('Error accessing camera:', error);
                        alert('Gagal mengakses kamera. Pastikan Anda telah memberikan izin akses kamera.');
                    }
                },

                startFaceDetection() {
                    this.detectionInterval = setInterval(async () => {
                        if (!this.$refs.video || !this.modelsLoaded) return;

                        const video = this.$refs.video;
                        const canvas = this.$refs.overlay;

                        if (video.videoWidth === 0) return;

                        // Set canvas size to match video
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;

                        const detections = await faceapi.detectAllFaces(
                            video,
                            new faceapi.TinyFaceDetectorOptions()
                        ).withFaceLandmarks().withFaceDescriptors();

                        // Clear canvas
                        const ctx = canvas.getContext('2d');
                        ctx.clearRect(0, 0, canvas.width, canvas.height);

                        if (detections.length > 0) {
                            this.faceDetected = true;

                            // Draw detection box
                            const resizedDetections = faceapi.resizeResults(detections, {
                                width: video.videoWidth,
                                height: video.videoHeight
                            });

                            faceapi.draw.drawDetections(canvas, resizedDetections);
                            faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
                        } else {
                            this.faceDetected = false;
                        }
                    }, 100);
                },

                async captureFace() {
                    if (!this.faceDetected || this.capturing) return;

                    this.capturing = true;

                    try {
                        const video = this.$refs.video;

                        const detection = await faceapi
                            .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                            .withFaceLandmarks()
                            .withFaceDescriptor();

                        if (!detection) {
                            throw new Error('Wajah tidak terdeteksi. Silakan coba lagi.');
                        }

                        // Get face descriptor (128-dimensional array)
                        const descriptor = Array.from(detection.descriptor);

                        // Stop camera
                        this.stopCamera();

                        // Save to database
                        await @this.call('saveFaceDescriptor', descriptor);

                    } catch (error) {
                        console.error('Error capturing face:', error);
                        alert(error.message || 'Gagal mengambil data wajah. Silakan coba lagi.');
                        this.capturing = false;
                    }
                },

                stopCamera() {
                    if (this.detectionInterval) {
                        clearInterval(this.detectionInterval);
                        this.detectionInterval = null;
                    }

                    if (this.stream) {
                        this.stream.getTracks().forEach(track => track.stop());
                        this.stream = null;
                    }
                },

                destroy() {
                    this.stopCamera();
                }
            }
        }
    </script>
</div>
