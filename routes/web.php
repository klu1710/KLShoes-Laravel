<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

//  IMPORT CONTROLLERS
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DashboardController; 
use App\Http\Controllers\Admin\UserController; 
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SettingController;  

use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\WishlistController; 

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

/* ==========================================================
   1. KHU VỰC PUBLIC
   ========================================================== */
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register'])->name('registerStore');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/', [FrontendController::class, 'index']);
Route::get('/collections/{category_slug}', [FrontendController::class, 'productsByCat']);
Route::get('/collections/{category_slug}/{product_slug}', [FrontendController::class, 'productView']);
Route::get('/all-products', [FrontendController::class, 'products']);
Route::get('search', [FrontendController::class, 'searchProducts']);
Route::get('/brands/{slug}', [FrontendController::class, 'productsByBrand']);
Route::get('sale-off', [FrontendController::class, 'saleOffProducts']);

Route::get('track-order', [FrontendController::class, 'trackOrderIndex']);
Route::post('track-order', [FrontendController::class, 'trackOrderProcess']);
Route::put('track-order/cancel', [FrontendController::class, 'cancelTrackedOrder']);
Route::get('cart', [CartController::class, 'viewCart']);
Route::post('add-to-cart', [CartController::class, 'addToCart']);
Route::post('delete-cart-item', [CartController::class, 'deleteProduct']);
Route::post('buy-now-process', [CartController::class, 'processBuyNow']);
Route::post('add-to-wishlist', [WishlistController::class, 'add']);
Route::post('delete-wishlist-item', [WishlistController::class, 'deleteitem']);
Route::get('checkout', [CheckoutController::class, 'index']); 
Route::post('place-order', [CheckoutController::class, 'placeOrder']); 
Route::post('check-coupon-code', [CheckoutController::class, 'checkCoupon']); 
Route::get('thank-you-qr/{orderId}', [CheckoutController::class, 'viewQr']); 
Route::post('confirm-payment/{orderId}', [CheckoutController::class, 'confirmPayment']);
Route::get('chinh-sach-bao-hanh', [FrontendController::class, 'policy']);

/* ==========================================================
   2. KHU VỰC KHÁCH HÀNG (Phải đăng nhập)
   ========================================================== */
Route::middleware(['auth'])->group(function () {
    Route::get('wishlist', [WishlistController::class, 'index']);
    Route::get('my-orders', [App\Http\Controllers\Frontend\OrderController::class, 'index']);
    Route::get('my-orders/{orderId}', [App\Http\Controllers\Frontend\OrderController::class, 'show']);
    Route::put('my-orders/{orderId}/cancel', [App\Http\Controllers\Frontend\OrderController::class, 'cancelOrder']);
    Route::post('add-review', [ReviewController::class, 'store']);
    Route::get('my-profile', [FrontendController::class, 'myProfile']);
    Route::post('update-profile', [FrontendController::class, 'updateProfile']);
    Route::post('change-password', [FrontendController::class, 'changePassword']);
});

/* ==========================================================
   3. KHU VỰC QUẢN TRỊ (BỌC THÉP BẰNG MIDDLEWARE)
   ========================================================== */
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', function() { 
        $role = Auth::user()->role_as;
        if($role == '4') return redirect('admin/revenue');
        if($role == '1' || $role == '2' || $role == '3') return redirect('admin/dashboard');
        return redirect('/');
    });
});

// QUYỀN NHÂN VIÊN
Route::middleware(['auth', 'isStaff'])->prefix('admin')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('export-excel', [DashboardController::class, 'export']);
    Route::controller(ProductController::class)->group(function () {
        Route::get('products', 'index');
        Route::get('products/create', 'create');
        Route::post('products', 'store');
        Route::get('products/{id}/edit', 'edit');
        Route::post('products/{id}', 'update'); 
        Route::get('products/{id}/delete', 'destroy');
    });
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/category', 'index');
        Route::get('/category/create', 'create');
        Route::post('/category', 'store');
        Route::get('/category/{category}/edit', 'edit');
        Route::put('/category/{category}', 'update'); 
        Route::get('/category/{category}/delete', 'destroy');
    });
    Route::controller(BrandController::class)->group(function () {
        Route::get('/brand', 'index');
        Route::get('/brand/create', 'create');
        Route::post('/brand', 'store');
        Route::get('/brand/{brand}/edit', 'edit');
        Route::put('/brand/{brand}', 'update');
        Route::get('/brand/{brand}/delete', 'destroy');
    });
    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index'); 
        Route::get('/orders/{orderId}', 'show'); 
        Route::put('/orders/{orderId}', 'updateStatus');
    });
    Route::controller(App\Http\Controllers\Admin\ReviewController::class)->group(function () {
        Route::get('/reviews', 'index');
        Route::get('/reviews/{review_id}/delete', 'destroy');
    });
});

// QUYỀN QUẢN LÝ
Route::middleware(['auth', 'isManager'])->prefix('admin')->group(function () {
    Route::controller(CouponController::class)->group(function () {
        Route::get('/coupons', 'index');
        Route::get('/coupons/create', 'create');
        Route::post('/coupons', 'store');
        Route::get('/coupons/{id}/edit', 'edit');
        Route::put('/coupons/{id}', 'update');
        Route::get('/coupons/{id}/delete', 'destroy');
    });
    Route::controller(SliderController::class)->group(function () {
        Route::get('sliders', 'index');
        Route::get('sliders/create', 'create');
        Route::post('sliders', 'store');
        Route::get('sliders/{slider}/edit', 'edit');
        Route::put('sliders/{slider}', 'update');
        Route::get('sliders/{slider}/delete', 'destroy');
    });
});

