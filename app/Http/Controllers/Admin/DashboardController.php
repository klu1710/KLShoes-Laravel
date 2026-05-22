<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

//  Phải dùng đúng tên file Export bạn đã tạo
use App\Exports\RevenueExport; 
use Maatwebsite\Excel\Facades\Excel; 

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Tổng đơn hàng (Trừ đơn đã hủy)
        $totalOrder = Order::where('status_message', '!=', 'cancelled')->count();

        // 2. Tổng doanh thu (Trừ đơn đã hủy)
        $totalRevenue = Order::where('status_message', '!=', 'cancelled')->sum('total_price');

        // 3. Doanh thu hôm nay (Trừ đơn đã hủy)
        $todayRevenue = Order::whereDate('created_at', Carbon::today())
                             ->where('status_message', '!=', 'cancelled')
                             ->sum('total_price');

        // 4. Doanh thu THÁNG NÀY (Trừ đơn đã hủy)
        $thisMonthRevenue = Order::whereMonth('created_at', Carbon::now()->month)
                                 ->whereYear('created_at', Carbon::now()->year)
                                 ->where('status_message', '!=', 'cancelled')
                                 ->sum('total_price');

        // 5. Dữ liệu cho BIỂU ĐỒ (Doanh thu 12 tháng - Trừ đơn đã hủy)
        $chartData = [];
        $currentYear = Carbon::now()->year;

        for ($i = 1; $i <= 12; $i++) {
            $monthRevenue = Order::whereMonth('created_at', $i)
                                 ->whereYear('created_at', $currentYear)
                                 ->where('status_message', '!=', 'cancelled') // 👈 Quan trọng: Loại bỏ đơn hủy
                                 ->sum('total_price');
            
            $chartData[] = $monthRevenue; 
        }
        
        // Chuyển mảng thành JSON để JS đọc được
        $chartData = json_encode($chartData);

        // 6. Tổng số sản phẩm
        $totalProducts = Product::count();

        // 7. Lấy 5 đơn hàng mới nhất (Vẫn hiện đơn hủy để Admin biết)
        $recentOrders = Order::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalOrder', 
            'totalRevenue', 
            'todayRevenue', 
            'totalProducts', 
            'recentOrders',
            'thisMonthRevenue',
            'chartData'
        ));
    }

    //  HÀM XUẤT EXCEL
    public function export() 
    {
        // Sử dụng RevenueExport mà chúng ta đã tạo
        return Excel::download(new RevenueExport, 'doanh-thu-klshoes-' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }
}