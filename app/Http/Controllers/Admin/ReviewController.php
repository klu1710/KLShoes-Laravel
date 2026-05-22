<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    // 1. Xem danh sách đánh giá
    public function index()
    {
        // Lấy đánh giá mới nhất, kèm thông tin User và Product
        $reviews = Review::with(['user', 'product'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    // 2. Xóa đánh giá (nếu là spam)
    public function destroy($review_id)
    {
        $review = Review::findOrFail($review_id);
        $review->delete();
        return redirect()->back()->with('message', 'Đã xóa đánh giá thành công!');
    }
}