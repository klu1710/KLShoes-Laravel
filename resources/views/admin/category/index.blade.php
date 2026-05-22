@extends('admin.index')

@section('admin_content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h4 class="m-0 font-weight-bold text-primary">QUẢN LÝ LOẠI GIÀY</h4>
        <a href="{{ url('admin/category/create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Thêm Loại Giày
        </a>
    </div>

    <div class="card-body">
        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên loại</th>
                    <th>Slug</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->slug }}</td>
                    <td>
                        @if($item->status == '1')
                            <span class="badge bg-success text-white">Hiển thị</span>
                        @else
                            <span class="badge bg-danger text-white">Ẩn</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ url('admin/category/'.$item->id.'/edit') }}" class="btn btn-success btn-sm">Sửa</a>
                        <a href="{{ url('admin/category/'.$item->id.'/delete') }}" 
                           onclick="return confirm('Bạn chắc chắn muốn xóa?')" 
                           class="btn btn-danger btn-sm">Xóa</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        {{-- Phân trang --}}
        <div class="mt-3">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection