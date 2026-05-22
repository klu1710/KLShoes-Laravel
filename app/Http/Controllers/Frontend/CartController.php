<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session; 
use App\Models\Product;
use App\Models\Cart;

class CartController extends Controller
{
    // 1. Thêm vào giỏ hàng (Xử lý cho cả Khách & Member)
    public function addToCart(Request $request)
    {
        $product_id = $request->input('product_id');
        $product_qty = $request->input('quantity');
        $color = $request->input('color'); // Lấy thông tin màu
        $size = $request->input('size');   // Lấy thông tin size
        
        // Kiểm tra sản phẩm có tồn tại không
        $prod_check = Product::where('id', $product_id)->first();

        if ($prod_check) {
            
            // --- BƯỚC 1 & 2: Xác định User/Session và Kiểm tra trùng lặp BIẾN THỂ ---
            if (Auth::check()) {
                $cartItem = Cart::where('product_id', $product_id)
                                ->where('user_id', Auth::id())
                                ->where('color', $color) // Phải khớp màu
                                ->where('size', $size)   // Phải khớp size
                                ->exists();
            } else {
                $cartItem = Cart::where('product_id', $product_id)
                                ->where('session_id', Session::getId())
                                ->where('color', $color) // Phải khớp màu
                                ->where('size', $size)   // Phải khớp size
                                ->exists();
            }

            if ($cartItem) {
                // Báo lỗi chi tiết hơn cho khách biết
                return response()->json(['status' => $prod_check->name . " (Size: $size - Màu: $color) đã có trong giỏ hàng rồi!"]);
            } 
            else {
                // --- BƯỚC 3: Tạo mới giỏ hàng ---
                $cartItem = new Cart();
                $cartItem->product_id = $product_id;
                
                // Lưu đúng định danh người dùng
                if(Auth::check()){
                    $cartItem->user_id = Auth::id();
                    $cartItem->session_id = null; 
                } else {
                    $cartItem->user_id = null;
                    $cartItem->session_id = Session::getId(); 
                }
                
                $cartItem->quantity = $product_qty;
                $cartItem->color = $color; 
                $cartItem->size = $size;
                $cartItem->save();
                
                return response()->json(['status' => "Đã thêm " . $prod_check->name . " vào giỏ hàng!"]);
            }
        }
        else {
             return response()->json(['status' => "Sản phẩm không tồn tại!"]);
        }
    }

    // 2. Xóa sản phẩm
    public function deleteProduct(Request $request)
    {
        $prod_id = $request->input('product_id');

        
        if(Auth::check()){
            $cartItem = Cart::where('product_id', $prod_id)->where('user_id', Auth::id())->first();
        } else {
            $cartItem = Cart::where('product_id', $prod_id)->where('session_id', Session::getId())->first();
        }
        
        if($cartItem){
            $cartItem->delete();
            return response()->json(['status' => "Đã xóa sản phẩm thành công!"]);
        }
        
        return response()->json(['status' => "Không tìm thấy sản phẩm trong giỏ!"]);
    }

    // 3. Xem giỏ hàng
    public function viewCart()
    {
        if(Auth::check()){
            $cartItems = Cart::where('user_id', Auth::id())->get();
        } else {
            $cartItems = Cart::where('session_id', Session::getId())->get();
        }

        return view('frontend.cart', compact('cartItems')); 
    }

    //  4. HÀM XỬ LÝ MUA NGAY (KHÔNG LƯU DB) 
    public function processBuyNow(Request $request)
    {
        // Kiểm tra tồn kho cơ bản trước
        $prod_check = Product::where('id', $request->product_id)->first();
        if(!$prod_check){
            return response()->json(['status' => "Sản phẩm không tồn tại!"]);
        }

        // Lưu thông tin sản phẩm muốn mua ngay vào Session tạm thời
        Session::put('buy_now_item', [
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'color' => $request->color,
            'size' => $request->size
        ]);

        return response()->json([
            'status' => "Đang chuyển đến trang thanh toán...",
            //  Thêm tham số ?mode=buynow để CheckoutController phân biệt
            'redirect' => url('checkout?mode=buynow') 
        ]);
    }
}