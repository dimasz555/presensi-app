<?php

namespace App\Filament\Resources\Payrolls\Tables;

use App\Models\Payroll;
use App\Models\User;
use App\Services\PayrollPdfService;
use Dom\Text;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
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
                    ->money('idr', true),
                TextColumn::make('total_deductions')
                    ->label('Total Potongan')
                    ->money('idr', true),
                TextColumn::make('net_salary')
                    ->label('Gaji Bersih')
                    ->money('idr', true),
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
                    ]),

            ])
            ->filters([
                SelectFilter::make('period_month')
                    ->label('Bulan')
                    ->options([
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
                    ]),
                SelectFilter::make('period_year')
                    ->label('Tahun')
                    ->options(function () {
                        return Payroll::query()
                            ->distinct()
                            ->pluck('period_year', 'period_year')
                            ->toArray();
                    }),
                SelectFilter::make('user_id')
                    ->label('Karyawan')
                    ->options(fn() => User::WhereHas('roles', fn($q) => $q->where('name', 'karyawan'))->pluck('name', 'id')),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Sudah Dikirim',
                        'rejected' => 'Batal',
                    ]),
                TrashedFilter::make(),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                Action::make('approve')
                    ->iconButton()
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->tooltip('Kirim Slip Gaji')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Slip Gaji')
                    ->modalDescription('Apakah Anda yakin ingin mengirim slip gaji ini?')
                    ->modalSubmitActionLabel('Ya, Terima')
                    ->action(function ($record) {
                        try {
                            $pdfService = new PayrollPdfService();
                            $pdfPath = $pdfService->generatePdf($record);

                            $updated = $record->update([
                                'status' => 'paid',
                                'file_path' => $pdfPath,
                            ]);

                            Notification::make()
                                ->success()
                                ->title('Slip Gaji Terkirim')
                                ->body('Slip gaji berhasil dibuat dan dikirim.')
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Gagal Mengirim Slip Gaji')
                                ->body('Error: ' . $e->getMessage())
                                ->send();
                        }
                    })
                    ->visible(fn($record) => $record->status === 'pending'),
                Action::make('view_pdf')
                    ->iconButton()
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->tooltip('Lihat Slip Gaji')
                    ->url(function ($record) {
                        $pdfService = new PayrollPdfService();
                        return $pdfService->getPdfUrl($record);
                    })
                    ->openUrlInNewTab()
                    ->visible(fn($record) => $record->status === 'paid'),
                Action::make('cancel')
                    ->iconButton()
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->tooltip('Batalkan Slip Gaji')
                    ->requiresConfirmation()
                    ->modalHeading('Batalkan Pengiriman Slip Gaji')
                    ->modalDescription('Apakah Anda yakin membatalkan pengiriman slip gaji ini?')
                    ->modalSubmitActionLabel('Ya, Batalkan')
                    ->action(function ($record) {
                        $pdfService = new PayrollPdfService();
                        $pdfService->deletePdf($record);
                        $record->update([
                            'status' => 'pending',
                            'file_path' => null,
                        ]);

                        Notification::make()
                            ->danger()
                            ->title('Slip Gaji Dibatalkan')
                            ->body('Pengiriman slip gaji berhasil dibatalkan.')
                            ->send();
                    })
                    ->visible(fn($record) => $record->status === 'paid'),
                EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit')
                    ->visible(fn($record) => $record->status === 'pending'),
                DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Hapus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('period_month', direction: 'desc');
    }
}
