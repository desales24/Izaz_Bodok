<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Admin\Widgets\IncomeChart;
use App\Filament\Admin\Widgets\CustomerChart;
use App\Filament\Admin\Widgets\MonthlyRevenueStat;

class Dashboard extends BaseDashboard
{
    protected function getHeaderWidgets(): array
    {
        return [
            IncomeChart::class,
            CustomerChart::class,
            MonthlyRevenueStat::class,
        ];
    }
}
