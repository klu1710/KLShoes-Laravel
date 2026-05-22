<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Lấy dữ liệu từ Database
    */
    public function collection()
    {
        // Lấy tất cả đơn hàng, sắp xếp mới nhất
        return Order::orderBy('created_at', 'desc')->get();
    }

    /**
     * Định nghĩa các cột muốn xuất ra Excel
     */
    public function map($order): array
    {
        return [
            $order->id,
            $order->tracking_no,
            $order->fullname,
            $order->phone,
            number_format($order->total_price) . ' VNĐ', 
            $order->payment_mode,
            $order->status_message,
            $order->created_at->format('d/m/Y'), 
        ];
    }

    /**
     * Đặt tên tiêu đề cho các cột (Dòng đầu tiên trong Excel)
     */
    public function headings(): array
    {
        return [
            'ID',
            'Mã Đơn Hàng',
            'Tên Khách Hàng',
            'Số Điện Thoại',
            'Tổng Tiền',
            'Hình Thức TT',
            'Trạng Thái',
            'Ngày Đặt',
        ];
    }
}