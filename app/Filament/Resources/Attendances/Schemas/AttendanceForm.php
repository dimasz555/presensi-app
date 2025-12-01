<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Nama Karyawan')
                    ->relationship('user', 'name', function ($query) {
                        $query->whereHas('roles', function ($q) {
                            $q->where('name', 'karyawan');
                        });
                    })
                    ->preload()
                    ->searchable()
                    ->required(),
                DatePicker::make('date')
                    ->label('Tanggal')
                    ->required(),
                DateTimePicker::make('check_in')
                    ->label('Waktu Masuk')
                    ->required(),
                DateTimePicker::make('check_out')
                    ->label('Waktu Keluar')
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'hadir' => 'Hadir',
                        'telat' => 'Terlambat',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpha' => 'Alpha',
                    ])
                    ->required(),
                TextInput::make('check_in_lat')
                    ->label('Latitude Masuk')
                    ->numeric(),
                TextInput::make('check_in_long')
                    ->label('Longitude Masuk')
                    ->numeric(),
                TextInput::make('check_out_lat')
                    ->label('Latitude Keluar')
                    ->numeric(),
                TextInput::make('check_out_long')
                    ->label('Longitude Keluar')
                    ->numeric(),
                Toggle::make('face_matched')
                    ->label('Kecocokan Wajah')
                    ->required(),
                TextInput::make('face_confidence')
                    ->label('Tingkat Keyakinan Wajah')
                    ->numeric(),
            ]);
    }
}
