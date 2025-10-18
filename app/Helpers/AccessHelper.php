<?php
// app/Helpers/AccessHelper.php
namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccessHelper
{
    public static function hasAccess(string $menuKey, string $actionName): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        // Find menu id by key (slug or route name or db column)
        $menu = DB::table('menus')->where('slug', $menuKey)->first();
        if (!$menu) return false;

        // Find action id
        $action = DB::table('actions')->where('name', $actionName)->first();
        if (!$action) return false;

        return DB::table('access_to_role')
            ->where('role_id', $user->role_id)
            ->where('menu_id', $menu->id)
            ->where('action_id', $action->id)
            ->exists();
    }
}
