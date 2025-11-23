<?php

namespace App\Filament\Resources\LeaveRequests\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Karyawan')
                    ->options(fn() => User::whereHas('roles', fn($q) => $q->where('name', 'karyawan'))->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->beforeOrEqual('end_date'),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->afterOrEqual('start_date'),
                Textarea::make('reason')
                    ->label('Keterangan')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'approved' => 'Diterima', 'rejected' => 'Ditolak'])
                    ->default('pending')
                    ->required(),
            ]);
    }
}
