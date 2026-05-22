<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Hash;


use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User; 
use App\Models\Order; 
use App\Models\Wishlist; 
use App\Models\ProductColor; 
use App\Models\ProductSize; 
use App\Models\Slider;

class FrontendController extends Controller
{
    public function index()
    {
        // 1. Lấy sản phẩm nổi bật
        $featuredProducts = Product::where('trending', '1')->latest()->take(15)->get();
        
        // 2. Lấy sản phẩm mới
        $newProducts = Product::where('status', '1')->orderBy('created_at', 'DESC')->take(10)->get();
        
        // 3. Lấy danh mục
        $categories = Category::where('status', '1')->get();

        // 4. Lấy Slider (Banner) - Đã sửa lỗi code không chạy
        $sliders = Slider::where('status', '0')->get(); 

        // 5. Trả về View cùng lúc tất cả các biến
        return view('frontend.index', compact('featuredProducts', 'newProducts', 'categories', 'sliders'));
    }

    // --- TRANG TẤT CẢ SẢN PHẨM & BỘ LỌC ---
    public function products(Request $request)
    {
        $query = Product::where('status', '1');

        // Lọc theo giá
        if ($request->min_price && $request->max_price) {
            $query->whereBetween('selling_price', [$request->min_price, $request->max_price]);
        }

        // Lọc theo màu
        if ($request->filled('color')) {
            $colors = $request->color;
            $query->whereHas('productSizes', function ($q) use ($colors) {
                $q->whereIn('color_id', $colors)->where('quantity', '>', 0);
            });
        }

        // Lọc theo size
        if ($request->filled('size')) {
            $sizes = $request->size; 
            $query->whereHas('productSizes', function ($q) use ($sizes) {
                $q->whereIn('size', $sizes)->where('quantity', '>', 0);
            });
        }

        $giays = $query->orderBy('created_at', 'DESC')->paginate(12);

        // Sidebar data
        $loaigiays = Category::where('status', '1')->get();
        $thuonghieus = Brand::where('status', '0')->get();
        $allColors = ProductColor::where('status', '0')->get()->unique('name');
        $allSizes = ProductSize::select('size')->distinct()->orderBy('size', 'asc')->pluck('size');

        return view('frontend.products.index', compact('giays', 'loaigiays', 'thuonghieus', 'allColors', 'allSizes'));
    }

    public function productsByCat($category_slug)
    {
        $category = Category::where('slug', $category_slug)->first();
        if($category){
            $products = $category->products()->where('status', '1')->get();
            return view('frontend.collections.products.index', compact('products', 'category'));
        }else{
            return redirect()->back();
        }
    }

