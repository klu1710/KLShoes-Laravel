<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Chỉ cho role 1 và 2
        if(Auth::check() && (Auth::user()->role_as == '1' || Auth::user()->role_as == '2')){
            return $next($request);
        }
        // Nếu là nhân viên cố vào Dashboard -> Đẩy về trang đơn hàng
        return redirect('/admin/orders')->with('message', 'Bạn không đủ quyền hạn!');
    }
}