@extends('layouts.app')

@section('title')
    Giỏ hàng của tôi
@endsection

@section('content')
<div class="py-3 mb-4 shadow-sm bg-warning border-top">
    <div class="container">
        <h6 class="mb-0">
            <a href="{{ url('/') }}" class="text-dark text-decoration-none">Trang chủ</a> / 
            Giỏ hàng
        </h6>
    </div>
</div>

<div class="container my-5">
    <div class="card shadow">
        <div class="card-body">
            @if($cartItems->count() > 0)
                <div class="table-responsive">
                    <table class="table text-center align-middle">
                        <thead>
                            <tr>
                                <th style="width: 5%">
                                    <input type="checkbox" id="selectAll" checked style="width: 20px; height: 20px; cursor: pointer;">
                                </th>
                                <th>Hình ảnh</th>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Tổng</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $item)
                                
                                {{--  LỚP GIÁP BẢO VỆ: KIỂM TRA SẢN PHẨM CÓ CÒN TỒN TẠI KHÔNG  --}}
                                @if($item->product)
                                
                                    <tr class="product_data">
                                        {{-- CHECKBOX --}}
                                        <td>
                                            <input type="checkbox" 
                                                   class="select-item form-check-input" 
                                                   value="{{ $item->product_id }}"
                                                   checked 
                                                   data-price="{{ $item->product->getSellingPrice() }}" 
                                                   style="width: 20px; height: 20px; cursor: pointer;">
                                        </td>

                                        {{-- HÌNH ẢNH --}}
                                        <td style="width: 10%">
                                            @php
                                                $imagePath = $item->product->image; 
                                                if (!$imagePath && $item->product->productImages->count() > 0) {
                                                    $imagePath = $item->product->productImages[0]->image;
                                                }
                                            @endphp

                                            @if($imagePath)
                                                <img src="{{ asset($imagePath) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     style="height: 70px; width: 70px; object-fit: cover;"
                                                     onerror="this.onerror=null;this.src='https://placehold.co/70x70?text=No+Image';">
                                            @else
                                                <img src="https://placehold.co/70x70?text=No+Image" alt="No Image" style="height: 70px; width: 70px;">
                                            @endif
                                        </td>

                                        {{-- TÊN SP --}}
                                        <td style="width: 30%" class="text-start">
                                            <h6 class="fw-bold">{{ $item->product->name }}</h6>
                                            <small class="text-muted">
                                                @if($item->color)
                                                    Màu: {{ \App\Models\ProductColor::find($item->color)->name ?? $item->color }}
                                                @endif
                                                @if($item->size)
                                                    | Size: {{ $item->size }}
                                                @endif
                                            </small>
                                        </td>

                                        {{-- GIÁ --}}
                                        <td style="width: 15%">
                                            <span class="fw-bold">
                                                {{ number_format($item->product->getSellingPrice(), 0, ',', '.') }} đ
                                            </span>
                                        </td>

                                        {{-- SỐ LƯỢNG --}}
                                        <td style="width: 15%">
                                            <div class="input-group input-group-sm text-center mb-3" style="width: 110px; margin: 0 auto;">
                                                <button class="input-group-text decrement-btn">-</button>
                                                <input type="hidden" class="prod_id" value="{{ $item->product_id }}">
                                                <input type="text" value="{{ $item->quantity }}" class="form-control qty-input text-center" readonly />
                                                <button class="input-group-text increment-btn">+</button>
                                            </div>
                                        </td>
                                        
                                        {{-- THÀNH TIỀN --}}
                                        <td style="width: 15%">
                                            {{ number_format($item->product->getSellingPrice() * $item->quantity, 0, ',', '.') }} đ
                                        </td>

                                        {{-- NÚT XÓA --}}
                                        <td style="width: 10%">
                                            <button class="btn btn-danger btn-sm delete-cart-item">
                                                <i class="fa fa-trash"></i> 
                                            </button>
                                        </td>
                                    </tr>
                                    
                                {{--  ĐÓNG LỚP BẢO VỆ  --}}
                                @endif
                                
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white border-0 p-3">
                    <h5 class="fw-bold d-flex justify-content-between align-items-center">
                        Tổng thanh toán: 
                        <span class="text-danger fs-4" id="total-price-display">0 đ</span>
                    </h5>
                    <hr>
                    
                    <button class="btn btn-success w-100 py-2 fw-bold text-uppercase checkOutBtn">
                        Tiến hành Thanh toán <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            @else
                <div class="text-center p-5">
                    <h3><i class="fa fa-shopping-cart fa-3x text-muted"></i></h3>
                    <h4 class="mt-3">Giỏ hàng của bạn đang trống</h4>
                    <a href="{{ url('/') }}" class="btn btn-outline-primary mt-3">Tiếp tục mua sắm</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection