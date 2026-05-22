@extends('admin.index')

@section('admin_content')

{{-- BẮT ĐẦU PHẦN THÔNG BÁO --}}
@if(session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ session('message') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
{{-- KẾT THÚC PHẦN THÔNG BÁO --}}

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-primary text-white">
        <h4 class="m-0 font-weight-bold">THÊM GIÀY MỚI</h4>
    </div>

    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
            </div>
        @endif

        <form action="{{ url('admin/products') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Tên giày</label>
                        <input type="text" class="form-control" name="name" required placeholder="Nhập tên giày...">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Slug (Đường dẫn)</label>
                        <input type="text" class="form-control" name="slug" required placeholder="tu-dong-tao-slug">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold text-primary">Loại giày (Danh mục)</label>
                        <select name="category_id" class="form-control form-select">
                            <option value="">-- Chọn loại giày --</option>
                            @foreach ($loaigiays as $loaigiay)
                                <option value="{{ $loaigiay->id }}">{{ $loaigiay->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold text-primary">Thương hiệu</label>
                        <select name="brand_id" class="form-control form-select">
                            <option value="">-- Chọn thương hiệu --</option>
                            @foreach ($thuonghieus as $thuonghieu)
                                <option value="{{ $thuonghieu->id }}">{{ $thuonghieu->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="fw-bold">Mô tả sản phẩm</label>
                <textarea class="form-control" name="description" rows="3"></textarea>
            </div>

            {{--  PHẦN MỚI THÊM: TRẠNG THÁI HIỂN THỊ  --}}
            <div class="row mt-3 mb-4 p-3 bg-light rounded border">
                <div class="col-md-6">
                    <div class="form-check d-flex align-items-center">
                        <input class="form-check-input" type="checkbox" name="status" checked style="width: 20px; height: 20px;">
                        <label class="form-check-label ms-2 fw-bold text-success">Hiển thị ngay (Status)</label>
                        <small class="text-muted ms-2">(Bỏ tích sẽ ẩn khỏi web)</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check d-flex align-items-center">
                        <input class="form-check-input" type="checkbox" name="trending" style="width: 20px; height: 20px;">
                        <label class="form-check-label ms-2 fw-bold text-danger">Sản phẩm nổi bật (Trending)</label>
                    </div>
                </div>
            </div>
            {{--  KẾT THÚC PHẦN MỚI THÊM  --}}

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Giá Gốc</label>
                        <input type="number" class="form-control" name="original_price" value="0">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold text-danger">Giá Bán (Quan trọng)</label>
                        <input type="number" class="form-control" name="selling_price" value="0">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Ảnh đại diện</label>
                        <input type="file" class="form-control" name="image">
                    </div>
                </div>
            </div>

            <hr>
            <h5 class="text-primary fw-bold">Kho hàng (Size & Màu)</h5>
            <table class="table table-bordered" id="dynamicTable">  
                <thead class="table-light">
                    <tr>
                        <th>Màu Sắc</th>
                        <th>Size</th>
                        <th>Số Lượng</th>
                        <th><button type="button" name="add" id="add" class="btn btn-success btn-sm">+ Thêm dòng</button></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>  
                        <td>
                            <select name="colors[]" class="form-control">
                                @foreach($colors as $color)
                                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                                @endforeach
                            </select>
                        </td>  
                        <td><input type="text" name="sizes[]" placeholder="VD: 40" class="form-control" /></td>  
                        <td><input type="number" name="quantities[]" value="10" class="form-control" /></td>  
                        <td></td>  
                    </tr>  
                </tbody>
            </table> 

            <br>
            <button type="submit" class="btn btn-primary btn-lg px-5">LƯU SẢN PHẨM</button>
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
    
    // Code tự động tạo Slug khi nhập Tên giày
    $('input[name="name"]').keyup(function() {
        var title = $(this).val();
        var slug = title.toLowerCase();
        slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
        slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
        slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
        slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
        slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
        slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
        slug = slug.replace(/đ/gi, 'd');
        slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
        slug = slug.replace(/ /gi, "-");
        slug = slug.replace(/\-\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-/gi, '-');
        slug = slug.replace(/\-\-/gi, '-');
        slug = '@' + slug + '@';
        slug = slug.replace(/\@\-|\-\@|\@/gi, '');
        $('input[name="slug"]').val(slug);
    });
</script>
@endsection