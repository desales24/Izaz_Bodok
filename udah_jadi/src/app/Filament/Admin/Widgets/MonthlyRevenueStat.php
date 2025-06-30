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

        // Tambahkan 12 card pemasukan bulanan
        foreach (range(1, 12) as $month) {
            $total = Payment::where('status', 'lunas')
                ->whereMonth('paid_at', $month)
                ->whereYear('paid_at', $year)
                ->sum('amount');

            $monthName = Carbon::create()->month($month)->locale('id')->translatedFormat('F');

            $cards[] = Card::make("Pemasukan $monthName", 'Rp ' . number_format($total, 0, ',', '.'))
                ->color('success');
        }

        // Tambahkan tombol Export Data Pemasukan
        $cards[] = Card::make('Export Data Pemasukan', 'Download Laporan Excel')
            ->color('primary')
            ->icon('heroicon-o-document')
            ->description('Klik untuk mengunduh laporan lengkap')
            ->extraAttributes([
                'class' => 'transform hover:scale-[1.01] transition-all cursor-pointer shadow-md',
                'style' => 'background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);'
            ])
            ->url(route('export.pemasukan'), true);

        // Tambahkan tombol Export Pesanan Hari Ini
        $cards[] = Card::make('Export Pesanan Hari Ini', 'Download Data Hari Ini')
            ->color('primary')
            ->icon('heroicon-o-document-arrow-down')
            ->url(route('export.semua.pesanan', now()->toDateString()), true)
            ->extraAttributes([
                'class' => 'cursor-pointer hover:scale-[1.01] transition',
            ]);

        return $cards;
    }
}
