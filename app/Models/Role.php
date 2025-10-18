<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'status'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function accessControls()
    {
        return $this->hasMany(AccessToRole::class);
    }
    public function hasAccess($menuId, $actionId)
    {
        return $this->accessControls()
            ->where('menu_id', $menuId)
            ->where('action_id', $actionId)
            ->exists();
    }
}
