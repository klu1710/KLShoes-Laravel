@extends('admin.index')

@section('admin_content')

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary">Thêm Tài Khoản Mới
            <a href="{{ url('admin/users') }}" class="btn btn-danger btn-sm float-end">Quay lại</a>
        </h4>
    </div>
    <div class="card-body">
        <form action="{{ url('admin/users') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Họ và Tên</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email (Tài khoản đăng nhập)</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Mật khẩu</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Phân quyền (Role)</label>
                <select name="role_as" class="form-control">
                    <option value="0">Khách hàng (User)</option>
                    <option value="1">Quản trị viên (Admin)</option>
                    <option value="2">Quản lý (Manager)</option> {{-- 👇 MỚI THÊM --}}
                    <option value="3">Nhân viên (Staff)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Lưu Tài Khoản</button>

        </form>
    </div>
</div>

@endsection