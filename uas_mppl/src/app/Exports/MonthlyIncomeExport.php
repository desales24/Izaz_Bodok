<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthlyIncomeExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return DB::table('orders')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as bulan, SUM(total) as total_pemasukan')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
    }

    public function headings(): array
    {
        return ['Bulan', 'Total Pemasukan'];
    }
}
