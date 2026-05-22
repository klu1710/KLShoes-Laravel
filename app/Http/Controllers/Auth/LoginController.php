<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // 1. Hiển thị Form Đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Xử lý Đăng nhập (Login)
    public function login(Request $request)
    {
        // Validate dữ liệu
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Vui lòng nhập Email',
            'password.required' => 'Vui lòng nhập Mật khẩu',
        ]);

        // Thử đăng nhập
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Kiểm tra quyền: Sếp (1) hoặc Nhân viên (2) thì vào Admin
            if(Auth::user()->role_as == '1' || Auth::user()->role_as == '2'){
                return redirect('admin/products')->with('message', 'Chào Sếp! Đã đăng nhập thành công.');
            }

            // Khách thường (0) thì về trang chủ
            return redirect('/')->with('message', 'Đăng nhập thành công!');
        }

        // Đăng nhập thất bại
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    // 3. Xử lý Đăng xuất (Logout) - KHẮC PHỤC LỖI CỦA BẠN TẠI ĐÂY
    public function logout(Request $request)
    {
        Auth::logout(); // Đăng xuất khỏi hệ thống

        $request->session()->invalidate(); // Hủy session cũ
        $request->session()->regenerateToken(); // Tạo token mới (bảo mật)

        return redirect('/')->with('message', 'Đã đăng xuất thành công!');
    }
}