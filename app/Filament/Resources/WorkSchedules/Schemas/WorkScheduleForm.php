<?php

namespace App\Filament\Resources\WorkSchedules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class WorkScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('work_day')
                    ->label('Hari Kerja')
                    ->options([
                        'monday' => 'Senin',
                        'tuesday' => 'Selasa',
                        'wednesday' => 'Rabu',
                        'thursday' => 'Kamis',
                        'friday' => 'Jumat',
                        'saturday' => 'Sabtu',
                        'sunday' => 'Minggu',
                    ])
                    ->required(),
                TimePicker::make('work_start_time')
                    ->label('Jam Mulai Kerja')
                    ->required()
                    ->placeholder('Contoh: 08:00'),

                TimePicker::make('work_end_time')
                    ->label('Jam Selesai Kerja')
                    ->required()
                    ->helperText('Jam selesai harus lebih besar dari jam mulai'),
            ]);
    }
}
