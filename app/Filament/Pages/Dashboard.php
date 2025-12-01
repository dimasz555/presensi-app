<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\RecentActivities;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TopPerformance;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // protected static string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            RecentActivities::class,
            // TopPerformance::class,
        ];
    }

    // public function getColumns(): int | string | array
    // {
    //     return 2;
    // }
}
