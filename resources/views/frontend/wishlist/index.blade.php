@extends('layouts.app')

@section('title', 'Danh sách yêu thích')

@section('content')

<div class="py-3 mb-4 shadow-sm bg-warning border-top">
    <div class="container">
        <h6 class="mb-0">
            <a href="{{ url('/') }}" class="text-dark text-decoration-none">Trang chủ</a> / Yêu thích
        </h6>
    </div>
</div>

<div class="container my-5">
    <div class="card shadow">
        <div class="card-body">
            <h4>Sản phẩm yêu thích của bạn</h4>
            <hr>

            @if($wishlist->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Hình ảnh</th>
                                <th>Sản phẩm</th>
                                <th>Giá bán</th>
                                <th>Tình trạng</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($wishlist as $item)
                            <tr class="product_data border-bottom">
                                <td width="10%">
                                    <input type="hidden" class="prod_id" value="{{ $item->product_id }}">
                                    {{-- BƯỚC SỬA 1: Kiểm tra sản phẩm có tồn tại không --}}
                                    @if($item->product)
                                        @if($item->product->image)
                                            <img src="{{ asset($item->product->image) }}" width="70px" alt="Image">
                                        @else
                                            <img src="https://via.placeholder.com/70" width="70px" alt="No Image">
                                        @endif
                                    @else
                                        <img src="https://via.placeholder.com/70" width="70px" alt="Deleted">
                                    @endif
                                </td>
                                <td>
                                    @if($item->product)
                                        <h6 class="mb-0">{{ $item->product->name }}</h6>
                                    @else
                                        <h6 class="mb-0 text-danger">Sản phẩm không còn tồn tại</h6>
                                    @endif
                                </td>
                                <td>
                                    @if($item->product)
                                        {{ number_format($item->product->getSellingPrice(), 0, ',', '.') }}đ
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($item->product)
                                        @if($item->product->productSizes->sum('quantity') > 0)
                                            <label class="badge bg-success">Còn hàng</label>
                                        @else
                                            <label class="badge bg-danger">Hết hàng</label>
                                        @endif
                                    @else
                                        <label class="badge bg-secondary">Ngừng kinh doanh</label>
                                    @endif
                                </td>
                                <td>
                                    {{-- Chỉ hiện nút XEM nếu sản phẩm và danh mục còn tồn tại --}}
                                    @if($item->product && $item->product->category)
                                        <a href="{{ url('collections/'.$item->product->category->slug.'/'.$item->product->slug) }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-eye"></i> Xem
                                        </a>
                                    @endif
                                    
                                    {{-- Nút Xóa luôn hiện để khách có thể xóa sản phẩm lỗi --}}
                                    <button class="btn btn-danger btn-sm remove-wishlist-item">
                                        <i class="fa fa-trash"></i> Xóa
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <h4>Bạn chưa có sản phẩm yêu thích nào.</h4>
                    <a href="{{ url('all-products') }}" class="btn btn-primary mt-3">Tiếp tục mua sắm</a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        
        // Xử lý nút xóa trong trang danh sách yêu thích
        $('.remove-wishlist-item').click(function (e) { 
            e.preventDefault();
            var prod_id = $(this).closest('.product_data').find('.prod_id').val();

            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            $.ajax({
                method: "POST",
                url: "delete-wishlist-item",
                data: { 'product_id': prod_id },
                success: function (response) {
                    // Tải lại trang để cập nhật danh sách
                    window.location.reload(); 
                    alert(response.status);
                }
            });
        });
    });
</script>
@endsection