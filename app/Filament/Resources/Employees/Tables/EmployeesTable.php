<?php

namespace App\Filament\Resources\Employees\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('position.name')
                    ->label('Jabatan')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Nomor Whatsapp')
                    ->searchable(),
                TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->sortable()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'l' => 'Laki-Laki',
                        'p' => 'Perempuan',
                        default => ucfirst($state),
                    }),
                TextColumn::make('status')
                    ->label('status')
                    ->sortable()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'active' => 'Aktif',
                        'inactive' => 'Non-Aktif',
                        default => ucfirst($state),
                    }),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('position_id')
                    ->label('Jabatan')
                    ->relationship('position', 'name'),
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Non-Aktif',
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->IconButton()
                    ->tooltip('Edit Karyawan'),
                DeleteAction::make()
                    ->IconButton()
                    ->tooltip('Hapus Karyawan'),
                ViewAction::make()
                    ->IconButton()
                    ->tooltip('Detail Karyawan')
                    ->infolist([ // Tambahkan infolist di dalam action
                        Section::make('Informasi Karyawan')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nama'),
                                TextEntry::make('email')
                                    ->label('Email'),
                                TextEntry::make('phone')
                                    ->label('Nomor Whatsapp'),
                                TextEntry::make('gender')
                                    ->label('Jenis Kelamin')
                                    ->formatStateUsing(fn($state) => match ($state) {
                                        'l' => 'Laki-Laki',
                                        'p' => 'Perempuan',
                                        default => ucfirst($state),
                                    }),
                                TextEntry::make('position.name')
                                    ->label('Jabatan'),
                                TextEntry::make('basic_salary')
                                    ->label('Gaji Pokok')
                                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->formatStateUsing(fn($state) => match ($state) {
                                        'active' => 'Aktif',
                                        'inactive' => 'Non-Aktif',
                                        default => ucfirst($state),
                                    }),
                                TextEntry::make('roles')
                                    ->label('Role')
                                    ->formatStateUsing(fn($state, $record) => $record->getRoleNames()->join(', ')),
                                TextEntry::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->dateTime(),
                                TextEntry::make('updated_at')
                                    ->label('Diupdate Pada')
                                    ->dateTime(),
                            ])
                            ->columns(2),
                    ]),
                Action::make('Nonaktif')
                    ->iconButton()
                    ->icon('heroicon-o-user-minus')
                    ->color('danger')
                    ->tooltip('Nonaktifkan Karyawan')
                    ->requiresConfirmation()
                    ->modalHeading('Nonaktifkan Karyawan')
                    ->modalDescription('Apakah Anda yakin ingin menonaktifkan karyawan ini?')
                    ->modalSubmitActionLabel('Ya, Nonaktifkan')
                    ->action(function ($record) {
                        $record->update(['status' => 'inactive']);

                        Notification::make()
                            ->success()
                            ->title('Karyawan Dinonaktifkan')
                            ->body('Karyawan telah berhasil dinonaktifkan.')
                            ->send();
                    })
                    ->visible(fn($record) => $record->status === 'active'),
                Action::make('Aktifkan')
                    ->iconButton()
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->tooltip('Aktifkan Karyawan')
                    ->requiresConfirmation()
                    ->modalHeading('Aktifkan Karyawan')
                    ->modalDescription('Apakah Anda yakin ingin mengaktifkan karyawan ini?')
                    ->modalSubmitActionLabel('Ya, Aktifkan')
                    ->action(function ($record) {
                        $record->update(['status' => 'active']);
                        Notification::make()
                            ->success()
                            ->title('Karyawan Diaktifkan')
                            ->body('Karyawan telah berhasil diaktifkan.')
                            ->send();
                    })
                    ->visible(fn($record) => $record->status === 'inactive'),
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
