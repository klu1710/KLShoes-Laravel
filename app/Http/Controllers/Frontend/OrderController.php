<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\ProductSize; 
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCancelledMail;

class OrderController extends Controller
{
    // 1. Danh sách đơn hàng
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('frontend.orders.index', compact('orders'));
    }

    // 2. Chi tiết đơn hàng
    public function show($orderId)
    {
        $order = Order::where('user_id', Auth::id())->where('id', $orderId)->first();
        if($order){
            return view('frontend.orders.view', compact('order'));
        }else{
            return redirect()->back()->with('message', 'Không tìm thấy đơn hàng!');
        }
    }

    // 3. Khách hàng HỦY ĐƠN (FULL TÍNH NĂNG)
    public function cancelOrder($orderId)
    {
        $order = Order::where('user_id', Auth::id())->where('id', $orderId)->first();

        if($order)
        {
            if($order->status_message == 'in progress') 
            {
                // A. Đổi trạng thái đơn
                $order->status_message = 'cancelled';
                $order->save();

                // B. HOÀN LẠI KHO (RESTOCK)
                foreach ($order->orderItems as $item) 
                {
                    // 1. Cộng lại số lượng TỔNG (bảng products)
                    if($item->product){
                        $item->product()->increment('quantity', $item->quantity);
                    }

                    // 2. Cộng lại số lượng SIZE (bảng product_sizes) - QUAN TRỌNG
                    if($item->size) {
                        // Tìm đúng dòng có Product_ID và Size tương ứng
                        $sizeStock = ProductSize::where('product_id', $item->product_id)
                                                ->where('size', $item->size)
                                                ->first();
                        
                        // Nếu tìm thấy thì cộng lại số lượng
                        if($sizeStock){
                            $sizeStock->increment('quantity', $item->quantity);
                        }
                    }
                }

                // C. GỬI MAIL CHO ADMIN
                try {
                    // Mail admin của bạn
                    Mail::to('cskh.klshoes@gmail.com')->send(new OrderCancelledMail($order));
                } catch (\Exception $e) {
                    // Lỗi mail bỏ qua
                }

                return redirect()->back()->with('message', 'Đơn hàng đã được hủy thành công!');
            } 
            else 
            {
                return redirect()->back()->with('message', 'Đơn hàng đang vận chuyển, không thể hủy!');
            }
        }
        else
        {
            return redirect()->back()->with('message', 'Không tìm thấy đơn hàng!');
        }
    }
}