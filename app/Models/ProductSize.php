<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;
    protected $table = 'product_sizes';
    protected $guarded = [];

    public function color() {
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }
}