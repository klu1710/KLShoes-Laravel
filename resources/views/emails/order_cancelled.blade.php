<!DOCTYPE html>
<html>
<head>
    <title>Thông báo Hủy đơn hàng</title>
</head>
<body>
    <h2> Có một đơn hàng vừa bị khách hủy!</h2>
    <p>Xin chào Admin,</p>
    <p>Khách hàng <strong>{{ $order->fullname }}</strong> vừa thực hiện hủy đơn hàng.</p>
    
    <p><strong>Thông tin đơn hàng:</strong></p>
    <ul>
        <li>Mã đơn: <strong>{{ $order->tracking_no }}</strong></li>
        <li>Lý do: Khách tự hủy trên Website</li>
        <li>Thời gian: {{ date('d-m-Y H:i:s') }}</li>
    </ul>

    <p>Vui lòng kiểm tra lại kho hàng và không giao đơn này nhé.</p>
    <p><a href="{{ url('admin/orders/'.$order->id) }}"> Xem chi tiết đơn hàng tại đây</a></p>
    
    <p>Trân trọng,<br>Hệ thống KLShoes</p>
</body>
</html>