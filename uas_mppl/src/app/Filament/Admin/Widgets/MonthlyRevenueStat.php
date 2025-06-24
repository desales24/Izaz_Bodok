<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Carbon;

class MonthlyRevenueStat extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        $year = now()->year;
        $cards = [];

        foreach (range(1, 12) as $month) {
            $total = Payment::where('status', 'lunas')
                ->whereMonth('paid_at', $month)
                ->whereYear('paid_at', $year)
                ->sum('amount');

            $monthName = Carbon::create()->month($month)->locale('id')->translatedFormat('F');

            $cards[] = Card::make("Pemasukan $monthName", 'Rp ' . number_format($total, 0, ',', '.'))
                ->color('success');
        }

        // Card tambahan untuk tombol export
        $cards[] = Card::make('Export Data Pemasukan', 'Download Laporan Excel')
            ->color('primary')
            ->icon('heroicon-o-document')
            ->description('Klik untuk mengunduh laporan lengkap')
            ->extraAttributes([
                'class' => 'transform hover:scale-[1.01] transition-all cursor-pointer shadow-md',
                'style' => 'background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);'
            ])
            ->url(
                route('export.pemasukan'),
                true // Open in new tab
            );

        return $cards;
    }
}
