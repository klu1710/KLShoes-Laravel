@extends('layouts.app')

@section('title', 'Kết quả tìm kiếm')

@section('content')
<div class="py-3 mb-4 shadow-sm bg-light border-top">
    <div class="container">
        <h6 class="mb-0">
            <a href="{{ url('/') }}" class="text-dark text-decoration-none">Trang chủ</a> / 
            Tìm kiếm
        </h6>
    </div>
</div>

<div class="container pb-5">
    <div class="row">
        <div class="col-md-12">
            <h4 class="fw-bold mb-4">
                Kết quả tìm kiếm cho từ khóa: <span class="text-danger">"{{ $keyword }}"</span>
            </h4>
        </div>

        @forelse($searchProducts as $product)
            <div class="col-md-3 col-6 mb-4">
                <div class="card h-100 border-0 shadow-sm product-card">
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
        @empty
            <div class="col-12 text-center p-5">
                <img src="https://cdn-icons-png.flaticon.com/512/6134/6134065.png" alt="Not found" style="width: 120px; opacity: 0.5;" class="mb-3">
                <h4 class="text-muted">Rất tiếc, KLShoes không tìm thấy đôi giày nào tên là "{{ $keyword }}"</h4>
                <p>Bạn hãy thử tìm bằng một từ khóa khác ngắn gọn hơn xem sao nhé!</p>
                <a href="{{ url('all-products') }}" class="btn btn-primary mt-3">Xem tất cả sản phẩm</a>
            </div>
        @endforelse

        {{-- Phân trang kết quả tìm kiếm --}}
        <div class="col-12 mt-4 d-flex justify-content-center">
            {{ $searchProducts->appends(request()->input())->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>
@endsection