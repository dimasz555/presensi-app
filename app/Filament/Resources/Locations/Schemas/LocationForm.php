<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Lokasi')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lokasi')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Kantor Pusat'),

                        Textarea::make('address')
                            ->label('Alamat')
                            ->rows(3)
                            ->placeholder('Masukkan alamat lengkap lokasi'),
                    ]),

                Section::make('Koordinat & Radius')
                    ->description('Masukkan koordinat lokasi dan radius yang diperbolehkan untuk presensi')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('latitude')
                                    ->label('Latitude')
                                    ->required()
                                    ->numeric()
                                    ->placeholder('Contoh: -6.200000')
                                    ->helperText('Koordinat lintang lokasi'),

                                TextInput::make('longitude')
                                    ->label('Longitude')
                                    ->required()
                                    ->numeric()
                                    ->placeholder('Contoh: 106.816666')
                                    ->helperText('Koordinat bujur lokasi'),
                            ]),

                        TextInput::make('radius')
                            ->label('Radius (Meter)')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(1000)
                            ->default(50)
                            ->suffix('meter')
                            ->helperText('Jarak maksimal dari titik lokasi yang diperbolehkan untuk presensi'),

                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->helperText('Hanya lokasi aktif yang bisa digunakan untuk presensi'),
                    ]),
            ]);
    }
}
