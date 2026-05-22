@extends('admin.index')

@section('admin_content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h4 class="m-0 font-weight-bold text-primary">QUẢN LÝ ĐÁNH GIÁ & BÌNH LUẬN</h4>
    </div>

    <div class="card-body">
        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr class="text-center bg-light text-dark">
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Sản phẩm</th>
                        <th>Đánh giá</th>
                        <th>Nội dung bình luận</th>
                        <th>Ngày gửi</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reviews as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>
                                <b>{{ $item->user->name ?? 'User đã xóa' }}</b>
                                <br>
                                <small>{{ $item->user->email ?? '' }}</small>
                            </td>
                            <td>
                                @if($item->product)
                                    <a href="{{ url('collections/'.$item->product->category->slug.'/'.$item->product->slug) }}" target="_blank">
                                        {{ $item->product->name }}
                                    </a>
                                @else
                                    Sản phẩm đã xóa
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark" style="font-size: 14px;">
                                    {{ $item->rating }} <i class="fas fa-star"></i>
                                </span>
                            </td>
                            <td>
                                <p class="mb-0" style="font-style: italic;">"{{ $item->comment }}"</p>
                            </td>
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <a href="{{ url('admin/reviews/'.$item->id.'/delete') }}" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này không?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            {{-- Phân trang --}}
            <div class="d-flex justify-content-center">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
</div>

@endsection