<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Route;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | Controller này chịu trách nhiệm xử lý việc đặt lại mật khẩu mới
    | và cập nhật vào database.
    |
    */

    use ResetsPasswords;

    // Sau khi đổi pass xong thì chuyển hướng về đâu? -> Về trang chủ
    protected $redirectTo = '/';
}