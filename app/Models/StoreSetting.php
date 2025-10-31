<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $fillable = [
        'store_name',
        'store_tagline',
        'store_logo',
        'store_favicon',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'postal_code',
        'currency',
        'currency_symbol',
        'maintenance_mode',
        'facebook_url',
        'instagram_url',
        'tiktok_url',
        'twitter_url',
        'youtube_url',
        'cod',
        'bkash',
        'nagad',
        'rocket',
        'meta_title',
        'meta_description',
        'products_title',
        'customer_info_title',
        'customer_info_label',
        'customer_info_name_label',
        'customer_info_phone_label',
        'customer_info_email_label',
        'customer_info_address_label',
        'delivery_zone_title',
        'additional_note_title',
        'order_summary_title',
        'payment_title',
        'submit_button',
    ];

    public static function current(): self
    {
        return static::first() ?? static::create();
    }
    // Relations to payment methods
    public function codMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'cod');
    }

    public function bkashMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'bkash');
    }

    public function nagadMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'nagad');
    }

    public function rocketMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'rocket');
    }
}
