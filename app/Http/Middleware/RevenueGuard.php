<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevenueGuard
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Chưa đăng nhập -> Đá về trang login
        if (!Auth::check()) {
            return redirect('/login')->with('status', 'Vui lòng đăng nhập!');
        }

        $role = Auth::user()->role_as;

        // 2. NHỮNG AI ĐƯỢC PHÉP XEM DOANH THU?
        // - Role 4: Giám đốc (Đương nhiên)
        // - Role 3: Admin (Trùm cuối, cho xem luôn)
        if ($role == '4' || $role == '3') {
            return $next($request);
        }

        // 3. CÁC QUYỀN CÒN LẠI (0, 1, 2) -> CHẶN
        // Nếu là Quản lý (2) hoặc Nhân viên (1) cố tình vào -> Đá về Dashboard
        if($role == '1' || $role == '2'){
             return redirect('/admin/dashboard')->with('message', 'Bạn không có quyền xem báo cáo doanh thu!');
        }

        // Khách thường -> Đá về trang chủ
        return redirect('/')->with('status', 'Bạn không có quyền truy cập!');
    }
}