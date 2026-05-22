@extends('layouts.app')

@section('title')
    {{ $category->name }} - KL Shoes
@endsection

@section('content')

{{-- 1. THANH TIÊU ĐỀ --}}
<div class="py-3 mb-4 shadow-sm bg-warning border-top">
    <div class="container">
        <h6 class="mb-0">
            <a href="{{ url('/') }}" class="text-dark text-decoration-none">Trang chủ</a> / 
            Danh mục / 
            {{ $category->name }}
        </h6>
    </div>
</div>

<div class="container pb-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="fw-bold mb-0">Danh mục: {{ $category->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{--  LẶP QUA DANH SÁCH SẢN PHẨM --}}
                        @forelse($products as $product)
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card h-100 border-0 shadow-sm product-card">
                                    {{-- Link chi tiết --}}
                                    <a href="{{ url('collections/'.$category->slug.'/'.$product->slug) }}" class="text-decoration-none text-dark">
                                        
                                        {{-- ẢNH SẢN PHẨM --}}
                                        <div class="position-relative overflow-hidden">
                                            @if($product->image)
                                                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: contain;">
                                            @else
                                                <img src="https://via.placeholder.com/250" class="card-img-top" style="height: 250px; object-fit: cover;">
                                            @endif

                                            {{--  LOGIC NHÃN GIẢM GIÁ (MỚI) --}}
                                            @if($product->hasDiscount())
                                                <div class="position-absolute top-0 end-0 bg-warning text-dark px-2 py-1 m-2 rounded small fw-bold shadow-sm">
                                                    -{{ $product->discount_percent }}%
                                                </div>
                                            @endif

                                            {{-- Nhãn Hết hàng --}}
                                            @if($product->productSizes->sum('quantity') == 0)
                                                <div class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 m-2 rounded small shadow-sm">
                                                    Hết hàng
                                                </div>
                                            @endif
                                        </div>

                                        {{-- THÔNG TIN --}}
                                        <div class="card-body text-center d-flex flex-column">
                                            <h5 class="card-title fw-bold text-truncate" title="{{ $product->name }}" style="font-size: 16px;">
                                                {{ $product->name }}
                                            </h5>
                                            
                                            <div class="mt-auto">
                                                {{--  LOGIC HIỂN THỊ GIÁ THÔNG MINH --}}
                                                @if($product->hasDiscount())
                                                    {{-- Có giảm giá --}}
                                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                                        <span class="text-danger fw-bold fs-5">
                                                            {{ number_format($product->getSellingPrice(), 0, ',', '.') }}đ
                                                        </span>
                                                        <del class="text-muted small">
                                                            {{ number_format($product->original_price, 0, ',', '.') }}đ
                                                        </del>
                                                    </div>
                                                @else
                                                    {{-- Không giảm giá --}}
                                                    <span class="text-dark fw-bold fs-5">
                                                        {{ number_format($product->selling_price, 0, ',', '.') }}đ
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <button class="btn btn-outline-primary w-100 mt-2">Xem Chi Tiết</button>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 p-5 text-center">
                                <h4>Không có sản phẩm nào trong danh mục này :(</h4>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .product-card { transition: all 0.3s ease; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
</style>

@endsection