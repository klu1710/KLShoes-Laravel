@extends('admin.index')

@section('admin_content')

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary text-uppercase">THÊM MÃ GIẢM GIÁ</h4>
    </div>

    <div class="card-body">
        
        {{-- Hiển thị lỗi nếu nhập sai --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('admin/coupons') }}" method="POST">
            @csrf
            
            <div class="row">
                {{-- Cột 1: Thông tin cơ bản --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="font-weight-bold">Mã Code (4 ký tự)</label>
                        <input type="text" name="code" class="form-control" placeholder="VD: SALE" maxlength="4" required>
                        <small class="text-muted">Nhập mã viết liền không dấu (Ví dụ: TET1, A1B2)</small>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold">Số lượng phát hành</label>
                        <input type="number" name="quantity" class="form-control" placeholder="Nhập số lượng..." required>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold">Ngày bắt đầu</label>
                        <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                {{-- Cột 2: Giá trị & Thời gian --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="font-weight-bold">Loại giảm giá</label>
                        <select name="type" class="form-control">
                            <option value="1">Theo Phần trăm (%)</option>
                            <option value="2">Theo Tiền mặt (VND)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold">Giá trị giảm</label>
                        <input type="number" name="value" class="form-control" placeholder="VD: 10 hoặc 50000" required>
                        <small class="text-muted">Nếu chọn %, nhập số (VD: 10). Nếu chọn tiền, nhập số tiền (VD: 50000)</small>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold">Ngày kết thúc</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                </div>

                {{-- Trạng thái --}}
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="font-weight-bold d-block">Trạng thái</label>
                        <div class="form-check">
                            {{-- Logic cũ: Check vào là ẨN (status=1), Không check là HIỆN (status=0) --}}
                            <input class="form-check-input" type="checkbox" name="status" id="statusCheck">
                            <label class="form-check-label text-danger" for="statusCheck">
                                Tạm ẩn mã này (Không hiển thị cho khách hàng)
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Các nút bấm giống trang Loại giày --}}
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary font-weight-bold text-uppercase px-4">
                        LƯU LẠI
                    </button>
                    
                    <a href="{{ url('admin/coupons') }}" class="btn btn-warning font-weight-bold text-uppercase text-white px-4 ml-2">
                        QUAY LẠI
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection