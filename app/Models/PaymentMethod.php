<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'provider',
        'account_number',
        'qr_code',
        'logo',
        'status',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
