<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'type',
        'value',
        'quantity',
        'start_date',
        'end_date',
        'status'
    ];

    //  1. THÊM CÁI NÀY: Để Laravel tự hiểu start_date và end_date là ngày tháng
    // Giúp bạn so sánh ngày (hết hạn chưa?) dễ dàng hơn
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    //  2. THÊM CÁI NÀY: Kết nối với bảng CouponUsage
    // Để sau này mình đếm xem mã này đã được dùng bao nhiêu lần rồi
    public function usages()
    {
        return $this->hasMany(CouponUsage::class, 'coupon_id', 'id');
    }
}