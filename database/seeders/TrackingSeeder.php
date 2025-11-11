<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tracking;

class TrackingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tracking::create([
            'facebook_pixel_id' => '1445509416741744',
            'facebook_access_token' => 'EAAGzBnjYjP8BPa4SAQLwk4MtqUymRlIuQgcxbp9bxtlGwzOi1ue8ZCFaZCGgamwhcg8ajSiUOX1UAAk8JJAfHPDcgEGWGorlejvuOwP11jNTD4909UVVOQzeffYWMCxPIcpzi2g65PWRkeD3imdeINgg22ZCrQzyrTGBWjJZAlE5zELfsNZAqZA8FmiwX07JgZDZD',
            'facebook_test_event' => true,
            'facebook_test_event_code' => 'Buy',
            'google_tag_manager_id' => 'GTM-XXXXXXX',
            'google_analytics_id' => 'UA-XXXXXXXXX-X',
            'data_layer' => [
                'page' => 'home',
                'userType' => 'guest',
            ],
        ]);
    }
}
