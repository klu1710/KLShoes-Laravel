@extends('admin.index') 

@section('admin_content')

<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between flex-wrap">
            <div class="d-flex align-items-end flex-wrap">
                <div class="me-md-3 me-xl-5">
                    <h3 class="m-0 font-weight-bold text-primary">Dashboard Thống Kê (Năm {{ date('Y') }})</h3>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-end flex-wrap">
                {{--  NÚT XUẤT EXCEL  --}}
                <a href="{{ url('admin/export-excel') }}" class="btn btn-success mt-2 mt-xl-0 text-white shadow-sm">
                    <i class="fas fa-file-excel"></i> Xuất Báo Cáo Excel
                </a>
            </div>
        </div>
    </div>
</div>

{{-- HÀNG 1: CÁC THẺ THỐNG KÊ --}}
<div class="row mt-3">
    {{-- Ô 1: Tổng Đơn hàng --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Tổng Đơn Hàng</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrder }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ô 2: Tổng Doanh Thu --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Tổng Doanh Thu</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalRevenue, 0, ',', '.') }}đ</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ô 3: Doanh Thu Hôm Nay --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Hôm Nay</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($todayRevenue, 0, ',', '.') }}đ</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ô 4: Doanh Thu Tháng Này --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Tháng Này</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($thisMonthRevenue, 0, ',', '.') }}đ</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- HÀNG 2: BIỂU ĐỒ DOANH THU --}}
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Biểu Đồ Doanh Thu 12 Tháng</h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="height: 350px;">
                    <canvas id="myAreaChart"></canvas> 
                </div>
            </div>
        </div>
    </div>
</div>

{{-- HÀNG 3: DANH SÁCH ĐƠN HÀNG --}}
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-white">
        <h6 class="m-0 font-weight-bold text-primary">Đơn hàng mới nhất</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Phương thức</th>
                        <th>Ngày đặt</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->fullname }}</td>
                        <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                        <td>
                            <span class="badge bg-secondary text-white">{{ $order->payment_mode }}</span>
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ url('admin/orders/'.$order->id) }}" class="btn btn-sm btn-primary">Xem</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 👇 ĐÃ SỬA: Dùng Chart.js v2.9.4 để KHỚP với code JS bên dưới (Fix lỗi biểu đồ không hiện) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>

<script>
    // 1. Lấy dữ liệu từ Controller
    var chartData = JSON.parse('{!! $chartData !!}');

    // 2. Cấu hình Biểu đồ
    var ctx = document.getElementById("myAreaChart");
    var myLineChart = new Chart(ctx, {
        type: 'line', 
        data: {
            labels: ["T1", "T2", "T3", "T4", "T5", "T6", "T7", "T8", "T9", "T10", "T11", "T12"],
            datasets: [{
                label: "Doanh thu",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: chartData, 
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
            scales: {
                xAxes: [{ gridLines: { display: false, drawBorder: false }, ticks: { maxTicksLimit: 12 } }],
                yAxes: [{ 
                    ticks: { 
                        maxTicksLimit: 5, padding: 10,
                        callback: function(value) { return new Intl.NumberFormat('vi-VN').format(value) + 'đ'; }
                    },
                    gridLines: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] }
                }],
            },
            legend: { display: false },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': ' + new Intl.NumberFormat('vi-VN').format(tooltipItem.yLabel) + 'đ';
                    }
                }
            }
        }
    });
</script>

@endsection