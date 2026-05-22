@extends('layouts.app')

@section('title', 'Chính Sách Bảo Hành & Đổi Trả - KLShoes')

@section('content')

<div class="py-5 bg-light">
    <div class="container">
        <div class="row">
            
            {{-- CỘT TRÁI: NỘI DUNG CHÍNH --}}
            <div class="col-md-9">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h4 class="mb-0 fw-bold text-uppercase mt-2"><i class="fas fa-shield-alt text-warning me-2"></i> Chính sách bảo hành & Đổi hàng</h4>
                    </div>
                    <div class="card-body p-4 text-secondary" style="line-height: 1.8;">
                        
                        <h5 class="fw-bold text-dark">I. Chính sách bảo hành sản phẩm</h5>
                        <p class="fst-italic text-primary">"KLShoes cam kết tất cả các sản phẩm bán ra đều là hàng chính hãng 100%!"</p>
                        
                        <p>Trong quá trình sử dụng, nếu phát hiện sản phẩm có những vấn đề do lỗi của nhà sản xuất, KLShoes cam kết bảo hành miễn phí cho Quý khách:</p>
                        <ul>
                            <li><strong>Thời gian bảo hành:</strong> 12 tháng kể từ ngày mua hàng.</li>
                            <li><strong>Các lỗi được bảo hành:</strong> Bong keo, sứt chỉ, lỗi để giày do nhà sản xuất.</li>
                            <li><strong>Lưu ý:</strong> Chúng tôi sẽ không bảo hành nếu lỗi do người sử dụng gây ra (trầy xước, rách do va quẹt, thú vật cắn, phai màu do dùng chất tẩy rửa mạnh...).</li>
                        </ul>

                        <hr>

                        <h5 class="fw-bold text-dark mt-4">II. Chính sách đổi hàng và hoàn tiền</h5>
                        
                        <p><strong>1. Điều kiện đổi hàng:</strong></p>
                        <ul>
                            <li>KLShoes hỗ trợ đổi size/mẫu trong vòng <strong>30 ngày</strong> kể từ ngày nhận hàng.</li>
                            <li>Sản phẩm phải còn nguyên vẹn, chưa qua sử dụng, còn nguyên tem mác, hộp và quà tặng kèm (nếu có).</li>
                            <li>Sản phẩm không bị dơ bẩn, trầy xước, có mùi lạ.</li>
                        </ul>

                        <p><strong>2. Cam kết hoàn tiền:</strong></p>
                        <ul>
                            <li>Hoàn tiền <strong>200%</strong> nếu Quý khách phát hiện hàng giả, hàng nhái (Fake).</li>
                            <li>Hoàn tiền 100% nếu sản phẩm bị lỗi nặng không thể khắc phục hoặc hết size để đổi.</li>
                        </ul>

                        <hr>

                        <h5 class="fw-bold text-dark mt-4">III. Liên hệ hỗ trợ</h5>
                        <p>Mọi thông tin về việc bảo hành, đổi hàng Quý khách vui lòng liên hệ với bộ phận chăm sóc khách hàng của KLShoes:</p>
                        <div class="alert alert-warning border-0 text-dark">
                            <p class="mb-1"><i class="fas fa-phone-alt me-2"></i> Hotline: <strong>0783.177.977</strong></p>
                            <p class="mb-0"><i class="fas fa-envelope me-2"></i> Email: <strong>cskh.klshoes@gmail.com</strong></p>
                        </div>

                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI: BANNER QUẢNG CÁO & MENU PHỤ --}}
            <div class="col-md-3">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-uppercase">Danh mục hỗ trợ</h6>
                        <ul class="list-unstyled mt-3">
                            <li class="mb-2"><a href="{{ url('/') }}" class="text-decoration-none text-muted"><i class="fas fa-angle-right me-2"></i>Trang chủ</a></li>
                            <li class="mb-2"><a href="{{ url('all-products') }}" class="text-decoration-none text-muted"><i class="fas fa-angle-right me-2"></i>Sản phẩm</a></li>
                            <li class="mb-2"><a href="{{ url('track-order') }}" class="text-decoration-none text-muted"><i class="fas fa-angle-right me-2"></i>Tra cứu đơn hàng</a></li>
                            <li class="mb-2"><a href="{{ url('chinh-sach-bao-hanh') }}" class="text-decoration-none fw-bold text-danger"><i class="fas fa-angle-right me-2"></i>Chính sách bảo hành</a></li>
                        </ul>
                    </div>
                </div>

                {{-- Banner Sale nhỏ --}}
                <div class="card border-0 shadow-sm text-white text-center" style="background: linear-gradient(45deg, #ff357a, #fff172);">
                    <div class="card-body py-5">
                        <h3 class="fw-bold">SIÊU SALE</h3>
                        <h1 class="display-4 fw-bold">50%</h1>
                        <p class="mb-4">OFF</p>
                        <a href="{{ url('sale-off') }}" class="btn btn-light fw-bold px-4 rounded-pill text-danger">XEM NGAY</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection