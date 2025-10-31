<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_index',
        'extra_price',
        'quantity',
        'price',
        'subtotal',
    ];
    protected $casts = [
        'price' => 'float',
        'subtotal' => 'float',
        'quantity' => 'integer',
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Auto-update subtotal before saving
     */
    protected static function booted()
    {
        static::saving(function ($item) {
            $item->subtotal = $item->quantity * $item->price;
        });
    }
}
