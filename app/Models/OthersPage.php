<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OthersPage extends Model
{
    protected $table = 'otherspages';

    protected $fillable = [
        'slug',
        'title',
        'content',
        'meta_title',
        'meta_description',
    ];
}
