<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | Controller này chịu trách nhiệm xử lý việc gửi email liên kết 
    | đặt lại mật khẩu cho người dùng.
    |
    */

    use SendsPasswordResetEmails;
}