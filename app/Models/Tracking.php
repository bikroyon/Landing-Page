<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'ip',
        'user_agent',
        'fb_pixel_id',
        'google_analytics_id',
        'event_name',
        'event_data',
    ];

    protected $casts = [
        'event_data' => 'array',
    ];
}
