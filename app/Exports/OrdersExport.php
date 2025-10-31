<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Order::select('id', 'store_id', 'total', 'status', 'created_at')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Store ID',
            'Total (€)',
            'Status',
            'Created At',
        ];
    }
}
