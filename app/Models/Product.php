<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'status', 'product_type', 'variations', 'downloads', 'service', 'image'
    ];

    protected $casts = [
        'variations' => 'array',
        'downloads' => 'array',
        'service' => 'array',
        'status' => 'boolean',
        'price' => 'decimal:2',
    ];

    // Relationship: Product has many OrderItems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


// Optional: average rating helper
public function averageRating()
{
    return $this->reviews()->avg('rating');
}

}
