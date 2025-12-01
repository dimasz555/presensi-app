<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\User;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopPerformance extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $performanceData = $this->getPerformanceData();

        return $table
            ->query(
                User::query()
                    ->whereIn('id', $performanceData->pluck('id'))
                    ->orderByRaw('FIELD(id, ' . $performanceData->pluck('id')->implode(',') . ')')
            )
            ->columns([
                Tables\Columns\TextColumn::make('rank')
                    ->label('Peringkat')
                    ->state(function ($record) use ($performanceData) {
                        $data = $performanceData->firstWhere('id', $record->id);
                        return $data['rank'] ?? '-';
                    })
                    ->badge()
                    ->color(function ($record) use ($performanceData) {
                        $data = $performanceData->firstWhere('id', $record->id);
                        $rank = $data['rank'] ?? 0;
                        return match ((int) $rank) {
                            1 => 'success',
                            2 => 'warning',
                            3 => 'danger',
                            default => 'gray',
                        };
                    }),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('position.name')
                    ->label('Jabatan')
                    ->default('-'),

                Tables\Columns\TextColumn::make('total_hadir')
                    ->label('Total Hadir')
                    ->state(function ($record) use ($performanceData) {
                        $data = $performanceData->firstWhere('id', $record->id);
                        return $data['total_hadir'] ?? 0;
                    })
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('total_telat')
                    ->label('Total Terlambat')
                    ->state(function ($record) use ($performanceData) {
                        $data = $performanceData->firstWhere('id', $record->id);
                        return $data['total_telat'] ?? 0;
                    })
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('late_minutes')
                    ->label('Total Menit Terlambat')
                    ->state(function ($record) use ($performanceData) {
                        $data = $performanceData->firstWhere('id', $record->id);
                        return ($data['late_minutes'] ?? 0) . ' menit';
                    })
                    ->color('danger'),

                Tables\Columns\TextColumn::make('performance_score')
                    ->label('Skor Performa')
                    ->state(function ($record) use ($performanceData) {
                        $data = $performanceData->firstWhere('id', $record->id);
                        return number_format($data['performance_score'] ?? 0, 1) . '%';
                    })
                    ->badge()
                    ->color(function ($record) use ($performanceData) {
                        $data = $performanceData->firstWhere('id', $record->id);
                        $score = $data['performance_score'] ?? 0;
                        return match (true) {
                            $score >= 90 => 'success',
                            $score >= 75 => 'warning',
                            default => 'danger',
                        };
                    }),
            ])
            ->defaultPaginationPageOption(10)
            ->paginated([10, 25, 50])
            ->heading('Top Performa Karyawan Bulan Ini')
            ->description('Peringkat karyawan berdasarkan ketepatan waktu presensi');
    }

    protected function getPerformanceData()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $employees = User::role('karyawan')
            ->where('status', 'active')
            ->with(['position'])
            ->get()
            ->map(function ($user) use ($startOfMonth, $endOfMonth) {
                $attendances = Attendance::where('user_id', $user->id)
                    ->whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->get();

                $totalHadir = $attendances->where('status', 'hadir')->count();
                $totalTelat = $attendances->where('status', 'telat')->count();

                // Calculate total late minutes
                $totalLateMinutes = 0;
                foreach ($attendances->where('status', 'telat') as $attendance) {
                    $dayOfWeek = strtolower(Carbon::parse($attendance->date)->format('l'));
                    $schedule = WorkSchedule::where('work_day', $dayOfWeek)->first();

                    if ($schedule && $attendance->check_in) {
                        $dateString = Carbon::parse($attendance->date)->format('Y-m-d');
                        $timeString = Carbon::parse($schedule->work_start_time)->format('H:i:s');
                        $scheduledStart = Carbon::parse($dateString . ' ' . $timeString);
                        $actualCheckIn = Carbon::parse($attendance->check_in);

                        if ($actualCheckIn->gt($scheduledStart)) {
                            $totalLateMinutes += abs($scheduledStart->diffInMinutes($actualCheckIn));
                        }
                    }
                }

                // Calculate performance score
                $totalAttendances = $attendances->count();
                $performanceScore = $totalAttendances > 0
                    ? (($totalHadir / $totalAttendances) * 100)
                    : 0;

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'total_hadir' => $totalHadir,
                    'total_telat' => $totalTelat,
                    'late_minutes' => $totalLateMinutes,
                    'performance_score' => $performanceScore,
                ];
            })
            ->sortBy([
                ['late_minutes', 'asc'],
                ['name', 'asc'],
            ])
            ->values();

        // Add rank
        $employees = $employees->map(function ($employee, $index) {
            $employee['rank'] = $index + 1;
            return $employee;
        });

        return collect($employees);
    }
}
