<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'region',
        'area',
        'delivery_fee',
        'free_delivery_min_order',
        'status',
    ];

    // Relationships
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Business logic
    public function isFreeForOrder(float $orderTotal): bool
    {
        return $this->free_delivery_min_order &&
               $orderTotal >= $this->free_delivery_min_order;
    }

    public function effectiveFee(float $orderTotal): float
    {
        return $this->isFreeForOrder($orderTotal) ? 0 : $this->delivery_fee;
    }
}