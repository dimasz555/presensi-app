<?php

namespace App\Filament\Resources\AttendanceReports;

use App\Filament\Resources\AttendanceReports\Pages\CreateAttendanceReport;
use App\Filament\Resources\AttendanceReports\Pages\EditAttendanceReport;
use App\Filament\Resources\AttendanceReports\Pages\ListAttendanceReports;
use App\Filament\Resources\AttendanceReports\Pages\ViewAttendanceReport;
use App\Filament\Resources\AttendanceReports\Schemas\AttendanceReportForm;
use App\Filament\Resources\AttendanceReports\Schemas\AttendanceReportInfolist;
use App\Filament\Resources\AttendanceReports\Tables\AttendanceReportsTable;
use App\Models\AttendanceReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;
use UnitEnum;

class AttendanceReportResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;


    protected static string | UnitEnum | null $navigationGroup = 'Laporan';

    protected static ?string $navigationLabel = 'Presensi';

    public static function getModelLabel(): string
    {
        return 'Laporan Absensi';
    }

    // public static function form(Form $form): Form
    // {
    //     return $form->schema([]);
    // }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->role('karyawan')
                    ->with('position')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('position.name')
                    ->label('Jabatan')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('total_masuk')
                    ->label('Total Masuk')
                    ->getStateUsing(function ($record, $livewire) {
                        $filters = $livewire->tableFilters;
                        $startDate = $filters['date_range']['start_date'] ?? null;
                        $endDate = $filters['date_range']['end_date'] ?? null;

                        if (!$startDate || !$endDate) return '-';

                        return $record->attendances()
                            ->whereBetween('date', [$startDate, $endDate])
                            ->whereIn('status', ['hadir', 'telat'])
                            ->count();
                    })
                    ->badge()
                    ->color('success')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('total_telat')
                    ->label('Total Terlambat')
                    ->getStateUsing(function ($record, $livewire) {
                        $filters = $livewire->tableFilters;
                        $startDate = $filters['date_range']['start_date'] ?? null;
                        $endDate = $filters['date_range']['end_date'] ?? null;

                        if (!$startDate || !$endDate) return '-';

                        return $record->attendances()
                            ->whereBetween('date', [$startDate, $endDate])
                            ->where('status', 'telat')
                            ->count();
                    })
                    ->badge()
                    ->color('warning')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('total_jam_telat')
                    ->label('Total Jam Terlambat')
                    ->getStateUsing(function ($record, $livewire) {
                        $filters = $livewire->tableFilters;
                        $startDate = $filters['date_range']['start_date'] ?? null;
                        $endDate = $filters['date_range']['end_date'] ?? null;

                        if (!$startDate || !$endDate) return '-';

                        $totalMinutes = DB::table('attendances')
                            ->leftJoin('work_schedules', function ($join) {
                                $join->on(DB::raw('LOWER(DAYNAME(attendances.date))'), '=', 'work_schedules.work_day');
                            })
                            ->where('attendances.user_id', $record->id)
                            ->whereBetween('attendances.date', [$startDate, $endDate])
                            ->where('attendances.status', 'telat')
                            ->whereNotNull('attendances.check_in')
                            ->whereNotNull('work_schedules.work_start_time')
                            ->sum(DB::raw('
                                CASE 
                                    WHEN TIME(attendances.check_in) > work_schedules.work_start_time 
                                    THEN TIMESTAMPDIFF(MINUTE, work_schedules.work_start_time, TIME(attendances.check_in))
                                    ELSE 0
                                END
                            '));

                        return self::formatLateTime($totalMinutes ?? 0);
                    })
                    ->badge()
                    ->color('warning')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('total_izin')
                    ->label('Total Izin')
                    ->getStateUsing(function ($record, $livewire) {
                        $filters = $livewire->tableFilters;
                        $startDate = $filters['date_range']['start_date'] ?? null;
                        $endDate = $filters['date_range']['end_date'] ?? null;

                        if (!$startDate || !$endDate) return '-';

                        return $record->attendances()
                            ->whereBetween('date', [$startDate, $endDate])
                            ->where('status', 'izin')
                            ->count();
                    })
                    ->badge()
                    ->color('info')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('total_alpha')
                    ->label('Total Tidak Masuk')
                    ->getStateUsing(function ($record, $livewire) {
                        $filters = $livewire->tableFilters;
                        $startDate = $filters['date_range']['start_date'] ?? null;
                        $endDate = $filters['date_range']['end_date'] ?? null;

                        if (!$startDate || !$endDate) return '-';

                        return $record->attendances()
                            ->whereBetween('date', [$startDate, $endDate])
                            ->where('status', 'alpha')
                            ->count();
                    })
                    ->badge()
                    ->color('danger')
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->native(false)
                            ->default(now()->startOfMonth())
                            ->required(),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('Tanggal Akhir')
                            ->native(false)
                            ->default(now()->endOfMonth())
                            ->required(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        // Filter ini hanya untuk trigger refresh table
                        // Data filtering dilakukan di getStateUsing masing-masing column
                        return $query;
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['start_date'] || !$data['end_date']) {
                            return null;
                        }

                        return 'Periode: ' .
                            \Carbon\Carbon::parse($data['start_date'])->format('d M Y') .
                            ' - ' .
                            \Carbon\Carbon::parse($data['end_date'])->format('d M Y');
                    }),

                Tables\Filters\SelectFilter::make('position_id')
                    ->label('Jabatan')
                    ->relationship('position', 'name')
                    ->placeholder('Semua Jabatan')
                    ->preload(),
            ])
            ->actions([
                // Tidak ada actions
            ])
            ->bulkActions([
                ExportBulkAction::make()
            ])
            ->defaultSort('name', 'asc')
            ->emptyStateHeading('Tidak Ada Data')
            ->emptyStateDescription('Silakan pilih filter tanggal untuk menampilkan laporan absensi.');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendanceReports::route('/'),
        ];
    }

    /**
     * Format menit terlambat ke format jam:menit
     */
    protected static function formatLateTime(int $minutes): string
    {
        if ($minutes === 0) {
            return '0 menit';
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($hours > 0 && $remainingMinutes > 0) {
            return "{$hours} jam {$remainingMinutes} menit";
        } elseif ($hours > 0) {
            return "{$hours} jam";
        } else {
            return "{$remainingMinutes} menit";
        }
    }
}
