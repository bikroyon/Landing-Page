<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessToRole extends Model
{
    protected $table = 'access_to_role';
    protected $fillable = ['role_id', 'menu_id', 'action_id'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function action()
    {
        return $this->belongsTo(Action::class);
    }
}
