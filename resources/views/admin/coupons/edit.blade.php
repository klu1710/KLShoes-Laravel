@extends('admin.index')

@section('admin_content')

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary text-uppercase">SỬA MÃ GIẢM GIÁ</h4>
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

        <form action="{{ url('admin/coupons/'.$coupon->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                {{-- Cột 1: Thông tin cơ bản --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="font-weight-bold">Mã Code</label>
                        <input type="text" name="code" value="{{ $coupon->code }}" class="form-control" maxlength="4" required>
                        <small class="text-muted">Mã gồm 4 ký tự (VD: SALE)</small>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold">Số lượng phát hành</label>
                        <input type="number" name="quantity" value="{{ $coupon->quantity }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold">Ngày bắt đầu</label>
                        <input type="date" name="start_date" value="{{ $coupon->start_date ? $coupon->start_date->format('Y-m-d') : '' }}" class="form-control">
                    </div>
                </div>

                {{-- Cột 2: Giá trị & Thời gian --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="font-weight-bold">Loại giảm giá</label>
                        <select name="type" class="form-control">
                            <option value="1" {{ $coupon->type == '1' ? 'selected' : '' }}>Theo Phần trăm (%)</option>
                            <option value="2" {{ $coupon->type == '2' ? 'selected' : '' }}>Theo Tiền mặt (VND)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold">Giá trị giảm</label>
                        <input type="number" name="value" value="{{ $coupon->value }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold">Ngày kết thúc</label>
                        <input type="date" name="end_date" value="{{ $coupon->end_date ? $coupon->end_date->format('Y-m-d') : '' }}" class="form-control">
                    </div>
                </div>

                {{-- Trạng thái --}}
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="font-weight-bold d-block">Trạng thái</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" id="statusCheck" {{ $coupon->status == '1' ? 'checked' : '' }}>
                            <label class="form-check-label text-danger" for="statusCheck">
                                Tạm ẩn mã này (Không hiển thị cho khách hàng)
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Các nút bấm --}}
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary font-weight-bold text-uppercase px-4">
                        CẬP NHẬT
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