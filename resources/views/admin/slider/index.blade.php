@extends('admin.index') {{-- 👈 Sửa thành admin.index --}}

@section('admin_content') {{-- 👈 Sửa thành admin_content --}}

<div class="row">
    <div class="col-md-12">
        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3>Danh sách Banner (Slider)
                    <a href="{{ url('admin/sliders/create') }}" class="btn btn-primary btn-sm float-end text-white">
                        Thêm Banner Mới
                    </a>
                </h3>
            </div>
            <div class="card-body">
                {{-- Div này giúp bảng có thanh trượt ngang trên điện thoại --}}
                <div class="table-responsive"> 
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Hình ảnh</th>
                                <th>Tiêu đề</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sliders as $slider)
                                <tr>
                                    <td>{{ $slider->id }}</td>
                                    <td>
                                        <img src="{{ asset($slider->image) }}" style="width: 70px; height: 70px; object-fit: cover;" alt="Slider">
                                    </td>
                                    <td>{{ $slider->title }}</td>
                                    <td>{{ $slider->description }}</td>
                                    <td>
                                        <span class="badge {{ $slider->status == '0' ? 'badge-success' : 'badge-danger' }} p-2">
                                            {{ $slider->status == '0' ? 'Đang hiện' : 'Đang ẩn' }}
                                        </span>
                                    </td>
                                    <td style="width: 150px;">
                                        <a href="{{ url('admin/sliders/'.$slider->id.'/edit') }}" class="btn btn-success btn-sm text-white">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <a href="{{ url('admin/sliders/'.$slider->id.'/delete') }}" 
                                           onclick="return confirm('Bạn có chắc muốn xóa hình này không?')" 
                                           class="btn btn-danger btn-sm text-white">
                                            <i class="fas fa-trash"></i> Xóa
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection