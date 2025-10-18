<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessToRoleSeeder extends Seeder
{
    public function run(): void
    {
        // 🧹 Clear previous data to avoid duplicate key errors
        DB::table('access_to_role')->truncate();

        $menuIds = DB::table('menus')->pluck('id')->toArray();
        $actionIds = DB::table('actions')->pluck('id')->toArray();

        // 🌟 1️⃣ Admin role (ID = 1) - all menus, all actions
        foreach ($menuIds as $menuId) {
            foreach ($actionIds as $actionId) {
                DB::table('access_to_role')->insert([
                    'role_id' => 1,
                    'menu_id' => $menuId,
                    'action_id' => $actionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 🌟 2️⃣ Customer role (ID = 2) - only menus 14–18
        $customerMenuIds = [14, 15, 16, 17, 18];
        foreach ($customerMenuIds as $menuId) {
            foreach ($actionIds as $actionId) {
                DB::table('access_to_role')->insert([
                    'role_id' => 2,
                    'menu_id' => $menuId,
                    'action_id' => $actionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
