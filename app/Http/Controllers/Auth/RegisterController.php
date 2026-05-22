<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // 1. Hiện Form Đăng ký
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // 2. Xử lý Đăng ký
    public function register(Request $request)
    {
        // Bước 1: Kiểm tra dữ liệu nhập vào
    
        $request->validate([
            'ten_nguoi_dung' => ['required', 'string', 'max:255'], 
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'], // Mình đã bỏ 'confirmed' vì form của bạn có thể không có ô nhập lại mật khẩu
        ], [
            'ten_nguoi_dung.required' => 'Vui lòng nhập tên người dùng',
            'email.required' => 'Vui lòng nhập email',
            'email.unique' => 'Email này đã được sử dụng',
            'password.min' => 'Mật khẩu phải từ 8 ký tự',
        ]);

        // Bước 2: Tạo User mới vào Database
        $user = User::create([
            'name' => $request->ten_nguoi_dung, 
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_as' => 0, 
        ]);

        // Bước 3: Đăng nhập luôn cho họ sau khi đăng ký
        Auth::login($user);

        // Bước 4: Chuyển hướng về Trang chủ
        return redirect('/')->with('message', 'Đăng ký tài khoản thành công!');
    }
}