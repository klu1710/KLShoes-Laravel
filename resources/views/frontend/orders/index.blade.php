@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')

<div class="py-3 py-md-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="shadow bg-white p-3">
                    <h4 class="mb-4">Đơn hàng của tôi</h4>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Ngày đặt</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $item)
                                    <tr>
                                        <td>{{ $item->tracking_no }}</td>
                                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            @if($item->status_message == 'in progress')
                                                <span class="badge bg-warning text-dark">Đang xử lý</span>
                                            @elseif($item->status_message == 'completed')
                                                <span class="badge bg-success">Đã hoàn thành</span>
                                            @elseif($item->status_message == 'cancelled')
                                                <span class="badge bg-danger">Đã hủy</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $item->status_message }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ url('my-orders/'.$item->id) }}" class="btn btn-primary btn-sm">
                                                Xem chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Bạn chưa mua đơn hàng nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection