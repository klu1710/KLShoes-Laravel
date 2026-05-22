<!DOCTYPE html>
<html>
<head>
    <title>Thông báo thanh toán mới</title>
</head>
<body>
    <h2> Khách hàng vừa xác nhận đã chuyển khoản!</h2>
    <p><strong>Mã vận đơn:</strong> {{ $order->tracking_no }}</p>
    <p><strong>Khách hàng:</strong> {{ $order->fullname }}</p>
    <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
    <p><strong>Tổng tiền:</strong> {{ number_format($order->total_price) }} đ</p>
    <hr>
    <p>Vui lòng kiểm tra tài khoản ngân hàng và xác nhận đơn hàng trên trang quản trị.</p>
</body>
</html>