<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class MenuService
{
    public function getMenusForRole(int $roleId)
    {
        // Get all access records for this role
        $access = DB::table('access_to_role')
            ->where('role_id', $roleId)
            ->get();

        // Map menu_id => [action_ids]
        $menuActions = $access->groupBy('menu_id')->map(function ($rows) {
            return $rows->pluck('action_id')->unique()->values();
        });

        // Get all menu IDs
        $menuIds = $menuActions->keys()->toArray();

        // Load parent menus with filtered children
        $menus = Menu::with(['children' => function ($query) use ($menuIds) {
            $query->whereIn('id', $menuIds)
                ->orderBy('sequance')
                ->with(['children' => function ($query) use ($menuIds) {
                    $query->whereIn('id', $menuIds)
                        ->orderBy('sequance');
                }]);
        }])
            ->whereIn('id', $menuIds)
            ->whereNull('parent_id')
            ->orderBy('sequance')
            ->get(['id', 'name', 'slug', 'icon', 'href', 'parent_id', 'sequance']);


        // Attach actions (with names) to each menu
        $actionList = DB::table('actions')->pluck('name', 'id'); // [id => name]

        $menus->each(function ($menu) use ($menuActions, $actionList) {
            $this->attachActions($menu, $menuActions, $actionList);
        });

        return $menus->filter(fn($menu) => $menu->can_view)->values();
    }

    private function attachActions($menu, $menuActions, $actionList)
    {
        // Actions assigned to current menu
        $menu->actions = $menuActions->has($menu->id)
            ? $menuActions[$menu->id]->map(fn($id) => $actionList[$id])->toArray()
            : [];

        // ✅ Add flag for "view" access
        $menu->can_view = in_array('view', $menu->actions);

        // Recurse for children
        if ($menu->children) {
            $menu->children->each(function ($child) use ($menuActions, $actionList) {
                $this->attachActions($child, $menuActions, $actionList);
            });

            // ✅ If any child has view access, parent should also show
            $menu->can_view = $menu->can_view || $menu->children->contains(fn($c) => $c->can_view);
        }
    }
}
