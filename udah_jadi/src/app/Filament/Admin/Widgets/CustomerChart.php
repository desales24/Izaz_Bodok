<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class CustomerChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Pesanan per Bulan (12 Bulan ke Depan)';

    protected function getData(): array
    {
        // Ambil 12 bulan ke depan dari bulan ini
        $months = collect(range(0, 11))->map(function ($i) {
            return now()->copy()->addMonths($i)->startOfMonth();
        });

        // Ambil semua pesanan dari bulan ini sampai 12 bulan ke depan
        $orders = Order::whereBetween('order_time', [
                now()->startOfMonth(),
                now()->copy()->addMonths(11)->endOfMonth()
            ])
            ->get();

        // Group berdasarkan bulan (format Y-m)
        $grouped = $orders->groupBy(function ($order) {
            return Carbon::parse($order->order_time)->format('Y-m');
        });

        // Siapkan label dan data
        $labels = [];
        $values = [];

        foreach ($months as $month) {
            $key = $month->format('Y-m');
            $labels[] = $month->translatedFormat('M Y');
            $values[] = isset($grouped[$key]) ? $grouped[$key]->count() : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pesanan',
                    'data' => $values,
                    'backgroundColor' => '#34d399',
                    'borderColor' => '#059669',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // bisa diganti menjadi 'line' jika diinginkan
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
