@extends('admin.index')

@section('admin_content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h4 class="m-0 font-weight-bold text-primary">Danh sách Tài khoản</h4>
        <a href="{{ url('admin/users/create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Thêm Tài khoản
        </a>
    </div>

    <div class="card-body">
        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên hiển thị</th>
                        <th>Email</th>
                        <th>Vai trò (Role)</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>
                            {{-- 👇 ĐÃ SỬA LẠI ĐÚNG THEO DB CỦA BẠN 👇 --}}
                            
                            {{-- 1: Admin (Quyền cao nhất cũ) --}}
                            @if($item->role_as == '1')
                                <span class="badge bg-danger">Admin</span>

                            {{-- 2: Quản lý --}}
                            @elseif($item->role_as == '2')
                                <span class="badge bg-primary">Quản lý</span>

                            {{-- 3: Nhân viên --}}
                            @elseif($item->role_as == '3')
                                <span class="badge bg-info text-dark">Nhân viên</span>

                            {{-- 4: Giám đốc (Mới thêm) --}}
                            @elseif($item->role_as == '4')
                                <span class="badge bg-warning text-dark" style="font-size: 13px; font-weight: bold; border: 1px solid #333;">
                                    👑 Ban Giám đốc
                                </span>

                            {{-- 0: Khách hàng --}}
                            @elseif($item->role_as == '0')
                                <span class="badge bg-secondary">Khách hàng</span>
                            
                            @else
                                <span class="badge bg-light text-dark">Chưa phân quyền</span>
                            @endif
                            {{-- 👆 KẾT THÚC SỬA 👆 --}}
                        </td>
                        <td>
                            <a href="{{ url('admin/users/'.$item->id.'/edit') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <a href="{{ url('admin/users/'.$item->id.'/delete') }}" 
                               onclick="return confirm('Bạn có chắc muốn xóa tài khoản này không?')"
                               class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

@endsection