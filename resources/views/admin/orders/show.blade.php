@extends('admin.index')

@section('admin_content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h4 class="m-0 font-weight-bold text-primary">CHI TIẾT ĐƠN HÀNG</h4>
        <a href="{{ url('admin/orders') }}" class="btn btn-danger btn-sm">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card-body">
        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <div class="row">
            {{-- CỘT TRÁI: THÔNG TIN KHÁCH & ĐƠN HÀNG --}}
            <div class="col-md-6">
                <h5 class="font-weight-bold text-dark">Thông tin vận chuyển</h5>
                <hr>
                <div class="border p-3 rounded">
                    {{-- 👇 THÊM PHẦN NÀY ĐỂ PHÂN BIỆT KHÁCH --}}
                    <label>Loại tài khoản:</label> 
                    @if($order->user_id == null)
                        <span class="badge bg-secondary text-white" style="background-color: #6c757d;">Khách vãng lai (Guest)</span>
                    @else
                        <span class="badge bg-success text-white" style="background-color: #198754;">Thành viên (ID: {{ $order->user_id }})</span>
                    @endif
                    <br>
                    {{-- 👆 --------------------------------- --}}

                    <label>Họ tên:</label> <strong>{{ $order->fullname }}</strong> <br>
                    <label>Email:</label> {{ $order->email }} <br>
                    <label>Điện thoại:</label> {{ $order->phone }} <br>
                    <label>Địa chỉ:</label> {{ $order->address }} <br>
                    <label>Mã bưu vận:</label> {{ $order->tracking_no }} <br>
                    <label>Ngày đặt:</label> {{ $order->created_at->format('d/m/Y h:i A') }} <br>
                    <label>Phương thức:</label> {{ $order->payment_mode }} <br>
                    
                    <label class="mt-2">Trạng thái hiện tại:</label> 
                    @if($order->status_message == 'in progress')
                        <span class="badge bg-warning text-dark border border-warning">Đang xử lý</span>
                    @elseif($order->status_message == 'completed')
                        <span class="badge bg-success text-white border border-success">Đã hoàn thành</span>
                    @elseif($order->status_message == 'cancelled')
                        <span class="badge bg-danger text-white border border-danger">Đã hủy</span>
                    @else
                        <span class="badge bg-secondary text-white">{{ $order->status_message }}</span>
                    @endif
                </div>
            </div>

            {{-- CỘT PHẢI: CẬP NHẬT TRẠNG THÁI --}}
            <div class="col-md-6">
                <h5 class="font-weight-bold text-dark">Quy trình xử lý</h5>
                <hr>
                <div class="border p-3 rounded">
                    <form action="{{ url('admin/orders/'.$order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <label class="font-weight-bold">Cập nhật trạng thái đơn hàng:</label>
                        <select name="order_status" class="form-select form-control mb-3">
                            <option value="in progress" {{ $order->status_message == 'in progress' ? 'selected':'' }}>Đang xử lý (Mới)</option>
                            <option value="completed" {{ $order->status_message == 'completed' ? 'selected':'' }}>Đã hoàn thành (Giao xong)</option>
                            <option value="pending" {{ $order->status_message == 'pending' ? 'selected':'' }}>Đang vận chuyển</option>
                            <option value="cancelled" {{ $order->status_message == 'cancelled' ? 'selected':'' }}>Đã hủy</option>
                        </select>

                        <button type="submit" class="btn btn-primary w-100">Cập nhật trạng thái</button>
                    </form>
                </div>
            </div>
        </div>

        <br>
        
        {{-- DANH SÁCH SẢN PHẨM MUA --}}
        <h5 class="font-weight-bold text-dark mt-3">Danh sách sản phẩm</h5>
        <div class="table-responsive border rounded">
            <table class="table table-bordered table-striped mb-0">
                <thead class="bg-light text-dark">
                    <tr>
                        <th>ID SP</th>
                        <th>Hình ảnh</th>
                        <th>Sản phẩm</th>
                        <th>Giá bán</th>
                        <th>Số lượng</th>
                        <th>Tổng tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
                        <tr>
                            <td width="10%">{{ $item->product_id }}</td>
                            <td width="10%">
                                {{-- Thêm xử lý ảnh lỗi cho Admin luôn cho đẹp --}}
                                @if($item->product->image)
                                    <img src="{{ asset($item->product->image) }}" 
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;" 
                                         alt="{{ $item->product->name }}"
                                         onerror="this.onerror=null;this.src='https://placehold.co/50x50?text=No+Img';">
                                @else
                                    <span class="text-muted small">Không hình</span>
                                @endif
                            </td>
                            <td>
                                <b>{{ $item->product->name }}</b>
                                <br>
                                <small class="text-muted">
                                    @if($item->color) Màu: {{ \App\Models\ProductColor::find($item->color)->name ?? $item->color }} @endif
                                    @if($item->size) | Size: {{ $item->size }} @endif
                                </small>
                            </td>
                            <td width="15%">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                            <td width="10%">{{ $item->quantity }}</td>
                            <td width="15%" class="font-weight-bold text-primary">
                                {{ number_format($item->quantity * $item->price, 0, ',', '.') }}₫
                            </td>
                        </tr>
                    @endforeach

                    {{-- PHẦN TỔNG TIỀN --}}
                    <tr>
                        <td colspan="5" class="text-right font-weight-bold">Tổng tiền hàng:</td>
                        <td colspan="1" class="font-weight-bold">{{ number_format($order->total_price + $order->discount_amount, 0, ',', '.') }}₫</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right font-weight-bold">Giảm giá (Coupon):</td>
                        <td colspan="1" class="font-weight-bold text-success">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right font-weight-bold text-uppercase">Thực thu (Tổng thanh toán):</td>
                        <td colspan="1" class="font-weight-bold text-danger h5">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection