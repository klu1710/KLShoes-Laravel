@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')

<div class="container mt-4">
    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle"></i> <strong>Thông báo:</strong> {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Vui lòng kiểm tra lại:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

<div class="py-3 py-md-4 checkout">
    <div class="container">
        <h4>Thanh toán đơn hàng</h4>
        <hr>

        <form action="{{ url('place-order') }}" method="POST">
            @csrf

            {{--  TRUYỀN CHẾ ĐỘ MUA NGAY/THƯỜNG --}}
            <input type="hidden" name="checkout_mode" value="{{ request('mode') }}">
            
            {{--  TRUYỀN DANH SÁCH SẢN PHẨM ĐÃ CHỌN  --}}
            <input type="hidden" name="selected_products" value="{{ request('items') }}">
            {{--  ---------------------------------------------------- --}}

            {{--  Ô ẨN LƯU TỔNG TIỀN (RAW NUMBER) ĐỂ JS XỬ LÝ --}}
            @php $rawTotalPrice = 0; @endphp

            <div class="row">
                
                <div class="col-md-7">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 font-weight-bold text-primary">1. Thông tin giao hàng</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Họ và tên (*)</label>
                                    <input type="text" name="fullname" value="{{ Auth::user()?->name ?? '' }}" class="form-control" placeholder="Nhập họ tên" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Số điện thoại (*)</label>
                                    <input type="text" name="phone" value="{{ Auth::user()?->phone ?? '' }}" class="form-control" placeholder="Nhập SĐT" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Email (*)</label>
                                    <input type="email" name="email" value="{{ Auth::user()?->email ?? '' }}" class="form-control" placeholder="Nhập Email" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Địa chỉ nhận hàng (*)</label>
                                    <textarea name="address" class="form-control" rows="2" placeholder="Số nhà, tên đường, phường/xã..." required>{{ Auth::user()->address ?? '' }}</textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Ghi chú đơn hàng (Tùy chọn)</label>
                                    <textarea name="note" class="form-control" rows="2" placeholder="Ví dụ: Giao giờ hành chính..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 font-weight-bold text-primary">2. Đơn hàng của bạn</h5>
                        </div>
                        <div class="card-body">
                            
                            {{-- BẢNG SẢN PHẨM --}}
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50%">Sản phẩm</th>
                                        <th>Giá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartItems as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->product->name }}</strong> 
                                            <br>
                                            <small>
                                                @if($item->color) 
                                                    Màu: {{ \App\Models\ProductColor::find($item->color)->name ?? $item->color }} | 
                                                @endif

                                                @if($item->size) Size: {{ $item->size }} | @endif
                                                SL: {{ $item->quantity }}
                                            </small>
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($item->product->getSellingPrice() * $item->quantity, 0, ',', '.') }}₫
                                        </td>
                                    </tr>
                                    {{-- Cộng dồn tổng tiền --}}
                                    @php $rawTotalPrice += $item->product->getSellingPrice() * $item->quantity; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <hr>

                            {{--   Ô INPUT ẨN CHỨA TỔNG TIỀN RAW --}}
                            <input type="hidden" id="raw_total_price" value="{{ $rawTotalPrice }}">

                            {{-- Ô NHẬP MÃ GIẢM GIÁ --}}
                            <div class="coupon-section mb-3">
                                <label class="fw-bold">Mã giảm giá</label>
                                <div class="input-group mt-1">
                                    <input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="Nhập mã (VD: KL17)">
                                    <button class="btn btn-primary apply-coupon-btn" type="button">Áp dụng</button>
                                </div>
                                <small id="coupon_message" class="fw-bold mt-1 d-block"></small>

                                {{-- MỤC CHỌN MÃ GIẢM GIÁ --}}
                                @if(isset($coupons) && count($coupons) > 0)
                                    <div class="mt-2 p-2 bg-light border rounded">
                                        <label class="small text-muted mb-1 fw-bold">🎁 Hoặc chọn mã ưu đãi có sẵn:</label>
                                        
                                        <select class="form-select form-select-sm border-primary" onchange="document.getElementById('coupon_code').value = this.value">
                                            <option value="">-- Chọn mã giảm giá --</option>
                                            @foreach($coupons as $coupon)
                                                <option value="{{ $coupon->code }}">
                                                    ➤ Mã: {{ $coupon->code }} 
                                                    (Giảm @if($coupon->type == '1' || $coupon->type == 'percent') {{ $coupon->value }}% @else {{ number_format($coupon->value, 0, ',', '.') }}đ @endif)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                            <hr>

                            {{-- HIỂN THỊ TIỀN --}}
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span class="fw-bold">
                                    {{ number_format($rawTotalPrice, 0, ',', '.') }}₫
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Giảm giá:</span>
                                <span class="fw-bold discount-price">- 0₫</span> 
                            </div>
                            <div class="d-flex justify-content-between mb-4 border-top pt-2">
                                <span class="h5">Tổng cộng:</span>
                                <span class="h5 text-primary grand-total">
                                    {{ number_format($rawTotalPrice, 0, ',', '.') }}₫
                                </span>
                            </div>

                            {{-- MỤC CHỌN PHƯƠNG THỨC THANH TOÁN --}}
                            <hr>
                            <h6 class="fw-bold mb-3">Phương thức thanh toán:</h6>
                            <div class="payment-options mb-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_mode" id="mode1" value="COD" checked>
                                    <label class="form-check-label" for="mode1" style="cursor: pointer;">
                                        <i class="fas fa-truck text-primary me-2"></i> Thanh toán khi nhận hàng (COD)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_mode" id="mode2" value="VIETQR">
                                    <label class="form-check-label" for="mode2" style="cursor: pointer;">
                                        <i class="fas fa-qrcode text-success me-2"></i> Chuyển khoản Ngân hàng (VietQR)
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary py-2 text-uppercase fw-bold">
                                    Xác nhận đặt hàng
                                </button>
                            </div>
                            
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection