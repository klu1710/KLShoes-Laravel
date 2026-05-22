@extends('layouts.app')

@section('title', 'Tra cứu đơn hàng')

@section('content')

<div class="py-3 mb-4 shadow-sm bg-warning border-top">
    <div class="container">
        <h6 class="mb-0">
            <a href="{{ url('/') }}" class="text-dark text-decoration-none">Trang chủ</a> / Tra cứu đơn hàng
        </h6>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            {{-- FORM NHẬP THÔNG TIN --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-search me-2"></i> Tra cứu tình trạng đơn hàng</h4>
                </div>
                <div class="card-body">
                    
                    @if(session('status'))
                        {{-- Hiển thị màu sắc thông báo hợp lý --}}
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ url('track-order') }}" method="POST">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-5 mb-3">
                                <label class="fw-bold">Mã vận đơn (Có trong Email)</label>
                                <input type="text" name="tracking_no" class="form-control" placeholder="VD: KLS-xxxxxxx" value="{{ request('tracking_no') }}" required>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="fw-bold">Số điện thoại đặt hàng</label>
                                <input type="text" name="phone" class="form-control" placeholder="Nhập SĐT của bạn" value="{{ request('phone') }}" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <button type="submit" class="btn btn-dark w-100">Tra cứu</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- KẾT QUẢ HIỂN THỊ --}}
            @if(isset($order))
                <div class="card shadow">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-success">
                            <i class="fas fa-check-circle"></i> Kết quả tìm kiếm: {{ $order->tracking_no }}
                        </h5>
                        
                        {{--  NÚT HỦY ĐƠN HÀNG (CHỈ HIỆN KHI ĐƠN MỚI)  --}}
                        @if($order->status_message == 'in progress' || $order->status_message == 'pending')
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                <i class="fas fa-times-circle"></i> Hủy đơn hàng này
                            </button>

                            {{-- MODAL XÁC NHẬN HỦY --}}
                            <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold text-danger">Xác nhận hủy đơn hàng?</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-dark">
                                            Bạn có chắc chắn muốn hủy đơn hàng <strong>{{ $order->tracking_no }}</strong> không?<br>
                                            Hành động này không thể hoàn tác.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                            
                                            {{-- Form gửi yêu cầu hủy --}}
                                            <form action="{{ url('track-order/cancel') }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                <input type="hidden" name="tracking_no" value="{{ $order->tracking_no }}">
                                                <input type="hidden" name="phone" value="{{ $order->phone }}">
                                                <button type="submit" class="btn btn-danger">Xác nhận Hủy</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--  KẾT THÚC MODeL  --}}
                        @endif

                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Người nhận:</strong> {{ $order->fullname }}</p>
                                <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
                                <p><strong>Trạng thái:</strong> 
                                    @if($order->status_message == 'in progress')
                                        <span class="badge bg-warning text-dark">Đang xử lý</span>
                                    @elseif($order->status_message == 'completed')
                                        <span class="badge bg-success">Giao thành công</span>
                                    @elseif($order->status_message == 'cancelled')
                                        <span class="badge bg-danger">Đã hủy</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $order->status_message }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- DANH SÁCH SẢN PHẨM --}}
                        <div class="table-responsive border p-2">
                            <table class="table mb-0">
                                <thead>
                                    <tr class="bg-light">
                                        <th>Hình ảnh</th>
                                        <th>Sản phẩm</th>
                                        <th>Giá</th>
                                        <th>SL</th>
                                        <th>Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderItems as $item)
                                        <tr>
                                            <td width="10%">
                                                @if($item->product->image)
                                                    <img src="{{ asset($item->product->image) }}" 
                                                         style="width: 50px; height: 50px; object-fit: cover" 
                                                         alt="{{ $item->product->name }}"
                                                         onerror="this.onerror=null;this.src='https://placehold.co/50x50?text=No+Img';">
                                                @else
                                                     <img src="https://placehold.co/50x50?text=No+Img" style="width: 50px;">
                                                @endif
                                            </td>
                                            <td>
                                                {{ $item->product->name }}
                                                @if($item->size) 
                                                    <br><small>Size: {{ $item->size }}</small> 
                                                @endif
                                                @if($item->color) 
                                                    <small>/ Màu: {{ \App\Models\ProductColor::find($item->color)->name ?? $item->color }}</small> 
                                                @endif
                                            </td>
                                            <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                            <td>x{{ $item->quantity }}</td>
                                            <td class="fw-bold">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Tổng thanh toán:</td>
                                        <td class="fw-bold text-danger">{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

@endsection