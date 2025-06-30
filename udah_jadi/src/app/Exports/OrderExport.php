<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('users', 'orders.customer_id', '=', 'users.id')
            ->selectRaw('
                DATE(orders.order_time) as tanggal,
                users.name as pelanggan,
                menus.name as menu,
                menus.price as harga_satuan,
                order_items.quantity as jumlah,
                (menus.price * order_items.quantity) as subtotal,
                orders.total as total_pesanan
            ')
            ->orderBy('orders.order_time')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Pelanggan',
            'Menu',
            'Harga Satuan',
            'Jumlah',
            'Subtotal',
            'Total Pesanan',
        ];
    }
}
