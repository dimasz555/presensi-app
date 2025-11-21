<?php

namespace App\Filament\Resources\Payrolls\Schemas;

use App\Models\User;
use App\Models\Payroll;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Karyawan & Periode')
                    ->schema([
                        Select::make('user_id')
                            ->label('Karyawan')
                            ->options(fn() => User::whereHas('roles', fn($q) => $q->where('name', 'karyawan'))->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $user = User::find($state);
                                if ($user) {
                                    $set('basic_salary', $user->basic_salary ?? 0);
                                    $set('late_penalty_per_minute', $user->late_penalty_per_minute ?? 0);

                                    $month = $get('period_month');
                                    $year = $get('period_year');

                                    if ($month && $year) {
                                        self::calculateLatePenalty($user, $month, $year, $set);
                                    }
                                }
                            }),

                        Select::make('period_month')
                            ->options([
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ])
                            ->label('Bulan')
                            ->default((int)date('n'))
                            ->required()
                            ->native(false)
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $userId = $get('user_id');
                                $year = $get('period_year');

                                if ($userId && $year) {
                                    $user = User::find($userId);
                                    self::calculateLatePenalty($user, $state, $year, $set);
                                }
                            }),

                        TextInput::make('period_year')
                            ->numeric()
                            ->default((int)date('Y'))
                            ->label('Tahun')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $userId = $get('user_id');
                                $month = $get('period_month');

                                if ($userId && $month) {
                                    $user = User::find($userId);
                                    self::calculateLatePenalty($user, $month, $state, $set);
                                }
                            }),
                    ])
                    ->columns(1),

                Section::make('Informasi Gaji')
                    ->schema([
                        TextInput::make('basic_salary')
                            ->numeric()
                            ->label('Gaji Pokok')
                            ->prefix('Rp')
                            ->readOnly()
                            ->default(0)
                            ->required(),

                        TextInput::make('total_bonus')
                            ->numeric()
                            ->label('Total Bonus')
                            ->prefix('Rp')
                            ->default(0)
                            ->readOnly()
                            ->dehydrated(false),

                        TextInput::make('total_deductions')
                            ->numeric()
                            ->label('Total Potongan')
                            ->prefix('Rp')
                            ->default(0)
                            ->readOnly()
                            ->dehydrated(false),

                        TextInput::make('net_salary')
                            ->numeric()
                            ->label('Gaji Bersih')
                            ->prefix('Rp')
                            ->default(0)
                            ->readOnly()
                            ->dehydrated(false),
                    ])
                    ->columns(1),

                Section::make('Informasi Keterlambatan')
                    ->schema([
                        TextInput::make('late_penalty_per_minute')
                            ->numeric()
                            ->label('Denda per Menit')
                            ->prefix('Rp')
                            ->readOnly()
                            ->default(0)
                            ->dehydrated(false)
                            ->helperText('Denda keterlambatan per menit (dari data karyawan)'),

                        TextInput::make('total_late_minutes')
                            ->numeric()
                            ->label('Total Menit Terlambat')
                            ->suffix('menit')
                            ->readOnly()
                            ->default(0)
                            ->dehydrated(false)
                            ->helperText('Total menit terlambat di bulan ini'),

                        TextInput::make('total_late_penalty')
                            ->numeric()
                            ->label('Total Denda Keterlambatan')
                            ->prefix('Rp')
                            ->readOnly()
                            ->default(0)
                            ->dehydrated(false)
                            ->helperText('Denda = Total Menit × Denda per Menit'),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Section::make('Status Penggajian')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Selesai',
                                'rejected' => 'Dibatalkan',
                            ])
                            ->label('Status')
                            ->default('pending')
                            ->required()
                            ->native(false),
                    ])
                    ->columns(1),
            ]);
    }

    private static function calculateLatePenalty(?User $user, $month, $year, Set $set): void
    {
        if (!$user || !$month || !$year) {
            return;
        }

        $totalLateMinutes = $user->getTotalLateMinutesInMonth((int)$month, (int)$year);
        $totalLatePenalty = $user->getTotalLatePenaltyInMonth((int)$month, (int)$year);

        $set('total_late_minutes', $totalLateMinutes);
        $set('total_late_penalty', $totalLatePenalty);
    }
}
