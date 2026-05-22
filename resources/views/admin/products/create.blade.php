@extends('layouts.admin')

@section('content')

<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Thêm Giày Mới</h4>
    </div>
    <div class="card-body">
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
            </div>
        @endif

        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <form action="{{ url('admin/products') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Tên Giày</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug (Đường dẫn)</label>
                    <input type="text" name="slug" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Danh mục</label>
                    <select name="category_id" class="form-control">
                        @foreach($categories as $cate)
                            <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Thương hiệu</label>
                    <select name="brand_id" class="form-control">
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Giá Gốc</label>
                    <input type="number" name="original_price" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Giá Bán (Mặc định)</label>
                    <input type="number" name="selling_price" class="form-control">
                </div>
            </div>

            {{-- 👇👇👇 PHẦN MỚI THÊM: KHUYẾN MÃI 👇👇👇 --}}
            <div class="row mb-3 p-3 bg-light border rounded">
                <div class="col-md-12 mb-2">
                    <h5 class="text-primary fw-bold"><i class="fas fa-percent"></i> Thiết lập Khuyến mãi (Tùy chọn)</h5>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Phần trăm giảm (%)</label>
                    <input type="number" name="discount_percent" class="form-control" placeholder="VD: 20" min="0" max="100">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ngày bắt đầu giảm</label>
                    <input type="datetime-local" name="sale_start" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ngày kết thúc giảm</label>
                    <input type="datetime-local" name="sale_end" class="form-control">
                </div>
            </div>
            {{-- 👆👆👆 HẾT PHẦN KHUYẾN MÃI 👆👆👆 --}}

            <div class="mb-3">
                <label class="form-label fw-bold">Ảnh Đại Diện</label>
                <input type="file" name="image" class="form-control">
            </div>

            <hr>
            <h5 class="text-primary">Kho hàng (Size & Màu)</h5>
            <table class="table table-bordered" id="dynamicTable">
                <tr class="table-light">
                    <th>Màu Sắc</th>
                    <th>Size</th>
                    <th>Số Lượng Tồn</th>
                    <th>Hành động</th>
                </tr>
                <tr>
                    <td>
                        <select name="colors[]" class="form-control">
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" name="sizes[]" placeholder="VD: 40" class="form-control" /></td>
                    <td><input type="number" name="quantities[]" placeholder="10" class="form-control" /></td>
                    <td><button type="button" name="add" id="add" class="btn btn-success btn-sm">+ Thêm dòng</button></td>
                </tr>
            </table>

            <button type="submit" class="btn btn-primary btn-lg mt-3 w-100">LƯU SẢN PHẨM</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var i = 0;
    $("#add").click(function(){
        ++i;
        $("#dynamicTable").append('<tr><td><select name="colors[]" class="form-control">@foreach($colors as $color)<option value="{{ $color->id }}">{{ $color->name }}</option>@endforeach</select></td><td><input type="text" name="sizes[]" class="form-control" /></td><td><input type="number" name="quantities[]" class="form-control" /></td><td><button type="button" class="btn btn-danger btn-sm remove-tr">Xóa</button></td></tr>');
    });
    $(document).on('click', '.remove-tr', function(){  
         $(this).parents('tr').remove();
    });  
</script>
@endsection