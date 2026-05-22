@extends('layouts.app')

@section('content')

{{-- 1. SLIDER / BANNER (ĐÃ SỬA THÀNH ĐỘNG) --}}
<div id="slider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000" style="background: #f8f9fa;">
    
    {{-- A. Phần nút chấm tròn (Indicators) - Tự động sinh ra theo số lượng banner --}}
    <div class="carousel-indicators">
        @if(isset($sliders) && $sliders->count() > 0)
            @foreach($sliders as $key => $sliderItem)
                <button type="button" data-bs-target="#slider" data-bs-slide-to="{{ $key }}" 
                    class="{{ $key == 0 ? 'active' : '' }}" aria-current="true"></button>
            @endforeach
        @endif
    </div>

    {{-- B. Phần hình ảnh (Slides) --}}
    <div class="carousel-inner">
        @if(isset($sliders) && $sliders->count() > 0)
            @foreach($sliders as $key => $sliderItem)
            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                @if($sliderItem->image)
                    <img src="{{ asset($sliderItem->image) }}" class="d-block w-100" alt="Slider" 
                         style="height: 450px; object-fit: cover;"> 
                @endif
                
                {{-- Phần chữ đè lên ảnh --}}
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
                    <h3 class="fw-bold text-warning">{{ $sliderItem->title }}</h3>
                    <p class="text-white">{{ $sliderItem->description }}</p>
                </div>
            </div>
            @endforeach
        @else
            {{-- Nếu chưa có Banner nào thì hiện ảnh mặc định để không bị lỗi giao diện --}}
            <div class="carousel-item active">
                <img src="https://via.placeholder.com/1200x450?text=Chua+co+Banner" class="d-block w-100" style="height: 450px; object-fit: cover;">
            </div>
        @endif
    </div>

    {{-- C. Nút chuyển trái phải --}}
    <button class="carousel-control-prev" type="button" data-bs-target="#slider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#slider" data-bs-slide="next">
        <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

{{-- 2. SẢN PHẨM NỔI BẬT --}}
<div class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center mb-4">
                <h2 class="fw-bold text-uppercase">Sản Phẩm Nổi Bật 🔥</h2>
                <div style="width: 100px; height: 3px; background: #dc3545; margin: 0 auto;"></div>
            </div>

            @if(isset($featuredProducts) && $featuredProducts->count() > 0)
                @foreach($featuredProducts as $product)
                    <div class="col-md-3 col-6 mb-4">
                       
                        <div class="card shadow-sm h-100 border-0 product-card">
                            @php
                                $catSlug = $product->category ? $product->category->slug : 'khong-co-danh-muc';
                                $url = url('collections/'.$catSlug.'/'.$product->slug);
                            @endphp

                            <div class="position-relative">
                                <a href="{{ $url }}">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                                    @else
                                        <img src="https://via.placeholder.com/250x250?text=No+Image" class="card-img-top" style="height: 250px; object-fit: cover;">
                                    @endif
                                </a>
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2">Hot</span>
                                @if($product->hasDiscount())
                                    <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">-{{ $product->discount_percent }}%</span>
                                @endif
                            </div>

                            <div class="card-body text-center d-flex flex-column">
                                <h5 class="card-title mb-1">
                                    <a href="{{ $url }}" class="text-decoration-none text-dark fw-bold">
                                        {{ $product->name }}
                                    </a>
                                </h5>
                                <div class="mt-auto pt-2">
                                    @if($product->hasDiscount())
                                        <span class="text-muted text-decoration-line-through small me-1">{{ number_format($product->original_price, 0, ',', '.') }}đ</span>
                                        <span class="text-danger fw-bold fs-5">{{ number_format($product->getSellingPrice(), 0, ',', '.') }}đ</span>
                                    @else
                                        <span class="text-dark fw-bold fs-5">{{ number_format($product->selling_price, 0, ',', '.') }}đ</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center"><p>Chưa có sản phẩm nổi bật nào.</p></div>
            @endif
        </div>
    </div>
</div>

{{-- 3. DANH MỤC SẢN PHẨM (CATEGORY) --}}
<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center mb-4">
                <h2 class="fw-bold text-uppercase">Danh Mục Giày </h2>
                <div style="width: 100px; height: 3px; background: #0d6efd; margin: 0 auto;"></div>
            </div>

            @if(isset($categories) && $categories->count() > 0)
                @foreach($categories as $cateItem)
                    <div class="col-6 col-md-3 mb-3">
                        
                        <div class="card bg-dark text-white border-0 shadow product-card" style="overflow: hidden;">
                            @if($cateItem->image)
                                <img src="{{ asset($cateItem->image) }}" class="card-img opacity-50" style="height: 150px; object-fit: cover;">
                            @else
                                <div style="height: 150px; background: #333;"></div>
                            @endif

                            <div class="card-img-overlay d-flex align-items-center justify-content-center">
                                <h4 class="card-title fw-bold text-uppercase text-center">
                                    <a href="{{ url('collections/'.$cateItem->slug) }}" class="text-white text-decoration-none stretched-link">
                                        {{ $cateItem->name }}
                                    </a>
                                </h4>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                 <div class="col-12 text-center">
                    <p>Chưa có danh mục nào.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- 4. SẢN PHẨM MỚI (NEW) --}}
<div class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center mb-4">
                <h2 class="fw-bold text-uppercase">Sản Phẩm Mới 👟</h2>
                <div style="width: 100px; height: 3px; background: #0d6efd; margin: 0 auto;"></div>
            </div>

            @if(isset($newProducts) && $newProducts->count() > 0)
                @foreach($newProducts as $product)
                <div class="col-md-3 col-6 mb-4">
                    
                    <div class="card shadow-sm h-100 border-0 product-card">
                        @php
                            $catSlug = $product->category ? $product->category->slug : 'khong-co-danh-muc';
                            $url = url('collections/'.$catSlug.'/'.$product->slug);
                        @endphp

                        <div class="position-relative">
                            <a href="{{ $url }}">
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                                @else
                                    <img src="https://via.placeholder.com/250x250?text=No+Image" class="card-img-top" style="height: 250px; object-fit: cover;">
                                @endif
                            </a>
                            <span class="badge bg-primary position-absolute top-0 start-0 m-2">Mới</span>
                            @if($product->hasDiscount())
                                <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">-{{ $product->discount_percent }}%</span>
                            @endif
                        </div>

                        <div class="card-body text-center d-flex flex-column">
                            <h5 class="card-title mb-1">
                                <a href="{{ $url }}" class="text-decoration-none text-dark fw-bold">
                                    {{ $product->name }}
                                </a>
                            </h5>

                            <div class="mt-auto pt-3">
                                @if($product->hasDiscount())
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <span class="text-muted text-decoration-line-through small">{{ number_format($product->original_price, 0, ',', '.') }}đ</span>
                                        <h5 class="text-danger fw-bold mb-0">{{ number_format($product->getSellingPrice(), 0, ',', '.') }}đ</h5>
                                    </div>
                                @else
                                    <h5 class="text-dark fw-bold mb-0">{{ number_format($product->selling_price, 0, ',', '.') }}đ</h5>
                                @endif

                                <a href="{{ $url }}" class="btn btn-outline-primary w-100 mt-2">
                                    Xem Chi Tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                 <div class="col-12 text-center">
                    <p>Chưa có sản phẩm mới nào.</p>
                </div>
            @endif

        </div>
    </div>
</div>

@endsection