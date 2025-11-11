<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Menu;
use App\Models\Action;
use App\Models\AccessToRole;
use Illuminate\Http\Request;

class RoleAccessController extends Controller
{
    public function edit($roleId)
    {
        $role = Role::findOrFail($roleId);
        $menus = Menu::with('children')->whereNull('parent_id')->get();
        $actions = Action::all();

        $access = AccessToRole::where('role_id', $roleId)->get();

        $accessMap = $access->mapToGroups(function ($item) {
            return [ $item->menu_id => $item->action_id ];
        });

        return inertia('roles/permissions', [
            'role' => $role,
            'menus' => $menus,
            'actions' => $actions,
            'accessMap' => $accessMap,
        ]);
    }

public function update(Request $request, $roleId)
{
    $request->validate([
        'permissions' => 'array'
    ]);

    // Clear old permissions
    AccessToRole::where('role_id', $roleId)->delete();

    // Insert new ones
    $data = [];
    foreach ($request->permissions as $menuId => $actionIds) {
        foreach ($actionIds as $actionId) {
            $data[] = [
                'role_id' => $roleId,
                'menu_id' => $menuId,
                'action_id' => $actionId,
            ];
        }
    }

    if (!empty($data)) {
        AccessToRole::insert($data);
    }

    return back()->with('success', 'Permissions updated.');
}

}
