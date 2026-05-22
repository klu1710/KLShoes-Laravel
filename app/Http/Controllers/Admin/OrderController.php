<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    // 1. Danh sách đơn hàng
    public function index(Request $request)
    {
        $orders = Order::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }
    
    // 2. Xem chi tiết đơn hàng
    public function show($orderId)
    {
        $order = Order::where('id', $orderId)->first();
        if($order){
            return view('admin.orders.show', compact('order'));
        }
        return redirect('admin/orders')->with('message', 'Không tìm thấy đơn hàng!');
    }

    // 3. Cập nhật trạng thái đơn hàng
    public function updateStatus(Request $request, $orderId)
    {
        $order = Order::where('id', $orderId)->first();
        if($order){
            $order->status_message = $request->order_status;
            $order->update();
            return redirect('admin/orders/'.$orderId)->with('message', 'Cập nhật trạng thái thành công!');
        }
        return redirect('admin/orders/'.$orderId)->with('message', 'Lỗi: Không tìm thấy đơn hàng!');
    }
}