@extends('admin.index')

@section('admin_content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary">SỬA LOẠI GIÀY</h4>
    </div>
    <div class="card-body">

        <form action="{{ url('admin/category/'.$category->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- BẮT BUỘC PHẢI CÓ DÒNG NÀY VỚI ROUTE PUT --}}
            
            <div class="mb-3">
                <label>Tên loại giày</label>
                <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Slug</label>
                <input type="text" name="slug" value="{{ $category->slug }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Mô tả</label>
                <textarea name="description" class="form-control" rows="3">{{ $category->description }}</textarea>
            </div>

            <div class="mb-3">
                <label>Trạng thái</label> <br>
                <input type="checkbox" name="status" {{ $category->status == '1' ? 'checked' : '' }} style="width: 20px; height: 20px;"> Hiển thị
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ url('admin/category') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection