@extends('admin.index')

@section('admin_content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h4 class="m-0 font-weight-bold text-primary">QUẢN LÝ ĐƠN HÀNG</h4>
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
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->tracking_no }}</td>
                            
                            {{--  CỘT KHÁCH HÀNG (ĐÃ SỬA) --}}
                            <td>
                                <strong>{{ $item->fullname }}</strong><br>
                                <small class="text-muted">{{ $item->phone }}</small>
                                <br>
                                {{-- Logic phân biệt khách --}}
                                @if($item->user_id == null)
                                    <span class="badge bg-secondary text-white" style="background-color: #6c757d;">
                                        Khách vãng lai
                                    </span>
                                @else
                                    <span class="badge bg-success text-white" style="background-color: #198754;">
                                        Thành viên (ID: {{ $item->user_id }})
                                    </span>
                                @endif
                            </td>
                            {{--  ---------------------- --}}

                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                            <td>
                                @if($item->status_message == 'in progress')
                                    <span class="badge bg-warning text-dark">Đang xử lý</span>
                                @elseif($item->status_message == 'completed')
                                    <span class="badge bg-success text-white">Đã hoàn thành</span>
                                @elseif($item->status_message == 'cancelled')
                                    <span class="badge bg-danger text-white">Đã hủy</span>
                                @else
                                    <span class="badge bg-secondary text-white">{{ $item->status_message }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ url('admin/orders/'.$item->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-danger font-weight-bold">
                                Chưa có đơn hàng nào!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Phân trang --}}
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>

@endsection