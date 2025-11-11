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
            ['id' => 2, 'parent_id' => null, 'name' => 'All Products', 'slug' => 'products', 'href' => '/products', 'icon' => 'fa-solid:users', 'sequance' => 2, 'status' => 1],
            ['id' => 3, 'parent_id' => null, 'name' => 'Add Product', 'slug' => 'add-product', 'href' => '/products/create', 'icon' => 'raphael:users', 'sequance' => 3, 'status' => 1],
            ['id' => 4, 'parent_id' => null, 'name' => 'Orders', 'slug' => 'orders', 'href' => '/orders', 'icon' => 'bx:food-menu', 'sequance' => 4, 'status' => 1],
            ['id' => 5, 'parent_id' => null, 'name' => 'Customers', 'slug' => 'customers', 'href' => '/customers', 'icon' => 'material-symbols:delivery-truck-speed', 'sequance' => 5, 'status' => 1],
            ['id' => 6, 'parent_id' => null, 'name' => 'Reviews', 'slug' => 'reviews', 'href' => '/reviews', 'icon' => 'ph:warehouse-fill', 'sequance' => 6, 'status' => 1],
            ['id' => 7, 'parent_id' => null, 'name' => 'Marketing', 'slug' => 'marketing', 'href' => '#', 'icon' => 'game-icons:warehouse', 'sequance' => 7, 'status' => 1],
            ['id' => 8, 'parent_id' => null, 'name' => 'Reports', 'slug' => 'reports', 'href' => '#', 'icon' => 'fluent:tag-multiple-16-filled', 'sequance' => 8, 'status' => 1],
            ['id' => 9, 'parent_id' => null, 'name' => 'Fraud Checker', 'slug' => 'fraud-checker', 'href' => '/fraud-checker', 'icon' => 'ri:database-fill', 'sequance' => 9, 'status' => 1],
            ['id' => 10, 'parent_id' => null, 'name' => 'Store Settings', 'slug' => 'store-settings', 'href' => '/store-settings', 'icon' => 'jam:computer-f', 'sequance' => 10, 'status' => 1],
            ['id' => 11, 'parent_id' => null, 'name' => 'supports', 'slug' => 'supports', 'href' => '/supports', 'icon' => 'streamline-ultimate:shopping-cart-full-bold', 'sequance' => 11, 'status' => 1],
            ['id' => 12, 'parent_id' => null, 'name' => 'OthersPages', 'slug' => 'pages', 'href' => '/pages', 'icon' => 'ic:baseline-library-books', 'sequance' => 12, 'status' => 1],
            ['id' => 13, 'parent_id' => null, 'name' => 'Roles Permission', 'slug' => 'roles-permission', 'href' => '/roles', 'icon' => 'mdi:shield-key', 'sequance' => 13, 'status' => 1],
            ['id' => 14, 'parent_id' => null, 'name' => 'Customizations', 'slug' => 'customizations', 'href' => '#', 'icon' => 'mdi:cart-arrow-right', 'sequance' => 14, 'status' => 1],

            //Customer Side
            ['id' => 15, 'parent_id' => null, 'name' => 'Make an Order', 'slug' => 'make-an-order', 'href' => '/', 'icon' => 'streamline-ultimate:shopping-cart-full-bold', 'sequance' => 15, 'status' => 1],
            ['id' => 16, 'parent_id' => null, 'name' => 'My Orders', 'slug' => 'my-order', 'href' => '/my-orders', 'icon' => 'streamline:shopping-cart-check-solid', 'sequance' => 16, 'status' => 1],
            ['id' => 17, 'parent_id' => null, 'name' => 'My Reviews', 'slug' => 'my-reviews', 'href' => '/my-reviews', 'icon' => 'fluent:vehicle-truck-checkmark-48-filled', 'sequance' => 17, 'status' => 1],
            ['id' => 18, 'parent_id' => null, 'name' => 'Track Orders', 'slug' => 'track-orders', 'href' => 'track-orders', 'icon' => 'bi:cart-x-fill', 'sequance' => 18, 'status' => 1],
            ['id' => 19, 'parent_id' => null, 'name' => 'Take Support', 'slug' => 'tickets', 'href' => '/tickets', 'icon' => 'mdi:cart-arrow-right', 'sequance' => 19, 'status' => 1],

            //SubMenu for Marketing
            ['id' => 20, 'parent_id' => 7, 'name' => 'Tackings', 'slug' => 'tracckings', 'href' => '/tracckings', 'icon' => 'hugeicons:coupon-02', 'sequance' => 20, 'status' => 1],
            ['id' => 21, 'parent_id' => 7, 'name' => 'Delivery Zones', 'slug' => 'delivery-zones', 'href' => '/delivery-zones', 'icon' => 'ic:baseline-delivery-dining', 'sequance' => 21, 'status' => 1],

            //SubMenu for Reports
            ['id' => 22, 'parent_id' => 8, 'name' => 'Sales Report', 'slug' => 'sales-report', 'href' => '/reports/sales', 'icon' => 'mdi:cart-arrow-right', 'sequance' => 22, 'status' => 1],
            ['id' => 23, 'parent_id' => 8, 'name' => 'Customer Report', 'slug' => 'customers-report', 'href' => '/reports/customer', 'icon' => 'mdi:cart-arrow-right', 'sequance' => 23, 'status' => 1],
            ['id' => 24, 'parent_id' => 8, 'name' => 'Products Report', 'slug' => 'products-report', 'href' => '/reports/products', 'icon' => 'mdi:cart-arrow-right', 'sequance' => 24, 'status' => 1],

            //Customizations Menu
            ['id' => 25, 'parent_id' => 14, 'name' => 'Carusole', 'slug' => 'carusole', 'href' => '/Carusole', 'icon' => 'mdi:cart-arrow-right', 'sequance' => 25, 'status' => 1],
            ['id' => 26, 'parent_id' => 14, 'name' => 'Banner', 'slug' => 'banner', 'href' => '/Carusole', 'icon' => 'mdi:cart-arrow-right', 'sequance' => 26, 'status' => 1],
            ['id' => 27, 'parent_id' => 14, 'name' => 'FAQ', 'slug' => 'faq', 'href' => '/faq', 'icon' => 'mdi:cart-arrow-right', 'sequance' => 27, 'status' => 1],

        ];

        foreach ($menus as &$menu) {
            $menu['created_at'] = $now;
            $menu['updated_at'] = $now;
        }

        DB::table('menus')->insert($menus);
    }
}
