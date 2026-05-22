@extends('admin.index')

@section('admin_content')

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary">Sửa Tài Khoản
            <a href="{{ url('admin/users') }}" class="btn btn-danger btn-sm float-end">Quay lại</a>
        </h4>
    </div>
    <div class="card-body">
        <form action="{{ url('admin/users/'.$user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label>Họ và Tên</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Email (Không thể sửa)</label>
                        <input type="text" value="{{ $user->email }}" class="form-control" readonly style="background-color: #e9ecef;">
                    </div>

                    <div class="mb-3">
                        <label>Mật khẩu mới (Để trống nếu không muốn đổi)</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Phân quyền (Role)</label>
                        <select name="role_as" class="form-control">
                            
                            {{-- 👇 ĐÃ SỬA LẠI ĐÚNG QUY ƯỚC CỦA BẠN 👇 --}}

                            {{-- 1: Admin --}}
                            <option value="1" {{ $user->role_as == '1' ? 'selected':'' }}>Quản trị viên (Admin)</option>
                            
                            {{-- 2: Quản lý --}}
                            <option value="2" {{ $user->role_as == '2' ? 'selected':'' }}>Quản lý (Manager)</option>
                            
                            {{-- 3: Nhân viên --}}
                            <option value="3" {{ $user->role_as == '3' ? 'selected':'' }}>Nhân viên (Staff)</option>

                            {{-- 4: Giám đốc (Mới thêm) --}}
                            <option value="4" {{ $user->role_as == '4' ? 'selected':'' }}>Ban Giám đốc (Director)</option>

                            {{-- 0: Khách hàng --}}
                            <option value="0" {{ $user->role_as == '0' ? 'selected':'' }}>Khách hàng (User)</option>

                            {{-- 👆 KẾT THÚC SỬA 👆 --}}

                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label>Ảnh đại diện (Avatar)</label>
                        <input type="file" name="avatar" class="form-control">
                        <div class="mt-3 text-center">
                            <label>Ảnh hiện tại:</label><br>
                            @if($user->avatar)
                                <img src="{{ asset($user->avatar) }}" class="rounded-circle shadow" width="150" height="150" style="object-fit: cover; border: 3px solid #ddd;">
                            @else
                                <div class="alert alert-secondary d-inline-block">Chưa có ảnh</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary px-4">Cập nhật</button>
            </div>

        </form>
    </div>
</div>

@endsection