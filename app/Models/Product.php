<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; 

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $guarded = [];

    // Ép kiểu dữ liệu ngày tháng
    protected $casts = [
        'sale_start' => 'datetime',
        'sale_end' => 'datetime',
    ];

    public function productImages() {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function productSizes() {
        return $this->hasMany(ProductSize::class, 'product_id', 'id');
    }

    // Quan hệ Màu sắc (Laravel sẽ tự tìm bảng 'colors' thông qua Model ProductColor)
    public function productColors() {
        return $this->hasMany(ProductColor::class, 'product_id', 'id');
    }

    public function reviews() {
        return $this->hasMany(Review::class, 'product_id', 'id');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function brand() {
       return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    // Kiểm tra có đang giảm giá không
    public function hasDiscount()
    {
        if ($this->discount_percent > 0 && $this->sale_start && $this->sale_end) {
            $now = Carbon::now();
            if ($now->between($this->sale_start, $this->sale_end)) {
                return true;
            }
        }
        return false;
    }

    // Hàm lấy giá bán cuối cùng (Giá gốc hoặc Giá khuyến mãi)
    public function getSellingPrice()
    {
        if ($this->hasDiscount()) {
            return $this->selling_price - ($this->selling_price * $this->discount_percent / 100);
        }
        return $this->selling_price;
    }
}