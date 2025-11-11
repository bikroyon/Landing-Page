<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OthersPageSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now()->toDateTimeString();

        $pages = [
            [
                'slug' => '',
                'title' => 'Privacy Policy',
                'meta_title' => 'Privacy Policy - Your Data Protection',
                'meta_description' => 'Learn how we collect, store and protect your personal information.',
                'content' => '<h2>Privacy Policy</h2><p>This is the privacy policy page. You can update the content later.</p>',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'terms-and-conditions',
                'title' => 'Terms & Conditions',
                'meta_title' => 'Terms & Conditions',
                'meta_description' => 'Read our terms and conditions regarding using our services.',
                'content' => '<h2>Terms & Conditions</h2><p>This page contains the terms and conditions for using our platform.</p>',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'return-and-refund-policy',
                'title' => 'Return & Refund Policy',
                'meta_title' => 'Return & Refund Policy',
                'meta_description' => 'Understand our return, replacement, and refund guidelines.',
                'content' => '<h2>Return & Refund Policy</h2><p>This page explains our return and refund process.</p>',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('otherspages')->insert($pages);
    }
}
