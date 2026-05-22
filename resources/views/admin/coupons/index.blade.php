@extends('admin.index')

@section('admin_content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h4 class="m-0 font-weight-bold text-primary">QUẢN LÝ MÃ GIẢM GIÁ</h4>
        <a href="{{ url('admin/coupons/create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Thêm Mã Mới
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
                    <th>Mã Code</th>
                    <th>Giảm giá</th>
                    <th>Số lượng</th>
                    <th>Hạn dùng</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coupons as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    
                    {{-- Mã Code --}}
                    <td class="text-uppercase font-weight-bold">{{ $item->code }}</td>
                    
                    {{-- Logic hiển thị Giá trị giảm (% hay Tiền) --}}
                    <td>
                        @if($item->type == '1')
                            {{ $item->value }}%
                        @else
                            {{ number_format($item->value, 0, ',', '.') }}đ
                        @endif
                    </td>

                    {{-- Số lượng --}}
                    <td>{{ $item->quantity }}</td>

                    {{-- Logic hiển thị Ngày hết hạn --}}
                    <td>
                        @if($item->end_date)
                            {{ $item->end_date->format('d/m/Y') }}
                            @if($item->end_date < now())
                                <br><small class="text-danger font-weight-bold">(Hết hạn)</small>
                            @endif
                        @else
                            Vô thời hạn
                        @endif
                    </td>

                    {{-- Trạng thái (Lưu ý: Logic Controller là 0=Hiện, 1=Ẩn) --}}
                    <td>
                        @if($item->status == '0')
                            <span class="badge bg-success text-white">Hiển thị</span>
                        @else
                            <span class="badge bg-danger text-white">Đang ẩn</span>
                        @endif
                    </td>

                    {{-- Các nút bấm --}}
                    <td>
                        <a href="{{ url('admin/coupons/'.$item->id.'/edit') }}" class="btn btn-success btn-sm">Sửa</a>
                        
                        <a href="{{ url('admin/coupons/'.$item->id.'/delete') }}" 
                           onclick="return confirm('Bạn chắc chắn muốn xóa mã này?')" 
                           class="btn btn-danger btn-sm">Xóa</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        {{-- 
           LƯU Ý: Nếu Controller bạn dùng ->get() thì xóa đoạn div phân trang dưới đây đi để tránh lỗi.
           Nếu Controller bạn dùng ->paginate(10) thì giữ lại.
        --}}
        <div class="mt-3">
            {{-- {{ $coupons->links() }} --}} 
        </div>
    </div>
</div>
@endsection