@extends('layouts.app')

@section('title', $product->name)

@section('content')

<style>
    /* CSS cho đánh giá sao */
    .rate { float: left; height: 46px; padding: 0 10px; }
    .rate:not(:checked) > input { position:absolute; top:-9999px; }
    .rate:not(:checked) > label { float:right; width:1em; overflow:hidden; white-space:nowrap; cursor:pointer; font-size:30px; color:#ccc; }
    .rate:not(:checked) > label:before { content: '★ '; }
    .rate > input:checked ~ label { color: #ffc700; }
    .rate:not(:checked) > label:hover,
    .rate:not(:checked) > label:hover ~ label { color: #deb217; }
    .rate > input:checked + label:hover,
    .rate > input:checked + label:hover ~ label,
    .rate > input:checked ~ label:hover,
    .rate > input:checked ~ label:hover ~ label,
    .rate > label:hover ~ input:checked ~ label { color: #c59b08; }
    .checked { color: #ffc700; }

    /* CSS cho nút Disabled (Hết hàng) */
    .size-btn.disabled-btn {
        background-color: #e9ecef !important;
        color: #6c757d !important;
        border-color: #dee2e6 !important;
        cursor: not-allowed !important;
        opacity: 0.6;
        text-decoration: line-through;
    }
</style>

{{-- MODEL ĐÁNH GIÁ --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ url('/add-review') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Đánh giá sản phẩm: {{ $product->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="rating-css">
                        <div class="star-icon">
                            <div class="rate">
                                <input type="radio" id="star5" name="product_rating" value="5" checked />
                                <label for="star5" title="5 sao">5 stars</label>
                                <input type="radio" id="star4" name="product_rating" value="4" />
                                <label for="star4" title="4 sao">4 stars</label>
                                <input type="radio" id="star3" name="product_rating" value="3" />
                                <label for="star3" title="3 sao">3 stars</label>
                                <input type="radio" id="star2" name="product_rating" value="2" />
                                <label for="star2" title="2 sao">2 stars</label>
                                <input type="radio" id="star1" name="product_rating" value="1" />
                                <label for="star1" title="1 star">1 star</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="fw-bold">Nội dung đánh giá:</label>
                        <textarea name="user_review" class="form-control" rows="5" placeholder="Chia sẻ cảm nhận..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="py-3 py-md-5 bg-light">
    <div class="container">
        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <div class="row">
            <div class="col-md-5 mt-3">
                <div class="bg-white border rounded p-3 text-center">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" class="w-100" alt="{{ $product->name }}" style="max-height: 400px; object-fit: contain;">
                    @else
                        <img src="https://via.placeholder.com/400" class="w-100" alt="No Image">
                    @endif
                </div>
            </div>

            <div class="col-md-7 mt-3">
                <div class="product-view product_data"> 
                    
                    {{--  DỮ LIỆU TỒN KHO ĐỂ JS ĐỌC  --}}
                    <div id="product_stock_data" data-stock='@json($product->productSizes)' style="display: none;"></div>
                    {{--  ------------------------------------------ --}}

                    <h4 class="product-name fw-bold">
                        {{ $product->name }}
                        @if($product->productSizes->sum('quantity') > 0)
                            <label class="label-stock bg-success text-white px-2 py-1 rounded small">Còn hàng</label>
                        @else
                            <label class="label-stock bg-danger text-white px-2 py-1 rounded small">Hết hàng</label>
                        @endif
                    </h4>
                    <hr>
                    <p class="product-path text-muted">
                        Trang chủ / {{ $product->category ? $product->category->name : 'Sản phẩm' }} / {{ $product->name }}
                    </p>
                    
                    <div class="mb-3">
                        @if($product->hasDiscount())
                            <span class="text-muted text-decoration-line-through me-2 fs-5">
                                {{ number_format($product->original_price, 0, ',', '.') }} đ
                            </span>
                            <h3 class="fw-bold text-danger d-inline">
                                {{ number_format($product->getSellingPrice(), 0, ',', '.') }} đ
                            </h3>
                            <span class="badge bg-danger ms-2">Giảm {{ $product->discount_percent }}%</span>
                        @else
                            <h3 class="fw-bold text-dark d-inline">
                                {{ number_format($product->selling_price, 0, ',', '.') }} đ
                            </h3>
                        @endif
                    </div>

                    <input type="hidden" value="{{ $product->id }}" class="prod_id">

                    {{-- CHỌN MÀU --}}
                    <div class="mt-3">
                        <label class="fw-bold">Màu Sắc:</label>
                        <div class="mt-2">
                            @if($product->productSizes)
                                @foreach($product->productSizes->unique('color_id') as $sizeItem)
                                    @if($sizeItem->color)
                                        <label class="btn btn-sm btn-outline-secondary color-btn" style="cursor: pointer;" data-val="{{ $sizeItem->color->id }}"> 
                                            {{ $sizeItem->color->name }}
                                        </label>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- CHỌN SIZE --}}
                    <div class="mt-3">
                        <label class="fw-bold">Size:</label>
                        <div class="mt-2">
                            @if($product->productSizes)
                                @foreach($product->productSizes->unique('size') as $sizeItem)
                                    @if($sizeItem->size)
                                        {{-- Thêm class 'size-btn' để JS xử lý --}}
                                        <label class="btn btn-sm btn-outline-dark size-btn" style="cursor: pointer; min-width: 50px;" data-val="{{ $sizeItem->size }}">
                                            {{ $sizeItem->size }}
                                        </label>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <input type="hidden" class="color_val" name="color_val">
                    <input type="hidden" class="size_val" name="size_val">

                    <div class="mt-3">
                        <label class="fw-bold">Số lượng:</label>
                        <div class="input-group text-center mb-3" style="width: 130px;">
                            <button class="input-group-text decrement-btn">-</button>
                            <input type="text" name="quantity" value="1" class="form-control qty-input text-center" />
                            <button class="input-group-text increment-btn">+</button>
                        </div>
                    </div>

                    <div class="mt-4 row">
                        @if($product->productSizes->sum('quantity') > 0)
                            <div class="col-md-3 mb-2">
                                <button type="button" class="btn btn-primary w-100 p-2 addToCartBtn">
                                    <i class="fa fa-shopping-cart"></i> Thêm giỏ hàng
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button type="button" class="btn btn-success w-100 p-2 buyNowBtn">
                                    <i class="fa fa-check"></i> Mua ngay
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button type="button" 
                                    class="btn {{ $is_wishlist ? 'btn-secondary' : 'btn-danger' }} w-100 p-2 addToWishlist" 
                                    data-status="{{ $is_wishlist ? 'added' : 'not_added' }}">
                                    <i class="fa fa-heart"></i> 
                                    <span class="wishlist-text">{{ $is_wishlist ? 'Đã yêu thích' : 'Yêu thích' }}</span>
                                </button>
                            </div>
                        @else
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secondary w-100 p-2 mb-2" disabled>
                                    <i class="fa fa-ban"></i> Hết hàng
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Mô tả & Đánh giá</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-5">
                            <h5 class="fw-bold">Mô tả sản phẩm</h5>
                            <p>{!! $product->description !!}</p>
                        </div>
                        <hr>
                        {{-- Phần đánh giá (Giữ nguyên) --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold">Đánh giá sản phẩm ({{ $product->reviews->count() }})</h5>
                                    @if(Auth::check())
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <i class="fa fa-pencil"></i> Viết đánh giá
                                        </button>
                                    @else
                                        <a href="{{ url('login') }}" class="btn btn-outline-danger">Đăng nhập để đánh giá</a>
                                    @endif
                                </div>
                                @forelse($product->reviews as $review)
                                    <div class="user-review mb-3 p-3 border rounded bg-light">
                                        <label class="fw-bold mb-1">{{ $review->user->name }}</label>
                                        @if($review->rating)
                                            <div class="mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fa fa-star {{ $i <= $review->rating ? 'checked' : 'text-secondary' }}"></i>
                                                @endfor
                                            </div>
                                        @endif
                                        <p class="mb-1 text-muted small">Đã đánh giá: {{ $review->created_at->format('d-m-Y') }}</p>
                                        <p class="mb-0">{{ $review->comment }}</p>
                                    </div>
                                @empty
                                    <div class="text-center p-4">
                                        <p class="text-muted">Chưa có đánh giá nào.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        
        // ==========================================
        // 1. LOGIC KIỂM TRA TỒN KHO THÔNG MINH
        // ==========================================
        
        // Lấy dữ liệu tồn kho từ thẻ div ẩn
        var stockData = $('#product_stock_data').data('stock');

        // Hàm kiểm tra và cập nhật trạng thái các nút Size
        function checkStock() {
            var selectedColor = $('.color_val').val(); // Lấy ID màu đang chọn
            
            $('.size-btn').each(function() {
                var sizeBtn = $(this);
                var sizeVal = sizeBtn.data('val');
                
                // Tìm trong dữ liệu xem có cặp (Màu + Size) này không
                var item = stockData.find(function(i) {
                    // Nếu có màu thì so sánh cả màu, không thì chỉ so sánh size
                    return (selectedColor ? i.color_id == selectedColor : true) && i.size == sizeVal;
                });

                // Nếu tìm thấy và số lượng > 0 -> Cho phép bấm
                if (item && item.quantity > 0) {
                    sizeBtn.removeClass('disabled-btn').css('pointer-events', 'auto');
                } else {
                    // Nếu không tìm thấy hoặc số lượng = 0 -> Khóa nút
                    sizeBtn.addClass('disabled-btn').css('pointer-events', 'none');
                    
                    // Nếu nút này đang được chọn -> Bỏ chọn nó đi
                    if(sizeBtn.hasClass('btn-dark')) {
                        sizeBtn.removeClass('btn-dark text-white').addClass('btn-outline-dark');
                        $('.size_val').val(''); // Xóa giá trị size đang chọn
                    }
                }
            });
        }

        // KHI CHỌN MÀU -> CẬP NHẬT LẠI SIZE
        $('.color-btn').click(function (e) { 
            e.preventDefault();
            $('.color-btn').removeClass('btn-secondary text-white').addClass('btn-outline-secondary');
            $(this).removeClass('btn-outline-secondary').addClass('btn-secondary text-white');
            
            var colorId = $(this).data('val');
            $('.color_val').val(colorId);
            
            //  GỌI HÀM CHECK STOCK NGAY
            checkStock(); 
        });

        // KHI CHỌN SIZE
        $(document).on('click', '.size-btn', function (e) { 
            e.preventDefault();
            
            // Nếu nút đang bị khóa thì không làm gì cả
            if($(this).hasClass('disabled-btn')) return;

            $('.size-btn').removeClass('btn-dark text-white').addClass('btn-outline-dark');
            $(this).removeClass('btn-outline-dark').addClass('btn-dark text-white');
            $('.size_val').val($(this).data('val'));
        });

        // TỰ ĐỘNG CHỌN MÀU ĐẦU TIÊN KHI VÀO TRANG (Để kích hoạt checkStock)
        if($('.color-btn').length > 0) {
            $('.color-btn').first().click();
        } else {
            checkStock(); // Nếu ko có màu thì check size luôn
        }

        // ==========================================
        // 2. CÁC CHỨC NĂNG MUA HÀNG KHÁC
        // ==========================================

        $('.addToCartBtn').click(function (e) {
            e.preventDefault();
            var product_id = $(this).closest('.product_data').find('.prod_id').val();
            var product_qty = $(this).closest('.product_data').find('.qty-input').val();
            var color = $('.color_val').val();
            var size = $('.size_val').val();

            if(!size && $('.size-btn').length > 0) {
                alert("Vui lòng chọn Size!");
                return;
            }

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

            $.ajax({
                method: "POST",
                url: "{{ url('add-to-cart') }}",
                data: { 'product_id': product_id, 'quantity': product_qty, 'color': color, 'size': size },
                success: function (response) { alert(response.status); },
                error: function (xhr) { alert("Lỗi: " + xhr.status); }
            });
        });

        $('.buyNowBtn').click(function (e) { 
            e.preventDefault();
            var product_id = $(this).closest('.product_data').find('.prod_id').val();
            var product_qty = $(this).closest('.product_data').find('.qty-input').val();
            var color = $('.color_val').val();
            var size = $('.size_val').val();

            if(!size && $('.size-btn').length > 0) {
                alert("Vui lòng chọn Size!");
                return;
            }

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

            $.ajax({
                method: "POST",
                url: "{{ url('buy-now-process') }}",
                data: { 'product_id': product_id, 'quantity': product_qty, 'color': color, 'size': size },
                success: function (response) {
                    if(response.redirect) window.location.href = response.redirect;
                    else alert(response.status);
                },
                error: function (xhr) { alert("Lỗi kết nối: " + xhr.status); }
            });
        });

        $('.addToWishlist').click(function (e) { 
            e.preventDefault();
            var product_id = $(this).closest('.product_data').find('.prod_id').val();
            var $btn = $(this);
            var current_status = $btn.data('status'); 
            var url = (current_status == 'added') ? "{{ url('delete-wishlist-item') }}" : "{{ url('add-to-wishlist') }}";

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

            $.ajax({
                method: "POST",
                url: url,
                data: { 'product_id': product_id },
                success: function (response) {
                    if(response.status.includes("đăng nhập")) {
                        alert(response.status);
                        window.location.href = "{{ url('login') }}";
                        return;
                    }
                    if(response.status.includes("Đã thêm") || response.status.includes("đã có")) {
                        $btn.removeClass('btn-danger').addClass('btn-secondary');
                        $btn.find('.wishlist-text').text('Đã yêu thích');
                        $btn.data('status', 'added');
                        alert(response.status);
                    }
                    else if(response.status.includes("Đã xóa")) {
                        $btn.removeClass('btn-secondary').addClass('btn-danger');
                        $btn.find('.wishlist-text').text('Yêu thích');
                        $btn.data('status', 'not_added');
                        alert(response.status);
                    } else {
                        alert(response.status);
                    }
                },
                error: function (xhr) {
                    if(xhr.status == 401) {
                         alert("Vui lòng đăng nhập để sử dụng!");
                         window.location.href = "{{ url('login') }}";
                    } else {
                         alert("Lỗi: " + xhr.status);
                    }
                }
            });
        });

        $('.increment-btn').click(function (e) { 
            e.preventDefault();
            var val = parseInt($(this).closest('.product_data').find('.qty-input').val(), 10);
            if(val < 10) $(this).closest('.product_data').find('.qty-input').val(val + 1);
        });

        $('.decrement-btn').click(function (e) { 
            e.preventDefault();
            var val = parseInt($(this).closest('.product_data').find('.qty-input').val(), 10);
            if(val > 1) $(this).closest('.product_data').find('.qty-input').val(val - 1);
        });
    });
</script>
@endsection