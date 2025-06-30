<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthlyIncomeExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return DB::table('payments')
            ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as bulan, SUM(amount) as total_pemasukan')
            ->where('status', 'lunas')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
    }

    public function headings(): array
    {
        return ['Bulan', 'Total Pemasukan'];
    }
}
