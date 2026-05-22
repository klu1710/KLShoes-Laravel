<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    // 1. Xem danh sách mã
    public function index()
    {
        $coupons = Coupon::orderBy('id', 'DESC')->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    // 2. Mở trang thêm mới
    public function create()
    {
        return view('admin.coupons.create');
    }

    // 3. Lưu mã giảm giá mới
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'code' => 'required|unique:coupons|string|size:4', // 👈 QUAN TRỌNG: Bắt buộc đúng 4 ký tự
            'type' => 'required',
            'value' => 'required|numeric',
            'quantity' => 'required|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date', // Ngày kết thúc phải sau ngày bắt đầu
        ], [
            'code.required' => 'Vui lòng nhập mã giảm giá',
            'code.unique' => 'Mã này đã tồn tại, vui lòng chọn mã khác',
            'code.size' => 'Mã giảm giá bắt buộc phải có đúng 4 ký tự (VD: SALE, TET1)!',
            'value.required' => 'Vui lòng nhập giá trị giảm',
            'end_date.after' => 'Ngày kết thúc phải diễn ra sau ngày bắt đầu',
        ]);

        $coupon = new Coupon();
        $coupon->code = strtoupper($request->input('code')); // Tự động viết hoa (vd: sale -> SALE)
        $coupon->type = $request->input('type'); // 1: Phần trăm, 2: Tiền mặt
        $coupon->value = $request->input('value');
        $coupon->quantity = $request->input('quantity');
        
        $coupon->start_date = $request->input('start_date');
        $coupon->end_date = $request->input('end_date');

        // Status: Check vào là ẩn (1), không check là hiện (0)
        $coupon->status = $request->input('status') == true ? '1' : '0';
        
        $coupon->save();

        return redirect('admin/coupons')->with('message', 'Thêm mã giảm giá thành công!');
    }

    // 4. Mở trang sửa
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    // 5. Cập nhật mã
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code' => 'required|string|size:4',
            'value' => 'required|numeric',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $coupon->code = strtoupper($request->input('code'));
        $coupon->type = $request->input('type');
        $coupon->value = $request->input('value');
        $coupon->quantity = $request->input('quantity');
        $coupon->start_date = $request->input('start_date');
        $coupon->end_date = $request->input('end_date');
        $coupon->status = $request->input('status') == true ? '1' : '0';
        
        $coupon->update();

        return redirect('admin/coupons')->with('message', 'Cập nhật mã thành công!');
    }

    // 6. Xóa mã
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return redirect('admin/coupons')->with('message', 'Đã xóa mã giảm giá!');
    }
}