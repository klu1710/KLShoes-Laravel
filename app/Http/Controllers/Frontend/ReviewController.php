<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Order;
use App\Models\Product;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $product_id = $request->input('product_id');
        $product = Product::where('id', $product_id)->first();

        if($product)
        {
            $user_review = $request->input('user_review'); // Nội dung bình luận
            $new_rating = $request->input('product_rating'); // Số sao (1-5)

            // 1. KIỂM TRA: Khách đã mua sản phẩm này và đơn hàng đã "completed" chưa?
            $verified_purchase = Order::where('orders.user_id', Auth::id())
                                    ->join('order_items', 'orders.id', 'order_items.order_id')
                                    ->where('order_items.product_id', $product_id)
                                    ->where('orders.status_message', 'completed') // Quan trọng: Chỉ đơn hoàn thành mới được đánh giá
                                    ->select('orders.id') // Lấy ra cái ID đơn hàng để lưu
                                    ->first();

            if($verified_purchase)
            {
                // 2. KIỂM TRA: Đã đánh giá đơn này chưa? (Tránh spam 1 đơn đánh giá 10 lần)
                $existing_review = Review::where('user_id', Auth::id())
                                         ->where('product_id', $product_id)
                                         ->where('order_id', $verified_purchase->id) // Check theo mã đơn
                                         ->first();

                if($existing_review)
                {
                    // Nếu có rồi thì cho cập nhật lại (hoặc báo lỗi tùy bạn)
                    $existing_review->rating = $new_rating;
                    $existing_review->comment = $user_review;
                    $existing_review->update();
                    return redirect()->back()->with('message', 'Cập nhật đánh giá thành công!');
                }
                else
                {
                    // Tạo đánh giá mới
                    Review::create([
                        'user_id' => Auth::id(),
                        'product_id' => $product_id,
                        'order_id' => $verified_purchase->id, // Lưu mã đơn hàng để chứng thực
                        'rating' => $new_rating,
                        'comment' => $user_review
                    ]);
                    return redirect()->back()->with('message', 'Cảm ơn bạn đã đánh giá sản phẩm!');
                }
            }
            else
            {
                return redirect()->back()->with('message', 'Bạn không thể đánh giá sản phẩm này vì chưa mua hoặc đơn hàng chưa hoàn thành!');
            }
        }
        else
        {
            return redirect()->back()->with('message', 'Không tìm thấy sản phẩm!');
        }
    }
}