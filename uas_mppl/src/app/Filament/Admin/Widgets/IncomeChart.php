<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class IncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Pemasukan per Bulan (12 Bulan ke Depan)';

    protected function getData(): array
    {
        // Buat array 12 bulan ke depan
        $months = collect(range(0, 11))->map(fn ($i) => now()->copy()->addMonths($i)->startOfMonth());

        // Ambil pembayaran status 'lunas' dari bulan ini sampai 12 bulan ke depan berdasarkan paid_at
        $payments = Payment::where('status', 'lunas')
            ->whereBetween('paid_at', [
                now()->startOfMonth(),
                now()->copy()->addMonths(11)->endOfMonth(),
            ])
            ->get();

        // Kelompokkan pembayaran berdasarkan Y-m (bulan)
        $grouped = $payments->groupBy(fn ($payment) => Carbon::parse($payment->paid_at)->format('Y-m'));

        // Siapkan label & data
        $labels = [];
        $values = [];

        foreach ($months as $month) {
            $key = $month->format('Y-m');
            $labels[] = $month->translatedFormat('M Y');
            $values[] = ($grouped[$key] ?? collect())->sum('amount');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Pemasukan',
                    'data' => $values,
                    'backgroundColor' => '#4ade80',
                    'borderColor' => '#16a34a',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getHeight(): string|int
    {
        return 300;
    }

    public function getColumnSpan(): int|string|array
    {
        return 1;
    }
}
