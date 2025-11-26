<div class="min-h-screen bg-custom-gray-20 pb-24">
    <!-- Header with Gradient -->
    <div class="bg-gradient-to-br from-primary to-secondary text-white px-6 pt-6 pb-24 rounded-b-[2rem]">
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-white/80 text-sm">Selamat Datang,</p>
                <h1 class="text-2xl font-bold">{{ $userName }}</h1>
                <p class="text-white/70 text-xs mt-1">{{ $userPosition }}</p>
            </div>
            <div class="flex items-center gap-2">
                <button
                    class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center hover:bg-white/30 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </button>
                <button wire:click="logout"
                    class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center hover:bg-white/30 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Date & Time -->
        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm">{{ now()->locale('id')->isoFormat('dddd') }}</p>
                    <p class="text-2xl font-bold">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-white/80 text-sm">Waktu</p>
                    <p class="text-2xl font-bold" x-data="{ time: '{{ now()->format('H:i') }}' }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) }, 1000)" x-text="time"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-6 -mt-16">
        <!-- Status Card -->
        <div class="bg-white rounded-2xl shadow-lg p-5 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-custom-gray-100">Status Hari Ini</h2>
                @if ($todayAttendance)
                    <span
                        class=" text-success-main text-xs font-semibold px-3 py-1.5 rounded-full flex items-center gap-1">
                        @if ($todayAttendance->face_matched)
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                        {{ $todayAttendance->status_label }}
                    </span>
                @else
                    <span class="bg-danger-secondary text-danger-main text-xs font-semibold px-3 py-1.5 rounded-full">
                        Belum Absen
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Check In -->
                <div class="bg-success-secondary rounded-xl p-4">
                    <div class="flex items-center mb-2">
                        <div class="w-8 h-8 bg-success-main rounded-lg flex items-center justify-center mr-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-success-pressed">Masuk</span>
                    </div>
                    <p class="text-2xl font-bold text-custom-gray-100">
                        {{ $todayAttendance?->check_in ? \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') : '--:--' }}
                    </p>
                    <p class="text-xs text-custom-gray-90 mt-1">
                        {{ $todayAttendance?->check_in ? \Carbon\Carbon::parse($todayAttendance->check_in)->diffForHumans() : 'Belum check in' }}
                    </p>
                </div>

                <!-- Check Out -->
                <div class="bg-danger-secondary rounded-xl p-4">
                    <div class="flex items-center mb-2">
                        <div class="w-8 h-8 bg-danger-main rounded-lg flex items-center justify-center mr-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-danger-pressed">Keluar</span>
                    </div>
                    <p class="text-2xl font-bold text-custom-gray-100">
                        {{ $todayAttendance?->check_out ? \Carbon\Carbon::parse($todayAttendance->check_out)->format('H:i') : '--:--' }}
                    </p>
                    <p class="text-xs text-custom-gray-90 mt-1">
                        {{ $todayAttendance?->check_out ? \Carbon\Carbon::parse($todayAttendance->check_out)->diffForHumans() : 'Belum check out' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-6">
            <h2 class="text-lg font-bold text-custom-gray-100 mb-4">Menu Utama</h2>
            <div class="grid grid-cols-4 gap-4">
                <!-- Absensi -->
                <a href="/absensi" wire:navigate class="flex flex-col items-center">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-2xl flex items-center justify-center mb-2 shadow-lg hover:scale-105 active:scale-95 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-custom-gray-90 text-center">Absensi</span>
                </a>

                <!-- Pengajuan -->
                <a href="/pengajuan" wire:navigate class="flex flex-col items-center">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-warning-main to-warning-pressed rounded-2xl flex items-center justify-center mb-2 shadow-lg hover:scale-105 active:scale-95 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-custom-gray-90 text-center">Pengajuan</span>
                </a>

                <!-- Riwayat -->
                <a href="/riwayat" wire:navigate class="flex flex-col items-center">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-info-focus to-secondary rounded-2xl flex items-center justify-center mb-2 shadow-lg hover:scale-105 active:scale-95 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-custom-gray-90 text-center">Riwayat</span>
                </a>

                <!-- Profil -->
                <a href="/profil" wire:navigate class="flex flex-col items-center">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-green-primary to-green-secondary rounded-2xl flex items-center justify-center mb-2 shadow-lg hover:scale-105 active:scale-95 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-custom-gray-90 text-center">Profil</span>
                </a>
            </div>
        </div>

        <!-- Stats This Month -->
        <div class="bg-white rounded-2xl shadow-lg p-5 mb-6">
            <h2 class="text-lg font-bold text-custom-gray-100 mb-4">Statistik Bulan Ini</h2>
            <div class="grid grid-cols-5 gap-3">
                <div class="text-center">
                    <div
                        class="w-10 h-10 bg-success-secondary rounded-xl flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-success-main" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-xl font-bold text-custom-gray-100">{{ $monthStats['hadir'] }}</p>
                    <p class="text-xs text-custom-gray-60">Hadir</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-10 h-10 bg-warning-secondary rounded-xl flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-warning-pressed" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-xl font-bold text-custom-gray-100">{{ $monthStats['telat'] }}</p>
                    <p class="text-xs text-custom-gray-60">Telat</p>
                </div>
                <div class="text-center">
                    <div class="w-10 h-10 bg-info-focus rounded-xl flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="text-xl font-bold text-custom-gray-100">{{ $monthStats['izin'] }}</p>
                    <p class="text-xs text-custom-gray-60">Izin</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-10 h-10 bg-warning-secondary rounded-xl flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-warning-pressed" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="text-xl font-bold text-custom-gray-100">{{ $monthStats['sakit'] }}</p>
                    <p class="text-xs text-custom-gray-60">Sakit</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-10 h-10 bg-danger-secondary rounded-xl flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-danger-main" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-xl font-bold text-custom-gray-100">{{ $monthStats['alpha'] }}</p>
                    <p class="text-xs text-custom-gray-60">Alpha</p>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl shadow-lg p-5">
            <h2 class="text-lg font-bold text-custom-gray-100 mb-4">Aktivitas Terbaru</h2>
            <div class="space-y-3">
                @forelse($recentActivities as $activity)
                    <div class="flex items-start space-x-3 pb-3 border-b border-custom-gray-30 last:border-0">
                        <div
                            class="w-10 h-10 bg-primary-secondary rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-custom-gray-100 flex items-center gap-2">
                                {{ $activity->type }}
                                @if ($activity->face_matched)
                                    <span class="text-success-main text-xs">✓ Face ID</span>
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
                        <svg class="w-16 h-16 text-custom-gray-40 mx-auto mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-custom-gray-60 text-sm">Belum ada aktivitas</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    @include('components.bottom-nav')
</div>
