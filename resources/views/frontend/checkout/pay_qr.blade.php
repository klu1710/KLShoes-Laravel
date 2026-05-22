@extends('layouts.app')

@section('title', 'Thanh toán VietQR')

@section('content')

@php
    $bankClientId = 'VCB';          
    $accountNo    = '1030049630';   
    $accountName  = 'LUU DOAN TRUNG KIEN'; 
    $content      = 'THANHTOAN ' . $order->tracking_no; 
    $qrUrl = "https://img.vietqr.io/image/{$bankClientId}-{$accountNo}-compact2.png?amount={$order->total_price}&addInfo={$content}&accountName={$accountName}";
@endphp

<div class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow border-0 text-center">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0 fw-bold">THANH TOÁN CHUYỂN KHOẢN</h4>
                    </div>
                    <div class="card-body p-4">
                        
                        {{-- ĐỒNG HỒ ĐẾM NGƯỢC --}}
                        <div class="alert alert-danger d-inline-block p-2 px-4 mb-3">
                            <span class="small">Mã QR hết hạn sau:</span><br>
                            <h2 class="mb-0 fw-bold" id="countdown-timer">03:00</h2>
                        </div>

                        <p>Vui lòng quét mã QR bên dưới để thanh toán.</p>

                        {{-- ẢNH QR CODE --}}
                        <div class="border p-2 d-inline-block rounded mb-3 bg-white position-relative">
                            <img id="qr-image" src="{{ $qrUrl }}" alt="Mã QR Thanh Toán" style="width: 100%; max-width: 350px;">
                            
                            {{-- LỚP MỜ KHI HẾT GIỜ --}}
                            <div id="qr-overlay" class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex align-items-center justify-content-center d-none" style="z-index: 10;">
                                <div class="text-center">
                                    <i class="fa fa-sync fa-spin fa-3x text-danger mb-2"></i>
                                    <h5 class="fw-bold text-danger">Mã đã hết hạn!</h5>
                                    <button onclick="location.reload()" class="btn btn-sm btn-danger mt-2">Lấy mã mới</button>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning text-start" role="alert">
                            <strong><i class="fa fa-info-circle"></i> Thông tin chuyển khoản thủ công:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Ngân hàng: <strong>VietComBank (VCB)</strong></li>
                                <li>Số tài khoản: <strong>{{ $accountNo }}</strong></li>
                                <li>Chủ tài khoản: <strong>{{ $accountName }}</strong></li>
                                <li>Số tiền: <strong class="text-danger fs-5">{{ number_format($order->total_price, 0, ',', '.') }}đ</strong></li>
                                <li>Nội dung CK: <strong class="text-primary">{{ $content }}</strong></li>
                            </ul>
                        </div>

                        <p class="small text-muted">
                            * Sau khi chuyển khoản, vui lòng bấm nút xác nhận bên dưới để thông báo cho Shop.
                        </p>

                        {{-- FORM XÁC NHẬN ĐÃ THANH TOÁN (GỬI MAIL) --}}
                        <form action="{{ url('confirm-payment/'.$order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 py-2 mb-2 fw-bold">
                                <i class="fa fa-check-circle"></i> TÔI ĐÃ THANH TOÁN XONG
                            </button>
                        </form>

                        <a href="{{ url('/') }}" class="btn btn-link text-decoration-none">
                            <i class="fa fa-arrow-left"></i> Quay về trang chủ
                        </a>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT ĐẾM NGƯỢC 3 PHÚT --}}
<script>
    // Thời gian đếm ngược: 3 phút = 180 giây
    var timeLeft = 180; 
    var timerElement = document.getElementById('countdown-timer');
    var qrOverlay = document.getElementById('qr-overlay');

    var countdown = setInterval(function() {
        var minutes = Math.floor(timeLeft / 60);
        var seconds = timeLeft % 60;

        // Thêm số 0 đằng trước nếu < 10 (VD: 09, 05)
        if (seconds < 10) seconds = "0" + seconds;
        if (minutes < 10) minutes = "0" + minutes;

        timerElement.innerHTML = minutes + ":" + seconds;

        if (timeLeft <= 0) {
            clearInterval(countdown);
            timerElement.innerHTML = "00:00";
            timerElement.classList.add('text-danger');
            
            // Hiển thị lớp phủ làm mờ mã QR
            qrOverlay.classList.remove('d-none');
        }

        timeLeft -= 1;
    }, 1000); // Chạy mỗi 1 giây
</script>

@endsection