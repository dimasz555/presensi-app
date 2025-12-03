<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                textInput::make('name')
                    ->label('Nama')
                    ->required(),
                textInput::make('email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create'),
                textInput::make('phone')
                    ->label('Nomor WhatsApp')
                    ->unique(ignoreRecord: true)
                    ->required(),
                Select::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'l' => 'Laki-Laki',
                        'p' => 'Perempuan',
                    ])
                    ->required(),
                Select::make('position')
                    ->label('Jabatan')
                    ->relationship('position', 'name')
                    ->required(),
                TextInput::make('basic_salary')
                    ->label('Gaji Pokok')
                    ->numeric()
                    ->Prefix('Rp ')
                    ->required(),
                TextInput::make('late_penalty_per_minute')
                    ->numeric()
                    ->label('Denda Keterlambatan per Menit')
                    ->prefix('Rp')
                    ->default(0)
                    ->helperText('Jumlah denda yang dipotong per menit keterlambatan'),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Non-Aktif',
                    ])
                    ->default('active')
                    ->required()
            ]);
    }
}
