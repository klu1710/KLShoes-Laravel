@extends('layouts.app')

@section('title', 'Săn Sale Giá Sốc - KLShoes')

@section('content')

<div class="py-5 bg-light" style="min-height: 80vh;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center mb-4">
                <h4 class="fw-bold text-danger text-uppercase">🔥 SẢN PHẨM ĐANG GIẢM GIÁ SỐC 🔥</h4>
                <p class="text-muted">Cơ hội sở hữu giày xịn giá tốt nhất hôm nay!</p>
            </div>

            @forelse ($products as $productItem)
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100 product-card border-0 position-relative">
                    
                    {{-- Tính % giảm giá --}}
                    @php
                        $discount = 0;
                        if($productItem->original_price > 0) {
                            $discount = round((($productItem->original_price - $productItem->selling_price) / $productItem->original_price) * 100);
                        }
                    @endphp

                    {{--  SỬA GIAO DIỆN TEM GIẢM GIÁ  --}}
                    @if($discount > 0)
                        <div class="position-absolute bg-warning text-dark px-2 py-1 rounded" 
                             style="top: 10px; right: 10px; z-index: 10; font-weight: bold; font-size: 14px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                            -{{ $discount }}%
                        </div>
                    @endif
                    {{--  ------------------------------------------  --}}

                    <div class="product-card-img text-center p-3 bg-white rounded-top position-relative overflow-hidden">
                        <a href="{{ url('/collections/'.$productItem->category->slug.'/'.$productItem->slug) }}">
                            
                            {{-- GỌI ẢNH AN TOÀN --}}
                            @if($productItem->image)
                                <img src="{{ asset($productItem->image) }}" 
                                     alt="{{ $productItem->name }}" 
                                     class="img-fluid transition-transform" 
                                     style="height: 220px; object-fit: contain; transition: transform 0.3s;">
                            @else
                                <img src="https://via.placeholder.com/220x220?text=No+Image" 
                                     alt="Chưa có ảnh" 
                                     class="img-fluid" 
                                     style="height: 220px; object-fit: contain; opacity: 0.6">
                            @endif
                            
                        </a>
                    </div>
                    
                    <div class="card-body border-top bg-white">
                        
                        <p class="mb-1 text-muted small text-uppercase fw-bold">
                            {{ $productItem->brand->name ?? 'KLShoes' }}
                        </p>

                        <h6 class="product-name mb-3" style="height: 40px; overflow: hidden;">
                           <a href="{{ url('/collections/'.$productItem->category->slug.'/'.$productItem->slug) }}" class="text-dark text-decoration-none fw-bold">
                                {{ $productItem->name }}
                           </a>
                        </h6>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="d-block text-muted text-decoration-line-through small">
                                    {{ number_format($productItem->original_price) }}đ
                                </span>
                                <span class="fw-bold text-danger fs-5">
                                    {{ number_format($productItem->selling_price) }}đ
                                </span>
                            </div>
                            <a href="{{ url('/collections/'.$productItem->category->slug.'/'.$productItem->slug) }}" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="Xem chi tiết">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-md-12 text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-box-open fa-4x text-gray-300"></i>
                </div>
                <h5 class="text-muted">Hiện tại chưa có chương trình khuyến mãi nào! 😢</h5>
                <a href="{{ url('/all-products') }}" class="btn btn-primary mt-3 px-4 rounded-pill">
                    <i class="fas fa-shopping-bag me-2"></i> Xem tất cả sản phẩm
                </a>
            </div>
            @endforelse

        </div>
    </div>
</div>

<style>
    .product-card:hover .transition-transform {
        transform: scale(1.05);
    }
    .product-card:hover {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        transition: box-shadow 0.3s;
    }
</style>

@endsection