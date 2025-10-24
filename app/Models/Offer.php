<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_type',
        'code',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount',
        'product_id',
        'usage_limit',
        'used_count',
        'starts_at',
        'expires_at',
        'active',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Check if the offer is active and usable
    public function isActive(): bool
    {
        $now = Carbon::now();
        return $this->active
            && (!$this->starts_at || $this->starts_at <= $now)
            && (!$this->expires_at || $this->expires_at >= $now)
            && (!$this->usage_limit || $this->used_count < $this->usage_limit);
    }

    // Calculate discount for a given amount
    public function calculateDiscount(float $amount): float
    {
        if (!$this->isActive() || $amount < $this->min_order_amount) {
            return 0;
        }

        $discount = $this->discount_type === 'fixed'
            ? $this->discount_value
            : ($amount * $this->discount_value / 100);

        return $this->max_discount ? min($discount, $this->max_discount) : $discount;
    }
}
