<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now()->toDateTimeString();

        $menus = [
            //Sidebar Menus For Admin Only
            ['id' => 1, 'parent_id' => null, 'name' => 'Dashboard', 'slug' => 'dashboard', 'href' => '/dashboard', 'icon' => 'ic:round-space-dashboard', 'sequance' => 1, 'status' => 1],
            ['id' => 2, 'parent_id' => null, 'name' => 'All Products', 'slug' => 'products', 'href' => '/products', 'icon' => 'bxs:shopping-bags', 'sequance' => 2, 'status' => 1],
            ['id' => 3, 'parent_id' => null, 'name' => 'Add Product', 'slug' => 'add-product', 'href' => '/products/create', 'icon' => 'icon-park-solid:shopping-cart-add', 'sequance' => 3, 'status' => 1],
            ['id' => 4, 'parent_id' => null, 'name' => 'Orders', 'slug' => 'orders', 'href' => '/orders', 'icon' => 'bx:food-menu', 'sequance' => 4, 'status' => 1],
            ['id' => 5, 'parent_id' => null, 'name' => 'Customers', 'slug' => 'customers', 'href' => '/customers', 'icon' => 'fa7-solid:users', 'sequance' => 5, 'status' => 1],
            ['id' => 6, 'parent_id' => null, 'name' => 'Reviews', 'slug' => 'reviews', 'href' => '/reviews', 'icon' => 'material-symbols:reviews', 'sequance' => 6, 'status' => 1],
            ['id' => 7, 'parent_id' => null, 'name' => 'Delivery Zones', 'slug' => 'delivery-zones', 'href' => '/delivery-zones', 'icon' => 'iconamoon:delivery-fast-fill', 'sequance' => 7, 'status' => 1],
            ['id' => 8, 'parent_id' => null, 'name' => 'Sales Report', 'slug' => 'sales-report', 'href' => 'sales-report', 'icon' => 'mdi:report-box', 'sequance' => 8, 'status' => 1],
            ['id' => 9, 'parent_id' => null, 'name' => 'Fraud Checker', 'slug' => 'fraud-checker', 'href' => '/fraud-checker', 'icon' => 'pepicons-pencil:shield-checkered-circle-filled', 'sequance' => 9, 'status' => 1],
            ['id' => 10, 'parent_id' => null, 'name' => 'supports', 'slug' => 'supports', 'href' => '/supports', 'icon' => 'streamline-ultimate:headphones-customer-support-question-bold', 'sequance' => 10, 'status' => 1],
            ['id' => 11, 'parent_id' => null, 'name' => 'OthersPages', 'slug' => 'pages', 'href' => '/pages', 'icon' => 'material-symbols:web-stories', 'sequance' => 11, 'status' => 1],
            ['id' => 12, 'parent_id' => null, 'name' => 'Roles Permission', 'slug' => 'roles-permission', 'href' => '/roles', 'icon' => 'mdi:shield-key', 'sequance' => 12, 'status' => 1],
            ['id' => 13, 'parent_id' => null, 'name' => 'Customizations', 'slug' => 'customizations', 'href' => 'customizations', 'icon' => 'mingcute:switch-fill', 'sequance' => 13, 'status' => 1],
            ['id' => 14, 'parent_id' => null, 'name' => 'Store Settings', 'slug' => 'store-settings', 'href' => '/store-settings', 'icon' => 'material-symbols:settings-rounded', 'sequance' => 14, 'status' => 1],

            //Customer Side
            ['id' => 15, 'parent_id' => null, 'name' => 'Make an Order', 'slug' => 'make-an-order', 'href' => '/', 'icon' => 'streamline-ultimate:shopping-cart-full-bold', 'sequance' => 15, 'status' => 1],
            ['id' => 16, 'parent_id' => null, 'name' => 'My Orders', 'slug' => 'my-order', 'href' => '/my-orders', 'icon' => 'streamline:shopping-cart-check-solid', 'sequance' => 16, 'status' => 1],
            ['id' => 17, 'parent_id' => null, 'name' => 'My Reviews', 'slug' => 'my-reviews', 'href' => '/my-reviews', 'icon' => 'material-symbols:reviews', 'sequance' => 17, 'status' => 1],
            ['id' => 18, 'parent_id' => null, 'name' => 'Track Orders', 'slug' => 'track-orders', 'href' => 'track-orders', 'icon' => 'si:pin-alt-1-fill', 'sequance' => 18, 'status' => 1],
            ['id' => 19, 'parent_id' => null, 'name' => 'Take Support', 'slug' => 'tickets', 'href' => '/tickets', 'icon' => 'ic:sharp-contact-support', 'sequance' => 19, 'status' => 1],

        ];

        foreach ($menus as &$menu) {
            $menu['created_at'] = $now;
            $menu['updated_at'] = $now;
        }

        DB::table('menus')->insert($menus);
    }
}
