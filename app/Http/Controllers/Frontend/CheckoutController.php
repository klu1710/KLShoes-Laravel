<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\PlaceOrderMailable;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Coupon;

class CheckoutController extends Controller
{
    // 1. HIỂN THỊ TRANG THANH TOÁN
    public function index(Request $request)
    {
        if ($request->query('mode') == 'buynow' && Session::has('buy_now_item')) {
            $data = Session::get('buy_now_item');
            $cartItem = new Cart();
            $cartItem->product_id = $data['product_id'];
            $cartItem->quantity = $data['quantity'];
            $cartItem->color = $data['color'];
            $cartItem->size = $data['size'];
            $cartItem->user_id = Auth::id(); 
            $cartItem->setRelation('product', Product::find($data['product_id']));
            $cartItems = collect([$cartItem]);
        } 
        else {
            $query = Cart::query();

            if(Auth::check()){
                $query->where('user_id', Auth::id());
            } else {
                $query->where('session_id', Session::getId());
            }

            if ($request->has('items')) {
                $selectedIds = explode(',', $request->query('items')); 
                $query->whereIn('product_id', $selectedIds); 
            }

            $cartItems = $query->get();
        }

        $today = Carbon::today();
        $coupons = Coupon::where('status', '0')
                        ->where('quantity', '>', 0)
                        ->where(function($q) use ($today) {
                            $q->whereDate('end_date', '>=', $today)->orWhereNull('end_date');
                        })->get();

        return view('frontend.checkout.index', compact('cartItems', 'coupons'));
    }

