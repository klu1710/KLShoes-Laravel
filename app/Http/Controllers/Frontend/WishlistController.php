<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // 1. Hiển thị trang danh sách yêu thích
    public function index()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->get();
        return view('frontend.wishlist.index', compact('wishlist'));
    }

    // 2. Thêm vào danh sách yêu thích (Xử lý AJAX)
    public function add(Request $request)
    {
        // Kiểm tra đăng nhập
        if(Auth::check())
        {
            $prod_id = $request->input('product_id');

            // Kiểm tra sản phẩm có tồn tại không
            if(Product::find($prod_id)) 
            {
                $wish = Wishlist::where('product_id', $prod_id)
                                ->where('user_id', Auth::id())->first();

                // Nếu đã có rồi -> Không thêm nữa
                if($wish)
                {
                    return response()->json(['status' => "Sản phẩm này đã có trong danh sách yêu thích!"]);
                }
                else
                {
                    // Nếu chưa có -> Tạo mới
                    $wish = new Wishlist();
                    $wish->product_id = $prod_id;
                    $wish->user_id = Auth::id();
                    $wish->save();

                    return response()->json(['status' => "Đã thêm vào danh sách yêu thích! ❤️"]);
                }
            }
            else
            {
                return response()->json(['status' => "Sản phẩm không tồn tại."]);
            }
        }
        else
        {
            return response()->json(['status' => "Vui lòng đăng nhập để tiếp tục!"]);
        }
    }

    // 3. Xóa khỏi danh sách yêu thích
    public function deleteitem(Request $request)
    {
        if(Auth::check())
        {
            $prod_id = $request->input('product_id');
            
            // Tìm đúng sản phẩm của user đó để xóa
            $wish = Wishlist::where('product_id', $prod_id)
                            ->where('user_id', Auth::id())->first();

            if($wish)
            {
                $wish->delete();
                return response()->json(['status' => "Đã xóa khỏi danh sách yêu thích! 💔"]);
            }
            else
            {
                return response()->json(['status' => "Không tìm thấy sản phẩm."]);
            }
        }
        else
        {
            return response()->json(['status' => "Vui lòng đăng nhập để tiếp tục!"]);
        }
    }
}