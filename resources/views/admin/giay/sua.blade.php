@extends('admin.index')

@section('admin_content')
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-warning text-dark">
        <h4 class="m-0 font-weight-bold">
            SỬA SẢN PHẨM: {{ $product->name }} &ensp;
            <i class="fas fa-edit"></i>
        </h4>
    </div>

    <div class="card-body">

        {{-- Hiển thị thông báo lỗi nếu có --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
            </div>
        @endif

        {{-- Form trỏ về hàm update (POST) --}}
        <form action="{{ url('admin/products/'.$product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{--  ĐÃ XÓA DÒNG @method('PUT') ĐỂ SỬA LỖI --}}
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Tên giày</label>
                    <input type="text" class="form-control" name="name" value="{{ $product->name }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Slug (Đường dẫn)</label>
                    <input type="text" class="form-control" name="slug" value="{{ $product->slug }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-primary">Loại giày</label>
                    <select name="category_id" class="form-control form-select">
                        @foreach ($loaigiays as $loaigiay)
                            <option value="{{ $loaigiay->id }}" {{ $product->category_id == $loaigiay->id ? 'selected' : '' }}>
                                {{ $loaigiay->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-primary">Thương hiệu</label>
                    <select name="brand_id" class="form-control form-select">
                        @foreach ($thuonghieus as $thuonghieu)
                            <option value="{{ $thuonghieu->id }}" {{ $product->brand_id == $thuonghieu->id ? 'selected' : '' }}>
                                {{ $thuonghieu->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Mô tả sản phẩm</label>
                <textarea class="form-control" name="description" rows="3">{{ $product->description }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Giá Gốc</label>
                    <input type="number" class="form-control" name="original_price" value="{{ $product->original_price }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-danger">Giá Bán</label>
                    <input type="number" class="form-control" name="selling_price" value="{{ $product->selling_price }}">
                </div>
            </div>

            {{-- 👇👇👇 PHẦN KHUYẾN MÃI 👇👇👇 --}}
            <div class="row mb-3 mx-1 p-3 bg-light border rounded">
                <div class="col-md-12 mb-2">
                    <h5 class="text-primary fw-bold">
                        <i class="fas fa-tags"></i> Thiết lập Khuyến mãi (Tùy chọn)
                    </h5>
                </div>
                
                <div class="col-md-4">
                    <label class="fw-bold">Phần trăm giảm (%)</label>
                    <input type="number" name="discount_percent" 
                           value="{{ $product->discount_percent }}" 
                           class="form-control" placeholder="VD: 20" min="0" max="100">
                </div>

                <div class="col-md-4">
                    <label class="fw-bold">Ngày bắt đầu giảm</label>
                    <input type="datetime-local" name="sale_start" 
                           value="{{ $product->sale_start ? date('Y-m-d\TH:i', strtotime($product->sale_start)) : '' }}" 
                           class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="fw-bold">Ngày kết thúc giảm</label>
                    <input type="datetime-local" name="sale_end" 
                           value="{{ $product->sale_end ? date('Y-m-d\TH:i', strtotime($product->sale_end)) : '' }}" 
                           class="form-control">
                </div>
            </div>
            {{-- 👆👆👆 HẾT PHẦN KHUYẾN MÃI 👆👆👆 --}}

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="fw-bold">Ảnh đại diện hiện tại:</label>
                    <br>
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" width="100px" class="border rounded">
                    @else
                        <span>Chưa có ảnh</span>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="fw-bold">Chọn ảnh mới (Nếu muốn thay đổi):</label>
                    <input type="file" class="form-control" name="image">
                </div>
            </div>

            <hr>
            <h5 class="text-primary fw-bold">Cập nhật Kho hàng (Size & Màu)</h5>
            <p class="text-muted small">Lưu ý: Bạn có thể sửa trực tiếp số lượng hoặc thêm dòng mới.</p>
            
            <table class="table table-bordered" id="dynamicTable">  
                <thead class="table-light">
                    <tr>
                        <th>Màu Sắc</th>
                        <th>Size</th>
                        <th>Số Lượng</th>
                        <th><button type="button" id="add" class="btn btn-success btn-sm">+ Thêm dòng</button></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($product->productSizes as $sizeItem)
                    <tr>
                        <td>
                            <select name="colors[]" class="form-control">
                                @foreach($colors as $color)
                                    <option value="{{ $color->id }}" {{ $sizeItem->color_id == $color->id ? 'selected' : '' }}>
                                        {{ $color->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" name="sizes[]" value="{{ $sizeItem->size }}" class="form-control" /></td>
                        <td><input type="number" name="quantities[]" value="{{ $sizeItem->quantity }}" class="form-control" /></td>
                        <td><button type="button" class="btn btn-danger remove-tr">Xóa</button></td>
                    </tr>
                    @endforeach
                    
                    @if($product->productSizes->count() == 0)
                    <tr>  
                        <td>
                            <select name="colors[]" class="form-control">
                                @foreach($colors as $color)
                                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                                @endforeach
                            </select>
                        </td>  
                        <td><input type="text" name="sizes[]" placeholder="Size" class="form-control" /></td>  
                        <td><input type="number" name="quantities[]" placeholder="SL" class="form-control" /></td>  
                        <td></td>  
                    </tr> 
                    @endif
                </tbody>
            </table> 

            <br>
            <button type="submit" class="btn btn-warning btn-lg px-5">CẬP NHẬT SẢN PHẨM</button>
            <a href="{{ url('admin/products') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var i = 0;
    $("#add").click(function(){
        ++i;
        $("#dynamicTable").append('<tr><td><select name="colors[]" class="form-control">@foreach($colors as $color)<option value="{{ $color->id }}">{{ $color->name }}</option>@endforeach</select></td><td><input type="text" name="sizes[]" class="form-control" /></td><td><input type="number" name="quantities[]" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Xóa</button></td></tr>');
    });
    $(document).on('click', '.remove-tr', function(){  
         $(this).parents('tr').remove();
    });  
</script>

@endsection