<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;
    
    protected $table = 'carts';
    
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        
        'color', 
        'size', 
    ];

    /**
     * Mối quan hệ: 1 dòng trong giỏ hàng thuộc về 1 Sản phẩm
     * Giúp gọi: $cartItem->product->name
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}