    // 2. XỬ LÝ ĐẶT HÀNG
    public function placeOrder(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
        ]);

        // Dùng Transaction khóa dữ liệu chống Race Condition
        DB::beginTransaction();

        try {
            if ($request->checkout_mode == 'buynow' && Session::has('buy_now_item')) {
                $data = Session::get('buy_now_item');
                $cartItem = new Cart();
                $cartItem->product_id = $data['product_id'];
                $cartItem->quantity = $data['quantity'];
                $cartItem->color = $data['color'];
                $cartItem->size = $data['size'];
                $cartItems = collect([$cartItem]);
            } else {
                $query = Cart::query();
                
                if(Auth::check()) $query->where('user_id', Auth::id());
                else $query->where('session_id', Session::getId());

                if ($request->has('selected_products') && !empty($request->selected_products)) {
                    $selectedIds = explode(',', $request->selected_products);
                    $query->whereIn('product_id', $selectedIds);
                }

                $cartItems = $query->get();
            }

            if(count($cartItems) == 0) throw new \Exception('Giỏ hàng trống hoặc lỗi dữ liệu!');

            $order = new Order();
            $order->user_id = Auth::check() ? Auth::id() : null;
            $order->tracking_no = 'KLS-' . Str::random(10);
            $order->fullname = $request->fullname;
            $order->email = $request->email;
            $order->phone = $request->phone;
            $order->address = $request->address;
            $order->note = $request->note;
            $order->status_message = 'in progress';
            $order->payment_mode = $request->payment_mode ?? 'COD'; 
            $order->payment_id = $request->payment_id;

            $totalAmount = 0;
            foreach($cartItems as $item){
                $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
                if(!$product || $product->quantity < $item->quantity) {
                    throw new \Exception("Sản phẩm {$product->name} đã hết hàng!");
                }
                $totalAmount += $product->getSellingPrice() * $item->quantity;
            }

            $discount = 0;
            $couponToDecrement = null;
            if(Session::has('coupon')){
                $code = Session::get('coupon')['code'];
                $coupon = Coupon::where('code', $code)->lockForUpdate()->first();
                if($coupon && $coupon->quantity > 0) {
                    $today = Carbon::now();
                    if (!$coupon->end_date || $today <= $coupon->end_date) {
                        $discount = Session::get('coupon')['discount_amount'] ?? 0;
                        $couponToDecrement = $coupon;
                    }
                } else {
                    Session::forget('coupon');
                }
            }

            if($discount > $totalAmount) $discount = $totalAmount;
            $grandTotal = $totalAmount - $discount;

            $order->discount_amount = $discount;
            $order->total_price = $grandTotal;
            $order->save();

            if($couponToDecrement) {
                $couponToDecrement->decrement('quantity');
                Session::forget('coupon');
            }

            foreach ($cartItems as $item) {
                $product = Product::where('id', $item->product_id)->first();
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $product->getSellingPrice(), 
                    'color' => $item->color,
                    'size' => $item->size,
                ]);

                if($item->size) {
                     $productSize = ProductSize::where('product_id', $item->product_id)
                                                ->where('size', $item->size)
                                                ->lockForUpdate()
                                                ->first();
                     if($productSize) $productSize->decrement('quantity', $item->quantity);
                }
                $product->decrement('quantity', $item->quantity);
            }

            DB::commit();

            // Gửi email hóa đơn cho Khách & CC bản sao cho Shop
            try {
                $orderMail = Order::with('orderItems.product')->findOrFail($order->id);
                Mail::to($orderMail->email)
                    ->cc('cskh.klshoes@gmail.com')
                    ->send(new PlaceOrderMailable($orderMail));
            } catch (\Exception $e) { }

            if ($request->checkout_mode == 'buynow') {
                Session::forget('buy_now_item');
            } else {
                $queryDelete = Cart::query();
                if(Auth::check()) $queryDelete->where('user_id', Auth::id());
                else $queryDelete->where('session_id', Session::getId());

                if ($request->has('selected_products') && !empty($request->selected_products)) {
                    $selectedIds = explode(',', $request->selected_products);
                    $queryDelete->whereIn('product_id', $selectedIds);
                }
                $queryDelete->delete();
            }
            
            Session::forget('coupon');

            if($request->payment_mode == 'VIETQR') return redirect('thank-you-qr/' . $order->id);

            if (Auth::check()) {
                return redirect('my-orders')->with('message', 'Đặt hàng thành công! Mã: ' . $order->tracking_no);
            } else {
                return redirect('track-order?tracking_no=' . $order->tracking_no . '&phone=' . $order->phone)->with('status', 'Đặt hàng thành công!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', $e->getMessage());
        }
    }

    public function checkCoupon(Request $request)
    {
        $coupon_code = $request->input('coupon_code');
        $totalPrice = $request->input('order_total');
        $totalPrice = str_replace('.', '', $totalPrice);
        $totalPrice = str_replace(',', '', $totalPrice);

        $coupon = Coupon::where('code', $coupon_code)->first();

        if (!$coupon) return response()->json(['status' => 'error', 'message' => 'Mã giảm giá không tồn tại!']);
        if ($coupon->status == '1') return response()->json(['status' => 'error', 'message' => 'Mã này đang bị khóa!']);
        if ($coupon->quantity <= 0) return response()->json(['status' => 'error', 'message' => 'Mã giảm giá đã hết lượt sử dụng!']);

        $today = Carbon::now();
        if ($coupon->end_date && $today > $coupon->end_date) return response()->json(['status' => 'error', 'message' => 'Mã giảm giá đã hết hạn!']);

        $discountAmount = 0;
        if ($coupon->type == '1' || $coupon->type == 'percent') {
            $discountAmount = ($totalPrice * $coupon->value) / 100;
        } else {
            $discountAmount = $coupon->value;
        }

        if($discountAmount > $totalPrice) $discountAmount = $totalPrice;
        $grandTotal = $totalPrice - $discountAmount;

        Session::put('coupon', [
            'code' => $coupon->code,
            'discount_amount' => $discountAmount
        ]);

        return response()->json([
            'status' => 'success',
            'discount_amount' => $discountAmount,
            'grand_total' => $grandTotal,
            'discount_amount_text' => number_format($discountAmount, 0, ',', '.') . 'đ',
            'final_total_text' => number_format($grandTotal, 0, ',', '.') . 'đ',
            'message' => 'Áp dụng mã giảm giá thành công!'
        ]);
    }

    public function viewQr($orderId) {
        $query = Order::where('id', $orderId);
        if (Auth::check()) $query->where('user_id', Auth::id());
        $order = $query->first();
        return $order ? view('frontend.checkout.pay_qr', compact('order')) : redirect('/')->with('message', 'Không tìm thấy đơn hàng!');
    }

    // 3. XÁC NHẬN THANH TOÁN QR (GỬI THÔNG BÁO CHO SHOP)
    public function confirmPayment($orderId) {
        $order = Order::find($orderId);
        if($order) {
            try { 
                
                Mail::to('cskh.klshoes@gmail.com')->send(new \App\Mail\AdminOrderNotification($order)); 
            } catch (\Exception $e) {}
            return redirect(Auth::check() ? 'my-orders' : 'track-order?tracking_no=' . $order->tracking_no . '&phone=' . $order->phone)->with('status', 'Đã gửi thông báo thanh toán!');
        }
        return redirect()->back()->with('status', 'Có lỗi xảy ra!');
    }
}