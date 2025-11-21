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
                    ->required(),
                DateTimePicker::make('check_in'),
                DateTimePicker::make('check_out'),
                Select::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'telat' => 'Terlambat',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpha' => 'Alpha',
                    ])
                    ->required(),
                TextInput::make('check_in_lat')
                    ->numeric(),
                TextInput::make('check_in_long')
                    ->numeric(),
                TextInput::make('check_out_lat')
                    ->numeric(),
                TextInput::make('check_out_long')
                    ->numeric(),
                Toggle::make('face_matched')
                    ->required(),
                TextInput::make('face_confidence')
                    ->numeric(),
            ]);
    }
}
