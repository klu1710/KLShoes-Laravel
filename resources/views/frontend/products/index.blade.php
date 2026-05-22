@extends('layouts.app')

@section('title', 'Cửa hàng - Tất cả sản phẩm')

@section('content')

{{-- 1. THANH TIÊU ĐỀ --}}
<div class="py-3 mb-4 shadow-sm bg-light border-top">
    <div class="container">
        <h6 class="mb-0">
            <a href="{{ url('/') }}" class="text-dark text-decoration-none">Trang chủ</a> / 
            Tất cả sản phẩm
        </h6>
    </div>
</div>

<div class="container pb-5">
    <div class="row">
        
        {{-- ================= SIDEBAR BỘ LỌC (BÊN TRÁI) ================= --}}
        <div class="col-md-3">
            <form action="{{ url('all-products') }}" method="GET">
                
                {{-- 1. LỌC THEO GIÁ --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary fw-bold text-white">
                        <i class="fas fa-money-bill-wave"></i> Khoảng Giá
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <input type="number" name="min_price" value="{{ Request::get('min_price') }}" class="form-control form-control-sm" placeholder="Từ (đ)">
                            <span class="mx-1">-</span>
                            <input type="number" name="max_price" value="{{ Request::get('max_price') }}" class="form-control form-control-sm" placeholder="Đến (đ)">
                        </div>
                    </div>
                </div>

                {{-- 2. LỌC THEO MÀU SẮC --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary fw-bold text-white">
                        <i class="fas fa-palette"></i> Màu Sắc
                    </div>
                    <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                        @if(isset($allColors) && count($allColors) > 0)
                            @foreach($allColors as $color)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="color[]" value="{{ $color->id }}" id="color_{{ $color->id }}" 
                                        {{ in_array($color->id, Request::get('color', [])) ? 'checked' : '' }} 
                                    >
                                    <label class="form-check-label" for="color_{{ $color->id }}">
                                        {{ $color->name }}
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted small">Chưa có màu sắc nào.</p>
                        @endif
                    </div>
                </div>

                {{-- 3. LỌC THEO SIZE --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary fw-bold text-white">
                        <i class="fas fa-ruler"></i> Kích Thước (Size)
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if(isset($allSizes) && count($allSizes) > 0)
                                @foreach($allSizes as $size)
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="size[]" value="{{ $size }}" id="size_{{ $size }}"
                                                {{ in_array($size, Request::get('size', [])) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="size_{{ $size }}">
                                                Size {{ $size }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12"><p class="text-muted small">Chưa có size nào.</p></div>
                            @endif
                        </div>
                        
                        {{--  NÚT BẤM XEM BẢNG SIZE GUIDE Ở ĐÂY  --}}
                        <div class="text-center border-top mt-3 pt-3">
                            <button type="button" class="btn btn-outline-info btn-sm w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#sizeGuideModal">
                                <i class="fas fa-ruler-horizontal"></i> Xem Bảng Size Chuẩn
                            </button>
                        </div>
                    </div>
                </div>

                {{-- NÚT ÁP DỤNG LỌC --}}
                <div class="d-grid gap-2 mb-4">
                    <button type="submit" class="btn btn-primary fw-bold">
                        <i class="fa fa-filter"></i> ÁP DỤNG LỌC
                    </button>
                    <a href="{{ url('all-products') }}" class="btn btn-outline-secondary btn-sm">
                        Xóa bộ lọc
                    </a>
                </div>

                {{-- 4. DANH MỤC --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-white fw-bold">Danh Mục</div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach ($loaigiays as $cate)
                                <li class="list-group-item">
                                    <a href="{{ url('collections/'.$cate->slug) }}" class="text-dark text-decoration-none">{{ $cate->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- 5. THƯƠNG HIỆU --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-white fw-bold">Thương Hiệu</div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach ($thuonghieus as $brand)
                                <li class="list-group-item">
                                    <a href="{{ url('brands/'.$brand->slug) }}" class="text-dark text-decoration-none">{{ $brand->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </form>
        </div>

        {{-- ================= DANH SÁCH SẢN PHẨM (BÊN PHẢI) ================= --}}
        <div class="col-md-9">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold mb-0">Tất cả sản phẩm</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($giays as $product)
                            <div class="col-md-4 col-6 mb-4">
                                {{--  ĐÃ GẮN CLASS product-card ĐỂ CÓ HIỆU ỨNG HOVER  --}}
                                <div class="card h-100 border-0 shadow-sm product-card">
                                    <a href="{{ url('collections/'.$product->category->slug.'/'.$product->slug) }}" class="text-decoration-none text-dark">
                                        
                                        {{-- ẢNH SẢN PHẨM --}}
                                        <div class="position-relative overflow-hidden">
                                            @if($product->image)
                                                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: contain;">
                                            @else
                                                <img src="https://via.placeholder.com/250" class="card-img-top" style="height: 250px; object-fit: cover;">
                                            @endif

                                            {{-- LOGIC NHÃN GIẢM GIÁ --}}
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
                                                {{-- LOGIC HIỂN THỊ GIÁ --}}
                                                @if($product->hasDiscount())
                                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                                        <span class="text-danger fw-bold fs-5">
                                                            {{ number_format($product->getSellingPrice(), 0, ',', '.') }}đ
                                                        </span>
                                                        <del class="text-muted small">
                                                            {{ number_format($product->original_price, 0, ',', '.') }}đ
                                                        </del>
                                                    </div>
                                                @else
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
                                <h4>Không tìm thấy sản phẩm nào phù hợp :(</h4>
                                <p class="text-muted">Vui lòng thử bỏ bớt bộ lọc để tìm kiếm lại.</p>
                                <a href="{{ url('all-products') }}" class="btn btn-warning">Xem tất cả sản phẩm</a>
                            </div>
                        @endforelse
                    </div>

                    {{-- PHÂN TRANG --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $giays->appends(request()->input())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--  KHUNG POP-UP CHỨA BẢNG SIZE GUIDE ĐƯỢC ẨN ĐI  --}}
<div class="modal fade" id="sizeGuideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-shoe-prints"></i> Bảng Kích Thước (Size Guide)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <p class="mb-4">Bạn hãy đo chiều dài bàn chân từ gót đến ngón dài nhất và đối chiếu với bảng dưới đây nhé:</p>
                <img src="https://cdn.tgdd.vn/Files/2021/08/11/1374526/bang-size-giay-nam-nu-chuan-viet-nam-va-quoc-te--2.jpg" alt="Size Guide" class="img-fluid rounded border shadow-sm">
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS TẠO HIỆU ỨNG NHÚN NHẢY KHI RE CHUỘT */
    .product-card { transition: all 0.3s ease; }
    .product-card:hover { transform: translateY(-8px); box-shadow: 0 15px 25px rgba(0,0,0,0.15) !important; border: 1px solid #ffc107 !important; z-index: 2;}
</style>

@endsection