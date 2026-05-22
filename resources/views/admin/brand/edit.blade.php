@extends('admin.index')

@section('admin_content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary">SỬA THƯƠNG HIỆU</h4>
    </div>
    <div class="card-body">

        {{-- SỬA 1: Đường dẫn trỏ về admin/brand --}}
        {{-- SỬA 2: Đổi biến $category thành $brand --}}
        <form action="{{ url('admin/brand/'.$brand->id) }}" method="POST">
            @csrf
            @method('PUT') 
            
            <div class="mb-3">
                <label>Tên Thương hiệu</label>
                <input type="text" name="name" value="{{ $brand->name }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Slug</label>
                <input type="text" name="slug" value="{{ $brand->slug }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Mô tả</label>
                <textarea name="description" class="form-control" rows="3">{{ $brand->description }}</textarea>
            </div>

            <div class="mb-3">
                <label>Trạng thái</label> <br>
                {{-- SỬA 3: Kiểm tra status của $brand --}}
                <input type="checkbox" name="status" {{ $brand->status == '1' ? 'checked' : '' }} style="width: 20px; height: 20px;"> Hiển thị
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ url('admin/brand') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection