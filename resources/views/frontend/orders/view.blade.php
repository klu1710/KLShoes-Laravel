@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')

<div class="py-3 py-md-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="shadow bg-white p-3">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-primary">
                            <i class="fa fa-shopping-cart text-dark"></i> Chi tiết đơn hàng
                        </h4>
                        <a href="{{ url('my-orders') }}" class="btn btn-danger btn-sm">Quay lại</a>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Thông tin giao hàng</h5>
                            <hr>
                            <label class="fw-bold">Họ tên:</label> {{ $order->fullname }} <br>
                            <label class="fw-bold">Email:</label> {{ $order->email }} <br>
                            <label class="fw-bold">SĐT:</label> {{ $order->phone }} <br>
                            <label class="fw-bold">Địa chỉ:</label> {{ $order->address }} <br>
                            <label class="fw-bold">Mã bưu vận:</label> {{ $order->tracking_no }} <br>
                        </div>
                        <div class="col-md-6">
                            <h5>Trạng thái đơn hàng</h5>
                            <hr>
                            <label class="fw-bold">Phương thức TT:</label> {{ $order->payment_mode }} <br>
                            <label class="fw-bold">Tình trạng:</label> 
                            @if($order->status_message == 'in progress')
                                <span class="badge bg-warning text-dark border border-warning">Đang xử lý</span>
                            @elseif($order->status_message == 'completed')
                                <span class="badge bg-success border border-success">Đã hoàn thành</span>
                            @elseif($order->status_message == 'cancelled')
                                <span class="badge bg-danger border border-danger">Đã hủy</span>
                            @else
                                <span class="badge bg-secondary">{{ $order->status_message }}</span>
                            @endif
                        </div>
                        
                        {{-- NÚT HỦY ĐƠN HÀNG --}}
                        <div class="mt-3">
                            @if($order->status_message == 'in progress')
                                <form action="{{ url('my-orders/'.$order->id.'/cancel') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger text-white w-100" 
                                            onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')">
                                        <i class="fa fa-times"></i> Yêu cầu Hủy đơn hàng
                                    </button>
                                </form>
                                <p class="small text-muted mt-2">
                                    * Lưu ý: Bạn chỉ có thể hủy khi đơn hàng chưa được vận chuyển.
                                </p>
                            @else
                                <button class="btn btn-secondary w-100" disabled>
                                    Không thể hủy ({{ $order->status_message }})
                                </button>
                            @endif
                        </div>
                    </div>

                    <br>
                    <h5>Sản phẩm đã mua</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Hình ảnh</th>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td width="10%">
                                            @if($item->product->image)
                                                <img src="{{ asset($item->product->image) }}" style="width: 50px; height: 50px; object-fit: cover" alt="">
                                            @else
                                                <img src="" style="width: 50px; height: 50px" alt="">
                                            @endif
                                        </td>
                                        <td>
                                            {{--  Biến tên sản phẩm thành đường link --}}
                                            @if($item->product->category)
                                                <a href="{{ url('collections/'.$item->product->category->slug.'/'.$item->product->slug) }}" target="_blank" class="text-dark fw-bold text-decoration-none">
                                                    {{ $item->product->name }}
                                                </a>
                                            @else
                                                {{ $item->product->name }}
                                            @endif
                                            
                                            <br>
                                            <small>
                                                @if($item->color) Màu: {{ $item->color }} @endif
                                                @if($item->size) | Size: {{ $item->size }} @endif
                                            </small>
                                        </td>
                                        <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="fw-bold">{{ number_format($item->quantity * $item->price, 0, ',', '.') }}₫</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            
                            {{-- PHẦN TỔNG TIỀN --}}
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Tổng tiền hàng:</td>
                                    <td colspan="1" class="fw-bold">{{ number_format($order->total_price + $order->discount_amount, 0, ',', '.') }}₫</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Giảm giá (Voucher):</td>
                                    <td colspan="1" class="fw-bold text-success">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold text-uppercase">Thực thu (Tổng thanh toán):</td>
                                    <td colspan="1" class="fw-bold text-danger h5">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection