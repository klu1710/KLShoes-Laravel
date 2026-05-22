<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cho phép role 1, 2, 3 truy cập
        if(Auth::check() && (Auth::user()->role_as == '1' || Auth::user()->role_as == '2' || Auth::user()->role_as == '3')){
            return $next($request);
        }
        return redirect('/')->with('message', 'Bạn không có quyền truy cập!');
    }
}