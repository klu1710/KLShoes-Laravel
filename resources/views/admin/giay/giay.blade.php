@extends('admin.index')

@section('admin_content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h4 class="m-0 font-weight-bold text-primary" style="margin-top: 10px">
            <strong>DANH SÁCH GIÀY (KLShoes)</strong>&ensp;
            <i class="fas fa-shoe-prints"></i>
        </h4>
        {{-- Nút thêm mới đưa lên đây cho tiện --}}
        <a href="{{ url('admin/products/create') }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus-circle"></i> Thêm Giày Mới
        </a>
    </div>

    <div class="card-body">
        
        {{-- Hiển thị thông báo thành công --}}
        @if(session('message'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-light text-dark">
                    <tr class="text-center">
                        <th style="width: 5%">ID</th>
                        <th style="width: 10%">Hình ảnh</th>
                        <th style="width: 25%">Tên giày</th>
                        <th>Danh mục</th>
                        <th>Thương hiệu</th>
                        <th>Giá bán</th>
                        <th>Kho</th>
                        <th style="width: 15%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- SỬA 1: Duyệt mảng $products (từ Controller gửi sang) --}}
                    @foreach ($products as $key => $item)
                    <tr>
                        <td class="text-center">{{ $item->id }}</td>
                        
                        <td class="text-center">
                            {{-- SỬA 2: Hiển thị ảnh (Check nếu có ảnh thì hiện, ko thì hiện chữ) --}}
                            @if($item->image)
                                <img src="{{ asset($item->image) }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                            @else
                                <span class="badge badge-secondary">No Image</span>
                            @endif
                        </td>

                        <td>
                            <strong class="text-primary">{{ $item->name }}</strong>
                            <br>
                            <small class="text-muted">Slug: {{ $item->slug }}</small>
                        </td>

                        <td>
                            {{-- SỬA 3: Gọi quan hệ category (Cần Model Product có hàm category) --}}
                            <span class="badge badge-info">{{ $item->category->name ?? 'Trống' }}</span>
                        </td>

                        <td>
                            {{-- SỬA 4: Gọi quan hệ brand (Cần Model Product có hàm brand) --}}
                            <span class="badge badge-warning text-dark">{{ $item->brand->name ?? 'Trống' }}</span>
                        </td>

                        <td class="text-right text-danger font-weight-bold">
                            {{ number_format($item->selling_price) }} đ
                        </td>

                        <td class="text-center">
                            {{-- Tính tổng số lượng --}}
                            <span class="badge badge-secondary">{{ $item->productSizes->sum('quantity') }}</span>
                        </td>

                        <td class="text-center">
                            {{-- SỬA 5: Link sửa/xóa chuẩn Laravel --}}
                            <a href="{{ url('admin/products/'.$item->id.'/edit') }}" class="btn btn-warning btn-sm btn-circle" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <a href="{{ url('admin/products/'.$item->id.'/delete') }}" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa giày: {{ $item->name }} không?');" 
                               class="btn btn-danger btn-sm btn-circle" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Phân trang --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Script bật DataTables --}}
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": false, // Tắt phân trang của JS để dùng phân trang Laravel
            "info": false,
            "searching": true
        });
    });
</script>

@endsection