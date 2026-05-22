@extends('admin.index')

@section('admin_content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary">THÊM LOẠI GIÀY</h4>
    </div>
    <div class="card-body">
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
            </div>
        @endif

        <form action="{{ url('admin/category') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label>Tên loại giày</label>
                <input type="text" name="name" class="form-control" required placeholder="VD: Giày Đá Bóng">
            </div>

            <div class="mb-3">
                <label>Slug (Tên trên đường dẫn)</label>
                <input type="text" name="slug" class="form-control" required placeholder="VD: giay-da-bong">
            </div>

            <div class="mb-3">
                <label>Mô tả</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label>Trạng thái</label> <br>
                <input type="checkbox" name="status" checked style="width: 20px; height: 20px;"> Hiển thị
            </div>

            <button type="submit" class="btn btn-primary">Lưu lại</button>
            <a href="{{ url('admin/category') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection