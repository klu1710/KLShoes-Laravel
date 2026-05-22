<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Hóa đơn bán hàng</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; } /* DejaVu Sans hỗ trợ tiếng Việt */
        .invoice-box { width: 100%; border: 1px solid #eee; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #333; }
        table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        table td { padding: 5px; vertical-align: top; }
        table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
        table tr.item td { border-bottom: 1px solid #eee; }
        table tr.total td { border-top: 2px solid #333; font-weight: bold; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <h1>HÓA ĐƠN BÁN HÀNG</h1>
            <p>KLShoes Store - Uy tín chất lượng</p>
        </div>

        <table cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 60%;">
                    <strong>Khách hàng:</strong> {{ $order->fullname }}<br>
                    <strong>Địa chỉ:</strong> {{ $order->address }}<br>
                    <strong>SĐT:</strong> {{ $order->phone }}<br>
                    <strong>Email:</strong> {{ $order->email }}
                </td>
                <td style="width: 40%; text-align: right;">
                    <strong>Mã đơn hàng:</strong> #{{ $order->tracking_no }}<br>
                    <strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y') }}<br>
                    <strong>Thanh toán:</strong> {{ $order->payment_mode }}
                </td>
            </tr>
        </table>
        <br>

        <table cellpadding="0" cellspacing="0">
            <tr class="heading">
                <td>Sản phẩm</td>
                <td class="text-right">Đơn giá</td>
                <td class="text-right">SL</td>
                <td class="text-right">Thành tiền</td>
            </tr>

            @foreach($order->orderItems as $item)
            <tr class="item">
                <td>
                    {{ $item->product->name }} <br>
                    <small>({{ $item->color }} / {{ $item->size }})</small>
                </td>
                <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
            </tr>
            @endforeach

            <tr class="total">
                <td colspan="3" class="text-right">Tổng cộng:</td>
                <td class="text-right">{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
            </tr>
        </table>
        
        <br><br>
        <p style="text-align: center;">Cảm ơn quý khách đã mua hàng!</p>
    </div>
</body>
</html>