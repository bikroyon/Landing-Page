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
        'order_number',
        'delivery_zone_id',
        'payment_method_id',

        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',

        'additional_note',

        'transaction_method',
        'transaction_id',
        'transaction_mobile_number',

        'delivery_fee',
        'subtotal',
        'total_discount',
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

        // Delivery fee based on delivery zone
        $delivery_fee = $this->deliveryZone?->effectiveFee($subtotal) ?? 0;

        return max(0, $subtotal + $delivery_fee);
    }
    public static function generateOrderNumber()
    {
        do {
            $number = rand(100000, 999999);
        } while (self::where('order_number', $number)->exists());

        return $number;
    }
}