    public function productView($category_slug, $product_slug)
    {
        $category = Category::where('slug', $category_slug)->first();
        if ($category) {
            $product = Product::with(['productImages', 'productSizes', 'category', 'reviews.user'])
                        ->where('slug', $product_slug)
                        ->where('status', '1')
                        ->first();
            if ($product) {
                $is_wishlist = false;
                if(Auth::check()){
                    $is_wishlist = Wishlist::where('user_id', Auth::id())
                                             ->where('product_id', $product->id)
                                             ->exists();
                }
                return view('frontend.collections.products.view', compact('product', 'category', 'is_wishlist'));
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    public function productsByBrand($slug)
    {
        $brand = Brand::where('slug', $slug)->first();
        if($brand){
            $giays = $brand->products()->where('status', '1')->paginate(12);
            $loaigiays = Category::where('status', '1')->get();
            $thuonghieus = Brand::where('status', '0')->get();
            return view('frontend.products.index', compact('giays', 'loaigiays', 'thuonghieus'));
        }else{
            return redirect()->back();
        }
    }

    // ==========================================================
    // ==========================================================
    public function searchProducts(Request $request)
    {
        // 1. Kiểm tra xem khách có gõ chữ gì vào khung không
        if($request->keyword){
            // 2. Lấy chữ khách gõ và làm sạch (bỏ khoảng trắng thừa)
            $keyword = trim($request->keyword);
            
            // 3. Tiến hành truy quét kho (Tìm trong tên sản phẩm)
            $searchProducts = Product::where('name', 'LIKE', '%'.$keyword.'%')
                            ->where('status', '1') // Chỉ tìm những giày đang được bán
                            ->orderBy('created_at', 'DESC') // Xếp giày mới lên trước
                            ->paginate(16); // Lấy 16 đôi 1 trang cho gọn
                            
            // 4. Trả kết quả về đúng cái file search.blade.php bạn vừa tạo
            return view('frontend.search', compact('searchProducts', 'keyword'));
            
        } else {
            // Nếu khách không gõ gì mà bấm Enter thì đá về trang cũ
            return redirect()->back()->with('message', 'Bạn chưa nhập từ khóa tìm kiếm nào!');
        }
    }

    public function myProfile()
    {
        return view('frontend.user.profile');
    }

    public function updateProfile(Request $request)
    {
        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->address1 = $request->input('address1');
        $user->address2 = $request->input('address2');
        $user->city = $request->input('city');
        $user->state = $request->input('state');
        $user->country = $request->input('country');
        
        if ($request->hasFile('avatar')) {
            $path = 'uploads/users/' . $user->avatar;
            if(File::exists($path)){ File::delete($path); }
            $file = $request->file('avatar');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('uploads/users/', $filename);
            $user->avatar = 'uploads/users/' . $filename;
        }
        $user->update();
        return redirect()->back()->with('message', 'Cập nhật hồ sơ thành công!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $currentPasswordStatus = Hash::check($request->current_password, auth()->user()->password);
        if($currentPasswordStatus){
            User::findOrFail(Auth::user()->id)->update([
                'password' => Hash::make($request->password),
            ]);
            return redirect()->back()->with('status', 'Đổi mật khẩu thành công!');
        }else{
            return redirect()->back()->with('status_error', 'Mật khẩu cũ không đúng!');
        }
    }

    // --- TRA CỨU ĐƠN HÀNG ---
    public function trackOrderIndex()
    {
        return view('frontend.tracking.index');
    }

    public function trackOrderProcess(Request $request)
    {
        $request->validate([
            'tracking_no' => 'required|string',
            'phone' => 'required|string',
        ]);
        $order = Order::where('tracking_no', $request->tracking_no)
                      ->where('phone', $request->phone)
                      ->first();
        if ($order) {
            return view('frontend.tracking.index', compact('order'));
        } else {
            return redirect()->back()->with('status', 'Không tìm thấy đơn hàng! Vui lòng kiểm tra lại Mã vận đơn và SĐT.');
        }
    }

    // HỦY ĐƠN HÀNG TRA CỨU (CÓ HOÀN KHO)
    public function cancelTrackedOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'tracking_no' => 'required|string',
            'phone' => 'required|string',
        ]);

        $order = Order::where('id', $request->order_id)
                      ->where('tracking_no', $request->tracking_no)
                      ->where('phone', $request->phone)
                      ->first();

        if ($order) {
            if ($order->status_message == 'in progress' || $order->status_message == 'pending') {
                
                // 1. Cập nhật trạng thái
                $order->status_message = 'cancelled';
                $order->save();

                // 2. Hoàn kho (Restock)
                foreach ($order->orderItems as $item) {
                    // Kho tổng
                    $product = Product::where('id', $item->product_id)->first();
                    if($product) {
                        $product->increment('quantity', $item->quantity);
                    }
                    // Kho Size
                    if($item->size) {
                        $productSize = ProductSize::where('product_id', $item->product_id)
                                                  ->where('size', $item->size)
                                                  ->first();
                        if($productSize) {
                            $productSize->increment('quantity', $item->quantity);
                        }
                    }
                }

                return redirect()->back()->with('status', 'Đã hủy đơn hàng và hoàn kho thành công!');
            } else {
                return redirect()->back()->with('status', 'Đơn hàng đã được giao hoặc đã hủy, không thể thao tác!');
            }
        }
        
        return redirect()->back()->with('status', 'Có lỗi xảy ra, không tìm thấy đơn hàng!');
    }

    public function saleOffProducts()
    {
        $allProducts = \App\Models\Product::all();
        $products = $allProducts->filter(function ($item) {
            $priceOriginal = (int) $item->original_price;
            $priceSelling  = (int) $item->selling_price;
            return $priceOriginal > 0 && $priceSelling < $priceOriginal;
        });

        return view('frontend.pages.sale-off', compact('products'));
    }

    public function policy()
    {
        return view('frontend.pages.policy');
    }
}