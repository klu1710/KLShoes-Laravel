<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RevenueExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Lấy dữ liệu đơn hàng đã hoàn thành
    */
    public function collection()
    {
        return Order::where('status_message', 'completed')->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Mã Vận Đơn', 'Khách Hàng', 'Số Điện Thoại', 'Ngày Đặt', 'Tổng Tiền', 'Trạng Thái'
        ];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->tracking_no,
            $order->fullname,
            $order->phone,
            $order->created_at->format('d/m/Y'),
            $order->total_price,
            $order->status_message,
        ];
    }
}