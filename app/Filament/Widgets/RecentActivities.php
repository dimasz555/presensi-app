<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentActivities extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.position.name')
                    ->label('Jabatan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('check_in')
                    ->label('Check In')
                    ->dateTime('d M Y, H:i')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('check_out')
                    ->label('Check Out')
                    ->dateTime('d M Y, H:i')
                    ->badge()
                    ->color('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('date', 'desc')
            ->defaultPaginationPageOption(10)
            ->paginated([10, 25, 50])
            ->heading('Aktivitas Presensi Bulan Ini')
            ->description('Data aktivitas presensi karyawan bulan ' . Carbon::now()->locale('id')->translatedFormat('F Y'));
    }

    protected function getTableQuery(): Builder
    {
        return Attendance::query()
            ->with(['user.position'])
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc');
    }
}
