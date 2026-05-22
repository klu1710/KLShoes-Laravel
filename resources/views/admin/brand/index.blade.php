@extends('admin.index')

@section('admin_content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h4 class="m-0 font-weight-bold text-primary">QUẢN LÝ THƯƠNG HIỆU</h4>
        <a href="{{ url('admin/brand/create') }}" class="btn btn-primary btn-sm">Thêm Mới</a>
    </div>
    <div class="card-body">
        @if(session('message')) <div class="alert alert-success">{{ session('message') }}</div> @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Thương hiệu</th>
                    <th>Slug</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($brands as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->slug }}</td>
                    <td>{{ $item->status == '1' ? 'Hiển thị' : 'Ẩn' }}</td>
                    <td>
                        <a href="{{ url('admin/brand/'.$item->id.'/edit') }}" class="btn btn-success btn-sm">Sửa</a>
                        <a href="{{ url('admin/brand/'.$item->id.'/delete') }}" onclick="return confirm('Xóa nhé?')" class="btn btn-danger btn-sm">Xóa</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $brands->links() }}
    </div>
</div>
@endsection