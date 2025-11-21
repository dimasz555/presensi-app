<?php

namespace App\Filament\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
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
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ViewAction::make()
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
