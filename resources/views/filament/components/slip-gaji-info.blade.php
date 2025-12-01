@php
    $formData = $getRecord() ?? null;
    $state = $getState();

    // Ambil dari form state
    $userId = data_get($this->data ?? [], 'user_id');
    $month = data_get($this->data ?? [], 'period_month', now()->month);
    $year = data_get($this->data ?? [], 'period_year', now()->year);

    $user = $userId ? \App\Models\User::with('position')->find($userId) : null;

    $attendanceData = [
        'hadir' => 0,
        'telat' => 0,
        'izin' => 0,
        'sakit' => 0,
        'alpha' => 0,
    ];
    $totalWorkDays = 0;
    $totalLateMinutes = 0;
    $lateHours = 0;
    $lateMinutes = 0;

    if ($user) {
        $attendances = $user->attendances()->whereMonth('date', $month)->whereYear('date', $year)->get();

        $attendanceData = [
            'hadir' => $attendances->where('status', 'hadir')->count(),
            'telat' => $attendances->where('status', 'telat')->count(),
            'izin' => $attendances->where('status', 'izin')->count(),
            'sakit' => $attendances->where('status', 'sakit')->count(),
            'alpha' => $attendances->where('status', 'alpha')->count(),
        ];

        $presenceTotal = $attendanceData['hadir'] + $attendanceData['telat'];

        // Hitung total hari kerja
        $workDays = \App\Models\WorkSchedule::pluck('work_day')->toArray();
        $dayMapping = [
            'monday' => \Carbon\Carbon::MONDAY,
            'tuesday' => \Carbon\Carbon::TUESDAY,
            'wednesday' => \Carbon\Carbon::WEDNESDAY,
            'thursday' => \Carbon\Carbon::THURSDAY,
            'friday' => \Carbon\Carbon::FRIDAY,
            'saturday' => \Carbon\Carbon::SATURDAY,
            'sunday' => \Carbon\Carbon::SUNDAY,
        ];

        $workDayNumbers = array_filter(array_map(fn($day) => $dayMapping[$day] ?? null, $workDays));
        $startDate = \Carbon\Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        while ($startDate->lte($endDate)) {
            if (in_array($startDate->dayOfWeek, $workDayNumbers)) {
                $totalWorkDays++;
            }
            $startDate->addDay();
        }

        // Hitung total menit terlambat
        $totalLateMinutes = $user->getTotalLateMinutesInMonth((int) $month, (int) $year);

        // Konversi menit ke jam dan menit
        $lateHours = floor($totalLateMinutes / 60);
        $lateMinutes = $totalLateMinutes % 60;
    }

    $monthNames = [
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
    ];
@endphp

@if ($user)
    <div
        class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="bg-primary-500 dark:bg-primary-600 px-6 py-4">
            <h3 class="text-lg font-bold text-white">
                Data Slip Gaji - {{ $monthNames[$month] ?? '' }} {{ $year }}
            </h3>
        </div>

        {{-- Content --}}
        <div class="p-6">
            {{-- Informasi Karyawan --}}
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                    Informasi Karyawan
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex">
                        <span class="text-gray-500 dark:text-gray-400 w-32">Nama</span>
                        <span class="text-gray-400 dark:text-gray-500 mr-2">:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</span>
                    </div>
                    <div class="flex">
                        <span class="text-gray-500 dark:text-gray-400 w-32">Jabatan</span>
                        <span class="text-gray-400 dark:text-gray-500 mr-2">:</span>
                        <span
                            class="font-medium text-gray-900 dark:text-white">{{ $user->position?->name ?? '-' }}</span>
                    </div>
                    <div class="flex">
                        <span class="text-gray-500 dark:text-gray-400 w-32">No. Telepon</span>
                        <span class="text-gray-400 dark:text-gray-500 mr-2">:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $user->phone ?? '-' }}</span>
                    </div>
                    <div class="flex">
                        <span class="text-gray-500 dark:text-gray-400 w-32">Email</span>
                        <span class="text-gray-400 dark:text-gray-500 mr-2">:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $user->email }}</span>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200 dark:border-gray-700 my-4">

            {{-- Rekap Kehadiran (seperti Informasi Karyawan) --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                    Rekap Kehadiran
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex">
                        <span class="text-gray-500 dark:text-gray-400 w-32">Hari Kerja</span>
                        <span class="text-gray-400 dark:text-gray-500 mr-2">:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $totalWorkDays }}</span>
                    </div>

                    <div class="flex">
                        <span class="text-gray-500 dark:text-gray-400 w-32">Masuk</span>
                        <span class="text-gray-400 dark:text-gray-500 mr-2">:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $presenceTotal }}</span>
                    </div>

                    <div class="flex">
                        <span class="text-gray-500 dark:text-gray-400 w-32">Tepat Waktu</span>
                        <span class="text-gray-400 dark:text-gray-500 mr-2">:</span>
                        <span
                            class="font-medium text-green-600 dark:text-green-400">{{ $attendanceData['hadir'] }}</span>
                    </div>

                    <div class="flex">
                        <span class="text-gray-500 dark:text-gray-400 w-32">Terlambat</span>
                        <span class="text-gray-400 dark:text-gray-500 mr-2">:</span>
                        <span class="font-medium text-yellow-600 dark:text-yellow-400">{{ $attendanceData['telat'] }}
                            <span class="text-gray-500 dark:text-gray-400 font-normal">
                                (
                                @if ($lateHours > 0)
                                    {{ $lateHours }} j {{ $lateMinutes }} m
                                @else
                                    {{ $lateMinutes }} m
                                @endif
                                )
                            </span> </span>
                    </div>

                    <div class="flex">
                        <span class="text-gray-500 dark:text-gray-400 w-32">Izin</span>
                        <span class="text-gray-400 dark:text-gray-500 mr-2">:</span>
                        <span class="font-medium text-blue-600 dark:text-blue-400">{{ $attendanceData['izin'] }}</span>
                    </div>

                    <div class="flex">
                        <span class="text-gray-500 dark:text-gray-400 w-32">Sakit</span>
                        <span class="text-gray-400 dark:text-gray-500 mr-2">:</span>
                        <span
                            class="font-medium text-purple-600 dark:text-purple-400">{{ $attendanceData['sakit'] }}</span>
                    </div>

                    <div class="flex">
                        <span class="text-gray-500 dark:text-gray-400 w-32">Alpha</span>
                        <span class="text-gray-400 dark:text-gray-500 mr-2">:</span>
                        <span class="font-medium text-red-600 dark:text-red-400">{{ $attendanceData['alpha'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="text-gray-500 dark:text-gray-400 text-center py-4">
        Pilih karyawan terlebih dahulu untuk melihat data slip gaji.
    </div>
@endif
