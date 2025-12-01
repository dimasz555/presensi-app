<?php

namespace App\Filament\Resources\LeaveRequests\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeaveRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Karyawan ')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->date('l, d F Y')
                    ->sortable(),
                TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date('l, d F Y')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date('l, d F Y')
                    ->sortable(),
                TextColumn::make('reason')
                    ->label('Keterangan'),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => match (strtolower((string) $state)) {
                        'pending'  => 'Pending',
                        'approved'     => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default    => ucfirst((string) $state),
                    })
                    ->badge()
                    ->colors([
                        'success'   => fn($state) => strtolower((string) $state) === 'approved',
                        'danger'    => fn($state) => strtolower((string) $state) === 'rejected',
                        'warning'   => fn($state) => strtolower((string) $state) === 'pending',
                        'secondary' => fn($state) => empty($state),
                    ]),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('approve')
                    ->iconButton()
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->tooltip('Terima Izin')
                    ->requiresConfirmation()
                    ->modalHeading('Terima Pengajuan Izin')
                    ->modalDescription('Apakah Anda yakin ingin menerima pengajuan izin ini?')
                    ->modalSubmitActionLabel('Ya, Terima')
                    ->action(function ($record) {
                        $record->update(['status' => 'approved']);

                        Notification::make()
                            ->success()
                            ->title('Izin Diterima')
                            ->body('Pengajuan izin berhasil diterima.')
                            ->send();
                    })
                    ->visible(fn($record) => $record->status === 'pending'),

                Action::make('reject')
                    ->iconButton()
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->tooltip('Tolak Izin')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pengajuan Izin')
                    ->modalDescription('Apakah Anda yakin ingin menolak pengajuan izin ini?')
                    ->modalSubmitActionLabel('Ya, Tolak')
                    ->action(function ($record) {
                        $record->update(['status' => 'rejected']);

                        Notification::make()
                            ->danger()
                            ->title('Izin Ditolak')
                            ->body('Pengajuan izin berhasil ditolak.')
                            ->send();
                    })
                    ->visible(fn($record) => $record->status === 'pending'),

                EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
