<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Karyawan '),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('l, d M Y')
                    ->sortable(),
                TextColumn::make('check_in')
                    ->label('Jam Masuk')
                    ->suffix(' WIB')
                    ->time(),
                TextColumn::make('check_out')
                    ->label('Jam Keluar')
                    ->suffix(' WIB')
                    ->time(),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'hadir' => 'Tepat Waktu',
                        'telat' => 'Terlambat',
                        'Izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpha' => 'Alpha',
                        default => ucfirst($state),
                    }),
                IconColumn::make('face_matched')
                    ->label('Verifikasi Wajah')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ViewAction::make()
                    ->infolist([
                        Section::make('Detail Presensi')
                            ->schema([
                                TextEntry::make('user.name')
                                    ->label('Nama'),
                                TextEntry::make('user.phone')
                                    ->label('Nomor Whatsapp'),
                                TextEntry::make('user.gender')
                                    ->label('Jenis Kelamin')
                                    ->formatStateUsing(fn($state) => match ($state) {
                                        'l' => 'Laki-Laki',
                                        'p' => 'Perempuan',
                                        default => ucfirst($state),
                                    }),
                                TextEntry::make('user.position.name')
                                    ->label('Jabatan'),
                                TextEntry::make('date')
                                    ->label('Tanggal')
                                    ->date(),
                                TextEntry::make('check_in')
                                    ->label('Jam Masuk')
                                    ->suffix(' WIB')
                                    ->time(),
                                TextEntry::make('check_out')
                                    ->label('Jam Keluar')
                                    ->suffix(' WIB')
                                    ->time(),
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->formatStateUsing(fn($state) => match ($state) {
                                        'hadir' => 'Hadir',
                                        'telat' => 'Terlambat',
                                        'Izin' => 'Izin',
                                        'sakit' => 'Sakit',
                                        'alpha' => 'Alpha',
                                        default => ucfirst($state),
                                    }),
                                TextEntry::make('face_matched')
                                    ->label('Verifikasi Wajah')
                                    ->formatStateUsing(fn($state) => $state ? 'Terverifikasi' : 'Tidak Terverifikasi'),
                                TextEntry::make('face_confidence')
                                    ->label('Tingkat Kepercayaan Wajah')
                                    ->formatStateUsing(fn($state) => $state !== null ? number_format($state * 100, 2) . '%' : 'N/A'),
                                TextEntry::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->dateTime(),
                                TextEntry::make('updated_at')
                                    ->label('Diupdate Pada')
                                    ->dateTime(),
                                TextEntry::make('deleted_at')
                                    ->label('Dihapus Pada')
                                    ->dateTime(),
                            ])
                            ->columns(2),
                    ]),
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