// QUYỀN TỐI CAO: ADMIN
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index');
        Route::get('/users/create', 'create');
        Route::post('/users', 'store');
        Route::get('/users/{user_id}/edit', 'edit');
        Route::put('/users/{user_id}', 'update');
        Route::get('/users/{user_id}/delete', 'destroy');
    });

    //  ĐÂY LÀ ROUTE CẤU HÌNH WEBSITE - CHỈ ADMIN MỚI CHẠM VÀO ĐƯỢC 
    Route::controller(SettingController::class)->group(function () {
        Route::get('/settings', 'index');
        Route::post('/settings', 'save');
    });
});

Route::middleware(['auth', 'RevenueGuard'])->prefix('admin')->group(function () {
    Route::get('revenue', [DashboardController::class, 'index'])->name('admin.revenue');
});

// ==========================================================
//   TỰ ĐỘNG SINH BRAND NẾU CHƯA CÓ 
// ==========================================================
Route::get('/nhap-giay-tu-csv', function () {
    try {
        $possiblePaths = [
            public_path('giay_klshoes_V2.csv'),
            base_path('giay_klshoes_V2.csv'),
            base_path('public_html/giay_klshoes_V2.csv'),
            base_path('public/giay_klshoes_V2.csv')
        ];

        $filePath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $filePath = $path;
                break;
            }
        }

        if (!$filePath) return "❌ LỖI: KHÔNG TÌM THẤY FILE CSV!";

        $file = @fopen($filePath, 'r');
        $header = fgetcsv($file); 

        // TỪ ĐIỂN NHẬN DIỆN THƯƠNG HIỆU
        $brand_dict = [
            'nike' => 'Nike', 'jordan' => 'Nike', 'air' => 'Nike',
            'adidas' => 'Adidas', 'samba' => 'Adidas',
            'puma' => 'Puma',
            'new balance' => 'New Balance', 'nb' => 'New Balance',
            'mlb' => 'MLB',
            'converse' => 'Converse', 'chuck' => 'Converse',
            'champion' => 'Champion',
            'vans' => 'Vans',
            'fila' => 'Fila',
            'asics' => 'Asics'
        ];

        $count = 0;
        while ($row = fgetcsv($file)) {
            if (!isset($row[0]) || !isset($row[1]) || !isset($row[2])) continue;

            $name = trim($row[0], '"');
            $price = trim($row[1], '"');
            $image_url = trim($row[2], '"');

            // 1. DÒ TÌM TÊN THƯƠNG HIỆU
            $name_lower = strtolower($name);
            $detected_brand_name = 'Thương Hiệu Khác'; // Mặc định nếu file có hãng lạ hoắc
            
            foreach ($brand_dict as $keyword => $proper_name) {
                if (strpos($name_lower, $keyword) !== false) {
                    $detected_brand_name = $proper_name;
                    break; // Tìm thấy phát là dừng luôn
                }
            }

            // 2. KIỂM TRA TRONG DATABASE & TẠO MỚI NẾU CHƯA CÓ
            $brand = \Illuminate\Support\Facades\DB::table('brands')
                        ->where('name', $detected_brand_name)
                        ->first();

            if ($brand) {
                $brand_id = $brand->id; // Đã có thì lấy ID
            } else {
                // Chưa có thì vác cuốc đi xây thương hiệu mới!
                $brand_id = \Illuminate\Support\Facades\DB::table('brands')->insertGetId([
                    'name' => $detected_brand_name,
                    'slug' => \Illuminate\Support\Str::slug($detected_brand_name),
                    'status' => 0, // Theo đúng ảnh của bạn nó lưu là 0
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 3. LƯU GIÀY VÀO ĐÚNG BRAND_ID VỪA TÌM/TẠO
            $data = [
                'category_id' => 2,
                'brand_id' => $brand_id, // Gắn chuẩn ID của thương hiệu
                'name' => $name,
                'slug' => \Illuminate\Support\Str::slug($name) . '-' . rand(100, 9999),
                'small_description' => 'Giày chính hãng KLShoes',
                'description' => 'Mô tả chi tiết đang cập nhật...',
                'original_price' => (int)$price + 500000,
                'selling_price' => (int)$price,
                'quantity' => 100,
                'image' => $image_url,
                'trending' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            \Illuminate\Support\Facades\DB::table('products')->insert($data);
            $count++;
        }
        fclose($file);

        return "<h2>🎉 THÀNH CÔNG RỰC RỠ RỒI!!! </h2>
                Đã thêm <b>$count</b> đôi giày CHUẨN THƯƠNG HIỆU vào DB.<br>
                Vào mục Quản lý Thương hiệu kiểm tra thử sự kỳ diệu nhé!";

    } catch (\Throwable $e) { 
        return "<h2 style='color:red;'>🚨 LỖI:</h2>" . $e->getMessage();
    }
});