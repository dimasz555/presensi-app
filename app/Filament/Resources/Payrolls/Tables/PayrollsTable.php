<?php

namespace App\Filament\Resources\Payrolls\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Karyawan')
                    ->searchable(),
                TextColumn::make('period_month')
                    ->label('Bulan')
                    ->formatStateUsing(fn($state) => match ($state) {
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
                        default => 'Tidak Diketahui',
                    })
                    ->sortable(),
                TextColumn::make('period_year')
                    ->label('Tahun')
                    ->sortable(),
                TextColumn::make('basic_salary')
                    ->label('Gaji Pokok')
                    ->money('idr', true)
                    ->sortable(),
                TextColumn::make('total_bonus')
                    ->label('Total Bonus')
                    ->money('idr', true)
                    ->sortable(),
                TextColumn::make('total_deductions')
                    ->label('Total Potongan')
                    ->money('idr', true)
                    ->sortable(),
                TextColumn::make('net_salary')
                    ->label('Gaji Bersih')
                    ->money('idr', true)
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => match (strtolower((string) $state)) {
                        'pending'  => 'Pending',
                        'paid'     => 'Sudah Dikirim',
                        'rejected' => 'Batal',
                        default    => ucfirst((string) $state),
                    })
                    ->badge()
                    ->colors([
                        'success'   => fn($state) => strtolower((string) $state) === 'paid',
                        'danger'    => fn($state) => strtolower((string) $state) === 'rejected',
                        'warning'   => fn($state) => strtolower((string) $state) === 'pending',
                        'secondary' => fn($state) => empty($state),
                    ])
                    ->sortable(),

            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
