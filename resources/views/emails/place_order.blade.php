<!DOCTYPE html>
<html>
<head>
    <title>Xác nhận đơn hàng KLShoes</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background-color: #0d6efd; color: #fff; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
        .footer { font-size: 12px; text-align: center; margin-top: 20px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>CẢM ƠN BẠN ĐÃ ĐẶT HÀNG!</h2>
        </div>
        
        <p>Xin chào <strong>{{ $order->fullname }}</strong>,</p>
        
        <p>Đơn hàng <strong>#{{ $order->tracking_no }}</strong> của bạn đã được ghi nhận thành công.</p>
        
        <p>Chúng tôi đã đính kèm <strong>Hóa đơn chi tiết (PDF)</strong> trong email này. Bạn vui lòng tải về để xem nhé.</p>

        <p>Nếu bạn chọn thanh toán qua VietQR, vui lòng hoàn tất chuyển khoản để chúng tôi giao hàng sớm nhất.</p>

        <br>
        <p>Trân trọng,<br>Đội ngũ KLShoes.</p>

        <div class="footer">
            &copy; 2026 KLShoes Store. Địa chỉ: Long Xuyên, An Giang.
        </div>
    </div>
</body>
</html>