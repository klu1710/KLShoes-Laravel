<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    use HasFactory;

    //  Đã sửa thành 'colors' để khớp với database của bạn
    protected $table = 'colors'; 

    protected $fillable = [
        'product_id',
        'name',
        'code',
        'quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}