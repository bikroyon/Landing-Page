<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'delivery_zone_id',
        'payment_method_id',
        'offer_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'transaction_method',
        'transaction_id',
        'transaction_number',
        'subtotal',
        'total_amount',
        'status',
        'note',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deliveryZone()
    {
        return $this->belongsTo(DeliveryZone::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function reviews()
{
    return $this->hasMany(Review::class);
}


    // Helper to calculate total from items
    public function calculateSubtotal(): float
    {
        return $this->items->sum(fn($item) => $item->subtotal);
    }

    // Calculate final total (subtotal + delivery - discount)
    public function calculateTotal(): float
    {
        $subtotal = $this->calculateSubtotal();
        $delivery_fee = $this->deliveryZone?->effectiveFee($subtotal) ?? 0;
        $discount = 0;

        if ($this->offer && $this->offer->isActive()) {
            $discount = $this->offer->discount_type === 'fixed'
                ? $this->offer->discount_value
                : ($subtotal * $this->offer->discount_value / 100);

            if ($this->offer->max_discount) {
                $discount = min($discount, $this->offer->max_discount);
            }
        }

        return max(0, $subtotal + $delivery_fee - $discount);
    }
}
