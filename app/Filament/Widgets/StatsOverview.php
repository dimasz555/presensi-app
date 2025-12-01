<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalKaryawan = User::role('karyawan')->count();
        $karyawanAktif = User::role('karyawan')->where('status', 'active')->count();
        $karyawanTidakAktif = User::role('karyawan')->where('status', 'inactive')->count();

        return [
            Stat::make('Total Karyawan', $totalKaryawan)
                ->description('Semua karyawan terdaftar')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('primary'),

            Stat::make('Karyawan Aktif', $karyawanAktif)
                ->description('Karyawan dengan status aktif')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Karyawan Tidak Aktif', $karyawanTidakAktif)
                ->description('Karyawan dengan status tidak aktif')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}
