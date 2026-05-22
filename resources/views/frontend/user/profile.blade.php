@extends('layouts.app')

@section('title', 'Hồ sơ của tôi')

@section('content')

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-warning p-0">
                    {{--  NAV TABS (THANH CHUYỂN TAB) --}}
                    <ul class="nav nav-tabs nav-fill border-0" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold text-dark rounded-0 py-3" id="home-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                                <i class="fas fa-user-circle"></i> HỒ SƠ CÁ NHÂN
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold text-dark rounded-0 py-3" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">
                                <i class="fas fa-key"></i> ĐỔI MẬT KHẨU
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    {{-- Thông báo thành công --}}
                    @if(session('status'))
                        <div class="alert alert-success fw-bold">{{ session('status') }}</div>
                    @endif
                    @if(session('message'))
                        <div class="alert alert-success fw-bold">{{ session('message') }}</div>
                    @endif

                    {{-- Thông báo lỗi --}}
                    @if(session('status_error'))
                        <div class="alert alert-danger fw-bold">{{ session('status_error') }}</div>
                    @endif
                    
                    {{-- Hiển thị lỗi validate --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="tab-content" id="myTabContent">
                        
                        {{-- ================= TAB 1: THÔNG TIN CÁ NHÂN ================= --}}
                        <div class="tab-pane fade show active" id="profile" role="tabpanel">
                            {{--   Thêm enctype để upload ảnh --}}
                            <form action="{{ url('update-profile') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                {{--  KHU VỰC UPLOAD AVATAR CHO KHÁCH HÀNG  --}}
                                <div class="row mb-4 align-items-center">
                                    <div class="col-md-3 text-center">
                                        @if(Auth::user()->avatar)
                                            <img src="{{ asset(Auth::user()->avatar) }}" class="rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #f6f6f6;">
                                        @else
                                            <img src="https://via.placeholder.com/120" class="rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover;">
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-label fw-bold">Ảnh đại diện</label>
                                        <input type="file" name="avatar" class="form-control">
                                        <small class="text-muted">Chọn ảnh đẹp nhất của bạn (jpg, png, jpeg)</small>
                                    </div>
                                </div>
                                <hr>
                                {{--  KẾT THÚC  --}}

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Họ & Tên đệm</label>
                                        <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Email</label>
                                        <input type="text" class="form-control bg-light" readonly value="{{ Auth::user()->email }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Số điện thoại</label>
                                        <input type="text" class="form-control" name="phone" value="{{ Auth::user()->phone }}">
                                    </div>
                                    
                                    <div class="col-md-12 mb-3">
                                        <hr>
                                        <h5 class="fw-bold text-success"><i class="fas fa-map-marker-alt"></i> Địa chỉ mặc định</h5>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Địa chỉ 1 (Số nhà, đường)</label>
                                        <input type="text" class="form-control" name="address1" value="{{ Auth::user()->address1 }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Địa chỉ 2 (Phường/Xã)</label>
                                        <input type="text" class="form-control" name="address2" value="{{ Auth::user()->address2 }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Thành phố / Tỉnh</label>
                                        <input type="text" class="form-control" name="city" value="{{ Auth::user()->city }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Quận / Huyện</label>
                                        <input type="text" class="form-control" name="state" value="{{ Auth::user()->state }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Quốc gia</label>
                                        <input type="text" class="form-control" name="country" value="{{ Auth::user()->country }}">
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <button type="submit" class="btn btn-primary fw-bold px-4">Cập nhật thông tin</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {{-- ================= TAB 2: ĐỔI MẬT KHẨU ================= --}}
                        <div class="tab-pane fade" id="password" role="tabpanel">
                            <form action="{{ url('change-password') }}" method="POST">
                                @csrf
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Mật khẩu hiện tại</label>
                                            <input type="password" class="form-control" name="current_password" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Mật khẩu mới</label>
                                            <input type="password" class="form-control" name="password" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Xác nhận mật khẩu mới</label>
                                            <input type="password" class="form-control" name="password_confirmation" required>
                                        </div>
                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-danger fw-bold text-white">Đổi mật khẩu</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS để Tab đẹp hơn */
    .nav-tabs .nav-link { color: #333; transition: 0.3s; }
    .nav-tabs .nav-link.active { background-color: #fff !important; border-bottom: 3px solid #ffc107; color: #000 !important; }
    .nav-tabs .nav-link:hover { background-color: rgba(255,255,255,0.2); }
</style>

@endsection