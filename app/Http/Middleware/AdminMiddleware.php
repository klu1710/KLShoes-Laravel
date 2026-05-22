<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $role = Auth::user()->role_as;

            // 1. NẾU LÀ GIÁM ĐỐC (Role 4)
            // Ông này chỉ được xem doanh thu, nếu cố vào trang quản lý khác -> Đẩy sang trang Doanh thu
            if ($role == '4') {
                return redirect('/admin/revenue')->with('message', 'Giám đốc chỉ được xem Thống kê doanh thu!'); 
            }

            // 2. CÁC QUYỀN ĐƯỢC VÀO DASHBOARD QUẢN LÝ
            // - 1: Nhân viên
            // - 2: Quản lý
            // - 3: Admin (Quản trị viên)
            if ($role == '1' || $role == '2' || $role == '3') {
                return $next($request);
            }

            // 3. KHÁCH HÀNG (Role 0) -> ĐÁ VỀ TRANG CHỦ
            return redirect('/')->with('message', 'Bạn không có quyền truy cập Admin!');
        }
        
        // 4. CHƯA ĐĂNG NHẬP
        return redirect('/login')->with('message', 'Vui lòng đăng nhập để tiếp tục!');
    }
}