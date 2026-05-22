<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        //  ĐĂNG KÝ CÁC (MIDDLEWARE)  
        $middleware->alias([
            'isAdmin' => \App\Http\Middleware\AdminMiddleware::class,
            'isManager' => \App\Http\Middleware\ManagerMiddleware::class,
            'isStaff' => \App\Http\Middleware\StaffMiddleware::class,
            
            //  THÊM DÒNG NÀY ĐỂ BẢO VỆ TRANG DOANH THU
            'RevenueGuard' => \App\Http\Middleware\RevenueGuard::class, 
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();