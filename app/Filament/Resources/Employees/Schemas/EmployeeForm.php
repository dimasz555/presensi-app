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
                    ->required(),
                textInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create'),
                textInput::make('phone')
                    ->label('Nomor Whatsapp')
                    ->required(),
                Select::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'l' => 'Laki-Laki',
                        'p' => 'Perempuan',
                    ])
                    ->required(),
                Select::make('status')
                    ->label('Jenis Kelamin')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Non-Aktif',
                    ])
                    ->default('active')
                    ->required()
            ]);
    }
}
