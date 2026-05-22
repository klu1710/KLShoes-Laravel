@extends('admin.index') {{-- 👈 Sửa thành admin.index --}}

@section('admin_content') {{-- 👈 Sửa thành admin_content --}}

<div class="row">
        <div class="col-md-12">
            {{-- 👇 ĐOẠN CODE NÀY DÙNG ĐỂ HIỆN LỖI MÀU ĐỎ --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- 👆 KẾT THÚC ĐOẠN CODE HIỆN LỖI --}}
        </div>
    </div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3>Thêm Banner Mới
                    <a href="{{ url('admin/sliders') }}" class="btn btn-danger btn-sm text-white float-end">Quay lại</a>
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('admin/sliders') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label>Tiêu đề</label>
                        <input type="text" name="title" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Mô tả</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Hình ảnh (Bắt buộc)</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Trạng thái</label> <br/>
                        <input type="checkbox" name="status" style="width: 20px; height: 20px;" /> 
                        <span class="ms-2">Check vào ô này để ẨN banner (Mặc định là hiện)</span>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary text-white">Lưu lại</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection